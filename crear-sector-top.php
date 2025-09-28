<?php

require_once "conecion.php";
$dsn = 'mysql:host=localhost:3307;dbname=code_bar;';
try {
    $pdo = new PDO($dsn, $usuario, $contrasena);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'error al conectarse: ' . $e->getMessage();
    exit;
}
$productos_cookie = $_COOKIE['productosTop'] ?? '';
if ($productos_cookie !== "") {
    $productos_cookie = json_decode($productos_cookie, true);
}

$idSector = $_POST['id'];
if ($idSector == "noone") {
    try {
        $crearSector = "INSERT INTO sectores_top SET nombre_sector_top = :nombre";
        $stmt = $pdo->prepare($crearSector);
        $stmt->bindParam(':nombre', $_POST['nombre_sector_top'], PDO::PARAM_STR);
        $stmt->execute();
        $id = $pdo->lastInsertId();
        crearItemtop(intval($id), $pdo, $productos_cookie);
        redirigirA("crear-top.php");
    } catch (PDOException $e) {
        echo 'Error ' . $e->getMessage();
        exit;
    }

} else {
    // $selecionarId = "SELECT id FROM sectores_top WHERE id = :id";
    // $stmt = $pdo->prepare($selecionarId);
    // $stmt->bindParam(':id', $_POST['nombre_sector_top'], PDO::PARAM_STR);
    // $stmt->execute();
    // $id = $stmt->fetch(PDO::FETCH_ASSOC);
    // if (count($id) > 0) {

    // }
    crearItemtop(intval($_POST['id']), $pdo, $productos_cookie);
    // foreach ($productos_cookie as $producto) {
    //     $nom = $producto['nombre_producto'];
    //     $cod = $producto['codigo_barra'];

    //     $AgregaListaItem = "INSERT INTO sectores_top_items (id_sector_top, nombre_producto, codigo_barra) VALUES (:id, :nombre, :cod)";
    //     $stmt = $pdo->prepare($AgregaListaItem);
    //     $stmt->bindParam(':id', $id['id'], PDO::PARAM_INT);
    //     $stmt->bindParam(':nombre', $nom, PDO::PARAM_STR);
    //     $stmt->bindParam(':cod', $cod, PDO::PARAM_STR);
    //     $stmt->execute();
    // }
}

function redirigirA($url)
{
    if (isset($_COOKIE['productosTop'])) {
        setcookie('productosTop', '', time() - 3600, '/');
        unset($_COOKIE['productosTop']);
    }
    header("Location: $url");
    exit();
}

function crearItemtop($idS, $pdo, $productos_cookie)
{
    foreach ($productos_cookie as $producto) {
        $nom = $producto['nombre_producto'];
        $cod = $producto['codigo_barra'];

        $AgregaListaItem = "INSERT INTO sectores_top_items (id_sector_top, nombre_producto, codigo_barra) VALUES (:id, :nombre, :cod)";
        $stmt = $pdo->prepare($AgregaListaItem);
        $stmt->bindParam(':id', $idS, PDO::PARAM_INT);
        $stmt->bindParam(':nombre', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':cod', $cod, PDO::PARAM_STR);
        $stmt->execute();
    }

    redirigirA("crear-top.php");
}

?>