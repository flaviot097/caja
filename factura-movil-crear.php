<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>En qué formato desea imprimir</title>
    <link rel="stylesheet" href="styles.css">
</head>
<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 20px;
}

.container {
    max-width: 800px;
    margin: 0 auto;
    background: #fff;
    padding: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    color: #333;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
}

.form-group input {
    width: 100%;
    padding: 8px;
    box-sizing: border-box;
}

button {
    display: inline-block;
    padding: 10px 15px;
    color: #fff;
    background-color: #007BFF;
    border: none;
    cursor: pointer;
    text-decoration: none;
    font-size: 14px;
    margin-top: 10px;
}

button:hover {
    background-color: #0056b3;
}

.print-options {
    text-align: center;
    margin: 20px 0;
}

.print-options p {
    margin: 0;
    font-size: 16px;
    color: #333;
}

.print-options .button {
    margin: 0 10px;
    padding: 10px 20px;
    background-color: #007BFF;
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
}

.print-options .button:hover {
    background-color: #0056b3;
}

.table-container {
    margin-top: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

table th,
table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

table th {
    background-color: #f4f4f4;
    color: #333;
}

table tr:nth-child(even) {
    background-color: #f9f9f9;
}

table tr:hover {
    background-color: #ddd;
}

.delete {
    background-color: #dc3545;
}

.delete:hover {
    background-color: #c82333;
}
</style>
<?php
$cookie = $_COOKIE["imprimir"];
$json = json_decode($cookie);
$nombre_apellido = $json[0];
$pago = $json[1];

?>

<body>
    <div class="container">
        <h1>En qué formato desea imprimir</h1>
        <div class="print-options">
            <p>:</p>
            <a href="boleta-movil.php" class="button">A4</a>
            <a href="boleta-movil4.php" class="button">Mini impresora</a>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $json[0]; ?></td>
                        <td>
                            <a href="boleta-movil.php" class="button">A4</a>
                            <a href="boleta-movil4.php" class="button">Mini impresora</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <a href="eliminar-cookies.php" class="button">volver a caja</a>
    </div>
</body>

</html>