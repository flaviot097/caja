<!DOCTYPE html>
<html lang="es">
<?php
require_once "validacion-usuario.php";
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aumentar por proveedor</title>
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

    .form-input {
        width: 70%;
        height: 25px;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .form-input:focus {
        outline: none;
        border-color: #007bff;
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
                <div id="producto_spam_texto"><b>Proveedores</b></div>
                <div>
                    <?php
                    require_once "componente-card-prov.php";
                    ?>
                </div>
            </div>
        </div>
    </div>
    <script src="./js/pregunta_finalizaciones.js"></script>
    <script src="./js/nav-bar.js"></script>


</body>

<footer class="footer">
    <!-- <p>Copyright © 2024. Todos los derechos reservados</p> -->
    <p>Diseñado por <a href="#" class="footer-link">Flavio J. Trocello</a></p>
</footer>

</html>