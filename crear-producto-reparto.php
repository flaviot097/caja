<!DOCTYPE html>
<?php
session_start();
?>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="Flavio Trocello" content="" />

    <title>pagina de gestion</title>

    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/unicons.css" />
    <link rel="stylesheet" href="css/owl.carousel.min.css" />
    <link rel="stylesheet" href="css/owl.theme.default.min.css" />
    <link rel="stylesheet" href="css/target.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/estadisticas.css">
    <link rel="stylesheet" href="css/graficas.css">
    <link rel="stylesheet" href="css/cartas-fiado.css">

    <!-- MAIN STYLE -->
    <link rel="stylesheet" href="css/tooplate-style.css" />
</head>

<body>
    <!-- MENU -->
    <nav class="navbar navbar-expand-sm navbar-light backgraund-header" style="min-width: 1200px !important;">
        <div class="container">
            <a class="navbar-brand" href="index.php"><i class="uil uil-user"></i></a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                <span class="navbar-toggler-icon"></span>
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <?php require_once "cabecera.php";
                ?>

                <ul class="navbar-nav ml-lg-auto">
                    <div class="ml-lg-4" id="icono">
                        <div class="color-mode d-lg-flex justify-content-center align-items-center">
                            <i class="color-mode-icon"></i>
                        </div>
                    </div>
                </ul>
            </div>
        </div>
    </nav>
    <div class="mensaje" id="mensaje">

    </div>
    <div class="body-container">
        <div class="container-cartas-fiado">
            <form action="accion-crear-producto-reparto.php" class="card-fiado" method="post">
                <h2 class="text-center">Ingrese el producto al reparto</h2>
                <p class="text-center"><strong>Nombre producto</strong> <br><input type="text" name="nombre_producto">
                </p>
                <p class="text-center"><strong>Codigo de barra</strong> <br><input name="codigo_barra" type="text"></p>
                <p class="text-center"><strong>departamento</strong> <br><input name="departamento" type="text"></p>
                <p class="text-center"><strong>proveedor</strong> <br><input type="text" name="proveedor"></p>
                <p class="text-center"><strong>stock</strong> <br><input name="stock" type="text"></p>
                <p class="text-center"><strong>costo</strong> <br>$<input id="costo" name="costo" type="number"></p>
                <p class="text-center"><strong>ganancia</strong><br><input id="ganancia" name="ganancia" type="number">%
                </p>
                <p class="text-center"><strong>Precio final</strong><br>$<span id="precio-final">0.00</span></p>
                <p class="text-center"><strong>Stock Minino</strong> <br><input name="num_stock" type="number"></p>
                <button id="agregar-producto" style="margin-left: 19vh;" class="pay-button" type="submit">agregar
                    Producto</button>
            </form>
        </div>
    </div>


    <footer class="footer py-5" style="min-width: 1200px !important;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-12">
                    <p class="copyright-text text-center">
                        Copyright &copy; 2024. Todos los derechos reservados
                    </p>
                    <p class="copyright-text text-center">
                        Diseñado por <a rel="nofollow" href="">Flavio Trocello</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const costoInput = document.getElementById('costo');
        const gananciaInput = document.getElementById('ganancia');
        const precioFinalSpan = document.getElementById('precio-final');

        function calcularPrecioFinal() {
            const costo = parseFloat(costoInput.value) || 0;
            const ganancia = parseFloat(gananciaInput.value) || 0;
            const precioFinal = costo + (costo * (ganancia / 100));
            precioFinalSpan.textContent = precioFinal.toFixed(2);
        }

        costoInput.addEventListener('input', calcularPrecioFinal);
        gananciaInput.addEventListener('input', calcularPrecioFinal);
    });
    </script>

    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/Headroom.js"></script>
    <script src="js/jQuery.headroom.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/smoothscroll.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/mensaje.js"></script>
    <script src="js/dark-mode.js"></script>
</body>

</html>