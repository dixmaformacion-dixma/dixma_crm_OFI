<?php

    include "funciones/conexionBD.php";
    include "funciones/funcionesLlamadas.php";
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

            $datosOperador = [
                "operador" => $_GET['codigoOperador'],
                "fecha" => $_GET['fecha'],
            ];

            if($datosLlamadas = controlLlamadas($datosOperador)){


            } else {

                echo "<div class='alert alert-danger' role='alert'>No se encuentra ninguna llamada pendiente</div>";

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

            <div class="col-md-10 col-12" id="formLlamadas">

                <form method="GET">

                    <h2 class="text-center mt-2 pt-2 pb-3 mb-md-5 mb-3 border border-5 rounded" style="background-color: #b0d588; letter-spacing: 7px;">CONTROL DE LLAMADAS</h2>

                    <div class="container-fluid">

                        <div class="row d-flex justify-content-center">

                            <div class="form-group col-12 col-md-4 text-center">
                                
                                <label class="form-label"><b>Fecha:</b></label>
                                <input type="date" class="form-control col-12" name="fecha" value="<?php echo $fechaHoy ?>"></input>

                            </div>

                            <div class="form-group col-12 col-md-4 text-center">
                                
                                <label class="form-label"><b>Comercial:</b></label>
                                    <?php 
                                    
                                        if($_SESSION['rol'] != 'admin'){

                                            echo "<input class='form-control col-12' type='text' name='codigoOperador' readonly value='" . $_SESSION['codigoUsuario'] . "'> </input>";

                                            } else {

                                                $comerciales = cargarComerciales();

                                                echo "<select id='selectComercial' name='codigoOperador' class='form-select'>";

                                            for($i=0; $i < count($comerciales); $i++){
                                                
                                                if($comerciales[$i]['activo'] == 1){

                                                echo "<option value='" . $comerciales[$i]['codigousuario'] . "'>" . $comerciales[$i]['nombre'] . "</option>";

                                                }

                                            }

                                            echo "</select>";

                                        }

                                    ?>

                            </div>
                            
                            <div class="row d-flex justify-content-center">

                                <input type="submit" class="btn mb-3 mt-3 col-12 col-md-8" style="background-color: #1e989e" value="Buscar" name="consultar">

                            </div>
                            
                        </div>

                    </div>

                </form>

            </div>

            <?php

                if(!empty($datosLlamadas)){

                    echo "<script>";
                    echo "$('#formLlamadas').remove();";
                    echo "</script>";

                    echo "<div class='col-md-10 col-12 mt-2 table-responsive'>";

                    echo " <h2 class='text-center mt-2 pt-2 pb-3 mb-md-3 mb-3 border border-5 rounded' style='background-color: #b0d588; letter-spacing: 7px;'>CONTROL DE LLAMADAS</h2>";

                    echo "<table class='table table-striped table-bordered table-sm text-center align-middle'>";
 
                        echo "<div class='border rounded border-5 mt-3 col-12' id='datosLlamada'>";
                        echo "<label class='border rounded text-center d-inline-flex flex-column col-10 col-md-2 ms-4 mt-2 ms-md-5 mt-md-2 ' > <b>Total de empresas:</b> " . $datosLlamadas['numeroEmpresas'][0] . "</label>";
                        echo "<label class='border rounded text-center d-inline-flex flex-column col-10 col-md-2 ms-4 mt-2 ms-md-5 mt-md-2 ' > <b>Total de llamadas:</b> " . $datosLlamadas['numeroLlamadas'][0] . "</label>";
                        echo "<label class='border rounded text-center d-inline-flex flex-column col-10 col-md-2 ms-4 mt-2 ms-md-5 mt-md-2  text-danger' > <b>Pendientes:</b> " . $datosLlamadas['pendientes'][0] . "</label>";
                        echo "<label class='border rounded text-center d-inline-flex flex-column col-10 col-md-2 ms-4 mt-2 ms-md-5 mt-md-2  text-success' > <b>Citas:</b> " . $datosLlamadas['citas'][0] . "</label>";
                        echo "<label class='border rounded text-center d-inline-flex flex-column col-10 col-md-2 ms-4 mt-2 ms-md-5 mt-md-2 ' > <b>Han gastado credito:</b> " . $datosLlamadas['credito'][0] . "</label>";
                        echo "<label class='border rounded text-center d-inline-flex flex-column col-10 col-md-2 ms-4 mt-2 ms-md-5 mt-md-2 ' > <b>Con otra empresa:</b> " . $datosLlamadas['otraEmpresa'][0] . "</label>";
                        echo "<label class='border rounded text-center d-inline-flex flex-column col-10 col-md-2 ms-4 mt-2 ms-md-5 mt-md-2  mb-2' '> <b>Autonomos:</b> " . $datosLlamadas['autonomos'][0] . "</label>";
                        echo "<label class='border rounded text-center d-inline-flex flex-column col-10 col-md-2 ms-4 mt-2 ms-md-5 mt-md-2  mb-2' '> <b>No les interesa:</b> " . $datosLlamadas['noInteresa'][0] . "</label>";
                        echo "<label class='border rounded text-center d-inline-flex flex-column col-10 col-md-2 ms-4 mt-2 ms-md-5 mt-md-2  mb-2' '> <b>Telefono no existe:</b> " . $datosLlamadas['noTelefono'][0] . "</label>";
                        echo "<label class='border rounded text-center d-inline-flex flex-column col-10 col-md-2 ms-4 mt-2 ms-md-5 mt-md-2  mb-2' '> <b>No LOPD:</b> " . $datosLlamadas['noLOPD'][0] . "</label>";
                        echo "<label class='border rounded text-center d-inline-flex flex-column col-10 col-md-2 ms-4 mt-2 ms-md-5 mt-md-2  mb-2' '> <b>Otros:</b> " . $datosLlamadas['otros'][0] . "</label>";

                        echo "</div>";

                    echo "</table>";

                }

                echo "</div>";

                ?>

        </div>

    </div>

    <footer class="border-top border-secondary" style="background-color:#e4e4e4; height: 75px;">

            <p class="text-center mt-md-4" style='color: #8fd247;'> <b> © Dixma Formación 2022. | Ctra. Madrid 152, Vigo 36318 | info@dixmaformacion.com | Tlf: +34 604 067 035 </b> </p>

    </footer>
    
</body>
</html>