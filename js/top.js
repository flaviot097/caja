document.addEventListener("DOMContentLoaded", () => {
  const lista = document.getElementById("lista");
  const agregarProductoBtn = document.getElementById("agregarProducto");
  const finalizarVentaBtn = document.getElementById("finalizarVenta");
  let productos = [];

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
    const codigoBarras = document.getElementById("codigoBarras").value.trim();
    const nombreProducto = document
      .getElementById("nombreProducto")
      .value.trim();

    if (!codigoBarras && !nombreProducto) {
      alert("Por favor, ingrese un código de barras o un nombre.");
      return;
    }

    const productoAPI = await obtenerProducto(codigoBarras, nombreProducto);
    if (productoAPI) {
      // Crear el producto solo con código de barras y nombre
      const producto = {
        nombre_producto: productoAPI.nombre_producto || "N/A",
        codigo_barra: productoAPI.codigo_barra || "N/A",
      };

      // Agregar el producto a la lista
      productos.push(producto);

      // Actualizar la lista y limpiar campos
      actualizarLista();
      limpiarCampos();
    }
  });

  // Actualizar la lista de productos en la interfaz
  function actualizarLista() {
    lista.innerHTML = "";
    productos.forEach((producto, index) => {
      const li = document.createElement("li");
      li.className = "itemLista";
      let contenido = `
        ${producto.nombre_producto}  
        <input type="hidden" class="eliminar" name="codigo_barra" value="${producto.codigo_barra}">
        <button class="eliminar" data-index="${index}">Eliminar</button>
      `;

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
    productos.splice(index, 1);
    actualizarLista();
  }

  // Limpiar campos del formulario
  function limpiarCampos() {
    document.getElementById("codigoBarras").value = "";
    document.getElementById("nombreProducto").value = "";
  }

  // Finalizar la venta
  finalizarVentaBtn.addEventListener("click", () => {
    if (productos.length > 0) {
      const productosCookie = productos.map((producto) => ({
        nombre_producto: producto.nombre_producto,
        codigo_barra: producto.codigo_barra,
      }));

      //const product_total = document.getElementById("productos_total");
      //product_total.value = JSON.stringify(productosCookie);
      document.cookie = `productosTop=${JSON.stringify(
        productosCookie
      )}; path=/`;
      // Limpiar la lista después de finalizar
      productos = [];
      lista.innerHTML = "";
    } else {
      alert("No hay productos en la lista.");
    }
  });
});
