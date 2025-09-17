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

$id = $_POST['id_sector'];
$consulta = "DELETE FROM sectores WHERE id = :id";
$stmt = $pdo->prepare($consulta);
$stmt->bindParam('id', $id, PDO::PARAM_INT);
if ($stmt->execute()) {
    header("Location: sectores.php");
} else {
    echo "Error al crear el sector.";
}

?>