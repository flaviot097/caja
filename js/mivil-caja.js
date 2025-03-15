document.addEventListener("DOMContentLoaded", () => {
  const lista = document.getElementById("lista");
  const totalVenta = document.getElementById("totalVenta");
  const agregarProductoBtn = document.getElementById("agregarProducto");
  const finalizarVentaBtn = document.getElementById("finalizarVenta");
  const descuento_total = document.getElementById("descuento_total");
  let productos = [];
  let total = 0;

  // Función para obtener los datos del producto desde la API
  async function obtenerProducto(codigoBarras, nombre) {
    try {
      let url = `producto_movil_caja.php?`;
      if (codigoBarras) {
        url += `codigo_barra=${encodeURIComponent(codigoBarras)}`;
      } else if (nombre) {
        url += `nombre=${encodeURIComponent(nombre)}`;
      }
      const response = await fetch(url);
      if (!response.ok) {
        alert("No se encontró el producto");
        return null;
      }
      const data = await response.json();
      return data;
    } catch (error) {
      console.error("Error al obtener el producto:", error);
      return null;
    }
  }

  // Agregar producto a la lista
  agregarProductoBtn.addEventListener("click", async () => {
    const codigoBarras1 = document
      .getElementById("codigoBarras")
      .value.toString();
    const codigoBarras = document.getElementById("codigoBarras").value.trim();
    const nombreProducto = document
      .getElementById("nombreProducto")
      .value.trim();
    const cantidad = parseFloat(document.getElementById("cantidad").value);
    const descuentoUni = parseFloat(
      document.getElementById("decuento_uni").value
    );

    if ((!codigoBarras && !nombreProducto) || cantidad <= 0) {
      alert(
        "Por favor, ingrese un código de barras o un nombre, y una cantidad válida."
      );
      return;
    }

    const productoAPI = await obtenerProducto(codigoBarras, nombreProducto);
    if (productoAPI) {
      const { nombre_producto, precio } = productoAPI;
      const precioUnitario = parseFloat(precio);

      // Calcular el precio unitario final según el descuento
      const descuentoUnitario =
        descuentoUni > 0 ? (precioUnitario * descuentoUni) / 100 : 0;
      const precioFinal = precioUnitario - descuentoUnitario;
      const subtotal = precioFinal * cantidad;

      // Crear el producto con descuento aplicado (si aplica)
      const producto = {
        nombre_producto: descuentoUni > 0 ? nombre_producto : nombre_producto,
        codigo_barra: productoAPI.codigo_barra || "N/A",
        cantidad,
        precioUnitario: precioFinal,
        subtotal,
        decuento_u: descuentoUni > 0 ? descuentoUni : 0, // Descuento unitario
        precio_sim: descuentoUni > 0 ? precioUnitario : 0, // Precio original sin descuento
      };

      // Agregar el producto a la lista
      productos.push(producto);

      // Actualizar la lista y limpiar campos
      actualizarLista();
      limpiarCampos();

      // Actualizar el total
      total += parseFloat(subtotal);
      totalVenta.textContent = total.toFixed(2);
      document.getElementById("searchInputTotal").value = total.toFixed(2);
      if (descuento_total.value !== 0) {
        let condescuento =
          total - (total * parseFloat(descuento_total.value)) / 100;
        totalVenta.textContent = condescuento.toFixed(2);
        document.getElementById("searchInputTotal").value =
          condescuento.toFixed(2);
        document.cookie = `descuentos=${
          "0." + descuento_total
        }; path=/; max-age=3600`;
      }
    }
  });

  // Actualizar la lista de productos en la interfaz
  function actualizarLista() {
    lista.innerHTML = "";
    productos.forEach((producto, index) => {
      const li = document.createElement("li");
      let contenido = `
        ${producto.nombre_producto} (${
        producto.cantidad
      } x $${producto.precioUnitario.toFixed(
        2
      )}) - $${producto.subtotal.toFixed(2)}
        <input type="hidden" class="eliminar" name="codigo_barra" value="${
          producto.codigo_barra
        }">
        <button class="eliminar" data-index="${index}">Eliminar</button>
      `;

      // Mostrar descuento y precio original si aplica
      if (producto.decuento_u > 0) {
        contenido += `<br><small>Descuento: ${
          producto.decuento_u
        }%, Precio Original: $${producto.precio_sim.toFixed(2)}</small>`;
      }

      li.innerHTML = contenido;
      lista.appendChild(li);
    });

    // Agregar evento para eliminar productos
    document.querySelectorAll(".eliminar").forEach((boton) => {
      boton.addEventListener("click", (e) => {
        const index = e.target.getAttribute("data-index");
        eliminarProducto(index);
      });
    });
  }

  // Eliminar un producto de la lista
  function eliminarProducto(index) {
    const productoEliminado = productos.splice(index, 1)[0];
    total -= parseFloat(productoEliminado.subtotal);
    totalVenta.textContent = total.toFixed(2);
    actualizarLista();
  }

  // Limpiar campos del formulario
  function limpiarCampos() {
    document.getElementById("codigoBarras").value = "";
    document.getElementById("nombreProducto").value = "";
    document.getElementById("cantidad").value = "1";
    document.getElementById("decuento_uni").value = "0";
  }

  // Finalizar la venta
  finalizarVentaBtn.addEventListener("click", () => {
    if (productos.length > 0) {
      const productosCookie = productos.map((producto) => ({
        nombre_producto: producto.nombre_producto,
        precio: producto.precioUnitario,
        codigo_barra: producto.codigo_barra,
        cantidad: producto.cantidad,
        total: producto.subtotal,
        decuento_u: producto.decuento_u || 0,
        precio_sim: producto.precio_sim || producto.precioUnitario,
      }));
      const product_total = document.getElementById("productos_total");
      product_total.value = JSON.stringify(productosCookie);

      // document.cookie =
      //   "productos_caja=; path=/; expires=Thu, 01 Jan 1970 00:00:00 UTC";
      // document.cookie = `productos_caja=${JSON.stringify(
      //   productosCookie
      // )}; path=/; max-age=3600`;
      productos = [];
      total = 0;
      totalVenta.textContent = "0.00";
      lista.innerHTML = "";
    } else {
      alert("No hay productos en la lista.");
    }
  });
});
