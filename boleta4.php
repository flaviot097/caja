<?php ob_start();

require 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura</title>
    <link rel="stylesheet" href="styles.css">
</head>
<?php
$cookie = $_COOKIE["imprimir"];
$json = json_decode($cookie);
$nombre_apellido = $json[0];
$pago = $json[1];
$descuento = json_decode($_COOKIE["descuentos"], true) ?? 0;
$total = 0;
$productos_caja = json_decode($_COOKIE["productos_caja"], true);
?>

<style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
    margin-bottom: -170px;
    text-overflow: ellipsis;
}

.invoice-container {
    width: 210mm;
    min-height: 297mm;
    padding: 20mm;
    margin: 10mm auto;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
    page-break-inside: avoid;
}

header {
    display: flex;

    align-items: center;
    margin-bottom: 20px;
}

header .company-details {
    text-align: left;
}

header .company-details h1 {
    margin: 0;
    font-size: 24px;
}

header .company-details p {
    margin: 5px 0;
}

header .logo img {
    max-width: 150px;
}

.invoice-details {
    text-align: center;
    margin-bottom: 20px;
}

.invoice-details h2 {
    margin: 0;
    font-size: 28px;
}

.invoice-details p {
    margin: 5px 0;
    text-aling: center;
}

.client-details {
    margin-bottom: 20px;
}

.client-details h3 {
    margin: 0 0 10px 0;
    font-size: 47px;
}

.client-details p {
    margin: 5px 0;
    font-size: 34px
}

.invoice-items table {
    margin-left: -60px;
    width: 90%;
    border-collapse: collapse;
    margin-bottom: 20px;
    min-height: 200px;
}

.invoice-items th,
.invoice-items td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
    font-size: 47px;
}

.invoice-items th {
    background-color: #f4f4f4;
}

.desc {
    width: 40%;
}

.invoice-items tfoot td {
    font-weight: bold;
}

footer {
    text-align: center;
    margin-top: 30px;
}

.total-venta {
    font-weight: bold;
    font-size: 36px;
}

footer p {
    margin: 0;
    font-size: 36px;
}

.company-details {
    font-size: 40px;
}

.texto-cliente {
    font-size: 30px;
}

#fecha {
    font-size: 40px;
}

.sub {
    font-size: 25px !important;
}

.cu {
    font-size: 25px !important;
}

.tr-cant {
    border: black solid 0.3px;
    width: 90% !important;
    margin-left: -60px;
    font-size: 47px;
}

.td-cant {
    border: black solid 0.3px;
    width: 50% !important;
}

.td-cant-prod {
    border: black solid 0.3px;
    width: 29%;
}

@page {
    margin: 0;
    size: auto 1500mm;
}


.tr-cant-total {
    border: black solid 0.3px;
    width: 90% !important;
    margin-left: -60px;
    font-size: 47px;
    height: 60px;
}
</style>

<body>
    <div class="invoice-container">
        <header>
            <div class="company-details">
                <p>Ruta 18- km26 - Espinillo Norte</p>
                <p>Teléfono: 155439860</p>
            </div>
            <div class="logo">
                <img src='http://localhost/santiago_pagina/images/mc.png' alt='MC'>
            </div>
        </header>

        <section class="invoice-details" id="fecha">
            <p><strong>Fecha:</strong> <?php echo date("d-m-y") ?></p>
        </section>

        <section class="client-details">
            <h3 class="texto-cliente">Cliente:</h3>
            <p><strong><?php echo $nombre_apellido; ?></strong></p>
            <p><strong><?php echo $pago; ?></strong></p>
        </section>

        <section class="invoice-items">
            <div class="tr-cant">
                Cant..............| Prod...
            </div>
            <table>
                <?php foreach ($productos_caja as $value) {
                    $total += $value["total"];
                    if ($value["nombre_producto"] !== "Producto") {
                        ?>

                <tbody>
                    <tr>
                        <td><?php echo $value["cantidad"]; ?></td>
                        <td style="width: 40px !important;
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;"><?php echo $value["nombre_producto"]; ?></td>
                    </tr>
                    <tr>
                        <td class="cu">Prec U</th>
                        <td class="sub">sub</th>
                    </tr>
                    <tr>
                        <td>$<?php echo $value["precio"]; ?></td>
                        <td>$<?php echo ($value["precio"] * $value["cantidad"]); ?></td>
                    </tr>
                </tbody>
                <?php }
                }
                ; ?>
                <tfoot>


                    <tr>
                        <td class="desc">Desc</td>
                        <td><?php echo $descuento; ?>%</td>
                    </tr>

                </tfoot>

            </table>
            <div class="tr-cant-total">
                Total..............| $<?php echo $total; ?>
            </div>

        </section>

        <footer>
            <p>Gracias por su compra!</p>
        </footer>
    </div>
</body>

</html>
<?php

$html = ob_get_clean();
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$options->set('isPhpEnabled', true);

$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);
$dompdf->setPaper('custom', 'portrait');
setcookie("productos_caja", "", time() - 3600, "/");
// Renderizar el PDF
$dompdf->render();

// Mostrar el PDF en el navegador
$dompdf->stream("factura_pdf", array("Attachment" => false)); ?>