@extends('layouts.app')

@section('title', 'Historial Reservas')

@section('content')
    <h2 class="text-center text-primary mb-4">Historial de Reservas</h2>
    @if($historial->count())
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Caravana</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Precio Total</th>
                <th>Fianza</th>
            </tr>
        </thead>
        <tbody>
            @foreach($historial as $reserva)
                <tr>
                    <td>{{ $reserva->user->name ?? $reserva->user_id }}</td>
                    <td>{{ $reserva->caravana->nombre ?? 'Caravana '.$reserva->caravana_id }}</td>
                    <td>{{ \Carbon\Carbon::parse($reserva->fecha_inicio)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($reserva->fecha_fin)->format('d/m/Y') }}</td>
                    <td>{{ $reserva->precio_total }} €</td>
                    <td>{{ $reserva->fianza }} €</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <p>No hay registros en el historial.</p>
    @endif
    <a href="{{ route('dashboard') }}" class="btn btn-secondary mt-3">Volver</a>
</div>
@endsection