<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CanalIngreso;
use App\Http\Requests\TicketStore;
use App\Http\Requests\CiudadanoStore;
use App\Models\Ciudadano;
use App\Models\DireccionMunicipal;
use App\Models\Servicio;
use App\Models\Usuario;
use App\Models\TicketRespuesta;
use App\Models\EstadoTicket;

class AgenteController extends Controller
{
    /**
     * Dashboard del agente.
     * Muestra resumen de sus tickets + listado paginado.
     */
    public function dashboard()
    {
        $agente = Auth::user();

        $tickets    = Ticket::where('id_agente_asignado', $agente->id)->latest()->paginate(10);
        $nuevos     = Ticket::where('id_agente_asignado', $agente->id)->where('estado', 'Nuevo')->count();
        $abiertos   = Ticket::where('id_agente_asignado', $agente->id)->where('estado', 'Abierto')->count();
        $pendientes = Ticket::where('id_agente_asignado', $agente->id)->where('estado', 'Pendiente')->count();
        $resueltos  = Ticket::where('id_agente_asignado', $agente->id)->where('estado', 'Resuelto')->count();
        $totalAsignados = Ticket::where('id_agente_asignado', $agente->id)->count();

        return view('pages.agente.dashboard', compact(
            'tickets',
            'totalAsignados',
            'abiertos',
            'pendientes',
            'resueltos',
            'nuevos'
        ));
    }

    public function tickets(Request $request)
    {
        $query = Ticket::where('id_agente_asignado', Auth::id());

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('canal')) {
            $query->where('id_canal', $request->canal);
        }

        $tickets = $query->latest()->paginate(15)->withQueryString();
        $canales = CanalIngreso::orderBy('nombre')->get();
        $estados = EstadoTicket::where('activo', true)->orderBy('nombre_agente')->get();

        return view('pages.agente.tickets.index', compact('tickets', 'canales', 'estados'));
    }

    public function resolver(Ticket $ticket)
    {
        if ($ticket->id_agente_asignado !== Auth::id()) {
            abort(403, 'No tienes permiso para resolver este ticket.');
        }

        $ticket->update([
            'estado'           => 'Resuelto',
            'fecha_resolucion' => now(),
        ]);

        return redirect()->back()->with('success', 'Ticket marcado como resuelto.');
    }

    public function create()
    {
        $ciudadanos = Ciudadano::select('id', 'nombre', 'apellido_paterno', 'apellido_materno')
            ->orderBy('nombre')->get();

        $agentes = Usuario::select('id', 'nombre', 'apellido', 'email')
            ->orderBy('nombre')->get();

        $direcciones = DireccionMunicipal::select('id', 'nombre_direccion')
            ->where('estatus', true)->orderBy('nombre_direccion')->get();

        $servicios = Servicio::select('id', 'nombre_servicio', 'id_direccion_municipal')
            ->where('activo', true)->orderBy('nombre_servicio')->get();

        return view('pages.agente.tickets.create', compact('ciudadanos', 'agentes', 'direcciones', 'servicios'));
    }

    public function store(TicketStore $request)
    {
        $input = $request->validated();

        if (!empty($input['canal_ingreso'])) {
            $canal = CanalIngreso::firstOrCreate(['nombre' => $input['canal_ingreso']]);
            $input['id_canal'] = $canal->id;
        }
        unset($input['canal_ingreso']);

        if (!empty($input['id_agente_asignado']) && ($input['estado'] ?? 'Nuevo') === 'Nuevo') {
            $input['estado'] = 'Abierto';
        }

        Ticket::create($input);

        if ($request->wantsJson()) {
            return response()->json($request, 201);
        }

        return redirect()->route('agente.tickets.index')->with('success', 'Ticket creado exitosamente.');
    }

    public function ciudadanoCreate()
    {
        return view('pages.agente.ciudadanos.create');
    }

    // Guardar nuevo ciudadano
    public function ciudadanoStore(CiudadanoStore $request)
    {
        $input = $request->validated();
        $ciudadano = Ciudadano::create($input);

        if ($request->wantsJson()) {
            return response()->json($ciudadano, 201);
        }

        return redirect()->route('agente.tickets.create')
            ->with('success', 'Ciudadano creado exitosamente.');
    }

    public function show(string $id)
    {
        $ticket = Ticket::with([
            'ciudadano',
            'agente',
            'respuestas.usuario'
        ])->findOrFail($id);

        $agentes = Usuario::select('id', 'nombre', 'apellido')->orderBy('nombre')->get();
        $estados = EstadoTicket::where('activo', true)->orderBy('nombre_agente')->get();
        $direcciones = DireccionMunicipal::select('id', 'nombre_direccion')->where('estatus', true)->orderBy('nombre_direccion')->get();
        $servicios = Servicio::select('id', 'nombre_servicio', 'id_direccion_municipal')->where('activo', true)->orderBy('nombre_servicio')->get();

        return view('pages.agente.tickets.show', compact('ticket', 'agentes', 'estados', 'direcciones', 'servicios'));
    }

    public function update(Request $request, string $id)
    {
        $ticket = Ticket::findOrFail($id);

        $ticket->update($request->only([
            'id_agente_asignado',
            'estado',
            'prioridad',
            'tipo_ticket',
            'id_direccion_municipal',
            'id_servicio',
        ]));

        return redirect()->route('agente.tickets.index')
            ->with('success', 'Ticket actualizado exitosamente.');
    }

    public function responder(Request $request, string $id)
    {
        $request->validate([
            'contenido' => ['required', 'string', 'max:2000'],
            'tipo'      => ['in:respuesta,nota_interna'],
        ]);

        TicketRespuesta::create([
            'id_ticket'  => $id,
            'id_usuario' => Auth::id(),
            'contenido'  => $request->contenido,
            'tipo'       => $request->tipo ?? 'respuesta',
        ]);

        return back()->with('success', 'Respuesta enviada.');
    }
}
