document.addEventListener("DOMContentLoaded", function () {
  fetch("../../santiago_pagina/costos-estadisticas.php")
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok " + response.statusText);
      }
      return response.json();
    })
    .then((data) => {
      //console.log(data);

      const meses = data.mes; // Accediendo a la clave "mes"
      //console.log(meses);
      var total = 0;
      meses.forEach((e) => {
        total += parseInt(e);
      });

      // Graficar estadísticas
      const ctx = document.getElementById("grafica3");

      new Chart(ctx, {
        type: "bar", // Tipo de gráfica
        data: {
          labels: [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Agosto",
            "Septiembre",
            "Octubre", // Corregido error tipográfico en "Octubre"
            "Noviembre",
            "Diciembre",
          ],
          datasets: [
            {
              label: `Total :$${total}`,
              data: meses, // Usando directamente el array de meses
              backgroundColor: "black",
            },
          ],
        },
        options: {
          scales: {
            y: { beginAtZero: false },
          },
        },
      });
    })
    .catch((error) => {
      console.error("Hubo un problema con la solicitud fetch:", error);
    });
});
