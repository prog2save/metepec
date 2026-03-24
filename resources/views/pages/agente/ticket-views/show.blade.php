@extends('layouts.app')

@section('content')

<div class="mb-6 flex items-center justify-between">
    <div>
        <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-1">
            <span>Vistas</span>
            <span>/</span>
            <span>{{ $ticketView->title }}</span>
        </div>
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">{{ $ticketView->title }}</h1>
        @if($ticketView->description)
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $ticketView->description }}</p>
        @endif
    </div>
</div>

@if (session('success'))
<x-ui.alert variant="success" title="{{ session('success') }}" message="" :showLink="false" linkHref="/" linkText="" />
@endif

<div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

    <div class="max-w-full overflow-x-auto custom-scrollbar">
        <table id="tabla-vista-tickets" class="w-full min-w-[900px] p-2">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800">
                    @foreach($columns as $column)
                    <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">
                        {{ $column->label ?? match($column->column_key) {
                            'id'                  => 'ID',
                            'estado'              => 'Estado',
                            'asunto'              => 'Asunto',
                            'id_ciudadano'        => 'Solicitante',
                            'id_agente_asignado'  => 'Agente asignado',
                            'created_at'          => 'Fecha de solicitud',
                            'prioridad'           => 'Prioridad',
                            'tipo_ticket'         => 'Tipo',
                            default               => $column->column_key,
                        } }}
                    </th>
                    @endforeach
                    <th class="px-4 py-3 font-normal text-center text-gray-500 text-theme-sm dark:text-gray-400">Acción</th>
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
                        @if($t->ciudadano)
                        <p class="text-gray-700 text-theme-sm dark:text-gray-400">
                            {{ $t->ciudadano->nombre }} {{ $t->ciudadano->apellido_paterno }}
                        </p>
                        @else
                        <p class="italic text-gray-400 text-theme-sm dark:text-gray-600">Sin asignar</p>
                        @endif
                        @break

                        @case('id_agente_asignado')
                        @if($t->agente)
                        <p class="text-gray-700 text-theme-sm dark:text-gray-400">
                            {{ $t->agente->nombre }} {{ $t->agente->apellido }}
                        </p>
                        @else
                        <p class="italic text-gray-400 text-theme-sm dark:text-gray-600">Sin asignar</p>
                        @endif
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

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new DataTable('#tabla-vista-tickets', {
            searchable: true,
            paging: true,
            perPage: 30,
            labels: {
                placeholder: "Buscar tickets...",
                perPage: "Tickets por página",
                noRows: "No hay tickets que coincidan",
                info: "Mostrando {start} a {end} de {rows} tickets"
            }
        });
    });
</script>
@endpush