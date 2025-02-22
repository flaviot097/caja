<?php
session_start();
require_once 'validacion-usuario.php';

require_once "conecion.php";
require_once "delete-element.php";
//require_once "cargar_cookie.php";

$url = $_SERVER['REQUEST_URI'];

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
    $descuento_unitario = $_GET["descuento_prod"] ?? "";
    $descuento_uni = 1;


    if ($descuento_unitario !== "") {
        $descuento_uni = floatval(intval($descuento_unitario) / 100);
    }

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
        $query = "SELECT nombre_producto, precio, codigo_barra FROM producto WHERE nombre_producto = :producto";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(":producto", $producto, PDO::PARAM_STR);
    } elseif ($codigo !== '') {
        $query = "SELECT nombre_producto, precio, codigo_barra FROM producto WHERE codigo_barra = :codigo";
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
        $list;
        if ($descuento_unitario !== "") {

            $list = [
                'nombre_producto' => $results[0]["nombre_producto"],
                'precio' => $results[0]['precio'] - ($results[0]['precio'] * $descuento_uni),
                'codigo_barra' => $results[0]['codigo_barra'],
                'cantidad' => $cantidad,
                'total' => floatval($results[0]['precio'] - ($results[0]['precio'] * $descuento_uni)) * $cantidad,
                "decuento_u" => $descuento_unitario,
                "precio_sim" => $results[0]['precio']
            ];
        } else {
            $list = [
                'nombre_producto' => $results[0]["nombre_producto"],
                'precio' => $results[0]['precio'] * $descuento_uni,
                'codigo_barra' => $results[0]['codigo_barra'],
                'cantidad' => $cantidad,
                'total' => floatval($results[0]['precio'] * $descuento_uni) * $cantidad,
            ];
        }

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
    <meta name="Flavio Trocello" content="" />

    <title>Caja Registradora</title>

    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/unicons.css" />
    <link rel="stylesheet" href="css/owl.carousel.min.css" />
    <link rel="stylesheet" href="css/owl.theme.default.min.css" />
    <link rel="stylesheet" href="css/target.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/caja.css">
    <link rel="stylesheet" href="css/estadisticas.css">
    <link rel="stylesheet" href="css/graficas.css">

    <!-- MAIN STYLE -->
    <link rel="stylesheet" href="css/tooplate-style.css" />
</head>
<style>
.body-container {
    background-color: #333333;
    /* Color oscuro de fondo */
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    max-width: 800px;
    margin: 34px auto;
}

.btn.checkout-btn {
    background-color: #FF5722;
    /* Un color naranja para el botón */
    color: #FFFFFF;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
}

.btn.checkout-btn:hover {
    background-color: #E64A19;
    /* Un tono más oscuro al hacer hover */
}

#searchInput,
#cantidad_input {
    background-color: #444444;
    color: #FFFFFF;
    border: 1px solid #555555;
    border-radius: 5px;
    padding: 10px;
    margin-bottom: 10px;
}

.product-list .product {
    background-color: #3E3E3E;
    /* Fondo más oscuro para los productos */
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 10px;
    color: #FFFFFF;
}

.container-data-product div {
    margin-right: 10px;
    font-weight: bold;
}

#searchInput::placeholder {
    color: #AAAAAA;
    /* Color más claro para los placeholders */
}

#cantidad_input::placeholder {
    color: #AAAAAA;
}

.footer {
    background-color: #2B2B2B;
    color: #CCCCCC;
    padding: 20px 0;
    text-align: center;
    font-size: 14px;
}

.footer a {
    color: #FF5722;
    text-decoration: none;
}

.footer a:hover {
    text-decoration: underline;
}

.div-de-prod {
    width: 12%;
}

.headroom--not-bottom {
    min-width: 1100px !important;
}

.numero_total {
    font-size: 27px;
    font-weight: 600;
    line-height: 1.5;
    color: black;
    background-color: #e9c192 !important;
    width: min-content;
}

.contenedor-total-divs {
    display: flex;
    justify-content: flex-end;
}

.total_general {
    color: black;
    font-size: x-large;
}


/* Modal */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    justify-content: center;
    align-items: center;
}

.modal-content {
    background: #2c2c2c;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
}

.modal button {
    margin: 10px;
}

.cancel-btn {
    background: #dc3545;
}

.cancel-btn:hover {
    background: #a71d2a;
}
</style>


<body>
    <!-- MENU -->
    <nav class="navbar navbar-expand-sm navbar-light backgraund-header" style="min-width: 1200px !important;">
        <div class="container">
            <a class="navbar-brand" href="index.php"><i class="uil uil-user"></i></a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                <span class="navbar-toggler-icon"></span>
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <?php require_once "cabecera.php"; ?>

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
    <!-- BARRA LATERAL -->
    <div class="body-container">
        <div class="pos-system">
            <div class="header">
                <h2 class="text-caja-black">Caja Registradora</h2>
                <form action="" method="get" id="myForm">
                    <div class="input-foms-caja">
                        <input type="text" id="searchInput" placeholder="Buscar producto..." name="producto"
                            style="margin-right: 5px;">
                        <input type="text" class="searchInput" placeholder="cantidad de productos..." name="cantidad"
                            value="1" id="cantidad_input" style="width: 30%; margin-right: 5px;">
                        <input type="text" id="searchInput" placeholder="Buscar codigo..." name="codigo"
                            style="margin-right: 5px;" autofocus>
                        <input type="number" id="searchInput" placeholder="descuento total..." name="descuento">
                        <input type="number" id="searchInput" placeholder="descuento producto..." name="descuento_prod">

                    </div>

                    <button class="btn checkout-btn" type="submit" id="openModal">Agregar</button>
                    <a href="consultar.php"><button type="button" class="btn checkout-btn"
                            id="consult">Consultar</button></a>
                </form>

            </div>
            <div class="form-conteiner-aling" style="flex-direction: column;">
                <form class="content" style="width: 100%;">
                    <div class="product-list">
                        <?php
                        $total_general = 0;
                        $total_descuento = 0;
                        $vuelta_cant = 0;


                        if (!empty($productos_caja)) {
                            foreach ($productos_caja as $clave) {
                                if ($clave['nombre_producto'] !== "Producto") {
                                    $precio_uni = $clave["precio"];
                                    $total_general += intval($clave['total']);
                                    ?>
                        <div class="product">
                            <div class="container-data-product" style="display: flex;
                            flex-direction: row;
                            flex-wrap: wrap;
                            width: 91%;
                            justify-content: space-between;">
                                <div style="width: 100%; display: contents;">
                                    <div class="div-de-prod"><?php echo $clave['nombre_producto'] ?> </div>
                                    <div class="div-de-prod"><?php echo $clave['codigo_barra'] ?> </div>
                                    <div style="width: 2%;" class="div-de-prod" id="precio_producto"> C/U
                                        $<?php if (isset($clave["precio_sim"])) {
                                                        echo $clave["precio_sim"];
                                                    } else {
                                                        echo $precio_uni;
                                                    } ?>
                                    </div>
                                    <div style="width: 2%;" class="div-de-prod"> Sub
                                        $<?php
                                                    echo (intval($precio_uni * ($clave["cantidad"])));
                                                    ?>
                                    </div>
                                    <div style="width: 2%;" class="div-de-prod">
                                        <?php if (isset($clave["decuento_u"])) {
                                                        echo "Desc: ";
                                                        echo ($clave["decuento_u"]);
                                                    } ?>%
                                    </div>
                                    <div id="cantidad-producto"><?php echo $clave['cantidad']; ?></div>
                                </div>
                            </div>
                            <form action="" method="get">
                                <input type="hidden" name="eliminar" value="<?php echo $clave['codigo_barra']; ?>">
                                <input type="hidden" name="indice_cantidad" value="<?php echo $vuelta_cant;
                                            $vuelta_cant++; ?>">
                                <button style="width: 35px; border-radius: 24%; height: 100%" type="submit">❌</button>
                            </form>
                        </div>
                        <?php } else {
                                    $total_general += intval($clave['total']);
                                    ?>
                        <div class="product ">
                            <div class="container-data-product" style="display: flex;
                        flex-direction: row;
                        flex-wrap: wrap;
                        width: 91%;
                        justify-content: space-between;font-weight: bold;">
                                <div style="font-weight: bold;"><?php echo $clave['nombre_producto'] ?> </div>
                                <div style="font-weight: bold;"><?php echo $clave['codigo_barra'] ?> </div>
                                <div> C/U
                                    <?php echo $clave['precio'] ?>
                                </div>
                                <div style="width: 2%;" class="div-de-prod"> Sub
                                    $<?php echo (intval($clave["precio"] * ($clave["cantidad"]))) ?>
                                </div>
                                <div style="width: 2%;" class="div-de-prod">
                                    <?php if (isset($clave["decuento_u"])) {
                                                    echo "Desc: ";
                                                    echo ($clave["decuento_u"]);
                                                } ?>%
                                </div>
                                Cantidad:
                                <?php echo $clave['cantidad'];
                                            ?>
                            </div>
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

                        <div class="cart-total">
                            <div class="contenedor-total-divs">
                                <p class="total_general">Total General:
                                <p class="numero_total">$<?php
                                if (isset($_COOKIE["descuentos"])) {
                                    $resta_desc = json_decode($_COOKIE["descuentos"]);
                                    $total_desc = $total_general - ($total_general * floatval($resta_desc));
                                    $total_general = $total_desc;
                                    echo $total_general;
                                } else {
                                    echo $total_general;
                                }
                                ?></p>
                                </p>
                            </div>
                        </div>
                        <form action="finalizar-compra.php" method="post" style="margin-left: -3%;">
                            <input type="text" id="searchInput" placeholder="nombre y apellido..."
                                name="nombre_y_apelido" required>
                            <input type="text" id="searchInput" placeholder="DNI..." name="DNI" required>
                            <input type="number" id="searchInput" placeholder="entrega..." name="entregar_plata">
                            <input type="hidden" id="searchInput" value="<?php echo $total_general; ?>" name="total">
                            <input type="hidden" id="searchInput" value="<?php echo $url; ?>" name="url">
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

    <div class="modal" id="confirmModal">
        <div class="modal-content">
            <h3>Cantidad productos</h3>
            <input type="text" id="cantidad_productos_modal" name="cantidad" placeholder="1" autofocus>
            <button id="confirmAction">Confirmar</button>
            <button class="cancel-btn" id="closeModal">Cancelar</button>
        </div>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function() {

        //funcionalidad modal
        const form = document.getElementById("myForm");
        const openModal = document.getElementById("openModal");
        const confirmModal = document.getElementById("confirmModal");
        const confirmAction = document.getElementById("confirmAction");
        const closeModal = document.getElementById("closeModal");

        function cargar() {
            var cantidadModal = document.getElementById("cantidad_productos_modal");
            var cantidadInput = document.getElementById("cantidad_input");
            var valorModal = cantidadModal.value;
            console.log(valorModal);
            if (!cantidadModal || !cantidadInput) {

            }
            console.log(cantidadInput.value);
            if (valorModal === "") {
                cantidadModal.value = "1";
            } else {
                cantidadInput.value = valorModal;
            }
            // si apreto enter se envia el formulario

            confirmModal.style.display = "none";
            form.submit(); // Envía el formulario al confirmar
        }

        openModal.addEventListener("click", function(event) {
            event.preventDefault(); // Evita el envío del formulario inmediato
            confirmModal.style.display = "flex";
            var cantidadModal = document.getElementById("cantidad_productos_modal");
            cantidadModal.focus()
            document.addEventListener("keypress", (e) => {
                if (e.key == "Enter") {
                    console.log(e.key)
                    cargar()
                }
            })
        });

        closeModal.addEventListener("click", function() {
            confirmModal.style.display = "none";
        });

        confirmAction.addEventListener("click", function() {
            cargar()
        });
    });
    </script>
    <?php

    ?>
    <footer class="footer py-5" style="min-width: 1200px !important;display: flex ;justify-content: space-around;">
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
    <script src="./js/dark-mode.js"></script>

</body>

</html>