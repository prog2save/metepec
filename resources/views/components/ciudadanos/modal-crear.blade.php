<x-modal id="modalCrearCiudadano" titulo="Crear Nuevo Ciudadano">

    <form id="formCrearCiudadano">
        @csrf
        <div class="space-y-4">

            {{-- Nombre --}}
            <div>
                <label for="modal_nombre" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Nombre <span class="text-red-500">*</span>
                </label>
                <input type="text" id="modal_nombre" name="nombre"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
            </div>

            {{-- Apellidos en grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="modal_apellido_paterno" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Apellido Paterno <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="modal_apellido_paterno" name="apellido_paterno"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                </div>
                <div>
                    <label for="modal_apellido_materno" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Apellido Materno <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="modal_apellido_materno" name="apellido_materno"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                </div>
            </div>

            {{-- Teléfonos en grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="modal_telefono_principal" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Teléfono <span class="text-red-500">*</span>
                    </label>
                    <input type="number" maxlength="10" id="modal_telefono_principal" name="telefono_principal"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                </div>
                <div>
                    <label for="modal_telefono_alterno" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Teléfono Alterno <span class="ml-1 text-gray-400 dark:text-gray-600 normal-case font-normal">(opcional)</span>
                    </label>
                    <input type="number" maxlength="10" id="modal_telefono_alterno" name="telefono_alterno"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                </div>
            </div>

            {{-- Email --}}
            <div>
                <label for="modal_email" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Email <span class="ml-1 text-gray-400 dark:text-gray-600 normal-case font-normal">(opcional)</span>
                </label>
                <input type="email" id="modal_email" name="email"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
            </div>

            {{-- Dirección --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label for="modal_calle" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Calle <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="modal_calle" name="direccion_calle"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                </div>
                <div>
                    <label for="modal_numero" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        N°  <span class="ml-1 text-gray-400 dark:text-gray-600 normal-case font-normal">(opcional)</span>
                    </label>
                    <input type="number" id="modal_numero" name="direccion_numero"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                </div>
            </div>

            <div>
                <label for="modal_colonia" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Colonia <span class="text-red-500">*</span>
                </label>
                <input type="text" id="modal_colonia" name="direccion_colonia"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
            </div>

            {{-- Errores AJAX --}}
            <div id="modal-errores" class="hidden text-sm text-red-600 dark:text-red-400 space-y-1"></div>

            {{-- Botones --}}
            <div class="flex justify-end gap-3 pt-2">
                <button type="button"
                    onclick="cerrarModal('modalCrearCiudadano')"
                    class="h-11 px-4 rounded-lg border border-gray-300 text-sm text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 transition-colors">
                    Cancelar
                </button>
                <x-ui.button size="sm" variant="primary" type="submit">
                    Crear Ciudadano
                </x-ui.button>
            </div>

        </div>
    </form>

</x-modal>

<script>
    document.getElementById('formCrearCiudadano').addEventListener('submit', async function(e) {
        e.preventDefault();

        const erroresDiv = document.getElementById('modal-errores');
        erroresDiv.classList.add('hidden');
        erroresDiv.innerHTML = '';

        const datos = {
            nombre: document.getElementById('modal_nombre').value,
            apellido_paterno: document.getElementById('modal_apellido_paterno').value,
            apellido_materno: document.getElementById('modal_apellido_materno').value,
            telefono_principal: document.getElementById('modal_telefono_principal').value,
            telefono_alterno: document.getElementById('modal_telefono_alterno').value,
            email: document.getElementById('modal_email').value,
            direccion_calle: document.getElementById('modal_calle').value,
            direccion_numero: document.getElementById('modal_numero').value,
            direccion_colonia: document.getElementById('modal_colonia').value,
        };

        const response = await fetch('{{ route("agente.ciudadanos.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json', // ← fix #2
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(datos)
        });

        const data = await response.json();

        if (response.ok) {
            // fix #3: usar TomSelect en lugar del select nativo
            const ts = document.getElementById('id_ciudadano')?.tomselect;
            if (ts) {
                ts.addOption({
                    value: String(data.id),
                    text: `${data.nombre} ${data.apellido_paterno} ${data.apellido_materno}`
                });
                ts.setValue(String(data.id));
            }

            // Limpiar form y cerrar
            this.reset();
            cerrarModal('modalCrearCiudadano');

        } else if (data.errors) {
            erroresDiv.classList.remove('hidden');
            Object.values(data.errors).forEach(msgs => {
                msgs.forEach(msg => {
                    const p = document.createElement('p');
                    p.textContent = '• ' + msg;
                    erroresDiv.appendChild(p);
                });
            });
        }
    });
</script>