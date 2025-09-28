<?php
require_once "conecion.php";

$dsn = 'mysql:host=localhost:3307;dbname=code_bar;';
try {
    $pdo = new PDO($dsn, $usuario, $contrasena);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'error al conectarse: ' . $e->getMessage();
    exit;
}
try {
    $id = $_POST['id_top'];
    $nombre_sector_top = $_POST['nombre_sector_top'];
    $consulta = "UPDATE sectores_top SET nombre_sector_top = :nombre_sector_top WHERE id = :id";
    $stmt = $pdo->prepare($consulta);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':nombre_sector_top', $nombre_sector_top, PDO::PARAM_STR);
    $stmt->execute();
    header("Location: sectores-top.php");
} catch (PDOException $e) {
    echo '¡Este nombre se encuentra en otro sector!';
    exit;
}
?>