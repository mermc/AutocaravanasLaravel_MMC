@extends('layouts.app')

@section('title', 'Crear Caravana')

@section('content')
    <h2 class="text-center text-primary mb-4">Nueva Caravana</h2>
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('caravanas.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Modelo</label>
            <input type="text" name="modelo" class="form-control">
        </div>
        <div class="mb-3">
            <label>Capacidad</label>
            <input type="number" name="capacidad" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Precio por día (€)</label>
            <input type="number" name="precio_dia" class="form-control" step="0.01" required>
        </div>
        <div class="mb-3">
            <label for="foto" class="form-label">Foto</label>
            <input type="file" name="foto" class="form-control">
        </div>
        <button class="btn btn-success">Guardar</button>
        <a href="{{ route('caravanas.index') }}" class="btn btn-secondary">Volver</a>
    </form>
</div>
@endsection