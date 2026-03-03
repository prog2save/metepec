<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;
use App\Models\DireccionMunicipal;
use App\Http\Requests\ServicioStore;

class ServiciosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $servicios = Servicio::with('direccionMunicipal')
            ->orderByDesc('id')
            ->paginate(10);

        $direcciones = DireccionMunicipal::select('id', 'nombre_direccion')
        ->where('estatus', true)
        ->orderBy('nombre_direccion')
        ->get();

        return view('pages.servicios.index', compact('servicios', 'direcciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $direcciones = DireccionMunicipal::select('id', 'nombre_direccion')
            ->where('estatus', true)
            ->orderBy('nombre_direccion')
            ->get();
        return view('pages.servicios.create', compact('direcciones'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ServicioStore $request)
    {
        $input = $request->validated();
        Servicio::create($input);
        return redirect()->route('servicios.index')->with('success', 'Servicio creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Servicio $servicio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $servicio = Servicio::findOrFail($id);
        $direcciones = DireccionMunicipal::select('id', 'nombre_direccion')
            ->where('estatus', true)
            ->orderBy('nombre_direccion')
            ->get();
        return view('pages.servicios.edit', compact('servicio', 'direcciones'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ServicioStore $request, string $id)
    {
        $servicio = Servicio::findOrFail($id);
        $input = $request->validated();

        $servicio->update($input);

        return redirect()->back()->with('success', 'Servicio actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Servicio::where('id',$id)->update(['activo' => false]);
        return redirect()->back()->with('success', 'Servicio eliminado exitosamente.');
    }
}
