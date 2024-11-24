<?php
$lista_actualizar = $_COOKIE["productos_stock"];
$lista_actualizar = json_decode($lista_actualizar);

require_once "conecion.php";
$dsn = "mysql:host=localhost:3307;dbname=code_bar;";
try {
    $pdo = new PDO($dsn, $usuario, $contrasena);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
$consultalocal = "UPDATE producto SET stock=:stock WHERE codigo_barra=:codigo_barra";
$consultaReparto = "UPDATE producto_reparto SET stock=:stock WHERE codigo_barra=:codigo_barra";
$selectProductoLocal = "SELECT stock FROM producto WHERE codigo_barra=:codigo_barra";

foreach ($lista_actualizar as $producto) {

    $codigo_barra = $producto->codigo_barra;
    $cantidad = $producto->cantidad;
    $stock = strval(floatval($producto->stock) + floatval($producto->cantidad));

    //traer el stock del local
    $stmtLocal = $pdo->prepare($selectProductoLocal);
    $stmtLocal->bindParam("codigo_barra", $codigo_barra, PDO::PARAM_STR);
    $stockLocal = $stmtLocal->execute();
    $stocknuevoLocal = $stmtLocal->fetchAll(PDO::FETCH_ASSOC);

    $stock_local_restado = strval(floatval($stocknuevoLocal[0]["stock"]) - floatval($producto->cantidad));

    $stmt = $pdo->prepare($consultalocal);
    $stmt_reparto = $pdo->prepare($consultaReparto);

    $stmt->bindParam("codigo_barra", $codigo_barra, PDO::PARAM_STR);
    $stmt->bindParam("stock", $stock_local_restado, PDO::PARAM_STR);

    $stmt_reparto->bindParam("codigo_barra", $codigo_barra, PDO::PARAM_STR);
    $stmt_reparto->bindParam("stock", $stock, PDO::PARAM_STR);

    if ($stmt_reparto->execute() && $stmt->execute()) {
        setcookie("productos_stock", $productos_caja_json, time() - 3600, "/");
        header("/template-stock-reparto.php");
    }
}