<?php

    include "funciones/conexionBD.php";
    include "funciones/funcionesCitas.php";

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
        $menuaction = 'callcenter';
        require_once './template-parts/header/menu_top.php' 
    ?>

    <!-- Menu lateral y formulario -->

    <div class="container-fluid">

        <div class="row">

            <?php require_once './template-parts/leftmenu/callcenter.template.php'; ?>

            <div class="col-md-10 col-12" id="formCitas">

                <form method="GET">

                    <h2 class="text-center mt-2 pt-2 pb-3 mb-md-5 mb-3 border border-5 rounded" style="background-color: #b0d588; letter-spacing: 7px;">CITAS</h2>

                    <div class="container-fluid">

                        <div class="row d-flex justify-content-center">

                            <div class="form-group col-12 col-md-4 text-center">
                                
                                <label class="form-label"><b>Fecha:</b></label>
                                <input type="date" class="form-control col-10 col-md-3" value="<?php echo $fechaHoy ?>" name="fecha"></input>
                                <input type="submit" class="btn mb-3 mt-3 col-12 col-md-12" style="background-color: #1e989e" value="Buscar" name="consultar">

                            </div>

                        </div>

                    </div>

                </form>

            </div>

            <?php

                if(!empty($citas)){

                    echo "<script>";
                    echo "$('#formCitas').remove();";
                    echo "</script>";

                    echo "<div class='col-md-10 col-12 mt-2 table-responsive'>";

                    echo " <h2 class='text-center mt-2 pt-2 pb-3 mb-md-3 mb-3 border border-5 rounded' style='background-color: #b0d588; letter-spacing: 7px;'>CITAS</h2>";


                    for($i=0; $i < count($citas); $i++){


                        echo "<div class='container-fluid border rounded mt-2 mb-2 border-5 col-md-11 col-12' id='datosLlamada'>";
                        echo "<h3 class='text-center underline'> <b><u>" . $citas[$i]['nombre'] . "</u></b> </h3>";
                        echo "<label class='border col-md-1 col-12  text-center mt-2 mb-2'> <b>ID:</b> " . $citas[$i]['idempresa'] . "</label>";
                        echo "<br>";
                        //echo "<label class='col-md-3 col-12 ms-md-2'> <b>Nombre empresa:</b> " . $citas[$i]['nombre'] . "</label>";
                        echo "<label class='col-md-3 col-12 col-md-4 '> <b>Direccion:</b> " . $citas[$i]['calle'] . "</label>";
                        echo "<label class='col-md-3 col-12 col-md-4'> <b>Telefono:</b> " . $citas[$i]['telef1'] . "</label>";
                        echo "<label class='col-md-3 col-12 col-md-4'> <b>Persona contacto:</b> " . $citas[$i]['personacontacto'] . "</label>";
                        echo "<label class='col-md-3 col-12 col-md-4'> <b>Cita:</b> " . $citas[$i]['diacita'] . " " . $citas[$i]['fechacita'] . " " . $citas[$i]['horacita']  . "</label>";
                        echo "<label class='col-md-3 col-12 col-md-4'> <b>Poblacion:</b> " . $citas[$i]['poblacion'] . "</label>";
                        echo "<br>";
                        echo "<button type='button' class='btn col-md-1 col-12 col-md-12 mt-2 mb-2' style='background-color: #1e989e;' onclick='enviarConsulta(" . $citas[$i]['idempresa'] . ")'> Consultar <img src='images/iconos/info-circle.svg' class='ml-5'> </button>";
                        echo "</div>";

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