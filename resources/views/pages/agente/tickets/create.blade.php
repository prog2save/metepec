@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Crear Ticket" />

<style>
    /* ===== Textareas: quitar h-11 y dar altura real ===== */
    #descripcion,
    #observaciones {
        min-height: 120px;
        resize: vertical;
    }

    /* ===== TomSelect: igualar a tus inputs ===== */
    .ts-wrapper {
        width: 100%;
    }

    .ts-control {
        height: 2.75rem;
        /* h-11 */
        width: 100%;
        border-radius: .5rem;
        /* rounded-lg */
        border: 1px solid rgb(209 213 219);
        /* border-gray-300 */
        background: transparent;
        padding: .625rem 1rem;
        /* aprox px-4 + py-2.5 */
        font-size: .875rem;
        /* text-sm */
        line-height: 1.25rem;
        color: rgb(31 41 55);
        /* text-gray-800 */
        display: flex;
        align-items: center;
        gap: .5rem;
        box-shadow: var(--shadow-theme-xs, 0 1px 2px rgba(0, 0, 0, .05));
        /* intenta respetar tu shadow-theme-xs */
    }

    /* Dark mode (Tailwind dark en html/body) */
    .dark .ts-control {
        border-color: rgb(55 65 81);
        /* dark:border-gray-700 */
        background-color: rgb(17 24 39);
        /* dark:bg-gray-900 */
        color: rgba(255, 255, 255, .90);
        /* dark:text-white/90 */
    }

    /* Input interno de búsqueda */
    .ts-control>input {
        margin: 0 !important;
        padding: 0 !important;
        font-size: .875rem !important;
        line-height: 1.25rem !important;
        color: rgb(31 41 55) !important;
    }

    .dark .ts-control>input {
        color: rgba(255, 255, 255, .90) !important;
    }

    /* Placeholder */
    .ts-control .item[data-value=""],
    .ts-control input::placeholder {
        color: rgb(156 163 175) !important;
        /* gray-400 */
    }

    .dark .ts-control .item[data-value=""],
    .dark .ts-control input::placeholder {
        color: rgba(255, 255, 255, .30) !important;
        /* white/30 */
    }

    /* Focus ring como tus inputs */
    .ts-wrapper.focus .ts-control,
    .ts-control:focus-within {
        outline: none;
        border-color: rgb(147 197 253);
        /* aprox focus:border-brand-300 (ajusta si tu brand difiere) */
        box-shadow: 0 0 0 3px rgba(59, 130, 246, .10);
        /* ring-brand-500/10 aprox */
    }

    .dark .ts-wrapper.focus .ts-control,
    .dark .ts-control:focus-within {
        border-color: rgb(30 64 175);
        /* aprox dark:focus:border-brand-800 */
    }

    /* Flechita del dropdown */
    .ts-control .ts-dropdown-toggle {
        margin-left: auto;
        opacity: .7;
    }

    /* Dropdown */
    .ts-dropdown {
        margin-top: .25rem;
        border-radius: .5rem;
        border: 1px solid rgb(229 231 235);
        /* gray-200 */
        background: #fff;
        overflow: hidden;
        box-shadow: 0 10px 15px rgba(0, 0, 0, .08);
        z-index: 50;
    }

    .dark .ts-dropdown {
        border-color: rgb(55 65 81);
        /* gray-700 */
        background: rgb(17 24 39);
        /* gray-900 */
    }

    .ts-dropdown .ts-dropdown-content {
        max-height: 15rem;
        overflow: auto;
    }

    .ts-dropdown .option {
        padding: .5rem 1rem;
        font-size: .875rem;
        line-height: 1.25rem;
        cursor: pointer;
        color: rgb(55 65 81);
        /* gray-700 */
    }

    .dark .ts-dropdown .option {
        color: rgb(229 231 235);
        /* gray-200 */
    }

    .ts-dropdown .option.active {
        background: rgb(243 244 246);
        /* gray-100 */
    }

    .dark .ts-dropdown .option.active {
        background: rgb(31 41 55);
        /* gray-800 */
    }

    .ts-dropdown .option.selected {
        background: rgba(59, 130, 246, .08);
        /* brand-50 aprox */
        color: rgb(29 78 216);
        /* brand-700 aprox */
    }

    .dark .ts-dropdown .option.selected {
        background: rgb(31 41 55);
        color: rgba(255, 255, 255, .90);
    }

    .ts-control {
        background-color: transparent !important;
    }

    /* Fondo del input interno también transparente (por si el navegador lo pinta) */
    .ts-control>input {
        background-color: transparent !important;
    }

    /* Cuando TomSelect entra en focus, forzamos el fondo (claro) */
    .ts-wrapper.focus .ts-control,
    .ts-control:focus-within {
        background-color: transparent !important;
    }

    /* Dark mode: fondo consistente, incluso en focus */
    .dark .ts-control {
        background-color: rgb(17 24 39) !important;
        /* gray-900 */
    }

    .dark .ts-wrapper.focus .ts-control,
    .dark .ts-control:focus-within {
        background-color: rgb(17 24 39) !important;
    }

    /* FIX extra: algunos navegadores usan text-fill-color */
    .ts-control>input {
        -webkit-text-fill-color: rgb(31 41 55) !important;
        /* text-gray-800 */
    }

    .dark .ts-control>input {
        -webkit-text-fill-color: rgba(255, 255, 255, .90) !important;
        /* white/90 */
    }

    /* Si el dropdown toggle/íconos se pierden en fondo, mantenlos visibles */
    .ts-control .ts-dropdown-toggle {
        background: transparent !important;
    }
</style>

<div class="space-y-6">

    @if (session('success'))
    <div class="space-y-4">
        <x-ui.alert variant="success" title="{{ session('success') }}" message="" :showLink="false" linkHref="/"
            linkText="Learn more" />
    </div>
    @endif

    @if ($errors->any())
    <x-ui.alert variant="error" title="Errores en el formulario">
        <div class="text-sm">
            <p class="font-medium mb-2">
                Por favor corrige los siguientes errores:
            </p>

            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </x-ui.alert>
    @endif

    <x-common.component-card title="Crear Nuevo Ticket">

        <form action="{{ route(name: 'agente.tickets.store') }}" method="post" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label for="asunto" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Asunto
                </label>
                <input type="text" id="asunto" name="asunto" value="{{ old('asunto') }}"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
            </div>

            <div>
                <label for="descripcion" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Descripción
                </label>
                {{-- Quitamos h-11 y damos min-height --}}
                <textarea name="descripcion" id="descripcion"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">{{ old('descripcion') }}</textarea>
            </div>

            {{-- Ciudadano (TomSelect) --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Ciudadano</label>
                <select id="id_ciudadano" name="id_ciudadano" class="w-full">
                    <option value="">Selecciona un ciudadano</option>
                    @foreach($ciudadanos as $c)
                    <option value="{{ $c->id }}" {{ old('id_ciudadano') == $c->id ? 'selected' : '' }}>
                        {{ $c->nombre }} {{ $c->apellido_paterno }} {{ $c->apellido_materno }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Agente (TomSelect) --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Agente (opcional)</label>
                <select id="id_agente_asignado" name="id_agente_asignado" class="w-full">
                    <option value="">Selecciona un agente</option>
                    @foreach($agentes as $a)
                    <option value="{{ $a->id }}" {{ old('id_agente_asignado') == $a->id ? 'selected' : '' }}>
                        {{ $a->nombre }} {{ $a->apellido}}
                    </option>
                    @endforeach
                </select>
            </div>

            <div
                x-data="{
                    servicios: @js($servicios),

                    // valores actuales (create usa old())
                    direccion: @js(old('id_direccion_municipal', '')),
                    servicio: @js(old('id_servicio', '')),

                    tsDireccion: null,
                    tsServicio: null,

                    init() {
                        this.$nextTick(() => {
                            const dirEl = document.getElementById('id_direccion_municipal');
                            const servEl = document.getElementById('id_servicio');
                            if (!dirEl || !servEl) return;

                            this.tsDireccion = dirEl.tomselect ?? new TomSelect(dirEl, {
                            create: false,
                            allowEmptyOption: true,
                            placeholder: 'Selecciona una dirección',
                            });

                            this.tsServicio = servEl.tomselect ?? new TomSelect(servEl, {
                            create: false,
                            allowEmptyOption: true,
                            placeholder: 'Selecciona un servicio',
                            });

                            // Listener dirección (toma valor real desde TomSelect)
                            this.tsDireccion.off('change');
                            this.tsDireccion.on('change', () => {
                            this.direccion = String(this.tsDireccion.getValue() || '');
                            this.refreshServicios();
                            });

                            // Listener servicio (opcional)
                            this.tsServicio.off('change');
                            this.tsServicio.on('change', () => {
                            this.servicio = String(this.tsServicio.getValue() || '');
                            });

                            // Si venían valores por old(), setéalos
                            if (this.direccion) this.tsDireccion.setValue(String(this.direccion), true);

                            // Aplica filtro inicial
                            this.refreshServicios();

                            if (this.servicio) this.tsServicio.setValue(String(this.servicio), true);
                        });
                        },

                        refreshServicios() {
                        if (!this.tsServicio) return;

                        const dir = String(this.tsDireccion?.getValue?.() || this.direccion || '');

                        console.log('direccion=', dir);
                        console.log('servicios total=', this.servicios?.length);

                        this.tsServicio.clearOptions();
                        this.tsServicio.addOption({ value: '', text: 'Selecciona un servicio' });

                        const filtrados = (this.servicios || []).filter(s => String(s.id_direccion_municipal) === dir);

                        filtrados.forEach(s => {
                            this.tsServicio.addOption({ value: String(s.id), text: s.nombre_servicio });
                        });

                        this.tsServicio.refreshOptions(false);

                        // Si el servicio ya no corresponde, límpialo
                        const actual = String(this.tsServicio.getValue() || '');
                        const ok = filtrados.some(s => String(s.id) === actual);

                        if (!ok) {
                            this.servicio = '';
                            this.tsServicio.clear(true);
                        }
                        },
                }"
                class="space-y-4">
                {{-- Dirección municipal --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
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

                {{-- Servicio (filtrado por dirección) --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Servicio
                    </label>

                    <select id="id_servicio" name="id_servicio" class="w-full">
                        <option value="">Selecciona un servicio</option>
                        {{-- Las opciones las gestiona refreshServicios() --}}
                    </select>

                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Selecciona una dirección para ver sus servicios.
                    </p>
                </div>
            </div>


            <div>
                <label for="canal_ingreso" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Canal de Ingreso
                </label>
                <input type="text" id="canal_ingreso" name="canal_ingreso" value="{{ old('canal_ingreso') }}"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
            </div>

            <div>
                <label for="tipo_ticket" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Tipo de Ticket
                </label>
                <select name="tipo_ticket" id="tipo_ticket"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                    <option value="">Selecciona un tipo de ticket</option>
                    <option value="Pregunta" {{ old('tipo_ticket') == 'Pregunta' ? 'selected' : '' }}>Pregunta</option>
                    <option value="Incidente" {{ old('tipo_ticket') == 'Incidente' ? 'selected' : '' }}>Incidente</option>
                    <option value="Problema" {{ old('tipo_ticket') == 'Problema' ? 'selected' : '' }}>Problema</option>
                    <option value="Tarea" {{ old('tipo_ticket') == 'Tarea' ? 'selected' : '' }}>Tarea</option>
                </select>
            </div>

            <div>
                <label for="prioridad" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Prioridad
                </label>
                <select name="prioridad" id="prioridad"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                    <option value="">Selecciona una prioridad</option>
                    <option value="Baja" {{ old('prioridad') == 'Baja' ? 'selected' : '' }}>Baja</option>
                    <option value="Media" {{ old('prioridad') == 'Media' ? 'selected' : '' }}>Media</option>
                    <option value="Alta" {{ old('prioridad') == 'Alta' ? 'selected' : '' }}>Alta</option>
                    <option value="Urgente" {{ old('prioridad') == 'Urgente' ? 'selected' : '' }}>Urgente</option>
                </select>
            </div>

            <div>
                <label for="estado" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Estado del ticket
                </label>
                <select name="estado" id="estado"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 disabled:opacity-60 disabled:cursor-not-allowed">
                    <option value="Nuevo" {{ old('estado', 'Nuevo') == 'Nuevo' ? 'selected' : '' }}>Nuevo</option>
                    <option value="Abierto" {{ old('estado') == 'Abierto' ? 'selected' : '' }}>Abierto</option>
                    <option value="Pendiente" {{ old('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="Resuelto" {{ old('estado') == 'Resuelto' ? 'selected' : '' }}>Resuelto</option>
                </select>
                <p id="estado-hint" class="mt-1 text-xs text-gray-500 dark:text-gray-400 hidden">
                    El estado se establece automáticamente a <strong>Abierto</strong> al asignar un agente.
                </p>
            </div>

            <div>
                <label for="fecha_resolucion"
                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Fecha de resolución (opcional)
                </label>

                <div class="relative">
                    <input
                        type="date"
                        id="fecha_resolucion"
                        name="fecha_resolucion"
                        value="{{ old('fecha_resolucion') }}"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 
                        dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 
                        bg-transparent px-4 pr-12 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 
                        focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 
                        dark:text-white/90 dark:placeholder:text-white/30" />

                    {{-- Botón para abrir el selector (modal) --}}
                    <button
                        type="button"
                        class="absolute right-2 top-1/2 -translate-y-1/2 inline-flex h-9 w-9 items-center justify-center
                            rounded-lg border border-gray-200 bg-white text-gray-600
                            hover:bg-gray-50 focus:outline-none focus:ring-3 focus:ring-brand-500/10
                            dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-800"
                        onclick="
                                const i = document.getElementById('fecha_resolucion');
                                if (!i) return;
                                // Chrome/Edge: abre el picker nativo
                                if (typeof i.showPicker === 'function') { i.showPicker(); return; }
                                // Fallback
                                i.focus(); i.click();
                            "
                        aria-label="Seleccionar fecha"
                        title="Seleccionar fecha">
                        {{-- icono calendario --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </button>
                </div>

            </div>

            <div>
                <label for="observaciones" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Observaciones
                </label>
                {{-- Quitamos h-11 y damos min-height --}}
                <textarea name="observaciones" id="observaciones"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">{{ old('observaciones') }}</textarea>
            </div>

            <div>
                <label for="adjuntos" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Archivos adjuntos (opcional)
                </label>

                <input type="file" id="adjuntos" name="adjuntos[]" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                    class="block w-full text-sm text-gray-800
                           file:mr-4 file:rounded-lg file:border-0
                           file:bg-gray-100 file:px-4 file:py-2
                           file:text-sm file:font-medium file:text-gray-700
                           hover:file:bg-gray-200
                           dark:text-white/90 dark:file:bg-gray-800 dark:file:text-gray-300" />

                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Puedes subir varios archivos. Formatos permitidos: JPG, PNG, PDF, DOC, DOCX.
                </p>
            </div>

            <div>
                <x-ui.button size="sm" variant="primary" type="submit" class="mt-4">Crear Ticket</x-ui.button>
            </div>
        </form>
    </x-common.component-card>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        console.log('DOMContentLoaded fired');
        console.log('agente tomselect ya existe?', !!document.querySelector('#id_agente_asignado')?.tomselect);
        // ... resto del código
    });

    document.addEventListener('DOMContentLoaded', () => {
        const opts = {
            create: false,
            allowEmptyOption: true,
            placeholder: 'Selecciona una opción',
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
                placeholder: 'Selecciona un agente (opcional)'
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
                if (val) {
                    estadoEl.value = 'Abierto';
                    estadoEl.disabled = true;
                    estadoHint?.classList.remove('hidden');
                } else {
                    estadoEl.value = 'Nuevo';
                    estadoEl.disabled = false;
                    estadoHint?.classList.add('hidden');
                }
            });
        }
    });
</script>
@endpush

@endsection