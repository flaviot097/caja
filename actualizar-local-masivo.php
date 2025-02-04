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
$consultalocal = "UPDATE producto SET stock=:stock ,costo=:costo ,ganancia=:ganancia, precio=:precio WHERE codigo_barra=:codigo_barra";
$selectlocal = "SELECT stock FROM producto WHERE codigo_barra=:codigo_barra";


foreach ($lista_actualizar as $producto) {

    $codigo_barra = $producto->codigo_barra;
    $cantidad = $producto->cantidad;
    $costo = $producto->costo;
    $ganancia = $producto->ganancia;
    $precio = $costo + (($costo * $ganancia) / 100);
    $stock = strval(floatval($producto->stock) + floatval($producto->cantidad));

    //traer el stock del local
    $stmtLocal = $pdo->prepare($selectlocal);
    $stmtLocal->bindParam(":codigo_barra", $codigo_barra, PDO::PARAM_STR);
    $stockLocal = $stmtLocal->execute();
    $existencias = $stmtLocal->fetchAll(PDO::FETCH_ASSOC);

    $stock_local_nuevo = strval(floatval($existencias[0]["stock"]) + floatval($producto->cantidad));
    echo $existencias[0]["stock"];
    $stmt = $pdo->prepare($consultalocal);

    $stmt->bindParam(":codigo_barra", $codigo_barra, PDO::PARAM_STR);
    $stmt->bindParam(":costo", $costo, PDO::PARAM_INT);
    $stmt->bindParam(":ganancia", $ganancia, PDO::PARAM_INT);
    $stmt->bindParam(":ganancia", $ganancia, PDO::PARAM_INT);
    $stmt->bindParam(":precio", $precio, PDO::PARAM_INT);
    $stmt->bindParam(":stock", $stock_local_nuevo, PDO::PARAM_STR);

    if ($stmt->execute() && $stmt->execute()) {
        setcookie("productos_stock", "", time() - 3600, "/");
        header("location: template-stock-reparto.php");
    }
}