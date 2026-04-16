<?php

namespace App\Http\Controllers;

use App\Models\Macro;
use App\Models\MacroAction;
use App\Models\Ciudadano;
use App\Models\DireccionMunicipal;
use App\Models\Servicio;
use App\Models\Usuario;
use App\Models\CanalIngreso;
use App\Models\EstadoTicket;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MacroController extends Controller
{
    public function index(): View
    {
        $macros = Macro::with('createdBy')
            ->withCount('actions')
            ->orderByDesc('id')
            ->get();

        return view('pages.macros.index', compact('macros'));
    }

    public function create(): View
    {
        return view('pages.macros.create', $this->formData());
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'                => 'required|string|max:255',
            'description'         => 'nullable|string',
            'actions'             => 'required|array|min:1',
            'actions.*.field'     => 'required|string|in:' . implode(',', array_keys(Macro::FIELDS)),
            'actions.*.value'     => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $macro = Macro::create([
                'name'        => $request->name,
                'description' => $request->description,
                'active'   => true,
                'created_by'  => Auth::id(),
            ]);

            $this->syncActions($macro, $request->actions);
        });

        return redirect()
            ->route('macros.index')
            ->with('success', 'Macro creado exitosamente.');
    }

    public function edit(Macro $macro): View
    {
        $macro->load('actions');

        return view('pages.macros.edit', array_merge(
            $this->formData(),
            compact('macro')
        ));
    }

    public function update(Request $request, Macro $macro): RedirectResponse
    {
        $request->validate([
            'name'                => 'required|string|max:255',
            'description'         => 'nullable|string',
            'actions'             => 'required|array|min:1',
            'actions.*.field'     => 'required|string|in:' . implode(',', array_keys(Macro::FIELDS)),
            'actions.*.value'     => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $macro) {
            $macro->update([
                'name'        => $request->name,
                'description' => $request->description,
            ]);

            $this->syncActions($macro, $request->actions);
        });

        return redirect()
            ->route('macros.index')
            ->with('success', 'Macro actualizado exitosamente.');
    }

    public function destroy(Macro $macro): RedirectResponse
    {
        $macro->delete(); 

        return redirect()
            ->route('macros.index')
            ->with('success', 'Macro eliminado exitosamente.');
    }

    // Activa o desactiva un macro sin entrar al edit
    public function toggleActive(Macro $macro): RedirectResponse
    {
        $macro->update(['active' => !$macro->active]);

        return back()->with('success', $macro->active ? 'Macro activado.' : 'Macro desactivado.');
    }

    // Datos compartidos entre create y edit
    private function formData(): array
    {
        return [
            'fields'     => Macro::FIELDS,
            'ciudadanos' => Ciudadano::select('id', 'nombre', 'apellido_paterno', 'apellido_materno')
                                ->orderBy('nombre')->get(),
            'agentes'    => Usuario::select('id', 'nombre', 'apellido')
                                ->orderBy('nombre')->get(),
            'direcciones'=> DireccionMunicipal::select('id', 'nombre_direccion')
                                ->where('estatus', true)->orderBy('nombre_direccion')->get(),
            'servicios'  => Servicio::select('id', 'nombre_servicio', 'id_direccion_municipal')
                                ->where('activo', true)->orderBy('nombre_servicio')->get(),
            'canales'    => CanalIngreso::select('id', 'nombre')->orderBy('nombre')->get(),
            'estados'    => EstadoTicket::select('id', 'nombre_agente')
                                ->where('activo', true)->orderBy('nombre_agente')->get(),
        ];
    }

    // Elimina las acciones anteriores y crea las nuevas
    private function syncActions(Macro $macro, array $actions): void
    {
        $macro->actions()->delete();

        $records = collect($actions)
            ->filter(fn($a) => !empty($a['field']))
            ->values()
            ->map(fn($a, $i) => [
                'macro_id'   => $macro->id,
                'field'      => $a['field'],
                'value'      => $a['value'] ?? null,
                'sort_order' => $i,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        MacroAction::insert($records->toArray());
    }
}