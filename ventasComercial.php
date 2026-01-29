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

    <?php 
        $menuaction = 'comercial';
        require_once './template-parts/header/menu_top.php' 
    ?>

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