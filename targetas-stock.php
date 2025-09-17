<?php
if ($_GET) {

    $count_vuelta = 0;

    require_once "conecion.php";

    $dsn = 'mysql:host=localhost:3307;dbname=code_bar;';
    try {
        $pdo = new PDO($dsn, $usuario, $contrasena);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo 'error al conectarse: ' . $e->getMessage();
        exit;
    }

    // Obtener valores de los parámetros GET
    $nombre_producto = $_GET['nombre_producto'] ?? '';
    $departamento = $_GET['departamento'] ?? '';
    $proveedor = $_GET['proveedor'] ?? '';
    $code_bar_con = $_GET['codigo_barra'] ?? '';

    if ($nombre_producto === '' && $departamento === '' && $proveedor === '' && $code_bar_con === '') {
        echo "Se debe completar al menos un campo para realizar la búsqueda.";
        exit;
    }

    // Construir la consulta base
    $query = "SELECT * FROM producto WHERE 1=1";

    // Preparar la consulta según los campos proporcionados
    if ($nombre_producto !== '') {
        $query .= " AND nombre_producto LIKE :nombre_producto%";
    }
    if ($departamento !== '') {
        $query .= " AND departamento LIKE :departamento%";
    }
    if ($proveedor !== '') {
        $query .= " AND proveedor LIKE :proveedor%";
    }
    if ($code_bar_con !== "") {
        $query .= " AND codigo_barra LIKE :codigo_barra%";
    }

    // Añadir el ORDER BY una sola vez al final
    $query .= " ORDER BY stock ASC";

    // Preparar la consulta con PDO
    $stmt = $pdo->prepare($query);

    // Vincular los parámetros según corresponda
    if ($nombre_producto !== '') {
        $stmt->bindValue(':nombre_producto', '%' . $nombre_producto . '%', PDO::PARAM_STR);
    }
    if ($departamento !== '') {
        $stmt->bindValue(':departamento', '%' . $departamento . '%', PDO::PARAM_STR);
    }
    if ($proveedor !== '') {
        $stmt->bindValue(':proveedor', '%' . $proveedor . '%', PDO::PARAM_STR);
    }
    if ($code_bar_con !== "") {
        $stmt->bindValue(':codigo_barra', '%' . $code_bar_con . '%', PDO::PARAM_STR);
    }


    // Ejecutar la consulta
    $stmt->execute();

    // Obtener los resultados si los hay
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Mostrar los resultados
    if ($resultados) {
        foreach ($resultados as $item) {
            $count_vuelta = $count_vuelta + $item["stock"];
            $color = "";
            if (floatval($item["stock"]) > $item["num_stock"] && floatval($item["stock"]) > ($item["num_stock"] + 6)) {
                $color = "green";
            } elseif (floatval($item["stock"]) < $item["num_stock"]) {
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
</a><form action='eliminar-producto.php' method='get' class='eliminar-producto eliminar-prod eliminate-prod' >
<input type='hidden' name='codigo_B' value=" . $item["codigo_barra"] . ">
<button style='height: 100%; type='submit' class='eliminar-prod' ><img class='eliminar-img' src='images/eliminar.png' alt='eliminar' ></button></form>
<div class='contenedor-formularios'><form  method='Post' class='editar-producto' id=" . $item["codigo_barra"] . " action='editar-p_l.php'><label for='ingrese stock a editar' class='label-producto' >Editar</label>
<input type='hidden' name='codigo_B' value=" . $item["codigo_barra"] . ">
<input type='hidden' name='stock' value=" . $item["stock"] . ">
<input class='input-producto' type='text' value=" . $item["stock"] . " name='editar_stock_prod'><button class='btm-submit' type='submit'>Stock</button>
</form>
<form class='editar-producto' action='template-editar-prod-l.php' method='post'>
<input type='hidden' name='codigo_B' value=" . $item["codigo_barra"] . ">
<button class='productos-editar' type='submit'>Producto</button></form></div>
</div> ";
        }
        echo "<br><div class='contador-container'> Total stock: $count_vuelta productos</div><br>";
    } else {
        echo "<p>No se encontraron resultados.</p>";
    }
} else {
    require_once "funcion-estadisticas.php";
}

echo "<div class='product-card'>
    <div class='product-info'>
        <div class='product-detail'>
            <span class='label'>código de producto:</span>
            <span class='value blue'>213</span>
        </div>
        <div class='product-detail'>
            <span class='label'>Categoría:</span>
            <span class='value green'>Verduras</span>
        </div>
        <div class='product-detail'>
            <span class='label'>Proveedor:</span>
            <span class='value'>Ivelsoft</span>
        </div>
        <div class='product-detail'>
            <span class='value'>21321321</span>
        </div>
        <div class='product-detail'>
            <span class='label'>costo:</span>
            <span class='value'>$1500</span>
        </div>
        <div class='product-detail'>
            <span class='label'>ganancia:</span>
            <span class='value'>10%</span>
        </div>
        <div class='product-detail'>
            <span class='label'>Precio final:</span>
            <span class='value'>$35433</span>
        </div>
    </div>
    <div class='product-actions'>
        <button class='btn btn-edit'>Editar</button>
        <div class='stock-info'>
            <div class='stock-number'>9992.7</div>
            <div class='stock-label'>Stock</div>
            <div class='stock-product'>Producto</div>
        </div>
        <button class='btn btn-delete'>🗑️</button>
    </div>
</div>";


?>