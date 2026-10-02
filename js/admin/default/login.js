// Admin bejelentkezés: a szem gomb megjeleníti / elrejti a jelszót
document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.querySelector('.js-jelszomutato');
    const password = document.getElementById('LoginJelszo');
    if (!toggle || !password) {
        return;
    }
    toggle.addEventListener('click', () => {
        const visible = password.type === 'password';
        password.type = visible ? 'text' : 'password';
        toggle.setAttribute('aria-pressed', visible ? 'true' : 'false');
        password.focus();
    });
});
