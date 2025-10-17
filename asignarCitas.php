<?php

    include "funciones/conexionBD.php";
    include "funciones/funcionesCitas.php";
    include "funciones/cargarComerciales.php";

    session_start();

    if(empty($_SESSION)){

        header("Location: index.php");

    }

    date_default_timezone_set("Europe/Madrid");
    setlocale(LC_ALL, "spanish");
    $fechaHoy = date('Y-m-d');

    if(isset($_GET['consultar']) && $_SERVER['REQUEST_METHOD'] == 'GET'){

        if(empty($_GET['fecha'])){

            echo "<div class='alert alert-danger' role='alert'> La <b>fecha</b> no puede estar vacia </div>";

        } else {

            $fecha = date("d-m-Y", strtotime($_GET['fecha']));

            if($citas = citas($fecha)){

            } else {

                echo "<div class='alert alert-danger' role='alert'>No se encuentra ninguna cita</div>";

            }

        }

    };
    
    if(isset($_POST['idCita']) && $_SERVER['REQUEST_METHOD'] == 'POST'){

        $fecha = $_GET['fecha'];

        if(asignarCita($_POST['idCita'], $_POST['comercial'])){

            echo "<div class='alert alert-success' role='alert'>Cita actualizada con exito</div>";

        }


        header("Refresh: 0; URL=asignarCitas.php?fecha=" . $fecha . "&consultar=Buscar");
        

    };

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

            <div class="col-md-10 col-12" id="formCitas">

                <form method="GET">

                    <h2 class="text-center mt-2 pt-2 pb-3 mb-md-5 mb-3 border border-5 rounded" style="background-color: #b0d588; letter-spacing: 7px;">ASIGNAR CITAS</h2>

                    <div class="container-fluid">

                        <div class="row d-flex justify-content-center">

                            <div class="form-group col-12 col-md-4 text-center">
                                
                                <label class="form-label"><b>Fecha:</b></label>
                                <input type="date" class="form-control col-10 col-md-3" value="<?php echo date('Y-m-d', strtotime($fechaHoy. "+ 1 day"))?>" name="fecha"></input>
                                <input type="submit" class="btn mb-3 mt-3 col-12 col-md-12" style="background-color: #1e989e" value="Buscar" name="consultar">

                            </div>

                        </div>

                    </div>

                </form>

            </div>


            <?php

                if(!empty($citas)){

                    $comerciales = cargarComerciales();

                    echo "<script>";
                    echo "$('#formCitas').remove();";
                    echo "</script>";

                    echo "<div class='col-md-10 col-12 mt-2 table-responsive'>";

                    echo "<h2 class='text-center mt-1 pt-2 pb-3 mb-md-3 mb-3 border border-5 rounded' style='background-color: #b0d588; letter-spacing: 7px;'>ASIGNAR CITAS</h2>";

                    for($i=0; $i < count($citas); $i++){

                        echo "<table class='table table-striped table-bordered table-sm mt-2 align-middle'>";

                        echo "<tr> <th style='background-color: #41ced5; width: 200px;'> Empresa </th> <td>" . $citas[$i]['nombre'] . "</td> </tr>";
                        echo "<tr> <th style='background-color: #41ced5;'> Direccion </th> <td>" . $citas[$i]['calle'] . "</td> </tr>";
                        echo "<tr> <th style='background-color: #41ced5;'> Poblacion </th> <td>" . $citas[$i]['poblacion'] . "</td> </tr>";
                        echo "<tr> <th style='background-color: #41ced5;'> Telefono </th> <td>" . $citas[$i]['telef1'] . "</td> </tr>";
                        echo "<tr> <th style='background-color: #41ced5;'> Persona contacto </th> <td>" . $citas[$i]['personacontacto'] . "</td> </tr>";
                        echo "<tr> <th style='background-color: #41ced5;'> Cita </th> <td>" . $citas[$i]['fechacita'] . "</td> </tr>";
                        echo "<tr> <th style='background-color: #41ced5;'> Realiza por </th> <td>" . $citas[$i]['codigousuario'] . "</td> </tr>";

                        if($citas[$i]['comercial'] == ""){

                            echo "<form method='POST' action=''>";
                            echo "<tr> <th style='background-color: #41ced5;'> Asignar cita </th> <td> <select name='comercial' class='form-select'>";

                            for($j=0; $j < count($comerciales); $j++){

                                if($comerciales[$j]['codigousuario'][0] == "1" && $comerciales[$j]['activo'] == "1"){

                                echo "<option value='" . $comerciales[$j]['codigousuario'] . "'>";
                                echo $comerciales[$j]['nombre'] . "</option>";

                                }
                                
                            }

                            echo "</select> </td> </tr>";
                            echo "<tr> <td colspan=2> <button name='idCita' class='btn btn-primary col-12' value='" . $citas[$i]['idcita'] . "'> Asignar </button> </td> </tr>";
                            echo "</form>";

                        } else {

                            echo "<tr> <th style='background-color: #41ced5;'> Cita asignada </th> <td>" . $citas[$i]['comercial'] . "</td>";

                        }

                        echo "</table>";
                        echo "<tr> <td colspan=2> <hr class='border border-success border-5'> </td> </tr>";

                    }

                    echo "</div>";

                }

                ?>


        </div>

    </div>

    <footer class="border-top border-secondary" style="background-color:#e4e4e4; height: 75px;">

            <p class="text-center mt-md-4" style='color: #8fd247;'> <b> © Dixma Formación 2022. | Ctra. Madrid 152, Vigo 36318 | info@dixmaformacion.com | Tlf: +34 604 067 035 </b> </p>

    </footer>
    
</body>
</html>