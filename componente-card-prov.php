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
$consulta = "SELECT DISTINCT proveedor FROM producto";
$stmt = $pdo->prepare($consulta);
if ($stmt->execute()) {
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $resultado = "error";
}

foreach ($resultado as $sector) {
    $nombre_sector = $sector['proveedor'];
    echo "<div class='product-card'>
     
    <div class='product-info'>
        <div class='product-detail'>
            <span class='label'>Sector:</span>
            <span class='value green'>$nombre_sector</span>
        </div>
    </div>
    <div class='product-actions'>
    <form class='product-actions' method='POST' action='accion-editar-proveedor.php'>
        <input type='text' id='productName' class='form-input' name='porcentaje' placeholder='100.00%' required>
        <input type='hidden' name='nombre_prov' value='$nombre_sector'>
        <button class='btn btn-edit'>Editar</button>
    </form>
    </div>
</div>";

}

?>