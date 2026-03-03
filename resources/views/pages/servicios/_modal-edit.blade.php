<style>
    .ts-wrapper {
        width: 100%;
    }

    .ts-control {
        height: 2.75rem;
        /* h-11 */
        width: 100%;
        border-radius: .5rem;
        /* rounded-lg */
        border: 1px solid rgb(209 213 219);
        /* border-gray-300 */
        background: transparent;
        padding: .625rem 1rem;
        /* aprox px-4 + py-2.5 */
        font-size: .875rem;
        /* text-sm */
        line-height: 1.25rem;
        color: rgb(31 41 55);
        /* text-gray-800 */
        display: flex;
        align-items: center;
        gap: .5rem;
        box-shadow: var(--shadow-theme-xs, 0 1px 2px rgba(0, 0, 0, .05));
    }

    .dark .ts-control {
        border-color: rgb(55 65 81);
        /* dark:border-gray-700 */
        background-color: rgb(17 24 39);
        /* dark:bg-gray-900 */
        color: rgba(255, 255, 255, .90);
        /* dark:text-white/90 */
    }

    /* Input interno de búsqueda */
    .ts-control>input {
        margin: 0 !important;
        padding: 0 !important;
        font-size: .875rem !important;
        line-height: 1.25rem !important;
        color: rgb(31 41 55) !important;
    }

    .dark .ts-control>input {
        color: rgba(255, 255, 255, .90) !important;
    }

    /* Placeholder */
    .ts-control .item[data-value=""],
    .ts-control input::placeholder {
        color: rgb(156 163 175) !important;
        /* gray-400 */
    }

    .dark .ts-control .item[data-value=""],
    .dark .ts-control input::placeholder {
        color: rgba(255, 255, 255, .30) !important;
        /* white/30 */
    }

    .ts-wrapper.focus .ts-control,
    .ts-control:focus-within {
        outline: none;
        border-color: rgb(147 197 253);
        /* aprox focus:border-brand-300 */
        box-shadow: 0 0 0 3px rgba(59, 130, 246, .10);
        /* ring-brand-500/10 aprox */
    }

    .dark .ts-wrapper.focus .ts-control,
    .dark .ts-control:focus-within {
        border-color: rgb(30 64 175);
        /* aprox dark:focus:border-brand-800 */
    }

    /* Flechita del dropdown */
    .ts-control .ts-dropdown-toggle {
        margin-left: auto;
        opacity: .7;
    }

    /* Dropdown */
    .ts-dropdown {
        margin-top: .25rem;
        border-radius: .5rem;
        border: 1px solid rgb(229 231 235);
        /* gray-200 */
        background: #fff;
        overflow: hidden;
        box-shadow: 0 10px 15px rgba(0, 0, 0, .08);
        z-index: 9999;
    }

    .dark .ts-dropdown {
        border-color: rgb(55 65 81);
        /* gray-700 */
        background: rgb(17 24 39);
        /* gray-900 */
    }

    .ts-dropdown .ts-dropdown-content {
        max-height: 15rem;
        overflow: auto;
    }

    .ts-dropdown .option {
        padding: .5rem 1rem;
        font-size: .875rem;
        line-height: 1.25rem;
        cursor: pointer;
        color: rgb(55 65 81);
        /* gray-700 */
    }

    .dark .ts-dropdown .option {
        color: rgb(229 231 235);
        /* gray-200 */
    }

    .ts-dropdown .option.active {
        background: rgb(243 244 246);
        /* gray-100 */
    }

    .dark .ts-dropdown .option.active {
        background: rgb(31 41 55);
        /* gray-800 */
    }

    .ts-dropdown .option.selected {
        background: rgba(59, 130, 246, .08);
        /* brand-50 aprox */
        color: rgb(29 78 216);
        /* brand-700 aprox */
    }

    .dark .ts-dropdown .option.selected {
        background: rgb(31 41 55);
        color: rgba(255, 255, 255, .90);
    }

    .ts-control {
        background-color: transparent !important;
    }

    /* Fondo del input interno también transparente*/
    .ts-control>input {
        background-color: transparent !important;
    }

     
    .ts-wrapper.focus .ts-control,
    .ts-control:focus-within {
        background-color: transparent !important;
    }

    .dark .ts-control {
        background-color: rgb(17 24 39) !important;
        /* gray-900 */
    }

    .dark .ts-wrapper.focus .ts-control,
    .dark .ts-control:focus-within {
        background-color: rgb(17 24 39) !important;
    }

    .ts-control>input {
        -webkit-text-fill-color: rgb(31 41 55) !important;
        /* text-gray-800 */
    }

    .dark .ts-control>input {
        -webkit-text-fill-color: rgba(255, 255, 255, .90) !important;
        /* white/90 */
    }

    .ts-control .ts-dropdown-toggle {
        background: transparent !important;
    }
</style>
{{-- MODAL EDIT SERVICIO --}}

<div
    x-cloak
    x-show="openEdit"
    x-transition.opacity
    class="fixed inset-0 z-50 flex items-center justify-center"
    role="dialog"
    aria-modal="true"
    @keydown.escape.window="closeEditModal()"
    style="display:none;">
    <div class="absolute inset-0 bg-black/40 dark:bg-black/60" @click="closeEditModal()"></div>

    <div
        x-transition
        @click.outside="closeEditModal()"
        class="relative w-[92%] max-w-lg rounded-2xl bg-white p-6 shadow-xl dark:bg-gray-900">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Editar servicio</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Actualiza la información del servicio.</p>
            </div>

            <button type="button" @click="closeEditModal()"
                class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700
                       dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-200">
                ✕
            </button>
        </div>

        <form :action="`{{ url('/servicios') }}/${edit.id}`" method="POST" class="mt-5 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Nombre del servicio
                </label>
                <input
                    x-ref="nombreEdit"
                    name="nombre_servicio"
                    x-model="edit.nombre_servicio"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5
                           text-sm text-gray-800 shadow-theme-xs
                           focus:outline-none focus:ring-3 focus:ring-blue-500/10 focus:border-blue-300
                           dark:border-gray-700 dark:bg-gray-900
                           dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-blue-800" />
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Dirección municipal
                </label>

                <select
                    id="edit_id_direccion_municipal"
                    name="id_direccion_municipal"
                    class="w-full"
                    x-model="edit.id_direccion_municipal">
                    <option value="">Selecciona una dirección</option>
                    @foreach($direcciones as $d)
                    <option value="{{ $d->id }}">{{ $d->nombre_direccion }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button type="button" @click="closeEditModal()"
                    class="inline-flex justify-center rounded-lg border border-gray-200 bg-white
                           px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50
                           dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800">
                    Cancelar
                </button>

                <button type="submit"
                    class="inline-flex justify-center rounded-lg bg-blue-600 px-4 py-2.5
                           text-sm font-medium text-white hover:bg-blue-700
                           focus:outline-none focus:ring-4 focus:ring-blue-500/20
                           dark:bg-blue-500 dark:hover:bg-blue-600">
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
</div>