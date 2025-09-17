<?php

session_start();

$url = $_POST["url"];

$local_reparto = "";
if ($url == "/santiago_pagina/caja.php") {
    $local_reparto = "local";
} else {
    $local_reparto = "reparto";
}

setcookie("url_location", $url, time() + 1200, "/");

$dni = $_POST["DNI"] ?? 1;
$nombre = $_POST["nombre_y_apelido"] ?? "NN";
date_default_timezone_set('America/Buenos_Aires');
$fecha_date = date("Y-m-d");

require_once "conecion.php";
$dsn = "mysql:host=localhost:3307;dbname=code_bar;";
try {
    $pdo = new PDO($dsn, $usuario, $contrasena);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $er) {
    echo $er->getMessage();
}

$hoy = date("Y-m-d H:i:s");

function tablaRepartos($codigoproducto, $cantidad_producto, $conneccion)
{
    $dateTime = date("Y-m-d");
    $hora = date("H:i:s");


    try {

        $sql = "INSERT INTO reparto_reporte(fecha, codigoBarra, cantidad, hora) VALUES ('$dateTime', '$codigoproducto', '$cantidad_producto', '$hora')";
        $stmt = $conneccion->prepare($sql);
        $stmt->execute();
    } catch (PDOException $e) {
        $sqlcreatetable = "CREATE TABLE reparto_reporte (
            fecha DATE, 
            codigoBarra VARCHAR(50), 
            cantidad FLOAT, 
            hora TIME
        )";
        $stmt = $conneccion->prepare($sqlcreatetable);
        $stmt->execute();


        $sql2 = "INSERT INTO reparto_reporte(fecha, codigoBarra, cantidad, hora) VALUES ('$dateTime', '$codigoproducto', '$cantidad_producto', '$hora')";
        $stmt2 = $conneccion->prepare($sql2);
        $stmt2->execute();
    }
}





if ($_POST["pago"] === "entrega") {

    $total_con_entrega = $_POST["total"];
    $restar_total = $_POST["entregar_plata"];
    $tot = intval(floatval($total_con_entrega) - floatval($restar_total));

    $fecha = date("Y-m-d H:i:s");//Y-m-d


    //actualizar stock
    $productos_caja_entrega = $_SESSION["productos_caja"];
    if ($local_reparto == "reparto") {
        foreach ($productos_caja_entrega as $value) {
            if ($value['codigo_barra'] !== "codigo de barra") {
                $cod = $value['codigo_barra'];
                $cantidad_prod_s = $value["cantidad"];
                $consultar_stock = "SELECT stock FROM producto_reparto WHERE codigo_barra=:codigo_barra";
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
                if ($local_reparto === "reparto") {
                    tablaRepartos($cod, $cantidad_prod_s, $pdo);
                }
            }
        }
    } else {

        foreach ($productos_caja_entrega as $value) {
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
                if ($local_reparto === "reparto") {
                    tablaRepartos($cod, $cantidad_prod_s, $pdo);
                }
            }
        }
    }

    //subir a ventas
    $entrega_pago_mitad = "INSERT INTO ventas (reparto_o_local, total, fecha, tipo, usuario) VALUES (:reparto_o_local, :total, :fecha, :tipo, :usuario)";
    $sql_entrega = "INSERT INTO fiado (dni,nombre_y_apellido,productos,saldo,cantidad,fecha) VALUES (:dni, :nombre_y_apellido, :productos ,:saldo,:cantidad,:fecha)";
    $stmt = $pdo->prepare($entrega_pago_mitad);
    $stmt->bindParam(':reparto_o_local', $local_reparto, PDO::PARAM_STR);
    $stmt->bindParam(':total', $restar_total, PDO::PARAM_INT);
    $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
    $stmt->bindParam(':usuario', $_COOKIE["usuario_caja"], PDO::PARAM_STR);
    $stmt->bindParam(':tipo', $_POST["pago"], PDO::PARAM_STR);
    $stmt->execute();

    $entrega_cliente = intval($restar_total);
    $paso_una_vez = 0;
    //subir a fiado
    foreach ($productos_caja_entrega as $value) {

        if ($value['codigo_barra'] !== "codigo de barra") {
            $cdb = $value["codigo_barra"];
            $precio2 = intval($value["precio"]);
            $cantidad_productos = floatval($value["cantidad"]);
            $subtotal = $precio2 * $cantidad_productos;

            $resto = $entrega_cliente - $subtotal;



            if ($resto >= 0) {
                $entrega_cliente = $entrega_cliente - $subtotal;
                //echo "- entrega mayo o igual a 0 -";
            } elseif (0 >= $resto && $paso_una_vez === 0) {
                $paso_una_vez = 1;
                $entrega_cliente = $entrega_cliente - $precio2;
                $sql_saldo = "INSERT INTO saldos (dni,saldo,fecha,nombre_y_apellido) VALUES (:dni, :saldo,:fecha,:nombre)";
                $resto_posiotivo = abs($resto);
                //subir a fiado 
                $fecha = date("Y-m-d");

                $stmt2 = $pdo->prepare($sql_saldo);
                $stmt2->bindParam(':dni', $dni, PDO::PARAM_STR);
                $stmt2->bindParam(':saldo', $resto_posiotivo, PDO::PARAM_STR);
                $stmt2->bindParam(':fecha', $hoy, PDO::PARAM_STR);
                $stmt2->bindParam(':nombre', $nombre, PDO::PARAM_STR);
                $stmt2->execute();


            } else {
                $sql_fiado = "INSERT INTO fiado (dni,nombre_y_apellido,productos,cantidad,fecha) VALUES (:dni, :nombre_y_apellido, :productos ,:cantidad,:fecha)";
                //subir a fiado
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
    //setcookie("productos_caja", "", time() - 3600, "/");
    //elimino cookies y genero mensaje

    setcookie("mensaje", "exito", time() + 10, '/');
    setcookie("imprimir", $imprimir, time() + 3600, "/");
    setcookie("entrega_si", $restar_total, time() + 3600, "/");
    header("location: factura-crear.php");

}



if ($_POST["pago"] === "efectivo") {

    if (isset($_SESSION["productos_caja"])) {
        $productos_caja = $_SESSION["productos_caja"];

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
                if ($local_reparto === "reparto") {
                    tablaRepartos($cod, $cantidad_prod_s, $pdo);
                }
            }

            $usuario = $_COOKIE["usuario_caja"];
            if ($value['nombre_producto'] !== "Producto") {
                $local = "local";
                $fecha = date("Y-m-d");
                $sql = "INSERT INTO ventas (reparto_o_local, total, fecha, tipo ,usuario) VALUES (:reparto_o_local, :total, :fecha, :tipo ,:usuario)";
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

                    //elimino cookies

                    setcookie("cantidad_prod", "", time() - 3600, "/");
                    setcookie("productos_caja", $productos_caja_json, time() + 3600, "/");
                    //setcookie("productos_caja", "", time() - 3600, "/");
                    setcookie("mensaje", "exito", time() + 10, '/');
                    setcookie("imprimir", $imprimir, time() + 3600, "/");



                } else {
                    setcookie("mensaje", "fallo", time() + 10, '/');
                    header("location: caja.php");
                }
            }
        }
        header("location: factura-crear.php");
    }
}



if ($_POST["pago"] === "trans") {

    if (isset($_SESSION["productos_caja"])) {
        $productos_caja = $_SESSION["productos_caja"];
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
                if ($local_reparto === "reparto") {
                    tablaRepartos($cod, $cantidad_prod_s, $pdo);
                }
            }
            if ($value['nombre_producto'] !== "Producto") {
                $local = "local";
                $fecha = date("Y-m-d");
                $vendedor = $_COOKIE["usuario_caja"];
                $sql = "INSERT INTO ventas (reparto_o_local, total, fecha, tipo,usuario) VALUES (:reparto_o_local, :total, :fecha, :tipo, :usuario)";
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

                    ///elimino cookies

                    setcookie("productos_caja", "", time() - 3600, "/");
                    setcookie("cantidad_prod", "", time() - 3600, "/");
                    setcookie("productos_caja", $productos_caja_json, time() + 3600, "/");
                    setcookie("mensaje", "exito", time() + 10, '/');
                    //setcookie("productos_caja", "", time() - 3600, "/");
                    setcookie("imprimir", $imprimir, time() + 3600, "/");

                    header("location: factura-crear.php");


                } else {
                    setcookie("mensaje", "fallo", time() + 10, '/');
                    header("location: caja.php");
                }
            }
        }
    }
}



if ($_POST["pago"] === "fiar") {
    if (isset($_SESSION["productos_caja"])) {

        // Decodificar la cookie
        $productos_caja = $_SESSION["productos_caja"];
        $fecha = date("Y-m-d");

        // Array para productos
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
                if ($local_reparto === "reparto") {
                    tablaRepartos($cod, $cantidad_prod_s, $pdo);
                }
            }
            if ($value['nombre_producto'] !== "Producto") {

                array_push($productos_fiado_json, $value['codigo_barra']);
            }
        }

        //consultar si hay un dni con productos 

        /*$sqldni = "SELECT productos FROM fiado WHERE dni= :dni";
        $stmtdni = $pdo->prepare($sqldni);
        $stmtdni->bindParam("dni", $dni, PDO::PARAM_STR);
        $stmtdni->execute();
        $result = $stmtdni->fetchAll(PDO::FETCH_ASSOC);
        if (count($result) !== 0) {

        }

        array_push($productos_fiado_json, "caca");
        var_dump($productos_fiado_json);*/

        $productos_fiado_json_str = json_encode($productos_fiado_json);
        $sql = "INSERT INTO fiado (dni,nombre_y_apellido,productos,cantidad,fecha) VALUES (:dni, :nombre_y_apellido, :productos,:cantidad,:fecha)";
        $stmt = $pdo->prepare($sql);
        $saldo = 0;
        $imprimir;
        $exito;

        foreach ($productos_caja as $productos_a_fiar) {
            //cantidad de productos
            if ($productos_a_fiar['codigo_barra'] !== "codigo de barra") {
                $cod = $productos_a_fiar['codigo_barra'];
                $cantidad_fiar = $productos_a_fiar["cantidad"];
                $vendedor = $_COOKIE["usuario_caja"];
                $imprimir = json_encode([$_POST["nombre_y_apelido"], "cuenta corriente", $value['total'], $vendedor]);
                var_dump($productos_a_fiar);
                echo "<br>";
                // Enlazar parámetros
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

        //redidijo si hay exito
        if ($exito === "exito") {
            //setcookie("productos_caja", "", time() - 3600, "/");
            //setcookie("cantidad_prod", "", time() - 3600, "/");
            setcookie("entrega_si", $restar_total, time() + 3600, "/");
            setcookie("mensaje", "exito", time() + 10, '/');
            setcookie("imprimir", $imprimir, time() + 3600, "/");
            header("location: factura-crear.php");


        } else {
            setcookie("mensaje", "fallo", time() + 10, '/');
            header("location: caja.php");
        }
    }
}