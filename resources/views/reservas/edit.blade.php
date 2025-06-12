@extends('layouts.app')

@section('title', 'Editar Reserva')

@section('content')
    <h2 class="text-center text-primary mb-4">Editar Reserva</h2>
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('reservas.update', $reserva) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="caravana_id" class="form-label">Caravana</label>
            <select name="caravana_id" class="form-control" required>
                @foreach($caravanas as $caravana)
                    <option value="{{ $caravana->id }}" {{ $caravana->id == $reserva->caravana_id ? 'selected' : '' }}>
                        {{ $caravana->nombre ?? 'Caravana '.$caravana->id }} ({{ $caravana->precio_dia }} €/día)
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Fecha Inicio</label>
            <input type="date" name="fecha_inicio" class="form-control" value="{{ \Carbon\Carbon::parse($reserva->fecha_inicio)->format('Y-m-d') }}" required>
        </div>
        <div class="mb-3">
            <label>Fecha Fin</label>
            <input type="date" name="fecha_fin" class="form-control" value="{{ \Carbon\Carbon::parse($reserva->fecha_fin)->format('Y-m-d') }}" required>
        </div>
        @auth
        @if(Auth::user()->is_admin)
            <div class="mb-3">
                <label>Precio Total (€)</label>
                <input type="number" step="0.01" name="precio_total" class="form-control" value="{{ old('precio_total', $reserva->precio_total) }}">
            </div>
            <div class="mb-3">
                <label>Precio Pagado (€)</label>
                <input type="number" step="0.01" name="precio_pagado" class="form-control" value="{{ old('precio_pagado', $reserva->precio_pagado) }}">
            </div>
        @else
            <div class="mb-3">
                <strong>Precio Total: {{ $reserva->precio_total }} €</strong>
            </div>
            <div class="mb-3">
                <strong>Precio Pagado: {{ $reserva->precio_pagado }} €</strong>
            </div>
        @endif
        @endauth
        <div class="mb-3">
            <strong>Fianza a depositar: {{ $reserva->fianza }} €</strong>
        </div>
        <button class="btn btn-success">Actualizar</button>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Volver</a>
    </form>
</div>
@endsection