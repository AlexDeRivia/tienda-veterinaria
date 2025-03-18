function limpiarFormulario() {
    const formulario = document.getElementById("clearForm");
    for (let i = 0; i < formulario.elements.length; i++) {
        const elemento = formulario.elements[i];
        elemento.value = null;
    }
}