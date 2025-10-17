<?php

    include "funciones/conexionBD.php";
    include "funciones/funcionesVentas.php";
    include "funciones/funcionesEmpresa.php";

    session_start();

    if(empty($_SESSION)){

        header("Location: index.php");

    }

    date_default_timezone_set("Europe/Madrid");
    setlocale(LC_ALL, "spanish");
    $fechaHoy = date('Y-m-d');

    if(isset($_GET['consultar']) && $_SERVER['REQUEST_METHOD'] == 'GET'){

            $fechaInicio = date('d-m-Y', strtotime($_GET['fechaInicio']));
            $fechaFin = date('d-m-Y', strtotime($_GET['fechaFin']));

            if($listadoVentas = listadoVentasEmpresas($fechaInicio, $fechaFin)){


            } else {

                echo "<div class='alert alert-danger' role='alert'>No se encuentra ninguna empresa</div>";

            }

        }

        if($empresa = cargarEmpresa($_GET['idEmpresa'])){

        }
    
        if($listadoVentas = listadoVentas($_GET['idEmpresa'])){
    
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
    <script src="js/botonConsultar.js"></script>
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

            <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">

                <div class="navbar-nav nav-pills">

                    <a class="nav-link" href="inicio.php" aria-current="page"><b> Call Center </b></a>

                    <?php

                        if($_SESSION['rol'] == "admin"){

                            echo "<a class='nav-link' href='administracion.php'><b> Administracion </b></a>";

                        }

                    ?>

                    <a class="nav-link active text-bg-secondary" href="comercial.php"><b> Comercial </b></a>

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
                    <a class="nav-link active text-bg-secondary" href="listadoVentasComercial.php"> <img class="ms-3" src="images/iconos/list.svg"> <b> Listado ventas </b></a>
                </nav> 
            </div>

                                <div class="col-md-10 col-12">
                                    <?php

                                        if(!empty($listadoVentas)){

                                            echo "<div class='container-fluid mt-2 mb-3'>";
                                            

                                            for($i=0; $i < count($listadoVentas); $i++){

                                                echo "<table class='table table-bordered table-striped table-sm text-center mt-2 align-middle'>";

                                                echo "<tr>";
                                                echo "<td> ID venta: </td>";
                                                echo "<th>" . $listadoVentas[$i]['idventa'] . "</th>";
    
                                                echo "<td> Fecha: </td>";
                                                echo "<th>" . $listadoVentas[$i]['fecha'] . "</th>";
    
                                                echo "<td> Hora: </td>";
                                                echo "<th>" . $listadoVentas[$i]['hora'] . "</th>";
    
                                                echo "<td> ID comercial: </td>";
                                                echo "<th>" . $listadoVentas[$i]['idcomercial'] . "</th>";
                                                echo "</tr>";
    
                                                echo "<tr>";
                                                echo "<td>" . $listadoVentas[$i]['curso1'] . "</td>";
                                                echo "<th colspan=2> Curso 1: " . $listadoVentas[$i]['nombrecurso1'] . "</th>";
    
                                                echo "<td> Horas: </td>";
                                                echo "<th>" . $listadoVentas[$i]['horascurso1'] . "</th>";
    
                                                echo "<td> Modalidad: </td>";
                                                echo "<th colspan=2>" . $listadoVentas[$i]['modalidadcurso1'] . "</th>";
                                                echo "</tr>";
    
                                                echo "<tr>";
                                                echo "<td>" . $listadoVentas[$i]['curso2'] . "</td>";
                                                echo "<th colspan=2> Curso 2: " . $listadoVentas[$i]['nombrecurso2'] . "</th>";
    
                                                echo "<td> Horas: </td>";
                                                echo "<th>" . $listadoVentas[$i]['horascurso2'] . "</th>";
    
                                                echo "<td> Modalidad: </td>";
                                                echo "<th colspan=2>" . $listadoVentas[$i]['modalidadcurso2'] . "</th>";
                                                echo "</tr>";
    
                                                echo "<tr>";
                                                echo "<td>" . $listadoVentas[$i]['curso3'] . "</td>";
                                                echo "<th colspan=2> Curso 3: " . $listadoVentas[$i]['nombrecurso3'] . "</th>";
    
                                                echo "<td> Horas: </td>";
                                                echo "<th>" . $listadoVentas[$i]['horascurso3'] . "</th>";
    
                                                echo "<td> Modalidad: </td>";
                                                echo "<th colspan=2>" . $listadoVentas[$i]['modalidadcurso3'] . "</th>";
                                                echo "</tr>";
    
                                                echo "<tr>";
                                                echo "<td> Observaciones: </td>";
                                                echo "<th colspan=7>" . $listadoVentas[$i]['observacionesventa'] . "</th>";
                                                echo "</tr>";
    
                                                echo "<tr>";
                                                echo "<td> Email factura: </td>";
                                                echo "<th colspan=7>" . $listadoVentas[$i]['emailfactura'] . "</th>";
                                                echo "</tr>";
    
                                                echo "<tr>";
                                                echo "<td> Nombre asesoria: </td>";
                                                echo "<th colspan=2>" . $listadoVentas[$i]['nombreasesoria'] . "</th>";
    
                                                echo "<td> Telf asesoria: </td>";    
                                                echo "<th>" . $listadoVentas[$i]['telfasesoria'] . "</th>"; 
                                                
                                                echo "<td> Email asesoria: </td>";
                                                echo "<th colspan=2>" . $listadoVentas[$i]['mailasesoria'] . "</th>";
                                                echo "</tr>";
    
                                                echo "<tr>";
                                                echo "<td> Importe: </td>";
                                                echo "<th>" . $listadoVentas[$i]['importe'] . "€</th>";
    
                                                echo "<td> Fecha cobro: </td>";
                                                echo "<th colspan=2>" . $listadoVentas[$i]['fechacobro'] . "</th>";
                                                
                                                echo "<td> FP: </td>";
                                                echo "<th colspan=2>" . $listadoVentas[$i]['formapago'] . "</th>";
                                                echo "</tr>";
    
                                                echo "<tr>";
                                                echo "<td> Numero de cuenta: </td>";
                                                echo "<th colspan=7>" . $listadoVentas[$i]['numerocuenta'] . "</th>";
                                                echo "</tr>";
    
                                                echo "</div>";

                                                echo "</table>";

                                                echo "<td colspan=8> <hr class='border border-success border-5'> </td>";

                                            } 

                                        }

                                    ?>
                                </div>
                            </div>



           
    <footer class="border-top border-secondary" style="background-color:#e4e4e4; height: 75px;">

            <p class="text-center mt-md-4" style='color: #8fd247;'> <b> © Dixma Formación 2022. | Ctra. Madrid 152, Vigo 36318 | info@dixmaformacion.com | Tlf: +34 604 067 035 </b> </p>

    </footer>
    
</body>
</html>