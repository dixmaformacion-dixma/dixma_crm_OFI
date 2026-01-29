<?php

    include "funciones/conexionBD.php";
    include "funciones/funcionesEmpresa.php";
    include "funciones/funcionesLlamadas.php";

    session_start();

    if(empty($_SESSION)){

        header("Location: index.php");

    }

    date_default_timezone_set("Europe/Madrid");
    setlocale(LC_ALL, "spanish");

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
    <script src="js/botonWord.js"></script>
    <link rel="icon" href="images/favicon.ico">
</head>
<body style="background-color:#f3f6f4;">

    <!-- Menu cabecera -->

    <?php 
        $menuaction = 'callcenter';
        require_once './template-parts/header/menu_top.php' 
    ?>

    <!-- Menu lateral y formulario -->

    <div class="container-fluid">

        <div class="row">

            <?php require_once './template-parts/leftmenu/callcenter.template.php'; ?>

            <div class="col-md-10 col-12" id="formBusqueda">

                    <h2 class="text-center mt-2 pt-2 pb-3 mb-md-2 mb-3 border border-5 rounded" style="background-color: #b0d588; letter-spacing: 7px;">HACER SEGUIMIENTO</h2>

                    <div class="container-fluid">

                        <div class="row d-flex justify-content-center">

                            <div class="col-md-12 col-12">
                                
                                <?php

                                    if($llamadas = hacerSeguimientoCallCenter()){

                                        $empresas = [];

                                        for($i=0; $i < count($llamadas); $i++){
                                            
                                            $empresa = cargarEmpresa($llamadas[$i]['idempresa']);
                                            array_push($empresas, $empresa);

                                        }

                                        echo "<table class='table table-striped table-bordered table-sm text-center align-middle'>";
                                        echo "<tr style='background-color: #8fd247;'>";
                                        echo "<th> ID </th>";
                                        echo "<th> Nombre </th>";
                                        echo "<th> Poblacion </th>";
                                        echo "<th> Fecha </th>";
                                        echo "<th> Tipo Seguimiento </th>";
                                        echo "<th> </th>";
                                        echo "</tr>";

                                        for($i=0; $i < count($empresas); $i++){

                                            echo "<tr>";
                                            echo "<td>" . $empresas[$i]['idempresa'] . "</td>";
                                            echo "<td>" . $empresas[$i]['nombre'] . "</td>";
                                            echo "<td>" . $empresas[$i]['poblacion'] . "</td>";
                                            echo "<td>" . date("d/m/Y",strtotime($llamadas[$i]['fechapendiente']))."</td>";
                                            echo "<td>" . $llamadas[$i]['tipo_seguimiento'] . "</td>";
                                            
                                            $redirectTo="hacerSeguimiento.php";
                                            echo "
                                            <td> 
                                                <form action='pedirCitaForm.php'>
                                                    <input type='hidden' name='idEmpresa' value='{$empresas[$i]['idempresa']}'>
                                                    <input type='hidden' name='idLlamada' value='{$llamadas[$i]['idllamada']}'>
                                                    <input type='hidden' name='tipo' value='seguimiento'>
                                                    <input type='hidden' name='redirect' value='{$redirectTo}'>
                                                    <button type='submit' class='btn' style='background-color: #1e989e;'>Consultar <img src='images/iconos/info-circle.svg' class='ml-5'> </button> 
                                                </form>
                                            </td>";

                                            echo "</tr>";

                                        }

                                        echo "</table>";

                                    }

                                ?>

                            </div>

                        </div>

                    </div>

            </div>

        </div>

    </div>

    <footer class="border-top border-secondary" style="background-color:#e4e4e4; height: 75px;">

            <p class="text-center mt-md-4" style='color: #8fd247;'> <b> © Dixma Formación 2022. | Ctra. Madrid 152, Vigo 36318 | info@dixmaformacion.com | Tlf: +34 604 067 035 </b> </p>

    </footer>

</body>
</html>