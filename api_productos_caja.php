<?php
require_once "conecion.php";
$dsn = "mysql:host=localhost:3307;dbname=code_bar;";

try {
    // Conexión a la base de datos usando PDO
    $pdo = new PDO($dsn, $usuario, $contrasena);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $nombre = $_GET['nombre'] ?? null;

    if ($nombre) {
        // Consulta SQL para buscar productos por nombre
        $sql = "SELECT nombre_producto, codigo_barra, precio FROM producto_reparto WHERE nombre_producto LIKE :nombre";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':nombre' => "%$nombre%"]);

        // Obtener todos los resultados
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($productos) {
            // Si se encuentran productos, devolverlos como JSON
            header('Content-Type: application/json');
            echo json_encode($productos);
        } else {
            // Si no se encuentran productos, devolver un error silencioso
            http_response_code(404);
            echo json_encode(["error" => "No se encontraron productos"]);
        }
    } else {
        // Si no se proporciona el parámetro 'nombre', devolver un error
        http_response_code(400);
        echo json_encode(["error" => "Falta el parámetro 'nombre'"]);
    }
} catch (PDOException $e) {
    // Manejo de errores de la base de datos
    http_response_code(500);
    echo json_encode(["error" => "Error en la base de datos: " . $e->getMessage()]);
}
?>