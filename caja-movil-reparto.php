<!DOCTYPE html>
<html lang="es">
<?php
require_once "validacion-usuario.php";
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caja Registradora Móvil</title>
    <link rel="stylesheet" href="./css/movil.css">
</head>

<body>
    <div class="reportes"><a class="atexto" href="caja-reparto.php">Atras</a></div>
    <div class="container">
        <h1>Caja Registradora</h1>
        <div class="form-container">
            <input type="text" id="nombreProducto" placeholder="Nombre del producto" required>
            <input type="text" id="codigoBarras" placeholder="Código de barras" autofocus required>
            <input type="number" id="cantidad" placeholder="Cantidad" value="1" required>
            <label for="Descuento total">Descuento a total (%)</label>
            <input type="number" id="descuento_total" placeholder="Descuento al total" value="0">
            <label for="Descuento">Descuento a Prodcuto (%)</label>
            <input type="number" id="decuento_uni" placeholder="Descuento al producto" value="0">
            <button id="agregarProducto">Agregar Producto</button>
        </div>
        <div class="lista-productos">
            <h2>Lista de Productos</h2>
            <ul id="lista"></ul>
        </div>
        <div class="total">
            <h3>Total: $<span id="totalVenta">0.00</span></h3>
        </div>
        <div class="acciones">
            <form action="finalizar-compra-movil.php" method="post">
                <input type="text" id="searchInput" placeholder="nombre y apellido..." name="nombre_y_apelido" required>
                <input type="text" id="searchInput" placeholder="DNI..." name="DNI" required>
                <input type="number" id="searchInput" placeholder="entrega..." name="entregar_plata">
                <input type="hidden" id="searchInputTotal" class="total_movil" value="" name="total">
                <input type="hidden" id="searchInput" value="movil" name="url">
                <select name="pago" id="searchInput">
                    <option value="efectivo" class="option-efectivo">💵 Efectivo</option>
                    <option value="trans" class="option-tarjeta">💳 Tarjeta</option>
                    <option value="entrega" class="option-fiar">Con entrega</option>
                    <option value="fiar" class="option-fiar">📝 Fiar</option>
                </select>
                <button type="submit" id="finalizarVenta">Finalizar Venta</button>
            </form>
        </div>
    </div>
    <script src="./js/mivil-caja.js"></script>
</body>

</html>