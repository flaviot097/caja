<?php
ob_start();

require 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

$dni = $_GET["dni-validate"];

require_once "conecion.php";
$dsn = "mysql:host=localhost:3307;dbname=code_bar;";
try {
    $pdo = new PDO($dsn, $usuario, $contrasena);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage();
}

$query = "SELECT * FROM fiado WHERE dni=:dni";
$statement = $pdo->prepare($query);
$statement->bindParam(":dni", $dni, PDO::PARAM_STR);
$statement->execute();
$todosFiados = $statement->fetchAll(PDO::FETCH_ASSOC);
//var_dump($todosFiados);
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
            <p class="title-detalle"><strong>Fecha:</strong> <?php echo date("d-m-y") ?></p>
        </section>

        <section class="client-details">
            <h3>Detalles del Cliente</h3>
            <p><strong>Nombre:</strong><?php echo $dni; ?></p>
        </section>

        <section class="invoice-items">
            <table><?php foreach ($todosFiados as $fiados) {
                $code_barra = $fiados["productos"];
                $saldo = $fiados["saldo"];
                $json_productos = json_decode($code_barra);
                $vuelta = 0;
                //var_dump($json_productos);
                //echo $json_productos[0];
                echo "<br>";

                foreach ($json_productos as $product) {

                    $consulta = "SELECT precio FROM producto WHERE codigo_barra = :codigo_barra";
                    $statement1 = $pdo->prepare($consulta);
                    $statement1->bindParam(":codigo_barra", $product, PDO::PARAM_STR);
                    $statement1->execute();
                    $todosFiados1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
                    //consulta nombre
                    $consulta1 = "SELECT nombre_producto FROM producto WHERE codigo_barra = :codigo_barra";
                    $statement2 = $pdo->prepare($consulta1);
                    $statement2->bindParam(":codigo_barra", $product, PDO::PARAM_STR);
                    $statement2->execute();
                    $nonmbre_p_fiado = $statement2->fetchAll(PDO::FETCH_ASSOC);
                    ///
                    //echo $fiados["saldo"];
                    $cantidad_productos_codigo = json_decode($fiados["cantidad"])[$vuelta];
                    //echo $todosFiados1[0]["precio"];
                    ?>
                <thead>
                    <tr>
                        <th>Cantidad</th>
                        <th>Procucto</th>
                        <th>Precio C/U</th>
                        <th>fecha</th>
                        <th>subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $cantidad_productos_codigo; ?></td>
                        <td><?php echo $nonmbre_p_fiado[0]["nombre_producto"]; ?></td>
                        <td>$<?php echo $todosFiados1[0]["precio"]; ?></td>
                        <td><?php echo ($fiados["fecha"]); ?></td>
                        <td>$<?php echo ($todosFiados1[0]["precio"] * $cantidad_productos_codigo);
                                $total += $todosFiados1[0]["precio"] * $cantidad_productos_codigo ?>
                        </td>
                    </tr>
                </tbody>
                <?php $vuelta++;
                }
            }
            ; ?>
                <tfoot>
                    <tr>
                        <td colspan="4">Saldo</td>
                        <td>$<?php echo $saldo; ?></td>
                    </tr>
                    <tr>
                        <td colspan="4"><strong>Total</strong></td>
                        <td><strong>$<?php echo $total + $saldo; ?></strong></td>
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