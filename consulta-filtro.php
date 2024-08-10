<?php
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

if ($nombre_producto === '' && $departamento === '' && $proveedor === '') {
    echo "Se debe completar al menos un campo para realizar la búsqueda.";
    exit;
}

// Construir la consulta base
$query = "SELECT * FROM producto WHERE 1=1";

// Preparar la consulta según los campos proporcionados
if ($nombre_producto !== '') {
    $query .= " AND nombre_producto LIKE :nombre_producto";
}
if ($departamento !== '') {
    $query .= " AND departamento LIKE :departamento";
}
if ($proveedor !== '') {
    $query .= " AND proveedor LIKE :proveedor";
}

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

// Ejecutar la consulta
$stmt->execute();

// Obtener los resultados si los hay
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mostrar los resultados
if ($resultados) {
    foreach ($resultados as $item) {
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
</a><a class='eliminar-producto' href='eliminar-producto.php'><p class='eliminar' id=" . $item["codigo_barra"] . "><img class='eliminar-img' src='images/eliminar.png' alt='eliminar'></p></a>
</div> ";
    }
} else {
    echo "<p>No se encontraron resultados.</p>";
}