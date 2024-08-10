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

    <!-- MAIN STYLE -->
    <link rel="stylesheet" href="css/tooplate-style.css" />
</head>

<body>
    <!-- MENU -->
    <nav class="navbar navbar-expand-sm navbar-light backgraund-header">
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
                    <div class="ml-lg-4">
                        <div class="color-mode d-lg-flex justify-content-center align-items-center">
                            <i class="color-mode-icon"></i>
                        </div>
                    </div>
                </ul>
            </div>
        </div>
    </nav>
    <!-- BARRA LATERAL -->
    <div class="body-container">
        <div class="ventas-recaudacion">
            <p><strong>Recaudacion de ventas(Local).</strong></p>
            <canvas id="grafica1"></canvas>
        </div>
        <div class="ventas-recaudacion">
            <p><strong>Recaudacion de ventas(Reparto).</strong></p>
            <canvas id="grafica2"></canvas>
        </div>
        <div class="ventas-recaudacion">
            <p><strong>Costos.</strong></p>
            <canvas id="grafica3"></canvas>
        </div>
        <div class="ventas-recaudacion">
            <p><strong>Dias del Mes.</strong></p>
            <canvas id="grafica4"></canvas>
        </div>
    </div>

    <footer class="footer py-5">
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

    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/Headroom.js"></script>
    <script src="js/jQuery.headroom.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/smoothscroll.js"></script>
    <script src="js/custom.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/estadisticas.js"></script>
    <script src="js/estadisticas-local.js"></script>
    <script src="js/estadisticas-reparto.js"></script>
    <script src="js/estadisticas-dia-venta.js"></script>
    <script src="js/costos.js"></script>
</body>

</html>