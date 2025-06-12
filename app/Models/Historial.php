<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Caravana;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Historial extends Model
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

    // Un registro de historial pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Un registro de historial pertenece a una caravana
    public function caravana()
    {
        return $this->belongsTo(Caravana::class);
    }
}