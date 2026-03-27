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
use App\Models\TicketView;
use App\Models\Tag;
use Illuminate\Support\Str;

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

    //Listamos los tickets que le corresponden al agente
    public function tickets(Request $request)
    {
        $query = Ticket::where('id_agente_asignado', Auth::id());

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado); //Llamada del filtro de estado
        }

        if ($request->filled('canal')) {
            $query->where('id_canal', $request->canal); //Llamada del filtro de canal de ingreso
        }

        if ($request->filled('tag')) {
            $query->whereHas('tags', fn($q) => $q->where('slug', $request->tag));
        }

        $tickets = $query->latest()->paginate(15)->withQueryString();
        $canales = CanalIngreso::orderBy('nombre')->get();
        $tags = Tag::orderBy('name')->get(); 
        $estados = EstadoTicket::where('activo', true)->orderBy('nombre_agente')->get();

        return view('pages.agente.tickets.index', compact('tickets', 'canales', 'estados', 'tags'));
    }

    //Resolver sin entrar a editar el ticket
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

        if (!empty($input['canal_ingreso'])) { //validar si ya existe dicho canal de ingreso
            //Si no existe crea un nuevo registro, si ya existe le corresponde el id
            $canal = CanalIngreso::firstOrCreate(['nombre' => $input['canal_ingreso']]);
            $input['id_canal'] = $canal->id;
        }
        unset($input['canal_ingreso']);

        //Si no coloca un agente el estado se marca como nuevo
        if (!empty($input['id_agente_asignado']) && ($input['estado'] ?? 'Nuevo') === 'Nuevo') {
            $input['estado'] = 'Abierto';
        }

        $ticket = Ticket::create($input);
        $this->syncTags($ticket, $request->tags);

        if ($request->wantsJson()) {
            return response()->json($request, 201);
        }

        return redirect()->route('agente.tickets.index')->with('success', 'Ticket creado exitosamente.');
    }

    public function ciudadanoCreate() //Crear ciudadano desde el select del create
    {
        return view('pages.agente.ciudadanos.create');
    }

    // Guardar al nuevo ciudadano
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

    //abrir el ticket para mostrar su contenido
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
        $tickets_creados = Ticket::with(['servicio'])
            ->where('activo', 1)
            ->where('id_ciudadano', $ticket->id_ciudadano)
            ->where('id', '!=', $ticket->id) // excluir el ticket actual
            ->orderByDesc('created_at')
            ->take(5) 
            ->get();


        return view('pages.agente.tickets.show', compact('ticket', 'agentes', 'estados', 'direcciones', 'servicios', 'tickets_creados'));
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

        $this->syncTags($ticket, $request->tags ?? null);

        return redirect()->route('agente.tickets.index')
            ->with('success', 'Ticket actualizado exitosamente.');
    }

    //Logica para las respuestas que contiene cada ticket y poderse comunicar con el solicitante 
    // usuario final
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

    public function indexForAgent()
    {
        $views = TicketView::active()
            ->visibleFor(auth()->user())
            ->ordered()
            ->with(['conditions', 'columns'])
            ->get();

        return view('pages.agente.ticket-views.index-agent', compact('views'));
    }

    public function showView(TicketView $ticketView)
    {
        //Verifica el acceso, solo mostrará las vistas que están puestas para todos los agentes
        if ($ticketView->visibility === 'only_me' && $ticketView->created_by !== auth()->id()) {
            abort(403);
        }

        // Todas las vistas para el sidebar izquierdo
        $views = TicketView::active()
            ->visibleFor(auth()->user())
            ->ordered()
            ->with(['conditions', 'columns'])
            ->get();

        //Carga de condiciones y columnas de la vista
        $ticketView->load(['conditions', 'columns']);

        //Query de los tickets
        $query = Ticket::query()->with(['ciudadano', 'agente']);

        //Aplicar las condiciones de ALL, aplicar todas las condiciones
        foreach ($ticketView->conditions->where('match_type', 'all') as $condition) {
            $this->applyCondition($query, $condition);
        }

        //Condiciones de al menos cumplirse una
        $anyConditions = $ticketView->conditions->where('match_type', 'any');
        if ($anyConditions->isNotEmpty()) {
            $query->where(function ($q) use ($anyConditions) {
                foreach ($anyConditions as $condition) {
                    $q->orWhere(function ($q2) use ($condition) {
                        $this->applyCondition($q2, $condition);
                    });
                }
            });
        }

        //Obtener los tickets resultado de la query
        $tickets = $query->get();
        //Ordenar las columnas segun como están ordenadas por el administrador
        $columns = $ticketView->columns->sortBy('position');

        return view('pages.agente.ticket-views.index-agent', compact(
            'views',
            'ticketView',
            'tickets',
            'columns'
        ));
    }

    private function applyCondition($query, $condition): void
    {
        $column   = str_replace('tickets.', '', $condition->field);
        $operator = $condition->operator;
        $value    = $condition->value;

        match ($operator) {
            'is'           => $query->where($column, '=', $value),
            'is_not'       => $query->where($column, '!=', $value),
            'contains'     => $query->where($column, 'ilike', '%' . $value . '%'),
            'not_contains' => $query->where($column, 'not ilike', '%' . $value . '%'),
            'present'      => $query->whereNotNull($column)->where($column, '!=', ''),
            'not_present'  => $query->where(function ($q) use ($column) {
                $q->whereNull($column)->orWhere($column, '=', '');
            }),
            'less_than'    => $query->where($column, '<', $value),
            'greater_than' => $query->where($column, '>', $value),
            'is_before'    => $query->whereDate($column, '<', $value),
            'is_after'     => $query->whereDate($column, '>', $value),
            default        => null,
        };
    }

    private function syncTags(Ticket $ticket, ?string $tagsInput): void
    {
        if (empty($tagsInput)) {
            $ticket->tags()->detach();
            return;
        }

        $names = array_filter(array_map('trim', explode(',', $tagsInput)));

        $tagIds = collect($names)->map(
            fn(string $name) =>
            Tag::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'color' => '#7F77DD']
            )->id
        );

        $ticket->tags()->sync($tagIds);
    }
}
