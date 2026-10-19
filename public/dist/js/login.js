const passwordToggle = document.querySelector('.password-toggle');
const passwordInput = document.getElementById('contrasena');

if (passwordToggle && passwordInput) {
    passwordToggle.addEventListener('click', () => {
        const isVisible = passwordInput.type === 'text';
        passwordInput.type = isVisible ? 'password' : 'text';
        passwordToggle.setAttribute('aria-pressed', String(!isVisible));
        passwordToggle.setAttribute('aria-label', isVisible ? 'Mostrar contraseña' : 'Ocultar contraseña');
        passwordToggle.querySelector('i').className = isVisible ? 'bi bi-eye-fill' : 'bi bi-eye-slash-fill';
    });
}
