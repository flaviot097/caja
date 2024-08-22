<?php require_once "conecion.php";
session_start();
$dsn = 'mysql:host=localhost:3307;dbname=code_bar;';
try {
    $pdo = new PDO($dsn, $usuario, $contrasena);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}


$departamento = $_POST["departamento_c"];
$costo = $_POST["costo"];
$ganancia = $_POST["ganancia"];
$sumaporcentaje = intval($costo * $ganancia / 100);
$precio = intval($costo) + $sumaporcentaje;
$fecha = date("Y-m-d");

$query = "UPDATE producto_reparto
SET precio = :precio, 
    costo = :costo, 
    ganancia = :ganancia,
    fecha = :fecha
WHERE departamento = :departamento";

$stmt = $pdo->prepare($query);



$stmt->bindParam(':precio', $precio, PDO::PARAM_INT);
$stmt->bindParam(':departamento', $departamento, PDO::PARAM_STR);
$stmt->bindParam(':costo', $costo, PDO::PARAM_INT);
$stmt->bindParam(':ganancia', $ganancia, PDO::PARAM_INT);
$stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);


if ($stmt->execute()) {

    setcookie("mensaje", "exito", time() + 10, '/');
    header("location: editar-departamento.php");

} else {
    setcookie("mensaje", "fallo", time() + 10, '/');
    header("location: editar-departamento.php");
}