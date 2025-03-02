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

$nombre = $_POST["nombre_producto"];
$codigo_barra = $_POST["codigo_barra"];
$departamento = $_POST["departamento"];
$proveedor = $_POST["proveedor"];
$stock = floatval($_POST["stock"]);
$costo = $_POST["costo"];
$ganancia = $_POST["ganancia"];
$num_stock = $_POST["num_stock"];
$stok_reparto = floatval($_POST["stock_reparto"]) ?? 0;
$sumaporcentaje = intval($costo * $ganancia / 100);
$precio = intval($costo) + $sumaporcentaje;
$fecha = date("Y-m-d");

$query = "INSERT INTO producto (nombre_producto, precio, codigo_barra, departamento, proveedor, stock, costo, ganancia, num_stock, fecha) 
          VALUES (:nombre_producto, :precio, :codigo_barra, :departamento, :proveedor, :stock, :costo, :ganancia, :num_stock, :fecha)";

$stmt = $pdo->prepare($query);


$stmt->bindParam(':nombre_producto', $nombre, PDO::PARAM_STR);
$stmt->bindParam(':precio', $precio, PDO::PARAM_INT);
$stmt->bindParam(':codigo_barra', $codigo_barra, PDO::PARAM_STR);
$stmt->bindParam(':departamento', $departamento, PDO::PARAM_STR);
$stmt->bindParam(':proveedor', $proveedor, PDO::PARAM_STR);
$stmt->bindParam(':stock', $stock, PDO::PARAM_STR);
$stmt->bindParam(':costo', $costo, PDO::PARAM_INT);
$stmt->bindParam(':ganancia', $ganancia, PDO::PARAM_INT);
$stmt->bindParam(':num_stock', $num_stock, PDO::PARAM_INT);
$stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);

if ($stmt->execute()) {

    $q = "INSERT INTO producto_reparto (nombre_producto, precio, codigo_barra, departamento, proveedor, stock, costo, ganancia, num_stock, fecha) 
VALUES (:nombre_producto, :precio, :codigo_barra, :departamento, :proveedor, :stock, :costo, :ganancia, :num_stock, :fecha)";

    $stmt2 = $pdo->prepare($q);


    $stmt2->bindParam(':nombre_producto', $nombre, PDO::PARAM_STR);
    $stmt2->bindParam(':precio', $precio, PDO::PARAM_INT);
    $stmt2->bindParam(':codigo_barra', $codigo_barra, PDO::PARAM_STR);
    $stmt2->bindParam(':departamento', $departamento, PDO::PARAM_STR);
    $stmt2->bindParam(':proveedor', $proveedor, PDO::PARAM_STR);
    $stmt2->bindParam(':stock', $stok_reparto, PDO::PARAM_STR);
    $stmt2->bindParam(':costo', $costo, PDO::PARAM_INT);
    $stmt2->bindParam(':ganancia', $ganancia, PDO::PARAM_INT);
    $stmt2->bindParam(':num_stock', $num_stock, PDO::PARAM_INT);
    $stmt2->bindParam(':fecha', $fecha, PDO::PARAM_STR);

    if ($stmt2->execute()) {

        $gasto = $costo * ($stock + $stok_reparto);
        $query1 = "INSERT INTO egresos (nombre_egreso, total, fecha) VALUES (:nombre_egreso, :total, :fecha)";

        $stmt1 = $pdo->prepare($query1);

        $stmt1->bindParam(':nombre_egreso', $proveedor, PDO::PARAM_STR);
        $stmt1->bindParam(':total', $gasto, PDO::PARAM_INT);
        $stmt1->bindParam(':fecha', $fecha, PDO::PARAM_STR);


        if ($stmt1->execute()) {
            setcookie("mensaje", "exito", time() + 10, '/');
            header("location: crear-producto.php");
        } else {
            setcookie("mensaje", "fallo", time() + 10, '/');
            header("location: crear-producto.php");
        }

    } else {
        setcookie("mensaje", "fallo", time() + 10, '/');
        header("location: crear-producto.php");
    }
} else {
    setcookie("mensaje", "fallo", time() + 10, '/');
    header("location: crear-producto.php");
}