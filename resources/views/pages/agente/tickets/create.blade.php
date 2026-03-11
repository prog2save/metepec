@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Crear Ticket" />

@if (session('success'))
<x-ui.alert variant="success" title="{{ session('success') }}" message="" :showLink="false" linkHref="/" linkText="" />
@endif

<style>
    .ts-wrapper {
        width: 100%;
    }

    .ts-control {
        height: 2.75rem;
        width: 100%;
        border-radius: .5rem;
        border: 1px solid rgb(209 213 219);
        background: transparent;
        padding: .625rem 1rem;
        font-size: .875rem;
        line-height: 1.25rem;
        color: rgb(31 41 55);
        display: flex;
        align-items: center;
        gap: .5rem;
        box-shadow: var(--shadow-theme-xs, 0 1px 2px rgba(0, 0, 0, .05));
    }

    .ts-control>input {
        margin: 0 !important;
        padding: 0 !important;
        font-size: .875rem !important;
        line-height: 1.25rem !important;
        color: rgb(31 41 55) !important;
        background-color: transparent !important;
        -webkit-text-fill-color: rgb(31 41 55) !important;
    }

    .ts-control>input::placeholder {
        color: rgb(156 163 175) !important;
    }

    .dark .ts-control {
        border-color: rgb(55 65 81);
        background-color: rgb(17 24 39) !important;
        color: rgba(255, 255, 255, .90);
    }

    .dark .ts-control>input {
        color: rgba(255, 255, 255, .90) !important;
        -webkit-text-fill-color: rgba(255, 255, 255, .90) !important;
    }

    .dark .ts-control>input::placeholder {
        color: rgba(255, 255, 255, .30) !important;
    }

    .ts-control {
        background-color: transparent !important;
    }

    .ts-wrapper.focus .ts-control,
    .ts-control:focus-within {
        outline: none;
        border-color: rgb(147 197 253);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, .10);
        background-color: transparent !important;
    }

    .dark .ts-wrapper.focus .ts-control,
    .dark .ts-control:focus-within {
        border-color: rgb(30 64 175);
        background-color: rgb(17 24 39) !important;
    }

    .ts-control .ts-dropdown-toggle {
        margin-left: auto;
        opacity: .7;
        background: transparent !important;
    }

    .ts-dropdown {
        margin-top: .25rem;
        border-radius: .5rem;
        border: 1px solid rgb(229 231 235);
        background: #fff;
        overflow: hidden;
        box-shadow: 0 10px 15px rgba(0, 0, 0, .08);
        z-index: 50;
    }

    .dark .ts-dropdown {
        border-color: rgb(55 65 81);
        background: rgb(17 24 39);
    }

    .ts-dropdown .ts-dropdown-content {
        max-height: 15rem;
        overflow: auto;
    }

    .ts-dropdown .option {
        padding: .5rem 1rem;
        font-size: .875rem;
        cursor: pointer;
        color: rgb(55 65 81);
    }

    .dark .ts-dropdown .option {
        color: rgb(229 231 235);
    }

    .ts-dropdown .option.active {
        background: rgb(243 244 246);
    }

    .dark .ts-dropdown .option.active {
        background: rgb(31 41 55);
    }

    .ts-dropdown .option.selected {
        background: rgba(59, 130, 246, .08);
        color: rgb(29 78 216);
    }

    .dark .ts-dropdown .option.selected {
        background: rgb(31 41 55);
        color: rgba(255, 255, 255, .90);
    }
</style>

<div class="space-y-4">

    @if (session('success'))
    <x-ui.alert variant="success" title="{{ session('success') }}" message="" :showLink="false" linkHref="/" linkText="" />
    @endif

    @if ($errors->any())
    <x-ui.alert variant="error" title="Errores en el formulario">
        <div class="text-sm">
            <p class="font-medium mb-2">Por favor corrige los siguientes errores:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </x-ui.alert>
    @endif

    <form action="{{ route('agente.tickets.store') }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="flex gap-4 items-start">

            {{-- ════════════════════════════════
                 COLUMNA IZQUIERDA — Metadata
            ════════════════════════════════ --}}
            <div class="w-72 shrink-0 space-y-3">

                <div class="rounded-xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-700 dark:bg-gray-900">

                    <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3 dark:border-gray-800">
                        <h2 class="text-sm font-semibold text-gray-800 dark:text-white/90">Nuevo ticket</h2>
                        <span class="inline-flex items-center rounded-full bg-orange-50 px-2 py-0.5 text-xs font-medium text-orange-700 border border-orange-200 dark:bg-orange-900/30 dark:text-orange-300 dark:border-orange-800">
                            Nuevo
                        </span>
                    </div>

                    <div class="space-y-4 p-4">

                        {{-- Ciudadano --}}
                        <div>
                            <label class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Solicitante
                            </label>
                            <select id="id_ciudadano" name="id_ciudadano" class="w-full">
                                <option value="">Selecciona un ciudadano</option>
                                @foreach($ciudadanos as $c)
                                <option value="{{ $c->id }}" {{ old('id_ciudadano') == $c->id ? 'selected' : '' }}>
                                    {{ $c->nombre }} {{ $c->apellido_paterno }} {{ $c->apellido_materno }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Agente --}}
                        <div>
                            <label class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Agente asignado
                                <span class="ml-1 normal-case font-normal text-gray-400">(opcional)</span>
                            </label>
                            <select id="id_agente_asignado" name="id_agente_asignado" class="w-full">
                                <option value="">–</option>
                                @foreach($agentes as $a)
                                <option value="{{ $a->id }}" {{ old('id_agente_asignado') == $a->id ? 'selected' : '' }}>
                                    {{ $a->nombre }} {{ $a->apellido }}
                                </option>
                                @endforeach
                            </select>
                            <p id="estado-hint" class="mt-1.5 text-xs text-blue-600 dark:text-blue-400 hidden">
                                Estado cambiará a <strong>Abierto</strong> automáticamente.
                            </p>
                        </div>

                        {{-- Dirección + Servicio --}}
                        <div
                            x-data="{
                                servicios: @js($servicios),
                                direccion: @js(old('id_direccion_municipal', '')),
                                servicio: @js(old('id_servicio', '')),
                                tsDireccion: null, tsServicio: null,
                                init() {
                                    this.$nextTick(() => {
                                        const dirEl = document.getElementById('id_direccion_municipal');
                                        const servEl = document.getElementById('id_servicio');
                                        if (!dirEl || !servEl) return;
                                        this.tsDireccion = dirEl.tomselect ?? new TomSelect(dirEl, { create: false, allowEmptyOption: true });
                                        this.tsServicio  = servEl.tomselect  ?? new TomSelect(servEl,  { create: false, allowEmptyOption: true });
                                        this.tsDireccion.off('change');
                                        this.tsDireccion.on('change', () => { this.direccion = String(this.tsDireccion.getValue() || ''); this.refreshServicios(); });
                                        this.tsServicio.off('change');
                                        this.tsServicio.on('change', () => { this.servicio = String(this.tsServicio.getValue() || ''); });
                                        if (this.direccion) this.tsDireccion.setValue(String(this.direccion), true);
                                        this.refreshServicios();
                                        if (this.servicio) this.tsServicio.setValue(String(this.servicio), true);
                                    });
                                },
                                refreshServicios() {
                                    if (!this.tsServicio) return;
                                    const dir = String(this.tsDireccion?.getValue?.() || this.direccion || '');
                                    this.tsServicio.clearOptions();
                                    this.tsServicio.addOption({ value: '', text: 'Selecciona un servicio' });
                                    const filtrados = (this.servicios || []).filter(s => String(s.id_direccion_municipal) === dir);
                                    filtrados.forEach(s => this.tsServicio.addOption({ value: String(s.id), text: s.nombre_servicio }));
                                    this.tsServicio.refreshOptions(false);
                                    const actual = String(this.tsServicio.getValue() || '');
                                    if (!filtrados.some(s => String(s.id) === actual)) { this.servicio = ''; this.tsServicio.clear(true); }
                                }
                            }"
                            class="space-y-4">

                            <div>
                                <label class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                    Dirección municipal
                                </label>
                                <select id="id_direccion_municipal" name="id_direccion_municipal" class="w-full">
                                    <option value="">Selecciona una dirección</option>
                                    @foreach($direcciones as $d)
                                    <option value="{{ $d->id }}" {{ (string)old('id_direccion_municipal') === (string)$d->id ? 'selected' : '' }}>
                                        {{ $d->nombre_direccion }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                    Servicio
                                </label>
                                <select id="id_servicio" name="id_servicio" class="w-full">
                                    <option value="">Selecciona un servicio</option>
                                </select>
                                <p class="mt-1 text-xs text-gray-400 dark:text-gray-600">Elige una dirección primero.</p>
                            </div>
                        </div>

                        {{-- Canal de ingreso --}}
                        <div>
                            <label for="canal_ingreso" class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Canal de ingreso
                            </label>
                            <input type="text" id="canal_ingreso" name="canal_ingreso" value="{{ old('canal_ingreso') }}"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-9 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                        </div>

                        {{-- Tipo --}}
                        <div>
                            <label for="tipo_ticket" class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Tipo
                            </label>
                            <select name="tipo_ticket" id="tipo_ticket"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-9 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                <option value="">Selecciona un tipo</option>
                                <option value="Pregunta" {{ old('tipo_ticket') == 'Pregunta'  ? 'selected' : '' }}>Pregunta</option>
                                <option value="Incidente" {{ old('tipo_ticket') == 'Incidente' ? 'selected' : '' }}>Incidente</option>
                                <option value="Problema" {{ old('tipo_ticket') == 'Problema'  ? 'selected' : '' }}>Problema</option>
                                <option value="Tarea" {{ old('tipo_ticket') == 'Tarea'     ? 'selected' : '' }}>Tarea</option>
                            </select>
                        </div>

                        {{-- Prioridad --}}
                        <div>
                            <label for="prioridad" class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Prioridad
                            </label>
                            <select name="prioridad" id="prioridad"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-9 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                <option value="">Selecciona una prioridad</option>
                                <option value="Baja" {{ old('prioridad') == 'Baja'    ? 'selected' : '' }}>Baja</option>
                                <option value="Media" {{ old('prioridad') == 'Media'   ? 'selected' : '' }}>Media</option>
                                <option value="Alta" {{ old('prioridad') == 'Alta'    ? 'selected' : '' }}>Alta</option>
                                <option value="Urgente" {{ old('prioridad') == 'Urgente' ? 'selected' : '' }}>Urgente</option>
                            </select>
                        </div>

                        {{-- Estado --}}
                        <div>
                            <label for="estado" class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Estado
                            </label>
                            <select name="estado" id="estado"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-9 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 disabled:opacity-60 disabled:cursor-not-allowed">
                                <option value="Nuevo" {{ old('estado', 'Nuevo') == 'Nuevo'     ? 'selected' : '' }}>Nuevo</option>
                                <option value="Abierto" {{ old('estado') == 'Abierto'             ? 'selected' : '' }}>Abierto</option>
                                <option value="Pendiente" {{ old('estado') == 'Pendiente'           ? 'selected' : '' }}>Pendiente</option>
                                <option value="Resuelto" {{ old('estado') == 'Resuelto'            ? 'selected' : '' }}>Resuelto</option>
                            </select>
                        </div>

                        {{-- Fecha resolución --}}
                        <div>
                            <label for="fecha_resolucion" class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Fecha de resolución
                                <span class="ml-1 normal-case font-normal text-gray-400">(opcional)</span>
                            </label>
                            <div class="relative">
                                <input type="date" id="fecha_resolucion" name="fecha_resolucion"
                                    value="{{ old('fecha_resolucion') }}"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-9 w-full rounded-lg border border-gray-300 bg-transparent px-3 pr-10 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                                <button type="button"
                                    onclick="const i=document.getElementById('fecha_resolucion');if(typeof i.showPicker==='function'){i.showPicker();}else{i.focus();i.click();}"
                                    class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Card adjuntos --}}
                <div class="rounded-xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-700 dark:bg-gray-900">
                    <div class="border-b border-gray-100 px-4 py-3 dark:border-gray-800">
                        <h3 class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Archivos adjuntos</h3>
                    </div>
                    <div class="p-4">
                        <input type="file" id="adjuntos" name="adjuntos[]" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                            class="block w-full text-sm text-gray-800
                                   file:mr-3 file:rounded-lg file:border-0
                                   file:bg-gray-100 file:px-3 file:py-1.5
                                   file:text-xs file:font-medium file:text-gray-700
                                   hover:file:bg-gray-200
                                   dark:text-white/90 dark:file:bg-gray-800 dark:file:text-gray-300" />
                        <p class="mt-2 text-xs text-gray-400 dark:text-gray-600">JPG, PNG, PDF, DOC, DOCX</p>
                    </div>
                </div>

            </div>

            {{-- ════════════════════════════════
                 COLUMNA DERECHA — Contenido
            ════════════════════════════════ --}}
            <div class="flex-1 min-w-0">
                <div class="rounded-xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-700 dark:bg-gray-900 flex flex-col">

                    {{-- Asunto --}}
                    <div class="border-b border-gray-100 px-5 py-3 dark:border-gray-800">
                        <input type="text" id="asunto" name="asunto" value="{{ old('asunto') }}"
                            placeholder="Asunto"
                            class="w-full bg-transparent text-base font-medium text-gray-800 placeholder:text-gray-400 focus:outline-none dark:text-white/90 dark:placeholder:text-white/30" />
                    </div>

                    {{-- Descripción --}}
                    <div class="flex-1 px-5 py-4">
                        <label class="mb-2 block text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-600">
                            Descripción
                        </label>
                        <textarea name="descripcion" id="descripcion"
                            placeholder="Describe el problema o solicitud..."
                            class="w-full bg-transparent text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none dark:text-white/90 dark:placeholder:text-white/30 resize-none"
                            style="min-height: 180px">{{ old('descripcion') }}</textarea>
                    </div>

                    {{-- Observaciones --}}
                    <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-800">
                        <label class="mb-2 block text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-600">
                            Observaciones internas
                        </label>
                        <textarea name="observaciones" id="observaciones"
                            placeholder="Notas internas visibles solo para agentes..."
                            class="w-full bg-transparent text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none dark:text-white/90 dark:placeholder:text-white/30 resize-none"
                            style="min-height: 100px">{{ old('observaciones') }}</textarea>
                    </div>

                    {{-- Footer --}}
                    <div class="flex items-center justify-between border-t border-gray-100 px-5 py-3 dark:border-gray-800">
                        <a href="{{ route('agente.tickets.index') }}"
                            class="text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                            Cancelar
                        </a>
                        <x-ui.button size="sm" variant="primary" type="submit">
                            Crear ticket
                        </x-ui.button>
                    </div>

                </div>
            </div>

        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const opts = {
            create: false,
            allowEmptyOption: true
        };

        if (document.querySelector('#id_ciudadano') && !document.querySelector('#id_ciudadano')?.tomselect) {
            new TomSelect('#id_ciudadano', {
                ...opts,
                placeholder: 'Selecciona un ciudadano'
            });
        }
        if (document.querySelector('#id_agente_asignado') && !document.querySelector('#id_agente_asignado')?.tomselect) {
            new TomSelect('#id_agente_asignado', {
                ...opts,
                placeholder: '–'
            });
        }
        if (document.querySelector('#id_direccion_municipal') && !document.querySelector('#id_direccion_municipal')?.tomselect) {
            new TomSelect('#id_direccion_municipal', {
                ...opts,
                placeholder: 'Selecciona una dirección'
            });
        }
        if (document.querySelector('#id_servicio') && !document.querySelector('#id_servicio')?.tomselect) {
            new TomSelect('#id_servicio', {
                ...opts,
                placeholder: 'Selecciona un servicio'
            });
        }

        // Lógica estado según agente
        const agenteTs = document.querySelector('#id_agente_asignado')?.tomselect;
        const estadoEl = document.getElementById('estado');
        const estadoHint = document.getElementById('estado-hint');

        if (agenteTs && estadoEl) {
            agenteTs.on('change', (val) => {
                const opcionNuevo = estadoEl.querySelector('option[value="Nuevo"]');
                if (val) {
                    // Ocultar "Nuevo" y cambiar a Abierto si estaba en Nuevo
                    if (opcionNuevo) opcionNuevo.hidden = true;
                    if (estadoEl.value === 'Nuevo') estadoEl.value = 'Abierto';
                    estadoHint?.classList.remove('hidden');
                } else {
                    // Restaurar "Nuevo" y volver a seleccionarlo
                    if (opcionNuevo) opcionNuevo.hidden = false;
                    estadoEl.value = 'Nuevo';
                    estadoHint?.classList.add('hidden');
                }
            });
        }
    });
</script>
@endpush

@endsection