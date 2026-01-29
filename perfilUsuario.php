<?php

include "funciones/conexionBD.php";

    session_start();

    if(empty($_SESSION)){

        header("Location: index.php");

    }

    date_default_timezone_set("Europe/Madrid");
    setlocale(LC_ALL, "spanish");

    if(isset($_POST['enviar']) && $_SERVER['REQUEST_METHOD'] == 'POST'){

        $ruta = "images/usuarios/" . $_SESSION['usuario'];

        if(!file_exists($ruta)){

            mkdir($ruta);

        }

        $foto = $_FILES['fotoPerfil']['name'][0];
        //$extension = pathinfo($foto, PATHINFO_EXTENSION);
        //$ruta = $ruta . "/fotoPerfil." . $extension;
        $ruta = $ruta . "/fotoPerfil.jpg";

        if(move_uploaded_file($_FILES['fotoPerfil']['tmp_name'][0], $ruta)){

            echo "<div class='alert alert-success'>Foto de perfil actualizada con exito</div>";

        } else {

            echo "<div class='alert alert-danger'>Error al subir la foto de perfil</div>";

        }
 
    }

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Call Center</title>
    <link href="css/bootstrap.min.css" rel="stylesheet"></link>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <link rel="icon" href="images/favicon.ico">

</head>
<body style="background-color: #f3f6f4;">

    <!-- Menu cabecera -->

    <?php 
        $menuaction = 'callcenter';
        require_once './template-parts/header/menu_top.php' 
    ?>

    <!-- Menu lateral y formulario -->
    <div class="container-fluid">

        <div class="row">

            <?php require_once './template-parts/leftmenu/callcenter.template.php'; ?>

            <div class="col-md-10 col-12" id="formUsuario">

                    <div class="container-fluid">

                    <form method="POST" enctype="multipart/form-data">

                        <div class="row text-center mt-3">

                            <div class="col-12 col-md-12">

                            <img class="rounded-circle border border-2 border-dark" width="100px" height="100px" src="<?php echo 'images/usuarios/' . $_SESSION['usuario'] . '/fotoPerfil.jpg' ?>">

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-12 col-md-6 mt-2">

                                <label class="form-label"> <b> Nombre usuario: </b></label>
                                <input class="form-control text-center" value=" <?php echo $_SESSION['usuario'] ?> " readonly></input>

                            </div>

                            <div class="col-12 col-md-6 mt-2">

                                <label class="form-label"> <b> Codigo usuario: </b></label>
                                <input class="form-control text-center" value=" <?php echo $_SESSION['codigoUsuario'] ?> " readonly></input>

                            </div>

                        </div>
                        
                        <div class="row">

                            <div class="col-12 col-md-12">

                                <label class="form-label"> <b> Foto de perfil: </b></label>
                                <input type="file" class="form-control" name="fotoPerfil[]"></input>

                            </div>

                        </div>

                        <div class="row text-center">

                            <div class="col-md-12 col-12">

                                <input type="submit" name="enviar" class="btn btn-primary col-8 mt-2"></input>

                            </div>

                        </div>

                    </form>

                    </div>

            </div>

        </div>

    </div>

    <footer class="border-top border-secondary" style="background-color:#e4e4e4; height: 75px;">

            <p class="text-center mt-md-4" style='color: #8fd247;'> <b> © Dixma Formación 2022. | Ctra. Madrid 152, Vigo 36318 | info@dixmaformacion.com | Tlf: +34 604 067 035 </b> </p>

    </footer>
    
</body>
</html>