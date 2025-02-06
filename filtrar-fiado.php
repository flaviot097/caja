<!DOCTYPE html>
<?php

date_default_timezone_set('America/Buenos_Aires');

require_once "validacion-usuario.php";

require_once "conecion.php";
$dsn = "mysql:host=localhost:3307;dbname=code_bar;";
try {
    $pdo = new PDO($dsn, $usuario, $contrasena);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage();
}

error_reporting(0);

$nombre_cuenta_corriente = $_POST["nombre"];

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
    <style>
    .button-search {
        background-color: #28a745;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    #input_nombre {
        border: 1px solid #555555;
        border-radius: 5px;
    }
    </style>

    <div class="body-container">
        <div class="container-cartas-fiado">
            <div class="container-formulario">
                <form action="#" method="post" style="display: flex; flex-wrap: wrap; justify-content: space-around;">
                    <input type="text" placeholder="Ingrese Nombre..." name="nombre" id="input_nombre" class="">
                    <button type="submit" class="button-search">Buscar</button>
                </form>
            </div>

            <?php

            $consulta_dni = "SELECT dni FROM fiado WHERE nombre_y_apellido = :nombre_y_apellido";
            $stmt_dni = $pdo->prepare($consulta_dni);
            $stmt_dni->bindValue(':nombre_y_apellido', $nombre_cuenta_corriente . "%", PDO::PARAM_STR);
            $stmt_dni->execute();
            $dni_nombre = $stmt_dni->fetchAll(PDO::FETCH_ASSOC);
            $dni_nombre = $dni_nombre[0]["dni"];

            $query = "SELECT DISTINCT dni FROM fiado";
            $statement = $pdo->prepare($query);
            $statement->execute();
            $todosFiados = $statement->fetchAll(PDO::FETCH_ASSOC);
            $para_cookies = json_encode($todosFiados);


            $lista_total_producto;

            foreach ($todosFiados as $dni_persona) {

                $query = "SELECT * FROM fiado WHERE dni = :dni";
                $statement = $pdo->prepare($query);
                $statement->bindParam(":dni", $dni_persona["dni"], PDO::PARAM_INT);
                $statement->execute();
                $persona_fiado = $statement->fetchAll(PDO::FETCH_ASSOC);
                $nombre_Y_apellido = $persona_fiado[0]["nombre_y_apellido"];

                $array_persona = [];

                foreach ($persona_fiado as $producto_fiado) {

                    $cantidad_productos = floatval($producto_fiado["cantidad"]);
                    $cb = $producto_fiado["productos"];
                    $consultar_stock = "SELECT precio , nombre_producto FROM producto WHERE codigo_barra = :codigo_barra";
                    $stmtconsulta_s = $pdo->prepare($consultar_stock);
                    $stmtconsulta_s->bindParam(':codigo_barra', $cb, PDO::PARAM_STR);
                    $stmtconsulta_s->execute();
                    $resultado_productos = $stmtconsulta_s->fetchAll(PDO::FETCH_ASSOC);
                    $precioUnitario = floatval($resultado_productos[0]["precio"]);
                    $nombre_producto = $resultado_productos[0]["nombre_producto"];
                    $total_producto = $precioUnitario * $cantidad_productos;
                    array_push($array_persona, array("dni" => $dni_persona["dni"], "subtotal" => $total_producto, "cantidad" => $cantidad_productos, "nombre_producto" => $nombre_producto, "nombre" => $nombre_Y_apellido));

                }



                $array_nuevo[] = array($dni_persona["dni"] => $array_persona);
            }
            //consulto saldo sin  productos fiado
            $consulta_saldo = "SELECT nombre_y_apellido ,dni, SUM(saldo) AS total_saldo FROM saldos GROUP BY dni";
            $statement_saldo = $pdo->prepare($consulta_saldo);
            $statement_saldo->execute();
            $saldo_dni_todos = $statement_saldo->fetchAll(PDO::FETCH_ASSOC);


            $dni_con_solo_saldo = array();
            $dni_conSaldo = array();
            foreach ($array_nuevo as $item_array) {
                foreach ($item_array as $item_dni) {
                    foreach ($item_dni as $desglose) {
                        array_push($dni_conSaldo, $desglose["dni"]);
                    }
                }
            }
            foreach ($saldo_dni_todos as $dni_saldo) {
                array_push($dni_con_solo_saldo, $dni_saldo["dni"]);
            }

            $dnis_con_saldo_solamente = (array_diff($dni_con_solo_saldo, $dni_conSaldo));



            foreach ($array_nuevo as $persona) {
                $saldo_total = 0;
                $total = 0;
                $person = 0;// variable para el saldo
                foreach ($persona as $producto_items) {
                    if ($person === 0) {
                        $globalDNI = $producto_items[0]["dni"];
                        $consulta_saldo = "SELECT saldo FROM saldos WHERE dni=:dni";
                        $statement_saldo = $pdo->prepare($consulta_saldo);
                        $statement_saldo->bindParam(":dni", $globalDNI, PDO::PARAM_INT);
                        $statement_saldo->execute();
                        $saldo = $statement_saldo->fetchAll(PDO::FETCH_ASSOC);

                        if (count($saldo) !== 0) {
                            if (count($saldo) > 1) {
                                foreach ($saldo as $mas_de_uno) {
                                    $saldo_total = $saldo_total + $mas_de_uno["saldo"];
                                }
                            } else {
                                $saldo_total = $saldo[0]["saldo"];
                            }
                        } else {
                            $saldo_total = 0;
                        }

                        $person = 1;// para que no se repita la consulta
                    }
                    $cliente_dni;
                    //recorre cada producto que se compro
                    foreach ($producto_items as $item) {
                        $cliente_dni = $item["dni"];

                        $total += intval($item["subtotal"]);
                    }

                    if ($cliente_dni == $dni_nombre || $item["nombre"] == $nombre_cuenta_corriente) {


                        ?>
            <h1 class="text-fiado">Cuenta corriente</h1>
            <div class="detalle">
                <div class="container-title">
                    <h2>
                        <?php echo $item["nombre"]; ?>
                    </h2>
                    <form action="detalle-fiado.php" method="get" style="display: flex;
                        align-content: space-around;
                        flex-wrap: wrap; margin-left: 15px;">
                        <input type="hidden" name="dni-validate" value="<?php echo $cliente_dni; ?>">
                        <input type="hidden" name="name-validate" value="<?php echo $item["nombre"]; ?>">
                        <input type="submit" value="Detalle">
                    </form>
                </div>

                <form class="form-detalle" action="fiado-actualizar.php" method="post" class="card-fiado">
                    <p><strong>Deuda:</strong> $<?php echo $saldo_total + $total ?></p>
                    <input type="number" value="<?php echo $saldo_total + $total; ?>" name="pagar_total"
                        style="display: none;">
                    <input type="hidden" name="nombre_apellido" value="<?php echo $item["nombre"]; ?>">
                    <input type="number" value="<?php echo $item["dni"]; ?>" name="dni" style="display: none;">
                    <input type="text" value="<?php echo ($saldo_total + $total); ?>" name="cantidad_productos"
                        style="display: none;">
                    <p><strong>Entrega:</strong> $<input type="number" value="<?php echo ($saldo_total + $total); ?>"
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
            }

            // Replico las tarjetas para los saldos solamente
            
            foreach ($dnis_con_saldo_solamente as $solo_saldo) {
                foreach ($saldo_dni_todos as $datos) {
                    if ($datos["dni"] == $solo_saldo) {
                        ?>

            <h1 class="text-fiado">Cuenta corriente</h1>
            <div class="detalle">
                <div class="container-title">
                    <h2>
                        <?php echo $datos["nombre_y_apellido"]; ?>
                    </h2>
                    <form action="detalle-fiado.php" method="get" style="display: flex;
                        align-content: space-around;
                        flex-wrap: wrap; margin-left: 15px;">
                        <input type="hidden" name="dni-validate" value="<?php echo $datos["dni"]; ?>">
                        <input type="hidden" name="name-validate" value="<?php echo $datos["nombre_y_apellido"]; ?>">
                        <input type="submit" value="Detalle">
                    </form>
                </div>

                <form class="form-detalle" action="fiado-actualizar.php" method="post" class="card-fiado">
                    <p><strong>Deuda:</strong> $<?php echo $datos["total_saldo"]; ?></p>
                    <input type="number" value="<?php echo $datos["total_saldo"]; ?>" name="pagar_total"
                        style="display: none;">
                    <input type="hidden" name="nombre_apellido" value="<?php echo $datos["nombre_y_apellido"]; ?>">
                    <input type="number" value="<?php echo $datos["dni"]; ?>" name="dni" style="display: none;">
                    <input type="text" value="<?php echo $datos["total_saldo"]; ?>" name="cantidad_productos"
                        style="display: none;">
                    <p><strong>Entrega:</strong> $<input type="number" value="<?php echo $datos["total_saldo"]; ?>"
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
                    </di v>
                    </di v>
                </div>
                </fo oter>

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