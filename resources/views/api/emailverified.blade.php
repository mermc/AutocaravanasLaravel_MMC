@extends('layouts.app')

@section('title', 'Verificación de correo')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if($success)
                <div class="alert alert-success text-center mt-4">
                    <h2 class="mb-3">¡Email verificado correctamente!</h2>
                    <p>{{ $message ?? 'Ya puedes acceder a la app con tu usuario y contraseña.' }}</p>
                    <a href="autocaravanas://login" class="btn btn-primary mt-3">Iniciar sesión en la app</a>
                </div>
            @else
                <div class="alert alert-danger text-center mt-4">
                    <h2 class="mb-3">Error de verificación</h2>
                    <p>{{ $message ?? 'El enlace no es válido o ha sido manipulado.' }}</p>
                    <a href="{{ route('login') }}" class="btn btn-secondary mt-3">Volver al inicio</a>
                </div>
            @endif
        </div>
    </div>
@endsection