@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Ticket #{{ $ticket->id }}" />

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

    <div class="flex gap-4 items-start">

        {{-- ════════════════════════════════
             COLUMNA IZQUIERDA — Metadata
        ════════════════════════════════ --}}
        <div class="w-64 shrink-0 space-y-3">

            <div class="rounded-xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-700 dark:bg-gray-900">

                {{-- Header --}}
                <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3 dark:border-gray-800">
                    <h2 class="text-sm font-semibold text-gray-800 dark:text-white/90">#{{ $ticket->id }}</h2>
                    <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs font-medium
                        @if($ticket->estado === 'Resuelto') bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-800
                        @elseif($ticket->estado === 'Pendiente') bg-gray-100 text-gray-700 border-gray-300 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700
                        @elseif($ticket->estado === 'Nuevo') bg-orange-50 text-orange-700 border-orange-200 dark:bg-orange-900/30 dark:text-orange-300 dark:border-orange-800
                        @elseif($ticket->estado === 'Abierto') bg-red-50 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800
                        @else bg-purple-50 text-purple-700 border-purple-200 dark:bg-purple-900/30 dark:text-purple-300 dark:border-purple-800
                        @endif">
                        {{ $ticket->estado }}
                    </span>
                </div>

                {{-- Formulario metadata --}}
                <form action="{{ route('agente.tickets.update', $ticket->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="space-y-4 p-4">

                        {{-- Agente --}}
                        <div>
                            <label class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Agente asignado
                            </label>
                            <select id="id_agente_asignado" name="id_agente_asignado" class="w-full">
                                <option value="">–</option>
                                @foreach($agentes as $a)
                                <option value="{{ $a->id }}"
                                    {{ (string)$ticket->id_agente_asignado === (string)$a->id ? 'selected' : '' }}>
                                    {{ $a->nombre }} {{ $a->apellido }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Estado --}}
                        <div>
                            <label for="estado" class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Estado
                            </label>
                            <select name="estado" id="estado"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-9 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                <option value="Nuevo" {{ $ticket->estado == 'Nuevo'     ? 'selected' : '' }}>Nuevo</option>
                                <option value="Abierto" {{ $ticket->estado == 'Abierto'   ? 'selected' : '' }}>Abierto</option>
                                <option value="Pendiente" {{ $ticket->estado == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="Resuelto" {{ $ticket->estado == 'Resuelto'  ? 'selected' : '' }}>Resuelto</option>
                                @if($estados->isNotEmpty())
                                <optgroup label="─ Personalizados ─">
                                    @foreach($estados as $estadoCustom)
                                    <option value="{{ $estadoCustom->nombre_agente }}"
                                        {{ $ticket->estado == $estadoCustom->nombre_agente ? 'selected' : '' }}>
                                        {{ $estadoCustom->nombre_agente }}
                                    </option>
                                    @endforeach
                                </optgroup>
                                @endif
                            </select>
                        </div>

                        {{-- Prioridad --}}
                        <div>
                            <label for="prioridad" class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Prioridad
                            </label>
                            <select name="prioridad" id="prioridad"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-9 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                <option value="">Sin prioridad</option>
                                <option value="Baja" {{ $ticket->prioridad == 'Baja'    ? 'selected' : '' }}>Baja</option>
                                <option value="Media" {{ $ticket->prioridad == 'Media'   ? 'selected' : '' }}>Media</option>
                                <option value="Alta" {{ $ticket->prioridad == 'Alta'    ? 'selected' : '' }}>Alta</option>
                                <option value="Urgente" {{ $ticket->prioridad == 'Urgente' ? 'selected' : '' }}>Urgente</option>
                            </select>
                        </div>

                        {{-- Tipo --}}
                        <div>
                            <label for="tipo_ticket" class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Tipo
                            </label>
                            <select name="tipo_ticket" id="tipo_ticket"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-9 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                <option value="">Sin tipo</option>
                                <option value="Pregunta" {{ $ticket->tipo_ticket == 'Pregunta'  ? 'selected' : '' }}>Pregunta</option>
                                <option value="Incidente" {{ $ticket->tipo_ticket == 'Incidente' ? 'selected' : '' }}>Incidente</option>
                                <option value="Problema" {{ $ticket->tipo_ticket == 'Problema'  ? 'selected' : '' }}>Problema</option>
                                <option value="Tarea" {{ $ticket->tipo_ticket == 'Tarea'     ? 'selected' : '' }}>Tarea</option>
                            </select>
                        </div>

                        {{-- Dirección + Servicio --}}
                        <div
                            x-data="{
                                servicios: @js($servicios),
                                direccion: @js((string)($ticket->id_direccion_municipal ?? '')),
                                servicio:  @js((string)($ticket->id_servicio ?? '')),
                                tsDireccion: null, tsServicio: null,
                                init() {
                                    this.$nextTick(() => {
                                        const dirEl  = document.getElementById('id_direccion_municipal');
                                        const servEl = document.getElementById('id_servicio');
                                        if (!dirEl || !servEl) return;
                                        this.tsDireccion = dirEl.tomselect  ?? new TomSelect(dirEl,  { create: false, allowEmptyOption: true });
                                        this.tsServicio  = servEl.tomselect ?? new TomSelect(servEl, { create: false, allowEmptyOption: true });
                                        this.tsDireccion.off('change');
                                        this.tsDireccion.on('change', () => { this.direccion = String(this.tsDireccion.getValue() || ''); this.refreshServicios(); });
                                        this.tsServicio.off('change');
                                        this.tsServicio.on('change',  () => { this.servicio  = String(this.tsServicio.getValue()  || ''); });
                                        if (this.direccion) this.tsDireccion.setValue(this.direccion, true);
                                        this.refreshServicios();
                                        if (this.servicio) this.tsServicio.setValue(this.servicio, true);
                                    });
                                },
                                refreshServicios() {
                                    if (!this.tsServicio) return;
                                    const dir      = String(this.tsDireccion?.getValue?.() || this.direccion || '');
                                    const selected = String(this.servicio || '');
                                    this.tsServicio.clearOptions();
                                    this.tsServicio.addOption({ value: '', text: 'Selecciona un servicio' });
                                    const filtrados = (this.servicios || []).filter(s => String(s.id_direccion_municipal) === dir);
                                    filtrados.forEach(s => this.tsServicio.addOption({ value: String(s.id), text: s.nombre_servicio }));
                                    this.tsServicio.refreshOptions(false);
                                    if (selected && filtrados.some(s => String(s.id) === selected)) { this.tsServicio.setValue(selected, true); return; }
                                    if (selected) { this.servicio = ''; this.tsServicio.clear(true); }
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
                                    <option value="{{ $d->id }}"
                                        {{ (string)$ticket->id_direccion_municipal === (string)$d->id ? 'selected' : '' }}>
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
                            </div>
                        </div>

                    </div>

                    {{-- Botón guardar metadata --}}
                    <div class="border-t border-gray-100 px-4 py-3 dark:border-gray-800">
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600 focus:outline-none focus:ring-3 focus:ring-brand-500/20 dark:bg-brand-500 dark:hover:bg-brand-600">
                            Guardar cambios
                        </button>
                    </div>

                </form>
            </div>

            {{-- Marcar como resuelto --}}
            @if($ticket->estado !== 'Resuelto')
            <form action="{{ route('agente.tickets.resolver', $ticket->id) }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full inline-flex items-center justify-center rounded-xl border border-blue-200 bg-blue-50 px-4 py-2.5 text-sm font-medium text-blue-700 hover:bg-blue-100 dark:border-blue-800 dark:bg-blue-900/20 dark:text-blue-300 dark:hover:bg-blue-900/40">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    Marcar como resuelto
                </button>
            </form>
            @endif

        </div>

        {{-- ════════════════════════════════
             COLUMNA CENTRAL — Conversación
        ════════════════════════════════ --}}
        <div class="flex-1 min-w-0 flex flex-col gap-3">

            {{-- Info del ticket --}}
            <div class="rounded-xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-700 dark:bg-gray-900">
                <div class="border-b border-gray-100 px-5 py-3 dark:border-gray-800">
                    <h1 class="text-base font-semibold text-gray-800 dark:text-white/90">{{ $ticket->asunto }}</h1>
                    <div class="mt-1 flex items-center gap-3 text-xs text-gray-400 dark:text-gray-600">
                        <span>Creado {{ $ticket->created_at->diffForHumans() }}</span>
                        @if($ticket->canal)
                        <span>· {{ $ticket->canal->nombre }}</span>
                        @endif
                    </div>
                </div>
                @if($ticket->descripcion)
                <div class="px-5 py-4">
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $ticket->descripcion }}</p>
                </div>
                @endif
            </div>

            {{-- Hilo de respuestas --}}
            @if($ticket->respuestas->isNotEmpty())
            <div class="space-y-3">
                @foreach($ticket->respuestas as $respuesta)
                <div class="rounded-xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-700 dark:bg-gray-900">
                    <div class="flex items-center justify-between border-b border-gray-100 px-5 py-3 dark:border-gray-800">
                        <div class="flex items-center gap-2">
                            {{-- Avatar inicial --}}
                            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-brand-100 text-xs font-semibold text-brand-700 dark:bg-brand-900/30 dark:text-brand-300">
                                {{ strtoupper(substr($respuesta->usuario->nombre ?? 'A', 0, 1)) }}
                            </span>
                            <span class="text-sm font-medium text-gray-800 dark:text-white/90">
                                {{ $respuesta->usuario->nombre ?? 'Agente' }}
                                {{ $respuesta->usuario->apellido ?? '' }}
                            </span>
                        </div>
                        <span class="text-xs text-gray-400 dark:text-gray-600">
                            {{ $respuesta->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <div class="px-5 py-4">
                        <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $respuesta->contenido }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            {{-- Caja de respuesta --}}
            <div class="rounded-xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-700 dark:bg-gray-900">
                <form action="{{ route('agente.tickets.responder', $ticket->id) }}" method="POST">
                    @csrf

                    <div class="border-b border-gray-100 px-5 py-3 dark:border-gray-800">
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-600">
                            Respuesta pública
                        </p>
                    </div>

                    <div class="px-5 py-4">
                        <textarea name="contenido" rows="4"
                            placeholder="Escribe tu respuesta..."
                            class="w-full bg-transparent text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none dark:text-white/90 dark:placeholder:text-white/30 resize-none">{{ old('contenido') }}</textarea>
                    </div>

                    <div class="flex items-center justify-between border-t border-gray-100 px-5 py-3 dark:border-gray-800">
                        <span class="text-xs text-gray-400 dark:text-gray-600">
                            Visible para el solicitante
                        </span>
                        <x-ui.button size="sm" variant="primary" type="submit">
                            Enviar respuesta
                        </x-ui.button>
                    </div>

                </form>
            </div>

            {{-- Adjuntos existentes --}}
            @php $adjuntos = is_array($ticket->adjuntos) ? $ticket->adjuntos : []; @endphp
            @if (!empty($adjuntos))
            <ul class="space-y-2">
                @foreach ($adjuntos as $a)
                <li class="flex items-center justify-between rounded-lg border border-gray-100 px-3 py-2 dark:border-gray-800">
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-xs font-medium text-gray-700 dark:text-gray-300">
                            {{ $a['nombre_original'] ?? 'Archivo' }}
                        </p>
                        @if (!empty($a['tamano']))
                        <p class="text-xs text-gray-400">{{ round($a['tamano'] / 1024, 1) }} KB</p>
                        @endif
                    </div>
                    @if (!empty($a['ruta']))
                    <button
                        type="button"
                        onclick="abrirPreview('{{ asset('storage/' . $a['ruta']) }}', '{{ addslashes($a['nombre_original']) }}', '{{ $a['mime'] }}')"
                        class="ml-2 shrink-0 text-xs font-medium text-brand-600 hover:underline dark:text-brand-400">
                        Ver
                    </button>
                    @endif
                </li>
                @endforeach
            </ul>
            <div class="border-t border-gray-100 dark:border-gray-800"></div>
            @endif

        </div>

        {{-- ════════════════════════════════
             COLUMNA DERECHA — Ciudadano
        ════════════════════════════════ --}}
        <div class="w-56 shrink-0 space-y-3">

            {{-- Info ciudadano --}}
            <div class="rounded-xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-700 dark:bg-gray-900">
                <div class="border-b border-gray-100 px-4 py-3 dark:border-gray-800">
                    <h3 class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Solicitante</h3>
                </div>
                <div class="p-4 space-y-3">

                    {{-- Avatar + nombre --}}
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gray-100 text-sm font-semibold text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                            {{ strtoupper(substr($ticket->ciudadano->nombre ?? '?', 0, 1)) }}
                        </span>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium text-gray-800 dark:text-white/90">
                                {{ $ticket->ciudadano->nombre ?? '–' }}
                                {{ $ticket->ciudadano->apellido_paterno ?? '' }}
                            </p>
                        </div>
                    </div>

                    {{-- Correo --}}
                    @if($ticket->ciudadano?->email)
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-600 mb-0.5">Correo</p>
                        <a href="mailto:{{ $ticket->ciudadano->email }}"
                            class="text-xs text-brand-600 hover:underline dark:text-brand-400 break-all">
                            {{ $ticket->ciudadano->email }}
                        </a>
                    </div>
                    @endif

                    {{-- Teléfono --}}
                    @if($ticket->ciudadano?->telefono_principal)
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-600 mb-0.5">Teléfono</p>
                        <a href="tel:{{ $ticket->ciudadano->telefono_principal }}"
                            class="text-xs text-gray-700 dark:text-gray-300">
                            {{ $ticket->ciudadano->telefono_principal }}
                        </a>
                    </div>
                    @endif

                </div>
            </div>

            {{-- Detalles del ticket --}}
            <div class="rounded-xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-700 dark:bg-gray-900">
                <div class="border-b border-gray-100 px-4 py-3 dark:border-gray-800">
                    <h3 class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Detalles</h3>
                </div>
                <div class="p-4 space-y-3">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-600 mb-0.5">Creado</p>
                        <p class="text-xs text-gray-700 dark:text-gray-300">{{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-600 mb-0.5">Actualizado</p>
                        <p class="text-xs text-gray-700 dark:text-gray-300">{{ $ticket->updated_at->diffForHumans() }}</p>
                    </div>

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
                        @forelse($tickets_creados as $ticket_creado)
                        <div class="rounded-lg border border-gray-100 p-3 dark:border-gray-800">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-800 dark:text-white/90 truncate">
                                        {{ $ticket_creado->asunto }}
                                    </p>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        Creado: {{ $ticket_creado->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>

                                <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium
                                @if($ticket_creado->estado === 'Resuelto') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400
                                @elseif($ticket_creado->estado === 'Pendiente') bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300
                                @elseif($ticket_creado->estado === 'Nuevo') bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400
                                @elseif($ticket_creado->estado === 'Abierto') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                                @else bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300
                                @endif">
                                    {{ $ticket_creado->estado }}
                                </span>
                            </div>
                            <!--
                            <div class="mt-3 grid grid-cols-1 gap-2 sm:grid-cols-2">
                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-600 mb-0.5">
                                        Prioridad
                                    </p>
                                    <span class="text-xs font-medium
                                        @if($ticket_creado->prioridad === 'Urgente') text-red-600 dark:text-red-400
                                        @elseif($ticket_creado->prioridad === 'Alta') text-orange-600 dark:text-orange-400
                                        @elseif($ticket_creado->prioridad === 'Media') text-yellow-600 dark:text-yellow-400
                                        @else text-gray-600 dark:text-gray-400
                                        @endif">
                                        {{ $ticket_creado->prioridad ?? 'Sin prioridad' }}
                                    </span>
                                </div>

                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-600 mb-0.5">
                                        Tipo
                                    </p>
                                    <p class="text-xs text-gray-700 dark:text-gray-300">
                                        {{ $ticket_creado->tipo_ticket ?? 'Sin tipo' }}
                                    </p>
                                </div>
                            </div>
-->
                        </div>
                        @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Este solicitante no tiene otros tickets recientes.
                        </p>
                        @endforelse
                    </div>
                </div>

        </div>

    </div>
</div>

@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const opts = {
            create: false,
            allowEmptyOption: true
        };

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
    });
</script>
@endpush

@endsection