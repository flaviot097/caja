<!DOCTYPE html>
<?php
session_start();

require_once "conecion.php";
$dsn = 'mysql:host=localhost:3307;dbname=code_bar;';
try {
    $pdo = new PDO($dsn, $usuario, $contrasena);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'error al conectarse: ' . $e->getMessage();
    exit;
}
$total_hoy = 0;
$color = "";
date_default_timezone_set('America/Buenos_Aires');
$hoy = date("Y-m-d");
$consulataTodosProveedores = "SELECT DISTINCT proveedor FROM producto";
$stmt = $pdo->prepare($consulataTodosProveedores);
$stmt->execute();
$todosProveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<html lang="en">
<style>
th,
td,
tr {
    border: solid black 0.5px;
    text-align: center;
}
</style>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="Flavio Trocello" content="" />

    <title>pagina de gestion</title>

    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/unicons.css" />
    <link rel="stylesheet" href="css/owl.carousel.min.css" />
    <link rel="stylesheet" href="css/owl.theme.default.min.css" />
    <link rel="stylesheet" href="css/target.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/estadisticas.css">
    <link rel="stylesheet" href="css/graficas.css">

    <!-- MAIN STYLE -->
    <link rel="stylesheet" href="css/tooplate-style.css" />
</head>

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
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a href="session-close.php" class="nav-link"><?php if ($_COOKIE["usuario_caja"]) {
                            echo "Salir";
                        } else {
                            echo "Iniciar Sesión";
                        } ?></span>
                        </a>

                    </li>
                    <li class="nav-item">
                        <a href="stock-template.php" class="nav-link">Stock</a>
                    </li>
                    <li class="nav-item">
                        <a href="template-stock-reparto.php" class="nav-link">Reparto</a>
                    </li>
                    <li class="nav-item">
                        <a href="estadisticas.php" class="nav-link">Estadisticas</a>
                    </li>
                    <li class="nav-item">
                        <a href="caja.php" class="nav-link">Caja</a>
                    </li>
                    <li class="nav-item">
                        <a href="caja-reparto.php" class="nav-link">Caja Reparto</a>
                    </li>
                    <li class="nav-item">
                        <a href="cuenta-corriente.php" class="nav-link">Fiado</a>
                    </li>
                    <li class="nav-item">
                        <a href="egresos.php" class="nav-link">Egresos</a>
                    </li>
                </ul>

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
    <div class="sidebar">
        <h2 class="text-filter">Filtrar Productos</h2>
        <form action="" method="get">
            <label for="usaer" class="form-control" style="border-color: transparent; background: #f8f9fa;">Seleccione
                Proveedor</label>
            <select name="proveedor" id="usaer" class="form-control">
                <option value="">Seleccione proveedor</option>
                <?php foreach ($todosProveedores as $selectproveedor) { ?>
                <option value="<?php echo $selectproveedor["proveedor"]; ?>">
                    <?php echo $selectproveedor["proveedor"]; ?>
                </option>
                <?php }
                $total = 0;
                ?>
            </select>
            <input type="submit" value="Consultar Productos" class="btn btn-primary mt-3" />
            <!-- <a href="estadisticas-fecha.php"></a><button>Consultar por fecha</button></a> -->
        </form>
    </div>
    <!-- PROJECTS -->
    <section class="project py-5" id="project">
        <div class="container">
            <div class="row">
                <div class="container-btn-actions"><a href="ordenar-stock-local.php" class="btn checkout-btn"
                        id="btnFiltrar-menor">Ordenar por
                        stock</a>
                    <a href="crear-producto.php" class="btn checkout-btn" id="btnFiltrar-menor"
                        style="cursor: pointer;">Agregar Producto</a>
                    <a href="editar-departamento-local.php" class="btn checkout-btn" id="btnFiltrar-menor"
                        style="cursor: pointer;">Editar por Departamento</a>
                    <a href="template-backup.php" class="btn checkout-btn" id="btnFiltrar-menor"
                        style="cursor: pointer;">Backup</a>
                    <a href="generar-code-bar.php" class="btn checkout-btn" id="btnFiltrar-menor"
                        style="cursor: pointer;">Crear C.Barra</a>
                    <a href="cargar-masivamente-local.php" class="btn checkout-btn" id="btnFiltrar-menor"
                        style="cursor: pointer; margin-top: 2.3px">Cargar Masivamente</a>
                    <?php if ($_COOKIE["usuario_caja"] == "a") { ?>
                    <a href="crear_usuario.php" class="btn checkout-btn" id="btnFiltrar-menor"
                        style="cursor: pointer; margin-top: 2.3px">Crear usuario</a>
                    <?php } ?>

                </div>
                <div class="productos-stock">
                    <?php
                    if ($_GET) {

                        $count_vuelta = 0;

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
                    
                        $proveedor = $_GET['proveedor'] ?? '';

                        if ($proveedor === '') {
                            echo "Se debe completar al menos un campo para realizar la búsqueda.";
                            exit;
                        } else {

                            // Construir la consulta base
                            $query = "SELECT * FROM producto WHERE proveedor = :proveedor ORDER BY stock ASC";
                        }

                        // Preparar la consulta con PDO
                        $stmt = $pdo->prepare($query);

                        // Vincular los parámetros según corresponda
                    

                        $stmt->bindValue(':proveedor', $proveedor, PDO::PARAM_STR);




                        // Ejecutar la consulta
                        $stmt->execute();

                        // Obtener los resultados si los hay
                        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        // Mostrar los resultados
                        if ($resultados) {
                            foreach ($resultados as $item) {
                                $count_vuelta = $count_vuelta + $item["stock"];
                                $color = "";
                                if (floatval($item["stock"]) > $item["num_stock"] && floatval($item["stock"]) > ($item["num_stock"] + 6)) {
                                    $color = "green";
                                } elseif (floatval($item["stock"]) < $item["num_stock"]) {
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
</a><form action='eliminar-producto.php' method='get' class='eliminar-producto eliminar-prod eliminate-prod' >
<input type='hidden' name='codigo_B' value=" . $item["codigo_barra"] . ">
<button style='height: 100%; type='submit' class='eliminar-prod' ><img class='eliminar-img' src='images/eliminar.png' alt='eliminar' ></button></form>
<div class='contenedor-formularios'><form  method='Post' class='editar-producto' id=" . $item["codigo_barra"] . " action='editar-p_l.php'><label for='ingrese stock a editar' class='label-producto' >Editar</label>
<input type='hidden' name='codigo_B' value=" . $item["codigo_barra"] . ">
<input type='hidden' name='stock' value=" . $item["stock"] . ">
<input class='input-producto' type='text' value=" . $item["stock"] . " name='editar_stock_prod'><button class='btm-submit' type='submit'>Stock</button>
</form>
<form class='editar-producto' action='template-editar-prod-l.php' method='post'>
<input type='hidden' name='codigo_B' value=" . $item["codigo_barra"] . ">
<button class='productos-editar' type='submit'>Producto</button></form></div>
</div> ";
                            }
                            echo "<br><div class='contador-container'> Total stock: $count_vuelta productos</div><br>";
                        } else {
                            echo "<p>No se encontraron resultados.</p>";
                        }
                    } else {
                        require_once "funcion-estadisticas-proveedores.php";
                    }

                    ?>

                </div>
            </div>
        </div>
    </section>
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p>¿Quiere eliminar este articulo del stock?.</p>
            <button class="eliminar-btn-modals" id="button-modal">Eliminar</button>
        </div>
    </div>
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
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Captura todos los elementos <p> con la clase 'eliminar'
        const eliminarElements = document.querySelectorAll('.eliminate-prod');


        eliminarElements.forEach(function(eliminarElement) {
            eliminarElement.addEventListener('submit', function(e) {
                e.preventDefault();
                // Obtén el id del elemento clickeado
                const codigoBarra = this.id;
                var modal = document.getElementById("myModal");
                var span = document.getElementsByClassName("close")[0];

                modal.style.display = "block";
                // Cuando se hace clic en <span> (x), se cierra el modal
                span.onclick = function() {
                    modal.style.display = "none";
                }
                // Cuando se hace clic fuera del modal, se cierra
                window.onclick = function(event) {
                    if (event.target == modal) {
                        modal.style.display = "none";
                    }
                }

                Escucharbtneliminar(this)
                // Redirige a eliminar-producto.php con el parámetro codigo_eliminar
                // window.location.href = 'eliminar-producto-reparto.php?codigo_eliminar=' +
                //     encodeURIComponent(codigoBarra);
            });
        });
    });




    function Escucharbtneliminar(evento) {
        const btn_eliminar = document.getElementById("button-modal")
        btn_eliminar.addEventListener("click", function() {
            if (btn_eliminar) {
                evento.submit();
            }
        })

    }
    </script>


    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/Headroom.js"></script>
    <script src="js/jQuery.headroom.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/smoothscroll.js"></script>
    <script src="js/custom.js"></script>
    <script src="./js/cartas-prod-stock.js"></script>
    <script src="./js/stock.js"></script>
    <script src="./js/dark-mode.js"></script>
</body>

</html>