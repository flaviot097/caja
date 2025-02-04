<?php
require_once "conecion.php";
$dsn = "mysql:host=localhost:3307;dbname=code_bar;";
try {
    $pdo = new PDO($dsn, $usuario, $contrasena);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage();
}
/*echo $_POST["dni"];
echo $_POST["pagar_total"];
echo $_POST["pagar"];
echo $_POST["entrega"];*/
$metodo = $_POST["pagar"];

$dni = $_POST["dni"];
$entrega_total = $_POST["entrega"];
date_default_timezone_set('America/Buenos_Aires');

$fecha_date = date("Y-m-d");
$nombre_apellido = $_POST["nombre_apellido"];
$total_dueda = $_POST["cantidad_productos"];

if ($metodo == "liquidar_total") {
    $consultadelete = "DELETE FROM fiado WHERE dni=:dni";
    $stmtC = $pdo->prepare($consultadelete);
    $stmtC->bindParam(":dni", $dni, PDO::PARAM_INT);
    $stmtC->execute();
    $consultadeleteSaldo = "DELETE FROM saldos WHERE dni=:dni";
    $stmts = $pdo->prepare($consultadeleteSaldo);
    $stmts->bindParam(":dni", $dni, PDO::PARAM_INT);
    $stmts->execute();

    echo "se elimina el producto";
    header("location:cuenta-corriente.php");
} else {

    if ($total_dueda !== $entrega_total) {
        $select = "SELECT * FROM fiado WHERE dni=:dni";
        $statement1 = $pdo->prepare($select);
        $statement1->bindParam(":dni", $dni, PDO::PARAM_INT);
        $statement1->execute();
        $todosFiados1 = $statement1->fetchAll(PDO::FETCH_ASSOC);

        $solamente_saldo = "";

        if (count($todosFiados1) == 0) {
            $solamente_saldo = "solamente saldo";
        }

        $saldo_total = 0;
        $entrega_cliente = intval($_POST["entrega"]);
        $paso_una_vez = 0;

        $consulta_saldo = "SELECT nombre_y_apellido, fecha, dni, SUM(saldo) AS saldo FROM saldos WHERE dni = :dni GROUP BY dni";
        $statement_saldo = $pdo->prepare($consulta_saldo);
        $statement_saldo->bindParam(":dni", $dni, PDO::PARAM_INT);
        $statement_saldo->execute();
        $saldo_consul = $statement_saldo->fetchAll(PDO::FETCH_ASSOC);

        $hay_saldo = "";
        $dni_saldo_elimintate;
        $nombre_saldo_elimintate = "";
        $fecha_saldo_elimintate = "";
        $saldo_total;

        if (count($saldo_consul) !== 0) {
            $hay_saldo = "hay";
            $dni_saldo_elimintate = $saldo_consul[0]["dni"];
            $saldo_total = $saldo_consul[0]["saldo"];
            $nombre_saldo_elimintate = $saldo_consul[0]["nombre_y_apellido"];
            $fecha_saldo_elimintate = $saldo_consul[0]["fecha"];
        }

        $entrega_cliente = $entrega_cliente - $saldo_total;



        if ($entrega_cliente > 0) {
            $consulta_saldo_delete = "DELETE FROM saldos WHERE dni=:dni";
            $statement_saldo = $pdo->prepare($consulta_saldo_delete);
            $statement_saldo->bindParam(":dni", $dni_saldo_elimintate, PDO::PARAM_INT);
            $statement_saldo->execute();

            //subir a ventas
            $local_o_reparto = "local";
            $cuenta_corriente_pago = "cuenta corriente";

            $entrega_pago_mitad = "INSERT INTO ventas (reparto_o_local, total, fecha, tipo, usuario) VALUES (:reparto_o_local, :total, :fecha, :tipo, :usuario)";
            $stmt = $pdo->prepare($entrega_pago_mitad);
            $stmt->bindParam(':reparto_o_local', $local_o_reparto, PDO::PARAM_STR);
            $stmt->bindParam(':total', $saldo_total, PDO::PARAM_INT);
            $stmt->bindParam(':fecha', $fecha_date, PDO::PARAM_STR);
            $stmt->bindParam(':usuario', $_COOKIE["usuario_caja"], PDO::PARAM_STR);
            $stmt->bindParam(':tipo', $cuenta_corriente_pago, PDO::PARAM_STR);
            $stmt->execute();
            ///

        } elseif ($hay_saldo == "hay") {
            var_dump($entrega_cliente);
            $consulta_saldo_delete = "DELETE FROM saldos WHERE dni=:dni";
            $statement_saldo = $pdo->prepare($consulta_saldo_delete);
            $statement_saldo->bindParam(":dni", $dni_saldo_elimintate, PDO::PARAM_INT);
            $statement_saldo->execute();

            $saldo_a_guardar = abs($entrega_cliente);
            $consulta_saldo_create = "INSERT INTO saldos (fecha, dni, saldo, nombre_y_apellido) VALUES (:fecha, :dni, :saldo, :nombre_y_apellido)";
            $statement_saldo = $pdo->prepare($consulta_saldo_create);
            $statement_saldo->bindParam(":fecha", $fecha_saldo_elimintate, PDO::PARAM_INT);
            $statement_saldo->bindParam(":dni", $dni_saldo_elimintate, PDO::PARAM_INT);
            $statement_saldo->bindParam(":saldo", $saldo_a_guardar, PDO::PARAM_INT);
            $statement_saldo->bindParam(":nombre_y_apellido", $nombre_saldo_elimintate, PDO::PARAM_INT);
            $statement_saldo->execute();
        }

        if ($solamente_saldo !== "solamente saldo") {
            $vuelta_unica = 0;

            foreach ($todosFiados1 as $product) {
                $codigo_barra = $product["productos"];
                $fecha_fiado = $product["fecha"];
                $dni_saldo_elimintate = $product["dni"];
                $cantidad_productos = floatval($product["cantidad"]);
                $consultar_stock = "SELECT precio , nombre_producto FROM producto WHERE codigo_barra = :codigo_barra";
                $stmtconsulta_s = $pdo->prepare($consultar_stock);
                $stmtconsulta_s->bindParam(':codigo_barra', $codigo_barra, PDO::PARAM_STR);
                $stmtconsulta_s->execute();
                $resultado_productos = $stmtconsulta_s->fetchAll(PDO::FETCH_ASSOC);
                $precio2 = floatval($resultado_productos[0]["precio"]);
                $nombre_producto = $resultado_productos[0]["nombre_producto"];
                $subtotal = $precio2 * $cantidad_productos;

                $resto = $entrega_cliente - $subtotal;

                if ($resto > 0) {
                    $entrega_cliente = $entrega_cliente - $subtotal;
                    $sql_delete_fiado = "DELETE FROM fiado WHERE dni=:dni AND fecha=:fecha AND productos=:codigo_barra";
                    $stmtupdate_f = $pdo->prepare($sql_delete_fiado);
                    $stmtupdate_f->bindParam(':dni', $dni_saldo_elimintate, PDO::PARAM_STR);
                    $stmtupdate_f->bindParam(':codigo_barra', $codigo_barra, PDO::PARAM_STR);
                    $stmtupdate_f->bindParam(':fecha', $fecha_fiado, PDO::PARAM_STR);
                    $stmtupdate_f->execute();
                    echo "se pado el total :" . $codigo_barra;
                    echo "<br>";


                }
                if (0 > $resto) {
                    $vuelta_unica = 1;
                    $puedo_pagar = $entrega_cliente / $precio2;
                    $cantidad_a_guardar = $cantidad_productos - $puedo_pagar;
                    $entrega_cliente = 0;//esto es para que no se repita el ciclo
                    echo "cantidad a guardar :" . $cantidad_a_guardar;
                    echo " ## codigo :" . $codigo_barra;
                    echo "<br>";

                    $sql_actualizar_fiado = "UPDATE fiado SET cantidad = :cantidad WHERE productos = :codigo_barra AND fecha = :fecha";
                    $stmtupdate_f = $pdo->prepare($sql_actualizar_fiado);
                    $stmtupdate_f->bindParam(':cantidad', $cantidad_a_guardar, PDO::PARAM_STR);
                    $stmtupdate_f->bindParam(':codigo_barra', $codigo_barra, PDO::PARAM_STR);
                    $stmtupdate_f->bindParam(':fecha', $fecha_fiado, PDO::PARAM_STR);
                    $stmtupdate_f->execute();

                    //     echo "- queda saldo- ";
                    // } else {
                    //     $sql_fiado = "INSERT INTO fiado (dni,nombre_y_apellido,productos,cantidad,fecha) VALUES (:dni, :nombre_y_apellido, :productos ,:cantidad,:fecha)";
                    //     //subir a fiado
                    //     $stmt2 = $pdo->prepare($sql_fiado);
                    //     $stmt2->bindParam(':dni', $dni, PDO::PARAM_STR);
                    //     $stmt2->bindParam(':nombre_y_apellido', $nombre, PDO::PARAM_STR);
                    //     $stmt2->bindParam(':productos', $cdb, PDO::PARAM_STR);
                    //     $stmt2->bindParam(':cantidad', $cantidad_productos, PDO::PARAM_STR);
                    //     $stmt2->bindParam(':fecha', $fecha, PDO::PARAM_STR);
                    //     $stmt2->execute();
                    //     echo "se debe fiar";
                }

            }

        }

    }

}


/*$resta = intval($_POST["pagar_total"]) - intval($_POST["entrega"]);
$prod_total = intval($_POST["pagar_total"]);
$entrega_P = intval($_POST["entrega"]);
$cantidadJson = $_POST['cantidad_productos'];
$cantidad = json_decode($cantidadJson, true);
$list_cantidad = [];
$lugar = 0;
for ($i = 0; $i < count($cantidad); $i++) {
    foreach ($cantidad as $key => $value) {
        echo $key;
        echo "#";
        echo $value;
        echo "**";

    }
    echo "--------- _________";
    if (intval($cantidad[$lugar])) {
        array_push($list_cantidad, $cantidad[$lugar]);
    }
    $lugar++;
}
$new_list_cant_prod = [];
$precio_filtar = [];


foreach (json_decode($_COOKIE["fiados_todos"]) as $persona) {
    if ($persona->dni == $_POST["dni"]) {
        $productos_lista = $persona->productos;
        $productos_lista = json_decode($productos_lista);

        $vuelta_cantidad_barra = 0;

        foreach ($productos_lista as $code_barra) {
            $queryP = "SELECT precio FROM producto WHERE codigo_barra = :codigo_barra";
            $stmtP = $pdo->prepare($queryP);
            $stmtP->bindParam(':codigo_barra', $code_barra, PDO::PARAM_STR);
            $stmtP->execute();
            $f = $stmtP->fetch(PDO::FETCH_ASSOC);
            $precioUnitario = $f["precio"];


            if ($entrega_P < $precioUnitario) {
                //echo $entrega_P;
                //echo "--#";
            } else {
                for ($i = 0; $i < count($list_cantidad[$vuelta_cantidad_barra]); $i++) {
                    $new_list_cant_prod[] = $code_barra;
                    $precio_filtar[] = $f["precio"];
                }
                $vuelta_cantidad_barra++;

            }
        }
    }
}

//echo $entrega_P;
$lista_a_subir = $new_list_cant_prod;

foreach ($new_list_cant_prod as $key => $value) {

    if ($entrega_P > $precio_filtar[$key]) {
        $entrega_P = $entrega_P - $precio_filtar[$key];
        unset($lista_a_subir[$key]);
    }

}*/
/*var_dump($lista_a_subir);
echo "....";

if ($entrega_P > 0) {
    $saldo_contra = abs($entrega_P);
    $dni = $_POST["dni"];
    $sql = "UPDATE `fiado` SET saldo = :saldo, productos = :productos ,cantidad = :cantidad WHERE dni = :dni";
    $stmt = $pdo->prepare($sql);
    $nuevo_A = json_encode($lista_a_subir);
    $stmt->bindParam(':dni', $dni, PDO::PARAM_INT);
    $stmt->bindParam(':saldo', $saldo_contra, PDO::PARAM_INT);
    $stmt->bindParam(':productos', $nuevo_A, PDO::PARAM_STR);
    $stmt->bindParam(':cantidad', $cantidada_db, PDO::PARAM_STR);
    $stmt->execute();
    header("location: cuenta-corriente.php");
} elseif ($entrega_P === 0) {
    $dni = $_POST["dni"];
    $NuevoPD = json_encode($new_list_cant_prod);
    $sql = "DELETE FROM fiado WHERE dni = :dni AND productos = :productos";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':dni', $dni, PDO::PARAM_INT);
    $stmt->bindParam(':productos', $NuevoPD, PDO::PARAM_STR);
    $stmt->execute();
    header("location: cuenta-corriente.php");
} else {
    echo "no se pudo completar compra";
}*/