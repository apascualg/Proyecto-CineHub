const catalogForm = document.querySelector('.catalog-search-form');

if (catalogForm) {
    let textInputTimeout;

    const submitCatalogForm = () => {
        catalogForm.requestSubmit();
    };

    catalogForm.querySelectorAll('select, input[type="number"]').forEach((field) => {
        field.addEventListener('change', submitCatalogForm);
    });

    catalogForm.querySelectorAll('input[type="search"], input[type="text"]').forEach((field) => {
        field.addEventListener('input', () => {
            window.clearTimeout(textInputTimeout);
            textInputTimeout = window.setTimeout(submitCatalogForm, 400);
        });
    });
}

document.querySelectorAll('.catalog-search input[type="search"]').forEach((input) => {
    const wrapper = input.closest('.catalog-search');
    const suggestions = document.createElement('div');
    suggestions.className = 'search-suggestions';
    suggestions.hidden = true;
    wrapper.appendChild(suggestions);

    let timeout;
    input.addEventListener('input', () => {
        window.clearTimeout(timeout);
        const query = input.value.trim();
        if (query.length < 2) {
            suggestions.hidden = true;
            return;
        }

        timeout = window.setTimeout(async () => {
            try {
                const response = await fetch(`/catalog/suggestions?q=${encodeURIComponent(query)}`);
                if (!response.ok) return;
                const titles = await response.json();
                suggestions.replaceChildren();

                if (!titles.length) {
                    suggestions.hidden = true;
                    return;
                }

                titles.forEach((title) => {
                    const link = document.createElement('a');
                    link.href = `/titles/${title.id}`;
                    link.textContent = `${title.title} · ${title.type === 'series' ? 'Serie' : 'Película'}`;
                    suggestions.appendChild(link);
                });
                suggestions.hidden = false;
            } catch {
                suggestions.hidden = true;
            }
        }, 250);
    });

    document.addEventListener('click', (event) => {
        if (!wrapper.contains(event.target)) {
            suggestions.hidden = true;
        }
    });
});

const buttonDescriptions = [
    [/^Explorar catálogo$/i, 'Abrir el catálogo público con películas y series'],
    [/^Crear cuenta$/i, 'Crear una cuenta para guardar títulos y valorar contenido'],
    [/^Registrarme ahora$/i, 'Crear una cuenta gratuita de CineHub'],
    [/^Iniciar sesión$/i, 'Acceder a tu cuenta personal'],
    [/^Registrarse$/i, 'Abrir el formulario de registro'],
    [/^Sorpréndeme$/i, 'Elegir aleatoriamente un título con los filtros actuales'],
    [/^Cerrar sesión$/i, 'Salir de tu cuenta actual'],
    [/^Buscar$/i, 'Buscar títulos con el texto y filtros aplicados'],
    [/^Limpiar$/i, 'Eliminar todos los filtros de búsqueda'],
    [/^Ver todo$/i, 'Abrir el catálogo completo'],
    [/^Valorar/i, 'Puntuar el título del 1 al 10'],
    [/^Añadir a/i, 'Guardar el título en esta lista'],
    [/^Quitar de/i, 'Eliminar el título de esta lista'],
    [/^Crear lista$/i, 'Crear una lista personalizada'],
    [/^Gestionar$/i, 'Abrir la gestión de este apartado'],
    [/^Administración$/i, 'Abrir el panel de administración'],
    [/^Moder/i, 'Revisar y cambiar el estado de las reseñas'],
    [/^Importar$/i, 'Importar este resultado desde TMDB'],
];

document.querySelectorAll('a, button').forEach((element) => {
    if (element.hasAttribute('title') || element.hasAttribute('aria-label')) return;

    const text = element.textContent?.trim().replace(/\s+/g, ' ');
    if (!text) return;

    const match = buttonDescriptions.find(([pattern]) => pattern.test(text));
    if (match) {
        element.setAttribute('title', match[1]);
    }
});