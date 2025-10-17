<?php

    include "funciones/conexionBD.php";
    include "funciones/funcionesLlamadas.php";

    session_start();

    if(empty($_SESSION)){

        header("Location: index.php");

    }

    date_default_timezone_set("Europe/Madrid");
    setlocale(LC_ALL, "spanish");
    $fechaHoy = date('Y-m-d');

    if(isset($_GET['consultar']) && $_SERVER['REQUEST_METHOD'] == 'GET'){

        if(empty($_GET['fechaInicio'])){

            echo "<div class='alert alert-danger' role='alert'> La <b>fecha de inicio</b> no puede estar vacia </div>";

        } else {

            $datosPendites = [
                'fechaInicio' => $_GET['fechaInicio'],
                'fechaFin' => $_GET['fechaFin'],
                'provincia' => $_GET['provincia'],
                'poblacion' => $_GET['poblacion'],
                'codigoUsuario' => $_SESSION['codigoUsuario'],

            ];
            

            if($llamadas = pendientes($datosPendites)){


            } else {

                echo "<div class='alert alert-danger' role='alert'>No se encuentra ninguna pendiente</div>";

            }

        }



    }
    if(isset($_GET['cambiarFechaBoton'])){

        if(!empty($_GET['cambiarFechaCheck'])){

            $nuevaFecha = date('d-m-Y', strtotime($_GET['nuevaFecha']));

            cambiarFechasPendientes($_GET['cambiarFechaCheck'], $nuevaFecha);
            
            echo "<div class='alert alert-success' role='alert'>Fechas cambiadas con exito</div>";

            $fechaInicio = $_GET['fechaInicio'];
            $fechaFin = $_GET['fechaFin'];
            $provincia = $_GET['provincia'];
            $poblacion = $_GET['poblacion'];
            header("Refresh: 1; URL=pendientes.php?fechaInicio=" . $fechaInicio . "&provincia=" . $provincia . "&fechaFin=" . $fechaFin . "&poblacion=" . $poblacion . "&consultar=Buscar");

        } else {

            echo "<div class='alert alert-danger' role='alert'>No se ha seleccionado ninguna llamada</div>";

        }


    }



?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es" lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Call Center</title>
    <link href="css/bootstrap.min.css" rel="stylesheet"></link>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/botonConsultar.js"></script>
    <script src="js/arrayProvincias.js"></script>
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

                    <a class="nav-link active text-bg-secondary" href="inicio.php" aria-current="page"><b> Call Center </b></a>

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

            <div class="col-md-2 col-12 align-items-start text-justify" style="background-color:#e4e4e4;">
                <nav class="navbar-nav nav-pills flex-column mt-2 mb-2">
                    <a class="nav-link" href="buscarEmpresa.php"> <img class="ms-3" src="images/iconos/search.svg"> <b> Insertar / Buscar </b></a>
                    <a class="nav-link active text-bg-secondary" href="pendientes.php"> <img class="ms-3" src="images/iconos/exclamation-triangle.svg"> <b> Pendientes </b></a>
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

            <div class="col-md-10 col-12" id="formPendientes">

                <form method="GET">

                    <h2 class="text-center mt-2 pt-2 pb-3 mb-md-5 mb-3 border border-5 rounded" style="background-color: #b0d588; letter-spacing: 7px;">PENDIENTES</h2>

                    <div class="container-fluid">

                        <div class="row d-flex justify-content-center">

                            <div class="form-group col-12 col-md-4 text-center">
                                
                                <label class="form-label"><b>Fecha de inicio:</b></label>
                                <input type="date" class="form-control col-6 col-md-1" value="<?php echo date('Y-m-d')?>" name="fechaInicio"></input>

                                <label class="form-label"><b>Provincia:</b></label>
                                <select class="form-select" name="provincia" id="selectProvincia">
                                    <option hidden="true" value="" selected></option>
                                    <option value="Pontevedra">Pontevedra</option>
                                    <option value="Orense">Orense</option>
                                    <option value="Lugo">Lugo</option>
                                    <option value="Coruña">Coruña</option>
                                </select>


                            </div>

                            <div class="form-group col-12 col-md-4 text-center">
                                
                                <label class="form-label mt-4 mt-md-0"><b>Fecha de fin:</b></label>
                                <input type="date" class="form-control col-6 col-md-1" value="<?php echo date('Y-m-d')?>" name="fechaFin"></input>

                                <label class="form-label"><b>Poblacion:</b></label>
                                <select class="form-select" name="poblacion" id="selectPoblacion">
                                    <option hidden="true" value="" selected></option>

                                </select>

                            </div>
                            
                            <div class="row d-flex justify-content-center">

                                <input type="submit" class="btn mb-3 mt-3 col-12 col-md-8" style="background-color: #1e989e" value="Buscar" name="consultar">

                            </div>
                            
                        </div>

                    </div>

                </form>

            </div>

            <?php

                if(!empty($llamadas)){

                    echo "<script>";
                    echo "$('#formPendientes').remove();";
                    echo "</script>";

                    echo "<div class='col-md-10 col-12 mt-2 table-responsive'>";

                    echo " <h2 class='text-center mt-2 pt-2 pb-3 mb-md-3 mb-3 border border-5 rounded' style='background-color: #b0d588; letter-spacing: 7px;'>PENDIENTES</h2>";

                    echo "<table class='table table-striped table-bordered table-sm text-center align-middle'>";
                    echo "<tr style='background-color: #8fd247;'>";
                    echo  "<th> ID </th>";
                    echo  "<th> Nombre </th>";
                    echo  "<th> Hora pendiente </th>";
                    echo  "<th> Poblacion </th>";

                    if($_SESSION['codigoUsuario'] == "103"){

                        echo "<th>Cambiar fecha</th>";

                    }

                    echo  "<th></th>";
                    echo "</tr>";

                    for($i=0; $i < count($llamadas); $i++){

                        echo "<tr>";

                        echo "<td>" . $llamadas[$i]['idempresa'] . "</td>";
                        echo "<td>" . $llamadas[$i]['nombre'] . "</td>";
                        echo "<td>" . $llamadas[$i]['horapendiente'] . "</td>";
                        echo "<td>" . $llamadas[$i]['poblacion'] . "</td>";

                        if($_SESSION['codigoUsuario'] == "103"){

                            echo "<form method='GET' name='cambiarFechas'>";

                            echo "<td> <input name='cambiarFechaCheck[]' value='" . $llamadas[$i]['idempresa'] . "' type='checkbox'></td>";
                           
                        }

                        $prueba = 'pendiente';
                        echo "<td> <button type='button' class='btn' style='background-color: #1e989e;' onclick='enviarConsulta(" . $llamadas[$i]['idempresa'] . ', "pendiente"' . ")'> Consultar <img src='images/iconos/info-circle.svg' class='ml-5'> </button> </td>";

                        echo "</tr>";

                    }

                    if($_SESSION['codigoUsuario'] == "103"){

                    echo "<tr>";
                    echo "<th>Fecha</th>";
                    echo "<td> </td>";
                    echo "<td> </td>";
                    echo "<td> </td>";

                    echo "<td> <input class='form-control' name='nuevaFecha' value='$fechaHoy' type='date' required> </input> </td>";
                    echo "<td> <button class='btn btn-danger' name='cambiarFechaBoton' value='cambiarFecha'>Cambiar fecha <img src='images/iconos/arrow-repeat.svg' class='ml-5'> </button> </td>";

                    echo "</form>";

                    echo " </tr>";

                }

                    echo "<tr>";
                    echo "<td colspan=6> <b> TOTAL PENDIENTES: $i </b> </td>";
                    echo "</tr>";

                    echo "</table>";
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