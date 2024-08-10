<!DOCTYPE html>
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
    <link rel="stylesheet" href="css/form-template.css" />

    <!-- MAIN STYLE -->
    <link rel="stylesheet" href="css/tooplate-style.css" />
</head>

<body>
    <!-- MENU -->
    <nav class="navbar navbar-expand-sm navbar-light backgraund-header">
        <div class="container">
            <a class="navbar-brand" href="index.php"><i class="uil uil-user"></i></a>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                </ul>

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

    <!-- PROJECTS -->
    <section id="formulario-conteiner">
        <div class="container-formulario">
            <div class="row">
                <div class="col-lg-11 text-center mx-auto col-12">
                    <div class="owl-carousel owl-theme">
                        <div class="item">
                            <div class="project-info">
                                <div class="img-fluid" alt="project image">
                                    <div class="">
                                        <form id="contactus" action="session.php" method="post">
                                            <h3 class="titulo">Iniciar Sesion</h3>

                                            <fieldset>
                                                <input placeholder="usuario" type="text" tabindex="1" required autofocus
                                                    name="usuario" />
                                            </fieldset>
                                            <fieldset>
                                                <input placeholder="Contraseña" type="password" tabindex="1"
                                                    name="password" required />
                                                <fieldset>
                                                    <button name="submit" type="submit" id="contactus-submit"
                                                        data-submit="...valindando">
                                                        Iniciar sesion
                                                    </button>
                                                </fieldset>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
    <script src="./js/cartas-prod-stock.js"></script>
    <script src="./js/stock.js"></script>
</body>

</html>