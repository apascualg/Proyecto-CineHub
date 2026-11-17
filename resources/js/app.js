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