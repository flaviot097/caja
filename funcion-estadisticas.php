<?php

require_once "conecion.php";
$query = "SELECT * FROM producto";
$stmt = $connection->prepare($query);
$stmt->execute();

$result = $stmt->get_result();

$user = $_SESSION["usuario"];
$contrasenoa = $_SESSION["password"];


if ($result->num_rows > 0) {
    while ($item = $result->fetch_assoc()) {
        $color = "";
        if (intval($item["stock"]) > $item["num_stock"] && intval($item["stock"]) > ($item["num_stock"] + 6)) {
            $color = "green";
        } elseif (intval($item["stock"]) < $item["num_stock"]) {
            $color = "red";
        }
        ;

        echo "<div class='producto-stock' id=" . $item["codigo_barra"] . ">
<a href='#' class='btn btn-primary btn-sm d-inline-flex align-items-center'>" . $item["nombre_producto"] . "<h6
        class='codigo-producto' id='codigo-producto'>codigo de producto: " . $item["codigo_barra"] . "</h6>
    <h6 class='cantidad-producto " . $color . "' id='cantidad-producto' name=" . $item["stock"] . "> Cantidad:" . $item["stock"] . " Unidades
    <h6 class='codigo-producto' id='proveedor-producto' name='proveedor'>Proveedor :" . $item["proveedor"] . "</h6>
    <h6 class='codigo-producto' id='depto-producto' name='dpartamento'>" . $item["departamento"] . "</h6>
    </h6>
    <h6 class='codigo-producto' id='costo-producto' name='costo'>costo: $" . $item["costo"] . "</h6>
    <h6 class='codigo-producto' id='ganancia-producto' name='ganancia'>ganancia: " . $item["ganancia"] . "%</h6>
    <h6 class='codigo-producto' id='precio-producto' name='precio'>Precio final: $" . $item["precio"] . "</h6>
</a><div class='eliminar-producto' ><p class='eliminar-prod' id=" . $item["codigo_barra"] . "><img class='eliminar-img' src='images/eliminar.png' alt='eliminar'></p></div>
<div class='contenedor-formularios'><form  method='Post' class='editar-producto' id=" . $item["codigo_barra"] . " action='editar-p_l.php'><label for='ingrese stock a editar' class='label-producto' >Editar</label>
<input type='hidden' name='codigo_B' value=" . $item["codigo_barra"] . ">
<input type='hidden' name='stock' value=" . $item["stock"] . ">
<input class='input-producto' type='number' value=" . $item["stock"] . " name='editar_stock_prod'><button class='btm-submit' type='submit'>Stock</button>
</form>
<form class='editar-producto' action='template-editar-prod-l.php' method='post'>
<input type='hidden' name='codigo_B' value=" . $item["codigo_barra"] . ">
<button class='productos-editar' type='submit'>Producto</button></form></div>
</div> ";
    }
} else {
    echo "<p class='no-productos'>No hay productos en el sistema</p>";
}