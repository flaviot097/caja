<!DOCTYPE html>
<html lang="es">

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
$consulta = "SELECT nombre_producto , id , codigo_barra  FROM sectores_top_items where id_sector_top = :sector";
$stmt = $pdo->prepare($consulta);
$sector_id = "";

if (isset($_POST['id_sector']) && !empty(trim($_POST['id_sector']))) {
    $sector_id = intval($_POST['id_sector']);

    if ($sector_id > 0) {
        setcookie("id_sector_top", $sector_id, time() + 3600, "/");
    }

} elseif (isset($_COOKIE["id_sector_top"]) && !empty($_COOKIE["id_sector_top"])) {
    $sector_id = intval($_COOKIE["id_sector_top"]);

    if ($sector_id <= 0) {
        header("Location: sectores-top.php");
        exit;
    }
} else {
    header("Location: sectores-top.php");
    exit;
}
$stmt->bindParam(':sector', $sector_id, PDO::PARAM_INT);
if ($stmt->execute()) {
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $resultado = "error";
}

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear top</title>
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

.eliminar {
    background-color: red;
    color: white;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
    border-radius: 5px;
}
</style>
<header class="header">
    <nav class="nav">
        <div class="nav-left">
            <div class="user-icon">👤</div>
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

        <h1>Ingesar productos a reporte diferenciado mensual</h1>
        <h6 id="text-top-sale"><span>Productos mas vendidos (top)</span></h6>
        <div class="form-container">
            <form action="Agregar-item-top.php" method="post">
                <input name="nombreProducto" type="text" id="nombreProducto" placeholder="Nombre del producto">
                <div id="resultados"></div>
                <input type="hidden" value="<?php echo $sector_id; ?>" name="id_sector" id="codigoBarras"
                    placeholder="Código de barras">
                <!-- <input type="text" id="codigoBarras" placeholder="Código de barras"> -->
                <button type="submit" id="agregarProducto">Agregar Producto</button>
            </form>
        </div>
        <div class="lista-productos">
            <h2>Lista de Productos</h2>
            <ul id="lista">
                <?php
                $vuelta_P = 0;
                $lista_productos = [];
                foreach ($resultado as $i) {
                    array_push($lista_productos, array($i["nombre_producto"], $i["codigo_barra"])); ?>
                <li class="itemLista">
                    <?php echo $i["nombre_producto"]; ?>
                    <form action="eliminar-item-sector-top.php" method="post" class="formulario-eliminar">
                        <input type="hidden" class="eliminar" name="codigo_barra"
                            value="<?php echo $i["codigo_barra"]; ?>">
                        <input type="hidden" class="eliminar" name="id_sector" value="<?php echo $sector_id; ?>">
                        <button class="eliminar" data-index="<?php echo $vuelta_P;
                            $vuelta_P + 1 ?>">Eliminar</button>
                    </form>
                </li>
                <?php }
                ; ?>
            </ul>
        </div>

    </div>
    <script src="./js/pregunta_finalizaciones.js"></script>
    <script src="./js/nav-bar.js"></script>
    <script src="./js/coneccion_api.js"></script>
    <script src="./js/top.js"></script>


</body>

</html>