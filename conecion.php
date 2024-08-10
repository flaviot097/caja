<?php $servername = "localhost:3307";
$usuario = "root";
$contrasena = "";
$dbname = "code_bar";

$connection = new mysqli($servername, $usuario, $contrasena, $dbname);
if ($connection->connect_error) {
    echo ("conecion fallida: " . $connection->connect_error);
}