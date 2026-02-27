@extends('layouts.app')
@section('content')
    <x-common.page-breadcrumb pageTitle="Editar Ciudadano" />
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

        <x-common.component-card title="Editar Ciudadano">

            <form action="{{ route('ciudadanos.update', $ciudadano->id) }}" method="post">
                @csrf
                @method('PATCH')
                <div>
                    <label for="nombre" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Nombre
                    </label>
                    <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $ciudadano->nombre) }}"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />

                </div>
                <div>
                    <label for="apellido_paterno" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Apellido Paterno
                    </label>
                    <input type="text" id="apellido_paterno" name="apellido_paterno" value="{{ old('apellido_paterno', $ciudadano->apellido_paterno) }}"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />

                </div>
                <div>
                    <label for="apellido_materno" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Apellido Materno
                    </label>
                    <input type="text" id="apellido_materno" name="apellido_materno" value="{{ old('apellido_materno', $ciudadano->apellido_materno) }}"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />

                </div>
                <div>
                    <label for="telefono_principal" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Telefono Principal
                    </label>
                    <input type="number" maxlength="10" id="telefono_principal" name="telefono_principal" value="{{ old('telefono_principal', $ciudadano->telefono_principal) }}"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />

                </div>
                <div>
                    <label for="telefono_alterno" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Telefono Alterno
                    </label>
                    <input type="number" maxlength="10" id="telefono_alterno" name="telefono_alterno" value="{{ old('telefono_alterno', $ciudadano->telefono_alterno) }}"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />

                </div>
                <div>
                    <label for="email" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Email
                    </label>
                    <input type="text" id="email" name="email" value="{{ old('email', $ciudadano->email) }}"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />

                </div>
                <div>
                    <label for="direccion_calle" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Calle
                    </label>
                    <input type="text" id="direccion_calle" name="direccion_calle" value="{{ old('direccion_calle', $ciudadano->direccion_calle) }}"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />

                </div>
                <div>
                    <label for="direccion_numero" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Número Exterior
                    </label>
                    <input type="number" id="direccion_numero" name="direccion_numero" value="{{ old('direccion_numero', $ciudadano->direccion_numero) }}"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />

                </div>
                <div>
                    <label for="direccion_colonia" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Colonia
                    </label>
                    <input type="text" id="direccion_colonia" name="direccion_colonia" value="{{ old('direccion_colonia', $ciudadano->direccion_colonia) }}"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />

                </div>
                


                <div>
                    <x-ui.button size="sm" variant="primary" type="submit" class="mt-4">Actualizar Ciudadano</x-ui.button>
                </div>
            </form>
        </x-common.component-card>
    </div>
@endsection