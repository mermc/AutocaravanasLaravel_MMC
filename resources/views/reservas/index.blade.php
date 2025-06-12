@php
    use Carbon\Carbon;
@endphp

@extends('layouts.app')

@section('title', 'Index Reservas')

@section('content')
    <h2 class="text-center text-primary mb-4">Reservas</h2>
    <a href="{{ route('reservas.consultar_disponibles') }}" class="btn btn-primary mb-3">Nueva Reserva</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('warning'))
        <div class="alert alert-warning">{{ session('warning') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif
    @if($reservas->count())
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Caravana</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Precio Total</th>
                <th>Precio Pagado</th>
                <th>Fianza</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservas as $reserva)
            @php
                        $diasHastaInicio = Carbon::today()->diffInDays(Carbon::parse($reserva->fecha_inicio), false);
                    @endphp
                <tr>
                    <td>{{ $reserva->caravana->nombre ?? 'Caravana '.$reserva->caravana_id }}</td>
                    <td>{{ \Carbon\Carbon::parse($reserva->fecha_inicio)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($reserva->fecha_fin)->format('d/m/Y') }}</td>
                    <td>{{ $reserva->precio_total }} €</td>
                    <td>{{ $reserva->precio_pagado }} €</td>
                    <td>{{ $reserva->fianza }} €</td>
                    <td>
                       @if($diasHastaInicio < 7)
                                <div class="alert alert-warning mb-2">
                                No se puede editar la reserva si faltan menos de 7 días para el inicio.
                                </div>
                                <button class="btn btn-sm btn-warning" disabled>Editar</button>
                                @else
                                <a href="{{ route('reservas.edit', $reserva) }}" class="btn btn-sm btn-warning">Editar</a>
                            @endif
                            <form action="{{ route('reservas.destroy', $reserva) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                @if($diasHastaInicio < 15)
                                    <div class="alert alert-warning mb-2">
                                        Atención: Cancelar ahora supondrá el cargo total según las condiciones.
                                    </div>
                                    <button onclick="return confirm('Cancelar ahora supondrá el cargo total. ¿Desea continuar?')" class="btn btn-sm btn-danger">
                                        Cancelar
                                    </button>
                                @else
                                    <button onclick="return confirm('¿Borrar reserva?')" class="btn btn-sm btn-danger">Cancelar Reserva</button>
                                @endif
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <p>No tienes reservas futuras.</p>
    @endif
    <a href="{{ route('dashboard') }}" class="btn btn-secondary mt-3">Volver</a>

</div>
@endsection