@extends('layouts.app')

@section('title', 'Reserva confirmada')

@section('content')
<h2>¡Reserva confirmada!</h2>
<p>Tu reserva ha sido registrada y el pago del 20% realizado correctamente.</p>
<ul>
    <li>Caravana: {{ $reserva->caravana->nombre ?? $reserva->caravana_id }}</li>
    <li>Fecha inicio: {{ $reserva->fecha_inicio }}</li>
    <li>Fecha fin: {{ $reserva->fecha_fin }}</li>
    <li>Importe total: {{ $reserva->precio_total }} €</li>
    <li>Pago realizado: {{ $reserva->precio_pagado }} €</li>
</ul>
<a href="{{ route('dashboard') }}">Volver al panel</a>
@endsection