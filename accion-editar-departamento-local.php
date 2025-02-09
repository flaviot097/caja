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


$departamento = $_POST["nombre_c"];
$costo = $_POST["costo_c"];
$ganancia = $_POST["ganancia_c"];
$fecha = date("Y-m-d");
$result;

$conuslta = "SELECT  costo, ganancia ,codigo_barra FROM producto WHERE nombre_producto LIKE :nombre_producto";
$stmtp = $pdo->prepare($conuslta);
$stmtp->bindValue(':nombre_producto', '%' . $departamento . '%', PDO::PARAM_STR);
$stmtp->execute();
$result = $stmtp->fetchAll(PDO::FETCH_ASSOC);

foreach ($result as $item) {

    $ganancia_vieja = $item['ganancia'];
    $code = $item["codigo_barra"];
    if ($ganancia === "") {
        $ganancia = $ganancia_vieja;
    }
    $sumaporcentaje = intval($costo * $ganancia / 100);
    $precio = intval($costo) + $sumaporcentaje;
    try {
        $update_Producto = "UPDATE producto SET precio = :precio, costo = :costo, ganancia = :ganancia, fecha = :fecha WHERE codigo_barra = :codigo_barra";
        $stmtpr = $pdo->prepare($update_Producto);
        $stmtpr->bindParam(':precio', $precio, PDO::PARAM_INT);
        $stmtpr->bindParam(':ganancia', $ganancia, PDO::PARAM_INT);
        $stmtpr->bindParam(':codigo_barra', $code, PDO::PARAM_STR);
        $stmtpr->bindParam(':costo', $costo, PDO::PARAM_INT);
        $stmtpr->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        $stmtpr->execute();

    } catch (\Throwable $th) {
        setcookie("mensaje", "fallo", time() + 10, '/');
        header("location: editar-departamento.php");
        exit;
    }
    try {
        $udate_reparto = "UPDATE producto_reparto SET precio = :precio, costo = :costo, ganancia = :ganancia, fecha = :fecha WHERE codigo_barra = :codigo_barra";
        $stmtpr = $pdo->prepare($update_Producto);
        $stmtpr->bindParam(':precio', $precio, PDO::PARAM_INT);
        $stmtpr->bindParam(':ganancia', $ganancia, PDO::PARAM_INT);
        $stmtpr->bindParam(':codigo_barra', $code, PDO::PARAM_STR);
        $stmtpr->bindParam(':costo', $costo, PDO::PARAM_INT);
        $stmtpr->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        $stmtpr->execute();

    } catch (\Throwable $th) {
        setcookie("mensaje", "fallo", time() + 10, '/');
        header("location: editar-departamento.php");
        exit;
    }
}


setcookie("mensaje", "exito", time() + 10, '/');
header("location: editar-departamento-local.php");
exit;