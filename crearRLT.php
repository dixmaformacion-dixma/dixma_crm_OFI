<?php

include "funciones/conexionBD.php";
include "funciones/funcionesEmpresa.php";
include "funciones/funcionesVentas.php";

setlocale(LC_ALL, 'ES_es');

session_start();

if (empty($_SESSION)) {

    header("Location: index.php");
}

date_default_timezone_set("Europe/Madrid");
setlocale(LC_ALL, "spanish");

$empresa = cargarEmpresa($_GET['idEmpresa']);

$venta = cargarVenta($_GET['idEmpresa']);


?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crear RLT</title>
    <script src="js/crearPDF.js"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            width: 180mm;
            margin: auto;
            font-size: 11px;
        }

        input {
            font-size: 11px !important;
        }

        @media print {
            #volver {
                display: none;
            }

            input {
                border: none !important;
            }
        }

        .section-header {
            font-size: 12px;
            background-color: #bcbcbc;
        }
    </style>
</head>

<body>

    <div class="container mt-4 mb-4">

        <div class="row">
            <div class="col-12 text-center" id="volver">
                <button class="col-5 btn btn-success text-center" onclick="imprimir()"><img class="me-3" src="images/iconos/printer.svg">IMPRIMIR</button>
                <button class="col-5 btn btn-danger text-center" onclick="window.history.back();"><img class="me-3" src="images/iconos/arrow-left.svg">VOLVER</button>
            </div>
        </div>

        <div class="row pt-3 pb-2 align-items-center border border-dark border-1 rounded-4">
            <div class="col-5">
                <img src="images/imagen_formación.jpg" height="60" width="250">
            </div>
            <div class="col-7 text-end">
                <img src="images/logoWord.jpg" height="60" width="180">
            </div>
            <div class="col-12 text-center mt-2">
                <h6 class="fw-bold">INFORME FAVORABLE DE LA REPRESENTACIÓN LEGAL DE LOS TRABAJADORES</h6>
            </div>
        </div>
        <br>
        <div class="row">
            <label class="fw-bold border border-2 w-50 mt-1 py-1 rounded section-header">DATOS DE LA EMPRESA (DEUDOR)</label>
        </div>

        <div class="row mt-3 border border-2 border-dark p-2">
            <div class="col-12">
                <h5 class="fw-bold">DATOS DE LA EMPRESA</h5>
                <div class="row">
                    <label class="col-3">RAZÓN SOCIAL:</label>
                    <div class="col-9">
                        <input class="form-control form-control-sm" type="text" value="<?php echo htmlspecialchars($empresa['nombre']); ?>">
                    </div>
                </div>
                <div class="row mt-1">
                    <label class="col-3">CIF:</label>
                    <div class="col-9">
                        <input class="form-control form-control-sm" type="text" value="<?php echo htmlspecialchars($empresa['cif']); ?>">
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3 border border-2 border-dark p-2">
            <div class="col-12">
                <h5 class="fw-bold">DATOS DE LA REPRESENTACIÓN LEGAL DE LOS TRABAJADORES</h5>
                <p>A la atenci&oacute;n de: <input type="text" class="form-control-plaintext d-inline-block w-75 border-bottom"></p>
                <p>En su calidad de: <input type="text" class="form-control-plaintext d-inline-block w-75 border-bottom"></p>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12">
                <p>De acuerdo con lo establecido en la normativa reguladora del subsistema de formaci&oacute;n profesional para el empleo en el &aacute;mbito laboral, le informamos sobre las acciones formativas de las que van a participar los trabajadores de esta empresa y para las que se va a solicitar la correspondiente bonificaci&oacute;n.</p>
            </div>
        </div>

        <div class="row mt-3 border border-2 border-dark p-2">
            <div class="col-12">
                <h5 class="fw-bold">DATOS DE LAS ACCIONES FORMATIVAS</h5>
                <div class="row mt-1">
                    <label class="col-4">Denominaci&oacute;n de la acci&oacute;n formativa:</label>
                    <div class="col-8">
                        <input class="form-control form-control-sm" type="text" value="">
                    </div>
                </div>
                <div class="row mt-1">
                    <label class="col-4">Colectivo destinatario:</label>
                    <div class="col-8">
                        <input class="form-control form-control-sm" type="text" value="">
                    </div>
                </div>
                <div class="row mt-1">
                    <label class="col-4">Calendario previsto de ejecuci&oacute;n:</label>
                    <div class="col-8">
                        <input class="form-control form-control-sm" type="text" value="">
                    </div>
                </div>
                <div class="row mt-1">
                    <label class="col-4">Horario:</label>
                    <div class="col-8">
                        <input class="form-control form-control-sm" type="text" value="">
                    </div>
                </div>
                <div class="row mt-1">
                    <label class="col-4">Lugar de impartici&oacute;n:</label>
                    <div class="col-8">
                        <input class="form-control form-control-sm" type="text" value="">
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-6">
                <p>Recib&iacute; y conforme:</p>
                <p>Fdo.:</p>
            </div>
            <div class="col-6 text-end">
                <p>En <input type="text" class="form-control-plaintext d-inline-block w-25 border-bottom" value="Vigo">, a <input type="text" class="form-control-plaintext d-inline-block w-auto border-bottom"> de <input type="text" class="form-control-plaintext d-inline-block w-auto border-bottom"> de <input type="text" class="form-control-plaintext d-inline-block w-auto border-bottom"></p>
                <p class="mt-4">Fdo.:</p>
            </div>
        </div>


    </div>

</body>

</html>