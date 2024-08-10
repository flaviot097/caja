function filtarStock() {
  let filtrado = datosProductos.sort(function (a, b) {
    if (a.stock < b.stock) {
      return -1;
    } else if (a.stock > b.stock) {
      return 1;
    } else {
      return 0;
    }
  });
  ////---------------------------------------------------------------
  const divConteiner = document.querySelector(".productos-stock");
  divConteiner.innerHTML = "";
  //---------------------------------------------------------------
  filtrado.forEach((articulo) => {
    if (articulo.stock > 500) {
      const carta = `<div class="producto-stock">
                              <a href="#" class="btn btn-primary btn-sm d-inline-flex align-items-center">${articulo.producto}. <h6
                                      class="codigo-producto" id="codigo-producto">codigo de producto: ${articulo.codigo}</h6>
                                  <h6 class="codigo-producto" id="cantidad-producto" name="${articulo.stock}"> Cantidad: ${articulo.stock} Unidades</h6>
                              </a>
                          </div>`;
      divConteiner.innerHTML += carta;
    } else if (articulo.stock < 500 && articulo.stock >= 1) {
      const cartaPocoStock = `<div class="producto-stock">
                                      <a href="#" class="btn btn-primary btn-sm d-inline-flex align-items-center poco" id="poco"> ${articulo.producto}.    
                                      <h6 class="codigo-producto" id="codigo-producto">codigo de producto: ${articulo.codigo}
                                      </h6>
                                      <h6 class="cantidad-producto" id="cantidad-producto" name="${articulo.stock}" > Cantidad: ${articulo.stock} Unidades</h6>
                                  </a>
                              </div>`;
      divConteiner.innerHTML += cartaPocoStock;
    } else {
      const cartaSinStock = `<div class="producto-stock">
                              <a href="#" class="btn btn-primary btn-sm d-inline-flex align-items-center faltante"
                                  id="faltante"> ${articulo.producto}. <h6 class="codigo-producto" id="codigo-producto">codigo de producto: ${articulo.codigo}
                                  </h6>
                                  <h6 class="codigo-producto" id="cantidad-producto" name="${articulo.stock}"> Sin stock</h6>
                              </a>
                          </div>`;
      divConteiner.innerHTML += cartaSinStock;
    }
  });
}

let btnMenorAMayor = document.querySelector("#btnFiltrar-menor");
btnMenorAMayor.addEventListener("click", () => {
  if (btnMenorAMayor.textContent == "Ordenar por stock") {
    filtarStock();
    btnMenorAMayor.textContent = "Ordenar por fecha";
  } else {
    btnMenorAMayor.textContent = "Ordenar por stock";
    window.location.reload();
  }
});
