<?php

    include "funciones/conexionBD.php";
    include "funciones/funcionesLlamadas.php";
    include "funciones/funcionesEmpresa.php";

    session_start();

    if(empty($_SESSION)){

        header("Location: index.php");

    }

    date_default_timezone_set("Europe/Madrid");
    setlocale(LC_ALL, "spanish");
    $fechaHoy = date('Y-m-d');

    if(isset($_GET['consultar']) && $_SERVER['REQUEST_METHOD'] == 'GET'){

            if($empresas = buscarPorSectores($_GET['sector'])){

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
    <title>Call Center</title>
    <link href="css/bootstrap.min.css" rel="stylesheet"></link>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/arraySector.js"></script>
    <script src="js/botonConsultar.js"></script>
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

            <div class="col-md-10 col-12" id="formSectores">

                <form method="GET">

                    <h2 class="text-center mt-2 pt-2 pb-3 mb-md-5 mb-3 border border-5 rounded" style="background-color: #b0d588; letter-spacing: 7px;">SECTORES</h2>

                    <div class="container-fluid">

                        <div class="row d-flex justify-content-center">

                            <div class="form-group col-12 col-md-4 text-center">
                                
                                <label class="form-label"><b>Sector:</b></label>
                                <select class="form-select" name="sector" id="sectores" required style="text-transform:uppercase">

                                </select>


                            </div>

                            <div class="row d-flex justify-content-center">

                                <input type="submit" class="btn mb-3 mt-3 col-12 col-md-4" style="background-color: #1e989e" value="Buscar" name="consultar">

                            </div>
                            
                        </div>

                    </div>

                </form>

            </div>

            <?php

                if(!empty($empresas)){

                    echo "<script>";
                    echo "$('#formSectores').remove();";
                    echo "</script>";

                    echo "<div class='col-md-10 col-12 mt-2 table-responsive'>";

                    echo " <h2 class='text-center mt-2 pt-2 pb-3 mb-md-3 mb-3 border border-5 rounded' style='background-color: #b0d588; letter-spacing: 7px;'>SECTORES</h2>";

                    echo "<table class='table table-striped table-bordered table-sm text-center align-middle'>";
                    echo "<tr class='' style='background-color: #8fd247;'>";
                    echo  "<th> ID </th>";
                    echo  "<th> Nombre </th>";
                    echo  "<th> Sector </th>";
                    echo  "<th></th>";
                    echo "</tr>";
                    $redirectTo="sectores.php?sector=".urlencode($_GET['sector'])."&consultar=Buscar";
                    for($i=0; $i < count($empresas); $i++){

                    echo "<tr>";

                    echo "<td>" . $empresas[$i]['idempresa'] . "</td>";
                    echo "<td>" . $empresas[$i]['nombre'] . "</td>";
                    echo "<td>" . $empresas[$i]['sector'] . "</td>";
                    echo "
                    <td> 
                        <form action='pedirCitaForm.php'>
                            <input type='hidden' name='idEmpresa' value='{$empresas[$i]['idempresa']}'>
                            <input type='hidden' name='idLlamada' value=''>
                            <input type='hidden' name='tipo' value=''>
                            <input type='hidden' name='redirect' value='{$redirectTo}'>
                            <button type='submit' class='btn' style='background-color: #1e989e;'>Consultar <img src='images/iconos/info-circle.svg' class='ml-5'> </button> 
                        </form>
                    </td>";

                    echo "</tr>";
                    
                    }

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