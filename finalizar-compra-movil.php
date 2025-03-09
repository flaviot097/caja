<?php
// Inicializa variables
$restar_total = 0;
$local_reparto = "";
$dni = $_POST["DNI"] ?? "";
$nombre = $_POST["nombre_y_apelido"] ?? "";
$url = $_POST["url"];


$p_total = json_decode($_POST["productos_total"], true);


$hoy = date("Y-m-d H:i:s");

$local_reparto = "reparto";


setcookie("url_location", $url, time() + 1200, "/");

// Configura la zona horaria
date_default_timezone_set('America/Buenos_Aires');
$fecha_date = date("Y-m-d");

// Conexión a la base de datos
require_once "conecion.php";
//$dsn = "mysql:host=localhost;dbname=c2750631_codeBar;";
$dsn = "mysql:host=localhost:3307;dbname=code_bar;";

try {
    $pdo = new PDO($dsn, $usuario, $contrasena);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $er) {
    echo $er->getMessage();
}


// $p_total_json = json_encode($_POST["productos_total"]);
// echo "<script>
//     localStorage.setItem('productos_caja', " . $p_total_json . ");
//     </script>";

//$productos_caja_entrega = json_decode($_COOKIE["productos_caja"], true);



try {
    $usuario_venta = $_COOKIE["usuario_caja"];
    $consulta = "INSERT INTO temporal(productos,fecha, usuario) values(:productos, :fecha, :usuario)";
    $stmtemporal = $pdo->prepare($consulta);
    $stmtemporal->bindParam(":productos", $_POST["productos_total"], PDO::PARAM_STR);
    $stmtemporal->bindParam(":fecha", $hoy, PDO::PARAM_STR);
    $stmtemporal->bindParam(":usuario", $usuario_venta, PDO::PARAM_STR);
    $stmtemporal->execute();

} catch (Exception $e) {
    echo $e->getMessage();
    exit;
}



if ($_POST["pago"] === "entrega") {

    $total_con_entrega = $_POST["total"];
    $restar_total = $_POST["entregar_plata"];
    $tot = $total_con_entrega - $restar_total;

    $fecha = date("Y-m-d");


    $productos_caja_entrega = $p_total;

    foreach ($productos_caja_entrega as $value) {
        if ($value['codigo_barra'] !== "codigo de barra") {
            $cod = $value['codigo_barra'];
            $cantidad_prod_s = $value["cantidad"];
            $consultar_stock = "SELECT stock FROM producto WHERE codigo_barra=:codigo_barra";
            $stmtconsulta_s = $pdo->prepare($consultar_stock);
            $stmtconsulta_s->bindParam(':codigo_barra', $cod, PDO::PARAM_STR);
            $stmtconsulta_s->execute();
            $existente1 = ($stmtconsulta_s->fetchAll())[0]["stock"];
            var_dump($existente1);

            $stock_nue = floatval($existente1) - floatval($cantidad_prod_s);
            $update_stock = "UPDATE producto SET stock=:stock WHERE codigo_barra=:codigo_barra";
            $stmtupdate_s = $pdo->prepare($update_stock);
            $stmtupdate_s->bindParam(':codigo_barra', $cod, PDO::PARAM_STR);
            $stmtupdate_s->bindParam(':stock', $stock_nue, PDO::PARAM_STR);
            $stmtupdate_s->execute();

            tablaRepartos($cod, $cantidad_prod_s, $pdo);

        }
    }


    $entrega_pago_mitad = "INSERT INTO ventas (reparto_o_local, total, fecha, tipo, usuario) VALUES (:reparto_o_local,
:total, :fecha, :tipo, :usuario)";
    $sql_entrega = "INSERT INTO fiado (dni,nombre_y_apellido,productos,saldo,cantidad,fecha) VALUES (:dni,
:nombre_y_apellido, :productos ,:saldo,:cantidad,:fecha)";
    $stmt = $pdo->prepare($entrega_pago_mitad);
    $stmt->bindParam(':reparto_o_local', $local_reparto, PDO::PARAM_STR);
    $stmt->bindParam(':total', $restar_total, PDO::PARAM_INT);
    $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
    $stmt->bindParam(':usuario', $_COOKIE["usuario_caja"], PDO::PARAM_STR);
    $stmt->bindParam(':tipo', $_POST["pago"], PDO::PARAM_STR);
    $stmt->execute();

    $entrega_cliente = intval($restar_total);
    $paso_una_vez = 0;

    foreach ($productos_caja_entrega as $value) {

        if ($value['codigo_barra'] !== "codigo de barra") {
            $cdb = $value["codigo_barra"];
            $precio2 = intval($value["precio"]);
            $cantidad_productos = floatval($value["cantidad"]);
            $subtotal = $precio2 * $cantidad_productos;

            $resto = $entrega_cliente - $subtotal;



            if ($resto >= 0) {
                $entrega_cliente = $entrega_cliente - $subtotal;

            } elseif (0 >= $resto && $paso_una_vez === 0) {
                $paso_una_vez = 1;
                $entrega_cliente = $entrega_cliente - $precio2;
                $sql_saldo = "INSERT INTO saldos (dni,saldo,fecha,nombre_y_apellido) VALUES (:dni, :saldo,:fecha,:nombre)";
                $resto_posiotivo = abs($resto);

                $fecha = date("Y-m-d");

                $stmt2 = $pdo->prepare($sql_saldo);
                $stmt2->bindParam(':dni', $dni, PDO::PARAM_STR);
                $stmt2->bindParam(':saldo', $resto_posiotivo, PDO::PARAM_STR);
                $stmt2->bindParam(':fecha', $hoy, PDO::PARAM_STR);
                $stmt2->bindParam(':nombre', $nombre, PDO::PARAM_STR);
                $stmt2->execute();


            } else {
                $sql_fiado = "INSERT INTO fiado (dni,nombre_y_apellido,productos,cantidad,fecha) VALUES (:dni, :nombre_y_apellido,
:productos ,:cantidad,:fecha)";

                $stmt2 = $pdo->prepare($sql_fiado);
                $stmt2->bindParam(':dni', $dni, PDO::PARAM_STR);
                $stmt2->bindParam(':nombre_y_apellido', $nombre, PDO::PARAM_STR);
                $stmt2->bindParam(':productos', $cdb, PDO::PARAM_STR);
                $stmt2->bindParam(':cantidad', $cantidad_productos, PDO::PARAM_STR);
                $stmt2->bindParam(':fecha', $hoy, PDO::PARAM_STR);
                $stmt2->execute();

            }

        }
    }


    $list = [
        'nombre_producto' => "Producto",
        'precio' => 0,
        'codigo_barra' => "codigo de barra",
        'cantidad' => 1,
        'total' => 0
    ];
    $vendedor = $_COOKIE["usuario_caja"];
    $imprimir = json_encode([$_POST["nombre_y_apelido"], "efectivo", $value['total'], $vendedor]);
    $productos_caja[] = $list;
    $productos_caja_json = json_encode($productos_caja);


    setcookie("mensaje", "exito", time() + 10, '/');
    setcookie("imprimir", $imprimir, time() + 3600, "/");
    setcookie("entrega_si", $restar_total, time() + 3600, "/");
    header("location: factura-movil-crear.php");

}


if ($_POST["pago"] === "efectivo") {

    if (isset($_COOKIE['productos_caja'])) {
        $productos_caja = $p_total;
        foreach ($productos_caja as $value) {
            if ($value['codigo_barra'] !== "codigo de barra") {
                $cod = $value['codigo_barra'];
                $cantidad_prod_s = $value["cantidad"];
                $consultar_stock = "SELECT stock FROM producto WHERE codigo_barra=:codigo_barra";
                $stmtconsulta_s = $pdo->prepare($consultar_stock);
                $stmtconsulta_s->bindParam(':codigo_barra', $cod, PDO::PARAM_STR);
                $stmtconsulta_s->execute();
                $existente1 = ($stmtconsulta_s->fetchAll())[0]["stock"];
                $stock_nue = floatval($existente1) - floatval($cantidad_prod_s);
                $update_stock = "UPDATE producto SET stock=:stock WHERE codigo_barra=:codigo_barra";
                $stmtupdate_s = $pdo->prepare($update_stock);
                $stmtupdate_s->bindParam(':codigo_barra', $cod, PDO::PARAM_STR);
                $stmtupdate_s->bindParam(':stock', $stock_nue, PDO::PARAM_STR);
                $stmtupdate_s->execute();

                tablaRepartos($cod, $cantidad_prod_s, $pdo);

            }

            $usuario = $_COOKIE["usuario_caja"];
            if ($value['nombre_producto'] !== "Producto") {
                $local = "local";
                $fecha = date("Y-m-d");
                $sql = "INSERT INTO ventas (reparto_o_local, total, fecha, tipo ,usuario) VALUES (:reparto_o_local, :total, :fecha,
:tipo ,:usuario)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':reparto_o_local', $local_reparto, PDO::PARAM_STR);
                $stmt->bindParam(':total', $value['total'], PDO::PARAM_INT);
                $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
                $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
                $stmt->bindParam(':tipo', $_POST["pago"], PDO::PARAM_STR);

                if ($stmt->execute()) {
                    $list = [
                        'nombre_producto' => "Producto",
                        'precio' => 0,
                        'codigo_barra' => "codigo de barra",
                        'cantidad' => 1,
                        'total' => 0
                    ];


                    $imprimir = json_encode([$_POST["nombre_y_apelido"], "efectivo", $value['total'], $usuario]);
                    $productos_caja[] = $list;
                    $productos_caja_json = json_encode($productos_caja);



                    setcookie("cantidad_prod", "", time() - 3600, "/");
                    setcookie("productos_caja", $productos_caja_json, time() + 3600, "/");

                    setcookie("mensaje", "exito", time() + 10, '/');
                    setcookie("imprimir", $imprimir, time() + 3600, "/");
                    header("location: factura-movil-crear.php");
                    exit;


                } else {
                    setcookie("mensaje", "fallo", time() + 10, '/');
                    header("location: caja.php");
                    exit;
                }
            }
        }
    }
}



if ($_POST["pago"] === "trans") {

    if (isset($_COOKIE['productos_caja'])) {
        $productos_caja = $p_total;
        foreach ($productos_caja as $value) {
            if ($value['codigo_barra'] !== "codigo de barra") {
                $cod = $value['codigo_barra'];
                $cantidad_prod_s = $value["cantidad"];
                $consultar_stock = "SELECT stock FROM producto WHERE codigo_barra=:codigo_barra";
                $stmtconsulta_s = $pdo->prepare($consultar_stock);
                $stmtconsulta_s->bindParam(':codigo_barra', $cod, PDO::PARAM_STR);
                $stmtconsulta_s->execute();
                $existente1 = ($stmtconsulta_s->fetchAll())[0]["stock"];
                $stock_nue = floatval($existente1) - floatval($cantidad_prod_s);
                $update_stock = "UPDATE producto SET stock=:stock WHERE codigo_barra=:codigo_barra";
                $stmtupdate_s = $pdo->prepare($update_stock);
                $stmtupdate_s->bindParam(':codigo_barra', $cod, PDO::PARAM_STR);
                $stmtupdate_s->bindParam(':stock', $stock_nue, PDO::PARAM_STR);
                $stmtupdate_s->execute();

                tablaRepartos($cod, $cantidad_prod_s, $pdo);

            }
            if ($value['nombre_producto'] !== "Producto") {
                $local = "local";
                $fecha = date("Y-m-d");
                $vendedor = $_COOKIE["usuario_caja"];
                $sql = "INSERT INTO ventas (reparto_o_local, total, fecha, tipo,usuario) VALUES (:reparto_o_local, :total, :fecha,
:tipo, :usuario)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':reparto_o_local', $local_reparto, PDO::PARAM_STR);
                $stmt->bindParam(':total', $value['total'], PDO::PARAM_INT);
                $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
                $stmt->bindParam(':tipo', $_POST["pago"], PDO::PARAM_STR);
                $stmt->bindParam(':usuario', $vendedor, PDO::PARAM_STR);
                if ($stmt->execute()) {
                    $list = [
                        'nombre_producto' => "Producto",
                        'precio' => 0,
                        'codigo_barra' => "codigo de barra",
                        'cantidad' => 1,
                        'total' => 0
                    ];

                    $vendedor = $_COOKIE["usuario_caja"];

                    $imprimir = json_encode([$_POST["nombre_y_apelido"], "Transferencia", $value['total'], $vendedor]);
                    $productos_caja[] = $list;
                    $productos_caja_json = json_encode($productos_caja);




                    setcookie("mensaje", "exito", time() + 10, '/');

                    setcookie("imprimir", $imprimir, time() + 3600, "/");

                    header("location: factura-movil-crear.php");
                    exit;


                } else {
                    setcookie("mensaje", "fallo", time() + 10, '/');
                    header("location: caja.php");
                    exit;
                }
            }
        }
    }
}



if ($_POST["pago"] === "fiar") {
    if (isset($_COOKIE['productos_caja'])) {


        $productos_caja = $p_total;
        $fecha = date("Y-m-d");


        $productos_fiado_json = array();
        foreach ($productos_caja as $value) {
            if ($value['codigo_barra'] !== "codigo de barra") {
                $cod = $value['codigo_barra'];
                $cantidad_prod_s = floatval($value["cantidad"]);
                $consultar_stock = "SELECT stock FROM producto WHERE codigo_barra=:codigo_barra";
                $stmtconsulta_s = $pdo->prepare($consultar_stock);
                $stmtconsulta_s->bindParam(':codigo_barra', $cod, PDO::PARAM_STR);
                $stmtconsulta_s->execute();
                $existente1 = ($stmtconsulta_s->fetchAll())[0]["stock"];
                $stock_nue = floatval($existente1) - $cantidad_prod_s;
                $update_stock = "UPDATE producto SET stock=:stock WHERE codigo_barra=:codigo_barra";
                $stmtupdate_s = $pdo->prepare($update_stock);
                $stmtupdate_s->bindParam(':codigo_barra', $cod, PDO::PARAM_STR);
                $stmtupdate_s->bindParam(':stock', $stock_nue, PDO::PARAM_STR);
                $stmtupdate_s->execute();

                tablaRepartos($cod, $cantidad_prod_s, $pdo);

            }
            if ($value['nombre_producto'] !== "Producto") {

                array_push($productos_fiado_json, $value['codigo_barra']);
            }
        }

        $productos_fiado_json_str = json_encode($productos_fiado_json);
        $sql = "INSERT INTO fiado (dni,nombre_y_apellido,productos,cantidad,fecha) VALUES (:dni, :nombre_y_apellido,
:productos,:cantidad,:fecha)";
        $stmt = $pdo->prepare($sql);
        $saldo = 0;
        $imprimir;
        $exito;

        foreach ($productos_caja as $productos_a_fiar) {

            if ($productos_a_fiar['codigo_barra'] !== "codigo de barra") {
                $cod = $productos_a_fiar['codigo_barra'];
                $cantidad_fiar = $productos_a_fiar["cantidad"];
                $vendedor = $_COOKIE["usuario_caja"];
                $imprimir = json_encode([$_POST["nombre_y_apelido"], "cuenta corriente", $value['total'], $vendedor]);


                $stmt->bindParam(':dni', $dni, PDO::PARAM_STR);
                $stmt->bindParam(':nombre_y_apellido', $nombre, PDO::PARAM_STR);
                $stmt->bindParam(':productos', $cod, PDO::PARAM_STR);
                $stmt->bindParam(':cantidad', $cantidad_fiar, PDO::PARAM_STR);
                $stmt->bindParam(':fecha', $hoy, PDO::PARAM_STR);
                if ($stmt->execute()) {
                    $exito = "exito";
                } else {
                    $exito = "error";
                }
            }
        }


        if ($exito === "exito") {


            setcookie("mensaje", "exito", time() + 10, '/');
            setcookie("imprimir", $imprimir, time() + 3600, "/");
            header("location: factura-movil-crear.php");
            exit;


        } else {
            setcookie("mensaje", "fallo", time() + 10, '/');
            header("location: caja.php");
            exit;
        }
    }
}

function tablaRepartos($codigoproducto, $cantidad_producto, $conneccion)
{
    $dateTime = date("Y-m-d");
    $hora = date("H:i:s");


    $sql = "INSERT INTO reparto_reporte(fecha, codigoBarra, cantidad, hora) VALUES (:fecha, :codigoBarra, :cantidad ,
:hora)";
    $stmt = $conneccion->prepare($sql);
    $stmt->bindParam(':fecha', $dateTime, PDO::PARAM_STR);
    $stmt->bindParam(':codigoBarra', $codigoproducto, PDO::PARAM_STR);
    $stmt->bindParam(':cantidad', $cantidad_producto, PDO::PARAM_STR);
    $stmt->bindParam(':hora', $hora, PDO::PARAM_STR);
    $stmt->execute();

}

?>