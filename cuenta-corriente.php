<!DOCTYPE html>
<?php
session_start();
date_default_timezone_set('America/Buenos_Aires');
require_once "conecion.php";
$dsn = "mysql:host=localhost:3307;dbname=code_bar;";
try {
    $pdo = new PDO($dsn, $usuario, $contrasena);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage();
}

$query = "SELECT * FROM fiado";
$statement = $pdo->prepare($query);
$statement->execute();
$todosFiados = $statement->fetchAll(PDO::FETCH_ASSOC);
$para_cookies = json_encode($todosFiados);

setcookie("fiados_todos", $para_cookies, time() + 3600, "/");
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
    <link rel="stylesheet" href="css/cuenta-corriente.css">

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
                <?php require_once "cabecera.php"; ?>

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

    <div class="body-container">
        <div class="container-cartas-fiado">
            <h1 class="text-fiado">Cuenta corriente</h1>
            <?php
            $array_deudas = [];

            // Agrupando las deudas por dni
            foreach ($todosFiados as $value) {
                $dni = $value["dni"];
                if (!isset($array_deudas[$dni])) {
                    $array_deudas[$dni] = [
                        "nombre_y_apellido" => $value["nombre_y_apellido"],
                        "saldo" => 0,
                        "productos" => [],
                        "cantidad" => []
                    ];
                }
                $array_deudas[$dni]["saldo"] += $value["saldo"];
                $productos_arr = json_decode($value["productos"], true);
                $cant_arr = json_decode($value["cantidad"], true);

                if (is_array($productos_arr) && is_array($cant_arr)) {
                    foreach ($productos_arr as $index => $producto) {
                        if (isset($array_deudas[$dni]["productos"][$producto])) {
                            $array_deudas[$dni]["productos"][$producto] += $cant_arr[$index];
                        } else {
                            $array_deudas[$dni]["productos"][$producto] = $cant_arr[$index];
                        }
                    }
                }
            }

            // Mostrar tarjetas para cada DNI
            foreach ($array_deudas as $dni => $deuda) {
                $totalPersona = $deuda["saldo"];
                foreach ($deuda["productos"] as $producto => $cantidad) {
                    $queryP = "SELECT precio FROM producto WHERE codigo_barra = :codigo_barra";
                    $stmtP = $pdo->prepare($queryP);
                    $stmtP->bindParam(':codigo_barra', $producto, PDO::PARAM_STR);
                    $stmtP->execute();
                    $f = $stmtP->fetch(PDO::FETCH_ASSOC);
                    if ($f !== false) {
                        $precioUnitario = $f["precio"];
                        $totalPersona += $precioUnitario * $cantidad;
                    }
                }
                if ($totalPersona !== 0) {

                    ?>

            <div class="detalle">
                <div class="container-title">
                    <h2><?php echo $deuda["nombre_y_apellido"]; ?></h2>
                    <form action="detalle-fiado.php" method="get" style="display: flex;
    align-content: space-around;
    flex-wrap: wrap; margin-left: 15px;">
                        <input type="hidden" name="dni-validate" value="<?php echo $dni; ?>">
                        <input type="submit" value="Detalle">
                    </form>
                </div>

                <form class="form-detalle" action="fiado-actualizar.php" method="post" class="card-fiado">
                    <p><strong>Deuda:</strong> $<?php echo $totalPersona; ?></p>
                    <input type="number" value="<?php echo $totalPersona; ?>" name="pagar_total" style="display: none;">
                    <input type="hidden" name="nombre_apellido" value="<?php echo $deuda["nombre_y_apellido"]; ?>">
                    <input type="number" value="<?php echo $dni; ?>" name="dni" style="display: none;">
                    <input type="text" value="<?php echo htmlspecialchars(json_encode($deuda["productos"])); ?>"
                        name="cantidad_productos" style="display: none;">
                    <p><strong>Entrega:</strong> $<input type="number" value="<?php echo $totalPersona; ?>"
                            name="entrega"></p>
                    <select name="pagar" id="pagar">
                        <option value="liquidar_total">Liquidar total de deuda</option>
                        <option value="entregar">Entrega</option>
                    </select>
                    <button class="pay-button" type="submit">Pagar</button>
                </form>
            </div>
            <?php }
            }
            ?>
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
    <script src="js/dark-mode.js"></script>
</body>

</html>

<?php


$ello = '<!DOCTYPE html>

session_start();

require_once "conecion.php";
$dsn = "mysql:host=localhost:3307;dbname=code_bar;";
try {
    $pdo = new PDO($dsn, $usuario, $contrasena);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage();
}

$query = "SELECT * FROM fiado";
$statement = $pdo->prepare($query);
$statement->execute();
$todosFiados = $statement->fetchAll(PDO::FETCH_ASSOC);
$para_cookies = json_encode($todosFiados);

setcookie("fiados_todos", $para_cookies, time() + 3600, "/");
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
    <link rel="stylesheet" href="css/cuenta-corriente.css">

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
                <?php require_once "cabecera.php"; ?>

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

    <div class="body-container">
        <div class="container-cartas-fiado">
            <h1 class="text-fiado">Cuenta corriente</h1>
            <?php
            $array_dni = [];
            $array_cant_p = [];
            foreach ($todosFiados as $value) {
                $productos_arr = json_decode($value["productos"], true);
                $cant_arr = json_decode($value["cantidad"], true);

                if (is_array($productos_arr) && is_array($cant_arr)) {
                    foreach ($productos_arr as $index => $producto) {
                        if (!isset($array_dni[$value["dni"]])) {
                            $array_dni[$value["dni"]] = [];
                            $array_cant_p[$value["dni"]] = [];
                        }
                        if (isset($array_dni[$value["dni"]][$producto])) {
                            $array_dni[$value["dni"]][$producto] += $cant_arr[$index];
                        } else {
                            $array_dni[$value["dni"]][$producto] = $cant_arr[$index];
                        }
                    }
                }
            }
            foreach ($array_dni as $dni => $productos) {
                $totalPersona = 0;
                foreach ($productos as $producto => $cantidad) {
                    $queryP = "SELECT precio FROM producto WHERE codigo_barra = :codigo_barra";
                    $stmtP = $pdo->prepare($queryP);
                    $stmtP->bindParam(":codigo_barra", $producto, PDO::PARAM_STR);
                    $stmtP->execute();
                    $f = $stmtP->fetch(PDO::FETCH_ASSOC);
                    if ($f !== false) {
                        $precioUnitario = $f["precio"];
                        $totalPersona += $precioUnitario * $cantidad;
                    }
                }
                $value = $todosFiados[array_search($dni, array_column($todosFiados, "dni"))];
                ?>

            <div class="detalle">
                <div class="container-title">
                    <h2><?php echo $value["nombre_y_apellido"]; ?></h2>
                    <form action="detalle-fiado.php" method="get" style="display: flex;
    align-content: space-around;
    flex-wrap: wrap; margin-left: 15px;">
                        <input type="hidden" name="dni-validate" value="<?php echo $value["dni"] ?>">
                        <input type="submit" value="Detalle">
                    </form>
                </div>

                <form class="form-detalle" action="fiado-actualizar.php" method="post" class="card-fiado">
                    <p><strong>Deuda:</strong> $<?php echo $totalPersona + $value["saldo"]; ?></p>
                    <input type="number" value="<?php echo $totalPersona; ?>" name="pagar_total" style="display: none;">
                    <input type="hidden" name="nombre_apellido" value="<?php echo $value["nombre_y_apellido"]; ?>">
                    <input type="number" value="<?php echo $value["dni"] ?>" name="dni" style="display: none;">
                    <input type="text" value="<?php echo htmlspecialchars(json_encode($productos)); ?>"
                        name="cantidad_productos" style="display: none;">
                    <p><strong>Entrega:</strong> $<input type="number"
                            value="<?php echo $totalPersona + $value["saldo"]; ?>" name="entrega"></p>
                    <select name="pagar" id="pagar">
                        <option value="liquidar_total">Liquidar total de deuda</option>
                        <option value="entregar">Entrega</option>
                    </select>
                    <button class="pay-button" type="submit">Pagar</button>
                </form>
            </div>
            <?php
            }

            ?>
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
</body>

</html>'; ?>