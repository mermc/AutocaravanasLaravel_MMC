<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Reserva;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Caravana extends Model
{
        use HasFactory;
    //

 protected $fillable = [
        'nombre',
        'modelo',
        'capacidad',
        'precio_dia',
        'foto',
    ];

    // Una caravana puede tener muchas reservas
    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }
}
