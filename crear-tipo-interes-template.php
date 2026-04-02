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
            <form action="crear-tipo-interes.php" method="post">
                <input class="nombre_sector_label" value="Nombre" readonly="readonly">
                <input type="text" class="nombre_sector" id="searchInput" placeholder="nombre sector... "
                    name="nombre_tipo">
                <input class="nombre_sector_label" value="Fecha" readonly="readonly">
                <input type="text" class="nombre_sector" id="searchInput" placeholder="Dias/meses... "
                    name="fecha_hasta">
                <input class="nombre_sector_label" value="Monto Desde" readonly="readonly">
                <input type="text" class="nombre_sector" id="searchInput" placeholder="monto desde... " value="0"
                    name="monto_desde" disabled>
                <input class="nombre_sector_label" value="Monto Hasta" readonly="readonly">
                <input type="text" class="nombre_sector" id="searchInput" placeholder="monto hasta... "
                    name="monto_hasta" disabled value="9999999999">
                <input class="nombre_sector_label" value="Interes (%)" readonly="readonly">
                <input type="text" class="nombre_sector" id="searchInput" placeholder="valor... " name="valor">
                <input class="nombre_sector_label" value="Mensual/Dias" readonly="readonly">
                <select name='mes' id='mes' class="nombre_sector" required>
                    <option value='Mensual'>Mesual</option>
                    <option value='Diario'>Diario</option>
                </select>
                <input class="nombre_sector_label" value="Estado" readonly="readonly">
                <select name='habilitado' id='estado' class="nombre_sector" required>
                    <option value='Activo'>Activo</option>
                    <option value='Deshabilitado'>Deshabilitado</option>
                </select>
                <button type="submit" id="finalizarVenta">Crear Tipo</button>
            </form>
        </div>
    </div>
    <script src="./js/nav-bar.js"></script>
    <script src="./js/top.js"></script>


</body>

</html>