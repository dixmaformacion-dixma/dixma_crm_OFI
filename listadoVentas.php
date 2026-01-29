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

    if(isset($_GET['consultar']) && $_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['consultar'] == "Buscar"){

        $fechaInicio = date('d-m-Y', strtotime($_GET['fechaInicio']));
        $fechaFin = date('d-m-Y', strtotime($_GET['fechaFin']));

        if($listadoVentas = listadoVentasEmpresas($fechaInicio, $fechaFin)){


        } else {

            echo "<div class='alert alert-danger' role='alert'>No se encuentra ninguna empresa</div>";

        }

    }

    if(isset($_GET['consultar']) && $_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['consultar'] == "Eliminar" && isset($_GET['idventa'])){
        if(eliminarVenta($_GET['idventa'])){
            echo "<div class='sucess'>venta eliminada exitosamente</div>";
        }else{
            echo "<div class='alert alert-danger' role='alert'>No se pudo eliminar esta venta</div>";
        }
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
        $menuaction = 'administracion';
        require_once './template-parts/header/menu_top.php' 
    ?>

    <!-- Menu lateral y formulario -->

    <div class="container-fluid">

        <div class="row">

            <?php require_once("template-parts/leftmenu/administracion.template.php"); ?>

            <div class="col-md-10 col-12" id="formListado">

                <form method="GET">

                    <h2 class="text-center mt-2 pt-2 pb-3 mb-md-5 mb-3 border border-5 rounded" style="background-color: #b0d588; letter-spacing: 7px;">LISTADO VENTAS</h2>

                    <div class="container-fluid">

                        <div class="row d-flex justify-content-center">

                            <div class="form-group col-12 col-md-4 text-center">
                                
                                <label class="form-label"><b>Fecha inicio:</b></label>
                                <input type="date" name="fechaInicio" value="<?php echo $fechaHoy ?>" class="form-control"></input>

                            </div>

                            <div class="form-group col-12 col-md-4 text-center">
                                
                                <label class="form-label"><b>Fecha fin:</b></label>
                                <input type="date" name="fechaFin" value="<?php echo $fechaHoy ?>" class="form-control"></input>

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
                        $onclick = 'onclick=\'
                        if (confirm("Esta acción es irreversible, ¿estás absolutamente seguro de que deseas eliminar esta venta?!")) {
                            window.location.href = "listadoVentas.php?idventa='.$listadoVentas[$i]['idventa'].'&consultar=Eliminar";
                        }
                        \'';

                        echo "<tr>";

                        echo "<td>" . $listadoVentas[$i]['idventa'] . "</td>";
                        echo "<td>" . $empresa['nombre'] . "</td>";
                        echo "<td>" . $listadoVentas[$i]['fecha'] . "</td>";
                        echo "<td>" . $listadoVentas[$i]['fechacobro'] . "</td>";
                        echo "<td>" . $listadoVentas[$i]['importe'] . "</td>";
                        echo "<td> <button type='button' class='btn btn-primary' style='background-color: #1e989e;' onclick='consultaAdministracion(" . $listadoVentas[$i]['idempresa'] . ")'>Consultar <img src='images/iconos/info-circle.svg' class='ml-5' style='filter: invert(1);'></button>";
                        echo "<button type='button' class='btn btn-danger' ".$onclick."><img src='images/iconos/x-circle-fill.svg' class='ml-5' style='filter: invert(1);'> </button>";
                        echo "<a href='editarVentas.php?idventa=".$listadoVentas[$i]['idventa']."' type='button' class='btn btn-primary'><img src='images/iconos2/pencil-square.svg' class='ml-5' style='filter: invert(1);'></a></td>";

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