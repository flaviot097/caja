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
$consulta = "SELECT nombre_sector , id , reparto_deposito FROM sectores";
$stmt = $pdo->prepare($consulta);
if ($stmt->execute()) {
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $resultado = "error";
}

foreach ($resultado as $sector) {
    $nombre_sector = $sector['nombre_sector'];
    $id_sector = $sector['id'];
    $rd = $sector['reparto_deposito'];
    $reparto_deposito = $sector['reparto_deposito'];
    if ($reparto_deposito == 1) {
        $reparto_deposito = "Deposito";
    } else {
        $reparto_deposito = "Reparto";
    }
    echo "<div class='product-card'>
    <div class='product-info'>
        <div class='product-detail'>
            <span class='label'>Sector:</span>
            <span class='value green'>$nombre_sector</span>
        </div>
        <div class='product-detail'>
            <span class='label'>Espacio:</span>
            <span class='value'>$reparto_deposito</span>
        </div>
    </div>
    <form class='product-actions' method='POST' action='elimina-sector.php'>
        <input type='hidden' name='id_sector' value='$id_sector'>
        <input type='hidden' name='nombre_sector' value='$nombre_sector'>
        <input type='hidden' name='reparto_deposito' value='$reparto_deposito'>
        <button class='btn btn-edit red'>Eliminar</button>
    </form>
    <form class='product-actions' method='POST' action='editar-sector-t.php'>
        <input type='hidden' name='id_sector' value='$id_sector'>
        <input type='hidden' name='nombre_sector' value='$nombre_sector'>
        <input type='hidden' name='reparto_deposito' value='$rd'>
        <button class='btn btn-edit'>Editar</button>
    </form>
    <form class='product-actions' method='POST' action='genera-reportes-sectores.php' >
        <input type='date' name='date_venta'>
        <select name='turno' id='turno' required>
            <option value='mañana'>Seleccione franja horaria</option>
            <option value='mañana'>Mañana</option>
            <option value='tarde'>Tarde</option>
        </select>
        <input type='hidden' name='id_sector' value='$id_sector'>
        <input type='hidden' name='nombre_sector' value='$nombre_sector'>
        <input type='hidden' name='reparto_deposito' value='$reparto_deposito'>
        <button class='btn btn-edit'>Generar Reporte</button>
    </form>
</div>";

}

?>