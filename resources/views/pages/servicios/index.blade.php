@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Servicios" />

<div
    x-data="{
        openCreate: false,
        openEdit: false,

        edit: {
            id: null,
            nombre_servicio: '',
            id_direccion_municipal: '',
        },

        openCreateModal() {
            this.openCreate = true;
            this.$nextTick(() => this.$refs.nombreCreate?.focus());
        },
        closeCreateModal() { this.openCreate = false; },

        openEditModal(servicio) {
            this.edit.id = servicio.id;
            this.edit.nombre_servicio = servicio.nombre_servicio ?? '';
            this.edit.id_direccion_municipal = (servicio.id_direccion_municipal ?? '').toString();
            if (this.edit.id_direccion_municipal === 'null') this.edit.id_direccion_municipal = '';

            this.openEdit = true;

            this.$nextTick(() => {
                this.$refs.nombreEdit?.focus();

                const el = document.getElementById('edit_id_direccion_municipal');
                if (el && !el.tomselect) {
                    new TomSelect('#edit_id_direccion_municipal', {
                        create: false,
                        allowEmptyOption: true,
                        placeholder: 'Selecciona una dirección',
                    });
                }
                if (el && el.tomselect) {
                    el.tomselect.setValue(this.edit.id_direccion_municipal, true);
                }
            });
        },
        closeEditModal() { this.openEdit = false; }
    }"
    class="space-y-6">
    @if (session('success'))
    <div class="space-y-4">
        <x-ui.alert variant="success" title="{{ session('success') }}" message="" :showLink="false" />
    </div>
    @endif

    {{-- Botón crear servicio --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <button
            type="button"
            @click="openCreateModal()"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5
                   text-sm font-medium text-white transition-colors hover:bg-blue-700
                   focus:outline-none focus:ring-4 focus:ring-blue-500/20
                   dark:bg-blue-500 dark:hover:bg-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Agregar servicio
        </button>
    </div>

    <x-common.component-card title="Lista de Servicios">
        <x-tables.servicios-table :servicios="$servicios" />
    </x-common.component-card>

    @include('pages.servicios._modal-create', ['direcciones' => $direcciones])
    @include('pages.servicios._modal-edit', ['direcciones' => $direcciones])

    @if ($errors->any())
    <div x-init="openCreate = true; $nextTick(() => $refs.nombreCreate?.focus())"></div>
    @endif

</div>
@endsection