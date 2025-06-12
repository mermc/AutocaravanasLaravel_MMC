@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 65vh;">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow p-4 border-0">
            <div class="d-flex flex-column align-items-center mb-4">
                <img src="{{ asset('images/logodef.png') }}" alt="Logo" style="height: 80px; width: 80px; object-fit: contain; border-radius: 12px; border:2px solid #0fab9f; background:#fff;">
                <h2 class="mt-3 mb-1 fw-bold" style="color: #0fab9f;">Hola de nuevo</h2>
                <p class="mb-0 text-muted">Accede a tu cuenta</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger py-2 small mb-3">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('status'))
                <div class="alert alert-success py-2 small mb-3">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email</label>
                    <input id="email" type="email" class="form-control" name="email"
                           value="{{ old('email') }}" required autofocus autocomplete="username">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Contraseña</label>
                    <input id="password" type="password" class="form-control" name="password"
                           required autocomplete="current-password">
                </div>

                <div class="mb-3 form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                    <label class="form-check-label small" for="remember_me">
                        Recuérdame
                    </label>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    @if (Route::has('password.request'))
                        <a class="link-secondary small" href="{{ route('password.request') }}">
                            He olvidado mi contraseña
                        </a>
                    @endif
                </div>

                <button type="submit" class="btn btn-success w-100 fw-bold" style="background:#0fab9f; border-color:#089485;">
                    <i class="bi bi-box-arrow-in-right"></i> Inicia Sesión
                </button>
            </form>

            <div class="mt-4 text-center small">
                ¿No tienes cuenta?
                <a href="{{ route('register') }}" class="fw-semibold" style="color: #0fab9f;">
                    Regístrate aquí
                </a>
            </div>

        </div>
    </div>
</div>
@endsection