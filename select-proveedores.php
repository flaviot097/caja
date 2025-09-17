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
$total_hoy = 0;
$color = "";
date_default_timezone_set('America/Buenos_Aires');
$hoy = date("Y-m-d");
$consulataTodosProveedores = "SELECT DISTINCT proveedor FROM producto";
$stmt = $pdo->prepare($consulataTodosProveedores);
$stmt->execute();
$todosProveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>