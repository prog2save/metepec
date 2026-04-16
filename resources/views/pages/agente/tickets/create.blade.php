@extends('layouts.app')
@section('title', 'Crear Ticket')
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
                                <option value="">Selecciona un solicitante</option>
                                @foreach($ciudadanos as $c)
                                <option value="{{ $c->id }}"
                                    {{ (string) old('id_ciudadano', $solicitanteSeleccionado->id ?? '') === (string) $c->id ? 'selected' : '' }}>
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
                                <option value="">Selecciona un agente</option>
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
                                    const selected = String(this.servicio || '');

                                    this.tsServicio.clearOptions();
                                    this.tsServicio.addOption({ value: '', text: 'Selecciona un servicio' });

                                    const filtrados = (this.servicios || []).filter(
                                        s => String(s.id_direccion_municipal) === dir
                                    );

                                    filtrados.forEach(s => {
                                        this.tsServicio.addOption({
                                            value: String(s.id),
                                            text: s.nombre_servicio
                                        });
                                    });

                                    this.tsServicio.refreshOptions(false);

                                    if (selected && filtrados.some(s => String(s.id) === selected)) {
                                        this.tsServicio.setValue(selected, true);
                                        return;
                                    }

                                    this.servicio = '';
                                    this.tsServicio.clear(true);
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

                                    @if(old('id_servicio'))
                                        @php
                                            $servicioOld = $servicios->firstWhere('id', old('id_servicio'));
                                        @endphp

                                        @if($servicioOld)
                                            <option value="{{ $servicioOld->id }}" selected>
                                                {{ $servicioOld->nombre_servicio }}
                                            </option>
                                        @endif
                                    @endif
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

                        {{-- Tags --}}
                        <div
                            x-data="{
                                tags: @js(old('tags') ? explode(',', old('tags')) : []),
                                input: '',
                                addTag() {
                                    const val = this.input.trim();
                                    if (val && !this.tags.includes(val)) this.tags.push(val);
                                    this.input = '';
                                },
                                removeTag(i) { this.tags.splice(i, 1); }
                            }"
                            @set-tags.window="tags = $event.detail.tags"
                            >
                            <label class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                Etiquetas
                            </label>

                            {{-- Input hidden que envía al servidor --}}
                            <input type="hidden" name="tags" :value="tags.join(',')">

                            {{-- Área visual de tags --}}
                            <div
                                class="min-h-[2.25rem] w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1.5
                                    flex flex-wrap gap-1 items-center cursor-text
                                    focus-within:border-brand-300 focus-within:ring-3 focus-within:ring-brand-500/10
                                    dark:border-gray-700 dark:bg-gray-900"
                                @click="$refs.taginput.focus()">
                                <template x-for="(tag, i) in tags" :key="i">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-indigo-50 border border-indigo-200
                                        px-2 py-0.5 text-xs font-medium text-indigo-700
                                      dark:bg-indigo-900/30 dark:border-indigo-800 dark:text-indigo-300">
                                        <span x-text="tag"></span>
                                        <button type="button" @click.stop="removeTag(i)"
                                            class="text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-200 leading-none">
                                            &#x2715;
                                        </button>
                                    </span>
                                </template>

                                <input
                                    x-ref="taginput"
                                    x-model="input"
                                    @keydown.space.prevent="addTag()"
                                    @keydown.enter.prevent="addTag()"
                                    @keydown.backspace="input === '' && tags.pop()"
                                    @blur="addTag()"
                                    type="text"
                                    class="flex-1 min-w-[100px] bg-transparent text-sm text-gray-800 placeholder:text-gray-400
                                    focus:outline-none dark:text-white/90 dark:placeholder:text-white/30">
                            </div>
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
                 COLUMNA CENTRAL — Contenido
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

                    {{-- Ubicación del ticket (opcional) --}}
                    <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-800">
                        <div class="mb-3">
                            <label class="block text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-600">
                                Dirección del ticket
                            </label>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                (Opcional)
                            </p>
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="md:col-span-2">
                                <label for="ticket_calle" class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                    Calle
                                </label>
                                <input
                                    type="text"
                                    id="ticket_calle"
                                    name="ticket_calle"
                                    value="{{ old('ticket_calle') }}"
                                    placeholder="Ej. Av. Reforma"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                />
                            </div>

                            <div>
                                <label for="ticket_numero" class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                    Número
                                </label>
                                <input
                                    type="text"
                                    id="ticket_numero"
                                    name="ticket_numero"
                                    value="{{ old('ticket_numero') }}"
                                    placeholder="Ej. 123"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                />
                            </div>

                            <div class="md:col-span-2">
                                <label for="ticket_calle" class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                    Colonia / Fraccionamiento
                                </label>
                                <input
                                    type="text"
                                    id="ticket_colonia"
                                    name="ticket_colonia"
                                    value="{{ old('ticket_colonia') }}"
                                    placeholder="Ej. Centro Histórico"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                />
                            </div>

                            <div>
                                <label for="ticket_municipio" class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                    Municipio
                                </label>
                                <input
                                    type="text"
                                    id="ticket_municipio"
                                    name="ticket_municipio"
                                    value="{{ old('ticket_municipio') }}"
                                    placeholder="Ej. Puebla"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                />
                            </div>

                            <div class="md:col-span-2">
                                <label for="ticket_estado_ubicacion" class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                    Estado
                                </label>
                                <input
                                    type="text"
                                    id="ticket_estado_ubicacion"
                                    name="ticket_estado"
                                    value="{{ old('ticket_estado') }}"
                                    placeholder="Ej. Puebla"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                />
                                <input
                                    type="hidden"
                                    disabled 
                                    id="ticket_pais_ubicacion"
                                    name="ticket_pais"
                                    value="{{ old('ticket_pais') }}"
                                    placeholder="Ej. México"
                                    value="México"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                />
                            </div>
                        </div>
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
            {{-- ════════════════════════════════
                COLUMNA DERECHA — Solicitante
            ════════════════════════════════ --}}
            <div class="w-56 shrink-0 space-y-3">

                {{-- Info solicitante --}}
                <div class="rounded-xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-700 dark:bg-gray-900">
                    <div class="border-b border-gray-100 px-4 py-3 dark:border-gray-800">
                        <h3 class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Solicitante
                        </h3>
                    </div>

                    <div class="p-4 space-y-3">
                        @if($solicitanteSeleccionado)

                            <div class="flex items-center gap-3">
                                <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gray-100 text-sm font-semibold text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                    {{ strtoupper(substr($solicitanteSeleccionado->nombre ?? '?', 0, 1)) }}
                                </span>
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-medium text-gray-800 dark:text-white/90">
                                        {{ $solicitanteSeleccionado->nombre ?? '–' }}
                                        {{ $solicitanteSeleccionado->apellido_paterno ?? '' }}
                                        {{ $solicitanteSeleccionado->apellido_materno ?? '' }}
                                    </p>
                                </div>
                            </div>

                            @if($solicitanteSeleccionado->email)
                            <div>
                                <p class="mb-0.5 text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-600">
                                    Correo
                                </p>
                                <a href="mailto:{{ $solicitanteSeleccionado->email }}"
                                    class="break-all text-xs text-brand-600 hover:underline dark:text-brand-400">
                                    {{ $solicitanteSeleccionado->email }}
                                </a>
                            </div>
                            @endif

                            @if($solicitanteSeleccionado->telefono_principal)
                            <div>
                                <p class="mb-0.5 text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-600">
                                    Teléfono principal
                                </p>
                                <a href="tel:{{ $solicitanteSeleccionado->telefono_principal }}"
                                    class="text-xs text-gray-700 dark:text-gray-300">
                                    {{ $solicitanteSeleccionado->telefono_principal }}
                                </a>
                            </div>
                            @endif

                            @if($solicitanteSeleccionado->telefono_alterno)
                            <div>
                                <p class="mb-0.5 text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-600">
                                    Teléfono alterno
                                </p>
                                <a href="tel:{{ $solicitanteSeleccionado->telefono_alterno }}"
                                    class="text-xs text-gray-700 dark:text-gray-300">
                                    {{ $solicitanteSeleccionado->telefono_alterno }}
                                </a>
                            </div>
                            @endif

                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Selecciona un solicitante para ver su información aquí.
                            </p>
                        @endif
                    </div>
                </div>

                {{-- Tickets recientes del solicitante --}}
                <div class="rounded-xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-700 dark:bg-gray-900">
                    <div class="border-b border-gray-100 px-4 py-3 dark:border-gray-800">
                        <h3 class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Tickets recientes del solicitante
                        </h3>
                    </div>

                    <div class="p-4 space-y-3">
                        @if($solicitanteSeleccionado)
                            @forelse($ticketsRecientesSolicitante as $ticketReciente)
                                <div class="rounded-lg border border-gray-100 p-3 dark:border-gray-800">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <p class="truncate text-sm font-medium text-gray-800 dark:text-white/90">
                                                {{ $ticketReciente->asunto }}
                                            </p>
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                Creado: {{ $ticketReciente->created_at->format('d/m/Y H:i') }}
                                            </p>
                                        </div>

                                        <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium
                                            @if($ticketReciente->estado === 'Resuelto') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400
                                            @elseif($ticketReciente->estado === 'Pendiente') bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300
                                            @elseif($ticketReciente->estado === 'Nuevo') bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400
                                            @elseif($ticketReciente->estado === 'Abierto') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                                            @else bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300
                                            @endif">
                                            {{ $ticketReciente->estado }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Este solicitante no tiene otros tickets recientes.
                                </p>
                            @endforelse
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Aún no hay solicitante seleccionado.
                            </p>
                        @endif
                    </div>
                </div>

            </div>
        </div>

    </form>
</div>

{{-- macros --}}
<div
    x-data="{
        open: false,
        search: '',
        canales: @js($canales->map(fn($c) => [
            'id' => $c->id,
            'nombre' => $c->nombre
        ])->values()),
        macros: @js($macros->map(fn($m) => [
            'id' => $m->id,
            'name' => $m->name,
            'actions' => $m->actions->map(fn($a) => [
                'field' => $a->field,
                'value' => $a->value,
            ])->values(),
        ])->values()),

        get filtered() {
            const q = this.search.trim().toLowerCase();
            if (!q) return this.macros;

            return this.macros.filter(m =>
                (m.name || '').toLowerCase().includes(q)
            );
        },

        setFieldValue(name, value = '') {
            const el = document.querySelector(`[name='${name}']`);
            if (!el) return false;

            if (el.tomselect) {
                el.tomselect.setValue(String(value || ''));
                el.dispatchEvent(new Event('change', { bubbles: true }));
                return true;
            }

            el.value = value ?? '';
            el.dispatchEvent(new Event('input', { bubbles: true }));
            el.dispatchEvent(new Event('change', { bubbles: true }));
            return true;
        },

        clearTomSelect(name) {
            const el = document.querySelector(`[name='${name}']`);
            if (!el || !el.tomselect) return;

            el.tomselect.clear();
            el.dispatchEvent(new Event('change', { bubbles: true }));
        },

        resetFields() {
            [
                'asunto',
                'descripcion',
                'observaciones',
                'tipo_ticket',
                'prioridad',
                'estado',
                'canal_ingreso',
                'fecha_resolucion'
            ].forEach(name => {
                const el = document.querySelector(`[name='${name}']`);
                if (!el) return;

                el.value = '';
                el.dispatchEvent(new Event('input', { bubbles: true }));
                el.dispatchEvent(new Event('change', { bubbles: true }));
            });

            [
                'id_ciudadano',
                'id_agente_asignado',
                'id_direccion_municipal',
                'id_servicio'
            ].forEach(name => this.clearTomSelect(name));

            window.dispatchEvent(new CustomEvent('set-tags', {
                detail: { tags: [] }
            }));
        },

        applyMacro(macro) {
            this.resetFields();

            let direccionValue = null;
            let servicioValue = null;

            macro.actions.forEach(action => {
                const field = action.field;
                const value = action.value ?? '';

                if (!field) return;

                // Tags
                if (field === 'tags') {
                    window.dispatchEvent(new CustomEvent('set-tags', {
                        detail: {
                            tags: String(value)
                                .split(',')
                                .map(t => t.trim())
                                .filter(Boolean)
                        }
                    }));
                    return;
                }

                // Canal guardado por id, aplicado al input/select visible del formulario
                if (field === 'id_canal' || field === 'id_canal_ingreso') {
                    const canal = this.canales.find(c => String(c.id) === String(value));
                    if (!canal) return;

                    // Ajusta aquí según el name real de tu formulario
                    this.setFieldValue('canal_ingreso', canal.nombre);
                    return;
                }

                // Aplicar después en orden
                if (field === 'id_direccion_municipal') {
                    direccionValue = value;
                    return;
                }

                if (field === 'id_servicio') {
                    servicioValue = value;
                    return;
                }

                this.setFieldValue(field, value);
            });

            // Dirección primero
            if (direccionValue) {
                this.setFieldValue('id_direccion_municipal', direccionValue);
            }

            // Servicio después de que se recarguen las opciones dependientes
            if (servicioValue) {
                setTimeout(() => {
                    this.setFieldValue('id_servicio', servicioValue);
                }, 250);
            }

            this.open = false;
            this.search = '';
        }
    }"
    class="fixed bottom-0 left-0 right-0 z-50 flex justify-center pointer-events-none"
    :class="{
        'xl:left-[290px]': $store.sidebar.isExpanded || $store.sidebar.isHovered,
        'xl:left-[90px]': !$store.sidebar.isExpanded && !$store.sidebar.isHovered,
        'left-0': $store.sidebar.isMobileOpen
    }"
>
    <div class="w-full max-w-screen-xl px-6 pointer-events-auto">

        <div
            x-show="open"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-2"
            @click.outside="open = false"
            class="mb-1 rounded-xl border border-gray-200 bg-white shadow-xl dark:border-gray-700 dark:bg-gray-900 overflow-hidden"
            style="max-height: 340px;"
        >
            <div class="flex items-center gap-3 border-b border-gray-100 px-4 py-2.5 dark:border-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                <span class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Aplicar macro
                </span>
            </div>

            {{-- buscador --}}
            <div class="border-b border-gray-100 px-4 py-2.5 dark:border-gray-800">
                <input
                    x-ref="searchInput"
                    x-model="search"
                    type="text"
                    placeholder="Buscar macro..."
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200"
                >
            </div>

            {{-- Lista de macros --}}
            <ul class="overflow-y-auto divide-y divide-gray-50 dark:divide-gray-800" style="max-height: 260px;">

                <template x-if="filtered.length === 0">
                    <li class="px-4 py-3 text-xs italic text-gray-400 dark:text-gray-600">
                        Sin coincidencias
                    </li>
                </template>

                <template x-for="macro in filtered" :key="macro.id">
                    <li>
                        <button
                            type="button"
                            @click="applyMacro(macro)"
                            class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-white/[0.04] transition-colors flex items-center justify-between group"
                        >
                            <span x-text="macro.name"></span>
                            <span class="text-xs text-gray-400 dark:text-gray-600 group-hover:text-brand-500 transition-colors">
                                Aplicar
                            </span>
                        </button>
                    </li>
                </template>

            </ul>
        </div>

        {{-- Botón tab inferior --}}
        <button
            type="button"
            @click="open = !open; if (open) setTimeout(() => $refs.searchInput?.focus(), 50)"
            class="flex items-center gap-2 rounded-t-xl border border-b-0 border-gray-200 bg-white px-4 py-2 text-xs font-medium text-gray-500 shadow-lg transition hover:text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:hover:text-white/90"
            :class="open ? 'text-brand-600 dark:text-brand-400' : ''"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>

            Macros

            <span
                class="ml-1 rounded-full bg-gray-100 px-1.5 py-0.5 text-xs text-gray-500 dark:bg-gray-800 dark:text-gray-400"
                x-text="macros.length"
            ></span>

            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
            </svg>
        </button>

    </div>
</div>

@include('components.ciudadanos.modal-crear')

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const opts = {
            create: false,
            allowEmptyOption: true
        };

        if (document.querySelector('#id_ciudadano') && !document.querySelector('#id_ciudadano')?.tomselect) {
            const tsCiudadano = new TomSelect('#id_ciudadano', {
                create: false,
                allowEmptyOption: true,
                placeholder: 'Selecciona un solicitante',
                onInitialize() {
                    // Inyectar botón al final del dropdown
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-3.5 w-3.5 mr-1.5 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Crear nuevo ciudadano
                    `;
                    btn.className = `
                        w-full text-left px-4 py-2.5 text-sm font-medium
                        text-brand-600 dark:text-brand-400
                        border-t border-gray-100 dark:border-gray-700
                        hover:bg-brand-50 dark:hover:bg-brand-900/20
                        transition-colors flex items-center
                    `;
                    btn.addEventListener('mousedown', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        this.close();
                        abrirModal('modalCrearCiudadano');
                    });

                    this.dropdown.appendChild(btn);
                }
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
                    if (opcionNuevo) opcionNuevo.hidden = true;

                    if (!estadoEl.value || estadoEl.value === 'Nuevo') {
                        estadoEl.value = 'Abierto';
                    }

                    estadoEl.dispatchEvent(new Event('change', { bubbles: true }));
                    estadoHint?.classList.remove('hidden');
                } else {
                    if (opcionNuevo) opcionNuevo.hidden = false;

                    estadoEl.value = 'Nuevo';
                    estadoEl.dispatchEvent(new Event('change', { bubbles: true }));
                    estadoHint?.classList.add('hidden');
                }
            });

            // aplicar estado inicial al cargar si viene old('id_agente_asignado')
            const valorInicialAgente = agenteTs.getValue();
            const opcionNuevo = estadoEl.querySelector('option[value="Nuevo"]');

            if (valorInicialAgente) {
                if (opcionNuevo) opcionNuevo.hidden = true;
                if (!estadoEl.value || estadoEl.value === 'Nuevo') {
                    estadoEl.value = 'Abierto';
                }
                estadoHint?.classList.remove('hidden');
            } else {
                if (opcionNuevo) opcionNuevo.hidden = false;
                estadoHint?.classList.add('hidden');
            }
        }
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const abrirModalCiudadano = @json($abrirModalCiudadano ?? false);
    const telefonoBuscado = @json($telefono_clean ?? '');

    if (abrirModalCiudadano) {
        abrirModal('modalCrearCiudadano');

        setTimeout(() => {
            const inputTelefono = document.getElementById('modal_telefono_principal');

            if (inputTelefono) {
                inputTelefono.value = telefonoBuscado;
                inputTelefono.dispatchEvent(new Event('input', { bubbles: true }));
                inputTelefono.dispatchEvent(new Event('change', { bubbles: true }));
                inputTelefono.focus();
            }
        }, 300);
    }
});
</script>
<script>
console.log('abrirModalCiudadano:', @json($abrirModalCiudadano ?? false));
console.log('telefono_clean:', @json($telefono_clean ?? ''));
</script>
@endpush

@endsection