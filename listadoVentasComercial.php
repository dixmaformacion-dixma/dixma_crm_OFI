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

            $fechaInicio = !empty($_GET['fechaInicio'])?date('d-m-Y', strtotime($_GET['fechaInicio'])):'';
            $fechaFin = !empty($_GET['fechaFin'])?date('d-m-Y', strtotime($_GET['fechaFin'])):'';
            $empresa = $_GET['empresa'];

            if($listadoVentas = listadoVentasEmpresasComercial($fechaInicio, $fechaFin, $empresa)){


            } else {

                echo "<div class='alert alert-danger' role='alert'>No se encuentra ninguna empresa</div>";

            }

        }

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es" lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comercial</title>
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

            <div class="col-md-10 col-12" id="formListado">

                <form method="GET">

                    <h2 class="text-center mt-2 pt-2 pb-3 mb-md-5 mb-3 border border-5 rounded" style="background-color: #b0d588; letter-spacing: 7px;">LISTADO VENTAS</h2>

                    <div class="container-fluid">

                        <div class="row d-flex justify-content-center">
                            <div class="form-group col-8">
                                
                                <label class="form-label"><b>ID Empresa / Nombre</b></label>
                                <input type="text" name="empresa" value="<?php echo @$_GET['empresa'] ?>" class="form-control"></input>

                            </div>
                            <div class="col-12"></div>

                            <div class="form-group col-12 col-md-4 text-center">
                                
                                <label class="form-label"><b>Fecha inicio:</b></label>
                                <input type="date" name="fechaInicio" value="<?php echo @$_GET['fechaInicio'] ?>" class="form-control"></input>

                            </div>

                            <div class="form-group col-12 col-md-4 text-center">
                                
                                <label class="form-label"><b>Fecha fin:</b></label>
                                <input type="date" name="fechaFin" value="<?php echo @$_GET['fechaFin'] ?>" class="form-control"></input>

                            </div>

                            
                            <div class="row d-flex justify-content-center">

                                <input type="submit" class="btn mb-3 mt-3 col-12 col-md-8" style="background-color: #1e989e" value="Buscar" name="consultar">

                            </div>
                            
                        </div>

                    </div>

                </form>

            </div>

            <?php

                if(!empty($listadoVentas)){

                    $total = 0;

                    echo "<script>";
                    echo "$('#formListado').remove();";
                    echo "</script>";

                    echo "<div class='col-md-10 col-12 mt-2 table-responsive'>";

                    echo " <h2 class='text-center mt-2 pt-2 pb-3 mb-md-3 mb-3 border border-5 rounded' style='background-color: #b0d588; letter-spacing: 7px;'>LISTADO VENTAS</h2>";


                    echo "<table class='table table-striped table-bordered table-sm text-center align-middle'>";
                    echo "<tr class='' style='background-color: #8fd247;'>";
                    echo  "<th> ID </th>";
                    echo  "<th> Nombre </th>";
                    echo  "<th> Fecha venta </th>";
                    echo  "<th> Fecha cobro </th>";
                    echo  "<th> Importe </th>";
                    echo  "<th></th>";
                    echo "</tr>";

                    for($i=0; $i < count($listadoVentas); $i++){

                        $empresa = buscarEmpresasPorID($listadoVentas[$i]['idempresa']);

                        echo "<tr>";

                        echo "<td>" . $listadoVentas[$i]['idventa'] . "</td>";
                        echo "<td>" . $empresa['nombre'] . "</td>";
                        echo "<td>" . $listadoVentas[$i]['fecha'] . "</td>";
                        echo "<td>" . $listadoVentas[$i]['fechacobro'] . "</td>";
                        echo "<td>" . $listadoVentas[$i]['importe'] . "</td>";
                        echo "<td> <button type='button' class='btn' style='background-color: #1e989e;' onclick='ventasComercial(" . $listadoVentas[$i]['idempresa'] . ")'>Consultar <img src='images/iconos/info-circle.svg' class='ml-5'> </button> </td>";

                        echo "</tr>";

                        $total = $total + intval($listadoVentas[$i]['importe']);

                    }

                    echo "<tr>";
                    echo "<td colspan=4 col=4 class='text-end'> <b> TOTAL: </b></td>";
                    echo "<td> <b> $total € </b> </td>";
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