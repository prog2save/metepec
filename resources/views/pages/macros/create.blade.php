@extends('layouts.app')

@section('title', 'Nuevo Macro')

@section('content')
<x-common.page-breadcrumb pageTitle="Nuevo macro" />

<form action="{{ route('macros.store') }}" method="POST">
@csrf

<div class="flex gap-4 items-start">

    {{-- ══════════════════════════
         COLUMNA IZQUIERDA
    ══════════════════════════ --}}
    <div class="w-72 shrink-0 space-y-3">
        <div class="rounded-xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-700 dark:bg-gray-900">

            <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3 dark:border-gray-800">
                <h2 class="text-sm font-semibold text-gray-800 dark:text-white/90">Nuevo macro</h2>
            </div>

            <div class="space-y-4 p-4">

                {{-- Nombre --}}
                <div>
                    <label class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                        Nombre <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        placeholder="Ej. Escalar a soporte técnico"
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800
                               h-9 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm
                               text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden
                               dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                    @error('name')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Descripción --}}
                <div>
                    <label class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                        Descripción
                        <span class="ml-1 normal-case font-normal text-gray-400">(opcional)</span>
                    </label>
                    <textarea name="description" rows="3"
                        placeholder="¿Para qué sirve este macro?"
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800
                               w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm
                               text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden
                               dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30
                               resize-none">{{ old('description') }}</textarea>
                </div>

                {{-- Disponible para --}}
                <div>
                    <label class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                        Disponible para
                    </label>
                    <div class="h-9 w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm
                                text-gray-400 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-600">
                        Todos los agentes
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ══════════════════════════
         COLUMNA DERECHA — Acciones
    ══════════════════════════ --}}
    <div class="flex-1 min-w-0">
        <div class="rounded-xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-700 dark:bg-gray-900">

            <div class="border-b border-gray-100 px-5 py-3 dark:border-gray-800">
                <h3 class="text-sm font-semibold text-gray-800 dark:text-white/90">Acciones</h3>
                <p class="text-xs text-gray-400 dark:text-gray-600 mt-0.5">
                    Agrega las acciones que se aplicarán al ticket al ejecutar este macro.
                </p>
            </div>

            {{-- Lista de acciones dinámica con Alpine --}}
            <div
                x-data="{
                    actions: @js(old('actions', [['field' => '', 'value' => '']])),
                    fields: @js($fields),
                    ciudadanos: @js($ciudadanos),
                    agentes: @js($agentes),
                    direcciones: @js($direcciones),
                    servicios: @js($servicios),
                    canales: @js($canales),
                    estados: @js($estados),
                    addAction() {
                        this.actions.push({ field: '', value: '' });
                    },
                    removeAction(i) {
                        if (this.actions.length > 1) this.actions.splice(i, 1);
                    },
                    filteredServicios(direccionId) {
                        return this.servicios.filter(s => String(s.id_direccion_municipal) === String(direccionId));
                    }
                }"
                class="divide-y divide-gray-100 dark:divide-gray-800"
            >
                <template x-for="(action, i) in actions" :key="i">
                    <div class="flex items-start gap-3 px-5 py-4">

                        {{-- Select del campo --}}
                        <div class="w-52 shrink-0">
                            <select
                                :name="`actions[${i}][field]`"
                                x-model="action.field"
                                @change="action.value = ''"
                                class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800
                                       h-9 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm
                                       text-gray-800 focus:ring-3 focus:outline-hidden
                                       dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                <option value="">Selecciona un campo</option>
                                <template x-for="(label, key) in fields" :key="key">
                                    <option :value="key" x-text="label" :selected="action.field === key"></option>
                                </template>
                            </select>
                        </div>

                        {{-- Input del valor según el campo seleccionado --}}
                        <div class="flex-1">

                            {{-- Ciudadano --}}
                            <template x-if="action.field === 'id_ciudadano'">
                                <select :name="`actions[${i}][value]`" x-model="action.value"
                                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-9 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                    <option value="">Selecciona un ciudadano</option>
                                    <template x-for="c in ciudadanos" :key="c.id">
                                        <option :value="c.id" x-text="`${c.nombre} ${c.apellido_paterno} ${c.apellido_materno}`" :selected="action.value == c.id"></option>
                                    </template>
                                </select>
                            </template>

                            {{-- Agente --}}
                            <template x-if="action.field === 'id_agente_asignado'">
                                <select :name="`actions[${i}][value]`" x-model="action.value"
                                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-9 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                    <option value="">Selecciona un agente</option>
                                    <template x-for="a in agentes" :key="a.id">
                                        <option :value="a.id" x-text="`${a.nombre} ${a.apellido}`" :selected="action.value == a.id"></option>
                                    </template>
                                </select>
                            </template>

                            {{-- Dirección municipal --}}
                            <template x-if="action.field === 'id_direccion_municipal'">
                                <select :name="`actions[${i}][value]`" x-model="action.value"
                                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-9 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                    <option value="">Selecciona una dirección</option>
                                    <template x-for="d in direcciones" :key="d.id">
                                        <option :value="d.id" x-text="d.nombre_direccion" :selected="action.value == d.id"></option>
                                    </template>
                                </select>
                            </template>

                            {{-- Servicio --}}
                            <template x-if="action.field === 'id_servicio'">
                                <select :name="`actions[${i}][value]`" x-model="action.value"
                                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-9 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                    <option value="">Selecciona un servicio</option>
                                    <template x-for="s in servicios" :key="s.id">
                                        <option :value="s.id" x-text="s.nombre_servicio" :selected="action.value == s.id"></option>
                                    </template>
                                </select>
                            </template>

                            {{-- Canal de ingreso --}}
                            <template x-if="action.field === 'id_canal'">
                                <select :name="`actions[${i}][value]`" x-model="action.value"
                                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-9 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                    <option value="">Selecciona un canal</option>
                                    <template x-for="c in canales" :key="c.id">
                                        <option :value="c.id" x-text="c.nombre" :selected="action.value == c.id"></option>
                                    </template>
                                </select>
                            </template>

                            {{-- Estado --}}
                            <template x-if="action.field === 'estado'">
                                <select :name="`actions[${i}][value]`" x-model="action.value"
                                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-9 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                    <option value="">Selecciona un estado</option>
                                    <option value="Nuevo" :selected="action.value === 'Nuevo'">Nuevo</option>
                                    <option value="Abierto" :selected="action.value === 'Abierto'">Abierto</option>
                                    <option value="Pendiente" :selected="action.value === 'Pendiente'">Pendiente</option>
                                    <option value="Resuelto" :selected="action.value === 'Resuelto'">Resuelto</option>
                                    @if($estados->isNotEmpty())
                                    <optgroup label="─ Personalizados ─">
                                        @foreach($estados as $estadoCustom)
                                        <option value="{{ $estadoCustom->nombre_agente }}"
                                            :selected="action.value === '{{ $estadoCustom->nombre_agente }}'">
                                            {{ $estadoCustom->nombre_agente }}
                                        </option>
                                        @endforeach
                                    </optgroup>
                                    @endif
                                </select>
                            </template>

                            {{-- Prioridad --}}
                            <template x-if="action.field === 'prioridad'">
                                <select :name="`actions[${i}][value]`" x-model="action.value"
                                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-9 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                    <option value="">Selecciona una prioridad</option>
                                    <option value="Baja"    :selected="action.value === 'Baja'">Baja</option>
                                    <option value="Media"   :selected="action.value === 'Media'">Media</option>
                                    <option value="Alta"    :selected="action.value === 'Alta'">Alta</option>
                                    <option value="Urgente" :selected="action.value === 'Urgente'">Urgente</option>
                                </select>
                            </template>

                            {{-- Tipo de ticket --}}
                            <template x-if="action.field === 'tipo_ticket'">
                                <select :name="`actions[${i}][value]`" x-model="action.value"
                                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-9 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                    <option value="">Selecciona un tipo</option>
                                    <option value="Pregunta"  :selected="action.value === 'Pregunta'">Pregunta</option>
                                    <option value="Incidente" :selected="action.value === 'Incidente'">Incidente</option>
                                    <option value="Problema"  :selected="action.value === 'Problema'">Problema</option>
                                    <option value="Tarea"     :selected="action.value === 'Tarea'">Tarea</option>
                                </select>
                            </template>

                            {{-- Asunto --}}
                            <template x-if="action.field === 'asunto'">
                                <input type="text" :name="`actions[${i}][value]`" x-model="action.value"
                                    placeholder="Asunto del ticket"
                                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-9 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                            </template>

                            {{-- Descripción --}}
                            <template x-if="action.field === 'descripcion'">
                                <textarea :name="`actions[${i}][value]`" x-model="action.value"
                                    rows="2" placeholder="Descripción del ticket"
                                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 resize-none"></textarea>
                            </template>

                            {{-- Tags --}}
                            <template x-if="action.field === 'tags'">
                                <div
                                    x-data="{
                                        tags: action.value ? action.value.split(',').filter(t => t.trim()) : [],
                                        input: '',
                                        addTag() {
                                            const val = this.input.trim();
                                            if (val && !this.tags.includes(val)) {
                                                this.tags.push(val);
                                                action.value = this.tags.join(',');
                                            }
                                            this.input = '';
                                        },
                                        removeTag(idx) {
                                            this.tags.splice(idx, 1);
                                            action.value = this.tags.join(',');
                                        }
                                    }"
                                >
                                    <input type="hidden" :name="`actions[${i}][value]`" :value="tags.join(',')">
                                    <div class="min-h-[2.25rem] w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1.5
                                                flex flex-wrap gap-1 items-center cursor-text
                                                focus-within:border-brand-300 focus-within:ring-3 focus-within:ring-brand-500/10
                                                dark:border-gray-700 dark:bg-gray-900"
                                        @click="$refs.taginput.focus()">
                                        <template x-for="(tag, idx) in tags" :key="idx">
                                            <span class="inline-flex items-center gap-1 rounded-full bg-indigo-50 border border-indigo-200
                                                         px-2 py-0.5 text-xs font-medium text-indigo-700
                                                         dark:bg-indigo-900/30 dark:border-indigo-800 dark:text-indigo-300">
                                                <span x-text="tag"></span>
                                                <button type="button" @click.stop="removeTag(idx)"
                                                    class="text-indigo-400 hover:text-indigo-700 leading-none">&#x2715;</button>
                                            </span>
                                        </template>
                                        <input x-ref="taginput" x-model="input" type="text"
                                            @keydown.space.prevent="addTag()"
                                            @keydown.enter.prevent="addTag()"
                                            @keydown.backspace="input === '' && (tags.pop(), action.value = tags.join(','))"
                                            @blur="addTag()"
                                            placeholder="Escribe y presiona espacio..."
                                            class="flex-1 min-w-[100px] bg-transparent text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none dark:text-white/90 dark:placeholder:text-white/30" />
                                    </div>
                                </div>
                            </template>

                            {{-- Fallback si no hay campo seleccionado --}}
                            <template x-if="action.field === ''">
                                <div class="h-9 w-full rounded-lg border border-dashed border-gray-200 dark:border-gray-700"></div>
                            </template>

                        </div>

                        {{-- Botón eliminar acción --}}
                        <button type="button" @click="removeAction(i)"
                            class="mt-1.5 text-gray-300 hover:text-red-400 dark:text-gray-700 dark:hover:text-red-400 transition-colors"
                            :class="{ 'opacity-30 cursor-not-allowed': actions.length === 1 }"
                            :disabled="actions.length === 1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                    </div>
                </template>

                {{-- Agregar acción --}}
                <div class="px-5 py-3">
                    <button type="button" @click="addAction()"
                        class="text-sm font-medium text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300">
                        + Agregar acción
                    </button>
                </div>

            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-between border-t border-gray-100 px-5 py-3 dark:border-gray-800">
                <a href="{{ route('macros.index') }}"
                    class="text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    Cancelar
                </a>
                <x-ui.button size="sm" variant="primary" type="submit">
                    Crear macro
                </x-ui.button>
            </div>

        </div>
    </div>

</div>
</form>
@endsection