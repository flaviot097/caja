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
$consulta = "SELECT * FROM tipo_interes";
$stmt = $pdo->prepare($consulta);
if ($stmt->execute()) {
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $resultado = "error";
}

foreach ($resultado as $tipo) {
    $nombre_tipo = $tipo['nombre_tipo'];
    $id_tipo = $tipo['id'];
    $habilitado = $tipo['habilitado'];
    $cod_tipo = intval($tipo['cod_tipo_interes']);
    $interes = intval($tipo['valor']);
    $es_mesual = "No";
    $colorMes = "red2";
    $es_habilitado = "No";
    if ($tipo['es_mensual']) {
        $es_mesual = "Si";
        $colorMes = "green";
    }
    $color = "red2";
    if ($tipo['habilitado']) {
        $es_habilitado = "Si";
        $color = "green";
    }
    echo "<div class='product-card'>
     
    <div class='product-info'>
        <div class='product-detail'>
            <span class='label'>Nombre:</span>
            <span class='value green'>$nombre_tipo</span>
        </div>
        <div class='product-detail'>
            <span class='label'>Porcentaje:</span>
            <span class='value green'>$interes%</span>
        </div>      
        <div class='product-detail'>
            <span class='label'>Interes Mensual:</span>
            <span class='value $colorMes'>$es_mesual</span>
        </div>                        
        <div class='product-detail'>
            <span class='label'>Habilitado:</span>
            <span class='value $color'>$es_habilitado</span>
        </div>        
    </div>
    <div class='product-actions'>
    <form class='product-actions formulario-eliminar' method='POST' action='elimina-tipo-interes.php'>
        <input type='hidden' name='id_tipo' value='$id_tipo'>
        <input type='hidden' name='cod_tipo' value='$cod_tipo'>
        <button class='btn btn-edit red'>Eliminar</button>
    </form>
    <form class='product-actions' method='POST' action='Editar-tipo-interes.php'>
        <input type='hidden' name='id_tipo' value='$id_tipo'>
        <input type='hidden' name='cod_tipo' value='$cod_tipo'>
        <button class='btn btn-edit green'>Editar</button>
    </form>
    </div>
</div>";

}

?>