<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ReservaController;
use App\Http\Controllers\Api\MailController;
use Illuminate\Auth\Events\Verified;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

// ---- VERIFICACIÓN EMAIL (requieren auth, pero NO 'verified') ----
Route::middleware('auth:sanctum')->group(function () {

    // Enviar de nuevo el email de verificación
    Route::post('/email/verification-notification', function (Request $request) {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Tu email ya está verificado.'], 400);
        }
        $request->user()->sendEmailVerificationNotification();
        return response()->json(['message' => 'Enlace de verificación enviado.']);
    })->middleware('throttle:6,1');


    // Permitir consultar datos de usuario aunque no esté verificado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
    // Endpoint que el usuario abre desde el email (el enlace apunta aquí)
    Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = \App\Models\User::findOrFail($id);

    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        return response()->view('api.emailverified', [
            'message' => 'El enlace no es válido o está manipulado', 
            'success'=> false,
        ], 403);
    }

    if ($user->hasVerifiedEmail()) {
        return response()->view('api.emailverified', [
            'message' => 'Tu correo ya estaba verificado. ¡Ya puedes acceder a la app con tu usuario y contraseña!',
            'success' => true,
        ]);
    }

    $user->markEmailAsVerified();

    event(new Verified($user));

    return response()->view('api.emailverified', [
        'message' => '¡Email verificado correctamente! Ya puedes acceder a la app con tu usuario y contraseña.',
        'success' => true,
    ]);

})->middleware(['signed'])->name('api.verification.verify');


     
Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    //Enviar Email 
    Route::post('email', [MailController::class, 'sendEmail']);

    // Reservas
    Route::get('reserva', [ReservaController::class, 'index']); // Listar reservas activas/futuras
    Route::get('reserva/{reserva}', [ReservaController::class, 'show']); // Ver una reserva concreta
    Route::put('reserva/{reserva}', [ReservaController::class, 'update']); // Actualizar reserva
    Route::delete('reserva/{reserva}', [ReservaController::class, 'destroy']); // Cancelar reserva

    // Crear una nueva reserva
    Route::post('reserva', [ReservaController::class, 'store']); 

    // Consultar caravanas disponibles
    Route::match(['get', 'post'], '/reservas/disponibles', [ReservaController::class, 'consultarDisponibles'])
        ->name('reservas.consultar_disponibles');

    // Logout
    Route::post('logout', [AuthController::class, 'logout']);

});
