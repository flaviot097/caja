<?php
require_once "conecion.php";
$dsn = "mysql:host=localhost:3307;dbname=code_bar;";
try {
    $pdo = new PDO($dsn, $usuario, $contrasena);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $er) {
    echo $er->getMessage();
}

$nombre_egreso = $_POST["nombre_egreso"];
$monto = $_POST["monto"];

$query = "SELECT costo FROM producto";

$query1 = "INSERT INTO egresos (nombre_egreso ,total,fecha) VALUES (:nombre_egreso ,:total,:fecha)";

echo $nombre_egreso;
echo $monto;
$fecha = date("Y-m-d");

$stmt = $pdo->prepare($query1);
$stmt->bindParam(':nombre_egreso', $nombre_egreso);
$stmt->bindParam(':total', $monto);
$stmt->bindParam(':fecha', $fecha);

if ($stmt->execute()) {
    setcookie("mensaje", "exito", time() + 10, '/');
    header("location: egresos.php");

} else {
    setcookie("mensaje", "fallo", time() + 10, '/');
    header("location: egresos.php");
}