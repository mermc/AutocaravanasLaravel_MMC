<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Caravana;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reserva extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'caravana_id',
        'fecha_inicio',
        'fecha_fin',
        'precio_total',
        'precio_pagado',
        'fianza',
    ];


    //
        public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

     // Una reserva pertenece a una caravana
    public function caravana()
    {
        return $this->belongsTo(Caravana::class);
    }
}
