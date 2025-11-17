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

$fecha_informe = new DateTime();
$dias_habiles_a_restar = 4;
$dias_restados = 0;

while ($dias_restados < $dias_habiles_a_restar) {
    $fecha_informe->modify('-1 day');
    // N es la representación numérica ISO-8601 del día de la semana (1 para lunes hasta 7 para domingo)
    if ($fecha_informe->format('N') < 6) { // Es un día laborable (Lunes a Viernes)
        $dias_restados++;
    }
}

$meses_es = [
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
    7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
];
$mes_actual = $meses_es[(int)$fecha_informe->format('n')];


?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RLT</title>
    <script src="js/crearPDF.js"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            width: 170mm;
            margin: auto;
            font-size: 14px;
            font-family: Calibri;
            -webkit-print-color-adjust: exact;
            position: relative; /* Added for positioning context */
            /* Para Chrome, Safari, Opera */
            print-color-adjust: exact;
            /* Para Firefox */
        }

        input {
            font-size: inherit !important;
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
            font-size: 15px;
            background: linear-gradient(to bottom, #d5ceceff, #bcb7b7ff) !important;
            border-color: #bcbcbc !important;
            -webkit-print-color-adjust: exact;
            /* Para Chrome, Safari, Opera */
            print-color-adjust: exact;
            /* Para Firefox */
        }

        .text-justify {
            text-align: justify;
        }

        .border-bottom-dotted {
            border-bottom: 2px dotted black !important;
        }

        .legal-disclaimer {
            position: absolute;
            left: -8mm; /* Position it further to the left to avoid overlap */
            bottom: 0; /* Position at the bottom of the page */
            width: 270mm; /* This will become the height when rotated (standard A4 height) */
            height: 10mm; /* This will become the width when rotated (desired width of the text band) */
            font-size: 8px; /* Small font size */
            color: #555; /* Slightly lighter color for legal text */
            transform-origin: 0 100%; /* Rotate around the bottom-left corner of the element */
            transform: rotate(-90deg); /* Rotate counter-clockwise (text reads bottom-up) */
            padding-right: 5mm;
            box-sizing: border-box;
            text-align: left;
            line-height: 1.2;
            z-index: 1000; /* Ensure it's on top of other content */
        }

        .legal-disclaimer p {
            margin: 0; /* Remove default paragraph margin */
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
                <img src="images/imagen_formación.jpg" height="50" width="240">
            </div>
            <div class="col-7 text-end">
                <img src="images/logoWord.jpg" height="50" width="175">
            </div>
            <div class="col-12 text-center" style="font-size: 18px;">
                <div class="fw-bold">INFORME FAVORABLE DE LA REPRESENTACIÓN LEGAL DE LOS TRABAJADORES</div>
            </div>
        </div>
        <br>
        <div class="row">
            <label class="fw-bold border border-2 w-50 mt-1 py-1 rounded section-header">DATOS DE IDENTIFICACIÓN DE LA EMPRESA</label>
        </div>

        <div class="row mt-2 fst-italic align-items-baseline">
            <div class="col-9 d-flex align-items-baseline">
                <span class="me-2 text-nowrap">Razón Social:</span>
                <input type="text" class="form-control text-center flex-grow-1 border-0 border-bottom border-dark rounded-0 px-2" value="<?php echo htmlspecialchars($empresa['nombre']); ?>">
            </div>
            <div class="col-3 d-flex align-items-baseline">
                <span class="ms-1 me-2">CIF:</span>
                <input type="text" class="form-control text-center flex-grow-1 border-0 border-bottom border-dark rounded-0 px-2" value="<?php echo htmlspecialchars($empresa['cif']); ?>">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <p class="ms-5 mb-1">Dispone de:</p>
                <div class="border border-1 border-dark py-3 px-2">
                    <div class="ms-5">
                        <span class="me-2">❑</span><label>&nbsp;Un Centro de Trabajo. Dirección:</label>&nbsp;
                        <span class="border-bottom border-secondary" style="display: inline-block; width: 58%;">&nbsp;</span>
                    </div>
                </div>
                <br>
                <div class="border border-1 border-dark py-3 px-2">
                    <div class="ms-5 mt-1">
                        <span class="me-2">❑</span><label>&nbsp;Más de un Centro de Trabajo</label>
                    </div>
                    <div class="ms-5 ps-5 mt-1">
                        <span>o &nbsp;&nbsp; Ubicados en la misma Comunidad Autónoma (especificar):</span>
                        <span class="d-block ms-5 border-bottom border-dark w-75">&nbsp;</span>
                    </div>
                    <div class="ms-5 ps-5 mt-1">
                        <span>o &nbsp;&nbsp; Ubicados en más de una Comunidad Autónoma (especificar):</span>
                        <span class="d-block ms-5 border-bottom border-dark w-75">&nbsp;</span>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <label class="fw-bold border border-2 w-50 mt-1 py-1 rounded section-header">DATOS DE LA ENTIDAD ORGANIZADORA</label>
        </div>
        <br>
        <p>Nombre: DIXMA. con CIF: E27876325, y domicilio social en Ctra. De Madrid, 152 - 1ª planta - 36318 - Vigo</p>
        <div class="row">
            <label class="fw-bold border border-2 w-75 mt-1 py-1 rounded section-header">INFORME REPRESENTACIÓN LEGAL DE LOS TRABAJADORES</label>
        </div>

        <div class="row mt-2">
            <div class="col-12">
                <p class="fw-bold fst-italic text-justify">La REPRESENTACIÓN LEGAL DE LOS TRABAJADORES afirma conocer la denominación, descripción y
                    objetivos de las acciones formativas a desarrollar, colectivos destinatarios y número de participantes, el calendario previsto de ejecución, los medios pedagógicos, los criterios de selección de los participantes, el lugar previsto de impartición de las acciones formativas y el balance de las acciones formativas desarrolladas en el ejercicio precedente.</p>
                <p class="text-justify">Por medio del presente documento <strong>Representación Legal de los Trabajadores INFORMA
                        FAVORABLEMENTE sobre el Plan de Formación,</strong> de conformidad con el el artículo 13 del Real Decreto
                    694/2017, de 3 de julio por la que se regula el Sistema de Formación Profesional para el Empleo en el ámbito
                    laboral.
                </p>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12">
                <p>En <input type="text" class="border-0 text-center border-bottom-dotted" style="width: 150px;" value="Vigo">, a <input type="text" class="border-0 text-center border-bottom-dotted" style="width: 40px;" value="<?php echo $fecha_informe->format('d'); ?>"> de <input type="text" class="border-0 text-center border-bottom-dotted" style="width: 120px;" value="<?php echo ucfirst($mes_actual); ?>"> de <?php echo $fecha_informe->format('Y'); ?></p>
            </div>
        </div>

        <div class="row mt-2 justify-content-between">
            <div class="col-5 ms-3">
                Por la Empresa
            </div>
            <div class="col-5">
                Por la Representación Trabajadores
            </div>
        </div>
        <div class="row mt-5 justify-content-between">
            <div class="col-5 ms-3">
                Nombre:………………………………………………………
            </div>
            <div class="col-5">
                Nombre:………………………………………………………
            </div>
        </div>
        <div class="row mt-3 justify-content-between">
            <div class="col-5 ms-3">
                N.I.F/C.I.F.&nbsp;……………………………………………………
            </div>
            <div class="col-5">
                N.I.F/C.I.F.&nbsp;……………………………………………………
            </div>
        </div>
    </div>

    <div class="legal-disclaimer">
        <p>Datos del responsable del tratamiento: Identidad: DIXMA - NIF: E27876325 - Dirección postal: CTRA. DE MADRID, 152, 36318, VIGO, PONTEVEDRA - Teléfono: 604067035 - Correo electrónico: <a href="mailto:info@dixmaformacion.com">info@dixmaformacion.com</a> “ Le informamos que tratamos la información que nos facilita con el fin de prestarles el servicio solicitado y realizar su facturación. Los datos
            proporcionados se conservarán mientras se mantenga la relación comercial o durante el tiempo necesario para cumplir con las obligaciones legales y atender las posibles responsabilidades que pudieran derivar del cumplimiento de la finalidad para la que los datos fueron recabados. Los datos no se cederán a terceros salvo en los casos en que exista una obligación legal.
            Usted tiene derecho a obtener información sobre si en DIXMA estamos tratando sus datos personales, por lo que puede ejercer sus derechos de acceso, rectificación, supresión y portabilidad de datos y oposición y limitación a su tratamiento ante DIXMA, CTRA. DE MADRID, 152, 36318, VIGO, PONTEVEDRA o en la dirección de correo electrónico info@dixmaformacion.com,
            adjuntando copia de su DNI o documento equivalente. Asimismo, y especialmente si considera que no ha obtenido satisfacción plena en el ejercicio de sus derechos, podrá presentar una reclamación ante la autoridad nacional de control dirigiéndose a estos efectos a la Agencia Española de Protección de Datos, C/ Jorge Juan, 6 – 28001 Madrid.
            Asimismo, solicitamos su autorización para ofrecerle productos y servicios relacionados con los contratados y fidelizarle como cliente.” SI ❑ NO ❑</p>
    </div>

</body>

</html>