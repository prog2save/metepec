<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EstadoTicket;
use App\Http\Requests\EstadoTicketStore;

class EstadoTicketController extends Controller
{
    public function index(Request $request)
    {
       $estados = EstadoTicket::orderBy('id', 'desc')->where('activo', true)->get();
       return view('pages.estados.index', compact('estados'));
    }

    public function create()
    {
        return view('pages.estados.create');
    }

    public function store(EstadoTicketStore $request)
    {
        $input = $request->validated();
        $input['vista_usuario'] = $request->boolean('vista_usuario');
        $input['activo'] = $request->boolean('activo');

        EstadoTicket::create($input);

        return redirect()
        ->route('estados.index')
        ->with('success', 'Estado creado exitosamente.');
    }

    public function show(EstadoTicket $estado)
    {

    }

    public function edit(string $id)
    {
        $estado = EstadoTicket::findOrFail($id);

        return view('pages.estados.edit', compact('estado'));
    }

    public function update(EstadoTicketStore $request, string $estado)
    {
        $input = $request->validated();
        EstadoTicket::where('id', $estado)->update($input);
        return redirect()
            ->route('estados.index')
            ->with('success', 'Estado actualizado correctamente');
    }

    public function destroy(string $id)
    {        
        EstadoTicket::where('id', $id)->update(['activo' => false]);
        return redirect()
            ->route('estados.index')
            ->with('success', 'Estado eliminado correctamente');
        
    }
}
