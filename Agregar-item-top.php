<?php
require_once "validacion-usuario.php";

require_once "conecion.php";

$dsn = 'mysql:host=localhost:3307;dbname=code_bar;';
try {
    $pdo = new PDO($dsn, $usuario, $contrasena);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'error al conectarse: ' . $e->getMessage();
    exit;
}
$consulta = "SELECT  codigo_barra  FROM producto where nombre_producto = :nombre_producto";
$stmt = $pdo->prepare($consulta);
$nombre = $_POST['nombreProducto'];
$sector_id = intval($_POST['id_sector']);

$stmt->bindParam(':nombre_producto', $nombre, PDO::PARAM_INT);
$stmt->execute();
$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
$resultado = $resultado[0]["codigo_barra"];


$AgregaListaItem = "INSERT INTO sectores_top_items (id_sector_top, nombre_producto, codigo_barra) VALUES (:id, :nombre, :cod)";
$stmt = $pdo->prepare($AgregaListaItem);
$stmt->bindParam(':id', $sector_id, PDO::PARAM_INT);
$stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
$stmt->bindParam(':cod', $resultado, PDO::PARAM_STR);
$stmt->execute();
header("Location: Actualizar-item-top.php");
exit();
?>