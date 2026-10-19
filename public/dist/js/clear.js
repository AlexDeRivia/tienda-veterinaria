function limpiarFormulario() {
    const formulario = document.getElementById('clearForm');
    if (!formulario) return;

    formulario.querySelectorAll('input, textarea, select').forEach((elemento) => {
        if (elemento.type === 'hidden') return;
        if (elemento.type === 'checkbox' || elemento.type === 'radio') {
            elemento.checked = false;
        } else if (elemento.tagName === 'SELECT') {
            elemento.selectedIndex = 0;
        } else {
            elemento.value = '';
        }
        elemento.classList.remove('is-invalid');
    });
    formulario.classList.remove('was-validated');
}
