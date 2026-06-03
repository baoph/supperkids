import './bootstrap';

// Currency formatting is handled by inline script in layouts/app.blade.php
// to ensure it runs reliably regardless of Vite module loading order.

import Choices from 'choices.js';

// Initialize Choices.js searchable selects (e.g. student picker in payment forms).
function initChoices() {
    document.querySelectorAll('select.js-choice').forEach(function (el) {
        if (el.dataset.choicesInitialized) {
            return;
        }
        el.dataset.choicesInitialized = 'true';

        new Choices(el, {
            searchEnabled: true,
            searchPlaceholderValue: el.dataset.searchPlaceholder || 'Tìm kiếm...',
            itemSelectText: '',
            shouldSort: false,
            placeholder: true,
            placeholderValue: el.dataset.placeholder || null,
            noResultsText: 'Không tìm thấy kết quả',
            noChoicesText: 'Không có lựa chọn',
        });
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initChoices);
} else {
    initChoices();
}
