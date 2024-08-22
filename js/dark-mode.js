var icono = document.getElementById("icono");
const f = document.querySelector("body");
var modo;
window.addEventListener("DOMContentLoaded", function () {
  modo = localStorage.getItem("modo");
  if (modo !== "claro") {
    localStorage.setItem("modo", "oscuro");
    const contedor = f;
    contedor.style.backgroundColor = "black";
    contedor.style.color = "white !important";
    if (document.querySelector(".reparto-texto")) {
      const letras = document.querySelector(".reparto-texto");
      letras.style.color = "white";
    }
  } else {
    localStorage.setItem("modo", "claro");
    const contedor = f;
    contedor.style.backgroundColor = "white";
    contedor.style.color = "black";
    if (document.querySelector(".reparto-texto")) {
      const letras = document.querySelector(".reparto-texto");
      letras.style.color = "black";
    }
  }
});

icono.addEventListener("click", function () {
  const validarModo = localStorage.getItem("modo");
  if (validarModo === "claro") {
    localStorage.setItem("modo", "oscuro");
    const contedor = f;
    contedor.style.backgroundColor = "black";
    contedor.style.color = "white !important";
    if (document.querySelector(".reparto-texto")) {
      const letras = document.querySelector(".reparto-texto");
      letras.style.color = "white";
    }
  } else {
    localStorage.setItem("modo", "claro");
    const contedor = f;
    contedor.style.backgroundColor = "white";
    contedor.style.color = "black";
    if (document.querySelector(".reparto-texto")) {
      const letras = document.querySelector(".reparto-texto");
      letras.style.color = "black";
    }
  }
});
console.log(icono);
