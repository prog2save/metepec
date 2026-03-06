@props(['tickets'])

<div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table class="w-full min-w-[1102px]">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800">

                        <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">
                            Estado
                        </th>

                        <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">
                            Asunto
                        </th>

                        <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">
                            Solicitante
                        </th>

                        <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">
                            Prioridad
                        </th>

                        <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">
                            Agente asignado
                        </th>

                        <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">
                            Fecha de resolución
                        </th>

                        <th class="px-4 py-3 font-normal text-center text-gray-500 text-theme-sm dark:text-gray-400">
                            Acciones
                        </th>

                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($tickets as $t)
                    <tr class="border-b border-gray-100 dark:border-gray-800">

                        {{-- Estado --}}
                        <td class="px-4 sm:px-6 py-3.5">
                            <span class="
                                inline-flex items-center rounded-full border px-3 py-1 text-xs font-medium
                                @if($t->estado === 'Resuelto')
                                    bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-800
                                @elseif($t->estado === 'Pendiente')
                                    bg-gray-100 text-gray-700 border-gray-300 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700
                                @elseif($t->estado === 'Nuevo')
                                    bg-orange-50 text-orange-700 border-orange-200 dark:bg-orange-900/30 dark:text-orange-300 dark:border-orange-800
                                @elseif($t->estado === 'Abierto')
                                    bg-red-50 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800
                                @endif
                            ">
                                {{ $t->estado }}
                            </span>
                        </td>

                        {{-- Nombre del ticket --}}
                        <td class="px-4 sm:px-6 py-3.5">
                            <p class="text-gray-700 text-theme-sm dark:text-gray-400">
                                {{ $t->asunto }}
                            </p>
                        </td>

                        {{-- Ciudadano solicitante --}}
                        <td class="px-4 sm:px-6 py-3.5">
                            @if ($t->ciudadano)
                            <p class="text-gray-700 text-theme-sm dark:text-gray-400">
                                {{ $t->ciudadano->nombre }} {{ $t->ciudadano->apellido_paterno }}
                            </p>
                            @else
                            <p class="text-gray-500 text-theme-sm italic dark:text-gray-600">
                                Sin asignar
                            </p>

                            @endif

                        </td>

                        {{-- Prioridad --}}
                        <td class="px-4 sm:px-6 py-3.5">
                            <p class="text-gray-700 text-theme-sm dark:text-gray-400">
                                {{ $t->prioridad ?? '—' }}
                            </p>
                        </td>

                        {{-- Agente asignado --}}
                        <td class="px-4 sm:px-6 py-3.5">
                            @if ($t->agente)
                            <p class="text-gray-700 text-theme-sm dark:text-gray-400">
                                {{ $t->agente->nombre }} {{ $t->agente->apellido }}
                            </p>
                            @else
                            <p class="text-gray-500 text-theme-sm italic dark:text-gray-600">
                                Sin asignar
                            </p>

                            @endif

                        </td>

                        <td>
                            <p class="text-gray-700 text-theme-sm dark:text-gray-400">
                                {{ optional($t->fecha_resolucion)->format('Y-m-d') ?? '—' }}
                            </p>
                        </td>


                        {{-- Acciones --}}
                        <td class="px-4 sm:px-6 py-3.5 text-center space-x-3">
                            <a href="{{ route('tickets.edit', $t->id) }}"
                                class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Editar</a>

                            <form action="{{ route('tickets.destroy', $t->id) }}"
                                method="POST"
                                class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400"
                                    onclick="return confirm('¿Deseas eliminar este ticket?')">
                                    Eliminar
                                </button>
                            </form>
                        </td>

                        <td class="px-4 py-2 align-middle">
                            <div class="flex justify-center">
                                {{-- AQUÍ va tu botón Resuelto --}}
                                <form action="{{ route('tickets.tickethecho', $t->id) }}"
                                    method="POST"
                                    class="inline-flex">
                                    @csrf
                                    @method('PUT')

                                    @if ($t->estado !== 'Resuelto')
                                    <button
                                        type="submit"
                                        onclick="return confirm('¿Deseas marcar este ticket como resuelto?')"
                                        class="
                                            inline-flex items-center gap-2
                                            rounded-lg border border-emerald-200
                                            bg-emerald-50 px-4 py-2.5
                                            text-sm font-medium text-emerald-700
                                            transition-all duration-200
                                            hover:bg-emerald-100 hover:border-emerald-300
                                            focus:outline-none focus:ring-3 focus:ring-emerald-500/20
                                            active:scale-[0.98]
                                            dark:border-emerald-800 dark:bg-emerald-900/30
                                            dark:text-emerald-300 dark:hover:bg-emerald-900/50
                                        ">
                                        Completar
                                    </button>
                                    @else
                                    <button
                                        type="button"
                                        disabled
                                        class="
                                            inline-flex items-center gap-2
                                            rounded-lg border border-amber-200
                                            bg-amber-50 px-4 py-2.5
                                            text-sm font-medium text-amber-700
                                            cursor-not-allowed opacity-80
                                            dark:border-amber-800 dark:bg-amber-900/30
                                            dark:text-amber-300
                                        ">
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

    {{-- Paginación --}}
    <div class="px-6 py-4 border-t border-gray-200 dark:border-white/[0.05]">
        {{ $tickets->links() }}
    </div>
</div>