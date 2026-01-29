<?php

    include "funciones/conexionBD.php";
    include "funciones/funcionesLlamadas.php";
    include "funciones/funcionesEmpresa.php";
    include "funciones/funcionesContenidos.php";

    session_start();

    if(empty($_SESSION)){

        header("Location: index.php");

    }

    date_default_timezone_set("Europe/Madrid");
    setlocale(LC_ALL, "spanish");
    
    $empresas = getEmpresasConCreditosACaducar();
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
                <h2 class="text-center mt-2 pt-2 pb-3 mb-md-2 mb-3 border border-5 rounded" style="background-color: #b0d588; letter-spacing: 7px;">EMPRESAS CON CREDITOS A CADUCAR</h2>
                <?php 
                    sqlToHtml($empresas,[
                        'idempresa',
                        'nombre',
                        'creditoCaducar',
                        'acciones'
                    ],[
                        'creditoCaducar'=>'IMPORTE PEND. 2 AÑOS'
                    ],[
                        'acciones'=>function($val,$row){
                            return '<a href="/consultarEmpresa.php?idEmpresa='.$row['idempresa'].'&tipo=undefined&fechaInicio=&fechaFin=&poblacion=&provincia=&redirect='.urlencode('credito_pendiente.php').'" class="btn btn-info">Consultar</a>';
                        }
                    ]) 
                ?>
            </div>

        </div>

    </div>

    <footer class="border-top border-secondary" style="background-color:#e4e4e4; height: 75px;">

            <p class="text-center mt-md-4" style='color: #8fd247;'> <b> © Dixma Formación 2022. | Ctra. Madrid 152, Vigo 36318 | info@dixmaformacion.com | Tlf: +34 604 067 035 </b> </p>

    </footer>
    
</body>
</html>