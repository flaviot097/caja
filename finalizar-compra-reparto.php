<?php

//echo $_POST["nombre_y_apelido"];
//echo $_POST["DNI"];
//echo $_POST["pago"];
date_default_timezone_set('America/Buenos_Aires');
use Svg\Gradient\Stop;

$dni = $_POST["DNI"];
$nombre = $_POST["nombre_y_apelido"];
$fecha_date = date("Y-m-d");

require_once "conecion.php";
$dsn = "mysql:host=localhost:3307;dbname=code_bar;";
try {
    $pdo = new PDO($dsn, $usuario, $contrasena);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $er) {
    echo $er->getMessage();
}

function tablaRepartos($codigoproducto, $cantidad_producto, $conneccion)
{
    $dateTime = date("Y-m-d");
    $hora = date("H:i:s");
    var_dump($hora);

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



if ($_POST["pago"] == "entrega") {
    echo $_POST["total"];
    $total_con_entrega = $_POST["total"];
    $restar_total = $_POST["entregar_plata"];
    $tot = $total_con_entrega - $restar_total;
    $local_reparto = "local";
    $fecha = date("Y-m-d");

    //actualizar stock

    $productos_caja_entrega = json_decode($_COOKIE["productos_caja"], true);
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
            $update_stock = "UPDATE producto_reparto SET stock=:stock WHERE codigo_barra=:codigo_barra";
            $stmtupdate_s = $pdo->prepare($update_stock);
            $stmtupdate_s->bindParam(':codigo_barra', $cod, PDO::PARAM_STR);
            $stmtupdate_s->bindParam(':stock', $stock_nue, PDO::PARAM_STR);
            $stmtupdate_s->execute();
            tablaRepartos($cod, $cantidad_prod_s, $pdo);
        }
    }

    //subir a ventas
    $entrega_pago_mitad = "INSERT INTO ventas (reparto_o_local, total, fecha, tipo) VALUES (:reparto_o_local, :total, :fecha, :tipo)";
    $sql_entrega = "INSERT INTO fiado (dni,nombre_y_apellido,productos,saldo,cantidad,fecha) VALUES (:dni, :nombre_y_apellido, :productos ,:saldo,:cantidad,:fecha)";
    $stmt = $pdo->prepare($entrega_pago_mitad);
    $stmt->bindParam(':reparto_o_local', $local_reparto, PDO::PARAM_STR);
    $stmt->bindParam(':total', $restar_total, PDO::PARAM_INT);
    $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
    $stmt->bindParam(':tipo', $_POST["pago"], PDO::PARAM_STR);
    $stmt->execute();
    //subir a fiado
    $productos_no_actualizables = json_encode(array());
    $cantidada_cero = "0";
    $stmtlp = $pdo->prepare($sql_entrega);
    $stmtlp->bindParam(':dni', $dni, PDO::PARAM_STR);
    $stmtlp->bindParam(':nombre_y_apellido', $nombre, PDO::PARAM_STR);
    $stmtlp->bindParam(':productos', $productos_no_actualizables, PDO::PARAM_STR);
    $stmtlp->bindParam(':saldo', $tot, PDO::PARAM_INT);
    $stmtlp->bindParam(':cantidad', $cantidada_cero, PDO::PARAM_STR);
    $stmtlp->bindParam(':fecha', $fecha_date, PDO::PARAM_STR);
    if ($stmtlp->execute()) {
        //setcookie("productos_caja", "", time() - 3600, "/");

        setcookie("productos_caja", "", time() - 3600, "/");
        setcookie("cantidad_prod", "", time() - 3600, "/");
        setcookie("mensaje", "exito", time() + 10, '/');
        setcookie("imprimir", $imprimir, time() + 3600, "/");
        header("location: factura-crear.php");


    } else {
        setcookie("mensaje", "fallo", time() + 10, '/');
        header("location: caja-reparto.php");
    }
}
if ($_POST["pago"] === "efectivo") {

    if (isset($_COOKIE['productos_caja'])) {
        $productos_caja = json_decode($_COOKIE["productos_caja"], true);
        foreach ($productos_caja as $value) {
            if ($value['codigo_barra'] !== "codigo de barra") {
                $cod = $value['codigo_barra'];
                $cantidad_prod_s = $value["cantidad"];
                $consultar_stock = "SELECT stock FROM producto_reparto WHERE codigo_barra=:codigo_barra";
                $stmtconsulta_s = $pdo->prepare($consultar_stock);
                $stmtconsulta_s->bindParam(':codigo_barra', $cod, PDO::PARAM_STR);
                $stmtconsulta_s->execute();
                $existente1 = ($stmtconsulta_s->fetchAll())[0]["stock"];
                $stock_nue = floatval($existente1) - floatval($cantidad_prod_s);

                $update_stock = "UPDATE producto_reparto SET stock=:stock WHERE codigo_barra=:codigo_barra";
                $stmtupdate_s = $pdo->prepare($update_stock);
                $stmtupdate_s->bindParam(':codigo_barra', $cod, PDO::PARAM_STR);
                $stmtupdate_s->bindParam(':stock', $stock_nue, PDO::PARAM_STR);
                $stmtupdate_s->execute();
                tablaRepartos($cod, $cantidad_prod_s, $pdo);
            }
            if ($value['nombre_producto'] !== "Producto") {
                $local = "local";
                $fecha = date("Y-m-d");
                $sql = "INSERT INTO ventas (reparto_o_local, total, fecha, tipo) VALUES (:reparto_o_local, :total, :fecha, :tipo)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':reparto_o_local', $local, PDO::PARAM_STR);
                $stmt->bindParam(':total', $value['total'], PDO::PARAM_INT);
                $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
                $stmt->bindParam(':tipo', $_POST["pago"], PDO::PARAM_STR);

                if ($stmt->execute()) {
                    $list = [
                        'nombre_producto' => "Producto",
                        'precio' => 0,
                        'codigo_barra' => "codigo de barra",
                        'cantidad' => 1,
                        'total' => 0
                    ];
                    $imprimir = json_encode([$_POST["nombre_y_apelido"], "efectivo", $value['total']]);
                    $productos_caja[] = $list;
                    $productos_caja_json = json_encode($productos_caja);


                    setcookie("cantidad_prod", "", time() - 3600, "/");
                    setcookie("productos_caja", $productos_caja_json, time() + 3600, "/");
                    setcookie("productos_caja", "", time() - 3600, "/");
                    setcookie("mensaje", "exito", time() + 10, '/');
                    setcookie("imprimir", $imprimir, time() + 3600, "/");
                    header("location: factura-crear.php");


                } else {
                    setcookie("mensaje", "fallo", time() + 10, '/');
                    //header("location: caja-reparto.php");
                }
            }
        }
    }
}
if ($_POST["pago"] === "trans") {

    if (isset($_COOKIE['productos_caja'])) {
        $productos_caja = json_decode($_COOKIE["productos_caja"], true);
        foreach ($productos_caja as $value) {
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
                tablaRepartos($cod, $cantidad_prod_s, $pdo);
            }
            if ($value['nombre_producto'] !== "Producto") {
                $local = "local";
                $fecha = date("Y-m-d");
                $sql = "INSERT INTO ventas (reparto_o_local, total, fecha, tipo) VALUES (:reparto_o_local, :total, :fecha, :tipo)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':reparto_o_local', $local, PDO::PARAM_STR);
                $stmt->bindParam(':total', $value['total'], PDO::PARAM_INT);
                $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
                $stmt->bindParam(':tipo', $_POST["pago"], PDO::PARAM_STR);
                if ($stmt->execute()) {
                    $list = [
                        'nombre_producto' => "Producto",
                        'precio' => 0,
                        'codigo_barra' => "codigo de barra",
                        'cantidad' => 1,
                        'total' => 0
                    ];
                    $imprimir = json_encode([$_POST["nombre_y_apelido"], "Transferencia", $value['total']]);
                    $productos_caja[] = $list;
                    $productos_caja_json = json_encode($productos_caja);



                    setcookie("cantidad_prod", "", time() - 3600, "/");
                    setcookie("productos_caja", $productos_caja_json, time() + 3600, "/");
                    setcookie("mensaje", "exito", time() + 10, '/');
                    //setcookie("productos_caja", "", time() - 3600, "/");
                    setcookie("imprimir", $imprimir, time() + 3600, "/");

                    header("location: factura-crear.php");


                } else {
                    setcookie("mensaje", "fallo", time() + 10, '/');
                    header("location: caja-reparto");
                }
            }
        }
    }
}
if ($_POST["pago"] === "fiar") {
    if (isset($_COOKIE['productos_caja'])) {

        // Decodificar la cookie
        $productos_caja = json_decode($_COOKIE["productos_caja"], true);
        $fecha = date("Y-m-d");

        // Array para productos
        $productos_fiado_json = array();
        foreach ($productos_caja as $value) {
            if ($value['codigo_barra'] !== "codigo de barra") {
                $cod = $value['codigo_barra'];
                $cantidad_prod_s = floatval($value["cantidad"]);
                $consultar_stock = "SELECT stock FROM producto_reparto WHERE codigo_barra=:codigo_barra";
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
        $sql = "INSERT INTO fiado (dni,nombre_y_apellido,productos,saldo,cantidad,fecha) VALUES (:dni, :nombre_y_apellido, :productos ,:saldo,:cantidad,:fecha)";
        $stmt = $pdo->prepare($sql);
        $saldo = 0;



        //cantidad de productos
        $cant_fiad = json_decode($_COOKIE["cantidad_prod"], true);
        $cant_fiado_json_str = json_encode($cant_fiad);
        $imprimir = json_encode([$_POST["nombre_y_apelido"], "cuenta corriente", $value['total']]);

        // Enlazar parámetros
        $stmt->bindParam(':dni', $dni, PDO::PARAM_STR);
        $stmt->bindParam(':nombre_y_apellido', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':productos', $productos_fiado_json_str, PDO::PARAM_STR);
        $stmt->bindParam(':saldo', $saldo, PDO::PARAM_INT);
        $stmt->bindParam(':cantidad', $cant_fiado_json_str, PDO::PARAM_STR);
        $stmt->bindParam(':fecha', $fecha_date, PDO::PARAM_STR);
        if ($stmt->execute()) {
            //setcookie("productos_caja", "", time() - 3600, "/");

            setcookie("productos_caja", "", time() - 3600, "/");
            setcookie("cantidad_prod", "", time() - 3600, "/");
            setcookie("mensaje", "exito", time() + 10, '/');
            setcookie("imprimir", $imprimir, time() + 3600, "/");
            header("location: factura-crear.php");


        } else {
            setcookie("mensaje", "fallo", time() + 10, '/');
            header("location: caja.php");
        }
    }
}