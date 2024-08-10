const imputNombre = document.getElementById("producto-filtro");
const inputPrecioMin = document.getElementById("precio-min");
const inputPrecioMax = document.getElementById("precio-max");
const contenedor = document.querySelector(".col-lg.contenedor-cartas");
const btnFiltrar = document.querySelector(".btn.btn-primary.filtrar");
const eliminarFiltros = document.getElementById("btn-eliminar-filtros");
let coincidencia = 0;

function filtrarProductos() {
  contenedor.innerHTML = "";
  coincidencia = 0;

  let palabraAfiltrar = imputNombre.value.trim();
  let precioMin = parseFloat(inputPrecioMin.value);
  let precioMax = parseFloat(inputPrecioMax.value);

  dataFilter.forEach((element) => {
    let matchesNombre =
      palabraAfiltrar === "" || element.producto.includes(palabraAfiltrar);
    let matchesPrecio =
      (isNaN(precioMin) || element.precio >= precioMin) &&
      (isNaN(precioMax) || element.precio <= precioMax);

    if (
      (palabraAfiltrar && matchesNombre && matchesPrecio) ||
      (!palabraAfiltrar && matchesPrecio) ||
      (palabraAfiltrar && matchesNombre && isNaN(precioMin) && isNaN(precioMax))
    ) {
      const imagePath = `http://localhost/proyecto-pagina-mayorista/pagina/uploads/${element.imagen}`;
      const divs = `
            <div loading="lazy" class="wow fadeInUp" id="${element.codigo}">
                <div class="card card-body border-0 text-center shadow pt-5 tarjeta-productos">
                    <form method="post" class="form-action-redirection" action="http://localhost/proyecto-pagina-mayorista/pagina/html/producto.php" id="${element.codigo}" value="${element.codigo}">
                        <input class="input-disabled" type="text" name="code">
                        <div class="svg-icon mx-auto mb-4">
                            <img loading="lazy" src="${imagePath}" alt="" class="img-productos" id="${element.codigo}">
                        </div>
                        <h5 class="fg-gray nombre-producto" id="${element.codigo}">${element.producto}</h5>
                        <p id="${element.codigo}" class="id-producto">codigo de producto:${element.codigo}</p>
                        <p class="fs-small" id="${element.codigo}">${element.descripcion}.</p>
                        <p id="${element.codigo}" class="proveedor"> Proveedor: ${element.distribuidora}.</p>
                        <h6 id="${element.codigo}" class="precio-producto">Precio: $${element.precio} c/u</h6>
                    </form>
                    <button id=${element.codigo} class="btn-agregar-carrito">
                        <img width="32" height="30" src="https://img.icons8.com/pastel-glyph/64/FFFFFF/shopping-trolley--v2.png" alt="shopping-trolley--v2" />
                    </button>
                </div>
            </div>`;
      contenedor.innerHTML += divs;
      coincidencia += 1;
    }
  });

  if (coincidencia === 0) {
    const divNocoincidencia = `<div class="no-coinciden"><p class="letras-no-coincide">No se encontraron resultados para los criterios especificados ❌</p></div>`;
    contenedor.innerHTML = divNocoincidencia;
    btnFiltrar.style.display = "none";
  } else {
    const filtradoExitoso = `<div loading="lazy" class="no-coinciden filtrado-exitoso" id="filtrado-exitoso">Filtrado exitoso</div>`;
    contenedor.innerHTML += filtradoExitoso;
    btnFiltrar.style.display = "none";
  }
}

btnFiltrar.addEventListener("click", () => {
  filtrarProductos();
  addEventListeners();
});
eliminarFiltros.addEventListener("click", () => {
  window.location.reload(true);
});

function addEventListeners() {
  const redirec = document.querySelectorAll(".form-action-redirection");
  const btnAgregarcarrito = document.querySelectorAll(".btn-agregar-carrito");

  redirec.forEach((element) => {
    element.addEventListener("click", (e) => {
      const imputvalue = e.target.id;
      console.log("caca");
      document.cookie = "code=" + imputvalue + ";max-age=3600;";
      document.location =
        "http://localhost/proyecto-pagina-mayorista/pagina/html/producto.php";
    });
  });

  btnAgregarcarrito.forEach((element) => {
    element.addEventListener("click", (e) => {
      const imputvalue = e.target.id;
      agregarProductoACarrito(imputvalue);
    });
  });
}
