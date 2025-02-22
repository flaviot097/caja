<!DOCTYPE html>
<?php
require_once "validacion-usuario.php";

if ($_COOKIE["usuario_caja"] !== "a") {
    header("location: stock-template.php");
}

$status = "";

if ($_GET) {
    $user = $_GET['nombre'];
    $con = $_GET["contrasena"];

    require_once "conecion.php";

    $dsn = 'mysql:host=localhost:3307;dbname=code_bar;';
    try {
        $pdo = new PDO($dsn, $usuario, $contrasena);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo 'error al conectarse: ' . $e->getMessage();
        exit;
    }

    $consulta = "INSERT INTO usuario (usuario, contrasenia) VALUES ( :usuario, :contrasenia)";
    $stmt = $pdo->prepare($consulta);
    $stmt->bindParam(':usuario', $user, PDO::PARAM_STR);
    $stmt->bindParam(':contrasenia', $con, PDO::PARAM_STR);
    if ($stmt->execute()) {
        $status = "Usuario creado con exito";
    } else {
        $status = "Error al crear usuario";
    }

}

?>
<html lang="en">

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
    <link rel="stylesheet" href="css/cartas-fiado.css">

    <!-- MAIN STYLE -->
    <link rel="stylesheet" href="css/tooplate-style.css" />
</head>
<style>
.card-fiado {
    display: flex;
    flex-direction: column;
    align-items: center;
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
    <div class="mensaje" id="mensaje">

    </div>
    <div class="body-container">
        <div class="container-cartas-fiado">
            <form class="card-fiado" method="get">
                <p class="text-center"><strong>Usuario</strong> <br><input type="text" name="nombre">
                </p>
                <p class="text-center"><strong>Contraseña</strong> <br><input name="contrasena" type="text"></p>
                <button class="pay-button" type="submit">agregar usuario</button>
            </form>
            <p class="text-center"><?php echo $status;
            ?></p>
        </div>
    </div>
    <?php
    ?>

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
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/Headroom.js"></script>
    <script src="js/jQuery.headroom.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/smoothscroll.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/mensaje.js"></script>
    <script src="js/dark-mode.js"></script>
</body>

</html>