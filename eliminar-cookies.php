<?php
$url = $_COOKIE["url_location"];
setcookie("url_location", "", time() - 1200, "/");
setcookie("productos_caja", "", time() - 3600, "/");
if ($url == "/santiago_pagina/caja.php") {
    header("location: caja.php");

} else {
    header("location: caja-reparto.php");

}
?>