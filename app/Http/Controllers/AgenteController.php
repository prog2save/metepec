<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CanalIngreso;

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
        $canales = CanalIngreso::orderBy('nombre')->get(); // para filtro

        return view('pages.agente.tickets.index', compact('tickets', 'canales'));
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
}
