@extends('layouts.app')

@section('title', 'Caravanas Disponibles')

@section('content')
    <h2 class="text-center text-primary mb-4">
            {{ __('Consultar Fechas y Caravanas Disponibles') }}
        </h2>
    <div class="container">
        <h2>Introduce las fechas para tu reserva</h2>
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('reservas.consultar_disponibles') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Fecha Inicio</label>
                <input type="date" name="fecha_inicio" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Fecha Fin</label>
                <input type="date" name="fecha_fin" class="form-control" required>
            </div>
            <button class="btn btn-info">Consultar</button>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Volver</a>
        </form>

        @if(!is_null($caravanasDisponibles))
            <hr>
            <h3>Caravanas Disponibles</h3>
            @if($caravanasDisponibles->count())
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nombre</th>
                            <th>Modelo</th>
                            <th>Capacidad</th>
                            <th>Precio/día</th>
                            <th>Fianza</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($caravanasDisponibles as $caravana)
                            <tr>
                                <td>
                                    @if($caravana->foto)
                                        <img src="{{ asset('storage/' . $caravana->foto) }}" alt="Foto" width="120">
                                    @else
                                        <span>Sin foto</span>
                                    @endif
                                </td>
                                <td>{{ $caravana->nombre ?? 'Caravana '.$caravana->id }}</td>
                                <td>{{ $caravana->modelo }}</td>
                                <td>{{ $caravana->capacidad }}</td>
                                <td>{{ $caravana->precio_dia }} €</td>
                                <td>150 €</td>
                                <td>
                        <form action="{{ route('reservas.create') }}" method="GET" style="display:inline;">
    <input type="hidden" name="caravana_id" value="{{ $caravana->id }}">
    <input type="hidden" name="fecha_inicio" value="{{ $fecha_inicio }}">
    <input type="hidden" name="fecha_fin" value="{{ $fecha_fin }}">
    <button type="submit" class="btn btn-sm btn-warning">Reservar</button>
</form>
                    </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No hay caravanas disponibles para esas fechas.</p>
            @endif
            <a href="{{ route('reservas.consultar_disponibles') }}" class="btn btn-secondary mt-3">Volver a consultar</a>
        @endif
    </div>
@endsection