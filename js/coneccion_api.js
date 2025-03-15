document.addEventListener("DOMContentLoaded", () => {
  const input = document.getElementById("nombreProducto");
  const resultadosDiv = document.getElementById("resultados");

  // Función para realizar la búsqueda
  const buscarProductos = async (nombre) => {
    try {
      const response = await fetch(
        `api_productos_caja.php?nombre=${encodeURIComponent(nombre)}`
      );
      if (!response.ok) {
        throw new Error("No se encontró el producto");
      }
      const data = await response.json();

      // Limpiar resultados anteriores
      resultadosDiv.innerHTML = "";

      if (data.error) {
        resultadosDiv.innerHTML = `<p>${data.error}</p>`;
        return;
      }

      // Mostrar los resultados
      if (Array.isArray(data)) {
        data.forEach((producto) => {
          const item = document.createElement("div");
          item.className = "resultado-item";
          item.textContent = `${producto.nombre_producto} - $${producto.precio}`;
          item.addEventListener("click", () => {
            input.value = producto.nombre_producto; // Autocompletar el campo
            resultadosDiv.innerHTML = ""; // Limpiar resultados
          });
          resultadosDiv.appendChild(item);
        });
      } else {
        const item = document.createElement("div");
        item.className = "resultado-item";
        item.textContent = `${data.nombre_producto} - $${data.precio}`;
        resultadosDiv.appendChild(item);
      }
    } catch (error) {
      resultadosDiv.innerHTML = `<p>Error: ${error.message}</p>`;
    }
  };

  // Evento para detectar cambios en el campo de entrada
  let timeoutId;
  input.addEventListener("input", (event) => {
    const nombre = event.target.value.trim();
    if (nombre.length === 0) {
      resultadosDiv.innerHTML = ""; // Limpiar resultados si el campo está vacío
      return;
    }

    // Retrasar la búsqueda para evitar demasiadas solicitudes
    clearTimeout(timeoutId);
    timeoutId = setTimeout(() => {
      buscarProductos(nombre);
    }, 300); // Esperar 300ms después de que el usuario deje de escribir
  });
});
