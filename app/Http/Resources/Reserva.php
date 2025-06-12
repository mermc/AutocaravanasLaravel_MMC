<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Reserva extends JsonResource
{
    /**
     *
     * @return array<string, mixed>
     */

     //incluyo todo lo que considero es util para la app de movil 
    public function toArray(Request $request): array
    {
        return [
        'id' => $this->id,
            'user_id' => $this->user_id,
            'usuario' => [
                'nombre' => $this->user->name ?? null,
                'email' => $this->user->email ?? null,
            ],
            'caravana_id' => $this->caravana_id,
            'caravana' => [
                'nombre' => $this->caravana->nombre ?? null,
                'precio_dia' => $this->caravana->precio_dia ?? null,
            ],
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'precio_total' => $this->precio_total,
            'precio_pagado' => $this->precio_pagado,
            'fianza' => $this->fianza,
            'created_at' => $this->created_at?->format('d/m/Y'),
            'updated_at' => $this->updated_at?->format('d/m/Y'),
    ];
    }
}
