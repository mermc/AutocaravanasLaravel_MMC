<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Reserva;

class ReservaPolicy
{
    /**
     * Determinamos que el usuario creador o admin pueden actualizar 
     */
    public function update(User $user, Reserva $reserva)
    {
        
        return $user->is_admin || $user->id === $reserva->user_id;
    }

    /**
     * Borrar si es admin o creador
     */
    public function delete(User $user, Reserva $reserva)
    {

        return $user->is_admin || $user->id === $reserva->user_id;
    }
}