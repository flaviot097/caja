<!DOCTYPE html>
<?php
session_start();
?>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="Flavio Trocello" content="" />

    <title>Inicio</title>

    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/unicons.css" />
    <link rel="stylesheet" href="css/owl.carousel.min.css" />
    <link rel="stylesheet" href="css/owl.theme.default.min.css" />
    <link rel="stylesheet" href="css/target.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/stock-template.css">

    <!-- MAIN STYLE -->
    <link rel="stylesheet" href="css/tooplate-style.css" />
</head>

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
                <?php require_once "cabecera.php";
                ?>

                <ul class="navbar-nav ml-lg-auto">
                    <div class="ml-lg-4">
                        <div class="color-mode d-lg-flex justify-content-center align-items-center">
                            <i class="color-mode-icon"></i>
                        </div>
                    </div>
                </ul>
            </div>
        </div>
    </nav>
    <!-- BARRA LATERAL -->
    <div class="sidebar">
        <h2 class="text-filter">Filtrar Productos</h2>
        <form action="" method="get" class="form-filtro">
            <label for="nombre">Nombre del Producto</label>
            <input type="text" id="nombre" name="nombre_producto" class="form-control">

            <label for="departamento">Departamento</label>
            <input type="text" id="departamento" name="departamento" class="form-control">

            <label for="proveedor">Proveedor</label>
            <input type="text" id="proveedor" name="proveedor" class="form-control">

            <button type="submit" class="btn btn-primary mt-3">Filtrar</button>
            <?php if ($_GET) {
                ?><a href="stock-template.php" class="btn btn-primary mt-3">Eliminar filtros</a><?php }
            ;
            ?>
        </form>
    </div>
    <!-- PROJECTS -->
    <section class="project py-5" id="project">
        <div class="container">
            <div class="row">
                <a href="stock-template.php" class="btn checkout-btn" id="btnFiltrar-menor">Ordenar por fecha</a>
                <a href="crear-producto.php" class="btn checkout-btn" id="btnFiltrar-menor"
                    style="cursor: pointer;">Agregar Producto</a>
                <div class="productos-stock">
                    <?php
                    if ($_GET) {

                        require_once "conecion.php";

                        $dsn = 'mysql:host=localhost:3307;dbname=code_bar;';
                        try {
                            $pdo = new PDO($dsn, $usuario, $contrasena);
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        } catch (PDOException $e) {
                            echo 'error al conectarse: ' . $e->getMessage();
                            exit;
                        }

                        // Obtener valores de los parámetros GET
                        $nombre_producto = $_GET['nombre_producto'] ?? '';
                        $departamento = $_GET['departamento'] ?? '';
                        $proveedor = $_GET['proveedor'] ?? '';

                        if ($nombre_producto === '' && $departamento === '' && $proveedor === '') {
                            echo "Se debe completar al menos un campo para realizar la búsqueda.";
                            exit;
                        }

                        // Construir la consulta base
                        $query = "SELECT * FROM producto WHERE 1=1";

                        // Preparar la consulta según los campos proporcionados
                        if ($nombre_producto !== '') {
                            $query .= " AND nombre_producto LIKE :nombre_producto";
                        }
                        if ($departamento !== '') {
                            $query .= " AND departamento LIKE :departamento";
                        }
                        if ($proveedor !== '') {
                            $query .= " AND proveedor LIKE :proveedor";
                        }

                        // Preparar la consulta con PDO
                        $stmt = $pdo->prepare($query);

                        // Vincular los parámetros según corresponda
                        if ($nombre_producto !== '') {
                            $stmt->bindValue(':nombre_producto', '%' . $nombre_producto . '%', PDO::PARAM_STR);
                        }
                        if ($departamento !== '') {
                            $stmt->bindValue(':departamento', '%' . $departamento . '%', PDO::PARAM_STR);
                        }
                        if ($proveedor !== '') {
                            $stmt->bindValue(':proveedor', '%' . $proveedor . '%', PDO::PARAM_STR);
                        }

                        // Ejecutar la consulta
                        $stmt->execute();

                        // Obtener los resultados si los hay
                        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        // Mostrar los resultados
                        if ($resultados) {
                            foreach ($resultados as $item) {
                                $color = "";
                                if (intval($item["stock"]) > $item["num_stock"] && intval($item["stock"]) > ($item["num_stock"] + 6)) {
                                    $color = "green";
                                } elseif (intval($item["stock"]) < $item["num_stock"]) {
                                    $color = "red";
                                }
                                ;
                                echo "<div class='producto-stock' id=" . $item["codigo_barra"] . ">
<a href='#' class='btn btn-primary btn-sm d-inline-flex align-items-center'>" . $item["nombre_producto"] . "<h6
        class='codigo-producto' id='codigo-producto'>codigo de producto: " . $item["codigo_barra"] . "</h6>
    <h6 class='cantidad-producto " . $color . "' id='cantidad-producto' name=" . $item["stock"] . "> Cantidad:" . $item["stock"] . " Unidades
    <h6 class='codigo-producto' id='proveedor-producto' name='proveedor'>Proveedor :" . $item["proveedor"] . "</h6>
    <h6 class='codigo-producto' id='depto-producto' name='dpartamento'>" . $item["departamento"] . "</h6>
    </h6>
    <h6 class='codigo-producto' id='costo-producto' name='costo'>costo: $" . $item["costo"] . "</h6>
    <h6 class='codigo-producto' id='ganancia-producto' name='ganancia'>ganancia: " . $item["ganancia"] . "%</h6>
    <h6 class='codigo-producto' id='precio-producto' name='precio'>Precio final: $" . $item["precio"] . "</h6>
</a><a class='eliminar-producto' href='eliminar-producto.php'><p class='eliminar' id=" . $item["codigo_barra"] . "><img class='eliminar-img' src='images/eliminar.png' alt='eliminar'></p></a>
<form  method='Post' class='editar-producto' id=" . $item["codigo_barra"] . " action='editar-p_l.php'><label for='ingrese stock a editar' class='label-producto' >Stock a editar</label>
<input type='hidden' name='codigo_B' value=" . $item["codigo_barra"] . ">
<input type='hidden' name='stock' value=" . $item["stock"] . ">
<input class='input-producto' type='number' value=" . $item["stock"] . " name='editar_stock_prod'><button type='submit'>Editar</button></form>
</div> ";
                            }
                        } else {
                            echo "<p>No se encontraron resultados.</p>";
                        }
                    } else {
                        require_once "funcion-ordenar.php";
                    }

                    ?>

                </div>
            </div>
        </div>
    </section>

    <footer class="footer py-5" style="min-width: 1200px !important;">
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
    <script>
    $(document).ready(function() {
        if ($(window).width() <= 768) {
            $('#sidebar').addClass('collapse');
        }

        $(window).resize(function() {
            if ($(window).width() <= 768) {
                $('#sidebar').addClass('collapse');
            } else {
                $('#sidebar').removeClass('collapse');
            }
        });
    });
    </script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/Headroom.js"></script>
    <script src="js/jQuery.headroom.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/smoothscroll.js"></script>
    <script src="js/custom.js"></script>
    <script src="./js/cartas-prod-stock.js"></script>
    <script src="./js/stock.js"></script>
</body>

</html>