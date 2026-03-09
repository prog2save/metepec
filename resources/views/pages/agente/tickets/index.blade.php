@extends('layouts.app')

@section('content')

<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">Mis tickets</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Listado completo de tickets que tienes asignados.
        </p>
    </div>
    <a href="{{ route('agente.tickets.create') }}"
        class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600 transition-colors">
        + Nuevo ticket
    </a>
</div>

<div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

    {{-- Filtros --}}
    <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-gray-800">

        {{-- Filtro Estado --}}
        <div x-data="{ open: false }" class="relative">
            <button
                @click="open = !open"
                class="inline-flex items-center gap-2 rounded-lg border px-4 py-2 text-sm font-medium transition
            {{ request('estado') ? 'border-brand-500 text-brand-600 dark:text-brand-400' : 'border-gray-200 text-gray-500 dark:border-gray-700 dark:text-gray-400' }}
            bg-white dark:bg-white/[0.03]">
                Estado
                @if(request('estado'))
                <span class="text-xs font-semibold">· {{ request('estado') }}</span>
                @endif
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                </svg>
            </button>

            <div x-show="open" @click.outside="open = false" x-transition
                class="absolute left-0 z-50 mt-1 w-44 rounded-xl border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-900">
                <ul class="py-1 text-sm text-gray-700 dark:text-gray-300">
                    @foreach(['Nuevo', 'Abierto', 'Pendiente', 'Resuelto'] as $estado)
                    <li>
                        <a href="{{ request()->fullUrlWithQuery(['estado' => $estado, 'page' => 1]) }}"
                            class="flex items-center px-4 py-2 hover:bg-gray-50 dark:hover:bg-white/[0.05]
                        {{ request('estado') === $estado ? 'text-brand-600 font-medium bg-brand-50 dark:bg-brand-900/20 dark:text-brand-400' : '' }}">
                            {{ $estado }}
                        </a>
                    </li>
                    @endforeach
                    @if(request('estado'))
                    <li class="border-t border-gray-100 dark:border-gray-700">
                        <a href="{{ request()->fullUrlWithQuery(['estado' => null, 'page' => 1]) }}"
                            class="flex items-center px-4 py-2 text-brand-500 hover:bg-gray-50 dark:hover:bg-white/[0.05]">
                            Borrar filtro
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>

        {{-- Filtro Canal --}}
        <div x-data="{ open: false }" class="relative">
            <button
                @click="open = !open"
                class="inline-flex items-center gap-2 rounded-lg border px-4 py-2 text-sm font-medium transition
            {{ request('canal') ? 'border-brand-500 text-brand-600 dark:text-brand-400' : 'border-gray-200 text-gray-500 dark:border-gray-700 dark:text-gray-400' }}
            bg-white dark:bg-white/[0.03]">
                Canal
                @if(request('canal'))
                <span class="text-xs font-semibold">· {{ $canales->firstWhere('id', request('canal'))?->nombre }}</span>
                @endif
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                </svg>
            </button>

            <div x-show="open" @click.outside="open = false" x-transition
                class="absolute left-0 z-50 mt-1 w-44 rounded-xl border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-900">
                <ul class="py-1 text-sm text-gray-700 dark:text-gray-300">
                    @forelse($canales as $canal)
                    <li>
                        <a href="{{ request()->fullUrlWithQuery(['canal' => $canal->id, 'page' => 1]) }}"
                            class="flex items-center px-4 py-2 hover:bg-gray-50 dark:hover:bg-white/[0.05]
                        {{ request('canal') == $canal->id ? 'text-brand-600 font-medium bg-brand-50 dark:bg-brand-900/20 dark:text-brand-400' : '' }}">
                            {{ $canal->nombre }}
                        </a>
                    </li>
                    @empty
                    <li class="px-4 py-2 text-gray-400 text-xs italic">Sin canales registrados</li>
                    @endforelse
                    @if(request('canal'))
                    <li class="border-t border-gray-100 dark:border-gray-700">
                        <a href="{{ request()->fullUrlWithQuery(['canal' => null, 'page' => 1]) }}"
                            class="flex items-center px-4 py-2 text-brand-500 hover:bg-gray-50 dark:hover:bg-white/[0.05]">
                            Borrar filtro
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>

        {{-- Limpiar todos --}}
        @if(request('estado') || request('canal'))
        <a href="{{ route('agente.tickets.index') }}"
            class="text-xs text-gray-400 hover:text-red-500 dark:text-gray-500 dark:hover:text-red-400 transition">
            Limpiar todos los filtros
        </a>
        @endif

    </div>

    <div class="max-w-full overflow-x-auto custom-scrollbar">

        <table id="tabla-tickets" class="w-full min-w-[1102px] p-2">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800">
                    <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400 min-w-[55px]">ID</th>
                    <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Estado</th>
                    <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Asunto</th>
                    <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Solicitante</th>
                    <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400 min-w-[100px]">Prioridad</th>
                    <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400 min-w-[160px]">Fecha de creación</th>
                    <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400 min-w-[150px]">Fecha resolución</th>
                    <th class="px-4 py-3 font-normal text-center text-gray-500 text-theme-sm dark:text-gray-400">Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tickets as $t)
                <tr class="border-b border-gray-100 dark:border-gray-800">

                    {{-- ID --}}
                    <td class="px-4 sm:px-6 py-3.5">
                        <p class="text-gray-700 text-theme-sm dark:text-gray-400">
                            #{{ $t->id }}</p>
                    </td>

                    {{-- Estado --}}
                    <td class="px-4 sm:px-6 py-3.5">
                        <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-medium
                                @if($t->estado === 'Resuelto') bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-800
                                @elseif($t->estado === 'Pendiente') bg-gray-100 text-gray-700 border-gray-300 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700
                                @elseif($t->estado === 'Nuevo') bg-orange-50 text-orange-700 border-orange-200 dark:bg-orange-900/30 dark:text-orange-300 dark:border-orange-800
                                @elseif($t->estado === 'Abierto') bg-red-50 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800
                                @endif">
                            {{ $t->estado }}
                        </span>
                    </td>

                    {{-- Asunto --}}
                    <td class="px-4 sm:px-6 py-3.5">
                        <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{ $t->asunto }}</p>
                    </td>

                    {{-- Solicitante --}}
                    <td class="px-4 sm:px-6 py-3.5">
                        @if ($t->ciudadano)
                        <p class="text-gray-700 text-theme-sm dark:text-gray-400">
                            {{ $t->ciudadano->nombre }} {{ $t->ciudadano->apellido_paterno }}
                        </p>
                        @else
                        <p class="italic text-gray-500 text-theme-sm dark:text-gray-600">Sin asignar</p>
                        @endif
                    </td>

                    {{-- Prioridad --}}
                    <td class="px-4 sm:px-6 py-3.5">
                        <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{ $t->prioridad ?? '—' }}</p>
                    </td>

                    {{-- Fecha creación --}}
                    <td class="px-4 sm:px-6 py-3.5">
                        <p class="text-gray-700 text-theme-sm dark:text-gray-400">
                            {{ $t->created_at->format('d/m/Y') }}
                        </p>
                    </td>

                    {{-- Fecha resolución --}}
                    <td class="px-4 sm:px-6 py-3.5">
                        <p class="text-gray-700 text-theme-sm dark:text-gray-400">
                            {{ optional($t->fecha_resolucion)->format('d/m/Y') ?? '—' }}
                        </p>
                    </td>

                    {{-- Acción --}}
                    <td class="px-4 py-2 text-center">
                        <form action="{{ route('agente.tickets.resolver', $t->id) }}" method="POST" class="inline-flex justify-center">
                            @csrf
                            @method('PUT')
                            @if ($t->estado !== 'Resuelto')
                            <button
                                type="submit"
                                onclick="return confirm('¿Marcar este ticket como resuelto?')"
                                class="inline-flex items-center gap-2 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-sm font-medium text-emerald-700 transition-all duration-200 hover:bg-emerald-100 hover:border-emerald-300 focus:outline-none focus:ring-3 focus:ring-emerald-500/20 active:scale-[0.98] dark:border-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300 dark:hover:bg-emerald-900/50">
                                Completar
                            </button>
                            @else
                            <button
                                type="button"
                                disabled
                                class="inline-flex items-center rounded-lg border border-amber-200 bg-amber-50 px-4 py-2.5 text-sm font-medium text-amber-700 cursor-not-allowed opacity-80 dark:border-amber-800 dark:bg-amber-900/30 dark:text-amber-300">
                                Resuelto
                            </button>
                            @endif
                        </form>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-10 text-center text-gray-400 text-theme-sm dark:text-gray-600">
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
            paging: true,
            perPage: 30,
            columns: [{
                    select: [7, 8 , 9],
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