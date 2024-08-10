const divC = document.querySelector("#mensaje-caja");
function mensajeExitosoC() {
  divC.innerHTML = `<div class="mensaje"><div class="exito" id="exito" >Compra finalizada</div></div>`;
}

function mensajeFallidoC() {
  divC.innerHTML = `<div class="mensaje"><div class="error" id="error">Error</div></div>`;
}

function mostrarMensajeC() {
  const cookiesArray = document.cookie.split(";");
  const cookiesObject = cookiesArray.reduce((acc, cookie) => {
    const [key, value] = cookie.split("=");
    acc[key.trim()] = value ? value.trim() : "";
    return acc;
  }, {});

  const cookiesJSON = JSON.stringify(cookiesObject);

  if (cookiesObject["mensaje"] === "exito") {
    mensajeExitosoC();
  } else if (cookiesObject["mensaje"] === "error") {
    mensajeFallidoC();
  }
}
window.onload = mostrarMensajeC();
