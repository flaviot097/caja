<?php
// Configuración de la base de datos
$host = 'localhost';
$usuario = 'root';
$password = '';
$nombre_base_datos = 'code_bar';

try {
    $pdo = new PDO("mysql:host=$host:3307;dbname=$nombre_base_datos;charset=utf8", $usuario, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener todas las tablas de la base de datos
    $tablas = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

    foreach ($tablas as $tabla) {
        $archivo_sql = fopen("$tabla.sql", "w");

        // Obtener la estructura de la tabla
        $estructura = $pdo->query("SHOW CREATE TABLE $tabla")->fetch(PDO::FETCH_ASSOC);
        fwrite($archivo_sql, $estructura['Create Table'] . ";\n\n");

        // Obtener los datos de la tabla
        $datos = $pdo->query("SELECT * FROM $tabla")->fetchAll(PDO::FETCH_ASSOC);

        foreach ($datos as $fila) {
            $columnas = array_map(function ($valor) use ($pdo) {
                return $pdo->quote($valor);
            }, array_values($fila));

            $columnas = implode(", ", $columnas);
            $query = "INSERT INTO $tabla VALUES ($columnas);\n";
            fwrite($archivo_sql, $query);
        }

        fclose($archivo_sql);
    }
    echo "Respaldo completado con éxito.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
header("location: caja.php") // Esto imprimirá los datos en la pantalla para verificación.

    ?>