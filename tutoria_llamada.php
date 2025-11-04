<?php

    include "funciones/conexionBD.php";
    include "funciones/funcionesAlumnosCursos.php";
    include "funciones/funcionesEmpresa.php";

    session_start();

    if(empty($_SESSION)){

        header("Location: index.php");

    }

    date_default_timezone_set("Europe/Madrid");
    setlocale(LC_ALL, "spanish");

    include "tutoria_editar_seguimentos_function.php";
    include "tutoria_insertar_commentario_function.php";

    $date = date("Y-m-d");
    if(isset($_GET['date']) && $_GET['date'] != ''){
        $date = $_GET['date'];
    }
    $Tipo_Venta = "Bonificado";
    if(isset($_GET['Tipo_Venta']) && $_GET['Tipo_Venta'] != ''){
        $Tipo_Venta = $_GET['Tipo_Venta'];
    }
    $missedCalls = "on";
    if(isset($_GET['missedCalls']) && $_GET['missedCalls'] == 'off'){
        $missedCalls = $_GET['missedCalls'];
    }
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es" lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutoria</title>
    <link href="css/bootstrap.min.css" rel="stylesheet"></link>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/tutoria.js"></script>
    <link rel="icon" href="images/favicon.ico">
</head>
<body style="background-color:#f3f6f4;">

    <?php require_once("template-parts/header/header.template.php"); ?>

    <!-- Menu lateral y formulario -->

    <div class="container-fluid">

        <div class="row">

            <?php require_once("template-parts/leftmenu/tutoria.template.php"); ?>

            <div class="col-md-10 col-12" id="formBusqueda">

                <form method="GET">

                    <h2 class="text-center mt-2 pt-2 pb-3 mb-md-5 mb-3 border border-5 rounded" style="background-color: #b0d588; letter-spacing: 7px;">LLAMADA</h2>

                    <div class="container-fluid">
                        <form method="get">
                            <div class="mx-auto row col-md-7 col-12">
                                <input name="date" class="form-control" value="<?php echo $date ?>" type="date"></input>
                                <div class="row">
                                    <label class='col-12'>
                                        <b>Tipo venta:</b>
                                        <input class="form-check-input" type="radio" name="Tipo_Venta" Value="Todos" <?php if($Tipo_Venta == "Todos"){echo "checked";} ?>>
                                        <label class="form-check-label" for="Todos">
                                            Todos
                                        </label>
                                        <input class="form-check-input" type="radio" name="Tipo_Venta" Value="Bonificado" <?php if($Tipo_Venta == "Bonificado"){echo "checked";} ?>>
                                        <label class="form-check-label" for="Bonificado">
                                            Bonificado
                                        </label>
                                        <input class="form-check-input" type="radio" name="Tipo_Venta" Value="Privado" <?php if($Tipo_Venta == "Privado"){echo "checked";} ?>>
                                        <label class="form-check-label" for="Privado">
                                            Privado
                                        </label>
                                    </label>
                                </div>
                                <div class="col-12">
                                    <input type="hidden" id="hidden_status_1" name="missedCalls" value="off" />
                                    <input type="checkbox" class="form-check-input" name="missedCalls" id="missedCalls" value="on" <?php if(isset($_GET['missedCalls']) && $_GET['missedCalls'] == "off"){}else{echo "checked";}?>>
                                    <label class="form-check-label" for="missedCalls">
                                        llamadas perdidas antes de esta fecha
                                    </label>
                                </div>
                                
                                <input class="form-control btn btn-primary" style="background-color:#1e989e" type="submit" value="Search"></input>
                            </div>
                        </form>
                        <?php
                            $llamadas = cargarCursoLlamadas($date, $Tipo_Venta, $missedCalls);
                            $numero_llamadas = $llamadas ? count($llamadas) : 0;
                        ?>
                        <div class="text-center my-3">
                            <h5>Total de llamadas para hoy: <span class="badge bg-info text-dark fs-6"><?php echo $numero_llamadas; ?></span></h5>
                            <p class="text-muted">Resultados para la fecha: <?php echo $date; ?> (Tipo venta: <?php echo $Tipo_Venta; ?>)</p>
                        </div>
                        <?php
                            if($llamadas){
                                foreach($llamadas as $llamada){
                                    include "template-parts/components/llamada.llamada.php";
                                }
                            }
                        ?>
                    </div>

                </form>

            </div>

        </div>

    </div>

    <footer class="border-top border-secondary" style="background-color:#e4e4e4; height: 75px;">

            <p class="text-center mt-md-4" style='color: #8fd247;'> <b> © Dixma Formación 2022. | Ctra. Madrid 152, Vigo 36318 | info@dixmaformacion.com | Tlf: +34 604 067 035 </b> </p>

    </footer>

</body>
</html>