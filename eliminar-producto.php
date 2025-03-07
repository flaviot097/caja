<?php
require_once "conecion.php";

// Capturar el código de barras desde la URL
$codigo_barra = $_GET["codigo_B"];

if ($codigo_barra) {
    $dsn = 'mysql:host=localhost:3307;dbname=code_bar;';
    try {
        $pdo = new PDO($dsn, $usuario, $contrasena);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo 'Error al conectarse: ' . $e->getMessage();
        exit;
    }

    $query = "DELETE FROM producto WHERE codigo_barra = :codigo_barra";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':codigo_barra', $codigo_barra, PDO::PARAM_STR);
    $stmt->execute();

    header("location: stock-template.php");
    exit;
} else {
    echo "Código de barras no especificado.";
}