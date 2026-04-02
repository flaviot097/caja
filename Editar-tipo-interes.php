<!DOCTYPE html>
<html lang="es">
<?php
require_once "validacion-usuario.php";
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Tipo Interes por mora en cuenta corriente</title>
    <link rel="stylesheet" href="./css/top.css">
    <link rel="stylesheet" href="./css/nav-bar.css">
    <link rel="stylesheet" href="./css/bts-acciones.css">
    <link rel="stylesheet" href="./css/darckMode.css">

</head>
<style>
#resultados {
    border: 1px solid #ccc;
    max-height: 200px;
    overflow-y: auto;
    margin-bottom: 10px;
}

.resultado-item {
    padding: 8px;
    cursor: pointer;
}

.resultado-item:hover {
    background-color: #f0f0f0;
}

#totalP {
    display: block;
    width: 100% !important;
    text-align: center;
    font-weight: bold;
}

#mes,
#estado {
    width: 100%;
    margin-top: 1%;
}

.nombre_sector_label {
    width: 100%;
    font-size: medium;
    border-color: transparent;
    text-align: center;
    font-weight: 700;
}

#searchInput {
    margin-top: 6px;
    margin-bottom: 6px;
}
</style>
<?php
require_once "conecion.php";

if (!isset($_POST['cod_tipo'])) {
    header("location: tipo-interes.php");
}

$dsn = 'mysql:host=localhost:3307;dbname=code_bar;';
try {
    $pdo = new PDO($dsn, $usuario, $contrasena);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'error al conectarse: ' . $e->getMessage();
    exit;
}
$resultado = [];
$consulta = "SELECT *  FROM tipo_interes where cod_tipo_interes = :cod_tipo";
$stmt = $pdo->prepare($consulta);
$cod_tipo = intval($_POST['cod_tipo']);
$stmt->bindParam(':cod_tipo', $_POST['cod_tipo'], PDO::PARAM_INT);
if ($stmt->execute()) {
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $resultado = "error";
}
?>
<header class="header">
    <nav class="nav">
        <div class="nav-left">
            <div class="user-icon"><a href="stock-template.php">👤</a></div>
        </div>
        <div class="nav-center">
            <?php
            require_once "div-nav.php"; ?>
        </div>
        <div class="nav-right">
            <button class="theme-toggle" id="themeToggle">🌙</button>
        </div>
    </nav>
</header>

<body>
    <main class="main-content">
        <?php require_once "acciones-stock.php" ?>
    </main>
    <div class="container">

        <h1>Actualizar Tipo Interes</h1>
        <h6 id="text-top-sale"><span></span></h6>
        <div class="acciones">
            <form action="Actualizar-tipo.php" method="post">
                <input type="number" class="nombre_sector" id="searchInput"
                    value="<?php echo $resultado[0]['cod_tipo_interes']; ?>" name="cod_tipo" style="display: none;">
                <input class="nombre_sector_label" value="Nombre" readonly="readonly">
                <input type="text" class="nombre_sector" id="searchInput" placeholder="nombre sector... "
                    value="<?php echo $resultado[0]['nombre_tipo']; ?>" name="nombre_tipo">
                <input class="nombre_sector_label" value="Fecha" readonly="readonly">
                <input type="text" class="nombre_sector" id="searchInput" placeholder="Dias/meses... "
                    value="<?php echo $resultado[0]['fecha_hasta']; ?>" name="fecha_hasta">
                <input class="nombre_sector_label" value="Monto Desde" readonly="readonly">
                <input type="text" class="nombre_sector" id="searchInput" placeholder="monto desde... "
                    value="<?php echo $resultado[0]['monto_desde']; ?>" name="monto_desde" disabled>
                <input class="nombre_sector_label" value="Monto Hasta" readonly="readonly">
                <input type="text" class="nombre_sector" id="searchInput" placeholder="monto hasta... "
                    value="<?php echo $resultado[0]['monto_hasta']; ?>" name="monto_hasta" disabled>
                <input class="nombre_sector_label" value="Interes (%)" readonly="readonly">
                <input type="text" class="nombre_sector" id="searchInput" placeholder="valor... "
                    value="<?php echo $resultado[0]['valor']; ?>" name="valor">
                <input class="nombre_sector_label" value="Mensual/Dias" readonly="readonly">
                <?php $es_men = 'Diario';
                if ($resultado[0]['es_mensual']) {
                    $es_men = "Mensual";
                } ?>
                <select name='mes' id='mes' class="nombre_sector" required>
                    <option value='<?php echo $es_men; ?>' selected> -
                        <?php
                        echo $es_men; ?> -
                    </option>
                    <option value='Mensual'>Mesual</option>
                    <option value='Diario'>Diario</option>
                </select>
                <input class="nombre_sector_label" value="Estado" readonly="readonly">
                <?php $es_hab = 'Deshabilitado';
                if ($resultado[0]['habilitado']) {
                    $es_hab = "Activo";
                }
                ?>
                <select name='habilitado' id='estado' class="nombre_sector" required>
                    <option value='<?php echo $es_hab; ?>' selected> -
                        <?php echo $es_hab; ?> -
                    </option>
                    <option value='Activo'>Activo</option>
                    <option value='Deshabilitado'>Deshabilitado</option>
                </select>
                <button type="submit" id="finalizarVenta">Actializar Tipo</button>
            </form>
        </div>
    </div>
    <script src="./js/nav-bar.js"></script>
    <script src="./js/top.js"></script>


</body>

</html>