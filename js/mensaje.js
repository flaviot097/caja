const div = document.querySelector("#mensaje");
console.log(div);

function mensajeExitoso() {
  div.innerHTML = `<div class="mensaje"><div class="exito" id="exito" >Creado con exito</div></div>`;
}

function mensajeFallido() {
  div.innerHTML = `<div class="mensaje"><div class="error" id="error">Error</div></div>`;
}

function mostrarMensaje() {
  const cookiesArray = document.cookie.split(";");
  const cookiesObject = cookiesArray.reduce((acc, cookie) => {
    const [key, value] = cookie.split("=");
    acc[key.trim()] = value ? value.trim() : "";
    return acc;
  }, {});

  const cookiesJSON = JSON.stringify(cookiesObject);

  if (cookiesObject["mensaje"] === "exito") {
    mensajeExitoso();
  } else if (cookiesObject["mensaje"] === "error") {
    mensajeFallido();
  }
}
window.onload = mostrarMensaje();
