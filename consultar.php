<html lang="es">
<?php

require_once 'validacion-usuario.php';

require_once "conecion.php";
require_once "delete-element.php";
//require_once "cargar_cookie.php";

$url = $_SERVER['REQUEST_URI'];

$dsn = "mysql:host=localhost:3307;dbname=code_bar;";
try {
    $pdo = new PDO($dsn, $usuario, $contrasena);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

$resultado_data;

if ($_GET) {
    $producto = $_GET['nombre'] ?? '';
    $codigo = $_GET['codigoBarra'] ?? '';
    $resultado;

    if ($producto !== "") {
        $consulta = "SELECT * FROM producto WHERE nombre_producto = :nombre_producto";
        $stmt = $pdo->prepare($consulta);
        $stmt->bindParam(':nombre_producto', $producto);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    if ($codigo !== "") {
        $consulta = "SELECT * FROM producto WHERE codigo_barra = :codigo_barra";
        $stmt = $pdo->prepare($consulta);
        $stmt->bindParam(':codigo_barra', $codigo);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    if (count($resultado) !== 0) {
        $data = json_encode($resultado);
        setcookie("resultado_busca", $data, time() + 3600, "/");


        $resultado_data = $resultado;
    } else {
        echo "<script> alert('no se encontro el producto'); </script>";
    }

}
// Verificar si la cookie existe antes de intentar acceder a ella
if (isset($_COOKIE["resultado_busca"])) {
    $resultado_data = json_decode($_COOKIE["resultado_busca"], true);
}

?>



<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Productos</title>
    <style>
    /* Estilos para el modal */
    .modal {
        display: block;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.89);
    }

    .modal-content {
        background-color: #fff;
        margin: 10% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 300px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
    }

    input {
        width: 100%;
        padding: 8px;
        margin-bottom: 16px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    button {
        padding: 10px 15px;
        background-color: #FF5722;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    button:hover {
        background-color: rgb(0, 179, 75);
    }

    .nombre_product {
        color: #FF5722;
    }

    .cantidad_comprar {
        color: rgb(0, 68, 255);
    }
    </style>
</head>

<body>

    <!-- Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <h2>Buscar Producto</h2>
            <form method="get" action="" id="dataForm">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre">

                <label for="codigoBarra">Código de Barra:</label>
                <input type="text" id="codigoBarra" name="codigoBarra" autofocus>

                <button type="submit">buscar</button>
            </form>
            <form action="guardar-producto_cookies.php" method="post">
                <?php if (!empty($resultado_data)) {
                    foreach ($resultado_data as $items) {
                        $code = $items["codigo_barra"];
                        $name = $items["nombre_producto"];
                        $price = $items["precio"];
                        ?>
                <label for="nombre"><strong class="nombre_product"><?php echo $name; ?></strong></label>
                <input type="hidden" name="nombreP" id="nombreP" value="<?php echo $name; ?>">
                <input type="hidden" name="codigoBarra" id="codigoBarraP" value="<?php echo $code; ?>">
                <label for="precio">Precio</label>
                <input for="precio" name="precioP" id="precio" value="<?php echo $price; ?>">
                <label for="codigoBarra">Descuento: (%)</label>
                <input type="number" id="decuento" name="decuento">
                <label for="cantidad">Cantidad que puede comprar:</label>
                <b><span id="cantidad_compra" class="cantidad_comprar">0</span></b>
                <input type="hidden" name="cantidad" value="" id="cantidad_input">
                <label for="cantidad">Monto:</label>
                <input type="number" id="cantidad_monto" name="monto">
                <?php }
                }
                ; ?>

                <button type="">Agregar a caja</button>
            </form>

        </div>
    </div>

    <script>
    //redirigir a caja

    const modal = document.getElementById('myModal');
    const modalContent = document.querySelector('.modal-content');

    modal.addEventListener('click', (event) => {

        if (!modalContent.contains(event.target)) {

            window.location.href = "caja.php"
        }
    });

    // Obtener elementos del DOM
    const monto = document.getElementById('cantidad_monto');
    const cantidadCompra = document.getElementById("cantidad_compra");
    const cantidad_input = document.getElementById("cantidad_input");

    // Manejar el evento input en el campo de monto
    monto.addEventListener("input", function() {
        // Obtener el precio dinámicamente
        const precio = parseFloat(document.getElementById("precio").value);
        const montoValue = parseFloat(monto.value);

        // Validar que el precio y el monto sean números válidos
        if (!isNaN(montoValue) && !isNaN(precio) && precio > 0) {
            const cantidad = (montoValue / precio).toFixed(3);
            cantidadCompra.textContent = cantidad;
            cantidad_input.value = cantidad;


        } else {
            cantidadCompra.textContent = "0";
        }
    });
    </script>
</body>

</html>