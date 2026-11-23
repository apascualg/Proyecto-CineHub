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