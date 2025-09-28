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
$consulataTodosSecotres = "SELECT nombre_sector_top ,id FROM sectores_top";
$stmt = $pdo->prepare($consulataTodosSecotres);
$stmt->execute();
$todosSectores = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>