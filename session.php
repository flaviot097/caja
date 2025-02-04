<?php
session_start();
require_once "conecion.php";

$dsn = "mysql:host=localhost:3307;dbname=code_bar;";
try {
    $pdo = new PDO($dsn, $usuario, $contrasena);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage();
}

$user = $_POST["usuario"];
$password = $_POST["password"];


$select = "SELECT contrasenia FROM usuario WHERE usuario = :usuario";
$stmt = $pdo->prepare($select);
$stmt->bindParam(":usuario", $user, PDO::PARAM_STR);
$stmt->execute();


$validacion = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($validacion) == 0) {
    echo "El usuario no existe";
}
if ($validacion[0]["contrasenia"] === $password) {
    echo "Bienvenido $user";
    $_SESSION["usuario"] = $user;
    $_SESSION["password"] = $password;
    setcookie("usuario_caja", $user, time() + 18000, "/");
    header("location: caja.php");
} else {
    header("location: redireccion-login.php");
}