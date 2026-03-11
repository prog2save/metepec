@props(['tickets'])

<div>
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table id="tabla-tickets" class="w-full min-w-[1102px] p-2">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <th class="px-4 py-3 font-normal text-gray-500 text-center text-theme-sm dark:text-gray-400 min-w-[55px]">ID</th>
                        <th class="px-4 py-3 font-normal text-gray-500 text-center text-theme-sm dark:text-gray-400 min-w-[110px]">Estado</th>
                        <th class="px-4 py-3 font-normal text-gray-500 text-center text-theme-sm dark:text-gray-400">Asunto</th>
                        <th class="px-4 py-3 font-normal text-gray-500 text-center text-theme-sm dark:text-gray-400">Solicitante</th>
                        <th class="px-4 py-3 font-normal text-gray-500 text-center text-theme-sm dark:text-gray-400 min-w-[100px]">Prioridad</th>
                        <th class="px-4 py-3 font-normal text-gray-500 text-center text-theme-sm dark:text-gray-400">Agente asignado</th>
                        <th class="px-4 py-3 font-normal text-gray-500 text-center text-theme-sm dark:text-gray-400">Fecha de resolución</th>
                        <th class="px-4 py-3 font-normal text-center text-gray-500 text-theme-sm dark:text-gray-400">Editar</th>
                        <th class="px-4 py-3 font-normal text-center text-gray-500 text-theme-sm dark:text-gray-400">Eliminar</th>
                        <th class="px-4 py-3 font-normal text-center text-gray-500 text-theme-sm dark:text-gray-400">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($tickets as $t)
                    <tr class="border-b border-gray-100 dark:border-gray-800">

                        <td class="px-4 sm:px-6 py-3.5">
                            <p class="text-gray-700 text-theme-sm dark:text-gray-400">#{{ $t->id }}</p>
                        </td>

                        <td class="px-4 sm:px-6 py-3.5">
                            <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-medium
                                @if($t->estado === 'Resuelto') bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-800
                                @elseif($t->estado === 'Pendiente') bg-gray-100 text-gray-700 border-gray-300 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700
                                @elseif($t->estado === 'Nuevo') bg-orange-50 text-orange-700 border-orange-200 dark:bg-orange-900/30 dark:text-orange-300 dark:border-orange-800
                                @elseif($t->estado === 'Abierto') bg-red-50 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800
                                @else bg-purple-50 text-purple-700 border-purple-200 dark:bg-purple-900/30 dark:text-purple-300 dark:border-purple-800
                                @endif">
                                {{ $t->estado }}
                            </span>
                        </td>

                        <td class="px-4 sm:px-6 py-3.5">
                            <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{ $t->asunto }}</p>
                        </td>

                        <td class="px-4 sm:px-6 py-3.5">
                            @if ($t->ciudadano)
                            <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{ $t->ciudadano->nombre }} {{ $t->ciudadano->apellido_paterno }}</p>
                            @else
                            <p class="text-gray-500 text-theme-sm italic dark:text-gray-600">Sin asignar</p>
                            @endif
                        </td>

                        <td class="px-4 sm:px-6 py-3.5">
                            <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{ $t->prioridad ?? '—' }}</p>
                        </td>

                        <td class="px-4 sm:px-6 py-3.5">
                            @if ($t->agente)
                            <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{ $t->agente->nombre }} {{ $t->agente->apellido }}</p>
                            @else
                            <p class="text-gray-500 text-theme-sm italic dark:text-gray-600">Sin asignar</p>
                            @endif
                        </td>

                        <td class="px-4 sm:px-6 py-3.5">
                            <p class="text-gray-700 text-theme-sm dark:text-gray-400">
                                {{ optional($t->fecha_resolucion)->format('Y-m-d') ?? '—' }}
                            </p>
                        </td>

                        <td class="px-4 sm:px-6 py-3.5">
                            <a href="{{ route('tickets.edit', $t->id) }}"
                                class="inline-flex items-center justify-center text-gray-400 hover:text-blue-500 transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </a>
                        </td>

                        <td class="px-4 sm:px-6 py-3.5 text-center space-x-3">

                            <form action="{{ route('tickets.destroy', $t->id) }}"
                                method="POST"
                                class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center justify-center text-gray-400 hover:text-red-500 transition-colors duration-200"
                                    onclick="return confirm('¿Deseas eliminar este ticket?')">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </form>
                        </td>

                        <td class="px-4 py-2 align-middle">
                            <div class="flex justify-center">
                                <form action="{{ route('tickets.tickethecho', $t->id) }}" method="POST" class="inline-flex">
                                    @csrf
                                    @method('PUT')

                                    @if ($t->estado !== 'Resuelto')
                                    <button type="submit"
                                        onclick="return confirm('¿Deseas marcar este ticket como resuelto?')"
                                        class="inline-flex items-center gap-2 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-sm font-medium text-emerald-700 transition-all duration-200 hover:bg-emerald-100 hover:border-emerald-300 focus:outline-none focus:ring-3 focus:ring-emerald-500/20 active:scale-[0.98] dark:border-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300 dark:hover:bg-emerald-900/50">
                                        Completar
                                    </button>
                                    @else
                                    <button type="button" disabled
                                        class="inline-flex items-center gap-2 rounded-lg border border-amber-200 bg-amber-50 px-4 py-2.5 text-sm font-medium text-amber-700 cursor-not-allowed opacity-80 dark:border-amber-800 dark:bg-amber-900/30 dark:text-amber-300">
                                        Resuelto
                                    </button>
                                    @endif
                                </form>
                            </div>
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
        new DataTable('#tabla-tickets', {
            searchable: true,
            perPage: 10,
            columns: [{
                    select: [7, 8 , 9],
                    sortable: false
                } // Acciones y Completar sin ordenamiento
            ],
            labels: {
                placeholder: "Buscar tickets...",
                perPage: "Tickets por página",
                noRows: "No se encontraron tickets",
                info: "Mostrando {start} a {end} de {rows} tickets"
            }
        });
    });
</script>