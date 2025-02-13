<?php
if ($_POST) {
    $producto = $_POST['nombreP'] ?? '';
    $codigo = $_POST['codigoBarra'] ?? '';
    $cantidad = $_POST['cantidad'] ?? 1;
    $precio = $_POST["precioP"];
    $descuento_unitario = $_POST["decuento"] ?? "";
    $total = $_POST["cantidad"];
    $descuento_uni = 1;


    if ($descuento_unitario !== "") {
        $descuento_uni = floatval(intval($descuento_unitario) / 100);
    }

    // Verificar si la cookie "cantidad_prod" existe
    if (!isset($_COOKIE["cantidad_prod"])) {
        $lista_c_c = [];
    } else {
        // Decodificar el valor de la cookie existente
        $lista_c_c = json_decode($_COOKIE["cantidad_prod"], true);

        // Asegurarse de que la variable sea un array
        if (!is_array($lista_c_c)) {
            $lista_c_c = [];
        }
    }
    // Añadir la nueva cantidad al array
    $lista_c_c[] = $cantidad;

    // Codificar el array y guardar en la cookie
    $lista_c_c_json = json_encode($lista_c_c);
    setcookie("cantidad_prod", $lista_c_c_json, time() + 3600, "/");
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

        if (isset($_COOKIE["productos_caja"])) {
            $productos_caja = json_decode($_COOKIE["productos_caja"], true);
            $productos_caja[] = $list;
            $productos_caja_json = json_encode($productos_caja);
            setcookie("productos_caja", $productos_caja_json, time() + 3600, "/");
            //

            setcookie("resultado_busca", "", time() - 3600, "/");
            header('Location: caja.php');
            exit;
        } else {
            $productos_caja = [$list];
            $productos_caja_json = json_encode($productos_caja);
            setcookie("productos_caja", $productos_caja_json, time() + 3600, "/");
            //
            setcookie("resultado_busca", "", time() - 3600, "/");
            header('Location: caja.php');
            exit;
        }
    } else {
        echo "No se encontraron productos.";
    }

}