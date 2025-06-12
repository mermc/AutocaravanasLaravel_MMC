@extends('layouts.app')

@section('title', 'Editar Caravanna')

@section('content')
    <h2 class="text-center text-primary mb-4">Editar Caravana</h2>
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('caravanas.update', $caravana) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ $caravana->nombre }}" required>
        </div>
        <div class="mb-3">
            <label>Modelo</label>
            <input type="text" name="modelo" class="form-control" value="{{ $caravana->modelo }}">
        </div>
        <div class="mb-3">
            <label>Capacidad</label>
            <input type="number" name="capacidad" class="form-control" value="{{ $caravana->capacidad }}" required>
        </div>
        <div class="mb-3">
            <label>Precio por día (€)</label>
            <input type="number" name="precio_dia" class="form-control" step="0.01" value="{{ $caravana->precio_dia }}" required>
        </div>
        <div class="mb-3">
            <label for="foto" class="form-label">Foto</label>
            <input type="file" name="foto" class="form-control">
            @if($caravana->foto)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $caravana->foto) }}" width="120" alt="Foto">
                </div>
            @endif
        </div>
        <button class="btn btn-success">Actualizar</button>
        <a href="{{ route('caravanas.index') }}" class="btn btn-secondary">Volver</a>
    </form>
</div>
@endsection