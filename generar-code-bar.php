<?php
function generarCodigoBarra($longitud = 8)
{
    $caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $codigoBarra = '';
    for ($i = 0; $i < $longitud; $i++) {
        $codigoBarra .= $caracteres[rand(0, strlen($caracteres) - 1)];
    }
    return $codigoBarra;
}
function dibujarCodigoBarra($codigoBarra)
{
    // Crear una imagen en blanco
    $ancho = 200;
    $alto = 50;
    $imagen = imagecreate($ancho, $alto);

    // Colores
    $colorFondo = imagecolorallocate($imagen, 255, 255, 255); // Blanco
    $colorTexto = imagecolorallocate($imagen, 0, 0, 0); // Negro

    // Dibujar el código de barras (en este caso, simplemente el texto)
    $fuente = 5; // Tamaño de la fuente
    $x = ($ancho - imagefontwidth($fuente) * strlen($codigoBarra)) / 2;
    $y = ($alto - imagefontheight($fuente)) / 2;
    imagestring($imagen, $fuente, $x, $y, $codigoBarra, $colorTexto);

    // Enviar la cabecera y mostrar la imagen
    header('Content-Type: image/png');
    imagepng($imagen);
    imagedestroy($imagen);
}

$codigoBarra = generarCodigoBarra();
dibujarCodigoBarra($codigoBarra);