<?php
function crearCookieProductosCaja()
{
    if (!isset($_COOKIE['productos_caja'])) {

        $list = [
            'nombre_producto' => "Producto",
            'precio' => 0,
            'codigo_barra' => "codigo de barra",
            'cantidad' => 1,
            'total' => 0
        ];
        $productos_caja[] = $list;
        $productos_caja_json = json_encode($productos_caja);
        setcookie("productos_caja", $productos_caja_json, time() + 3600, "/");
    }
}

crearCookieProductosCaja();