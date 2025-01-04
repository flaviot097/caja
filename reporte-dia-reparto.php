<section class="project py-5" id="project">
    <div class="container-back">
        <div class="row">
            <?php

            require_once "conecion.php";
            $dsn = "mysql:host=localhost:3307;dbname=code_bar;";
            try {
                $pdo = new PDO($dsn, $usuario, $contrasena);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }

            $fechaS = $_GET["datetime"];
            $hora = $_GET["turno"];
            $desde;
            $hasta;

            if ($hora === "manana") {
                $desde = "00:00:00";
                $hasta = "12:30:00";
            } elseif ($hora === "tarde") {
                $desde = "12:31:00";
                $hasta = "21:00:00";
            }

            $query = "SELECT * FROM reparto_reporte WHERE DATE(fecha) = '$fechaS' AND TIME(hora) BETWEEN '$desde' AND '$hasta'";

            $statement = $pdo->prepare($query);
            $statement->execute();
            $todosVendidos = $statement->fetchAll(PDO::FETCH_ASSOC);

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
                        <h3>Detalles de Ventas</h3>
                        <p><strong>Reparto</strong></p>
                        <p><strong>Fecha de consulta:</strong> <?php echo $fechaS; ?></p>
                    </section>

                    <section class="invoice-items">
                        <table>
                            <thead>
                                <tr>
                                    <th>Cantidad</th>
                                    <th>Procucto</th>
                                    <th>Codigo</th>
                                    <th>fecha</th>
                                    <th>horario</th>
                                </tr>
                            </thead>
                            <?php foreach ($todosVendidos as $vendido) {
                                $code_barra = $vendido["codigoBarra"];
                                $hora = $vendido["hora"];
                                $fecha_vendido = $vendido["fecha"];
                                //var_dump($json_productos);
                                //echo $json_productos[0];
                                echo "<br>";



                                //consulta nombre
                                $consulta1 = "SELECT nombre_producto FROM producto_reparto WHERE codigo_barra = :codigo_barra";
                                $statement2 = $pdo->prepare($consulta1);
                                $statement2->bindParam(":codigo_barra", $code_barra, PDO::PARAM_STR);
                                $statement2->execute();
                                $nonmbre_p_vendido = $statement2->fetchAll(PDO::FETCH_ASSOC);
                                ///
                                //echo $fiados["saldo"];
                                $cantidad_productos = $vendido["cantidad"];
                                //echo $todosFiados1[0]["precio"];
                                if ($cantidad_productos !== 0) { ?>

                            <tbody>
                                <tr>
                                    <td><?php echo $cantidad_productos; ?></td>
                                    <td><?php echo $nonmbre_p_vendido[0]["nombre_producto"]; ?></td>
                                    <td><?php echo $code_barra; ?></td>
                                    <td><?php echo $fecha_vendido; ?></td>
                                    <td><?php echo $hora; ?>
                                    </td>
                                </tr>
                            </tbody>
                            <?php
                                }

                            }
                            ; ?>
                        </table>
                    </section>
                </div>
            </body>

            </html>

            </html>
        </div>
    </div>
</section>