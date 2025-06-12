@extends('layouts.app')

@section('title', 'Bienvenida a Caravanas Milan')

@section('content')
<div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 75vh;">
    <!-- Imagen central -->
    <img src="{{ asset('images/logodef.png') }}" alt="Caravanas Milan"
         class="mb-4 shadow rounded-3 border border-2" style="width: 500px; height: 500px; background: #fff; object-fit: contain; border-color: #0fab9f;">
    <!-- Título y subtítulo -->
    <h1 class="text-center fw-bold mb-2" style="color: #0fab9f;">¡Te damos la bienvenida a Caravanas Milan!</h1>
    
    <!-- Botones centrados debajo de la imagen -->
    <div class="d-flex flex-column flex-md-row gap-3 w-100 justify-content-center align-items-center">
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}"
                   class="btn btn-success btn-lg px-4 py-2 fw-semibold shadow-sm"
                   style="background-color: #0fab9f; border-color: #089485;">
                    <i class="bi bi-house-door"></i> Ir al Dashboard
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="btn btn-success btn-lg px-4 py-2 fw-semibold shadow-sm"
                   style="background-color: #0fab9f; border-color: #089485;">
                    <i class="bi bi-box-arrow-in-right"></i> Inicia sesión
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="btn btn-outline-dark btn-lg px-4 py-2 fw-semibold shadow-sm"
                       style="border-color: #0fab9f; color: #089485;">
                        <i class="bi bi-person-plus"></i> Regístrate
                    </a>
                @endif
            @endauth
        @endif
    </div>
     <!-- Espacio para QR de descarga de la app -->
    <div class="my-4 text-center">
        <h4 class="mb-3" style="color:#0fab9f;">Descarga nuestra app</h4>
        <img src="{{ asset('storage/caravanas/qrapp.png') }}"
             alt="QR descarga app"
             style="width:200px; border: 4px solid #0fab9f; border-radius: 10px;">
        <div class="mt-2">
            <a href="https://milanmc.me/app/Caravanasmilan.apk" target="_blank" rel="noopener" class="btn btn-sm btn-outline-success">
                Descargar APK
            </a>
        </div>
        <small class="text-muted d-block mt-2">Escanea el QR o haz clic para instalar la app en Android</small>
    </div>

</div>
@endsection