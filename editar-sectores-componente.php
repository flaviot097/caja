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

$id = $_POST['id'];
$lugar = $_POST['lugar'];
$nombre_sector = $_POST['nombre_sector'];
$lugarAnterior = $_POST['repartodeposito'];
$nombre_anterior = $_POST['nombresector'];
if ($lugar == "") {
    $lugar = $lugarAnterior;
}
if ($nombre_sector == "") {
    $nombre_sector = $nombre_anterior;
}
$sector = [];

$consulta = "UPDATE sectores SET nombre_sector = :nombre_sector, reparto_deposito = :reparto_deposito WHERE id = :id";
$stmt = $pdo->prepare($consulta);
$stmt->bindParam('reparto_deposito', $lugar, PDO::PARAM_INT);
$stmt->bindParam('id', $id, PDO::PARAM_INT);
$stmt->bindParam('nombre_sector', $nombre_sector, PDO::PARAM_STR);
if ($stmt->execute()) {
    header("Location: sectores.php");
} else {
    echo "Error al crear el sector.";
}

?>