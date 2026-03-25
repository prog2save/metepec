@extends('layouts.app')

@section('content')

<style>
    .dataTable-wrapper,
    .dataTable-container,
    .dataTable-table {
        overflow: visible !important;
    }
</style>

<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">Vistas</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Administra las vistas que verán los agentes en su panel.
        </p>
    </div>
    <a href="{{ route('ticket-views.create') }}"
        class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600 transition-colors">
        + Crear vista
    </a>
</div>

@if (session('success'))
<x-ui.alert variant="success" title="{{ session('success') }}" message="" :showLink="false" linkHref="/" linkText="" />
@endif

<div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

    {{-- Filtros --}}
    <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-gray-800">

        {{-- Filtro Estado --}}
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open"
                class="inline-flex items-center gap-2 rounded-lg border px-4 py-2 text-sm font-medium transition
                {{ request('estado') ? 'border-brand-500 text-brand-600 dark:text-brand-400' : 'border-gray-200 text-gray-500 dark:border-gray-700 dark:text-gray-400' }}
                bg-white dark:bg-white/[0.03]">
                Estado
                @if(request('estado'))
                <span class="text-xs font-semibold">· {{ request('estado') === 'activo' ? 'Activo' : 'Inactivo' }}</span>
                @endif
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                </svg>
            </button>
            <div x-show="open" @click.outside="open = false" x-transition
                class="absolute left-0 z-50 mt-1 w-44 rounded-xl border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-900">
                <ul class="py-1 text-sm text-gray-700 dark:text-gray-300">
                    @foreach(['activo' => 'Activo', 'inactivo' => 'Inactivo'] as $val => $label)
                    <li>
                        <a href="{{ request()->fullUrlWithQuery(['estado' => $val, 'page' => 1]) }}"
                            class="flex items-center px-4 py-2 hover:bg-gray-50 dark:hover:bg-white/[0.05]
                            {{ request('estado') === $val ? 'text-brand-600 font-medium bg-brand-50 dark:bg-brand-900/20 dark:text-brand-400' : '' }}">
                            {{ $label }}
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

        {{-- Filtro Disponible para --}}
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open"
                class="inline-flex items-center gap-2 rounded-lg border px-4 py-2 text-sm font-medium transition
                {{ request('visibilidad') ? 'border-brand-500 text-brand-600 dark:text-brand-400' : 'border-gray-200 text-gray-500 dark:border-gray-700 dark:text-gray-400' }}
                bg-white dark:bg-white/[0.03]">
                Disponible para
                @if(request('visibilidad'))
                <span class="text-xs font-semibold">· {{ request('visibilidad') === 'all_agents' ? 'Todos' : 'Solo yo' }}</span>
                @endif
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                </svg>
            </button>
            <div x-show="open" @click.outside="open = false" x-transition
                class="absolute left-0 z-50 mt-1 w-44 rounded-xl border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-900">
                <ul class="py-1 text-sm text-gray-700 dark:text-gray-300">
                    @foreach(['all_agents' => 'Todos los agentes', 'only_me' => 'Solo yo'] as $val => $label)
                    <li>
                        <a href="{{ request()->fullUrlWithQuery(['visibilidad' => $val, 'page' => 1]) }}"
                            class="flex items-center px-4 py-2 hover:bg-gray-50 dark:hover:bg-white/[0.05]
                            {{ request('visibilidad') === $val ? 'text-brand-600 font-medium bg-brand-50 dark:bg-brand-900/20 dark:text-brand-400' : '' }}">
                            {{ $label }}
                        </a>
                    </li>
                    @endforeach
                    @if(request('visibilidad'))
                    <li class="border-t border-gray-100 dark:border-gray-700">
                        <a href="{{ request()->fullUrlWithQuery(['visibilidad' => null, 'page' => 1]) }}"
                            class="flex items-center px-4 py-2 text-brand-500 hover:bg-gray-50 dark:hover:bg-white/[0.05]">
                            Borrar filtro
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>

        @if(request('estado') || request('visibilidad'))
        <a href="{{ route('ticket-views.index') }}"
            class="text-xs text-gray-400 hover:text-red-500 dark:text-gray-500 dark:hover:text-red-400 transition">
            Limpiar todos los filtros
        </a>
        @endif

    </div>

    {{-- Tabla --}}
    <div class="max-w-full p-3">
        <table id="tabla-vistas" class="w-full min-w-[900px] p-2">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800">
                    <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Nombre</th>
                    <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Estado</th>
                    <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Disponible para</th>
                    <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Fecha de creación</th>
                    <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Última actualización</th>
                    <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Creado por</th>
                    <th class="px-4 py-3 font-normal text-center text-gray-500 text-theme-sm dark:text-gray-400">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($views as $view)
                <tr class="border-b border-gray-100 dark:border-gray-800">

                    {{-- Nombre --}}
                    <td class="px-4 py-3.5">
                        <span
                            class="text-theme-sm font-medium text-gray-700 text-theme-sm dark:text-gray-400">
                            {{ $view->title }}
                        </span>
                    </td>

                    {{-- Estado --}}
                    <td class="px-4 py-3.5">
                        <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-medium
                            {{ $view->active
                                ? 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-300 dark:border-emerald-800'
                                : 'bg-gray-100 text-gray-600 border-gray-300 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700' }}">
                            {{ $view->active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>

                    {{-- Disponible para --}}
                    <td class="px-4 py-3.5">
                        <p class="text-gray-700 text-theme-sm dark:text-gray-400">
                            {{ $view->visibility === 'all_agents' ? 'Todos los agentes' : 'Solo yo' }}
                        </p>
                    </td>

                    {{-- Fecha creación --}}
                    <td class="px-4 py-3.5">
                        <p class="text-gray-700 text-theme-sm dark:text-gray-400">
                            {{ $view->created_at->format('d/m/Y') }}
                        </p>
                    </td>

                    {{-- Última actualización --}}
                    <td class="px-4 py-3.5">
                        <p class="text-gray-700 text-theme-sm dark:text-gray-400">
                            {{ $view->updated_at->format('d/m/Y') }}
                        </p>
                    </td>

                    <td class="px-4 py-3.5">
                        <p class="text-gray-700 text-theme-sm dark:text-gray-400">
                            {{ $view->creator->nombre ?? '-' }}
                        </p>
                    </td>

                    {{-- Acciones --}}
                    <td class="px-4 py-3.5">
                        <div x-data="{ open: false }" class="relative flex justify-center">

                            <button @click="open = !open"
                                class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-white/[0.05] dark:hover:text-gray-300 transition">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 13a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-7 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm14 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z" />
                                </svg>
                            </button>

                            <div x-show="open"
                                @click.outside="open = false"
                                x-transition
                                style="position: absolute; right: 0; top: 100%; z-index: 9999;"
                                class="w-44 rounded-xl border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-900">
                                <ul class="py-1 text-sm text-gray-700 dark:text-gray-300">

                                    {{-- Editar --}}
                                    <li>
                                        <a href="{{ route('ticket-views.edit', $view) }}"
                                            class="flex items-center gap-2 px-4 py-2 hover:bg-gray-50 dark:hover:bg-white/[0.05]">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Editar
                                        </a>
                                    </li>

                                    {{-- Activar / Desactivar --}}
                                    <li>
                                        <button type="button"
                                            @click="
                            fetch('{{ route('ticket-views.toggle', $view) }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify({ _method: 'PATCH' })
                            }).then(() => window.location.reload())
                        "
                                            class="flex w-full items-center gap-2 px-4 py-2 hover:bg-gray-50 dark:hover:bg-white/[0.05]">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                            </svg>
                                            {{ $view->active ? 'Desactivar' : 'Activar' }}
                                        </button>
                                    </li>

                                    {{-- Eliminar --}}
                                    <li class="border-t border-gray-100 dark:border-gray-700">
                                        <button type="button"
                                            @click="
                            if(confirm('¿Eliminar esta vista?')) {
                                fetch('{{ route('ticket-views.destroy', $view) }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                        'Content-Type': 'application/json',
                                    },
                                    body: JSON.stringify({ _method: 'DELETE' })
                                }).then(() => window.location.reload())
                            }
                        "
                                            class="flex w-full items-center gap-2 px-4 py-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Eliminar
                                        </button>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-10 text-center text-gray-400 text-theme-sm dark:text-gray-600">
                        No hay vistas creadas aún.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>


</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (document.querySelector('#tabla-vistas')) {
            new DataTable('#tabla-vistas', {
                responsive: true,
                pageLength: 10,
                columnDefs: [{
                    orderable: false,
                    targets: [6]
                }],
                language: {
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ vistas por página",
                    zeroRecords: "No se encontraron vistas",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ vistas",
                    infoEmpty: "Mostrando 0 a 0 de 0 vistas",
                    emptyTable: "No hay vistas disponibles",
                    paginate: {
                        next: "Siguiente",
                        previous: "Anterior"
                    }
                }
            });
        }

        document.querySelectorAll('.dataTable-wrapper, .dataTable-container, .dataTable-table').forEach(el => {
            el.style.overflow = 'visible';
        });
    });
</script>
@endsection