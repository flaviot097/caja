<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo = $_POST['codigo'];

    // Conexión a la base de datos
    $servername = "localhost:3307";
    $username = "root";
    $password = "";
    $dbname = "code_bar";

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Consulta a la base de datos
    $sql = "SELECT * FROM producto WHERE codigo_barra = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $codigo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Mostrar los resultados
        while ($row = $result->fetch_assoc()) {
            echo "<div>";
            echo "<h2>Producto: " . $row["nombre_producto"] . "</h2>";
            echo "<p>Código: " . $row["id"] . "</p>";
            echo "<p>Precio: $" . $row["precio"] . "</p>";
            /*echo "<p>Stock: " . $row["stock"] . "</p>";
            echo "<p>Distribuidora: " . $row["distribuidora"] . "</p>";
            echo "<p>Descripción: " . $row["descripcion"] . "</p>";
            echo "<p>Fecha: " . $row["fecha"] . "</p>";
            echo "<p>DNI: " . $row["dni"] . "</p>";
            echo "<img src='uploads/" . $row["imagen"] . "' alt='Imagen del producto' style='max-width: 200px;'><br>*";*/
            echo "</div>";
        }
    } else {
        echo "<p>No se encontró el producto.</p>";
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();
}