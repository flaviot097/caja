<?php
$stock_editar = intval($_POST["editar_stock_prod"]);
$product = $_POST["codigo_B"];
$stock = intval($_POST["stock"]);

require_once "conecion.php";
$dsn = "mysql:host=localhost:3307;dbname=code_bar;";
try {
    $pdo = new PDO($dsn, $usuario, $contrasena);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $er) {
    echo $er->getMessage();
}


$query = "UPDATE producto  SET stock = :nuevo_stock WHERE codigo_barra = :codigo_barra";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':codigo_barra', $product, PDO::PARAM_STR);
$stmt->bindParam(':nuevo_stock', $stock_editar, PDO::PARAM_INT);
if ($stmt->execute()) {
    header("location: stock-template.php");
} else {
    echo "Error al actualizar el stock";
}
;