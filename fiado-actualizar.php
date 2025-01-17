<?php
require_once "conecion.php";
$dsn = "mysql:host=localhost:3307;dbname=code_bar;";
try {
    $pdo = new PDO($dsn, $usuario, $contrasena);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage();
}
/*echo $_POST["dni"];
echo $_POST["pagar_total"];
echo $_POST["pagar"];
echo $_POST["entrega"];*/
$metodo = $_POST["pagar"];
$entrega_total = $_POST["entrega"];
$dni = $_POST["dni"];
$fecha_date = date("Y-m-d");
$nombre_apellido = $_POST["nombre_apellido"];


$pagar = "";
$select = "SELECT * FROM fiado WHERE dni=:dni";
$statement1 = $pdo->prepare($select);
$statement1->bindParam(":dni", $dni, PDO::PARAM_INT);
$statement1->execute();
$todosFiados1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
$saldo = $todosFiados1[0]["saldo"];

$total = 0;
$lista_nuevos_productos = [];
$list_nuevos_cantidades = [];
$se_debe = 0;
if ($saldo === 0 && $todosFiados1[0]["productos"] === "[]") {
    $consultadeleteO = "DELETE FROM `fiado` WHERE dni=:dni";
    $stmtA = $pdo->prepare($consultadeleteO);
    $stmtA->bindParam(":dni", $dni, PDO::PARAM_INT);
    $stmtA->execute();
    echo "se elimina el producto";
    header("location:cuenta-corriente.php");
}

if ($metodo == "liquidar_total") {
    $consultadelete = "DELETE FROM fiado WHERE dni=:dni";
    $stmtC = $pdo->prepare($consultadelete);
    $stmtC->bindParam(":dni", $dni, PDO::PARAM_INT);
    $stmtC->execute();
    echo "se elimina el producto";
    header("location:cuenta-corriente.php");
} else {



    foreach ($todosFiados1 as $fiadodia) {
        $vuelta = 0;
        $productos_list = json_decode($fiadodia["productos"]);
        $cantidad_list = json_decode($fiadodia["cantidad"]);
        foreach ($productos_list as $producto_unidad) {
            $prod_code = $productos_list[$vuelta];
            $unidades_prod = $cantidad_list[$vuelta];

            // consulta precio

            $queryP = "SELECT precio FROM producto WHERE codigo_barra = :codigo_barra";
            $stmtP = $pdo->prepare($queryP);
            $stmtP->bindParam(':codigo_barra', $prod_code, PDO::PARAM_STR);
            $stmtP->execute();
            $f = $stmtP->fetch(PDO::FETCH_ASSOC);
            $precioUnitario = $f["precio"];
            $total = $total + ($precioUnitario * $unidades_prod);
            if ($entrega_total >= $total) {
                //echo "entro";
                $pagar = "eliminar";

            } else {
                //echo "no alcansa";
                //se resta si no se elacanza a cubrir el producto y se agrega un saldo
                $se_debe = $total - $entrega_total;
                if ($se_debe > $precioUnitario) {
                    //echo "**  saldo es mayor a precio de prod **";
                    array_push($lista_nuevos_productos, $prod_code);
                    array_push($list_nuevos_cantidades, $unidades_prod);
                    $se_debe = $se_debe - $precioUnitario;
                } else {
                    //echo "##  saldo es menor a precio  ##";
                }
                $pagar = "actualizar";
            }
            //

            //$total = $total + $suma_total;
            $vuelta++;
        }

        // echo "-----";

    }

    $guarda_p = json_encode($lista_nuevos_productos);
    $guarda_c = json_encode($list_nuevos_cantidades);
    if ($pagar === "eliminar") {
        $consultadelete = "DELETE FROM fiado WHERE dni=:dni";
        $stmtC = $pdo->prepare($consultadelete);
        $stmtC->bindParam(":dni", $dni, PDO::PARAM_INT);
        $stmtC->execute();
        echo "se elimina el producto";
        header("location:cuenta-corriente.php");
    } else {
        $consultadelete = "DELETE FROM fiado WHERE dni=:dni";
        $stmtC = $pdo->prepare($consultadelete);
        $stmtC->bindParam(":dni", $dni, PDO::PARAM_INT);
        $stmtC->execute();
        $crear_fiado = "INSERT INTO fiado (dni,nombre_y_apellido,productos,saldo,cantidad,fecha) 
              VALUES (:dni,:nombre_y_apellido,:productos,:saldo,:cantidad,:fecha)";
        $stmtD = $pdo->prepare($crear_fiado);
        $stmtD->bindParam(":dni", $dni, PDO::PARAM_INT);
        $stmtD->bindParam(":nombre_y_apellido", $nombre_apellido, PDO::PARAM_STR);
        $stmtD->bindParam(":productos", $guarda_p, PDO::PARAM_STR);
        $stmtD->bindParam(":saldo", $se_debe, PDO::PARAM_INT);
        $stmtD->bindParam(":cantidad", $guarda_c, PDO::PARAM_STR);
        $stmtD->bindParam(":fecha", $fecha_date, PDO::PARAM_STR);
        $stmtD->execute();
        echo "se actualiza el fiado";
        echo $se_debe;
        header("location:cuenta-corriente.php");
    }

}


/*$resta = intval($_POST["pagar_total"]) - intval($_POST["entrega"]);
$prod_total = intval($_POST["pagar_total"]);
$entrega_P = intval($_POST["entrega"]);
$cantidadJson = $_POST['cantidad_productos'];
$cantidad = json_decode($cantidadJson, true);
$list_cantidad = [];
$lugar = 0;
for ($i = 0; $i < count($cantidad); $i++) {
    foreach ($cantidad as $key => $value) {
        echo $key;
        echo "#";
        echo $value;
        echo "**";

    }
    echo "--------- _________";
    if (intval($cantidad[$lugar])) {
        array_push($list_cantidad, $cantidad[$lugar]);
    }
    $lugar++;
}
$new_list_cant_prod = [];
$precio_filtar = [];


foreach (json_decode($_COOKIE["fiados_todos"]) as $persona) {
    if ($persona->dni == $_POST["dni"]) {
        $productos_lista = $persona->productos;
        $productos_lista = json_decode($productos_lista);

        $vuelta_cantidad_barra = 0;

        foreach ($productos_lista as $code_barra) {
            $queryP = "SELECT precio FROM producto WHERE codigo_barra = :codigo_barra";
            $stmtP = $pdo->prepare($queryP);
            $stmtP->bindParam(':codigo_barra', $code_barra, PDO::PARAM_STR);
            $stmtP->execute();
            $f = $stmtP->fetch(PDO::FETCH_ASSOC);
            $precioUnitario = $f["precio"];


            if ($entrega_P < $precioUnitario) {
                //echo $entrega_P;
                //echo "--#";
            } else {
                for ($i = 0; $i < count($list_cantidad[$vuelta_cantidad_barra]); $i++) {
                    $new_list_cant_prod[] = $code_barra;
                    $precio_filtar[] = $f["precio"];
                }
                $vuelta_cantidad_barra++;

            }
        }
    }
}

//echo $entrega_P;
$lista_a_subir = $new_list_cant_prod;

foreach ($new_list_cant_prod as $key => $value) {

    if ($entrega_P > $precio_filtar[$key]) {
        $entrega_P = $entrega_P - $precio_filtar[$key];
        unset($lista_a_subir[$key]);
    }

}*/
/*var_dump($lista_a_subir);
echo "....";

if ($entrega_P > 0) {
    $saldo_contra = abs($entrega_P);
    $dni = $_POST["dni"];
    $sql = "UPDATE `fiado` SET saldo = :saldo, productos = :productos ,cantidad = :cantidad WHERE dni = :dni";
    $stmt = $pdo->prepare($sql);
    $nuevo_A = json_encode($lista_a_subir);
    $stmt->bindParam(':dni', $dni, PDO::PARAM_INT);
    $stmt->bindParam(':saldo', $saldo_contra, PDO::PARAM_INT);
    $stmt->bindParam(':productos', $nuevo_A, PDO::PARAM_STR);
    $stmt->bindParam(':cantidad', $cantidada_db, PDO::PARAM_STR);
    $stmt->execute();
    header("location: cuenta-corriente.php");
} elseif ($entrega_P === 0) {
    $dni = $_POST["dni"];
    $NuevoPD = json_encode($new_list_cant_prod);
    $sql = "DELETE FROM fiado WHERE dni = :dni AND productos = :productos";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':dni', $dni, PDO::PARAM_INT);
    $stmt->bindParam(':productos', $NuevoPD, PDO::PARAM_STR);
    $stmt->execute();
    header("location: cuenta-corriente.php");
} else {
    echo "no se pudo completar compra";
}*/