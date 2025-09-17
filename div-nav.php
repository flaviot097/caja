<a href="session-close.php" class="nav-item"><?php if ($_COOKIE["usuario_caja"]) {
    echo "Salir";
} else {
    echo "Iniciar Sesión";
} ?>
    <a href="stock-template.php" class="nav-item active">Stock</a>
    <a href="template-stock-reparto.php" class="nav-item">Reparto</a>
    <a href="estadisticas.php" class="nav-item">Estadísticas</a>
    <a href="caja.php" class="nav-item">Caja</a>
    <a href="caja-reparto.php" class="nav-item">Caja Reparto</a>
    <a href="cuenta-corriente.php" class="nav-item">Fiado</a>
    <a href="egresos.php" class="nav-item">Egresos</a>