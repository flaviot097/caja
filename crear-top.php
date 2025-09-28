<!DOCTYPE html>
<html lang="es">
<?php
require_once "validacion-usuario.php";
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
            <input type="text" id="nombreProducto" placeholder="Nombre del producto">
            <div id="resultados"></div>
            <input type="text" id="codigoBarras" placeholder="Código de barras" autofocus>
            <button id="agregarProducto">Agregar Producto</button>
        </div>
        <div class="lista-productos">
            <h2>Lista de Productos</h2>
            <ul id="lista"></ul>
        </div>

        <div class="acciones">
            <form action="crear-sector-top.php" method="post">
                <input type="text" class="nombre_sector" id="searchInput" placeholder="nombre sector..."
                    name="nombre_sector_top">
                <?php
                require_once "traeSectorestop.php"; ?>
                <select name="id" id="searchInput">
                    <option value="noone" class="option-efectivo" selected>Ninguno</option>
                    <?php foreach ($todosSectores as $i) { ?>
                    <option value=<?php echo $i["id"]; ?> class="option-efectivo"><?php echo $i["nombre_sector_top"]; ?>
                    </option>
                    <?php }
                    ; ?>
                </select>
                <button type="submit" id="finalizarVenta">Crear sector o actualizar sector</button>
            </form>
        </div>
    </div>
    <script src="./js/nav-bar.js"></script>
    <script src="./js/coneccion_api.js"></script>
    <script src="./js/top.js"></script>


</body>

</html>