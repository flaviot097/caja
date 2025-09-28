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
$consulta = "SELECT nombre_sector_top , id  FROM sectores_top";
$stmt = $pdo->prepare($consulta);
if ($stmt->execute()) {
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $resultado = "error";
}

$mesCorriente = date('m');
$anioCorriente = date('Y');
$fecha_actual = $anioCorriente . '-' . $mesCorriente;

foreach ($resultado as $sector) {
    $nombre_sector = $sector['nombre_sector_top'];
    $id_sector = $sector['id'];

    echo "<div class='product-card'>
     
    <div class='product-info'>
        <div class='product-detail'>
            <span class='label'>Sector:</span>
            <span class='value green'>$nombre_sector</span>
        </div>
    </div>
    <div class='product-actions'>
    <form class='product-actions' method='POST' action='elimina-sector-top.php'>
        <input type='hidden' name='id_sector' value='$id_sector'>
        <input type='hidden' name='nombre_sector' value='$nombre_sector'>
        <button class='btn btn-edit red'>Eliminar</button>
    </form>
    <form class='product-actions' method='POST' action='actualizar-top.php'>
        <input type='hidden' name='id_sector' value='$id_sector'>
        <input type='hidden' name='nombre_sector' value='$nombre_sector'>
        <button class='btn btn-edit'>Editar</button>
    </form>
    <form class='product-actions' method='POST' action='reportes-sectores-top.php' >
        <select name='mes' id='turno' required>
            <option value='$mesCorriente' selected > - $mesCorriente - </option>
            <option value='1'>1</option>
            <option value='2'>2</option>
            <option value='3'>3</option>
            <option value='4'>4</option>
            <option value='5'>5</option>
            <option value='6'>6</option>
            <option value='7'>7</option>
            <option value='8'>8</option>
            <option value='9'>9</option>
            <option value='10'>10</option>
            <option value='10'>10</option>
            <option value='11'>11</option>
            <option value='12'>12</option>
        </select>
        <input type='hidden' name='id_sector' value='$id_sector'>
        <input type='hidden' name='nombre_sector' value='$nombre_sector'>
        <button class='btn btn-edit'>Generar Reporte</button>
    </form>
    </div>
</div>";

}

?>