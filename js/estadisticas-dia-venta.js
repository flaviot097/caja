document.addEventListener("DOMContentLoaded", function () {
  fetch("../../santiago_pagina/estadisticas-local-dia.php")
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok " + response.statusText);
      }
      return response.json();
    })
    .then((data) => {
      // Asegúrate de que data sea un array
      if (Array.isArray(data)) {
        // Suma total de las ventas del mes
        var total = data.reduce((sum, value) => sum + parseInt(value), 0);

        // Selecciona el contexto de la gráfica
        const ctx = document.getElementById("grafica4");

        // Crear la gráfica
        new Chart(ctx, {
          type: "bar", // Tipo de gráfica
          data: {
            labels: [
              "1",
              "2",
              "3",
              "4",
              "5",
              "6",
              "7",
              "8",
              "9",
              "10",
              "11",
              "12",
              "13",
              "14",
              "15",
              "16",
              "17",
              "18",
              "19",
              "20",
              "21",
              "22",
              "23",
              "24",
              "25",
              "26",
              "27",
              "28",
              "29",
              "30",
              "31",
            ],
            datasets: [
              {
                label: `Total: $${total}`,
                data: data, // Utiliza directamente el array de datos
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
      } else {
        console.error("El valor de 'data' no es un array:", data);
      }
    })
    .catch((error) => {
      console.error("Hubo un problema con la solicitud fetch:", error);
    });
});
