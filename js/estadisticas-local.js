document.addEventListener("DOMContentLoaded", function () {
  fetch("../../santiago_pagina/estadisticas-local.php")
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok " + response.statusText);
      }
      return response.json();
    })
    .then((data) => {
      //console.log(data);

      const meses = data[1];
      //console.log(meses);
      var total = 0;
      meses.forEach((e) => {
        total += parseInt(e);
      });
      //graficar estadisticas
      const ctx = document.getElementById("grafica1");

      new Chart(ctx, {
        type: "bar", //tipo de gráfica
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
            "Octibre",
            "Noviembre",
            "Diciembre",
          ],
          datasets: [
            {
              label: `Total :$${total}`,
              data: [
                meses[0],
                meses[1],
                meses[2],
                meses[3],
                meses[4],
                meses[5],
                meses[6],
                meses[7],
                meses[8],
                meses[9],
                meses[10],
                meses[11],
              ],
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
