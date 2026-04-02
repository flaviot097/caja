<?php
require_once "validacion-usuario.php";

require_once "conecion.php";

if ($_POST['nombre_tipo'] !== "" && $_POST["valor"] !== "") {
    $dsn = 'mysql:host=localhost:3307;dbname=code_bar;';
    try {
        $pdo = new PDO($dsn, $usuario, $contrasena);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo 'error al conectarse: ' . $e->getMessage();
        exit;
    }

    $ultimo_id = 0;
    try {
        $consulta = "SELECT id FROM tipo_interes ORDER BY id DESC LIMIT 1";
        $stmt = $pdo->prepare($consulta);
        $stmt->execute();
        $ultimo_id = $stmt->fetchAll(PDO::FETCH_ASSOC)[0]["id"] + 1;
    } catch (\Throwable $th) {
        $ultimo_id = 0;
    }
    $consulta = "INSERT INTO tipo_interes (nombre_tipo, habilitado, cod_tipo_interes, fecha_hasta, monto_desde, monto_hasta, valor, es_mensual) VALUES(:nombre_tipo , :habilitado, :cod_tipo_interes, :fecha_hasta, :monto_desde, :monto_hasta, :valor, :es_mensual)";
    $stmt = $pdo->prepare($consulta);
    $nombre = $_POST['nombre_tipo'];
    $fecha = $_POST["fecha_hasta"];
    $montoDesde = 0;
    $montoHasta = 9999999999999;
    $valor = $_POST["valor"];
    $es_mesual = true;
    if ($_POST["mes"] == "Diario") {
        $es_mesual = false;
    }
    $hab = true;
    if ($_POST["habilitado"] == "Deshabilitado") {
        $hab = false;
    }
    $stmt->bindParam(':nombre_tipo', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':habilitado', $hab, PDO::PARAM_BOOL);
    $stmt->bindParam(':cod_tipo_interes', $ultimo_id, PDO::PARAM_INT);
    $stmt->bindParam(':fecha_hasta', $fecha, PDO::PARAM_STR);
    $stmt->bindParam(':monto_desde', $montoDesde, PDO::PARAM_INT);
    $stmt->bindParam(':monto_hasta', $montoHasta, PDO::PARAM_INT);
    $stmt->bindParam(':valor', $valor, PDO::PARAM_INT);
    $stmt->bindParam(':es_mensual', $es_mesual, PDO::PARAM_BOOL);
    if ($stmt->execute()) {
        header("location: tipo-interes.php");
        exit;
    } else {
        header("location: error_500.html");
        exit;
    }
} else {
    header("location: error_500.html");
    exit;
}
?>