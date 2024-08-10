<?php
require_once "conecion.php";
$dsn = 'mysql:host=localhost:3307;dbname=code_bar;';
try {
    $pdo = new PDO($dsn, $usuario, $contrasena);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}

$mes = date("m"); // Mes actual
$anio = date("Y"); // Año actual

// Consultar todas las ventas del mes actual
$query = "SELECT * FROM ventas WHERE MONTH(fecha) = :mes AND YEAR(fecha) = :anio";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':mes', $mes, PDO::PARAM_INT);
$stmt->bindParam(':anio', $anio, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Inicializar array para almacenar las ventas por día
$dia_array = array_fill(0, 31, 0); // 31 días máximo en un mes

if ($result) {
    foreach ($result as $venta) {
        $dia = intval(date("d", strtotime($venta['fecha']))) - 1; // Obtener el día de la venta y ajustar índice
        $dia_array[$dia] += intval($venta['total']); // Sumar la venta al día correspondiente
    }
}

header('Content-Type: application/json');
echo json_encode($dia_array);
?>