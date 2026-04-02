<?php ob_start();

require 'vendor/autoload.php';
require_once "conecion.php";

use Dompdf\Dompdf;
use Dompdf\Options;
date_default_timezone_set('America/Buenos_Aires');

session_start();

$dsn = 'mysql:host=localhost:3307;dbname=code_bar;';
try {
    $pdo = new PDO($dsn, $usuario, $contrasena);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'error al conectarse: ' . $e->getMessage();
    exit;
}
$consulataTodosProductos = "SELECT nombre_producto , precio ,codigo_barra FROM producto order by nombre_producto asc";
$stmt = $pdo->prepare($consulataTodosProductos);
$stmt->execute();
$lista_productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista Productos</title>
    <link rel="stylesheet" href="styles.css">
</head>
<?php

?>

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
    padding: 0mm;
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

.invoice-items {
    width: 100%;
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
    margin-right: 5%;
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
    width: 90%;
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
</style>

<body>
    <div class="invoice-container">
        <header>
            <div class="company-details">
                <h1>Nombre de la Empresa</h1>
                <p>Ruta 18- km26 - Espinillo Norte</p>
                <p>Teléfono: 155439860</p>
            </div>
            <div class="logo">
                <img src='http://localhost/santiago_pagina/images/mc.png' alt='MC'>
            </div>
        </header>

        <section class="invoice-details">
            <h2>Lista de productos</h2>
            <p><strong>Fecha:</strong> <?php echo date("d-m-y") ?></p>
        </section>


        <section class="invoice-items">
            <table>
                <thead>
                    <tr>
                        <th>Nombre Producto</th>
                        <th>Codigo barra</th>
                        <th>Precio</th>
                    </tr>
                </thead>
                <?php foreach ($lista_productos as $value) {
                    ?>
                <tbody>
                    <tr>

                        <td><?php echo $value["nombre_producto"]; ?></td>
                        <td><?php echo $value["codigo_barra"]; ?></td>
                        <td>$<?php echo $value["precio"]; ?></td>
                    </tr>
                </tbody>
                <?php
                }
                ; ?>

            </table>
        </section>
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
$dompdf->stream("lista_productos_local_pdf", array("Attachment" => true));
header("Location: stock-template.php");
exit();
?>