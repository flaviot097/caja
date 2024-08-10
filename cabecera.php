<ul class="navbar-nav mx-auto">
    <li class="nav-item">
        <a href="session-close.php" class="nav-link"><?php if ($_SESSION["usuario"]) {
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