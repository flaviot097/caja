console.log(datosProductos);
const divConteiner = document.querySelector(".productos-stock");
function reprodCart() {
  datosProductos.forEach((articulo) => {
    if (articulo.stock > 500) {
      const carta = `<div class="producto-stock" id="${articulo.codigo}">
                        <a href="#" class="btn btn-primary btn-sm d-inline-flex align-items-center">${articulo.producto}. <h6
                                class="codigo-producto" id="codigo-producto">codigo de producto: ${articulo.codigo}</h6>
                            <h6 class="codigo-producto" id="cantidad-producto" name="${articulo.stock}"> Cantidad: ${articulo.stock} Unidades</h6>
                        </a>
                    </div>`;
      divConteiner.innerHTML += carta;
    } else if (articulo.stock < 500 && articulo.stock >= 1) {
      const cartaPocoStock = `<div class="producto-stock" id="${articulo.codigo}">
                                <a href="#" class="btn btn-primary btn-sm d-inline-flex align-items-center poco" id="poco"> ${articulo.producto}.    
                                <h6 class="codigo-producto" id="codigo-producto">codigo de producto: ${articulo.codigo}
                                </h6>
                                <h6 class="cantidad-producto" id="cantidad-producto" name="${articulo.stock}" > Cantidad: ${articulo.stock} Unidades</h6>
                            </a>
                        </div>`;
      divConteiner.innerHTML += cartaPocoStock;
    } else {
      const cartaSinStock = `<div class="producto-stock" id="${articulo.codigo}">
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
reprodCart();
