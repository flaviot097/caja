<?php
/* composer require google/apiclient:^2.0 */
require 'vendor/autoload.php';

define('CREDENTIALS_PATH', 'credentials.json');
define('TOKEN_PATH', 'token.json');

// Inicializar Google Client
$client = new Google_Client();
$client->setAuthConfig(CREDENTIALS_PATH);
$client->addScope(Google_Service_Drive::DRIVE_FILE);
$client->setAccessType('offline');
$client->setPrompt('select_account consent');

// Intentar recuperar un token guardado
if (file_exists(TOKEN_PATH)) {
    $accessToken = json_decode(file_get_contents(TOKEN_PATH), true);
    $client->setAccessToken($accessToken);
}

// Si el token ha expirado, solicitar uno nuevo
if ($client->isAccessTokenExpired()) {
    if ($client->getRefreshToken()) {
        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
    } else {
        $authUrl = $client->createAuthUrl();
        printf("Abre el siguiente enlace en tu navegador:\n%s\n", $authUrl);
        $authCode = trim(fgets(STDIN));

        // Intercambiar el código de autenticación por un token de acceso
        $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
        $client->setAccessToken($accessToken);

        // Guardar el token para su uso futuro
        if (!file_exists(dirname(TOKEN_PATH))) {
            mkdir(dirname(TOKEN_PATH), 0700, true);
        }
        file_put_contents(TOKEN_PATH, json_encode($client->getAccessToken()));
    }
}

// Inicializar Google Drive Service
$driveService = new Google_Service_Drive($client);

// Crear un nuevo archivo en Google Drive
$fileMetadata = new Google_Service_Drive_DriveFile([
    'name' => 'nombre_de_tu_archivo.sql',
]);

$content = file_get_contents('ruta_de_tu_archivo.sql');

$file = $driveService->files->create($fileMetadata, [
    'data' => $content,
    'mimeType' => 'application/sql',
    'uploadType' => 'multipart',
    'fields' => 'id',
]);

printf("Archivo subido con ID: %s\n", $file->id);
?>




$productos_no_actualizables = json_encode(array());
$cantidada_cero = "0";
$stmtlp = $pdo->prepare($sql_entrega);
$stmtlp->bindParam(':dni', $dni, PDO::PARAM_STR);
$stmtlp->bindParam(':nombre_y_apellido', $nombre, PDO::PARAM_STR);
$stmtlp->bindParam(':productos', $productos_no_actualizables, PDO::PARAM_STR);
$stmtlp->bindParam(':saldo', $tot, PDO::PARAM_INT);
$stmtlp->bindParam(':cantidad', $cantidada_cero, PDO::PARAM_STR);
$stmtlp->bindParam(':fecha', $fecha_date, PDO::PARAM_STR);
if ($stmtlp->execute()) {

$list = [
'nombre_producto' => "Producto",
'precio' => 0,
'codigo_barra' => "codigo de barra",
'cantidad' => 1,
'total' => 0
];
$imprimir = json_encode([$_POST["nombre_y_apelido"], "efectivo", $value['total']]);
$productos_caja[] = $list;
$productos_caja_json = json_encode($productos_caja);
//setcookie("productos_caja", "", time() - 3600, "/");
//elimino cookies y genero mensaje

setcookie("mensaje", "exito", time() + 10, '/');
setcookie("imprimir", $imprimir, time() + 3600, "/");
setcookie("entrega_si", $restar_total, time() + 3600, "/");
header("location: factura-crear.php");
} else {
setcookie("mensaje", "fallo", time() + 10, '/');
header("location: caja.php");
}