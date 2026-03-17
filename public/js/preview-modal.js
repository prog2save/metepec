function abrirPreview(url, nombre, mime) {
    const modal = document.getElementById("previewModal");
    const contenido = document.getElementById("previewContenido");
    const titulo = document.getElementById("previewNombre");
    const descargar = document.getElementById("previewDescargar");

    titulo.textContent = nombre;
    descargar.href = url;
    descargar.setAttribute("download", nombre);
    contenido.innerHTML = "";

    // PDF
    if (mime === "application/pdf") {
        contenido.innerHTML = `
            <iframe src="${url}" frameborder="0"
                class="w-full h-full rounded-lg" style="min-height:70vh;">
            </iframe>`;

    // Imágenes (JPG, PNG, etc.)
    } else if (mime.startsWith("image/")) {
        contenido.innerHTML = `
            <img src="${url}" alt="${nombre}"
                class="max-w-full max-h-full object-contain rounded-lg shadow-md" />`;

    // DOC, DOCX y cualquier otro formato
    } else {
        contenido.innerHTML = `
            <div class="flex flex-col items-center gap-4 text-center">
                <svg class="w-14 h-14 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Este formato no tiene previsualización disponible
                </p>
                <a href="${url}" download="${nombre}"
                    class="inline-flex items-center gap-2 text-sm px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-lg transition font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Descargar ${nombre}
                </a>
            </div>`;
    }

    modal.classList.remove("hidden");
    modal.classList.add("flex");
    document.body.style.overflow = "hidden";
}

function cerrarPreview() {
    const modal = document.getElementById("previewModal");
    const contenido = document.getElementById("previewContenido");
    const iframe = contenido.querySelector("iframe");

    if (iframe) iframe.src = "";
    contenido.innerHTML = "";

    modal.classList.add("hidden");
    modal.classList.remove("flex");
    document.body.style.overflow = "";
}

// Los event listeners van dentro de DOMContentLoaded
// para garantizar que el modal ya existe en el DOM
document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("previewModal");
    if (!modal) return;

    // Cerrar con Escape
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") cerrarPreview();
    });

    // Cerrar al hacer clic fuera del panel
    modal.addEventListener("click", function (e) {
        if (e.target === this) cerrarPreview();
    });
});