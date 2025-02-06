<?php
require_once "conecion.php";
$dsn = "mysql:host=localhost:3307;dbname=code_bar;";

try {
    // Conexión a la base de datos usando PDO
    $pdo = new PDO($dsn, $usuario, $contrasena);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $codigoBarras = $_GET['codigo_barra'] ?? null;
    $nombre = $_GET['nombre'] ?? null;

    if ($codigoBarras || $nombre) {
        // Consulta SQL para buscar el producto por código de barras o nombre
        $sql = "SELECT nombre_producto, codigo_barra, precio FROM producto WHERE ";
        $params = [];

        if ($codigoBarras) {
            $sql .= "codigo_barra = :codigo_barra";
            $params[':codigo_barra'] = $codigoBarras;
        } elseif ($nombre) {
            $sql .= "nombre_producto LIKE :nombre";
            $params[':nombre'] = "%$nombre%";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        // Obtener el resultado de la consulta
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($producto) {
            // Si se encuentra el producto, devolverlo como JSON
            header('Content-Type: application/json');
            echo json_encode($producto);
        } else {
            // Si no se encuentra el producto, devolver un error silencioso
            http_response_code(404);
            echo json_encode(["error" => "Producto no encontrado"]);
        }
    } else {
        // Si no se proporciona el parámetro 'codigo_barras', devolver un error
        http_response_code(400);
        echo json_encode(["error" => "Falta el parámetro 'codigo_barras'"]);
    }
} catch (PDOException $e) {
    // Manejo de errores de la base de datos
    http_response_code(500);
    echo json_encode(["error" => "Error en la base de datos: " . $e->getMessage()]);
}
?>