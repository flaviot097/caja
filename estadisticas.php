<!DOCTYPE html>
<?php
session_start();

require_once "conecion.php";
$dsn = 'mysql:host=localhost:3307;dbname=code_bar;';
try {
    $pdo = new PDO($dsn, $usuario, $contrasena);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'error al conectarse: ' . $e->getMessage();
    exit;
}
$total_hoy = 0;
$color = "";
date_default_timezone_set('America/Buenos_Aires');
$hoy = date("Y-m-d");
$consulataTodosUsuarios = "SELECT usuario FROM usuario";
$stmt = $pdo->prepare($consulataTodosUsuarios);
$stmt->execute();
$todosUsuers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<html lang="en">
<style>
th,
td,
tr {
    border: solid black 0.5px;
    text-align: center;
}
</style>

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
        <div class="ventas-recaudacion">
            <p><strong>Tabla de ventas.</strong></p>
            <form action="" method="get">
                <label for="usaer">Seleccione usuario</label>
                <select name="usuario" id="usaer">
                    <?php foreach ($todosUsuers as $selectUser) { ?>
                    <option value="<?php echo $selectUser["usuario"]; ?>">
                        <?php echo $selectUser["usuario"]; ?>
                    </option>
                    <?php }
                    $total = 0;
                    ?>
                </select>
                <input type="date" name="fecha">
                <input type="submit" value="Consultar ventas" />
                <!-- <a href="estadisticas-fecha.php"></a><button>Consultar por fecha</button></a> -->
            </form>


            <table style="border: solid black 1px; width: 100%; ">
                <thead style="width: 100%;">
                    <tr>
                        <th>Usuario</th>
                        <th>Monto</th>
                        <th>fecha</th>
                    </tr>
                </thead>
                <?php if ($_GET) {
                    $user = $_GET["usuario"];
                    $fecha = $_GET["fecha"] ?? "";

                    $conultaVentas = "";
                    if ($fecha == "") {
                        $conultaVentas = "SELECT * FROM ventas WHERE usuario = :usuario";
                    } else {
                        $conultaVentas = "SELECT * FROM ventas WHERE usuario = :usuario AND fecha = :fecha";
                    }

                    $stmt = $pdo->prepare($conultaVentas);
                    $stmt->bindParam(':usuario', $user, PDo::PARAM_STR);
                    if ($fecha !== "") {
                        $stmt->bindParam(':fecha', $fecha, PDo::PARAM_STR);
                    }
                    $stmt->execute();
                    $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);


                    foreach ($datos as $venta) {
                        $total = $total + intval($venta["total"]);
                        ?>

                <tbody>
                    <tr>
                        <td><?php echo $venta["usuario"]; ?></td>
                        <td>$<?php echo $venta["total"]; ?></td>
                        <td <?php
                                if ($hoy == $venta["fecha"]) {
                                    $color = "green";
                                    $total_hoy += intval($venta["total"]);
                                }
                                $venta["fecha"]; ?> style=" background-color: <?php echo $color; ?>;">
                            <?php echo $venta["fecha"]; ?>
                        </td>
                    </tr>
                </tbody>
                <?php }
                } ?>
                <tfoot>
                    <tr>
                        <td colspan="2"><strong>Total</strong></td>
                        <td><strong>$<?php echo $total; ?></strong></td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong>Total hoy</strong></td>
                        <td><strong>$<?php echo $total_hoy; ?></strong></td>
                    </tr>
                </tfoot>
            </table>

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
    <script src="js/dark-mode.js"></script>
</body>

</html>