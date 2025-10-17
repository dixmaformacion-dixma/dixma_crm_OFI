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

    <nav class="navbar navbar-expand-lg border-bottom border-secondary" style="background-color:#e4e4e4;">

        <div class="container-fluid">

            <a class="navbar-brand" href="inicio.php"><img src="images/logo.gif" id="logo" class="img-fluid" style="width: 200px; heigth: 50px"></a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">

                <div class="navbar-nav nav-pills">

                    <a class="nav-link" href="inicio.php" aria-current="page"><b> Call Center </b></a>

                    <?php

                        if($_SESSION['rol'] == "admin"){

                        echo "<a class='nav-link' href='administracion.php'><b> Administracion </b></a>";

                        }

                    ?>

                    <a class="nav-link" href="comercial.php"><b> Comercial </b></a>

                <?php

                    if($_SESSION['rol'] == "admin" || $_SESSION['codigoUsuario'][0] == "3"){

                    echo "<a class='nav-link' href='tutoria.php'><b> Tutoria </b></a>";

                    }

                ?>

                    <a class="nav-link disabled me-5" href=""><b> Estadisticas </b></a>

                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle active text-bg-secondary" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <b> <?php echo $_SESSION['usuario'] ?> </b>
                        </a>

                        <div class="dropdown-menu" style="background-color: #e4e4e4">
                            <a class="dropdown-item " href="perfilUsuario.php"><b> Perfil </b></a>
                            <hr class="dropdown-divider">
                            <a class="dropdown-item " href="funciones/cerrarSesion.php"><b> Cerrar sesion </b></a>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </nav>

    <!-- Menu lateral y formulario -->
    <div class="container-fluid">

        <div class="row">

            <div class="col-md-2 col-12 align-items-start text-justify" style="background-color:#e4e4e4;">
                <nav class="navbar-nav nav-pills flex-column mt-2 mb-2">
                    <a class="nav-link" href="buscarEmpresa.php"> <img class="ms-3" src="images/iconos/search.svg"> <b> Insertar / Buscar </b></a>
                    <a class="nav-link" href="pendientes.php"> <img class="ms-3" src="images/iconos/exclamation-triangle.svg"> <b> Pendientes </b></a>
                    <a class="nav-link" href="listado.php"> <img class="ms-3" src="images/iconos/list.svg"> <b> Listado </b></a>
                    <a class="nav-link" href="sectores.php"> <img class="ms-3" src="images/iconos/briefcase.svg"> <b> Sectores </b></a>
                    <a class="nav-link" href="control_llamadas.php"> <img class="ms-3" src="images/iconos/telephone.svg"> <b> Control de llamadas </b></a>
                    <a class="nav-link" href="citas.php"> <img class="ms-3" src="images/iconos/calendar-day.svg"> <b> Citas </b></a>
                    <a class="nav-link" href="listadoCitas.php"> <img class="ms-3" src="images/iconos/calendar-date.svg"> <b> Listado de Citas </b></a>
                    <a class="nav-link" href="cursosInteresados.php"> <img class="ms-3" src="images/iconos/book.svg"> <b> Cursos interesados </b></a>

                    <?php 
                    
                        if($_SESSION['codigoUsuario'][0] == "2"){

                            echo "<hr class='border border-dark'>";
                            echo "<a class='nav-link' href='pedirCita.php'> <img class='ms-3' src='images/iconos/calendar-plus.svg'> <b> Pedir Cita </b></a>";
                            echo "<a class='nav-link' href='hacerSeguimiento.php'> <img class='ms-3' src='images/iconos/box-arrow-in-right.svg'> <b> Hacer seguimiento </b></a>";

                        }

                        if($_SESSION['codigoUsuario'][0] == "1"){

                            echo "<hr class='border border-dark'>";
                            echo "<a class='nav-link' href='Callcenter_crearCurso.php'> <img class='ms-3' src='images/iconos/book.svg'> <b> Crear curso </b></a>";

                        }

                    ?>

                </nav> 
            </div>

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