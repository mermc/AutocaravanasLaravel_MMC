<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Caravana;
use App\Models\Historial;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Mail\ReservaConfirmada;
use App\Mail\ReservaActualizada;
use App\Mail\ReservaEliminada;
use Illuminate\Support\Facades\Mail;

class ReservaController extends Controller
{
    // 1. Ver reservas futuras del usuario
    public function index()
    {
        $reservas = Reserva::where('user_id', Auth::id())
            ->where('fecha_fin', '>=', today())
            ->orderBy('fecha_inicio', 'asc')
            ->with('caravana')
            ->get();

        return response()->json($reservas);
    }

    //2. Enseñar una reserva concreta

    public function show($id)
    {
        $user = auth()->user();
        $reserva = Reserva::with('caravana')->find($id);

        if (is_null($reserva) || $reserva->user_id !== $user->id) {
            return $this->sendError('Reserva no encontrada o no autorizada.');
        }

        return response()->json($reserva);

    }
    
    // 3. Crear nueva reserva (sin pago por Stripe)
    public function store(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'caravana_id' => 'required|exists:caravanas,id',
            'fecha_inicio' => 'required|date|after_or_equal:today',
            'fecha_fin' => 'required|date|after:fecha_inicio',
        ]);

        $errores = $this->validarReglasNegocio($user, $data['caravana_id'], $data['fecha_inicio'], $data['fecha_fin']);

        if ($errores) {
            return response()->json(['errors' => $errores], 422);
        }

        $inicio = Carbon::parse($data['fecha_inicio']);
        $fin = Carbon::parse($data['fecha_fin']);
        $duracion = $inicio->diffInDays($fin);
        $caravana = Caravana::find($data['caravana_id']);

        $precio_total = $caravana->precio_dia * $duracion;
        $precio_pagado = round($precio_total * 0.2, 2); 

        $reserva = Reserva::create([
            'user_id' => $user->id,
            'caravana_id' => $caravana->id,
            'fecha_inicio' => $inicio,
            'fecha_fin' => $fin,
            'precio_total' => $precio_total,
            'precio_pagado' => $precio_pagado,
        ]);

        Mail::to($user->email)->send(new ReservaConfirmada($reserva));
        return response()->json(['reserva' => $reserva], 201);

        
    }

    // 4. Mostrar caravanas disponibles para fechas
    public function consultarDisponibles(Request $request)
    {
        $data = $request->validate([
            'fecha_inicio' => 'required|date|after_or_equal:today',
            'fecha_fin' => 'required|date|after:fecha_inicio',
        ]);

        $inicio = Carbon::parse($data['fecha_inicio']);
        $fin = Carbon::parse($data['fecha_fin']);
        $duracion = $inicio->diffInDays($fin);

        $errores = $this->validarReglasNegocio(Auth::user(), null, $inicio, $fin, null, true); // true para solo validar globales
        if ($errores) {
            return response()->json(['errors' => $errores], 422);
        }

        $caravanasDisponibles = Caravana::whereDoesntHave('reservas', function ($query) use ($inicio, $fin) {
            $query->where('fecha_inicio', '<', $fin)
                  ->where('fecha_fin', '>', $inicio);
        })->get();

        return response()->json(['disponibles' => $caravanasDisponibles]);

        
    }

    // 5. Actualizar reserva
    public function update(Request $request, Reserva $reserva)
    {
        $this->authorize('update', $reserva);
        $user = Auth::user();

        // Nueva lógica para bloquear modificación si quedan menos de 7 días
    $hoy = Carbon::today();
    $diasHastaInicio = $hoy->diffInDays(Carbon::parse($reserva->fecha_inicio), false);
    if ($diasHastaInicio < 7) {
        return response()->json([
            'error' => 'Quedan menos de 7 días para que empiece tu reserva, no se puede modificar.'
        ], 403);
    }

        $data = $request->validate([
            'caravana_id' => 'required|exists:caravanas,id',
            'fecha_inicio' => 'required|date|after_or_equal:today',
            'fecha_fin' => 'required|date|after:fecha_inicio',
        ]);

        $errores = $this->validarReglasNegocio($user, $data['caravana_id'], $data['fecha_inicio'], $data['fecha_fin'], $reserva->id);
        if ($errores) {
            return response()->json(['errors' => $errores], 422);
        }

        $inicio = Carbon::parse($data['fecha_inicio']);
        $fin = Carbon::parse($data['fecha_fin']);
        $duracion = $inicio->diffInDays($fin);
        $caravana = Caravana::find($data['caravana_id']);

        $reserva->update([
            'caravana_id' => $caravana->id,
            'fecha_inicio' => $inicio,
            'fecha_fin' => $fin,
            'precio_total' => $caravana->precio_dia * $duracion,
            'precio_pagado' => round($caravana->precio_dia * $duracion * 0.2, 2),
        ]);

        Mail::to($user->email)->send(new ReservaActualizada($reserva));

        return response()->json(['reserva' => $reserva]);

        
    }


 // 6. Eliminar reserva
   public function destroy(Reserva $reserva)
{
    $this->authorize('delete', $reserva);

    $hoy = Carbon::today();
    $fechaInicio = Carbon::parse($reserva->fecha_inicio);

    // No permitir borrar reservas ya empezadas
    if ($hoy->gte($fechaInicio)) {
        return response()->json([
            'success' => false,
            'message' => 'No se puede cancelar una reserva que ya ha comenzado o finalizado.'
        ], 403);
    }

    $diasHastaInicio = $hoy->diffInDays($fechaInicio, false);
    $mensaje = 'Reserva eliminada correctamente.';

    if ($diasHastaInicio < 15) {
        $mensaje = 'Reserva cancelada con menos de 15 días. Se hará el cobro total de la misma.';
    }

    $user = $reserva->user; //relación definida
    $reserva->delete();

    if ($user) {
        Mail::to($user->email)->send(new ReservaEliminada($reserva));
    }

    return response()->json([
        'success' => true,
        'message' => $mensaje
    ]);
}

    // 7. Ver historial (admin) aunque la app no contempla el  uso de admin pero para un posible futuro 
    public function historial()
    {
        $this->authorize('viewAny', Historial::class);
        $historial = Historial::orderBy('fecha_inicio', 'desc')->get();
        return response()->json($historial);
    }

    // 8. Ver todas (admin)
    public function todas()
    {
        $this->authorize('viewAny', Reserva::class);
        $reservas = Reserva::with(['user', 'caravana'])->orderBy('fecha_inicio', 'asc')->get();
        return response()->json($reservas);
    }

    // Reglas de negocio: como antes, reusadas
    private function validarReglasNegocio($user, $caravana_id, $fecha_inicio, $fecha_fin, $excluir_reserva_id = null, $soloGenerales = false)
    {
        $errores = [];
        $inicio = Carbon::parse($fecha_inicio);
        $fin = Carbon::parse($fecha_fin);
        $duracion = $inicio->diffInDays($fin);

        if (($inicio->month === 7 || $inicio->month === 8) && $duracion < 7) {
            $errores[] = 'En julio y agosto, mínimo 7 días.';
        } elseif ($duracion < 2) {
            $errores[] = 'Mínimo 2 días.';
        }

        if (now()->addDays(60)->lt($inicio)) {
            $errores[] = 'Máximo 60 días de antelación.';
        }

        $reservasActivas = Reserva::where('user_id', $user->id)
            ->where('fecha_fin', '>=', today())
            ->when($excluir_reserva_id, function ($q) use ($excluir_reserva_id) {
                $q->where('id', '!=', $excluir_reserva_id);
            })
            ->count();

        if ($reservasActivas >= 5) {
            $errores[] = 'Máximo 5 reservas activas.';
        }

        if (!$soloGenerales && $caravana_id) {
            $ocupado = Reserva::where('caravana_id', $caravana_id)
                ->when($excluir_reserva_id, function ($q) use ($excluir_reserva_id) {
                    $q->where('id', '!=', $excluir_reserva_id);
                })
                ->where('fecha_inicio', '<', $fin)
                ->where('fecha_fin', '>', $inicio)
                ->exists();

            if ($ocupado) {
                $errores[] = 'La caravana no está disponible para esas fechas.';
            }
        }

        return $errores;
    }
}
