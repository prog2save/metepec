@props(['users'])
<div>
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table id="suspendidos-table" class="w-full min-w-[1102px]">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Usuario</th>
                        <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Apellido</th>
                        <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Nombre</th>
                        <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Fecha Registro</th>
                        <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Rol</th>
                        <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Suspendido por</th>
                        <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Reactivar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr class="border-b border-gray-100 dark:border-gray-800">

                        {{-- Avatar + email --}}
                        <td class="px-4 sm:px-6 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full
                                                bg-red-100 text-red-500 font-medium text-sm">
                                    {{ strtoupper(substr($user->nombre, 0, 1) . substr($user->apellido, 0, 1)) }}
                                </div>
                                <div>
                                    <span class="mb-0.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-400">
                                        {{ $user->nombre }} {{ $user->apellido }}
                                    </span>
                                    <span class="text-gray-500 text-theme-sm dark:text-gray-400">
                                        {{ $user->email }}
                                    </span>
                                </div>
                            </div>
                        </td>

                        <td class="px-4 sm:px-6 py-3.5">
                            <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{ $user->apellido }}</p>
                        </td>

                        <td class="px-4 sm:px-6 py-3.5">
                            <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{ $user->nombre }}</p>
                        </td>

                        <td class="px-5 py-4 sm:px-6">
                            <span class="text-gray-500 text-theme-sm dark:text-gray-400">
                                {{ $user->created_at->format('d/m/Y') }}
                            </span>
                        </td>

                        <td class="px-5 py-4 sm:px-6">
                            <span class="text-gray-500 text-theme-sm dark:text-gray-400">
                                {{ $user->role }}
                            </span>
                        </td>

                        {{-- Motivo de suspensión --}}
                        <td class="px-5 py-4 sm:px-6">
                            <span class="text-red-500 text-theme-sm font-medium">
                                {{ $user->suspendido_por ?? 'Sin especificar' }}
                            </span>
                        </td>

                        {{-- Botón reactivar --}}
                        <td class="px-4 sm:px-6 py-3.5">
                            <form action="{{ route('usuarios.reactivar', $user->id) }}" method="POST"
                                onsubmit="return confirm('¿Reactivar a {{ $user->nombre }}?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="inline-flex items-center justify-center text-gray-400 hover:text-green-500 transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1 0 12.728 0M12 3v9" />
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
        new DataTable('#suspendidos-table', {
            searchable: true,
            perPage: 10,
            columns: [{
                select: [6],
                sortable: false
            }],
            labels: {
                placeholder: "Buscar usuario...",
                perPage: "Usuarios por página",
                noRows: "No hay usuarios suspendidos",
                info: "Mostrando {start} a {end} de {rows} usuarios"
            }
        });
    });
</script>