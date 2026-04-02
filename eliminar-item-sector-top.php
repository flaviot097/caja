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
$consulta = "DELETE FROM sectores_top_items WHERE id_sector_top = :id_sector AND codigo_barra = :codigo_barra";
$stmt = $pdo->prepare($consulta);
$sector_id = intval($_POST['id_sector']);

$stmt->bindParam(':codigo_barra', $_POST['codigo_barra'], PDO::PARAM_STR);
$stmt->bindParam(':id_sector', $sector_id, PDO::PARAM_INT);
$stmt->execute();
header("Location: Actualizar-item-top.php");
exit();
?>