<!DOCTYPE html>
<html lang="es">
<?php
require_once "validacion-usuario.php";
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sectores Top</title>
    <link rel="stylesheet" href="./css/sectores.css">
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

.main-content {
    display: flex;
    justify-content: center;
    width: 100%;
}

#crear-sector-top {
    background-color: #717a7b;
}

.red {
    background-color: #982424;
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
        <?php require_once "acciones-stock.php";

        ?>
    </main>
    <div class="container">
        <div class="acciones">

            <div class="products-container">
                <a class="btn-button-stock-actions" href="crear-top.php">
                    <button class="btn btn-success" id="crear-sector-top">Crear sector</button>
                </a>
                <div id="producto_spam_texto"><b>Reportes de venta mensual por sectores</b></div>
                <div>
                    <div class='product-card'>

                        <div class='product-info'>
                            <div class='product-detail'>
                                <span class='label'>Sector:</span>
                                <span class='value green'>General</span>
                            </div>
                        </div>
                        <div class='product-actions'>
                            <form class='product-actions' method='POST' action='reportes-sectores-top.php'>
                                <select name='mes' id='turno' required>
                                    <option value='<?php $mesCorriente = date('m');
                                    echo $mesCorriente;
                                    ?>' selected>
                                        <?php $mesCorriente = date('m');
                                        echo " - $mesCorriente - ";
                                        ?>
                                    </option>
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
                                <input type='hidden' name='id_sector' value='0'>
                                <input type='hidden' name='nombre_sector' value='todos'>
                                <button class='btn btn-edit'>Generar Reporte</button>
                            </form>
                        </div>
                    </div>
                    <?php
                    require_once "componente-tabla-sectores-top.php";
                    ?>
                </div>
            </div>
        </div>
    </div>
    <script src="./js/nav-bar.js"></script>


</body>

<footer class="footer">
    <!-- <p>Copyright © 2024. Todos los derechos reservados</p> -->
    <p>Diseñado por <a href="#" class="footer-link">Flavio J. Trocello</a></p>
</footer>

</html>