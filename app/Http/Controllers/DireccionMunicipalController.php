<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DireccionMunicipal;
use App\Http\Requests\DireccionMunicipalStore;

class DireccionMunicipalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $estatus = $request->get('estatus', true);
        $query = DireccionMunicipal::query();

        if ($estatus !== 'TODOS') {
            $query->where('estatus', $estatus);
        }

        $direcciones = $query
            ->orderBy('nombre_direccion')
            ->paginate(10)
            ->withQueryString();
            
        return view('pages.direcciones.index', compact('direcciones'));
    }
    public function create()
    {
        return view('pages.direcciones.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DireccionMunicipalStore $request)
    {
        $input = $request->validated();
        DireccionMunicipal::create($input);
        return redirect()->back()->with('success', 'Dirección Municipal agregada correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(DireccionMunicipal $direccionMunicipal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $direccion = DireccionMunicipal::findOrFail($id);
        return view('pages.direcciones.edit', compact('direccion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DireccionMunicipalStore $request, string $id)
    {
        $input = $request->validated();
        DireccionMunicipal::where('id', $id)->update($input);
        return redirect()->back()->with('success', 'Dirección Municipal actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DireccionMunicipal::where('id', $id)->update(['estatus' => false]);
        return redirect()->back()->with('success', 'Dirección Municipal suspendida correctamente.');
    }
}
