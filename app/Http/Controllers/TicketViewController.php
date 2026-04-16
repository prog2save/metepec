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
use App\Models\DireccionMunicipal;

class TicketViewController extends Controller
{
    public function index(Request $request)
    {
        $query = TicketView::with(['creator', 'conditions', 'columns', 'sorts'])
            ->ordered();

        if ($request->filled('estado')) {
            $query->where('active', $request->estado === 'activo');
        }

        if ($request->filled('visibilidad')) {
            $query->where('visibility', $request->visibilidad);
        }

        $views = $query->paginate(20)->withQueryString();

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

        $direcciones = DireccionMunicipal::select('id', 'nombre_direccion', 'estatus')
            ->where('estatus', true)
            ->orderBy('nombre_direccion')
            ->get()
            ->map(fn($d) => [
                'id'     => $d->id,
                'nombre_direccion' => $d->nombre_direccion,
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
            'tickets.id_canal'        => 'Ticket > Canal de ingreso',
            'tickets.estado'               => 'Ticket > Estado',
            'tickets.prioridad'            => 'Ticket > Prioridad',
            'tickets.id_direccion_municipal'  => 'Ticket > Dirección Municipal'
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
            'descripciones',
            'direcciones'
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

        return view('pages.ticket-views.edit', compact('ticketView'));
    }

    public function edit(TicketView $ticketView)
    {
        $ticketView->load(['conditions', 'columns', 'sorts']);

        $direcciones = DireccionMunicipal::select('id', 'nombre_direccion', 'estatus')
            ->where('estatus', true)
            ->orderBy('nombre_direccion')
            ->get()
            ->map(fn($d) => [
                'id'     => $d->id,
                'nombre_direccion' => $d->nombre_direccion,
            ]);

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
            'tickets.id_canal'      => 'Ticket > Canal de ingreso',
            'tickets.estado'             => 'Ticket > Estado',
            'tickets.prioridad'          => 'Ticket > Prioridad',
            'tickets.id_direccion_municipal'  => 'Ticket > Dirección Municipal'
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
            'canales',
            'direcciones'
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



    private function syncConditions(TicketView $view, array $conditions): void
    {
        $view->conditions()->delete();

        $operatorsThatNeedValue = [
            'is',
            'is_not',
            'contains',
            'not_contains',
            'less_than',
            'greater_than',
            'is_before',
            'is_after',
        ];

        foreach ($conditions as $condition) {
            $field = $condition['field'] ?? null;
            $operator = $condition['operator'] ?? null;
            $value = $condition['value'] ?? null;
            $matchType = $condition['match_type'] ?? null;

            $hasAnyData = filled($field) || filled($operator) || filled($value);

            if (! $hasAnyData) {
                continue;
            }

            if (blank($matchType) || blank($field) || blank($operator)) {
                continue;
            }

            if (in_array($operator, $operatorsThatNeedValue, true) && blank($value)) {
                continue;
            }

            $view->conditions()->create([
                'match_type' => $matchType,
                'field'      => $field,
                'operator'   => $operator,
                'value'      => filled($value) ? $value : null,
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

    //Carga las vistas disponibles para el agente pero sin abrirlas
    public function indexForAgent()
    {
        $views = TicketView::active()
            ->visibleFor(auth()->user())
            ->ordered()
            ->with(['conditions', 'columns'])
            ->get();

        return view('pages.ticket-views.index-agent', compact('views'));
    }

    public function showView(TicketView $ticketView)
    {
        //Verifica el acceso, solo mostrará las vistas que están puestas para todos los agentes
        if ($ticketView->visibility === 'only_me' && $ticketView->created_by !== auth()->id()) {
            abort(403);
        }

        //Cargar las vistas     
        $views = TicketView::active()
            ->visibleFor(auth()->user())
            ->ordered()
            ->with(['conditions', 'columns'])
            ->get();

        $ticketView->load(['conditions', 'columns']);

        $query = Ticket::query()->with(['ciudadano', 'agente']);

        foreach ($ticketView->conditions->where('match_type', 'all') as $condition) {
            $this->applyCondition($query, $condition);
        }

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

        $tickets = $query->get();
        $columns = $ticketView->columns->sortBy('position');

        return view('pages.ticket-views.index-agent', compact(
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
}
