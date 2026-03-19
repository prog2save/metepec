<?php

namespace App\Http\Controllers;

use App\Http\Controllers;
use App\Models\TicketView;
use App\Http\Requests\TicketViewStore;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Ciudadano;
use App\Models\EstadoTicket;
use App\Models\CanalIngreso;
use App\Models\Ticket;

class TicketViewController extends Controller
{
    public function index()
    {
        $views = TicketView::with(['creator', 'conditions', 'columns', 'sorts'])
            ->ordered()
            ->paginate(20);

        $deletedViews = TicketView::onlyTrashed()
            ->ordered()
            ->get();

        $usuarios = Usuario::select('id', 'nombre', 'apellido')
            ->orderBy('nombre')->get();


        return view('pages.ticket-views.index', compact('views', 'deletedViews', 'usuarios'));
    }

    // ── GET /admin/ticket-views/create ───────────────────
    public function create()
    {
        $ciudadanos = Ciudadano::select('id', 'nombre', 'apellido_paterno')
            ->orderBy('nombre')
            ->get()
            ->map(fn($c) => [
                'id'     => $c->id,
                'nombre' => $c->nombre . ' ' . $c->apellido_paterno,
            ]);

        $agentes = Usuario::select('id', 'nombre', 'apellido')
            ->where('role', 'agente')
            ->orderBy('nombre')
            ->get()
            ->map(fn($a) => [
                'id'     => $a->id,
                'nombre' => $a->nombre . ' ' . $a->apellido,
            ]);

        $prioridades = ['Baja', 'Media', 'Alta', 'Urgente'];

        $estados = EstadoTicket::select('id', 'nombre_agente')
            ->orderBy('nombre_agente')
            ->pluck('nombre_agente'); // ['Abierto', 'Cerrado', ...]

        $tipos_tickets = ['Pregunta', 'Incidente', 'Problema', 'Tarea'];

        $canales = CanalIngreso::select('id', 'nombre')
            ->orderBy('nombre')
            ->pluck('nombre', 'id'); // ['1' => 'Web', '2' => 'Call Center']

        $descripciones = Ticket::select('descripcion')
            ->orderBy('descripcion')
            ->get();

        $availableFields = [
            'tickets.tipo_ticket'          => 'Ticket > Tipo de ticket',
            'tickets.id_agente_asignado'   => 'Ticket > Agente asignado',
            'tickets.id_ciudadano'         => 'Ticket > Solicitante',
            'tickets.descripcion'          => 'Ticket > Descripción',
            'tickets.canal_ingreso'        => 'Ticket > Canal de ingreso',
            'tickets.estado'               => 'Ticket > Estado',
            'tickets.prioridad'            => 'Ticket > Prioridad',
        ];

        $availableOperators = [
            'is'            => 'Es',
            'is_not'        => 'No es',
            'contains'      => 'Contiene por lo menos',
            'not_contains'  => 'No contiene',
            'present'       => 'Está presente',
            'not_present'   => 'No está presente',
            'less_than'     => 'Menor que',
            'greater_than'  => 'Mayor que',
            'is_before'     => 'Es antes de',
            'is_after'      => 'Es después de',
        ];

        $availableColumns = [
            'id'         => 'ID',
            'estado'     => 'Estado del ticket',
            'asunto'    => 'Asunto',
            'id_ciudadano'  => 'Solicitante',
            'id_agente_asignado'   => 'Agente asignado',
            'created_at' => 'Fecha de solicitud',
            'prioridad'   => 'Prioridad',
            'tipo_ticket'       => 'Tipo',
        ];

        return view('pages.ticket-views.create', compact(
            'availableFields',
            'availableOperators',
            'availableColumns',
            'ciudadanos',
            'agentes',
            'prioridades',
            'estados',
            'tipos_tickets',
            'canales',
            'descripciones'
        ));
    }

    public function store(TicketViewStore $request)
    {
        $view = TicketView::create([
            'title'       => $request->title,
            'description' => $request->description,
            'visibility'  => $request->visibility,
            'created_by'  => (int) auth()->id(),
            'position'    => TicketView::max('position') + 1,
            'active'      => $request->boolean('active', true),
        ]);

        $this->syncConditions($view, $request->conditions ?? []);
        $this->syncColumns($view, $request->columns ?? []);
        //$this->syncSorts($view, $request->sorts ?? []);

        return redirect()->route('ticket-views.index')
            ->with('success', 'Vista creada correctamente.');
    }

    public function show(TicketView $ticketView)
    {
        $ticketView->load(['conditions', 'columns', 'sorts', 'creator']);

        return view('pages.ticket-views.show', compact('ticketView'));
    }

    public function edit(TicketView $ticketView)
    {
        $ticketView->load(['conditions', 'columns', 'sorts']);

        $ciudadanos = Ciudadano::select('id', 'nombre', 'apellido_paterno')
            ->orderBy('nombre')
            ->get()
            ->map(fn($c) => [
                'id'     => $c->id,
                'nombre' => $c->nombre . ' ' . $c->apellido_paterno,
            ]);

        $agentes = Usuario::select('id', 'nombre', 'apellido')
            ->where('role', 'agente')
            ->orderBy('nombre')
            ->get()
            ->map(fn($a) => [
                'id'     => $a->id,
                'nombre' => $a->nombre . ' ' . $a->apellido,
            ]);

        $prioridades = ['Baja', 'Media', 'Alta', 'Urgente'];

        $estados = EstadoTicket::select('id', 'nombre_agente')
            ->orderBy('nombre_agente')
            ->pluck('nombre_agente');

        $tipos_tickets = ['Pregunta', 'Incidente', 'Problema', 'Tarea'];

        $canales = CanalIngreso::select('id', 'nombre')
            ->orderBy('nombre')
            ->pluck('nombre', 'id');

        $availableFields = [
            'tickets.tipo_ticket'        => 'Ticket > Tipo de ticket',
            'tickets.id_agente_asignado' => 'Ticket > Agente asignado',
            'tickets.id_ciudadano'       => 'Ticket > Solicitante',
            'tickets.descripcion'        => 'Ticket > Descripción',
            'tickets.canal_ingreso'      => 'Ticket > Canal de ingreso',
            'tickets.estado'             => 'Ticket > Estado',
            'tickets.prioridad'          => 'Ticket > Prioridad',
        ];

        $availableOperators = [
            'is'           => 'Es',
            'is_not'       => 'No es',
            'contains'     => 'Contiene',
            'not_contains' => 'No contiene',
            'present'      => 'Está presente',
            'not_present'  => 'No está presente',
        ];

        $availableColumns = [
            'id'                 => 'ID',
            'estado'             => 'Estado del ticket',
            'asunto'             => 'Asunto',
            'id_ciudadano'       => 'Solicitante',
            'id_agente_asignado' => 'Agente asignado',
            'created_at'         => 'Fecha de solicitud',
            'prioridad'          => 'Prioridad',
            'tipo_ticket'        => 'Tipo',
        ];

        $allConditions = $ticketView->conditions
            ->where('match_type', 'all')
            ->values()
            ->map(fn($c) => [
                'field'    => $c->field,
                'operator' => $c->operator,
                'value'    => $c->value ?? '',
            ]);

        $anyConditions = $ticketView->conditions
            ->where('match_type', 'any')
            ->values()
            ->map(fn($c) => [
                'field'    => $c->field,
                'operator' => $c->operator,
                'value'    => $c->value ?? '',
            ]);

        $existingColumns = $ticketView->columns
            ->map(fn($c) => ['column_key' => $c->column_key]);

        return view('pages.ticket-views.edit', compact(
            'ticketView',
            'availableFields',
            'availableOperators',
            'availableColumns',
            'allConditions',
            'anyConditions',
            'existingColumns',
            'ciudadanos',
            'agentes',
            'prioridades',
            'estados',
            'tipos_tickets',
            'canales'
        ));
    }

    public function update(TicketViewStore $request, TicketView $ticketView)
    {
        $ticketView->update([
            'title'       => $request->title,
            'description' => $request->description,
            'visibility'  => $request->visibility,
            'active'      => $request->boolean('active'),
        ]);

        $this->syncConditions($ticketView, $request->conditions ?? []);
        $this->syncColumns($ticketView, $request->columns ?? []);
        //$this->syncSorts($ticketView, $request->sorts ?? []);

        return redirect()->route('ticket-views.index') // 
            ->with('success', 'Vista actualizada correctamente.');
    }

    public function destroy(TicketView $ticketView)
    {
        $ticketView->delete();

        return redirect()->route('ticket-views.index')
            ->with('success', 'Vista eliminada correctamente.');
    }

    public function restore(int $id)
    {
        $view = TicketView::withTrashed()->findOrFail($id);
        $view->restore();

        return redirect()->route('ticket-views.index')
            ->with('success', 'Vista restaurada correctamente.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'views'            => 'required|array',
            'views.*.id'       => 'required|integer|exists:ticket_views,id',
            'views.*.position' => 'required|integer|min:0',
        ]);

        foreach ($request->views as $item) {
            TicketView::where('id', $item['id'])->update(['position' => $item['position']]);
        }

        return response()->json(['message' => 'Orden actualizado correctamente.']);
    }

    public function toggle(TicketView $ticketView)
    {
        $ticketView->update(['active' => !$ticketView->active]);

        return redirect()->route('ticket-views.index')
            ->with('success', $ticketView->active ? 'Vista activada.' : 'Vista desactivada.');
    }

    public function indexForAgent()
    {
        $views = TicketView::active()
            ->visibleFor(auth()->user())
            ->ordered()
            ->with(['conditions', 'columns', 'sorts'])
            ->get();

        return view('pages.ticket-views.index', compact('views'));
    }


    private function syncConditions(TicketView $view, array $conditions): void
    {
        $view->conditions()->delete();

        foreach ($conditions as $condition) {
            $view->conditions()->create([
                'match_type' => $condition['match_type'],
                'field'      => $condition['field'],
                'operator'   => $condition['operator'],
                'value'      => $condition['value'] ?? null,
            ]);
        }
    }

    private function syncColumns(TicketView $view, array $columns): void
    {
        $view->columns()->delete();

        foreach ($columns as $index => $column) {
            $view->columns()->create([
                'column_key' => $column['column_key'],
                'label'      => $column['label'] ?? null,
                'position'   => $column['position'] ?? $index,
            ]);
        }
    }

    //No se usa por cambio de agrupar por y ordenar por no usados
    private function syncSorts(TicketView $view, array $sorts): void
    {
        $view->sorts()->delete();

        foreach ($sorts as $sort) {
            $view->sorts()->create([
                'sort_type'  => $sort['sort_type'],
                'column_key' => $sort['column_key'],
                'direction'  => $sort['direction'] ?? 'asc',
            ]);
        }
    }
}
