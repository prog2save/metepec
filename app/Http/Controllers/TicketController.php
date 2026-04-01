<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Http\Requests\TicketStore;
use App\Models\Ciudadano;
use App\Models\DireccionMunicipal;
use App\Models\Servicio;
use App\Models\Usuario;
use App\Models\CanalIngreso;
use App\Models\EstadoTicket;
use App\Models\Tag;
use App\Models\Macro;
use Illuminate\Support\Str;
use App\Services\GeocodingService;


class TicketController extends Controller
{
    private GeocodingService $geocoding;

    public function __construct(GeocodingService $geocoding)
    {
        $this->geocoding = $geocoding; //inyectar servicio
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tickets = Ticket::with('ciudadano', 'agente', 'estado')
            ->where('activo', 1)
            ->orderByDesc('created_at')
            ->get();
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

        $canales = CanalIngreso::select('id', 'nombre')
            ->orderBy('nombre')
            ->get();

        $estados = EstadoTicket::select('id', 'nombre_agente')
            ->orderBy('nombre_agente')
            ->get();

        $macros = Macro::with('actions')->where('active', true)
            ->orderBy('name')
            ->get();

        return view('pages.tickets.create', compact('ciudadanos', 'agentes', 'direcciones', 'servicios', 'canales', 'estados', 'macros'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TicketStore $request)
    {

        $input = $request->validated();

        $direccionArcgis = collect([
            $request->input('ticket_calle'),
            $request->input('ticket_numero'),
            $request->input('ticket_colonia'),
            $request->input('ticket_municipio'),
            $request->input('ticket_estado'),
            $request->input('ticket_pais'),
        ])
        ->filter(fn ($valor) => filled($valor))
        ->implode(' ');

        logger()->info('Dirección enviada a ArcGIS', [
            'direccion' => $direccionArcgis
        ]);

        $coordenadas = null;

        if (
            filled($request->ticket_calle) &&
            filled($request->ticket_municipio) &&
            filled($request->ticket_estado)
        ) {
            $coordenadas = $this->geocoding->getCoordinates($direccionArcgis);

            if (!$coordenadas) {
                logger()->warning('ArcGIS no encontró coordenadas', [
                    'direccion' => $direccionArcgis
                ]);
            } else {
                $input['latitud'] = $coordenadas['lat'];
                $input['longitud'] = $coordenadas['lng'];
            }
        }

        if (!empty($input['canal_ingreso'])) {
            $canal = CanalIngreso::firstOrCreate(['nombre' => $input['canal_ingreso']]);
            $input['id_canal'] = $canal->id;
        }
        unset($input['canal_ingreso']);

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

        if (!empty($input['id_agente_asignado']) && ($input['estado'] ?? 'Nuevo') === 'Nuevo') {
            $input['estado'] = 'Abierto';
        }

        $ticket = Ticket::create($input);
        $this->syncTags($ticket, $request->tags ?? null);

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

        $canales = CanalIngreso::select('id', 'nombre')
            ->orderBy('nombre')
            ->get();

        $estados = EstadoTicket::select('id', 'nombre_agente')
            ->orderBy('nombre_agente')
            ->get();

        $tickets_creados = Ticket::with(['servicio'])
            ->where('activo', 1)
            ->where('id_ciudadano', $ticket->id_ciudadano)
            ->where('id', '!=', $ticket->id) // excluir el ticket actual
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('pages.tickets.edit', compact('ticket', 'ciudadanos', 'agentes', 'direcciones', 'servicios', 'canales', 'estados', 'tickets_creados'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TicketStore $request, string $id)
    {
        $ticket = Ticket::findOrFail($id);
        $input = $request->validated();

        if (!empty($input['canal_ingreso'])) {
            $canal = CanalIngreso::firstOrCreate(['nombre' => $input['canal_ingreso']]);
            $input['id_canal'] = $canal->id;
        }
        unset($input['canal_ingreso']);

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

        // Si se asigna agente a un ticket Nuevo, cambiar estado a Abierto
        if (!empty($input['id_agente_asignado']) && $ticket->estado === 'Nuevo') {
            $input['estado'] = 'Abierto';
        }

        $ticket->update($input);
        $this->syncTags($ticket, $request->tags ?? null);

        return redirect()
            ->route('tickets.index')
            ->with('success', 'Ticket actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Ticket::where('id', $id)->update(['activo' => '']);
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
