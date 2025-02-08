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


$departamento = $_POST["departamento_c"];
$suba = $_POST["ganancia"];
$fecha = date("Y-m-d");
$exito = "";

$consulta = "SELECT  costo , ganancia ,codigo_barra FROM producto WHERE departamento = :departamento";
$stmtp = $pdo->prepare($consulta);
$stmtp->bindParam(':departamento', $departamento);
$stmtp->execute();
$result = $stmtp->fetchAll(PDO::FETCH_ASSOC);

foreach ($result as $item) {
    $ganancia = $item["ganancia"];
    $codigo_barra = $item["codigo_barra"];
    $costo = $item["costo"];
    $procentage_costo = ($costo * $suba) / 100;
    $nuevo_costo = $costo + $procentage_costo;
    $nuevo_pricio = intval($nuevo_costo * $ganancia / 100);
    $precio = intval($nuevo_costo) + $nuevo_pricio;


    $query = "UPDATE producto_reparto
SET precio = :precio, 
    costo = :costo,
    fecha = :fecha
WHERE codigo_barra = :codigo_barra";

    $stmtpr = $pdo->prepare($query);



    $stmtpr->bindParam(':precio', $precio, PDO::PARAM_INT);
    $stmtpr->bindParam(':codigo_barra', $codigo_barra, PDO::PARAM_STR);
    $stmtpr->bindParam(':costo', $nuevo_costo, PDO::PARAM_INT);
    $stmtpr->bindParam(':fecha', $fecha, PDO::PARAM_STR);

    $stmtpr->execute();


    $query2 = "UPDATE producto
    SET precio = :precio, 
        costo = :costo, 
        fecha = :fecha
    WHERE codigo_barra = :codigo_barra";

    $stmt = $pdo->prepare($query2);



    $stmt->bindParam(':precio', $precio, PDO::PARAM_INT);
    $stmt->bindParam(':codigo_barra', $codigo_barra, PDO::PARAM_STR);
    $stmt->bindParam(':costo', $nuevo_costo, PDO::PARAM_INT);
    $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
    $stmt->execute();

    $exito = "si";
}

if ($exito == "si") {
    setcookie("mensaje", "exito", time() + 10, '/');
    header("location: editar-departamento-local.php");

} else {
    setcookie("mensaje", "fallo", time() + 10, '/');
    header("location: editar-departamento-local.php");
}