<?php
require_once "validacion-usuario.php";

require_once "conecion.php";

$dsn = 'mysql:host=localhost:3307;dbname=code_bar;';
try {
    $pdo = new PDO($dsn, $usuario, $contrasena);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'error al conectarse: ' . $e->getMessage();
    exit;
}
$resultado = [];
$consulta = "DELETE FROM tipo_interes where cod_tipo_interes = :cod_tipo";
$stmt = $pdo->prepare($consulta);
$cod_tipo = intval($_POST['cod_tipo']);
$stmt->bindParam(':cod_tipo', $cod_tipo, PDO::PARAM_INT);
if ($stmt->execute()) {
    header("location: tipo-interes.php");
} else {
    header("location: error_500.html");

}
?>