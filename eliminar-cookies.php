<?php

session_start();
$url = $_COOKIE["url_location"];
setcookie("url_location", "", time() - 1200, "/");
setcookie("productos_caja", "", time() - 3600, "/");
$_SESSION["productos_caja"] = [];
if ($url == "/santiago_pagina/caja.php") {
    header("location: caja.php");

} elseif ($url === "movil") {
    header("location: caja-movil-reparto.php");
} else {
    header("location: caja-reparto.php");

}
?>