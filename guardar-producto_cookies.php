<?php

session_start();

if ($_POST) {
    $producto = $_POST['nombreP'] ?? '';
    $codigo = $_POST['codigoBarra'] ?? '';
    $cantidad = $_POST['cantidad'] ?? 1;
    $precio = $_POST["precioP"];
    $descuento_unitario = $_POST["decuento"] ?? "";
    $total = $_POST["cantidad"];
    $descuento_uni = 1;


    // Verificar si la cookie "cantidad_prod" existe
    if ($descuento_unitario !== "") {
        $descuento_uni = floatval(intval($descuento_unitario) / 100);
    }

    // Usar $_SESSION en lugar de $_COOKIE
    if (!isset($_SESSION["cantidad_prod"])) {
        $lista_c_c = [];
    } else {
        $lista_c_c = $_SESSION["cantidad_prod"];
    }

    $lista_c_c[] = $cantidad;
    $_SESSION["cantidad_prod"] = $lista_c_c;

    $results = [];
    ########################



    $results = [];

    if ((isset($_COOKIE["resultado_busca"]))) {
        $results = json_decode($_COOKIE["resultado_busca"], true);
        $list;
        if ($descuento_uni !== 1) {
            $list = [
                'nombre_producto' => $results[0]["nombre_producto"],
                'precio' => $results[0]['precio'] - ($results[0]['precio'] * $descuento_uni),
                'codigo_barra' => $results[0]['codigo_barra'],
                'cantidad' => $cantidad,
                'total' => floatval($results[0]['precio'] - ($results[0]['precio'] * $descuento_uni)) * $cantidad,
                "decuento_u" => $descuento_unitario,
                "precio_sim" => $results[0]['precio']
            ];
        } else {
            $list = [
                'nombre_producto' => $results[0]["nombre_producto"],
                'precio' => $results[0]['precio'] * $descuento_uni,
                'codigo_barra' => $results[0]['codigo_barra'],
                'cantidad' => $cantidad,
                'total' => floatval($results[0]['precio'] * $descuento_uni) * $cantidad,
            ];
        }

        if (isset($_SESSION["productos_caja"])) {
            $productos_caja = $_SESSION["productos_caja"];
            $productos_caja[] = $list;
            $_SESSION["productos_caja"] = $productos_caja;

            setcookie("resultado_busca", "", time() - 3600, "/"); // Eliminar la variable de sesión
            header('Location: caja.php');
            exit;
        } else {
            $productos_caja = [$list];
            $_SESSION["productos_caja"] = $productos_caja;

            setcookie("resultado_busca", "", time() - 3600, "/"); // Eliminar la variable de sesión
            header('Location: caja.php');
            exit;
        }
    } else {
        echo "No se encontraron productos.";
    }

}