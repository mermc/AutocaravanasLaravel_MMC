<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\CaravanaController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// Home pública
Route::get('/', function () {
    return view('welcome');
});

// --- RUTAS DE VERIFICACIÓN DE EMAIL (solo requieren auth) ---
Route::middleware(['auth'])->group(function () {
    // Mostrar aviso para verificar email
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    // Procesar el enlace de verificación
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/dashboard');
    })->middleware(['signed'])->name('verification.verify');

    // Reenviar correo de verificación
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    })->middleware(['throttle:6,1'])->name('verification.send');
});

// --- RUTAS QUE REQUIEREN EMAIL VERIFICADO ---
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Dashboard (Reservas futuras del usuario)
    Route::get('/dashboard', [ReservaController::class, 'index'])->name('dashboard');

    // Crear nueva reserva
    Route::get('/reservas/crear', [ReservaController::class, 'create'])->name('reservas.create');
    Route::post('/reservas/pago', [ReservaController::class, 'prepararPago'])->name('reservas.prepararPago');
    Route::get('/reservas/checkout/success', [ReservaController::class, 'checkoutSuccess'])->name('reservas.success');
    Route::get('/reservas/checkout/cancel', [ReservaController::class, 'checkoutCancel'])->name('reservas.cancel');

    // Solo para admin
    Route::post('/reservas', [ReservaController::class, 'store'])->middleware('admin')->name('reservas.store');

    // Editar reserva existente
    Route::get('/reservas/{reserva}/editar', [ReservaController::class, 'edit'])->name('reservas.edit');
    Route::put('/reservas/{reserva}', [ReservaController::class, 'update'])->name('reservas.update');

    // Eliminar reserva
    Route::delete('/reservas/{reserva}', [ReservaController::class, 'destroy'])->name('reservas.destroy');

    // Consultar caravanas disponibles para unas fechas (formulario y resultados)
    Route::match(['get', 'post'], '/reservas/disponibles', [ReservaController::class, 'consultarDisponibles'])->name('reservas.consultar_disponibles');

    // Historial solo visible por el administrador
    Route::get('/reservas/historial', [ReservaController::class, 'historial'])->middleware('admin')->name('reservas.historial');

    // Ver todas las reservas, solo el admin
    Route::get('/reservas/todas', [ReservaController::class, 'todas'])->middleware('admin')->name('reservas.todas');

    // CRUD de caravanas solo para administradores
    Route::middleware('admin')->group(function () {
        Route::resource('caravanas', CaravanaController::class)->except(['show']);
    });
});