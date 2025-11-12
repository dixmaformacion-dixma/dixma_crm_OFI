<?php
// Mostrar errores para desarrollo
ini_set('display_errors', 1);
error_reporting(E_ALL);

include "funciones/conexionBD.php";

session_start();
if (empty($_SESSION)) {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['StudentCursoID'])) {
    die("Falta el parámetro StudentCursoID");
}

// Consulta para obtener los datos del alumno y curso
$conexionPDO = realizarConexion();
$sql = '
SELECT
    alumnos.nombre,
    alumnos.apellidos,
    alumnos.nif,
    alumnocursos.Denominacion,
    alumnocursos.Modalidad,
    alumnocursos.N_Horas,
    alumnocursos.Fecha_Inicio,
    alumnocursos.Fecha_Fin
FROM alumnocursos
JOIN alumnos ON alumnocursos.idAlumno = alumnos.idAlumno
WHERE StudentCursoID = ?
';
$stmt = $conexionPDO->prepare($sql);
$stmt->bindValue(1, $_GET['StudentCursoID'], PDO::PARAM_INT);
$stmt->execute();
$datos = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$datos) {
    die("No se encontraron datos");
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recepción de Material</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    </link>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <link rel="icon" href="images/favicon.ico">


</head>

<body id="bydo" class="body1">
    <div class="row mb-2 mt-2" id="volver">
        <div class="col-12 text-center">
            <button class="col-5 btn btn-success text-center" onclick="window.print()">
                <img class="me-3" src="./images/iconos/printer.svg">IMPRIMIR
            </button>
        </div>
    </div>
    <div class="logo-container mb-4">
        <img src="images/logoWord.jpg" class="logo-img">
    </div>
    <div class="pagewrapper" style="padding:1cm">
        <div class="page" style="background:fixed;">

            <div class="titulo-recepcion">RECEPCIÓN DE MATERIAL</div>
            <div class="texto-certificado" id="" style="text-align:justify; line-height:2.1;">
                <?php
                function formato_fecha($fecha)
                {
                    if (!$fecha) return '';
                    $f = date_create($fecha);
                    return $f ? date_format($f, 'd/m/Y') : $fecha;
                }
                ?>
                D/Dña. <?php echo mb_strtoupper(htmlspecialchars($datos['nombre'] . ' ' . $datos['apellidos'])); ?>, con N.I.F. nº <?php echo mb_strtoupper(htmlspecialchars($datos['nif'])); ?><br>
                Participante en el curso de <?php echo mb_strtoupper(htmlspecialchars($datos['Denominacion'])); ?> (<?php echo mb_strtoupper(htmlspecialchars($datos['Modalidad'])); ?>), de
                <?php echo mb_strtoupper(htmlspecialchars($datos['N_Horas'])); ?> horas de duración, celebrado desde el <?php echo formato_fecha($datos['Fecha_Inicio']); ?>
                hasta el <?php echo formato_fecha($datos['Fecha_Fin']); ?>, ha recibido el siguiente material:
                <br><br>
                -GUIA DIDÁCTICA CON CLAVES DE ACCESO A LA PLATAFORMA DE TELEFORMACIÓN
                <br>
                -CUESTIONARIO DE EVALUACIÓN DE LA CALIDAD
                <br><br><br><br>
                <div style="text-align: center;">Firma del alumno/a:</div>
                <br><br><br><br>
                <?php
                $fecha_inicio = $datos['Fecha_Inicio'];
                $dt_inicio = date_create($fecha_inicio);
                $dia_inicio = $dt_inicio ? date_format($dt_inicio, 'd') : '';
                $mes_inicio_num = $dt_inicio ? date_format($dt_inicio, 'n') : '';
                $anio_inicio = $dt_inicio ? date_format($dt_inicio, 'Y') : '';
                $meses_es = [1 => 'ENERO', 2 => 'FEBRERO', 3 => 'MARZO', 4 => 'ABRIL', 5 => 'MAYO', 6 => 'JUNIO', 7 => 'JULIO', 8 => 'AGOSTO', 9 => 'SEPTIEMBRE', 10 => 'OCTUBRE', 11 => 'NOVIEMBRE', 12 => 'DICIEMBRE'];
                $mes_inicio = isset($meses_es[(int)$mes_inicio_num]) ? $meses_es[(int)$mes_inicio_num] : '';
                ?>
                En VIGO, a <?php echo $dia_inicio; ?> de <?php echo $mes_inicio; ?> de <?php echo $anio_inicio; ?>
            </div>
            <br>
            <div style="font-size:0.65rem; color:#444; font-weight: bold;">
                Nota: Formación Cofinanciada según el Real Decreto 694/2017, de 3 de julio, la Ley 30/2015, de 9 de septiembre, el Real Decreto-ley 4/2015, de 22 de marzo, y la Orden TAS/718/2008, de 7 de marzo.
            </div>
        </div>

    </div>
    <style media="print">
        #boton-imprimir-wrapper {
            display: none !important;
        }
    </style>
    <style>
        * {
            box-sizing: border-box;
        }

        .body1 {
            width: 220mm;
            max-width: 220mm;
            margin-left: auto;
            margin-right: auto;
            font-family: Calibri, sans-serif;
            font-size: 1.1rem;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .pagewrapper {
            width: 200mm;
            height: 245mm;
            max-width: 200mm;
            max-height: 245mm;
            margin-left: auto;
            margin-right: auto;
            padding: 0cm 1cm 0.7cm 1cm !important;
        }

        .page {
            background-color: #ecfdfc;
            background-repeat: no-repeat;
            background-size: cover;
            height: 100%;
            padding: 0.75cm;
            position: relative;
            box-sizing: border-box;
        }

        .logo-container {
            width: 85%;
            margin-left: auto;
            margin-right: auto;
            background: #fff;
            border: 1px solid #000000ff;
            border-radius: 15px;
            padding: 12px 0;
            display: flex;
            justify-content: start;
            box-sizing: border-box;
            margin-bottom: 24px;
        }

        .logo-img {
            max-width: 100%;
            height: 1.8cm;
            margin-left: 0.85cm;
        }

        .titulo-recepcion {
            text-align: center;
            font-weight: bold;
            font-size: 1.3rem;
            margin-top: 22px;
            margin-bottom: 50px;
        }
    </style>

    <style media='print'>
        #contenidos {
            overflow: hidden;
        }

        #contenidosprivado {
            overflow: hidden;
        }

        #volver {
            display: none;
        }

    </style>
    <style id="media_print" media='print'>
        @page {
            size: 216mm 279mm;
            margin-top: 1.5cm;
            padding: 0 !important;
            height: 100%;
        }
    </style>
</body>


</html>