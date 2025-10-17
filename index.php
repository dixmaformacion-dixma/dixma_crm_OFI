<?php

    include "funciones/iniciarSesion.php";

    if(isset($_POST['iniciarSesion']) && $_SERVER['REQUEST_METHOD'] == 'POST'){

        if(!empty($_POST['usuario']) && !empty($_POST['pass'])){

            $usuario = $_POST['usuario'];
            $password = $_POST['pass'];

            if(iniciarSesion($usuario, $password)){

                header("Location: inicio.php");

            } else {

                echo "<div class='alert alert-danger' role='alert'> Usuario o contraseña erroneo </div>";

            }

        }

    }

?>



<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es" lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesion</title>
    <link href="css/bootstrap.min.css" rel="stylesheet"></link>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <link rel="icon" href="images/favicon.ico">
</head>
<body>

    <form class="container-fluid py-5 px-5 my-5 mx-auto row justify-content-center border border-5 rounded" style="width: 80%; height: 400px; background-color:#f3f6f4;" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>">

        <img src="images/logo.gif" id="logo" class="img-fluid mb-5" style="width: 200px;">
        
        <label class="form-label mb-0"><b>Usuario:</b></label>
        <input type="text" class="form-control mb-3" name="usuario">

        <label class="form-label mb-0"><b>Contraseña:</b></label>
        <input type="password" class="form-control mb-3" name="pass">

        <button type="submit" value="Iniciar Sesion" class="btn col-xs-1 col-md-2 " name="iniciarSesion" style="background-color: #b0d588">Iniciar Sesion</button>

    </form>

    
</body>
</html>