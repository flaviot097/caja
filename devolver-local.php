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
$selectProductoLocal = "SELECT stock FROM producto WHERE codigo_barra = :codigo_barra";
$selectProductoReparto = "SELECT stock FROM producto_reparto WHERE codigo_barra=:codigo_barra";


foreach ($lista_actualizar as $producto) {

    $codigo_barra = $producto->codigo_barra;
    $cantidad_a_devolver = $producto->cantidad;
    $stock_nuevo_repearto = strval(floatval($producto->stock) - floatval($producto->cantidad));

    //traer el stock del local
    $stmtLocal = $pdo->prepare($selectProductoLocal);
    $stmtLocal->bindParam(":codigo_barra", $codigo_barra, PDO::PARAM_STR);
    $stockLocal = $stmtLocal->execute();
    $stocknuevoLocal = $stmtLocal->fetchAll(PDO::FETCH_ASSOC);

    $stock_local = strval(floatval($stocknuevoLocal[0]["stock"]) + floatval($producto->cantidad));

    $stmt = $pdo->prepare($consultalocal);

    $stmt->bindParam(":codigo_barra", $codigo_barra, PDO::PARAM_STR);
    $stmt->bindParam(":stock", $stock_local, PDO::PARAM_STR);
    $stmt->execute();

    //treaer stock reparto
    //resto y actualizo los productos del reparto
    $stmt_reparto = $pdo->prepare($consultaReparto);
    $stmt_reparto->bindParam(":codigo_barra", $codigo_barra, PDO::PARAM_STR);
    $stmt_reparto->bindParam(":stock", $stock_nuevo_repearto, PDO::PARAM_STR);

    if ($stmt_reparto->execute() && $stmt->execute()) {
        setcookie("productos_stock", "", time() - 3600, "/");
        header("location: template-stock-reparto.php");
    }
}