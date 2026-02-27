@props(['ciudadanos'])
<div>
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table class="w-full min-w-[1102px]">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <th></th>
                        <th scope="col"
                            class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">
                            Dirección</th>
                        <th scope="col"
                            class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">
                            Teléfono Principal</th>
                        <th scope="col"
                            class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">
                            Fecha Registro</th>
                        <th scope="col"
                            class="px-4 py-3 font-normal text-center text-gray-500 text-theme-sm dark:text-gray-400">
                            Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($ciudadanos as $c)
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <td class="px-4 sm:px-6 py-3.5">
                            <div class="flex items-center gap-3">

                                {{-- Avatar con iniciales --}}
                                <div class="flex items-center justify-center w-10 h-10 rounded-full 
                                                    bg-blue-100 text-blue-600 font-medium text-sm">
                                    {{ strtoupper(substr($c->nombre, 0, 1) . substr($c->apellido_paterno, 0, 1) . substr($c->apellido_materno, 0, 1)) }}
                                </div>

                                <div>
                                    <span
                                        class="mb-0.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-400">
                                        {{ $c->nombre }} {{ $c->apellido_paterno }} {{ $c->apellido_materno }}
                                    </span>

                                    <span class="text-gray-500 text-theme-sm dark:text-gray-400">
                                        {{$c->email }}
                                    </span>
                                </div>

                            </div>
                        </td>

                        <td class="px-4 sm:px-6 py-3.5">
                            <p class="text-gray-700 text-theme-sm dark:text-gray-400"> {{ $c->direccion_calle }}
                                {{ $c->direccion_numero ? ' ' . $c->direccion_numero : '' }},
                                {{ $c->direccion_colonia }}
                            </p>
                        </td>

                        <td class="px-4 sm:px-6 py-3.5">
                            <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{ $c->telefono_principal }}</p>
                        </td>

                        <td class="px-5 py-4 sm:px-6">
                            <span class="text-gray-500 text-theme-sm dark:text-gray-400">
                                {{ $c->created_at->format('d/m/Y') }}
                            </span>
                        </td>
                        <td class="px-4 sm:px-6 py-3.5 gap-2">
                            <a href="{{ route('ciudadanos.edit', $c->id) }}"
                                class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Editar</a>

                            <form action="{{ route('ciudadanos.destroy', $c->id) }}" method="POST"
                                style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400"
                                    onclick="return confirm('¿Estás seguro de eliminar este ciudadano?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 dark:border-white/[0.05]">
        {{ $ciudadanos->links() }}
    </div>

</div>