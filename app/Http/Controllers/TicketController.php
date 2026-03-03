<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Http\Requests\TicketStore;
use App\Models\Ciudadano;
use App\Models\DireccionMunicipal;
use App\Models\Servicio;
use App\Models\Usuario;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tickets = Ticket::with('ciudadano', 'agente')
            ->orderByDesc('id')
            ->paginate(10);
        return view('pages.tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ciudadanos = Ciudadano::select('id', 'nombre', 'apellido_paterno', 'apellido_materno')
            ->orderBy('nombre')
            ->get();

        $agentes = Usuario::select('id', 'nombre', 'apellido', 'email') // ajusta campos según tu tabla
            ->orderBy('nombre')
            ->get();

        $direcciones = DireccionMunicipal::select('id', 'nombre_direccion')
            ->where('estatus', true)
            ->orderBy('nombre_direccion')
            ->get();

        $servicios = Servicio::select('id', 'nombre_servicio', 'id_direccion_municipal')
            ->where('activo', true) 
            ->orderBy('nombre_servicio')
            ->get();

        return view('pages.tickets.create', compact('ciudadanos', 'agentes', 'direcciones', 'servicios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TicketStore $request)
    {
        $input = $request->validated();

        $adjuntos = [];

        if ($request->hasFile('adjuntos')) {
            foreach ($request->file('adjuntos') as $archivo) {
                $ruta = $archivo->store('tickets/adjuntos', 'public');

                $adjuntos[] = [
                    'nombre_original' => $archivo->getClientOriginalName(),
                    'ruta' => $ruta,
                    'mime' => $archivo->getClientMimeType(),
                    'tamano' => $archivo->getSize(),
                ];
            }
        }

        if (!empty($adjuntos)) {
            $input['adjuntos'] = $adjuntos;
        }

        if (in_array($input['estado'] ?? 'Abierto', ['Resuelto', 'Cerrado'])) {
            $input['fecha_resolucion'] = now();
        }

        Ticket::create($input);

        return redirect()
        ->route('tickets.index')
        ->with('success', 'Ticket creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ticket = Ticket::findOrFail($id);

        $ciudadanos = Ciudadano::select('id', 'nombre', 'apellido_paterno', 'apellido_materno', 'telefono_principal')
            ->orderBy('nombre')
            ->get();

        $agentes = Usuario::select('id', 'nombre', 'email')
            ->orderBy('nombre')
            ->get();

        $direcciones = DireccionMunicipal::select('id', 'nombre_direccion')
            ->where('estatus', true)
            ->orderBy('nombre_direccion')
            ->get();

        $servicios = Servicio::select('id', 'nombre_servicio', 'id_direccion_municipal')
            ->where('activo', true)
            ->orderBy('nombre_servicio')
            ->get();

        return view('pages.tickets.edit', compact('ticket', 'ciudadanos', 'agentes', 'direcciones', 'servicios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TicketStore $request, string $id)
    {
        $ticket = Ticket::findOrFail($id);
        $input = $request->validated();

        $adjuntos = $ticket->adjuntos ?? [];

        if ($request->hasFile('adjuntos')) {
            foreach ($request->file('adjuntos') as $archivo) {
                $ruta = $archivo->store('tickets/adjuntos', 'public');

                $adjuntos[] = [
                    'nombre_original' => $archivo->getClientOriginalName(),
                    'ruta' => $ruta,
                    'mime' => $archivo->getClientMimeType(),
                    'tamano' => $archivo->getSize(),
                ];
            }
        }

        $input['adjuntos'] = $adjuntos;

        $ticket->update($input);

        return redirect()
        ->route('tickets.index')
        ->with('success', 'Ticket actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Ticket::where('id', $id)->update(['activo' => false]);
        return redirect()->back()->with('success', 'Ticket eliminado exitosamente.');
    }

    public function tickethecho(string $id)
    {
        $ticket = Ticket::findOrFail($id);
        if ($ticket->estado === 'Resuelto') {
            return back()->with('info', 'El ticket ya está resuelto.');
        }
        $ticket->update(['estado' => 'Resuelto', 'fecha_resolucion' => now()]);
        return redirect()->back()->with('success', 'Ticket marcado como resuelto exitosamente.');
    }
}
