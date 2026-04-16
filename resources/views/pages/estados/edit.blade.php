@extends('layouts.app')

@section('title', 'Editar estado del ticket')

@section('content')
<x-common.page-breadcrumb pageTitle="Editar Estado del Ticket" />

<div class="space-y-6">

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

    <x-common.component-card title="Editar Estado del Ticket">

        <form action="{{ route('estados.update', $estado->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Categoría --}}
            <div>
                <label for="categoria" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Categoría
                    <span class="block text-xs font-normal text-gray-500 dark:text-gray-500 mt-0.5">
                        Elija una categoría de estado del sistema
                    </span>
                </label>
                <select name="categoria" id="categoria"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 @error('categoria') border-red-400 dark:border-red-500 @enderror">
                    <option value="Abierto"   {{ old('categoria', $estado->categoria) == 'Abierto'   ? 'selected' : '' }}>Abierto</option>
                    <option value="Pendiente" {{ old('categoria', $estado->categoria) == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="Resuelto"  {{ old('categoria', $estado->categoria) == 'Resuelto'  ? 'selected' : '' }}>Resuelto</option>
                </select>
                @error('categoria')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nombre agente --}}
            <div>
                <label for="nombre_agente" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Nombre (vista de agente)
                    <span class="block text-xs font-normal text-gray-500 dark:text-gray-500 mt-0.5">
                        Estado de ticket personalizado para agentes
                    </span>
                </label>
                <input type="text" id="nombre_agente" name="nombre_agente"
                    value="{{ old('nombre_agente', $estado->nombre_agente) }}" maxlength="50"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('nombre_agente') border-red-400 dark:border-red-500 @enderror" />
                @error('nombre_agente')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Descripción agente --}}
            <div>
                <label for="descripcion_agente" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Descripción (vista de agente)
                    <span class="block text-xs font-normal text-gray-500 dark:text-gray-500 mt-0.5">
                        Una descripción corta para los agentes
                    </span>
                </label>
                <input type="text" id="descripcion_agente" name="descripcion_agente"
                    value="{{ old('descripcion_agente', $estado->descripcion_agente) }}" maxlength="200"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('descripcion_agente') border-red-400 dark:border-red-500 @enderror" />
                @error('descripcion_agente')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Toggle: Vista usuario final --}}
            <div class="flex items-start gap-3">
                <div class="flex items-center h-5 mt-0.5">
                    <input type="checkbox" id="vista_usuario" name="vista_usuario" value="1"
                        {{ old('vista_usuario', $estado->vista_usuario) ? 'checked' : '' }}
                        onchange="toggleUserView(this)"
                        class="w-4 h-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500/20 dark:border-gray-600 dark:bg-gray-800 dark:checked:bg-brand-500 cursor-pointer" />
                </div>
                <label for="vista_usuario" class="cursor-pointer">
                    <span class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Mostrar a los usuarios finales una vista diferente
                    </span>
                    <span class="block text-xs text-gray-500 dark:text-gray-500 mt-0.5">
                        Los usuarios finales verán la vista de nombre y descripción que haya creado para ellos
                    </span>
                </label>
            </div>

            {{-- Sección usuario final --}}
            <div id="userViewSection"
                class="{{ old('vista_usuario', $estado->vista_usuario) ? '' : 'hidden' }} ml-7 space-y-5 rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800/50">

                <div>
                    <label for="nombre_usuario" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Nombre (vista de usuario final)
                        <span class="block text-xs font-normal text-gray-500 dark:text-gray-500 mt-0.5">
                            Estado de ticket personalizado para usuarios finales
                        </span>
                    </label>
                    <input type="text" id="nombre_usuario" name="nombre_usuario"
                        value="{{ old('nombre_usuario', $estado->nombre_usuario) }}" maxlength="50"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('nombre_usuario') border-red-400 dark:border-red-500 @enderror" />
                    @error('nombre_usuario')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="descripcion_usuario" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Descripción (vista de usuario final)
                        <span class="block text-xs font-normal text-gray-500 dark:text-gray-500 mt-0.5">
                            Una descripción corta para los usuarios finales
                        </span>
                    </label>
                    <input type="text" id="descripcion_usuario" name="descripcion_usuario"
                        value="{{ old('descripcion_usuario', $estado->descripcion_usuario) }}" maxlength="200"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('descripcion_usuario') border-red-400 dark:border-red-500 @enderror" />
                    @error('descripcion_usuario')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Toggle: Activo --}}
            <div class="flex items-start gap-3">
                <div class="flex items-center h-5 mt-0.5">
                    <input type="checkbox" id="activo" name="activo" value="1"
                        {{ old('activo', $estado->activo) ? 'checked' : '' }}
                        class="w-4 h-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500/20 dark:border-gray-600 dark:bg-gray-800 dark:checked:bg-brand-500 cursor-pointer" />
                </div>
                <label for="activo" class="cursor-pointer">
                    <span class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Establecer como activo
                    </span>
                    <span class="block text-xs text-gray-500 dark:text-gray-500 mt-0.5">
                        Aparecerá en la lista de estados del agente
                    </span>
                </label>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('estados.index') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    Cancelar
                </a>
                <x-ui.button size="sm" variant="primary" type="submit">Guardar cambios</x-ui.button>
            </div>

        </form>
    </x-common.component-card>
</div>

@push('scripts')
<script>
    function toggleUserView(checkbox) {
        const section = document.getElementById('userViewSection');
        if (checkbox.checked) {
            section.classList.remove('hidden');
        } else {
            section.classList.add('hidden');
            document.getElementById('nombre_usuario').value = '';
            document.getElementById('descripcion_usuario').value = '';
        }
    }
</script>
@endpush

@endsection