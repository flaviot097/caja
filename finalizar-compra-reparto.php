<?php

//echo $_POST["nombre_y_apelido"];
//echo $_POST["DNI"];
//echo $_POST["pago"];
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
                $stock_nue = intval($existente1) - intval($cantidad_prod_s);
                $update_stock = "UPDATE producto SET stock=:stock WHERE codigo_barra=:codigo_barra";
                $stmtupdate_s = $pdo->prepare($update_stock);
                $stmtupdate_s->bindParam(':codigo_barra', $cod, PDO::PARAM_STR);
                $stmtupdate_s->bindParam(':stock', $stock_nue, PDO::PARAM_INT);
                $stmtupdate_s->execute();
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
                    setcookie("productos_caja", $productos_caja_json, time() + 3600, "/");
                    //setcookie("productos_caja", "", time() - 3600, "/");
                    setcookie("mensaje", "exito", time() + 10, '/');
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
                $stock_nue = intval($existente1) - intval($cantidad_prod_s);
                $update_stock = "UPDATE producto SET stock=:stock WHERE codigo_barra=:codigo_barra";
                $stmtupdate_s = $pdo->prepare($update_stock);
                $stmtupdate_s->bindParam(':codigo_barra', $cod, PDO::PARAM_STR);
                $stmtupdate_s->bindParam(':stock', $stock_nue, PDO::PARAM_INT);
                $stmtupdate_s->execute();
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
    if (isset($_COOKIE['productos_caja'])) {

        // Decodificar la cookie
        $productos_caja = json_decode($_COOKIE["productos_caja"], true);
        $fecha = date("Y-m-d");

        // Array para productos
        $productos_fiado_json = array();
        foreach ($productos_caja as $value) {
            if ($value['codigo_barra'] !== "codigo de barra") {
                $cod = $value['codigo_barra'];
                $cantidad_prod_s = $value["cantidad"];
                $consultar_stock = "SELECT stock FROM producto_reparto WHERE codigo_barra=:codigo_barra";
                $stmtconsulta_s = $pdo->prepare($consultar_stock);
                $stmtconsulta_s->bindParam(':codigo_barra', $cod, PDO::PARAM_STR);
                $stmtconsulta_s->execute();
                $existente1 = ($stmtconsulta_s->fetchAll())[0]["stock"];
                $stock_nue = intval($existente1) - intval($cantidad_prod_s);
                $update_stock = "UPDATE producto SET stock=:stock WHERE codigo_barra=:codigo_barra";
                $stmtupdate_s = $pdo->prepare($update_stock);
                $stmtupdate_s->bindParam(':codigo_barra', $cod, PDO::PARAM_STR);
                $stmtupdate_s->bindParam(':stock', $stock_nue, PDO::PARAM_INT);
                $stmtupdate_s->execute();
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
            setcookie("mensaje", "exito", time() + 10, '/');
            setcookie("imprimir", $imprimir, time() + 3600, "/");
            header("location: factura-crear.php");


        } else {
            setcookie("mensaje", "fallo", time() + 10, '/');
            header("location: caja.php");
        }
    }
}