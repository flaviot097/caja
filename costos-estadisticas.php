<?php
require_once "conecion.php";

try {
    // Preparar las consultas
    $query = "SELECT * FROM egresos";
    $query1 = "SELECT * FROM producto WHERE costo IS NOT NULL";
    $query2 = "SELECT * FROM producto_reparto WHERE costo IS NOT NULL";

    // Ejecutar la primera consulta
    $stmt = $connection->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    // Inicializar arrays
    $mes_array = array_fill(0, 12, 0);  // 12 meses
    $dia_array = array_fill(0, 31, 0);  // 31 días
    $anio_array = [];

    // Procesar los resultados de la primera consulta
    while ($venta = $result->fetch_assoc()) {
        $fecha = new DateTime($venta['fecha']);
        $dia = $fecha->format('j') - 1;  // Día del mes (1-31)
        $mes = $fecha->format('n') - 1;  // Mes (1-12)
        $anio = $fecha->format('Y');

        $mes_array[$mes] += intval($venta['total']);
        $dia_array[$dia] += intval($venta['total']);
        if (!isset($anio_array[$anio])) {
            $anio_array[$anio] = 0;
        }
        $anio_array[$anio] += intval($venta['total']);
    }

    // Ejecutar y procesar la segunda consulta
    $stmt1 = $connection->prepare($query1);
    $stmt1->execute();
    $result1 = $stmt1->get_result();

    while ($venta = $result1->fetch_assoc()) {
        if ($venta['fecha'] !== NULL) {
            $fecha = new DateTime($venta['fecha']);
            $dia = $fecha->format('j') - 1;
            $mes = $fecha->format('n') - 1;
            $anio = $fecha->format('Y');

            $mes_array[$mes] += intval($venta['costo']);
            $dia_array[$dia] += intval($venta['costo']);
            if (!isset($anio_array[$anio])) {
                $anio_array[$anio] = 0;
            }
            $anio_array[$anio] += intval($venta['costo']);
        }
    }

    // Ejecutar y procesar la tercera consulta
    $stmt2 = $connection->prepare($query2);
    $stmt2->execute();
    $result2 = $stmt2->get_result();

    while ($venta = $result2->fetch_assoc()) {
        if ($venta['fecha'] !== NULL) {
            $fecha = new DateTime($venta['fecha']);
            $dia = $fecha->format('j') - 1;
            $mes = $fecha->format('n') - 1;
            $anio = $fecha->format('Y');

            $mes_array[$mes] += intval($venta['costo']);
            $dia_array[$dia] += intval($venta['costo']);
            if (!isset($anio_array[$anio])) {
                $anio_array[$anio] = 0;
            }
            $anio_array[$anio] += intval($venta['costo']);
        }
    }

    // Respuesta en formato JSON
    header('Content-Type: application/json');
    echo json_encode(['anio' => $anio_array, 'mes' => $mes_array, 'dia' => $dia_array]);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>