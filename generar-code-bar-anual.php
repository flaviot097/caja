<?php

require_once "validacion-usuario.php";

require_once "conecion.php";
$dsn = 'mysql:host=localhost:3307;dbname=code_bar;';
try {
    $pdo = new PDO($dsn, $usuario, $contrasena);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'error al conectarse: ' . $e->getMessage();
    exit;
}
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
    if ($tabla !== "usuario" && $tabla !== "saldos" && $tabla !== "producto_reparto" && $tabla !== "producto" && $tabla !== "fiado") {
        $consultaDelete = $pdo->prepare("TRUNCATE TABLE $tabla");
        $consultaDelete->execute();
    }

}

header("location: stock-template.php");

?>