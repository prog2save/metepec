@extends('layouts.app')

@section('title', 'Macros')

@section('content')
<x-common.page-breadcrumb pageTitle="Macros" />

@if(session('success'))
<x-ui.alert variant="success" title="{{ session('success') }}" message="" :showLink="false" linkHref="/" linkText="" />
@endif

<div class="space-y-4">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('macros.create') }}">
            <x-ui.button size="sm" variant="primary">
                + Nuevo macro
            </x-ui.button>
        </a>
    </div>

    {{-- Tabla --}}
    <div class="rounded-xl p-3 border border-gray-200 bg-white shadow-theme-xs dark:border-gray-700 dark:bg-gray-900 overflow-hidden">
        <table class="w-full text-sm" id="macros-table">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800 text-xs uppercase tracking-wide text-gray-400 dark:text-gray-600">
                    <th class="px-5 py-3 text-left font-medium">Nombre</th>
                    <th class="px-5 py-3 text-left font-medium">Acciones</th>
                    <th class="px-5 py-3 text-left font-medium">Creado por</th>
                    <th class="px-5 py-3 text-left font-medium">Estado</th>
                    <th class="px-5 py-3 text-left font-medium">Editar</th>
                    <th class="px-5 py-3 text-left font-medium">Eliminar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach($macros as $macro)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">

                    {{-- Nombre --}}
                    <td class="px-5 py-3">
                        <p class="font-medium text-gray-800 dark:text-white/90">{{ $macro->name }}</p>
                        @if($macro->description)
                        <p class="text-xs text-gray-400 dark:text-gray-600 mbrat-0.5 line-clamp-1">
                            {{ $macro->description }}
                        </p>
                        @endif
                    </td>

                    {{-- Cantidad de acciones --}}
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center rounded-full bg-gray-100 dark:bg-gray-800
                                     px-2.5 py-0.5 text-xs font-medium text-gray-600 dark:text-gray-400">
                                     @if ($macro->actions_count > 1)
                                         {{ $macro->actions_count }} acciones
                                     @else
                                         {{ $macro->actions_count }} acción
                                     @endif
                        </span>
                    </td>

                    {{-- Creado por --}}
                    <td class="px-5 py-3 text-gray-500 dark:text-gray-400">
                        {{ $macro->createdBy?->nombre ?? '—' }}
                    </td>

                    {{-- Toggle activo --}}
                    <td class="px-5 py-3">
                        <form action="{{ route('macros.toggle', $macro) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium border transition-colors
                                    {{ $macro->active
                                        ? 'bg-green-50 text-green-700 border-green-200 hover:bg-green-100 dark:bg-green-900/30 dark:text-green-300 dark:border-green-800'
                                        : 'bg-gray-100 text-gray-500 border-gray-200 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700' }}">
                                {{ $macro->active ? 'Activo' : 'Inactivo' }}
                            </button>
                        </form>
                    </td>

                    {{-- Acciones --}}

                    <td class="">
                            <a href="{{ route('macros.edit', $macro) }}"
                                class="flex gap-3 text-gray-400 hover:text-blue-500 transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </a>
                    </td>

                    <td class="">
                            <form action="{{ route('macros.destroy', $macro) }}" method="POST"
                                class="inline-block"
                                onsubmit="return confirm('¿Eliminar este macro?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="flex gap-3 text-gray-400 hover:text-red-700 transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </form>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selector = '#macros-table';

        if (document.querySelector(selector) && !DataTable.isDataTable(selector)) {
            new DataTable(selector, {
                responsive: true,
                pageLength: 10,
                columnDefs: [{
                    orderable: false,
                    targets: [4,5]
                }],
                language: {
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ macros por página",
                    zeroRecords: "No se encontraron macros",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ macros",
                    infoEmpty: "Mostrando 0 a 0 de 0 macros",
                    emptyTable: "No hay macros disponibles",
                    paginate: {
                        next: "Siguiente",
                        previous: "Anterior"
                    }
                }
            });
        }
    });
</script>