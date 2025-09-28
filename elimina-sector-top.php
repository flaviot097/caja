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
try {
    $id = $_POST['id_sector'];
    $consulta = "DELETE FROM sectores_top WHERE id = :id";
    $stmt = $pdo->prepare($consulta);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    header("Location: sectores-top.php");
} catch (PDOException $e) {
    echo '¡Error a eliminar el sector!';
    exit;
}
?>