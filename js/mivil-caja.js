document.addEventListener("DOMContentLoaded", () => {
  const lista = document.getElementById("lista");
  const totalVenta = document.getElementById("totalVenta");
  const agregarProductoBtn = document.getElementById("agregarProducto");
  const finalizarVentaBtn = document.getElementById("finalizarVenta");

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
        alert("no se encontro el producto");
        return null; // No mostramos alerta si no se encuentra el producto
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
    const codigoBarras = document.getElementById("codigoBarras").value.trim();
    const nombreProducto = document
      .getElementById("nombreProducto")
      .value.trim();
    const cantidad = parseFloat(document.getElementById("cantidad").value);

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
      const subtotal = precioUnitario * cantidad; //.toFixed(2);

      const producto = {
        nombre_producto,
        codigo_barra: productoAPI.codigo_barra || "N/A",
        cantidad,
        precioUnitario,
        subtotal,
      };

      productos.push(producto);
      actualizarLista();
      limpiarCampos();

      // Actualizar el total
      total += parseFloat(subtotal);
      totalVenta.textContent = total; //.toFixed(2);
      var total_general = document.getElementById("searchInputTotal");

      total_general.value = total;
    }
  });

  // Actualizar la lista de productos en la interfaz
  function actualizarLista() {
    lista.innerHTML = "";
    productos.forEach((producto, index) => {
      const li = document.createElement("li");
      li.innerHTML = `
                ${producto.nombre_producto} (${producto.cantidad} x $${producto.precioUnitario}) - $${producto.subtotal}
               <input type="hidden" class="eliminar" name="codigo_barra" value="${producto.codigo_barra}"></input>
                <button class="eliminar" data-index="${index}">Eliminar</button>
            `;
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
    document.getElementById("cantidad").value = "";
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
      }));

      document.cookie =
        "productos_caja=; path=/; expires=Thu, 01 Jan 1970 00:00:00 UTC";
      // Convertir a JSON y guardar en una cookie
      document.cookie = `productos_caja=${JSON.stringify(
        productosCookie
      )}; path=/; max-age=3600`;

      productos = [];
      total = 0;
      totalVenta.textContent = "0.00";
      lista.innerHTML = "";
    } else {
      alert("No hay productos en la lista.");
    }
  });
});
