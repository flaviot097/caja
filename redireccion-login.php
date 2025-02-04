<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
</head>
<style>
body {
    height: 100vh;
}

.contenedor {
    display: flex;
    justify-content: center;
    align-content: space-around;
    flex-wrap: wrap;
    height: 100%;
}

.tarjeta {
    padding: 20px;
    border-radius: 5px;
}
</style>

<body>
    <div class="contenedor">
        <div class="card text-bg-primary mb-3 tarjeta" style="background-color: gainsboro; ">
            <img src="https://static.vecteezy.com/system/resources/previews/026/526/151/non_2x/error-icon-vector.jpg"
                class="card-img-top" alt="..." style="width: 120px;">
            <div class="card-body">
                <h5 class="card-title">Ingrese nuevamente</h5>
                <p class="card-text">El usuario o contraseña es incorrecto</p>
                <p class="card-text"><small class="text-body-secondary">Intente nuevamente <a href="index.php">Iniciar
                            Sesion</a></small>
                </p>
            </div>
        </div>
    </div>
</body>

</html>