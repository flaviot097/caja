<?php
require_once "conecion.php";

$query = "SELECT * FROM ventas";
$stmt = $connection->prepare($query);
$stmt->execute();

$result = $stmt->get_result();


$mes_array = array_fill(0, 11, 0);
$dia_array = array_fill(0, 30, 0);
$anio_array = array_fill(2024, 9, 0);


if ($result->num_rows > 0) {
    while ($venta = $result->fetch_assoc()) {
        $array_date = explode("-", $venta['fecha']);
        $dia = intval($array_date[2]) - 1;
        $mes = intval($array_date[1]) - 1;
        $anio = intval($array_date[0]);
        $site = $venta['reparto_o_local'];
        if ($site === "reparto") {
            $mes_array[$mes] += intval($venta['total']);
            $dia_array[$dia] += intval($venta['total']);
            $anio_array[$anio] += intval($venta['total']);
        }


    }
}

function resp($anio_a, $mes_a, $dia_a)
{
    $respuesta = array($anio_a, $mes_a, $dia_a);
    echo json_encode($respuesta);
}
header('Content-Type: application/json');
resp($anio_array, $mes_array, $dia_array);