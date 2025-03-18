document.querySelector('.pass-show span').addEventListener('click', e => {
    const passwordInput = document.querySelector('#contrasena');
    var eye = document.getElementById('eye');
    if (e.target.classList.contains('show')) {
        e.target.classList.remove('show');
        eye.classList.remove('bi-eye-fill');
        eye.classList.add('bi-eye-slash-fill');
        passwordInput.type = 'text';
    } else {
        e.target.classList.add('show');
        eye.classList.remove('bi-eye-slash-fill');
        eye.classList.add('bi-eye-fill');
        passwordInput.type = 'password';
    }
});

document.getElementById('eye').classList.add('show');
