@props(['direcciones'])

<div>
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table class="w-full min-w-[1102px]">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <th></th>

                        <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">
                            Dirección Municipal
                        </th>

                        <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">
                            Contacto
                        </th>

                        <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">
                            Teléfono
                        </th>

                        <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">
                            Fecha Registro
                        </th>

                        <th class="px-4 py-3 font-normal text-center text-gray-500 text-theme-sm dark:text-gray-400">
                            Acciones
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($direcciones as $d)
                    <tr class="border-b border-gray-100 dark:border-gray-800">

                        {{-- Avatar con inicial --}}
                        <td class="px- sm:px-6 py-3.5">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex items-center justify-center w-10 h-10 rounded-full 
                                        bg-indigo-100 text-indigo-600 font-semibold text-sm">
                                    {{ strtoupper(substr($d->nombre_direccion, 0, 1)) }}
                                </div>
                            </div>
                        </td>

                        {{-- Nombre de la dirección --}}
                        <td class="px-4 sm:px-6 py-3.5">
                            <span class="block text-theme-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ $d->nombre_direccion }}
                            </span>

                            @if ($d->email)
                            <span class="text-gray-500 text-theme-sm dark:text-gray-400">
                                {{ $d->email }}
                            </span>
                            @endif
                        </td>

                        {{-- Contacto --}}
                        <td class="px-4 sm:px-6 py-3.5">
                            <p class="text-gray-700 text-theme-sm dark:text-gray-400">
                                {{ $d->contacto_principal ?? '—' }}
                            </p>
                        </td>

                        {{-- Teléfono --}}
                        <td class="px-4 sm:px-6 py-3.5">
                            <p class="text-gray-700 text-theme-sm dark:text-gray-400">
                                {{ $d->telefono ?? '—' }}
                            </p>
                        </td>

                        {{-- Fecha --}}
                        <td class="px-5 py-4 sm:px-6">
                            <span class="text-gray-500 text-theme-sm dark:text-gray-400">
                                {{ $d->created_at->format('d/m/Y') }}
                            </span>
                        </td>

                        {{-- Acciones --}}
                        <td class="px-4 sm:px-6 py-3.5 text-center space-x-3">
                            <a href="{{ route('direcciones.edit', $d->id) }}"
                                class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Editar</a>

                            <form action="{{ route('direcciones.destroy', $d->id) }}"
                                method="POST"
                                class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400"
                                    onclick="return confirm('¿Deseas desactivar esta dirección municipal?')">
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
        {{ $direcciones->links() }}
    </div>
</div>