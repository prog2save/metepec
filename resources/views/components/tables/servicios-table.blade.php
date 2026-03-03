@props(['servicios'])

<div>
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table class="w-full min-w-[1102px]">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <th></th>

                        <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">
                            Servicio
                        </th>

                        <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">
                            Dirección
                        </th>

                        <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">
                            Contacto
                        </th>

                        <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">
                            Acciones
                        </th>

                    </tr>
                </thead>

                <tbody>
                    @foreach ($servicios as $s)
                    <tr class="border-b border-gray-100 dark:border-gray-800">

                        {{-- Avatar con inicial --}}
                        <td class="px- sm:px-6 py-3.5">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex items-center justify-center w-10 h-10 rounded-full 
                                        bg-indigo-100 text-indigo-600 font-semibold text-sm">
                                    {{ strtoupper(substr($s->nombre_servicio, 0, 1)) }}
                                </div>
                            </div>
                        </td>

                        {{-- Nombre del servicio --}}
                        <td class="px-4 sm:px-6 py-3.5">
                            <span class="block text-theme-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ $s->nombre_servicio }}
                            </span>
                        </td>

                        {{-- Direccion Municipal --}}
                        <td class="px-4 sm:px-6 py-3.5">
                            <p class="text-gray-700 text-theme-sm dark:text-gray-400">
                                {{ $s->direccionMunicipal->nombre_direccion ?? '—' }}
                            </p>
                        </td>

                        {{-- Contacto --}}
                        <td class="px-4 sm:px-6 py-3.5">
                            <p class="text-gray-700 text-theme-sm dark:text-gray-400">
                                {{ $s->direccionMunicipal->contacto_principal ?? '—' }}
                            </p>
                        </td>


                        {{-- Acciones --}}
                        <td class="px-4 sm:px-6 py-3.5 space-x-3">
                            <button
                                type="button"
                                class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400 hover:underline"
                                @click="openEditModal({
                                    id: {{ $s->id }},
                                    nombre_servicio: @js($s->nombre_servicio),
                                    id_direccion_municipal: @js((string)($s->id_direccion_municipal ?? '')),
                                })">
                                Editar
                            </button>

                            <form action="{{ route('servicios.destroy', $s->id) }}"
                                method="POST"
                                class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400"
                                    onclick="return confirm('¿Deseas desactivar esta servicio?')">
                                    Eliminar
                                </button>
                            </form>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Paginación --}}
    <div class="px-6 py-4 border-t border-gray-200 dark:border-white/[0.05]">
        {{ $servicios->links() }}
    </div>
</div>