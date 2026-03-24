<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

function renderDocxDependencyError($title, $message, array $details = [])
{
    http_response_code(500);
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Error generando DOCX</title>
        <style>
            body {
                margin: 0;
                font-family: Arial, sans-serif;
                background-color: #f3f6f4;
                color: #1f2937;
            }
            .wrapper {
                max-width: 760px;
                margin: 40px auto;
                padding: 24px;
            }
            .panel {
                background-color: #ffffff;
                border: 2px solid #b0d588;
                border-radius: 10px;
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
                padding: 24px;
            }
            h1 {
                margin-top: 0;
                color: #c30d0d;
                font-size: 24px;
            }
            p {
                line-height: 1.5;
                margin-bottom: 12px;
            }
            ul {
                margin: 16px 0 0 20px;
                padding: 0;
            }
            li {
                margin-bottom: 8px;
            }
            .actions {
                margin-top: 20px;
            }
            .button {
                display: inline-block;
                padding: 10px 16px;
                background-color: #1e989e;
                color: #ffffff;
                text-decoration: none;
                border-radius: 6px;
            }
        </style>
    </head>
    <body>
        <div class="wrapper">
            <div class="panel">
                <h1><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></h1>
                <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
                <?php if (!empty($details)) { ?>
                    <ul>
                        <?php foreach ($details as $detail) { ?>
                            <li><?php echo htmlspecialchars($detail, ENT_QUOTES, 'UTF-8'); ?></li>
                        <?php } ?>
                    </ul>
                <?php } ?>
                <div class="actions">
                    <a class="button" href="javascript:history.back()">Volver</a>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

$possibleAutoloads = [
    __DIR__ . '/vendor/autoload.php',
    __DIR__ . '/librerias/PHPWord/vendor/autoload.php',
    __DIR__ . '/../vendor/autoload.php'
];

$autoload = null;
foreach ($possibleAutoloads as $pathAutoload) {
    if (!file_exists($pathAutoload)) {
        continue;
    }

    require_once $pathAutoload;

    if (class_exists('PhpOffice\\PhpWord\\PhpWord')) {
        $autoload = $pathAutoload;
        break;
    }
}

if ($autoload === null) {
    renderDocxDependencyError(
        'No se puede generar el DOCX',
        'No se ha encontrado una instalación válida de PHPWord en este CRM.',
        [
            'Comprueba que exista vendor/autoload.php con phpoffice/phpword instalado.',
            'Si el CRM usa una carpeta vendor propia para PHP 7.2, copia allí la dependencia compatible.',
            'Verifica también la ruta alternativa librerias/PHPWord/vendor/autoload.php si ese CRM la usa.'
        ]
    );
}

include __DIR__ . '/funciones/conexionBD.php';

session_start();
if (empty($_SESSION)) {
    header('Location: index.php');
    exit;
}

if (!isset($_GET['StudentCursoID']) || !is_numeric($_GET['StudentCursoID'])) {
    http_response_code(400);
    die('Falta el parametro StudentCursoID');
}

if (!class_exists('ZipArchive')) {
    renderDocxDependencyError(
        'No se puede generar el DOCX',
        'La extensión ZipArchive no está disponible en el servidor.',
        [
            'Activa la extensión zip en la configuración de PHP del CRM oficial.',
            'Reinicia Apache o PHP-FPM después de habilitar la extensión.',
            'Sin ZipArchive, PHPWord no puede crear archivos .docx.'
        ]
    );
}

$studentCursoID = (int) $_GET['StudentCursoID'];

$conexionPDO = realizarConexion();
$sql = '
SELECT
    alumnos.nombre,
    alumnos.apellidos,
    alumnocursos.Denominacion,
    alumnocursos.N_Horas,
    alumnocursos.Fecha_Inicio,
    alumnocursos.Fecha_Fin,
    alumnocursos.tutor
FROM alumnocursos
JOIN alumnos ON alumnocursos.idAlumno = alumnos.idAlumno
WHERE alumnocursos.StudentCursoID = ?
';

$stmt = $conexionPDO->prepare($sql);
$stmt->bindValue(1, $studentCursoID, PDO::PARAM_INT);
$stmt->execute();
$datos = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$datos) {
    http_response_code(404);
    die('No se encontraron datos para el curso solicitado');
}

function normalizarTextoPlano($valor)
{
    $valor = (string) $valor;
    $valor = html_entity_decode($valor, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    return trim(preg_replace('/\s+/', ' ', $valor));
}

function fechaPartesEspanol($fecha)
{
    if (empty($fecha) || $fecha === '0000-00-00') {
        return [
            'dia' => '',
            'mes' => '',
            'anio' => '',
            'completa' => ''
        ];
    }

    $timestamp = strtotime($fecha);
    if ($timestamp === false) {
        return [
            'dia' => '',
            'mes' => '',
            'anio' => '',
            'completa' => ''
        ];
    }

    $meses = [
        1 => 'enero',
        2 => 'febrero',
        3 => 'marzo',
        4 => 'abril',
        5 => 'mayo',
        6 => 'junio',
        7 => 'julio',
        8 => 'agosto',
        9 => 'septiembre',
        10 => 'octubre',
        11 => 'noviembre',
        12 => 'diciembre'
    ];

    $dia = date('d', $timestamp);
    $mes = $meses[(int) date('n', $timestamp)] ?? '';
    $anio = date('Y', $timestamp);

    return [
        'dia' => $dia,
        'mes' => $mes,
        'anio' => $anio,
        'completa' => $dia . ' de ' . $mes . ' de ' . $anio
    ];
}

function nombreArchivoSeguro($valor)
{
    $valor = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $valor);
    $valor = preg_replace('/[^A-Za-z0-9_-]+/', '_', (string) $valor);
    $valor = trim($valor, '_');
    return $valor !== '' ? $valor : 'documento';
}

function cargarTemplatePrimeraTutoria($templatePath, array $reemplazos)
{
    if (!file_exists($templatePath)) {
        http_response_code(500);
        die('No se encontro el template de la primera tutoria');
    }

    $template = file_get_contents($templatePath);
    if ($template === false) {
        http_response_code(500);
        die('No se pudo leer el template de la primera tutoria');
    }

    return strtr($template, $reemplazos);
}

$nombreAlumno = normalizarTextoPlano($datos['nombre'] . ' ' . $datos['apellidos']);
$nombreTutor = normalizarTextoPlano($datos['tutor'] ?: 'Tutoría diXma');
$nombreCurso = normalizarTextoPlano($datos['Denominacion']);
$numeroHoras = normalizarTextoPlano($datos['N_Horas']);

$fechaInicio = fechaPartesEspanol($datos['Fecha_Inicio']);
$fechaFin = fechaPartesEspanol($datos['Fecha_Fin']);

$templatePath = __DIR__ . '/template-parts/primera_tutoria_mail.txt';
$contenido = cargarTemplatePrimeraTutoria($templatePath, [
    '{{ALUMNO}}' => $nombreAlumno,
    '{{CURSO}}' => $nombreCurso,
    '{{HORAS}}' => $numeroHoras,
    '{{TUTOR}}' => $nombreTutor,
    '{{FECHA_INICIO_DIA}}' => $fechaInicio['dia'],
    '{{FECHA_INICIO_MES}}' => $fechaInicio['mes'],
    '{{FECHA_INICIO_ANIO}}' => $fechaInicio['anio'],
    '{{FECHA_INICIO_COMPLETA}}' => $fechaInicio['completa'],
    '{{FECHA_FIN_DIA}}' => $fechaFin['dia'],
    '{{FECHA_FIN_MES}}' => $fechaFin['mes'],
    '{{FECHA_FIN_ANIO}}' => $fechaFin['anio'],
    '{{FECHA_FIN_COMPLETA}}' => $fechaFin['completa']
]);

$contenido = preg_replace("/\r\n?|\n/", "\n", $contenido);
$paragraphs = preg_split("/\n{2,}/", trim($contenido));

$phpWord = new \PhpOffice\PhpWord\PhpWord();
$phpWord->setDefaultFontName('Calibri');
$phpWord->setDefaultFontSize(11);

$section = $phpWord->addSection([
    'marginTop' => 1134,
    'marginRight' => 1134,
    'marginBottom' => 1134,
    'marginLeft' => 1134
]);

$textStyle = ['name' => 'Calibri', 'size' => 11];
$paragraphStyle = [
    'spacing' => 120,
    'lineHeight' => 1.15
];

foreach ($paragraphs as $paragraph) {
    $lines = preg_split("/\n/", $paragraph);
    $textRun = $section->addTextRun($paragraphStyle);

    foreach ($lines as $index => $line) {
        if ($index > 0) {
            $textRun->addTextBreak();
        }
        $textRun->addText($line, $textStyle);
    }
}

$tempFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'primera_tutoria_' . $studentCursoID . '_' . time() . '.docx';
$writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$writer->save($tempFile);

$filename = 'primera_tutoria_' . nombreArchivoSeguro($nombreAlumno) . '_' . $studentCursoID . '.docx';

header('Content-Description: File Transfer');
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($tempFile));

readfile($tempFile);
@unlink($tempFile);
exit;
?>