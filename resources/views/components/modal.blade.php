<div id="{{ $id }}"
    style="display:none; position:fixed !important; top:0 !important; left:0 !important; width:100vw !important; height:100vh !important; z-index:99999 !important; background:rgba(0,0,0,0.5); overflow-y:auto; align-items:flex-start; justify-content:center;"
    onclick="if(event.target===this)cerrarModal('{{ $id }}')">

    <div style="width:100%; max-width:42rem; margin:2.5rem auto; padding:1rem;">
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl p-6 relative">

            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    {{ $titulo }}
                </h2>
                <button type="button"
                    onclick="cerrarModal('{{ $id }}')"
                    class="text-gray-400 hover:text-gray-600 text-2xl leading-none">
                    &times;
                </button>
            </div>

            {{ $slot }}
        </div>
    </div>
</div>
