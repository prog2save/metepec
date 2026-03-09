@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Ciudadanos" />
<div class="space-y-6">
    @if (session('success'))
    <div class="space-y-4">
        <x-ui.alert variant="success" title="{{ session('success') }}" message="" :showLink="false" linkHref="/"
            linkText="Learn more" />
    </div>
    @endif
    <!--
    <div class="bg-white dark:bg-white/[0.03] border border-gray-200 dark:border-gray-800 rounded-xl p-4">
        <form method="GET" class="flex flex-col md:flex-row gap-3 md:items-end">

            <div class="w-full md:w-56 text-gray-500 text-start text-theme-sm dark:text-gray-400">
                <label class="block text-theme-xs text-gray-500 mb-1">Estatus</label>
                <select name="estatus"
                    class="w-full rounded-lg border border-gray-200 dark:border-gray-800 bg-white dark:bg-transparent px-3 py-2 text-theme-sm">
                    <option value="ACTIVO" {{ request('estatus','ACTIVO') === 'ACTIVO' ? 'selected' : '' }}>Activo</option>
                    <option value="SUSPENDIDO" {{ request('estatus') === 'SUSPENDIDO' ? 'selected' : '' }}>Suspendido</option>
                    <option value="TODOS" {{ request('estatus') === 'TODOS' ? 'selected' : '' }}>Todos</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit"
                    class="px-4 py-2 rounded-lg bg-blue-600 text-white text-theme-sm hover:bg-blue-700">
                    Filtrar
                </button>

                <a href="{{ route('ciudadanos.index') }}"
                    class="px-4 py-2 rounded-lg bg-gray-100 dark:bg-white/[0.06] text-gray-700 dark:text-gray-300 text-theme-sm">
                    Limpiar
                </a>
            </div>
        </form>
    </div>-->
    <x-common.component-card title="Lista de Ciudadanos">
        <x-tables.ciudadanos-table :ciudadanos="$ciudadanos" />
    </x-common.component-card>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new DataTable('#ciudadanos-table', {
            searchable: true,
            perPage: 10,
            columns: [{
                select: [4, 5],
                sortable: false
            }],
            labels: {
                placeholder: "Buscar ciudadano...",
                perPage: "Ciudadanos por página",
                noRows: "No se encontraron ciudadanos",
                info: "Mostrando {start} a {end} de {rows} ciudadanos"
            }
        });
    });
</script>
@endpush