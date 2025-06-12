@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h2 class="text-center text-primary mb-4">{{ __('Dashboard') }}
        </h2>
    </x-slot>
   
    <div class="py-12 dark:bg-gray-800">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 dark:bg-gray-800">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-5">
                <table class="w-full text-md rounded mb-4 dark:bg-gray-800">
                    <thead>
                    <tr>
                        <th>
                            <div class="flex-auto text-left text-2xl mt-4 mb-4 dark:text-white">Lista de Reservas</div>
                        </th>
                        <th>
                            <div class="flex-auto text-right float-right mt-4 mb-4">
                                <a href="/note" class="bg-blue-500 dark:bg-cyan-700 hover:bg-gray-700 text-white font-bold mr-8 py-2 px-4 rounded">Realiza una Reserva</a>                        
                            </div>
                        </th>
                    </tr>
                    <tr class="border-b dark:text-white text-center">
                        <th class="text-center p-3 px-5">Id_usuario</th>
                        <th class="text-center p-3 px-5">Id_caravana</th>
                        <th class="text-center p-3 px-5">Fecha_Inicio</th>
                        <th class="text-center p-3 px-5">Fecha_Fin</th>
                        <th class="text-center p-3 px-5">PrecioTotal</th>
                        <th class="text-center p-3 px-5">PrecioPagado</th>
                        <th class="text-right p-3 px-5">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach(auth()->user()->reservas as $reserva)
                        <tr class="border-b hover:bg-orange-100 dark:text-white text-center">
                            <td class="p-3 px-5">
                                {{$reserva->Id_usuario}}
                            </td>    
                            <td class="p-3 px-5">
                                {{$reserva->Id_caravana}}
                            </td>
                            <td class="p-3 px-5">
                                {{$reserva->fecha_inicio}}
                            </td>
                             <td class="p-3 px-5">
                                {{$reserva->fecha_fin}}
                            </td>
                            <td class="p-3 px-5">
                                {{$reserva->precio_total}}
                            </td>
                            <td class="p-3 px-5">
                                {{$reserva->precio_pagado}}
                            </td>
                            <td class="p-3 px-5">                                  
                                <td>
                                    <a href="/reserva/{{$reserva->id}}" class="btn btn-primary">Edit</a>
                                </td>
                                <td>
                                    <form action="/reserva/{{$reserva->id}}" method="post">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Borrar</button>
                                    </form>
                                </td>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                
            </div>
        </div>
    </div>
@endsection