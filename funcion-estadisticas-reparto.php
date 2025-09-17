<?php
require_once "conecion.php";
$query = "SELECT * FROM producto_reparto";
$stmt = $connection->prepare($query);
$stmt->execute();

$result = $stmt->get_result();

$user = $_SESSION["usuario"];
$contrasenoa = $_SESSION["password"];


if ($result->num_rows > 0) {
    while ($item = $result->fetch_assoc()) {
        $color = "";
        if (floatval($item["stock"]) > $item["num_stock"] && floatval($item["stock"]) > ($item["num_stock"] + 6)) {
            $color = "green";
        } elseif (floatval($item["stock"]) < $item["num_stock"]) {
            $color = "red";
        }
        ;
        $dsn = 'mysql:host=localhost:3307;dbname=code_bar;';
        try {
            $pdo = new PDO($dsn, $usuario, $contrasena);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'error al conectarse: ' . $e->getMessage();
            exit;
        }
        $sectorP = "";
        $consultaID = "SELECT sector_id FROM productos_index_sectores WHERE codigo_barra = :codigo_barra";
        $stmtID = $pdo->prepare($consultaID);
        $stmtID->bindParam(':codigo_barra', $item["codigo_barra"], PDO::PARAM_STR);
        $sectorId = $stmtID->execute();
        $sectorId = $stmtID->fetchAll(PDO::FETCH_ASSOC);
        if (count($sectorId) > 0) {
            $consultaSectores = "SELECT nombre_sector FROM sectores WHERE id = :id_s";
            $sec = $sectorId[0]["sector_id"];
            $stmtc = $pdo->prepare($consultaSectores);
            $stmtc->bindParam(':id_s', $sec, PDO::PARAM_INT);
            if ($stmtc->execute()) {
                $sectorP = $stmtc->fetchAll(PDO::FETCH_ASSOC)[0]["nombre_sector"];
            }
        } else {
            $sectorP = "Sin sector";
        }
        ;

        echo "<div class='producto-stock' id=" . $item["codigo_barra"] . ">
<a href='#' class='btn btn-primary btn-sm d-inline-flex align-items-center'>" . $item["nombre_producto"] . "<h6
        class='codigo-producto' id='codigo-producto'>codigo de producto: " . $item["codigo_barra"] . "</h6>
    <h6 class='cantidad-producto " . $color . "' id='cantidad-producto' name=" . $item["stock"] . "> Cantidad:" . $item["stock"] . " Unidades
    <h6 class='codigo-producto' id='proveedor-producto' name='proveedor'>Proveedor :" . $item["proveedor"] . "</h6>
        <span class='codigo-producto sector' id='depto-producto' name='sector'>" . $sectorP . "</span>
    </span>
    <h6 class='codigo-producto' id='depto-producto' name='dpartamento'>" . $item["departamento"] . "</h6>
    </h6>
    <h6 class='codigo-producto' id='costo-producto' name='costo'>costo: $" . $item["costo"] . "</h6>
    <h6 class='codigo-producto' id='ganancia-producto' name='ganancia'>ganancia: " . $item["ganancia"] . "%</h6>
    <h6 class='codigo-producto' id='precio-producto' name='precio'>Precio final: $" . $item["precio"] . "</h6>
</a><form action='eliminar-producto-reparto.php' method='get' class='eliminar-producto eliminar-prod eliminate-prod' >
<input type='hidden' name='codigo_B' value=" . $item["codigo_barra"] . ">
<button style='height: 100%; type='submit' class='eliminar-prod' ><img class='eliminar-img' src='images/eliminar.png' alt='eliminar' ></button></form>
<div class='contenedor-formularios'><form  method='Post' class='editar-producto' id=" . $item["codigo_barra"] . " action='editar-p_l_r.php'><label for='ingrese stock a editar' class='label-producto' >Editar</label>
<input type='hidden' name='codigo_B' value=" . $item["codigo_barra"] . ">
<input type='hidden' name='stock' value=" . $item["stock"] . ">
<input class='input-producto' type='number' value=" . $item["stock"] . " name='editar_stock_prod'><button class='btm-submit' type='submit'>Stock</button>
</form>
<form class='editar-producto' action='template-editar-prod-r.php' method='post'>
<input type='hidden' name='codigo_B' value=" . $item["codigo_barra"] . ">
<button class='productos-editar' type='submit'>Producto</button></form></div>
</div> ";
    }
} else {
    echo "<p class='no-productos'>No hay productos en el sistema</p>";
}