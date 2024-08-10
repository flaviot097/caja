<?php
session_start();
require_once "conecion.php";
$select = "SELECT * FROM usuario";
$stmt = $connection->prepare($select);
$stmt->execute();

$result = $stmt->get_result();
$resp = $result->fetch_all();


$user = $_POST["usuario"];
$password = $_POST["password"];

foreach ($resp as $us) {
    if ($us[0] == $user && $us[1] == $password) {
        echo "Bienvenido $user";
        $_SESSION["usuario"] = $us[0];
        $_SESSION["password"] = $us[1];
        header("location: stock-template.php");
    }
}