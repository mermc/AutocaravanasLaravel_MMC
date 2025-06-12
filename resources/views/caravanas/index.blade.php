@extends('layouts.app')

@section('title', 'Index Caravanas')

@section('content')
    <h2 class="text-center text-primary mb-4">Listado de Caravanas</h2>
    <a href="{{ route('caravanas.create') }}" class="btn btn-primary mb-3">Nueva Caravana</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Foto</th>
                <th>Nombre</th>
                <th>Modelo</th>
                <th>Capacidad</th>
                <th>Precio/día</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($caravanas as $caravana)
            <tr>
                <td>
                    @if($caravana->foto)
                        <img src="{{ asset('storage/' . $caravana->foto) }}" width="300" alt="Foto">
                    @else
                        <span>Sin foto</span>
                    @endif
                </td>
                <td>{{ $caravana->nombre }}</td>
                <td>{{ $caravana->modelo }}</td>
                <td>{{ $caravana->capacidad }}</td>
                <td>{{ $caravana->precio_dia }} €</td>
                <td>
                    <a href="{{ route('caravanas.edit', $caravana) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('caravanas.destroy', $caravana) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('¿Borrar caravana?')" class="btn btn-sm btn-danger">Borrar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('dashboard') }}" class="btn btn-secondary mt-3">Volver</a>
</div>
@endsection