<!DOCTYPE html>
<html lang="es">
<?php
require_once "validacion-usuario.php";
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar top</title>
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
</style>
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
$resultado = [];
$consulta = "SELECT *  FROM sectores_top where id = :id_sector";
$stmt = $pdo->prepare($consulta);
$stmt->bindParam(':id_sector', $_POST['id_sector'], PDO::PARAM_INT);
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

        <h1>Actualizar Sector</h1>
        <h6 id="text-top-sale"><span>Productos mas vendidos (top)</span></h6>
        <div class="acciones">
            <form action="editar-sector-top.php" method="post">
                <input type="number" class="nombre_sector" id="searchInput" value="<?php echo $resultado[0]['id']; ?>"
                    name="id_top" style="display: none;">
                <input type="text" class="nombre_sector" id="searchInput" placeholder="nombre sector... "
                    value="<?php echo $resultado[0]['nombre_sector_top']; ?>" name="nombre_sector_top">
                <button type="submit" id="finalizarVenta">actualizar sector</button>
            </form>
        </div>
    </div>
    <script src="./js/nav-bar.js"></script>
    <script src="./js/top.js"></script>


</body>

</html>