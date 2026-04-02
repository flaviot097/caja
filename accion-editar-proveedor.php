<?php require_once "conecion.php";
session_start();
$dsn = 'mysql:host=localhost:3307;dbname=code_bar;';
try {
    $pdo = new PDO($dsn, $usuario, $contrasena);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}


$prov = $_POST["nombre_prov"];
$suba = $_POST["porcentaje"];
$exito = false;
$registros = 0;
$consulta = "SELECT  costo , precio ,codigo_barra ,ganancia FROM producto WHERE proveedor = :proveedor";
$stmtp = $pdo->prepare($consulta);
$stmtp->bindParam(':proveedor', $prov);
$stmtp->execute();
$result = $stmtp->fetchAll(PDO::FETCH_ASSOC);

foreach ($result as $item) {
    $ganancia = $item["ganancia"];
    $codigo_barra = strval($item["codigo_barra"]);
    $costo = $item["costo"];
    $nuevo_costo = $costo * (1 + $suba / 100);
    $nuevo_precio = $nuevo_costo * (1 + $ganancia / 100);

    //$precio_inicial = $item["precio"];



    $query = "UPDATE producto_reparto
SET precio = :precio, 
    costo = :costo
WHERE codigo_barra = :codigo_barra";

    $stmtpr = $pdo->prepare($query);
    $stmtpr->bindParam(':precio', $nuevo_precio, PDO::PARAM_INT);
    $stmtpr->bindParam(':codigo_barra', $codigo_barra, PDO::PARAM_STR);
    $stmtpr->bindParam(':costo', $nuevo_costo, PDO::PARAM_INT);
    $stmtpr->execute();


    $query2 = "UPDATE producto
    SET precio = :precio, 
        costo = :costo 
    WHERE codigo_barra = :codigo_barra";

    $stmt = $pdo->prepare($query2);
    $stmt->bindParam(':precio', $nuevo_precio, PDO::PARAM_INT);
    $stmt->bindParam(':codigo_barra', $codigo_barra, PDO::PARAM_STR);
    $stmt->bindParam(':costo', $nuevo_costo, PDO::PARAM_INT);
    $stmt->execute();



    $exito = true;
    $registros += 1;
}

if ($exito == true) {
    echo "<script>alert('¡Se actualizaron con exito " . $registros . " registros de reparto y local!')</script>";
    header("location: editar-por-proveedor.php");
    exit();

} else {
    echo "<script>alert('¡Error al actualizar!')</script>";
    header("location: editar-por-proveedor.php");
    exit();
}