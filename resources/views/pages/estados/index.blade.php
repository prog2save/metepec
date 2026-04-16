@extends('layouts.app')

@section('title', 'Estados de los tickets')

@section('content')
<x-common.page-breadcrumb pageTitle="Estados de los tickets" />

<div class="space-y-6">

    @if (session('success'))
    <x-ui.alert variant="success" title="{{ session('success') }}" message="" :showLink="false" linkHref="/" linkText="" />
    @endif

    @if (session('error'))
    <x-ui.alert variant="error" title="{{ session('error') }}" message="" :showLink="false" linkHref="/" linkText="" />
    @endif

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">
                Estados de los tickets
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 max-w-xl">
                Vea los estados de los tickets y cree estados personalizados para una mejor
                alineación del flujo de trabajo y una mejor comunicación con los clientes.
            </p>
        </div>
        <div class="shrink-0">
            <a href="{{ route('estados.create') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 focus:outline-none focus:ring-3 focus:ring-brand-500/20 dark:bg-brand-500 dark:hover:bg-brand-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Crear estado del ticket
            </a>
        </div>
    </div>
    <!--
    {{-- Search --}}
    <div class="flex items-center gap-3">
        <div class="relative w-full max-w-sm">
            <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-400 dark:text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                </svg>
            </span>
            <input
                type="text"
                id="searchInput"
                placeholder="Buscar"
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent pl-9 pr-4 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
            />
        </div>
    </div>
-->

    {{-- Table --}}
    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900">
        <table class="w-full text-sm" id="estadosTable">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400 w-1/3">
                        Nombre (vista de agente)
                    </th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400 w-1/3">
                        Descripción (vista de agente)
                    </th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Nombre (vista de usuario final)
                    </th>
                    <th class="px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400 w-12">
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800" id="estadosBody">

                @forelse ($estados->groupBy('categoria') as $categoria => $grupo)

                {{-- Fila de categoría --}}
                <tr class="bg-gray-50 dark:bg-gray-800/60">
                    <td colspan="4" class="px-6 py-2">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">
                                {{ $categoria }}
                            </span>
                            <span class="text-xs text-gray-400 dark:text-gray-500 font-normal">
                                @switch($categoria)
                                    @case('Abierto') Tickets en los que el equipo de soporte puede trabajar @break
                                    @case('Pendiente') El equipo de soporte está esperando la respuesta del solicitante @break
                                    @case('Resuelto') El ticket ha sido resuelto @break
                                    @default {{ $categoria }}
                                @endswitch
                            </span>
                        </div>
                    </td>
                </tr>

                {{-- Filas de estados de esta categoría --}}
                @foreach ($grupo as $estado)
                <tr class="group estado-row hover:bg-gray-50 dark:hover:bg-gray-800/40 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <span class="font-medium text-gray-800 dark:text-white/90 estado-nombre">
                                {{ $estado->nombre_agente }}
                            </span>
                            @if (!$estado->activo)
                            <span class="inline-flex items-center rounded-full bg-gray-100 dark:bg-gray-700 px-2 py-0.5 text-xs font-medium text-gray-500 dark:text-gray-400">
                                Inactivo
                            </span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-500 dark:text-gray-400 estado-descripcion">
                        {{ $estado->descripcion_agente }}
                    </td>
                    <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                        {{ $estado->nombre_usuario ?? '—' }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        {{-- Dropdown menú --}}
                        <div class="relative inline-block" x-data="{ open: false }">
                            <button
                                @click="open = !open"
                                @click.outside="open = false"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-gray-300 focus:outline-none transition-colors"
                                title="Opciones">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="5" r="1.5"/>
                                    <circle cx="12" cy="12" r="1.5"/>
                                    <circle cx="12" cy="19" r="1.5"/>
                                </svg>
                            </button>

                            <div
                                x-show="open"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                class="absolute right-0 z-10 mt-1 w-36 rounded-lg border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800"
                                style="display:none;">
                                <a href="{{ route('estados.edit', $estado->id) }}"
                                    class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700 rounded-t-lg transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Editar
                                </a>
                                <form action="{{ route('estados.destroy', $estado->id) }}" method="POST"
                                    onsubmit="return confirm('¿Estás seguro de que deseas eliminar este estado?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="flex w-full items-center gap-2 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-500/10 rounded-b-lg transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach

                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-400 dark:text-gray-500">
                        No hay estados registrados.
                        <a href="{{ route('estados.create') }}" class="ml-1 text-brand-500 hover:underline">Crear el primero</a>
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>

        {{-- Sin resultados de búsqueda --}}
        <div id="noResults" class="hidden px-6 py-12 text-center text-sm text-gray-400 dark:text-gray-500">
            No se encontraron estados que coincidan con la búsqueda.
        </div>
    </div>

</div>

@push('scripts')
<!--
<script>
    // Búsqueda en tiempo real
    document.getElementById('searchInput').addEventListener('input', function () {
        const query = this.value.toLowerCase().trim();
        const rows = document.querySelectorAll('.estado-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const nombre = row.querySelector('.estado-nombre')?.textContent.toLowerCase() ?? '';
            const descripcion = row.querySelector('.estado-descripcion')?.textContent.toLowerCase() ?? '';
            const matches = nombre.includes(query) || descripcion.includes(query);
            row.style.display = matches ? '' : 'none';
            if (matches) visibleCount++;
        });

        document.getElementById('noResults').classList.toggle('hidden', visibleCount > 0 || query === '');
    });
</script>
-->
@endpush

@endsection