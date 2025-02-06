<?php
require_once "conecion.php";
$dsn = "mysql:host=localhost:3307;dbname=code_bar;";
try {
    $pdo = new PDO($dsn, $usuario, $contrasena);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage();
}

$metodo = $_POST["pagar"];

$dni = $_POST["dni"];
$entrega_total = $_POST["entrega"];
date_default_timezone_set('America/Buenos_Aires');

$fecha_date = date("Y-m-d H:i:s");
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

        $saldo_total = 0;
        $entrega_cliente = intval($_POST["entrega"]);
        $paso_una_vez = 0;

        $consulta_saldo = "SELECT nombre_y_apellido, fecha, dni, SUM(saldo) AS saldo FROM saldos WHERE dni = :dni GROUP BY dni";
        $statement_saldo = $pdo->prepare($consulta_saldo);
        $statement_saldo->bindParam(":dni", $dni, PDO::PARAM_INT);
        $statement_saldo->execute();
        $saldo_consul = $statement_saldo->fetchAll(PDO::FETCH_ASSOC);

        $hay_saldo = "no hay";
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


        if ($entrega_cliente == 0) {
            $consulta_saldo_delete = "DELETE FROM saldos WHERE dni=:dni";
            $statement_saldo = $pdo->prepare($consulta_saldo_delete);
            $statement_saldo->bindParam(":dni", $dni_saldo_elimintate, PDO::PARAM_INT);
            $statement_saldo->execute();

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
        } elseif (0 > $entrega_cliente) {


            $consulta_saldo_delete = "DELETE FROM saldos WHERE dni=:dni";
            $statement_saldo = $pdo->prepare($consulta_saldo_delete);
            $statement_saldo->bindParam(":dni", $dni_saldo_elimintate, PDO::PARAM_INT);
            $statement_saldo->execute();

            $saldo_a_guardar = abs($entrega_cliente);
            $consulta_saldo_create = "INSERT INTO saldos (fecha, dni, saldo, nombre_y_apellido) VALUES (:fecha, :dni, :saldo, :nombre_y_apellido)";
            $statement_saldo = $pdo->prepare($consulta_saldo_create);
            $statement_saldo->bindParam(":fecha", $fecha_date, PDO::PARAM_INT);
            $statement_saldo->bindParam(":dni", $dni_saldo_elimintate, PDO::PARAM_INT);
            $statement_saldo->bindParam(":saldo", $saldo_a_guardar, PDO::PARAM_INT);
            $statement_saldo->bindParam(":nombre_y_apellido", $nombre_saldo_elimintate, PDO::PARAM_INT);
            $statement_saldo->execute();

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
        } elseif ($entrega_cliente > 0) {
            $consulta_saldo_delete = "DELETE FROM saldos WHERE dni=:dni";
            $statement_saldo = $pdo->prepare($consulta_saldo_delete);
            $statement_saldo->bindParam(":dni", $dni_saldo_elimintate, PDO::PARAM_INT);
            $statement_saldo->execute();

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
        }


        if ($entrega_cliente > 0) {

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


                }

            }

            exit;

        }

    }

}


header("location: cuenta-corriente.php");
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

// if ($entrega_cliente > 0) {
//     $consulta_saldo_delete = "DELETE FROM saldos WHERE dni=:dni";
//     $statement_saldo = $pdo->prepare($consulta_saldo_delete);
//     $statement_saldo->bindParam(":dni", $dni_saldo_elimintate, PDO::PARAM_INT);
//     $statement_saldo->execute();

//     //subir a ventas
//     $local_o_reparto = "local";
//     $cuenta_corriente_pago = "cuenta corriente";

//     $entrega_pago_mitad = "INSERT INTO ventas (reparto_o_local, total, fecha, tipo, usuario) VALUES (:reparto_o_local, :total, :fecha, :tipo, :usuario)";
//     $stmt = $pdo->prepare($entrega_pago_mitad);
//     $stmt->bindParam(':reparto_o_local', $local_o_reparto, PDO::PARAM_STR);
//     $stmt->bindParam(':total', $saldo_total, PDO::PARAM_INT);
//     $stmt->bindParam(':fecha', $fecha_date, PDO::PARAM_STR);
//     $stmt->bindParam(':usuario', $_COOKIE["usuario_caja"], PDO::PARAM_STR);
//     $stmt->bindParam(':tipo', $cuenta_corriente_pago, PDO::PARAM_STR);
//     $stmt->execute();
//     ///

// } elseif ($hay_saldo == "hay") {
//     var_dump($entrega_cliente);
//     $consulta_saldo_delete = "DELETE FROM saldos WHERE dni=:dni";
//     $statement_saldo = $pdo->prepare($consulta_saldo_delete);
//     $statement_saldo->bindParam(":dni", $dni_saldo_elimintate, PDO::PARAM_INT);
//     $statement_saldo->execute();

//     $saldo_a_guardar = abs($entrega_cliente);
//     $consulta_saldo_create = "INSERT INTO saldos (fecha, dni, saldo, nombre_y_apellido) VALUES (:fecha, :dni, :saldo, :nombre_y_apellido)";
//     $statement_saldo = $pdo->prepare($consulta_saldo_create);
//     $statement_saldo->bindParam(":fecha", $fecha_saldo_elimintate, PDO::PARAM_INT);
//     $statement_saldo->bindParam(":dni", $dni_saldo_elimintate, PDO::PARAM_INT);
//     $statement_saldo->bindParam(":saldo", $saldo_a_guardar, PDO::PARAM_INT);
//     $statement_saldo->bindParam(":nombre_y_apellido", $nombre_saldo_elimintate, PDO::PARAM_INT);
//     $statement_saldo->execute();
// }