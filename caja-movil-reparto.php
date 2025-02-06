<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caja Registradora Móvil</title>
    <link rel="stylesheet" href="./css/movil.css">
</head>

<body>
    <div class="container">
        <h1>Caja Registradora</h1>
        <div class="form-container">
            <input type="text" id="nombreProducto" placeholder="Nombre del producto" required>
            <input type="text" id="codigoBarras" placeholder="Código de barras" required>
            <input type="number" id="cantidad" placeholder="Cantidad" min="1" required>
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
            <form action="finalizar-compra.php" method="post">
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