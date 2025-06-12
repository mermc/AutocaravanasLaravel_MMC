<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller
{
    //
    //el trait para que ReservaController pueda usarlo así todos los que hereden tendrán disponible $this->authorize()
        use AuthorizesRequests;
}
