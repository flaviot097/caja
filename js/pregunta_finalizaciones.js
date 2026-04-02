const eliminarButtons = document.querySelectorAll(".eliminar");
const forms = document.querySelectorAll(".formulario-eliminar");
forms.forEach((form) => {
  form.addEventListener("submit", function (event) {
    event.preventDefault();
    const confirmed = confirm("¿Desea eliminar?");
    if (confirmed) {
      //this.parentElement.remove();
      this.submit();
    }
  });
});
