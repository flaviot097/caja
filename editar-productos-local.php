<?php
$codigo_barra_v = $_POST["codigo_B"];

require_once "conecion.php";
$dsn = "mysql:host=localhost:3307;dbname=code_bar;";
try {
    $pdo = new PDO($dsn, $usuario, $contrasena);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}

$query = "SELECT * FROM producto WHERE codigo_barra = :codigo_barra";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':codigo_barra', $codigo_barra_v);
$stmt->execute();
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
$nombre_producto = $resultados[0]["nombre_producto"];
$precio = $resultados[0]["precio"];
$departamento = $resultados[0]["departamento"];
$proveedor = $resultados[0]["proveedor"];
$stock = $resultados[0]["stock"];
$costo = $resultados[0]["costo"];
$ganancia = $resultados[0]["ganancia"];
$num_stock = $resultados[0]["num_stock"];
$fecha = date("Y-m-d");


$query1 = "UPDATE producto SET nombre_producto = :nombre_producto, 
            precio = :precio, 
            codigo_barra = :codigo_barra, 
            departamento = :departamento, 
            proveedor = :proveedor, 
            stock = :stock, 
            costo = :costo, 
            ganancia = :ganancia, 
            num_stock = :num_stock, 
            fecha = :fecha WHERE codigo_barra = :codigobarra";
$stmt1 = $pdo->prepare($query1);

$nombre;
$codigo;
$depa;
$prove;
$st;
$cost;
$gana;
$min_st;

if ($_POST["nombre"] !== "") {
    $nombre = $_POST["nombre"];
} else {
    $nombre = $nombre_producto;
}
if ($_POST["codigo_barra"] !== "") {
    $codigo = $_POST["codigo_barra"];
} else {
    $codigo = $codigo_barra_v;
}
if ($_POST["departamento"] !== "") {
    $depa = $_POST["departamento"];
} else {
    $depa = $departamento;
}
if ($_POST["proveedor"] !== "") {
    $prove = $_POST["proveedor"];
} else {
    $prove = $proveedor;
}
if ($_POST["stock"] !== "") {
    $st = $_POST["stock"];
} else {
    $st = $stock;
}
if ($_POST["costo"] !== "") {
    $cost = intval($_POST["costo"]);
} else {
    $cost = intval($costo);
}
if ($_POST["ganancia"] !== "") {
    $gana = intval($_POST["ganancia"]);
} else {
    $gana = intval($ganancia);
}
if ($_POST["num_stock"] !== "") {
    $min_st = intval($_POST["num_stock"]);
} else {
    $min_st = intval($num_stock);
}




$margen = $cost + intval($cost * $gana / 100);
var_dump($margen);
$stmt1->bindParam(':nombre_producto', $nombre, PDO::PARAM_STR);
$stmt1->bindParam(':precio', $margen, PDO::PARAM_INT);
$stmt1->bindParam(':codigo_barra', $codigo, PDO::PARAM_STR);
$stmt1->bindParam(':departamento', $depa, PDO::PARAM_STR);
$stmt1->bindParam(':proveedor', $prove, PDO::PARAM_STR);
$stmt1->bindParam(':stock', $st, PDO::PARAM_INT);
$stmt1->bindParam(':costo', $cost, PDO::PARAM_INT);
$stmt1->bindParam(':ganancia', $gana, PDO::PARAM_INT);
$stmt1->bindParam(':num_stock', $min_st, PDO::PARAM_INT);
$stmt1->bindParam(':fecha', $fecha, PDO::PARAM_STR);
$stmt1->bindParam(':codigobarra', $codigo_barra_v, PDO::PARAM_STR);

if ($stmt1->execute()) {

    $query2 = "SELECT * FROM producto_reparto WHERE codigo_barra = :codigo_barra";
    $stmt2 = $pdo->prepare($query);
    $stmt2->bindParam(':codigo_barra', $codigo_barra_v);
    $stmt2->execute();
    $resultados1 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    $nombre_producto1 = $resultados1[0]["nombre_producto"];
    $precio1 = $resultados1[0]["precio"];
    $departamento1 = $resultados1[0]["departamento"];
    $proveedor1 = $resultados1[0]["proveedor"];
    $stock1 = $resultados1[0]["stock"];
    $costo1 = $resultados1[0]["costo"];
    $ganancia1 = $resultados1[0]["ganancia"];
    $num_stock1 = $resultados1[0]["num_stock"];
    $fecha1 = date("Y-m-d");
    $query3 = "UPDATE producto_reparto SET nombre_producto = :nombre_producto, 
            precio = :precio, 
            codigo_barra = :codigo_barra, 
            stock = :stock,
            departamento = :departamento, 
            proveedor = :proveedor, 
            costo = :costo, 
            ganancia = :ganancia,  
            fecha = :fecha WHERE codigo_barra = :codigobarra";
    $stmt3 = $pdo->prepare($query3);

    $nombre1;
    $codigo1;
    $depa1;
    $prove1;
    $cost1;
    $gana1;
    $min_st1;

    if ($_POST["nombre"] !== "") {
        $nombre1 = $_POST["nombre"];
    } else {
        $nombre1 = $nombre_producto1;
    }
    if ($_POST["codigo_barra"] !== "") {
        $codigo1 = $_POST["codigo_barra"];
    } else {
        $codigo1 = $codigo_barra_v;
    }
    if ($_POST["departamento"] !== "") {
        $depa1 = $_POST["departamento"];
    } else {
        $depa1 = $departamento1;
    }
    if ($_POST["proveedor"] !== "") {
        $prove1 = $_POST["proveedor"];
    } else {
        $prove1 = $proveedor1;
    }
    if ($_POST["costo"] !== "") {
        $cost1 = intval($_POST["costo"]);
    } else {
        $cost1 = intval($costo);
    }
    if ($_POST["ganancia"] !== "") {
        $gana1 = intval($_POST["ganancia"]);
    } else {
        $gana1 = intval($ganancia);
    }
    if ($_POST["num_stock"] !== "") {
        $min_st1 = $_POST["num_stock"];
    } else {
        $min_st1 = $num_stock1;
    }


    $margen = $cost + intval($cost * $gana / 100);
    $stmt3->bindParam(':nombre_producto', $nombre, PDO::PARAM_STR);
    $stmt3->bindParam(':codigo_barra', $codigo, PDO::PARAM_STR);
    $stmt3->bindParam(':precio', $margen, PDO::PARAM_INT);
    $stmt3->bindParam(':departamento', $depa, PDO::PARAM_STR);
    $stmt3->bindParam(':proveedor', $prove, PDO::PARAM_STR);
    $stmt3->bindParam(':costo', $cost, PDO::PARAM_INT);
    $stmt3->bindParam(':ganancia', $gana, PDO::PARAM_INT);
    $stmt3->bindParam(':stock', $stock1, PDO::PARAM_INT);
    $stmt3->bindParam(':fecha', $fecha, PDO::PARAM_STR);
    $stmt3->bindParam(':codigobarra', $codigo_barra_v, PDO::PARAM_STR);

    var_dump($nombre);
    echo "-";
    var_dump($codigo);
    echo "-";
    var_dump($depa);
    echo "-";
    var_dump($prove);
    echo "-";
    var_dump($cost);
    echo "-";
    var_dump($gana);
    echo "-";
    var_dump($min_st);
    echo "-";
    if ($stmt3->execute()) {
        setcookie("mensaje", "exito", time() + 10, '/');
        header("location: template-editar-prod-l.php");
    } else {
        setcookie("mensaje", "fallo", time() + 10, '/');
        header("location: template-editar-prod-l.php");
    }

}
;