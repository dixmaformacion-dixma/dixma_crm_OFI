(function () {
    var storagePrefix = 'crmCursoScroll:';

    function getStorageKey(url) {
        var params = new URLSearchParams(url.search);
        params.delete('eliminarCurso');
        params.delete('returnUrl');
        var query = params.toString();
        return storagePrefix + url.pathname + (query ? '?' + query : '');
    }

    function saveCurrentScroll(verticalOffset) {
        var currentUrl = new URL(window.location.href);
        var key = getStorageKey(currentUrl);
        var offset = typeof verticalOffset === 'number' ? verticalOffset : 0;
        var targetY = Math.max(0, (window.scrollY || 0) - offset);
        sessionStorage.setItem(key, JSON.stringify({ x: window.scrollX || 0, y: targetY }));
    }

    function restoreScroll() {
        var currentUrl = new URL(window.location.href);
        var key = getStorageKey(currentUrl);
        var rawValue = sessionStorage.getItem(key);

        if (!rawValue) {
            return;
        }

        try {
            var position = JSON.parse(rawValue);
            sessionStorage.removeItem(key);
            window.scrollTo(position.x || 0, position.y || 0);
        } catch (error) {
            sessionStorage.removeItem(key);
        }
    }

    document.addEventListener('click', function (event) {
        var deleteLink = event.target.closest('.js-delete-curso');

        if (!deleteLink) {
            return;
        }

        event.preventDefault();

        var confirmMessage = deleteLink.getAttribute('data-confirm-message') || '¿Estás seguro de que deseas eliminar este curso? Esta acción es irreversible.';

        if (!window.confirm(confirmMessage)) {
            return;
        }

        saveCurrentScroll();
        window.location.href = deleteLink.href;
    });

    document.addEventListener('submit', function (event) {
        if (event.target.closest('.js-preserve-scroll-form')) {
            saveCurrentScroll(1000);
        }
    }, true);

    document.addEventListener('DOMContentLoaded', restoreScroll);
    window.addEventListener('load', restoreScroll);

    if ('scrollRestoration' in history) {
        history.scrollRestoration = 'manual';
    }
})();