<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Historial;
use App\Models\Caravana;
use App\Mail\ReservaConfirmada;
use App\Mail\ReservaActualizada;
use App\Mail\ReservaEliminada;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class ReservaController extends Controller
{

    // 1. Ver reservas futuras del usuario
    public function index()
    {
        $reservas = Reserva::where('user_id', Auth::id())
        //con Auth es para autorizar admin y el usuario
            ->where('fecha_fin', '>=', today())
            ->orderBy('fecha_inicio', 'asc')
            ->get();

        return view('reservas.index', compact('reservas'));
    }

   // 2. Mostrar formulario para nueva reserva


    public function create(Request $request)
{
    // Si vienen los parámetros, carga solo la caravana seleccionada
    $caravana_id = $request->input('caravana_id');
    $fecha_inicio = $request->input('fecha_inicio');
    $fecha_fin = $request->input('fecha_fin');

    if ($caravana_id && $fecha_inicio && $fecha_fin) {
        $caravana = \App\Models\Caravana::findOrFail($caravana_id);

        $inicio = Carbon::parse($fecha_inicio);
            $fin = Carbon::parse($fecha_fin);
            $duracion = $inicio->diffInDays($fin);

            $precio_total = $caravana->precio_dia * $duracion;
            $pagoReserva = round($precio_total * 0.2, 2);


        // Solo pasas la caravana seleccionada
        return view('reservas.create', [
            'caravanas' => collect([$caravana]), // Para mantener el select, pero solo con una opción
            'selected_caravana_id' => $caravana_id,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
            'precio_total' => $precio_total,
            'precio_pagado' => $pagoReserva,
        ]);
    } else {
        // Si entra sin parámetros, muestra todas las caravanas (por si lo quiere usar el admin)
        $caravanas = \App\Models\Caravana::all();
        return view('reservas.create', [
            'caravanas' => $caravanas,
            'selected_caravana_id' => null,
            'fecha_inicio' => null,
            'fecha_fin' => null,
            'precio_total' => null,
            'precio_pagado' => null,
        ]);
    }
}

/**
 * Valida reglas de negocio de reservas.
 * Devuelve un array de errores si hay alguna violación de reglas.
 */
private function validarReglasNegocio($user, $caravana_id, $fecha_inicio, $fecha_fin, $excluir_reserva_id = null)
{
    $errores = [];
    $inicio = \Carbon\Carbon::parse($fecha_inicio);
    $fin = \Carbon\Carbon::parse($fecha_fin);
    $duracion = $inicio->diffInDays($fin);

    // 1. Reglas de duración
    if (($inicio->month === 7 || $inicio->month === 8) && $duracion < 7) {
        $errores[] = 'En julio y agosto, la reserva mínima es de 7 días.';
    } elseif ($duracion < 2) {
        $errores[] = 'La duración mínima para una reserva es de 2 días.';
    }

    // 2. Antelación máxima
    if (now()->addDays(60)->lt($inicio)) {
        $errores[] = 'Se puede reservar con 60 días de antelación como máximo.';
    }

    // 3. Máximo de reservas activas por usuario
    $reservasActivas = \App\Models\Reserva::where('user_id', $user->id)
        ->where('fecha_fin', '>=', today())
        ->when($excluir_reserva_id, function($q) use ($excluir_reserva_id) {
            $q->where('id', '!=', $excluir_reserva_id);
        })
        ->count();
    if ($reservasActivas >= 5) {
        $errores[] = 'Solo puedes tener 5 reservas activas.';
    }

    // 4. Disponibilidad del vehículo
    $ocupado = \App\Models\Reserva::where('caravana_id', $caravana_id)
        ->when($excluir_reserva_id, function($q) use ($excluir_reserva_id) {
            $q->where('id', '!=', $excluir_reserva_id);
        })
        ->where('fecha_inicio', '<', $fin)
        ->where('fecha_fin', '>', $inicio)
        ->exists();
    if ($ocupado) {
        $errores[] = 'Lo sentimos, esta caravana no está disponible para esas fechas.';
    }

    return $errores;
}

    // 3. Guardar nueva reserva desde panel Solo ADMIN
    public function store(Request $request)
    {
        // Solo permite a admin acceder a este método
        if (!Auth::user() || !Auth::user()->is_admin) {
            abort(403, 'Solo el administrador puede crear reservas directamente.');
        }

        $user = Auth::user();
        $data = $request->validate([
            'caravana_id' => 'required|exists:caravanas,id',
            'fecha_inicio' => 'required|date|after_or_equal:today',
            'fecha_fin' => 'required|date|after:fecha_inicio',
        ]);

        $errores = $this->validarReglasNegocio($user, $data['caravana_id'], $data['fecha_inicio'], $data['fecha_fin']);
    if (count($errores)) {
        return back()->withErrors(['msg' => implode(' ', $errores)])->withInput();
    }

        $inicio = Carbon::parse($data['fecha_inicio']);
        $fin = Carbon::parse($data['fecha_fin']);
        $duracion = $inicio->diffInDays($fin);   

    // Calcular importe total según el precio de la caravana y fianza
        $caravana = Caravana::find($data['caravana_id']);
        $precio_total = $caravana->precio_dia * $duracion;
        $precio_pagado = round($precio_total * 0.2, 2); // 20% del precio total

        $reserva = Reserva::create([
            'user_id' => $user->id,
            'caravana_id' => $data['caravana_id'],
            'fecha_inicio' => $inicio,
            'fecha_fin' => $fin,
            'precio_total' => $precio_total,
            'precio_pagado' => $precio_pagado,
        ]);

        
        Mail::to($reserva->user->email)->send(new ReservaConfirmada($reserva));

        
        return redirect()->route('dashboard')->with('success', 'Reserva realizada correctamente.');
    }


//Método para reservar y pagar con Stripe lo llamamos prepararPago 
    public function prepararPago(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'caravana_id' => 'required|exists:caravanas,id',
            'fecha_inicio' => 'required|date|after_or_equal:today',
            'fecha_fin' => 'required|date|after:fecha_inicio',
        ]);

        $errores = $this->validarReglasNegocio($user, $data['caravana_id'], $data['fecha_inicio'], $data['fecha_fin']);
    if (count($errores)) {
        return back()->withErrors(['msg' => implode(' ', $errores)])->withInput();
    }

        $inicio = Carbon::parse($data['fecha_inicio']);
        $fin = Carbon::parse($data['fecha_fin']);
        $duracion = $inicio->diffInDays($fin);
        
        $caravana = Caravana::find($data['caravana_id']);
        $precio_total = $caravana->precio_dia * $duracion;
        $pagoReserva = round($precio_total * 0.2, 2);

        // Crear sesión Stripe
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Reserva caravana: ' . ($caravana->nombre ?? 'Caravana ' . $caravana->id),
                    ],
                    'unit_amount' => $pagoReserva * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('reservas.success') .
                '?caravana_id=' . $caravana->id .
                '&fecha_inicio=' . $inicio->toDateString() .
                '&fecha_fin=' . $fin->toDateString() .
                '&precio_total=' . $precio_total .
                '&precio_pagado=' . $pagoReserva,
            'cancel_url' => route('reservas.cancel'),
            'customer_email' => $user->email,
        ]);

        return redirect($session->url);
    }

    // Tras pago correcto, crear reserva y enviar email
    public function checkoutSuccess(Request $request)
    {
        $user = Auth::user();

        $caravana_id = $request->get('caravana_id');
        $fecha_inicio = $request->get('fecha_inicio');
        $fecha_fin = $request->get('fecha_fin');
        $precio_total = $request->get('precio_total');
        $precio_pagado = $request->get('precio_pagado');

        // Prevenir duplicados: solo crea si no existe una reserva igual
        $existe = Reserva::where([
            'user_id' => $user->id,
            'caravana_id' => $caravana_id,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
        ])->first();

        if (!$existe) {
            $reserva = Reserva::create([
                'user_id' => $user->id,
                'caravana_id' => $caravana_id,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'precio_total' => $precio_total,
                'precio_pagado' => $precio_pagado,
            ]);
            Mail::to($user->email)->send(new ReservaConfirmada($reserva));
        } else {
            $reserva = $existe;
        }

        return view('reservas.completada', compact('reserva'));
    }

    // Pago cancelado
    public function checkoutCancel()
    {
        return view('reservas.cancelada');
    }
    

    // 4. Editar reserva
    public function edit(Reserva $reserva)
{
    $this->authorize('update', $reserva);

    $hoy = Carbon::today();
    $diasHastaInicio = $hoy->diffInDays(Carbon::parse($reserva->fecha_inicio), false);
    if ($diasHastaInicio < 7) {
        return redirect()->route('dashboard')->withErrors(['msg' => 'No se puede editar la reserva si faltan menos de 7 días para el inicio. Contacte con nosotros']);
    }

    $caravanas = Caravana::all();
    return view('reservas.edit', compact('reserva', 'caravanas'));
}


    // 5. Actualizar reserva
    public function update(Request $request, Reserva $reserva)
{
    $this->authorize('update', $reserva);
    $user = Auth::user();

    $rules = [
        'fecha_inicio' => 'required|date|after_or_equal:today',
        'fecha_fin' => 'required|date|after:fecha_inicio',
        'caravana_id' => 'required|exists:caravanas,id',
    ];

    if (Auth::user()->is_admin) {
        $rules['precio_total'] = 'required|numeric|min:0';
        $rules['precio_pagado'] = 'required|numeric|min:0';
    }

    $data = $request->validate($rules);

    $errores = $this->validarReglasNegocio(
        $user, $data['caravana_id'], $data['fecha_inicio'], $data['fecha_fin'],$reserva->id );
    if (count($errores)) {
        return back()->withErrors(['msg' => implode(' ', $errores)])->withInput();
    }

        $inicio = Carbon::parse($data['fecha_inicio']);
        $fin = Carbon::parse($data['fecha_fin']);
        $duracion = $inicio->diffInDays($fin);

        $caravana = Caravana::find($data['caravana_id']);
        $precio_total = $caravana->precio_dia * $duracion;
        $pagoReserva = round($precio_total * 0.2, 2);

    $updateData = [
        'caravana_id' => $data['caravana_id'],
        'fecha_inicio' => $inicio,
        'fecha_fin' => $fin,
    ];

    // Actualizar precios y fianza según cambios
        $caravana = Caravana::find($data['caravana_id']);
        $precio_total = $caravana->precio_dia * $duracion;
        $fianza = 150.00;
        $pagoReserva = round($precio_total * 0.2, 2);

        $updateData = [
            'caravana_id' => $data['caravana_id'],
            'fecha_inicio' => $inicio,
            'fecha_fin' => $fin,
            'precio_total' => $precio_total,
            'precio_pagado' => $pagoReserva,
        ];

    if ($user->is_admin && isset($data['precio_total']) && isset($data['precio_pagado'])) {
        // Si el admin no ha modificado los precios respecto al cálculo automático
        if (
            floatval($data['precio_total']) === floatval($reserva->precio_total) &&
            floatval($data['precio_pagado']) === floatval($reserva->precio_pagado)
        ) {
            // El admin no ha tocado los precios, así que recalcúlalos
            $updateData['precio_total'] = $precio_total;
            $updateData['precio_pagado'] = $pagoReserva;
        } else {
            // El admin ha tocado algún precio, respétalo
            $updateData['precio_total'] = $data['precio_total'];
            $updateData['precio_pagado'] = $data['precio_pagado'];
        }
    } else {
        // Usuario normal: recalcular precio
        $updateData['precio_total'] = $precio_total;
        $updateData['precio_pagado'] = $pagoReserva;
    }

    $reserva->update($updateData);

    Mail::to($user->email)->send(new ReservaActualizada($reserva));

    return redirect()->route('dashboard')->with('success', 'Reserva actualizada con éxito.');

}

    // 6. Eliminar reserva
 public function destroy(Reserva $reserva)
{
    $this->authorize('delete', $reserva);
    $user = Auth::user();

    $hoy = Carbon::today();
    $diasHastaInicio = $hoy->diffInDays(Carbon::parse($reserva->fecha_inicio), false);

    if ($diasHastaInicio < 15) {
        return redirect()->back()->with('warning', 'La reserva ha sido cancelada a menos de 15 días del inicio. Según las condiciones, se realizará el cargo completo.');
    } else {
        return redirect()->back()->with('success', 'Reserva eliminada correctamente.');
    }

    $reserva->delete();
    Mail::to($user->email)->send(new ReservaEliminada($reserva));

}

    // 7. Mostrar caravanas disponibles
    public function consultarDisponibles(Request $request)
{
    $caravanasDisponibles = null;

    if($request->isMethod('post')) {
        $data = $request->validate([
            'fecha_inicio' => 'required|date|after_or_equal:today',
            'fecha_fin' => 'required|date|after:fecha_inicio',
        ]);
        $inicio = Carbon::parse($data['fecha_inicio']);
        $fin = Carbon::parse($data['fecha_fin']);
        $duracion = $inicio->diffInDays($fin);

        // Reglas de negocio (las mismas que en prepararPago/store)
        if (($inicio->month === 7 || $inicio->month === 8) && $duracion < 7) {
            return back()->withErrors(['msg' => 'En julio y agosto, mínimo 7 días.'])
                ->withInput();
        } elseif ($duracion < 2) {
            return back()->withErrors(['msg' => 'La duración mínima para una reserva es de 2 días.'])
                ->withInput();
        }
        if (now()->addDays(60)->lt($inicio)) {
            return back()->withErrors(['msg' => 'Se puede reservar con 60 días de antelación como máximo.'])
                ->withInput();
        }
        $user = Auth::user();
        $reservasActivas = Reserva::where('user_id', $user->id)
            ->where('fecha_fin', '>=', today())
            ->count();
        if ($reservasActivas >= 5) {
            return back()->withErrors(['msg' => 'Solo puedes tener 5 reservas activas.'])
                ->withInput();
        }

        $caravanasDisponibles = Caravana::whereDoesntHave('reservas', function ($query) use ($inicio, $fin) {
            $query->where('fecha_inicio', '<', $fin)
                  ->where('fecha_fin', '>', $inicio);
        })->get();

        // Pasa también las fechas al view para los formularios de reservar
        return view('reservas.disponibles', [
            'caravanasDisponibles' => $caravanasDisponibles,
            'fecha_inicio' => $inicio->toDateString(),
            'fecha_fin' => $fin->toDateString(),
        ]);
    }

    // GET: solo el formulario, sin resultados ni fechas
    return view('reservas.disponibles', [
        'caravanasDisponibles' => null,
        'fecha_inicio' => null,
        'fecha_fin' => null,
    ]);
}

    // 8. Historial (solo admin)
    public function historial()
    {
        //como el middleware ya lo maneja en web.php 
      $historial = Historial::orderBy('fecha_inicio', 'desc')->get();
    return view('reservas.historial', compact('historial'));
    }

    public function todas()
{
    //9. Ver todas las reservas (solo admin)
    $reservas = \App\Models\Reserva::with(['user', 'caravana'])
    //de las mas cercanas a las más lejanas
        ->orderBy('fecha_inicio', 'asc')
        ->get();

    return view('reservas.todas', compact('reservas'));
}

}
