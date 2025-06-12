@extends('layouts.app')

@section('title', 'Pago cancelado')

@section('content')
<h2>Pago cancelado</h2>
<p>No se ha completado el pago. No se ha realizado la reserva.</p>
<a href="{{ route('reservas.create') }}">Volver a intentar</a>
@endsection