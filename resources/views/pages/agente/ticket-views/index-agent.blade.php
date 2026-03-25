@extends('layouts.app')

@section('content')

<div class="flex gap-0 -m-4 md:-m-6 min-h-screen">

    {{-- Panel izquierdo — lista de vistas --}}
    <div class="w-64 shrink-0 border-r border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-y-auto">

        <div class="px-4 py-4 border-b border-gray-100 dark:border-gray-800">
            <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Vistas</h2>
        </div>

        <nav class="py-2">
            @forelse($views as $view)
            <a href="{{ route('agente.ticket-views.show', $view) }}"
                class="flex items-center justify-between px-4 py-2 text-sm transition
                {{ request()->route('ticketView')?->id === $view->id
                    ? 'bg-brand-50 text-brand-600 font-medium dark:bg-brand-900/20 dark:text-brand-400'
                    : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-white/[0.03]' }}">
                <span class="truncate">{{ $view->title }}</span>
            </a>
            @empty
            <p class="px-4 py-3 text-xs text-gray-400 dark:text-gray-600 italic">
                No hay vistas disponibles.
            </p>
            @endforelse
        </nav>

    </div>

    {{-- Panel derecho — tickets de la vista seleccionada --}}
    <div class="flex-1 overflow-auto p-4 md:p-6">

        @if(isset($ticketView))

            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-semibold text-gray-800 dark:text-white/90">{{ $ticketView->title }}</h1>
                    @if($ticketView->description)
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $ticketView->description }}</p>
                    @endif
                </div>
                <a href="{{ route('agente.tickets.create') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600 transition">
                    + Nuevo ticket
                </a>
            </div>

            <div class="rounded-xl p-2 border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="max-w-full overflow-x-auto">
                    <table id="tabla-vista-tickets" class="w-full min-w-[700px]">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-gray-800">
                                @foreach($columns as $column)
                                <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">
                                    {{ $column->label ?? match($column->column_key) {
                                        'id'                 => 'ID',
                                        'estado'             => 'Estado',
                                        'asunto'             => 'Asunto',
                                        'id_ciudadano'       => 'Solicitante',
                                        'id_agente_asignado' => 'Agente asignado',
                                        'created_at'         => 'Fecha de solicitud',
                                        'prioridad'          => 'Prioridad',
                                        'tipo_ticket'        => 'Tipo',
                                        default              => $column->column_key,
                                    } }}
                                </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $t)
                            <tr class="border-b border-gray-100 dark:border-gray-800">

                                @foreach($columns as $column)
                                <td class="px-4 py-3.5">
                                    @switch($column->column_key)
                                        @case('id')
                                            <p class="text-gray-700 text-theme-sm dark:text-gray-400">#{{ $t->id }}</p>
                                            @break

                                        @case('estado')
                                            <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-medium
                                                @if($t->estado === 'Resuelto') bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-800
                                                @elseif($t->estado === 'Pendiente') bg-gray-100 text-gray-700 border-gray-300 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700
                                                @elseif($t->estado === 'Nuevo') bg-orange-50 text-orange-700 border-orange-200 dark:bg-orange-900/30 dark:text-orange-300 dark:border-orange-800
                                                @elseif($t->estado === 'Abierto') bg-red-50 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800
                                                @else bg-purple-50 text-purple-700 border-purple-200 dark:bg-purple-900/30 dark:text-purple-300 dark:border-purple-800
                                                @endif">
                                                {{ $t->estado }}
                                            </span>
                                            @break

                                        @case('asunto')
                                            <a href="{{ route('agente.tickets.show', $t->id) }}"
                                                class="text-theme-sm font-medium text-brand-500 hover:underline dark:text-brand-400">
                                                {{ $t->asunto }}
                                            </a>
                                            @break

                                        @case('id_ciudadano')
                                            <p class="text-gray-700 text-theme-sm dark:text-gray-400">
                                                {{ $t->ciudadano?->nombre ?? '—' }}
                                                {{ $t->ciudadano?->apellido_paterno ?? '' }}
                                            </p>
                                            @break

                                        @case('id_agente_asignado')
                                            <p class="text-gray-700 text-theme-sm dark:text-gray-400">
                                                {{ $t->agente?->nombre ?? '—' }}
                                                {{ $t->agente?->apellido ?? '' }}
                                            </p>
                                            @break

                                        @case('created_at')
                                            <p class="text-gray-700 text-theme-sm dark:text-gray-400">
                                                {{ $t->created_at->format('d/m/Y') }}
                                            </p>
                                            @break

                                        @case('prioridad')
                                            <span class="text-theme-sm font-medium
                                                @if($t->prioridad === 'Urgente') text-red-600 dark:text-red-400
                                                @elseif($t->prioridad === 'Alta') text-orange-600 dark:text-orange-400
                                                @elseif($t->prioridad === 'Media') text-yellow-600 dark:text-yellow-400
                                                @else text-gray-600 dark:text-gray-400
                                                @endif">
                                                {{ $t->prioridad ?? '—' }}
                                            </span>
                                            @break

                                        @case('tipo_ticket')
                                            <p class="text-gray-700 text-theme-sm dark:text-gray-400">
                                                {{ $t->tipo_ticket ?? '—' }}
                                            </p>
                                            @break

                                        @default
                                            <p class="text-gray-700 text-theme-sm dark:text-gray-400">
                                                {{ $t->{$column->column_key} ?? '—' }}
                                            </p>
                                    @endswitch
                                </td>
                                @endforeach

                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ $columns->count() + 1 }}"
                                    class="px-4 py-10 text-center text-gray-400 text-theme-sm dark:text-gray-600">
                                    No hay tickets que coincidan con las condiciones de esta vista.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        @else

            {{-- Estado vacío — ninguna vista seleccionada --}}
            <div class="flex flex-col items-center justify-center h-full min-h-[400px] text-center">
                <svg class="w-12 h-12 text-gray-300 dark:text-gray-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                </svg>
                <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Selecciona una vista del panel izquierdo</p>
                <p class="text-gray-400 dark:text-gray-600 text-xs mt-1">Los tickets filtrados aparecerán aquí</p>
            </div>

        @endif

    </div>

</div>

@endsection

@push('scripts')
@if(isset($ticketView))
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (document.querySelector('#tabla-vista-tickets')) {
        new DataTable('#tabla-vista-tickets', {
            responsive: true,
            pageLength: 10,
            language: {
                search: "Buscar:",
                lengthMenu: "Mostrar _MENU_ tickets por página",
                zeroRecords: "No se encontraron tickets",
                info: "Mostrando _START_ a _END_ de _TOTAL_ tickets",
                infoEmpty: "Mostrando 0 a 0 de 0 tickets",
                emptyTable: "No hay tickets disponibles",
                paginate: {
                    next: "Siguiente",
                    previous: "Anterior"
                }
            }
        });
    }
});
</script>
@endif
@endpush