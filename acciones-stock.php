<div class="action-buttons">
    <button class="btn btn-success"><a class="btn-button-stock-actions" href="ordenar-stock-local.php">Ordenar por
            stock</a></button>
    <button class="btn btn-success"><a class="btn-button-stock-actions" href="crear-producto.php"> Agregar
            Producto </a></button>
    <button class="btn btn-success"> <a class="btn-button-stock-actions" href="editar-departamento-local.php">
            Editar
            por Departamento
        </a></button>
    <button class="btn btn-success"><a class="btn-button-stock-actions" href="template-backup.php"> Backup
        </a></button>
    <button class="btn btn-success"><a class="btn-button-stock-actions" href="generar-code-bar-anual.php">
            Borrar registros anuales </a>
    </button>
    <button class="btn btn-success"> <a class="btn-button-stock-actions" href="generar-code-bar.php"> Crear
            C.Barra </a></button>
    <button class="btn btn-success"> <a class="btn-button-stock-actions" href="sectores-top.php"> Reportes
            Ventas
        </a></button>
    <button class="btn btn-success"> <a class="btn-button-stock-actions" href="cargar-masivamente-local.php"> Cargar
            Masivamente
        </a></button>
    <?php if ($_COOKIE["usuario_caja"] == "a") { ?>
    <button class="btn btn-success"> <a class="btn-button-stock-actions" href="crear_usuario.php"> Crear
            usuario </a></button>
    <?php } ?>
    <?php if ($_SERVER['REQUEST_URI'] == "/santiago_pagina/sectores.php") {
                ?>
    <form action="crear-sector.php" method="post">
        <button class="btn btn-success">Crear Sector</button>
    </form>
    <?php }
        ; ?>
</div>