export function initPhoneFormatter() {
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            let x = value.match(/(\d{0,1})(\d{0,3})(\d{0,3})(\d{0,2})(\d{0,2})/);
            let formatted = !x[2] ? x[1] : x[1] + ' ' + x[2] + (x[3] ? ' ' + x[3] : '') + (x[4] ? ' ' + x[4] : '') + (x[5] ? ' ' + x[5] : '');
            e.target.value = formatted ? ('+' + formatted) : '';
        });

        // Удаляем все нецифровые символы перед отправкой формы
        phoneInput.form?.addEventListener('submit', function() {
            phoneInput.value = phoneInput.value.replace(/\D/g, '');
        });
    }
} 