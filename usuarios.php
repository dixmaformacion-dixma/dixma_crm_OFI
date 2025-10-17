<?php

    include "funciones/conexionBD.php";
    include "funciones/funcionesEmpresa.php";
    include "funciones/funcionesUsuarios.php";

    session_start();

    if(empty($_SESSION)){

        header("Location: index.php");

    }

    date_default_timezone_set("Europe/Madrid");
    setlocale(LC_ALL, "spanish");

    if(isset($_GET['nuevoUsuario']) && $_SERVER['REQUEST_METHOD'] == 'GET'){

        header("Location: nuevoUsuario.php");

    }

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es" lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Call Center</title>
    <link href="css/bootstrap.min.css" rel="stylesheet"></link>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/botonEliminar.js"></script>
    <link rel="icon" href="images/favicon.ico">
</head>
<body style="background-color:#f3f6f4;">

    <!-- Menu cabecera -->

    <nav class="navbar navbar-expand-lg justify-content-center border-bottom border-secondary" style="background-color:#e4e4e4;">

        <div class="container-fluid">

            <a class="navbar-brand" href="inicio.php"><img src="images/logo.gif" id="logo" class="img-fluid" style="width: 200px; heigth: 50px"></a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-center"  id="navbarSupportedContent">

                <div class="navbar-nav nav-pills">

                    <a class="nav-link" href="inicio.php" aria-current="page"><b> Call Center </b></a>
                    <a class="nav-link active text-bg-secondary" href="administracion.php"><b> Administracion </b></a>
                    <a class="nav-link" href="comercial.php"><b> Comercial </b></a>

                <?php

                    if($_SESSION['rol'] == "admin" || $_SESSION['codigoUsuario'][0] == "3"){

                    echo "<a class='nav-link' href='tutoria.php'><b> Tutoria </b></a>";

                    }

                ?>

                    <a class="nav-link disabled me-5" href=""><b> Estadisticas </b></a>
                    
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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

            <?php require_once("template-parts/leftmenu/administracion.template.php"); ?>

            <div class="col-md-10 col-12 table-responsive">

                <h2 class="text-center mt-2 pt-2 pb-3 mb-md-2 mb-2 border border-5 rounded" style="background-color: #b0d588; letter-spacing: 7px;">USUARIOS</h2>

                    <form method="GET">

                    <button class="btn" style="background-color: #1e989e" name="nuevoUsuario">AGREGAR NUEVO USUARIO</button>

                    </form>

                <?php

                $usuarios = cargarUsuarios();

                        echo "<table class='table table-striped table-bordered table-sm text-center mt-2 align-middle'>";

                        echo "<tr style='background-color: #8fd247;'>";
                        echo "<th> ID </th>";
                        echo "<th> Usuario </th>";
                        echo "<th> Tipo </th>";
                        echo "<th> Codigo usuario </th>";
                        echo "<th></th>";
                        echo "</tr>";

                        for($i=0; $i < count($usuarios); $i++){

                            echo "<tr>";

                            echo "<td>" . $usuarios[$i]['idusuario'] .  "</td>";
                            echo "<td>" . $usuarios[$i]['nombre'] .  "</td>";
                            echo "<td>" . $usuarios[$i]['tipo'] .  "</td>";
                            echo "<td>" . $usuarios[$i]['codigousuario'] .  "</td>";

                            if($usuarios[$i]['tipo'] != "admin"){

                            echo "<td> <button type='button' class='btn btn-danger' onclick='eliminarUsuario(" . $usuarios[$i]['idusuario'] . ")'>Eliminar <img src='images/iconos/x-circle.svg' class='ml-5'> </button> </td>";

                            } else {

                                echo "<td></td>";

                            }
                            
                            echo "</tr>";

                        }

                    echo "</table>";
                    echo "</div>";

                    ?>
            </div>
        </div>

    </div>

    <footer class="border-top border-secondary" style="background-color:#e4e4e4; height: 75px;">

            <p class="text-center mt-md-4" style='color: #8fd247;'> <b> © Dixma Formación 2022. | Ctra. Madrid 152, Vigo 36318 | info@dixmaformacion.com | Tlf: +34 604 067 035 </b> </p>

    </footer>

</body>
</html>