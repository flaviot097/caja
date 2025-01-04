<?php
session_start();

require_once "conecion.php";
require_once "delete-element.php";
//require_once "cargar_cookie.php";

if (!empty($_COOKIE["fiados_todos"])) {
    setcookie("fiados_todos", "", time() - 3600, "/");
}
if (!empty($_COOKIE["imprimir"])) {
    setcookie("imprimir", "", time() - 3600, "/");
}


$dsn = "mysql:host=localhost:3307;dbname=code_bar;";
try {
    $pdo = new PDO($dsn, $usuario, $contrasena);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

// Función para eliminar un producto de la cookie
if (isset($_GET['eliminar']) && isset($_COOKIE["productos_caja"])) {
    $eliminar_codigo = $_GET['eliminar'];
    $productos_caja = json_decode($_COOKIE["productos_caja"], true);





    // Buscar y eliminar el producto de la lista
    $productos_actualizados = [];
    foreach ($productos_caja as $producto) {
        if ($producto['codigo_barra'] !== $eliminar_codigo) {
            $productos_actualizados[] = $producto;
        }
    }



    /////////////// cantidad indice
    $cantidad_eliminar = intval($_GET["indice_cantidad"]);
    $cant_pro_caja = json_decode($_COOKIE["cantidad_prod"], true);
    unset($cant_pro_caja[$cantidad_eliminar]);
    $cant_pro_caja = array_values($cant_pro_caja);
    $pdc = json_encode($cant_pro_caja);
    setcookie("cantidad_prod", $pdc, time() + 3600, "/");



    // Actualizar la cookie con la lista de productos modificada
    $productos_caja_json = json_encode($productos_actualizados);
    setcookie("productos_caja", $productos_caja_json, time() + 3600, "/");
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Agregar producto a la cookie si se envía por GET
if ($_GET) {
    $producto = $_GET['producto'] ?? '';
    $codigo = $_GET['codigo'] ?? '';
    $cantidad = $_GET['cantidad'] ?? 1;
    $descuento = $_GET["descuento"] ?? 0;

    //descuento
    if ($descuento !== 0) {
        $N_desc = "0." . $descuento;
        setcookie("descuentos", $N_desc, time() + 3600, "/");
    }

    // Verificar si la cookie "cantidad_prod" existe
    if (!isset($_COOKIE["cantidad_prod"])) {
        $lista_c_c = [];
    } else {
        // Decodificar el valor de la cookie existente
        $lista_c_c = json_decode($_COOKIE["cantidad_prod"], true);

        // Asegurarse de que la variable sea un array
        if (!is_array($lista_c_c)) {
            $lista_c_c = [];
        }
    }
    // Añadir la nueva cantidad al array
    $lista_c_c[] = $cantidad;

    // Codificar el array y guardar en la cookie
    $lista_c_c_json = json_encode($lista_c_c);
    setcookie("cantidad_prod", $lista_c_c_json, time() + 3600, "/");
    ########################



    if ($producto !== '') {
        $query = "SELECT nombre_producto, precio, codigo_barra FROM producto_reparto WHERE nombre_producto = :producto";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(":producto", $producto, PDO::PARAM_STR);
    } elseif ($codigo !== '') {
        $query = "SELECT nombre_producto, precio, codigo_barra FROM producto_reparto WHERE codigo_barra = :codigo";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(":codigo", $codigo, PDO::PARAM_STR);
    } else {
        $results = [];

    }

    if (isset($stmt)) {
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    if (!empty($results)) {
        $list = [
            'nombre_producto' => $results[0]["nombre_producto"],
            'precio' => $results[0]['precio'],
            'codigo_barra' => $results[0]['codigo_barra'],
            'cantidad' => $cantidad,
            'total' => $results[0]['precio'] * $cantidad
        ];

        if (isset($_COOKIE["productos_caja"])) {
            $productos_caja = json_decode($_COOKIE["productos_caja"], true);
            $productos_caja[] = $list;
            $productos_caja_json = json_encode($productos_caja);
            setcookie("productos_caja", $productos_caja_json, time() + 3600, "/");
            //

            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $productos_caja = [$list];
            $productos_caja_json = json_encode($productos_caja);
            setcookie("productos_caja", $productos_caja_json, time() + 3600, "/");
            //
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        }
    } else {
        echo "No se encontraron productos.";
    }

}

// Cargar productos desde la cookie
if (isset($_COOKIE["productos_caja"])) {
    $productos_caja = json_decode($_COOKIE["productos_caja"], true);
} else {
    $productos_caja = [];
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="caja reparto" content="" />

    <title>Caja Registradora</title>

    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/unicons.css" />
    <link rel="stylesheet" href="css/owl.carousel.min.css" />
    <link rel="stylesheet" href="css/owl.theme.default.min.css" />
    <link rel="stylesheet" href="css/target.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/caja.css">

    <!-- MAIN STYLE -->
    <link rel="stylesheet" href="css/tooplate-style.css" />
</head>
<style>
.pos-system {
    background-color: #5894bfed !important;
}
</style>

<body>
    <!-- MENU -->
    <nav class="navbar navbar-expand-sm navbar-light backgraund-header">
        <div class="container">
            <a class="navbar-brand" href="index.php"><i class="uil uil-user"></i></a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                <span class="navbar-toggler-icon"></span>
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <?php require_once "cabecera.php";
                ?>

                <ul class="navbar-nav ml-lg-auto">
                    <div class="ml-lg-4" id="icono">
                        <div class="color-mode d-lg-flex justify-content-center align-items-center">
                            <i class="color-mode-icon"></i>
                        </div>
                    </div>
                </ul>
            </div>
        </div>
    </nav>

    <div class="body-container">
        <div class="pos-system">
            <div class="header">
                <h2 class="text-caja-black">Caja Registradora Reparto</h2>
                <form action="" method="get">
                    <div class="input-foms-caja">
                        <input type="text" id="searchInput" placeholder="Buscar producto..." name="producto"
                            style="margin-right: 5px;">
                        <input type="text" id="searchInput" placeholder="cantidad de productos..." name="cantidad"
                            value="1">
                        <input type="text" id="searchInput" placeholder="Buscar codigo..." name="codigo"
                            style="margin-right: 5px;">
                        <input type="number" id="searchInput" placeholder="descuento..." name="descuento">
                    </div>

                    <button class="btn checkout-btn" type="submit">Agregar</button>
                </form>
            </div>
            <div class="form-conteiner-aling">
                <form class="content">
                    <div class="product-list">
                        <?php
                        $total_general = 0;
                        $total_descuento = 0;
                        $vuelta_cant = 0;


                        if (!empty($productos_caja)) {
                            foreach ($productos_caja as $clave) {
                                if ($clave['nombre_producto'] !== "Producto") {
                                    $total_general += $clave['total'];
                                    ?>
                        <div class="product">
                            <?php echo $clave['nombre_producto'] ?> |
                            <?php echo $clave['codigo_barra'] ?> | C/U $<?php echo $clave['precio'] ?> | Cantidad:
                            <?php echo $clave['cantidad'];

                                        ?>
                            <form action="" method="get">
                                <input type="hidden" name="eliminar" value="<?php echo $clave['codigo_barra']; ?>">
                                <input type="hidden" name="indice_cantidad" value="<?php echo $vuelta_cant;
                                            $vuelta_cant++;

                                            ?>">
                                <button type="submit">❌</button>
                            </form>
                        </div>
                        <?php } else {
                                    $total_general += $clave['total'];
                                    ?>
                        <div class="product">
                            <?php echo $clave['nombre_producto'] ?> |
                            <?php echo $clave['codigo_barra'] ?> | C/U $<?php echo $clave['precio'] ?> |
                            Cantidad:
                            <?php echo $clave['cantidad'] ?>
                            <form action="" method="get">
                                <input type="hidden" name="eliminar" value="<?php echo $clave['codigo_barra']; ?>">
                                <button type="submit" style="display: none;"></button>
                            </form>
                        </div>
                        <?php
                                }
                            }
                        }
                        ?>
                    </div>
                    <div class="cart">
                        <h2 class="text-caja-black">Carrito de Compras</h2>
                        <div class="cart-items">
                        </div>
                        <div class="cart-total">
                            <p>Total General: $<?php
                            if (isset($_COOKIE["descuentos"])) {
                                $resta_desc = json_decode($_COOKIE["descuentos"]);
                                $total_desc = $total_general - ($total_general * floatval($resta_desc));
                                $total_general = $total_desc;
                                echo $total_general;
                            } else {
                                echo $total_general;
                            }
                            ?>
                            </p>
                        </div>
                        <form action="finalizar-compra-reparto.php" method="post">
                            <input type="text" id="searchInput" placeholder="nombre y apellido..."
                                name="nombre_y_apelido" required>
                            <input type="text" id="searchInput" placeholder="DNI..." name="DNI" required>
                            <input type="number" id="searchInput" placeholder="entrega..." name="entregar_plata">
                            <input type="hidden" id="searchInput" value="<?php echo $total_general; ?>" name="total">
                            <select name="pago" id="searchInput">
                                <option value="efectivo" class="option-efectivo">💵 Efectivo</option>
                                <option value="trans" class="option-tarjeta">💳 Tarjeta</option>
                                <option value="entrega" class="option-fiar">Con entrega</option>
                                <option value="fiar" class="option-fiar">📝 Fiar</option>
                            </select>
                            <button class="btn checkout-btn" type="submit" style="margin-top: 5px;">Finalizar
                                Compra</button>
                        </form>
                        <div id="mensaje-caja"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php

    ?>
    <footer class="footer py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-12">
                    <p class="copyright-text text-center">
                        Copyright &copy; 2024. Todos los derechos reservados
                    </p>
                    <p class="copyright-text text-center">
                        Diseñado por <a rel="nofollow" href="">Flavio Trocello</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/Headroom.js"></script>
    <script src="js/jQuery.headroom.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/smoothscroll.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/mensaje-caja.js"></script>
    <script src="js/dark-mode.js"></script>

</body>

</html>