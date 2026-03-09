@props(['servicios'])

<div>
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table id="servicios-table" class="w-full min-w-[1102px]">
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
                            Editar
                        </th>

                        <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">
                            Eliminar
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

                        <td class="px-4 sm:px-6 py-3.5">
                            <button 
                                type="button"
                                class="inline-flex items-center justify-center text-gray-400 hover:text-blue-500 transition-colors duration-200"
                                @click="openEditModal({
                                    id: {{ $s->id }},
                                    nombre_servicio: @js($s->nombre_servicio),
                                    id_direccion_municipal: @js((string)($s->id_direccion_municipal ?? '')),
                                })">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </button>
                        </td>

                        <td class="px-4 sm:px-6 py-3.5 space-x-3">
                            <form action="{{ route('servicios.destroy', $s->id) }}"
                                method="POST"
                                class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center justify-center text-gray-400 hover:text-red-500 transition-colors duration-200"
                                    onclick="return confirm('¿Deseas desactivar este servicio?')">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </form>

                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        new DataTable('#servicios-table', {
            searchable: true,
            perPage: 10,
            columns: [{
                    select: [0, 4 , 5],
                    sortable: false
                } // Acciones y Completar sin ordenamiento
            ],
            labels: {
                placeholder: "Buscar servicios...",
                perPage: "Servicios por página",
                noRows: "No se encontraron servicios",
                info: "Mostrando {start} a {end} de {rows} servicios"
            }
        });
    });
</script>