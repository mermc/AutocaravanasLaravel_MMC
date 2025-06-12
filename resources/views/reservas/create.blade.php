@extends('layouts.app')

@section('title', 'Crear Reserva')

@section('content')
    <h2 class="text-center text-primary mb-4">Nueva Reserva</h2>
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
        </div>
    @endif

    {{-- FORMULARIO CLIENTE (Stripe) --}}
    <form action="{{ route('reservas.prepararPago') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Fecha Inicio</label>
            <input type="date" name="fecha_inicio" class="form-control"
                   value="{{ old('fecha_inicio', $fecha_inicio ?? '') }}"
                   readonly required>
        </div>
        <div class="mb-3">
            <label>Fecha Fin</label>
            <input type="date" name="fecha_fin" class="form-control"
                   value="{{ old('fecha_fin', $fecha_fin ?? '') }}"
                   readonly required>
        </div>
        <div class="mb-3">
            <label for="caravana_id" class="form-label">Caravana</label>
            <select name="caravana_id" class="form-control" required disabled>
                <option value="{{ $selected_caravana_id ?? '' }}">
                    {{ $caravanas[0]->nombre ?? 'Caravana '.$caravanas[0]->id }}
                    ({{ $caravanas[0]->precio_dia }} €/día) ({{ $caravanas[0]->capacidad }} plazas)
                </option>
            </select>
            <input type="hidden" name="caravana_id" value="{{ $selected_caravana_id }}">
        </div>
        <div class="mb-3">
            <label><strong>Precio total:</strong></label>
            <div class="form-control-plaintext fs-4 mb-2">
                {{ number_format($precio_total ?? 0, 2) }} €
            </div>
            <input type="hidden" name="precio_total" value="{{ $precio_total ?? 0 }}">
        </div>
        <div class="mb-3">
            <label><strong>Pago para reservar (20%):</strong></label>
            <div class="form-control-plaintext fs-5 mb-2 text-success">
                {{ number_format($precio_pagado ?? 0, 2) }} €
            </div>
            <input type="hidden" name="precio_pagado" value="{{ $precio_pagado ?? 0 }}">
        </div>
        <div class="mb-3">
            <label><strong>Fianza (a depositar en metálico el día de la reserva):</strong></label>
            <div class="form-control-plaintext mb-2">150,00 €</div>
        </div>
        <button class="btn btn-success">Reservar y Pagar</button>
        <a href="{{ route('reservas.consultar_disponibles') }}" class="btn btn-secondary">Hacer otra consulta</a>
    </form>

    {{-- FORMULARIO ADMIN (Pago en efectivo) --}}
    @auth
    @if(Auth::user()->is_admin)
        <hr>
        <div class="mb-2">— O crear una reserva manualmente (TODO EN EFECTIVO, ADMIN) —</div>
        <form action="{{ route('reservas.store') }}" method="POST" style="margin-top: 10px;">
            @csrf
            <input type="hidden" name="caravana_id" value="{{ $selected_caravana_id }}">
            <input type="hidden" name="fecha_inicio" value="{{ $fecha_inicio }}">
            <input type="hidden" name="fecha_fin" value="{{ $fecha_fin }}">
            {{-- Puedes añadir más campos ocultos si lo deseas --}}
            <button type="submit" class="btn btn-secondary">Crear reserva (pago en efectivo, admin)</button>
        </form>
    @endif
    @endauth

@endsection