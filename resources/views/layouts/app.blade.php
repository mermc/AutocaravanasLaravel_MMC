<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel de Autocaravanas')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700&display=swap" rel="stylesheet">
    <!-- Iconos de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7fafc;
            font-family: 'Montserrat', Arial, sans-serif;
        }
        .navbar {
            background-color: #0fab9f !important;
            border-bottom: 3px solid #089485;
        }
        .navbar-brand img {
            height: 46px;
            margin-right: 12px;
            border-radius: 6px;
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .nav-link, .navbar-brand {
            color: #fff !important;
            transition: color 0.2s;
        }
        .nav-link:hover, .navbar-brand:hover {
            color: #e5f9f7 !important;
        }
        .btn-success, .bg-success {
            background-color: #0fab9f !important;
            border-color: #089485 !important;
        }
        .btn-success:hover {
            background-color: #089485 !important;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 2px 10px 0 rgba(15,171,159,0.07);
        }
        .table thead {
            background: #0fab9f !important;
            color: #fff;
        }
        .alert-success {
            background: #e0f7f5;
            color: #089485;
            border: 1px solid #0fab9f;
        }
        .alert-danger {
            background: #fbeee6;
            color: #b52a2a;
            border: 1px solid #eebba1;
        }
        @media (max-width: 575px) {
            .navbar-brand span {
                display: none;
            }
            .navbar-brand img {
                margin-right: 0;
            }
        }
    </style>
    @stack('styles')
    @livewireStyles
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="@auth {{ route('dashboard') }} @else {{ url('/') }} @endauth">
                <img src="{{ asset('images/logodef.png') }}" alt="Logo">
                <span>Autocaravanas Milan</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <i class="bi bi-house-door fs-5"></i> <span class="d-none d-lg-inline">Mis Reservas</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('reservas.consultar_disponibles') }}">
                                <i class="bi bi-calendar-plus fs-5"></i> <span class="d-none d-lg-inline">Nueva Reserva</span>
                            </a>
                        </li>
                        @if(auth()->user()->is_admin)
                            <li class="nav-item"><a class="nav-link" href="{{ route('reservas.todas') }}"><i class="bi bi-list-ul fs-5"></i> <span class="d-none d-lg-inline">Todas las reservas</span></a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('reservas.historial') }}"><i class="bi bi-clock-history fs-5"></i> <span class="d-none d-lg-inline">Historial</span></a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('caravanas.index') }}"><i class="bi bi-truck fs-5"></i> <span class="d-none d-lg-inline">Gestionar caravanas</span></a></li>
                        @endif
                        <li class="nav-item"><a class="nav-link" href="{{ route('profile.show') }}"><i class="bi bi-person-circle fs-5"></i> <span class="d-none d-lg-inline">Mi Perfil</span></a></li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="btn btn-link nav-link" type="submit"><i class="bi bi-box-arrow-right fs-5"></i> <span class="d-none d-lg-inline">Salir</span></button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mb-5">
        @yield('content')
    </div>
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
    @livewireScripts
</body>
</html>