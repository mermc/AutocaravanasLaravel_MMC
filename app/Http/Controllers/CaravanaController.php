<?php

namespace App\Http\Controllers;

use App\Models\Caravana;
use Illuminate\Http\Request;

class CaravanaController extends Controller
{
    //1. Mostrar listado de caravanas
    public function index()
    {
        $caravanas = Caravana::all();
        return view('caravanas.index', compact('caravanas'));
    }

    // 2. Mostrar formulario crear
    public function create()
    {
        return view('caravanas.create');
    }

    // 3. Guardar nueva caravana
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string',
            'modelo' => 'nullable|string',
            'capacidad' => 'required|integer',
            'precio_dia' => 'required|numeric',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('caravanas', 'public');
        }

        Caravana::create($data);

        return redirect()->route('caravanas.index')->with('success', 'Caravana creada correctamente');
    }

    // 4. Editar caravana
    public function edit(Caravana $caravana)
    {
        return view('caravanas.edit', compact('caravana'));
    }

    //5. Actualizar caravana
    public function update(Request $request, Caravana $caravana)
    {
        $data = $request->validate([
            'nombre' => 'required|string',
            'modelo' => 'nullable|string',
            'capacidad' => 'required|integer',
            'precio_dia' => 'required|numeric',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('caravanas', 'public');
        }

        $caravana->update($data);

        return redirect()->route('caravanas.index')->with('success', 'Caravana actualizada correctamente');
    }

    //6. Eliminar caravana
    public function destroy(Caravana $caravana)
    {
        $caravana->delete();
        return redirect()->route('caravanas.index')->with('success', 'Caravana eliminada');
    }
}