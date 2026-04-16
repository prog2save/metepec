<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;

class DashboardController extends Controller
{
    public function index()
    {
        $tickets        = Ticket::latest()->paginate(10);
        $totalAsignados = Ticket::count();
        $nuevos         = Ticket::where('estado', 'Nuevo')->count();
        $abiertos       = Ticket::where('estado', 'Abierto')->count();
        $pendientes     = Ticket::where('estado', 'Pendiente')->count();
        $resueltos      = Ticket::where('estado', 'Resuelto')->count();

        return view('pages.dashboard.dashboard', compact(
            'tickets',
            'totalAsignados',
            'abiertos',
            'pendientes',
            'resueltos',
            'nuevos'
        ));
    }
}
