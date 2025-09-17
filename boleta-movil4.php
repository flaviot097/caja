<?php

ob_start();
session_start();

require 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;
date_default_timezone_set('America/Buenos_Aires');

$entrega_si = 0; // Valor predeterminado
if (isset($_COOKIE["entrega_si"])) {
    $temp_entrega = $_COOKIE["entrega_si"];
    if (is_numeric($temp_entrega)) {
        $entrega_si = floatval($temp_entrega);
    } else {
        // Manejar el caso donde la cookie no es un número
        $entrega_si = 0;
    }
}

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
$cookie;
if ($_COOKIE["imprimir"]) {
    $cookie = $_COOKIE["imprimir"];
}

$json = json_decode($cookie);
$nombre_apellido = $json[0];
$pago = $json[1];
$vendedor = $json[3];

$descuento = 0;
if (isset($_COOKIE["descuentos"])) {
    $descuento = $_COOKIE["descuentos"];
}
$total = 0;
$productos_caja = $_COOKIE["productos_caja"];
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

    .invoice-items {
        width: 80%
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
                <img src='http://caja-app-mc.online/santiago_pagina/mc.png' alt='MC'>
            </div>
        </header>

        <section class="invoice-details">
            <h2>Factura</h2>
            <p><strong>Fecha:</strong> <?php echo date("d-m-y") ?></p>
        </section>

        <section class="client-details">
            <h3>Detalles del Cliente</h3>
            <p><strong>Nombre:</strong><?php echo $nombre_apellido; ?></p>
            <p><strong>Método de pago:</strong><?php echo $pago; ?></p>
            <p><strong>Vendedor:</strong> <?php echo $vendedor ?></p>
        </section>

        <section class="invoice-items">
            <table><?php foreach ($productos_caja as $value) {
                $total += $value["total"];
                if ($value["nombre_producto"] !== "Producto") {
                    ?>
                        <thead>
                            <tr>
                                <th>Cantidad</th>
                                <th>Procucto</th>
                                <th>Precio C/U</th>
                                <th>subtotal</th>
                                <th>Decuento Prodcuto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo $value["cantidad"]; ?></td>
                                <td><?php echo $value["nombre_producto"]; ?></td>
                                <td>$<?php echo $value["precio"]; ?>         <?php if (isset($value["precio_sim"])) { ?>
                                        <?php echo ("($" . $value["precio_sim"] . ")");
                                            } ?>
                                </td>
                                <td>$<?php echo ($value["precio"] * $value["cantidad"]); ?></td>
                                <?php if (isset($value["precio_sim"])) { ?>
                                    <td><?php echo ($value["decuento_u"]); ?>%</td>
                                    <?php ;
                                } else { ?>
                                    <td><?php echo "0"; ?>%</td>
                                    <?php ;
                                } ?>
                            </tr>
                        </tbody>
                    <?php }
            }
            ; ?>
                <tfoot>
                    <tr>
                        <td colspan="4">Descuento al total</td>
                        <td><?php echo $descuento; ?>%</td>
                    </tr>
                    <tr>
                        <td colspan="4">Entrega $<?php echo $entrega_si;
                        if ($entrega_si !== 0) { ?> | Saldo
                                $<?php echo ($total - $entrega_si);
                        } ?></td>

                    </tr>
                    <tr>
                        <td colspan="4"><strong>Total</strong></td>
                        <td><strong>$<?php echo $total; ?></strong></td>
                    </tr>
                </tfoot>
            </table>
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

$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);
$dompdf->setPaper("A4", 'portrait');
//setcookie("productos_caja", "", time() - 3600, "/");
//setcookie("cantidad_prod", "", time() - 3600, "/");

// Renderizar el PDF
$dompdf->render();

// Mostrar el PDF en el navegador
$dompdf->stream("factura_pdf", array("Attachment" => false));

try {
    $usuario_venta = $_COOKIE["usuario_caja"];
    $consulta = "DELETE FROM temporal WHERE usuario=:usuario";
    $stmtemporal = $pdo->prepare($consulta);
    $stmtemporal->bindParam(":usuario", $usuario_venta, PDO::PARAM_STR);
    $stmtemporal->execute();

} catch (PDOException $er) {
    echo $er->getMessage();
}
?>