@extends('layouts.app')

@section('title', 'Registro de usuario')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 65vh;">
    <div class="col-md-7 col-lg-6">
        <div class="card shadow p-4 border-0">
            <div class="d-flex flex-column align-items-center mb-4">
                <img src="{{ asset('images/logodef.png') }}" alt="Logo" style="height: 80px; width: 80px; object-fit: contain; border-radius: 12px; border:2px solid #0fab9f; background:#fff;">
                <h2 class="mt-3 mb-1 fw-bold" style="color: #0fab9f;">Crea tu cuenta</h2>
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

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Nombre</label>
                    <input id="name" type="text" class="form-control" name="name"
                           value="{{ old('name') }}" required autofocus autocomplete="name">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email</label>
                    <input id="email" type="email" class="form-control" name="email"
                           value="{{ old('email') }}" required autocomplete="username">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Contraseña</label>
                    <input id="password" type="password" class="form-control" name="password"
                           required autocomplete="new-password">
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label fw-semibold">Confirma Contraseña</label>
                    <input id="password_confirmation" type="password" class="form-control" name="password_confirmation"
                           required autocomplete="new-password">
                </div>

                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                    <div class="mb-3 form-check">
                        <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                        <label class="form-check-label small" for="terms">
                            Estoy de acuerdo con los
                            <a href="{{ route('terms.show') }}" target="_blank" style="color:#0fab9f;text-decoration:underline;">Términos de Uso</a> y la
                            <a href="{{ route('policy.show') }}" target="_blank" style="color:#0fab9f;text-decoration:underline;">Política de privacidad</a>
                        </label>
                    </div>
                @endif

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <a class="link-secondary small" href="{{ route('login') }}">
                        ¿Ya tienes cuenta?
                    </a>
                    <button type="submit" class="btn btn-success fw-bold px-4" style="background:#0fab9f; border-color:#089485;">
                        <i class="bi bi-person-plus"></i> Registro
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection