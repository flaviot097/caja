<?php
$stock_editar = intval($_POST["editar_stock_prod"]);
$product = $_POST["codigo_B"];
$stock = intval($_POST["stock"]);

$dif_stock = $stock_editar - $stock;
require_once "conecion.php";
$dsn = "mysql:host=localhost:3307;dbname=code_bar;";
try {
    $pdo = new PDO($dsn, $usuario, $contrasena);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $er) {
    echo $er->getMessage();
}
$queryget = "SELECT stock FROM producto WHERE codigo_barra = :codigo_barra";
$stmt1 = $pdo->prepare($queryget);
$stmt1->bindParam(':codigo_barra', $product);
$stmt1->execute();
$stock_product_get_array = $stmt1->fetchAll(PDO::FETCH_ASSOC);
$stock_product_get = $stock_product_get_array[0]["stock"];
$nueva_dif = $stock_product_get - $dif_stock;

$query = "UPDATE producto  SET stock = :nuevo_stock WHERE codigo_barra = :codigo_barra";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':codigo_barra', $product, PDO::PARAM_STR);
$stmt->bindParam(':nuevo_stock', $nueva_dif, PDO::PARAM_INT);
if ($stmt->execute()) {
    $query2 = "UPDATE producto_reparto  SET stock = :nuevo_stock WHERE codigo_barra = :codigo_barra";
    $stmt2 = $pdo->prepare($query2);
    $stmt2->bindParam(':nuevo_stock', $stock_editar, PDO::PARAM_INT);
    $stmt2->bindParam(':codigo_barra', $product, PDO::PARAM_STR);
    if ($stmt2->execute()) {
        header("location: template-stock-reparto.php");
    } else {
        echo "Error al actualizar el stock de reparto";
    }
    ;
} else {
    echo "Error al actualizar el stock";
}
;