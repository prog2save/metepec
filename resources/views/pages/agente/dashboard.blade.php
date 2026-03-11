@extends('layouts.app')

@section('content')

{{-- Header --}}
<div class="mb-6">
    <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">
        Bienvenido, {{ auth()->user()->nombre }}
    </h1>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
        Panel de agente — gestiona los tickets que tienes asignados.
    </p>
</div>

{{-- Tarjetas de resumen --}}
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5 mb-6">

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <span class="text-sm text-gray-500 dark:text-gray-400">Tickets asignados</span>
        <p class="mt-3 text-3xl font-bold text-gray-800 dark:text-white/90">{{ $totalAsignados }}</p>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <span class="text-sm text-gray-500 dark:text-gray-400">Abiertos</span>
        <p class="mt-3 text-3xl font-bold text-gray-800 dark:text-white/90">{{ $abiertos }}</p>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <span class="text-sm text-gray-500 dark:text-gray-400">Pendientes</span>
        <p class="mt-3 text-3xl font-bold text-gray-800 dark:text-white/90">{{ $pendientes }}</p>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <span class="text-sm text-gray-500 dark:text-gray-400">Resueltos</span>
        <p class="mt-3 text-3xl font-bold text-gray-800 dark:text-white/90">{{ $resueltos }}</p>
    </div>

</div>

{{-- Tabla de tickets asignados --}}
<div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-800">
        <h2 class="text-base font-semibold text-gray-800 dark:text-white/90">Mis tickets asignados</h2>
        <a href="{{ route('agente.tickets.create') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600 transition-colors">
            + Nuevo ticket
        </a>
    </div>

    <div class="max-w-full overflow-x-auto">
        <table id="tabla-tickets" class="w-full min-w-[900px] p-2">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800">
                    <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400 min-w-[55px]">ID</th>
                    <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Estado</th>
                    <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Asunto</th>
                    <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Solicitante</th>
                    <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400 min-w-[100px]">Prioridad</th>
                    <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400 min-w-[150px]">Fecha de creación</th>
                    <th class="px-4 py-3 font-normal text-center text-gray-500 text-theme-sm dark:text-gray-400">Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tickets as $t)
                <tr class="border-b border-gray-100 dark:border-gray-800">

                    {{-- ID --}}
                    <td class="px-4 py-3.5">
                        <p class="text-gray-700 text-theme-sm dark:text-gray-400">#{{ $t->id }}</p>
                    </td>

                    {{-- Estado --}}
                    <td class="px-4 py-3.5">
                        <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-medium
                                @if($t->estado === 'Resuelto') bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-800
                                @elseif($t->estado === 'Pendiente') bg-gray-100 text-gray-700 border-gray-300 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700
                                @elseif($t->estado === 'Nuevo') bg-orange-50 text-orange-700 border-orange-200 dark:bg-orange-900/30 dark:text-orange-300 dark:border-orange-800
                                @elseif($t->estado === 'Abierto') bg-red-50 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800
                                @else bg-purple-50 text-purple-700 border-purple-200 dark:bg-purple-900/30 dark:text-purple-300 dark:border-purple-800
                                @endif">
                            {{ $t->estado }}
                        </span>
                    </td>

                    {{-- Asunto --}}
                    <td class="px-4 py-3.5">
                        <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{ $t->asunto }}</p>
                    </td>

                    {{-- Solicitante --}}
                    <td class="px-4 py-3.5">
                        @if ($t->ciudadano)
                        <p class="text-gray-700 text-theme-sm dark:text-gray-400">
                            {{ $t->ciudadano->nombre }} {{ $t->ciudadano->apellido_paterno }}
                        </p>
                        @else
                        <p class="italic text-gray-400 text-theme-sm dark:text-gray-600">Sin asignar</p>
                        @endif
                    </td>

                    {{-- Prioridad --}}
                    <td class="px-4 py-3.5">
                        <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{ $t->prioridad ?? '—' }}</p>
                    </td>

                    {{-- Fecha --}}
                    <td class="px-4 py-3.5">
                        <p class="text-gray-700 text-theme-sm dark:text-gray-400">
                            {{ $t->created_at->format('d/m/Y') }}
                        </p>
                    </td>

                    {{-- Acción: marcar como resuelto --}}
                    <td class="px-4 py-3.5 text-center">
                        <form action="{{ route('agente.tickets.resolver', $t->id) }}" method="POST" class="inline-flex">
                            @csrf
                            @method('PUT')
                            @if ($t->estado !== 'Resuelto')
                            <button
                                type="submit"
                                onclick="return confirm('¿Marcar este ticket como resuelto?')"
                                class="inline-flex items-center gap-2 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-medium text-emerald-700 transition-all duration-200 hover:bg-emerald-100 hover:border-emerald-300 focus:outline-none active:scale-[0.98] dark:border-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300 dark:hover:bg-emerald-900/50">
                                Completar
                            </button>
                            @else
                            <button
                                type="button"
                                disabled
                                class="inline-flex items-center rounded-lg border border-amber-200 bg-amber-50 px-4 py-2 text-sm font-medium text-amber-700 cursor-not-allowed opacity-80 dark:border-amber-800 dark:bg-amber-900/30 dark:text-amber-300">
                                Resuelto
                            </button>
                            @endif
                        </form>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-10 text-center text-gray-400 text-theme-sm dark:text-gray-600">
                        No tienes tickets asignados por el momento.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>


</div>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        new DataTable('#tabla-tickets', {
            searchable: true,
            perPage: 10,
            columns: [{
                    select: [6],
                    sortable: false
                } // Acciones y Completar sin ordenamiento
            ],
            labels: {
                placeholder: "Buscar tickets...",
                perPage: "Tickets por página",
                noRows: "No se encontraron tickets",
                info: "Mostrando {start} a {end} de {rows} tickets"
            }
        });
    });
</script>