<?php
ob_start();
require 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

$cookie = $_COOKIE["imprimir"];
$json = json_decode($cookie);
$nombre_apellido = $json[0];
$pago = $json[1];
$descuento = json_decode($_COOKIE["descuentos"], true) ?? 0;
$total = 0;
$productos_caja = json_decode($_COOKIE["productos_caja"], true);

//Opciones para Dompdf

?>

<!DOCTYPE html>
<html lang='es'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Factura</title>
</head>
<style>
body {
    width: 80px;
    height: 100%;
    border: 0.2px solid black;
}

.factura {
    font-size: 3.5px
}

.info {
    margin-top: -1px;
    margin-botton: -1px;
    padding: -2px;
}

.encabezado {
    display: flex;
    justify-content: space-between;
}

.info-empresa,
.info-cliente {
    width: 95%;
}

.img {
    padding: 0.2px;
    width: 60%;
}


table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    border: 0.2px solid black;
    padding: 0.2px;
    text-align: center;
}

.totales {
    text-align: right;
}

.detalle-factura {
    margin-top: -2px;
}
</style>

<body>
    <div class='factura'>
        <div class='encabezado'>
            <img class='img' src='http://localhost/santiago_pagina/images/mc.png' alt='MC'>
            <div class='info-empresa'>
                <p class='info'>Ruta 18- km26 - Espinillo Norte</p>
                <p class='info'>155439860</p>
            </div>
            <div class='info-cliente'>
                <p class='info'>Cliente: $nombre_apellido</p>
                <p class='info'>Método de pago: <?php echo $pago ?></p>
            </div>
        </div>
        <div class='detalle-factura'>
            <table>
                <?php foreach ($productos_caja as $value) {
                    $total += $value["total"];
                    if ($value["nombre_producto"] !== "Producto") {
                        ?>
                <thead>
                    <tr>
                        <th>Cantidad</th>
                        <th>Produto</th>
                        <th>Precio C/U</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $value["cantidad"]; ?></td>
                        <td><?php echo $value["nombre_producto"]; ?></td>
                        <td>$<?php echo $value["precio"]; ?></td>
                        <td>$<?php echo ($value["precio"] * $value["cantidad"]); ?></td>
                    </tr>

                </tbody><?php }
                }
                ; ?>
                <tfoot>
                    <tr>
                        <td colspan="3">Descuento</td>
                        <td><?php echo $descuento; ?>%</td>
                    </tr>
                    <tr>
                        <td colspan="3"><strong>Total</strong></td>
                        <td><strong>$<?php echo $total; ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>

</html>

<?php
$html = ob_get_clean();
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
//$options->set('dpi', 300); // Ajuste de DPI para mejor calidad y precisión

$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);

// Configurar el tamaño del papel
$width = 40 * 2.83465; // 1 mm = 2.83465 puntos
$height = 57 * 2.83465;
$dompdf->setPaper([0, 0, $width, $height], 'portrait');
setcookie("productos_caja", "", time() - 3600, "/");
// Renderizar el PDF
$dompdf->render();

// Mostrar el PDF en el navegador
$dompdf->stream("factura_pdf", array("Attachment" => false)); ?>