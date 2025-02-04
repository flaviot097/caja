<?php
ob_start();

require 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;
date_default_timezone_set('America/Buenos_Aires');
$dni = $_GET["dni-validate"];

require_once "conecion.php";
$dsn = "mysql:host=localhost:3307;dbname=code_bar;";
try {
    $pdo = new PDO($dsn, $usuario, $contrasena);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage();
}


$saldo = 0;
$total = 0;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle</title>
    <link rel="stylesheet" href="styles.css">
</head>
<style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

.invoice-container {
    width: 210mm;
    min-height: 297mm;
    padding: 20mm;
    margin: 10mm auto;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
}

header {
    display: flex;
    justify-content: space-between;
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
}

.client-details {
    margin-bottom: 20px;
}

.client-details h3 {
    margin: 0 0 10px 0;
    font-size: 20px;
}

.client-details p {
    margin: 5px 0;
}

.invoice-items table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

.invoice-items th,
.invoice-items td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

.invoice-items th {
    background-color: #f4f4f4;
}

.invoice-items tfoot td {
    font-weight: bold;
}

footer {
    text-align: center;
    margin-top: 20px;
}

footer p {
    margin: 0;
    font-size: 16px;
}

.invoice-items {
    width: 70% !important;
}

.title-detalle {
    width: 70%;
}
</style>

<body>
    <div class="invoice-container">
        <header>
            <div class="company-details">
                <h1>MC</h1>
                <p>Ruta 18- km26 - Espinillo Norte</p>
                <p>Teléfono: 155439860</p>
            </div>
            <div class="logo">
                <img src='http://localhost/santiago_pagina/images/mc.png' alt='MC'>
            </div>
        </header>

        <section class="invoice-details">
            <h2 class="title-detalle">Detalle</h2>
            <p class="title-detalle"><strong>Fecha:</strong>
                <?php date_default_timezone_set('America/Buenos_Aires');
                echo date("d-m-y") ?>
            </p>
        </section>

        <section class="client-details">
            <h3>Detalles del Cliente</h3>
            <p><strong>Nombre:</strong><?php echo $_GET["name-validate"]; ?></p>
            <p><strong>DNI:</strong><?php echo $_GET["dni-validate"]; ?></p>
        </section>

        <section class="invoice-items">
            <table>
                <thead>
                    <tr>
                        <th>Cantidad</th>
                        <th>Procucto</th>
                        <th>Precio C/U</th>
                        <th>fecha</th>
                        <th>subtotal</th>
                    </tr>
                </thead>
                <?php

                $total_forheach = 0;

                $saldo_total = 0;

                $query = "SELECT * FROM fiado WHERE dni = :dni";
                $statement = $pdo->prepare($query);
                $statement->bindParam(":dni", $_GET["dni-validate"], PDO::PARAM_INT);
                $statement->execute();
                $persona_fiado = $statement->fetchAll(PDO::FETCH_ASSOC);


                $array_persona = [];


                $globalDNI = $_GET["dni-validate"];
                $consulta_saldo = "SELECT saldo FROM saldos WHERE dni=:dni";
                $statement_saldo = $pdo->prepare($consulta_saldo);
                $statement_saldo->bindParam(":dni", $globalDNI, PDO::PARAM_INT);
                $statement_saldo->execute();
                if ($saldo = $statement_saldo->fetchAll(PDO::FETCH_ASSOC)) {
                    if (count($saldo) > 1) {
                        foreach ($saldo as $mas_de_uno) {
                            $saldo_total = $saldo_total + $mas_de_uno["saldo"];
                        }
                    } else {
                        $saldo_total = $saldo[0]["saldo"];
                    }
                }

                if (count($persona_fiado) !== 0) {
                    $nombre_Y_apellido = $persona_fiado[0]["nombre_y_apellido"];

                    foreach ($persona_fiado as $fiado) {
                        $consultar_stock = "SELECT precio , nombre_producto FROM producto WHERE codigo_barra = :codigo_barra";
                        $stmtconsulta_s = $pdo->prepare($consultar_stock);
                        $stmtconsulta_s->bindParam(':codigo_barra', $fiado["productos"], PDO::PARAM_STR);
                        $stmtconsulta_s->execute();
                        $resultado_productos = $stmtconsulta_s->fetchAll(PDO::FETCH_ASSOC);
                        $precioUnitario = floatval($resultado_productos[0]["precio"]);
                        $nombre_producto = $resultado_productos[0]["nombre_producto"];
                        $cantidad = floatval($fiado["cantidad"]);
                        $fecha = $fiado["fecha"];
                        $subtotal = $precioUnitario * $cantidad;
                        $total_forheach = $total_forheach + $subtotal;


                        ?>
                <tbody>
                    <tr>
                        <td><?php echo $cantidad; ?></td>
                        <td><?php echo $nombre_producto; ?></td>
                        <td>$<?php echo $precioUnitario; ?></td>
                        <td><?php echo $fecha; ?></td>
                        <td>$<?php echo $subtotal; ?>
                        </td>
                    </tr>
                </tbody>

                <?php
                    }

                }
                ?>
                <tr>
                    <td colspan="4">Saldo</td>
                    <td>$<?php echo $saldo_total; ?></td>
                </tr>
                <tr>
                    <td colspan="4"><strong>Total</strong></td>
                    <td><strong>$<?php echo $saldo_total + $total_forheach; ?></strong></td>
                </tr>
                </tfoot>
            </table>
        </section>
    </div>
</body>


</html>

</html>
<?php

$html = ob_get_clean();
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);
$dompdf->setPaper("A4", 'portrait');

// Renderizar el PDF
$dompdf->render();

// Mostrar el PDF en el navegador
$dompdf->stream("detalle_pdf", array("Attachment" => false)); ?>