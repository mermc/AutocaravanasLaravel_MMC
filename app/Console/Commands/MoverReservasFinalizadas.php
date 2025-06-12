<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reserva;
use App\Models\Historial;
use Carbon\Carbon;

class MoverReservasFinalizadas extends Command
{
    protected $signature = 'reservas:mover-finalizadas';
    protected $description = 'Mueve las reservas finalizadas al historial';

    public function handle()
    {
        $hoy = Carbon::today();

        // Reservas cuya fecha_fin ya ha sido
        $reservasFinalizadas = Reserva::where('fecha_fin', '<', $hoy)->get();

        foreach ($reservasFinalizadas as $reserva) {
            // Copiamos los datos a historial
            $historialData = $reserva->only([
                'user_id',
                'caravana_id',
                'fecha_inicio',
                'fecha_fin',
                'precio_total',
                'precio_pagado',
                'fianza',
            ]);
            Historial::create($historialData);

            // Elimino la reserva activa
            $reserva->delete();
        }

        $this->info('Reservas finalizadas movidas al historial correctamente.');
    }
}