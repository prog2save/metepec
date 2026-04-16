@extends('layouts.app')
@section('title', 'Editar Vista')
@section('content')

<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-1">
        <a href="{{ route('ticket-views.index') }}" class="hover:text-brand-500 transition">Vistas</a>
        <span>/</span>
        <span>Editar vista</span>
    </div>
    <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">Editar vista</h1>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
        Modifica las condiciones, columnas y ordenamiento de la vista.
    </p>
</div>

@if ($errors->any())
<div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4 dark:border-red-800 dark:bg-red-900/20">
    <ul class="list-disc pl-5 text-sm text-red-600 dark:text-red-400">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('ticket-views.update', $ticketView) }}" method="POST" id="form-vista">
    @csrf
    @method('PUT')

    <div class="space-y-6">

        {{-- Nombre y descripción --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">

            <div class="mb-4">
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Nombre de la vista <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title', $ticketView->title) }}"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-white/[0.03] dark:text-white/90"
                    placeholder="Ej: Tickets sin asignar">
                @error('title')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Descripción</label>
                <input type="text" name="description" value="{{ old('description', $ticketView->description) }}"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-white/[0.03] dark:text-white/90"
                    placeholder="Descripción opcional">
            </div>

            <div class="mb-4">
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    ¿Quién tiene acceso? <span class="text-red-500">*</span>
                </label>
                <div class="flex flex-col gap-2 mt-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="radio" name="visibility" value="all_agents"
                            {{ old('visibility', $ticketView->visibility) === 'all_agents' ? 'checked' : '' }}
                            class="w-4 h-4 text-brand-500 border-gray-300 focus:ring-brand-500">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Cualquier agente</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="radio" name="visibility" value="only_me"
                            {{ old('visibility', $ticketView->visibility) === 'only_me' ? 'checked' : '' }}
                            class="w-4 h-4 text-brand-500 border-gray-300 focus:ring-brand-500">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Solo usted</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Estado</label>
                <div class="flex items-center gap-3 mt-2">
                    <input type="hidden" name="active" value="0">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="active" value="1"
                            {{ old('active', $ticketView->active) ? 'checked' : '' }}
                            class="w-4 h-4 text-brand-500 border-gray-300 rounded focus:ring-brand-500">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Vista activa</span>
                    </label>
                </div>
            </div>

        </div>

        {{-- Condiciones --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]"
            x-data="condicionesForm()">

            <h2 class="text-base font-medium text-gray-800 dark:text-white/90 mb-1">Condiciones <span class="text-red-500">*</span></h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                Controla lo que aparece en la vista usando las condiciones <strong>TODAS</strong> y <strong>CUALQUIERA</strong>.
            </p>

            {{-- Bloque ALL --}}
            <div class="mb-6">
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                    Cumplir <strong>TODAS</strong> las siguientes condiciones
                </p>

                <template x-for="(cond, index) in allConditions" :key="index">
                    <div class="flex items-center gap-3 mb-3">
                        <input type="hidden" :name="'conditions[' + allOffset + index + '][match_type]'" value="all">

                        <select :name="'conditions[' + allOffset + index + '][field]'" x-model="cond.field"
                            @change="onFieldChange(cond)"
                            class="rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                            <option value="">Campo</option>
                            @foreach($availableFields as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>

                        <select :name="'conditions[' + allOffset + index + '][operator]'"
                            x-model="cond.operator"
                            :disabled="!cond.field"
                            class="rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90 disabled:opacity-50">
                            <option value="">Operador</option>
                            <template x-for="op in getOperators(cond.field)" :key="op">
                                <option :value="op" :selected="cond.operator === op" x-text="operatorLabels[op]"></option>
                            </template>
                        </select>

                        <div class="flex-1">
                            <template x-if="needsValue(cond.operator)">
                                <div>
                                    <template x-if="cond.field === 'tickets.id_agente_asignado'">
                                        <select :name="'conditions[' + allOffset + index + '][value]'" x-model="cond.value"
                                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                                            <option value="">Seleccionar agente</option>
                                            @foreach($agentes as $a)
                                            <option value="{{ $a['id'] }}">{{ $a['nombre'] }}</option>
                                            @endforeach
                                        </select>
                                    </template>

                                    <template x-if="cond.field === 'tickets.id_ciudadano'">
                                        <select :name="'conditions[' + allOffset + index + '][value]'" x-model="cond.value"
                                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                                            <option value="">Seleccionar solicitante</option>
                                            @foreach($ciudadanos as $c)
                                            <option value="{{ $c['id'] }}">{{ $c['nombre'] }}</option>
                                            @endforeach
                                        </select>
                                    </template>

                                    <template x-if="cond.field === 'tickets.id_direccion_municipal'">
                                        <select :name="'conditions[' + allOffset + index + '][value]'" x-model="cond.value"
                                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                                            <option value="">Seleccionar dirección municipal</option>
                                            @foreach($direcciones as $d)
                                            <option value="{{ $d['id'] }}">{{ $d['nombre_direccion'] }}</option>
                                            @endforeach
                                        </select>
                                    </template>

                                    <template x-if="cond.field === 'tickets.estado'">
                                        <select :name="'conditions[' + allOffset + index + '][value]'" x-model="cond.value"
                                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                                            <option value="">Seleccionar estado</option>
                                            <option value="Nuevo">Nuevo</option>
                                            <option value="Abierto">Abierto</option>
                                            <option value="Pendiente">Pendiente</option>
                                            <option value="Resuelto">Resuelto</option>
                                            @foreach($estados as $e)
                                            <option value="{{ $e }}">{{ $e }}</option>
                                            @endforeach
                                        </select>
                                    </template>

                                    <template x-if="cond.field === 'tickets.prioridad'">
                                        <select :name="'conditions[' + allOffset + index + '][value]'" x-model="cond.value"
                                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                                            <option value="">Seleccionar prioridad</option>
                                            @foreach($prioridades as $p)
                                            <option value="{{ $p }}">{{ $p }}</option>
                                            @endforeach
                                        </select>
                                    </template>

                                    <template x-if="cond.field === 'tickets.tipo_ticket'">
                                        <select :name="'conditions[' + allOffset + index + '][value]'" x-model="cond.value"
                                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                                            <option value="">Seleccionar tipo</option>
                                            @foreach($tipos_tickets as $t)
                                            <option value="{{ $t }}">{{ $t }}</option>
                                            @endforeach
                                        </select>
                                    </template>

                                    <template x-if="cond.field === 'tickets.id_canal'">
                                        <select :name="'conditions[' + allOffset + index + '][value]'" x-model="cond.value"
                                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                                            <option value="">Seleccionar canal</option>
                                            @foreach($canales as $id => $nombre)
                                            <option value="{{ $id }}">{{ $nombre }}</option>
                                            @endforeach
                                        </select>
                                    </template>

                                    <template x-if="cond.field === 'tickets.descripcion'">
                                        <input type="text"
                                            :name="'conditions[' + allOffset + index + '][value]'"
                                            x-model="cond.value"
                                            placeholder="Ej: fuga de agua"
                                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                                    </template>

                                    <template x-if="!['tickets.id_direccion_municipal','tickets.descripcion','tickets.id_agente_asignado','tickets.id_ciudadano','tickets.estado','tickets.prioridad','tickets.tipo_ticket','tickets.id_canal'].includes(cond.field)">
                                        <input type="text" :name="'conditions[' + allOffset + index + '][value]'" x-model="cond.value"
                                            placeholder="Valor"
                                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                                    </template>
                                </div>
                            </template>

                            <template x-if="!needsValue(cond.operator)">
                                <input type="hidden" :name="'conditions[' + allOffset + index + '][value]'" value="">
                            </template>
                        </div>

                        <button type="button" @click="removeAll(index)"
                            class="p-1.5 text-gray-400 hover:text-red-500 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </template>

                <button type="button" @click="addAll()"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03] transition">
                    + Agregar condición
                </button>
            </div>

            {{-- Bloque ANY --}}
            <div>
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                    Cumplir <strong>CUALQUIERA</strong> de las siguientes condiciones
                </p>

                <template x-for="(cond, index) in anyConditions" :key="index">
                    <div class="flex items-center gap-3 mb-3">
                        <input type="hidden" :name="'conditions[' + anyOffset + index + '][match_type]'" value="any">

                        <select :name="'conditions[' + anyOffset + index + '][field]'" x-model="cond.field"
                            @change="onFieldChange(cond)"
                            class="rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                            <option value="">Campo</option>
                            @foreach($availableFields as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>

                        <select :name="'conditions[' + anyOffset + index + '][operator]'"
                            x-model="cond.operator"
                            :disabled="!cond.field"
                            class="rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90 disabled:opacity-50">
                            <option value="">Operador</option>
                            <template x-for="op in getOperators(cond.field)" :key="op">
                                <option :value="op" :selected="cond.operator === op" x-text="operatorLabels[op]"></option>
                            </template>
                        </select>

                        <div class="flex-1">
                            <template x-if="needsValue(cond.operator)">
                                <div>
                                    <template x-if="cond.field === 'tickets.id_agente_asignado'">
                                        <select :name="'conditions[' + anyOffset + index + '][value]'" x-model="cond.value"
                                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                                            <option value="">Seleccionar agente</option>
                                            @foreach($agentes as $a)
                                            <option value="{{ $a['id'] }}">{{ $a['nombre'] }}</option>
                                            @endforeach
                                        </select>
                                    </template>

                                    <template x-if="cond.field === 'tickets.id_direccion_municipal'">
                                        <select :name="'conditions[' + anyOffset + index + '][value]'" x-model="cond.value"
                                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                                            <option value="">Seleccionar dirección municipal</option>
                                            @foreach($direcciones as $d)
                                            <option value="{{ $d['id'] }}">{{ $d['nombre_direccion'] }}</option>
                                            @endforeach
                                        </select>
                                    </template>

                                    <template x-if="cond.field === 'tickets.id_ciudadano'">
                                        <select :name="'conditions[' + anyOffset + index + '][value]'" x-model="cond.value"
                                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                                            <option value="">Seleccionar solicitante</option>
                                            @foreach($ciudadanos as $c)
                                            <option value="{{ $c['id'] }}">{{ $c['nombre'] }}</option>
                                            @endforeach
                                        </select>
                                    </template>

                                    <template x-if="cond.field === 'tickets.estado'">
                                        <select :name="'conditions[' + anyOffset + index + '][value]'" x-model="cond.value"
                                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                                            <option value="">Seleccionar estado</option>
                                            <option value="Nuevo">Nuevo</option>
                                            <option value="Abierto">Abierto</option>
                                            <option value="Pendiente">Pendiente</option>
                                            <option value="Resuelto">Resuelto</option>
                                            @foreach($estados as $e)
                                            <option value="{{ $e }}">{{ $e }}</option>
                                            @endforeach
                                        </select>
                                    </template>

                                    <template x-if="cond.field === 'tickets.prioridad'">
                                        <select :name="'conditions[' + anyOffset + index + '][value]'" x-model="cond.value"
                                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                                            <option value="">Seleccionar prioridad</option>
                                            @foreach($prioridades as $p)
                                            <option value="{{ $p }}">{{ $p }}</option>
                                            @endforeach
                                        </select>
                                    </template>

                                    <template x-if="cond.field === 'tickets.tipo_ticket'">
                                        <select :name="'conditions[' + anyOffset + index + '][value]'" x-model="cond.value"
                                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                                            <option value="">Seleccionar tipo</option>
                                            @foreach($tipos_tickets as $t)
                                            <option value="{{ $t }}">{{ $t }}</option>
                                            @endforeach
                                        </select>
                                    </template>

                                    <template x-if="cond.field === 'tickets.id_canal'">
                                        <select :name="'conditions[' + anyOffset + index + '][value]'" x-model="cond.value"
                                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                                            <option value="">Seleccionar canal</option>
                                            @foreach($canales as $id => $nombre)
                                            <option value="{{ $id }}">{{ $nombre }}</option>
                                            @endforeach
                                        </select>
                                    </template>

                                    <template x-if="cond.field === 'tickets.descripcion'">
                                        <input type="text"
                                            :name="'conditions[' + anyOffset + index + '][value]'"
                                            x-model="cond.value"
                                            placeholder="Ej: fuga de agua"
                                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                                    </template>

                                    <template x-if="!['tickets.id_direccion_municipal','tickets.descripcion','tickets.id_agente_asignado','tickets.id_ciudadano','tickets.estado','tickets.prioridad','tickets.tipo_ticket','tickets.id_canal'].includes(cond.field)">
                                        <input type="text" :name="'conditions[' + anyOffset + index + '][value]'" x-model="cond.value"
                                            placeholder="Valor"
                                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                                    </template>
                                </div>
                            </template>

                            <template x-if="!needsValue(cond.operator)">
                                <input type="hidden" :name="'conditions[' + anyOffset + index + '][value]'" value="">
                            </template>
                        </div>

                        <button type="button" @click="removeAny(index)"
                            class="p-1.5 text-gray-400 hover:text-red-500 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </template>

                <button type="button" @click="addAny()"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03] transition">
                    + Agregar condición
                </button>
            </div>

        </div>

        {{-- Columnas --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]"
            x-data="columnasForm()">

            <h2 class="text-base font-medium text-gray-800 dark:text-white/90 mb-1">Seleccionar datos</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                Elige hasta 15 columnas para que aparezcan en la vista.
            </p>

            <div class="mb-6">
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                    Columnas (<span x-text="columns.length"></span> de 15)
                </p>

                <template x-for="(col, index) in columns" :key="index">
                    <div class="flex items-center gap-3 mb-2">
                        <input type="hidden" :name="'columns[' + index + '][position]'" :value="index">
                        <select :name="'columns[' + index + '][column_key]'" x-model="col.column_key"
                            class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                            <option value="">Seleccionar columna</option>
                            @foreach($availableColumns as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        <button type="button" @click="removeColumn(index)"
                            class="p-1.5 text-gray-400 hover:text-red-500 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </template>

                <button type="button" @click="addColumn()"
                    x-show="columns.length < 15"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03] transition">
                    + Agregar columna
                </button>
            </div>

        </div>

        {{-- Botones --}}
        <div class="flex items-center justify-end gap-3 pb-6">
            <a href="{{ route('ticket-views.index') }}"
                class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03] transition">
                Cancelar
            </a>
            <button type="submit"
                class="rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition">
                Guardar
            </button>
        </div>

    </div>
</form>

@endsection

@push('scripts')
<script>
    function condicionesForm() {
        return {
            allConditions: @json($allConditions),
            anyConditions: @json($anyConditions),

            operatorsByField: {
                'tickets.estado': ['is', 'is_not'],
                'tickets.prioridad': ['is', 'is_not'],
                'tickets.tipo_ticket': ['is', 'is_not'],
                'tickets.id_canal': ['is', 'is_not'],
                'tickets.id_agente_asignado': ['is', 'is_not', 'present', 'not_present'],
                'tickets.id_ciudadano': ['is', 'is_not', 'present', 'not_present'],
                'tickets.descripcion': ['contains', 'not_contains', 'present', 'not_present'],
                'tickets.id_direccion_municipal': ['is', 'is_not'],
            },

            operatorLabels: {
                'is': 'Es',
                'is_not': 'No es',
                'contains': 'Contiene',
                'not_contains': 'No contiene',
                'present': 'Está presente',
                'not_present': 'No está presente',
            },

            getOperators(field) {
                return this.operatorsByField[field] ?? Object.keys(this.operatorLabels);
            },

            onFieldChange(cond) {
                cond.operator = '';
                cond.value = '';
            },

            needsValue(operator) {
                return !['present', 'not_present'].includes(operator);
            },

            get allOffset() {
                return 0;
            },
            get anyOffset() {
                return this.allConditions.length;
            },
            addAll() {
                this.allConditions.push({
                    field: '',
                    operator: '',
                    value: ''
                });
            },
            removeAll(i) {
                this.allConditions.splice(i, 1);
            },
            addAny() {
                this.anyConditions.push({
                    field: '',
                    operator: '',
                    value: ''
                });
            },
            removeAny(i) {
                this.anyConditions.splice(i, 1);
            },
        }
    }

    function columnasForm() {
        return {
            columns: @json($existingColumns),
            addColumn() {
                if (this.columns.length < 15) {
                    this.columns.push({
                        column_key: ''
                    });
                }
            },
            removeColumn(i) {
                this.columns.splice(i, 1);
            },
        }
    }
</script>
@endpush