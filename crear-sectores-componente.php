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

$lugar = $_POST['lugar'];
$nombre_sector = $_POST['nombre_sector'];

$consulta = "INSERT INTO sectores (nombre_sector, reparto_deposito) VALUES (:nombre_sector, :reparto_deposito)";
$stmt = $pdo->prepare($consulta);
$stmt->bindParam('reparto_deposito', $lugar, PDO::PARAM_INT);
$stmt->bindParam('nombre_sector', $nombre_sector, PDO::PARAM_STR);
if ($stmt->execute()) {
    header("Location: sectores.php");
} else {
    echo "Error al crear el sector.";
}

?>