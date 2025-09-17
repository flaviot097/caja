<?php

ob_start();
session_start();

require 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;
date_default_timezone_set('America/Buenos_Aires');


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Remito</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        font-size: 11px;
        margin: 0;
        padding: 0;
    }

    .page {
        width: 210mm;
        height: 297mm;
        margin: auto;
        padding: 10mm;
        box-sizing: border-box;
        border: 1px solid #000;
    }

    .header {
        width: 100%;
        border-bottom: 1px solid #000;
    }

    .header td {
        vertical-align: top;
        padding: 2mm;
    }

    .header .logo {
        font-size: 20px;
        font-weight: bold;
    }

    .header .right {
        text-align: right;
        font-size: 12px;
    }

    .nro {
        border: 1px solid #000;
        padding: 2mm 5mm;
        display: inline-block;
        margin: 2mm 0;
        font-weight: bold;
    }

    .cliente,
    .datos-iva {
        width: 100%;
        border-collapse: collapse;
        margin-top: 2mm;
        font-size: 11px;
    }

    .cliente td,
    .datos-iva td {
        border: 1px solid #000;
        padding: 2mm;
    }

    .remito {
        width: 100%;
        border-collapse: collapse;
        margin-top: 3mm;
    }

    .remito th,
    .remito td {
        border: 1px solid #000;
        padding: 2mm;
        height: 10mm;
        text-align: left;
    }

    .remito th {
        text-align: center;
        font-weight: bold;
    }

    .firma {
        margin-top: 10mm;
        display: flex;
        justify-content: space-between;
        font-size: 10px;
    }

    .firma div {
        width: 48%;
    }

    .transportista {
        margin-top: 10mm;
        border-top: 1px solid #000;
        padding-top: 5mm;
        font-size: 11px;
    }

    .transportista p {
        margin: 2mm 0;
    }
    </style>
</head>

<body>
    <div class="page">
        <!-- ENCABEZADO -->
        <table class="header">
            <tr>
                <td style="width:70%;">
                    <div class="logo">Te Con</div>
                    <div>
                        Hormigón Elaborado<br>
                        Construcción y Servicios<br>
                        Viviendas llave en mano<br>
                        Movimientos de suelo<br>
                        Ruta 32 - Km. 32 - Parque Industrial Viale<br>
                        Tel: (0343) 4927067 / 156456096<br>
                        (3109) Viale - E.R.<br>
                        <b>I.V.A. Responsable Inscripto</b>
                    </div>
                </td>
                <td class="right" style="width:30%;">
                    <b>DOCUMENTO NO VÁLIDO COMO FACTURA</b><br>
                    <div class="nro">N° 0002 - 00003401</div><br>
                    Fecha: ____/____/______
                </td>
            </tr>
        </table>

        <!-- DATOS CLIENTE -->
        <table class="cliente">
            <tr>
                <td>Señor:</td>
                <td colspan="3">________________________________________</td>
            </tr>
            <tr>
                <td>Domicilio:</td>
                <td colspan="3">________________________________________</td>
            </tr>
            <tr>
                <td>Localidad:</td>
                <td>____________________</td>
                <td>Teléf:</td>
                <td>____________________</td>
            </tr>
            <tr>
                <td>IVA:</td>
                <td>Resp. Inscripto ☐ C. Final ☐ Resp. Monotributo ☐ Exento ☐ No Resp. ☐</td>
                <td>CUIT:</td>
                <td>____________________</td>
            </tr>
        </table>

        <!-- TABLA REMITO -->
        <table class="remito">
            <thead>
                <tr>
                    <th style="width:20%;">ARTÍCULO</th>
                    <th style="width:60%;">DESCRIPCIÓN</th>
                    <th style="width:20%;">CANTIDAD</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <!-- PIE -->
        <div class="firma">
            <div><i>Agregado de agua en obra bajo responsabilidad del director o encargado</i></div>
            <div>Firma y Sello: __________________________</div>
        </div>

        <!-- TRANSPORTISTA -->
        <div class="transportista">
            <b>Datos del Transportista:</b>
            <p>Nombre: ______________________________________________</p>
            <p>Datos del vehículo: ____________________________________</p>
            <p>Chofer: _______________________________________________</p>
            <p>Lugar de entrega: _____________________________________</p>
            <p>Fecha: ____/____/______ &nbsp;&nbsp; Hora: ___________</p>
            <p>Observaciones: ________________________________________</p>
        </div>
    </div>
</body>

</html>


<?php

$html = ob_get_clean();
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);
$dompdf->setPaper("A4", 'portrait');
//setcookie("productos_caja", "", time() - 3600, "/");
//setcookie("cantidad_prod", "", time() - 3600, "/");

// Renderizar el PDF
$dompdf->render();

// Mostrar el PDF en el navegador
$dompdf->stream("factura_pdf", array("Attachment" => false));


?>