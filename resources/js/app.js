import './bootstrap';

/**
 * Currency Input Formatter — Chuẩn Việt Nam
 * Formats number inputs with dot separators (e.g., 1.500.000)
 * and syncs the raw numeric value to a hidden input for form submission.
 */
document.addEventListener('DOMContentLoaded', function () {
    // Format a number string with dot separators (Vietnamese standard)
    function formatCurrency(value) {
        // Remove all non-digit characters
        let num = value.replace(/[^\d]/g, '');
        // Remove leading zeros (but keep at least one zero)
        num = num.replace(/^0+(?=\d)/, '');
        // Add thousand separators using dots
        return num.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // Parse formatted string back to raw number (strip dots and any non-digits)
    function parseCurrency(value) {
        return value.replace(/[^\d]/g, '');
    }

    // Initialize all currency inputs
    const currencyInputs = document.querySelectorAll('.currency-input');

    currencyInputs.forEach(function (input) {
        const targetName = input.getAttribute('data-target');
        const form = input.closest('form');
        const hiddenInput = form ? form.querySelector('input[type="hidden"][name="' + targetName + '"]') : null;

        // Format initial value on page load
        if (input.value) {
            const raw = parseCurrency(input.value);
            input.value = raw && raw !== '0' ? formatCurrency(raw) : '0';
            if (hiddenInput) {
                hiddenInput.value = raw || '0';
            }
        }

        // Format on input (real-time as user types)
        input.addEventListener('input', function () {
            const cursorPos = input.selectionStart;
            const oldLength = input.value.length;
            const raw = parseCurrency(input.value);

            input.value = formatCurrency(raw);
            if (hiddenInput) {
                hiddenInput.value = raw;
            }

            // Adjust cursor position after formatting
            const newLength = input.value.length;
            const diff = newLength - oldLength;
            const newPos = Math.max(0, cursorPos + diff);
            input.setSelectionRange(newPos, newPos);
        });

        // Ensure hidden input is synced on blur
        input.addEventListener('blur', function () {
            const raw = parseCurrency(input.value);
            if (hiddenInput) {
                hiddenInput.value = raw || '0';
            }
            // Re-format display
            input.value = raw ? formatCurrency(raw) : '0';
        });

        // Sync before form submit (safety net)
        if (form) {
            form.addEventListener('submit', function () {
                if (hiddenInput) {
                    hiddenInput.value = parseCurrency(input.value);
                }
            });
        }
    });
});
