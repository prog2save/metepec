<?php

namespace App\Http\Controllers;

use App\Models\Ciudadano;
use Illuminate\Http\Request;
use App\Http\Requests\CiudadanoStore;

class CiudadanoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $estatus = $request->get('estatus', 'ACTIVO');
        $query = Ciudadano::query();

        if ($estatus !== 'TODOS') {
            $query->where('estatus', $estatus);
        }

        $ciudadanos = $query
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('pages.ciudadanos.index', compact('ciudadanos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.ciudadanos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CiudadanoStore $request)
    {
        $input = $request->validated();
        Ciudadano::create($input);
        return redirect()->back()->with('success', 'Ciudadano creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ciudadano $ciudadano)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ciudadano = Ciudadano::findOrFail($id);
        return view('pages.ciudadanos.edit', compact('ciudadano'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CiudadanoStore $request, string $id)
    {
        $input = $request->validated();
        Ciudadano::where('id', $id)->update($input);
        return redirect()->back()->with('success', 'Ciudadano actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Ciudadano::where('id', $id)->update(['estatus' => 'SUSPENDIDO']);
        return redirect()->back()->with('success', 'Ciudadano suspendido correctamente.');
    }
}
