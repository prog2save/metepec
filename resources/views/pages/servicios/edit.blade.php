@extends('layouts.app')
@section('content')
    <x-common.page-breadcrumb pageTitle="Editar Dirección Municipal" />
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

        <x-common.component-card title="Editar Dirección Municipal">

            <form action="{{ route('direcciones.update', $direccion->id) }}" method="post">
                @csrf
                @method('PATCH')
                <div>
                    <label for="nombre_direccion" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Nombre de la Dirección Municipal
                    </label>
                    <input type="text" id="nombre_direccion" name="nombre_direccion" value="{{ old('nombre_direccion', $direccion->nombre_direccion) }}"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />

                </div>
                <div>
                    <label for="contacto_principal" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Nombre del Contacto Principal
                    </label>
                    <input type="text" id="contacto_principal" name="contacto_principal" value="{{ old('contacto_principal', $direccion->contacto_principal) }}"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />

                </div>
                <div>
                    <label for="telefono" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Teléfono
                    </label>
                    <input type="number" maxlength="10" id="telefono" name="telefono" value="{{ old('telefono', $direccion->telefono) }}"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />

                </div>
                <div>
                    <label for="email" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Email
                    </label>
                    <input type="text" id="email" name="email" value="{{ old('email', $direccion->email) }}"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />

                </div>
                <div>
                    <x-ui.button size="sm" variant="primary" type="submit" class="mt-4">Actualizar Dirección</x-ui.button>
                </div>
            </form>
        </x-common.component-card>
    </div>
@endsection