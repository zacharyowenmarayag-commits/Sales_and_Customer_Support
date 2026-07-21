(function () {
    function setupDebouncedSearch(inputId, onSearch) {
        const searchInput = document.getElementById(inputId);
        if (!searchInput) return;

        let debounceTimer;
        const applySearch = () => {
            const value = searchInput.value.trim();
            onSearch(value);
        };

        searchInput.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(applySearch, 800);
        });

        searchInput.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                clearTimeout(debounceTimer);
                applySearch();
            }
        });
    }

    window.applyQuerySearch = function (inputId, baseUrl, extraParams = {}) {
        setupDebouncedSearch(inputId, (value) => {
            const url = new URL(baseUrl, window.location.origin);
            if (value) url.searchParams.set('q', value);
            else url.searchParams.delete('q');
            url.searchParams.set('page', '1');

            Object.entries(extraParams).forEach(([key, paramValue]) => {
                if (paramValue) url.searchParams.set(key, paramValue);
                else url.searchParams.delete(key);
            });

            window.location.href = url.toString();
        });
    };

    document.querySelectorAll('.crm-status-select').forEach((select) => {
        const classes = {
            Pending: ['bg-yellow-100', 'text-yellow-800', 'border-yellow-200'],
            Completed: ['bg-blue-100', 'text-blue-800', 'border-blue-200'],
            Cancelled: ['bg-gray-100', 'text-gray-800', 'border-gray-200'],
        };

        const allClasses = Object.values(classes).flat();

        select.addEventListener('change', () => {
            select.classList.remove(...allClasses);
            select.classList.add(...(classes[select.value] ?? classes.Pending));
        });
    });
})();
