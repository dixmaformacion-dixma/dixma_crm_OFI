<?php
// Generador de Guía Didáctica (.docx) usando PHPWord
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Autoload de Composer (PHPWord)
// Buscamos varias rutas posibles; el paquete PHPWord puede estar en "librerias/PHPWord/vendor/autoload.php"
$possibleAutoloads = [
    __DIR__ . '/vendor/autoload.php',
    __DIR__ . '/librerias/PHPWord/vendor/autoload.php',
    __DIR__ . '/../librerias/PHPWord/vendor/autoload.php',
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/vendor/autoload.php'
];

$autoload = null;
foreach ($possibleAutoloads as $p) {
    if (file_exists($p)) { $autoload = $p; break; }
}

if ($autoload) {
    require_once $autoload;
} else {
    die('No se encontró autoload de Composer. Asegúrate de que PHPWord está instalado y vendor/autoload.php existe en librerias/PHPWord/vendor o en vendor/.');
}

include_once __DIR__ . '/funciones/conexionBD.php';
include_once __DIR__ . '/funciones/funcionesContenidos.php';

session_start();
if (empty($_SESSION)) {
    header('Location: index.php');
    exit;
}

if (!isset($_GET['StudentCursoID']) || !is_numeric($_GET['StudentCursoID'])) {
    die('Falta el parámetro StudentCursoID');
}

$studentCursoID = (int)$_GET['StudentCursoID'];

// Obtener datos del alumno/curso
$conexionPDO = realizarConexion();
$sql = "SELECT alumnos.nombre, alumnos.apellidos, alumnos.nif, alumnocursos.Denominacion, alumnocursos.Modalidad, alumnocursos.N_Horas, alumnocursos.Fecha_Inicio, alumnocursos.Fecha_Fin, alumnocursos.N_Accion
        FROM alumnocursos
        JOIN alumnos ON alumnocursos.idAlumno = alumnos.idAlumno
        WHERE StudentCursoID = ?";
$stmt = $conexionPDO->prepare($sql);
$stmt->bindValue(1, $studentCursoID, PDO::PARAM_INT);
$stmt->execute();
$datos = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$datos) {
    die('No se encontraron datos para StudentCursoID ' . $studentCursoID);
}

// Cargar contenido del curso usando función existente (devuelve array con 'Contenido')
$anio = $datos['Fecha_Inicio'] ? date('Y', strtotime($datos['Fecha_Inicio'])) : date('Y');
$contenidoArr = @cargarContenidoAccion($datos['N_Accion'], $anio);
$contenido = '';
if (is_array($contenidoArr) && isset($contenidoArr['Contenido'])) {
    $contenido = $contenidoArr['Contenido'];
} elseif (is_string($contenidoArr)) {
    $contenido = $contenidoArr;
}

// Sanear contenido: convertir <br> a saltos de línea y eliminar etiquetas HTML simples
$contenido_plain = $contenido;
$contenido_plain = str_ireplace(['<br>', '<br/>', '<br />'], "\n", $contenido_plain);
$contenido_plain = strip_tags($contenido_plain);
$contenido_plain = html_entity_decode($contenido_plain, ENT_QUOTES | ENT_HTML5, 'UTF-8');

// Fechas formateadas
function formato_fecha_corto($fecha) {
    if (!$fecha) return '';
    $d = date_create($fecha);
    return $d ? date_format($d, 'd/m/Y') : $fecha;
}

$values = [
    'CURSO' => mb_strtoupper($datos['Denominacion']),
    'ALUMNO' => $datos['nombre'] . ' ' . $datos['apellidos'],
    'NIF' => $datos['nif'],
    'CONTENIDO' => $contenido_plain,
    'FECHA_INICIO' => formato_fecha_corto($datos['Fecha_Inicio']),
    'FECHA_FIN' => formato_fecha_corto($datos['Fecha_Fin']),
];

// Ruta a la plantilla (suposición razonable)
$templatePaths = [
    __DIR__ . '/template-parts/components/GUIA_DIDACTICA_BASE.docx',
    __DIR__ . '/template-parts/components/GUIA_DIDACTICA_BASE',
    __DIR__ . '/templates/GUIA_DIDACTICA_BASE.docx',
    __DIR__ . '/GUIA_DIDACTICA_BASE.docx',
    __DIR__ . '/plantillas/GUIA_DIDACTICA_BASE.docx'
];
$templateFile = null;
foreach ($templatePaths as $p) {
    if (file_exists($p)) { $templateFile = $p; break; }
}

// Si la extensión ZipArchive no está disponible, TemplateProcessor fallará al abrir el .docx (usa ZipArchive).
// En ese caso hacemos fallback a la generación programática y registramos una nota en el log de errores.
if ($templateFile && !class_exists('ZipArchive')) {
    error_log("PHP extension ZipArchive not found: falling back to programmatic .docx generation. Enable the php_zip/ZipArchive extension in php.ini (e.g. uncomment/enable extension=zip or extension=php_zip.dll) and restart Apache to use template .docx files.");
    // forzamos que no se use plantilla
    $templateFile = null;
}

$tempFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'guia_' . $studentCursoID . '_' . time() . '.docx';

// Si no se encontró template directamente, intentamos escanear la carpeta components por si tiene extensión distinta o mayúsculas
if (!$templateFile) {
    $scanDir = __DIR__ . '/template-parts/components';
    if (is_dir($scanDir)) {
        foreach (scandir($scanDir) as $f) {
            if ($f === '.' || $f === '..') continue;
            if (stripos($f, 'GUIA_DIDACTICA_BASE') !== false) {
                $templateFile = $scanDir . DIRECTORY_SEPARATOR . $f;
                error_log('Found GUIA_DIDACTICA_BASE via scan: ' . $templateFile);
                break;
            }
        }
    }
}

try {
    // Intentamos usar plantilla si existe
    if ($templateFile) {
        error_log('Using template file: ' . $templateFile);
        try {
            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templateFile);

            // Intentar obtener la lista de variables presentes en la plantilla (si la versión de PHPWord lo soporta)
            $templateVars = [];
            if (method_exists($templateProcessor, 'getVariables')) {
                try {
                    $templateVars = $templateProcessor->getVariables();
                } catch (Exception $e) {
                    $templateVars = [];
                }
            }

            // Si estamos en modo debug, mostramos las variables encontradas por TemplateProcessor
            if (!empty($_GET['debug'])) {
                header('Content-Type: text/plain; charset=utf-8');
                echo "Template variables found:\n";
                if (empty($templateVars)) {
                    echo "(none)\n";
                } else {
                    foreach ($templateVars as $tv) {
                        echo "- " . $tv . "\n";
                    }
                }
                echo "\nValues available for replacement:\n";
                foreach (array_keys($values) as $vk) {
                    echo "- " . $vk . "\n";
                }
                // Mostrar la ruta de la plantilla usada
                echo "\nTemplate file: " . $templateFile . "\n";
                exit;
            }

            if (empty($templateVars)) {
                $templateVars = array_keys($values);
            }

            // Normalizar claves de valores
            $normalizedValues = [];
            foreach ($values as $k => $v) {
                $norm = preg_replace('/[^A-Z0-9]/', '', strtoupper($k));
                $normalizedValues[$norm] = $v;
            }

            foreach ($templateVars as $var) {
                $normVar = preg_replace('/[^A-Z0-9]/', '', strtoupper($var));
                $replace = '';
                if (isset($values[$var])) {
                    $replace = $values[$var];
                } elseif (isset($normalizedValues[$normVar])) {
                    $replace = $normalizedValues[$normVar];
                } else {
                    foreach ($normalizedValues as $nvk => $nvv) {
                        if ($nvk === $normVar || strpos($nvk, $normVar) !== false || strpos($normVar, $nvk) !== false) {
                            $replace = $nvv;
                            break;
                        }
                    }
                }

                $replace = str_replace(["\r\n", "\r"], "\n", (string)$replace);

                // Si la variable corresponde al contenido y tenemos HTML original, intentamos inyectar como bloque complejo
                $isContenido = ($normVar === preg_replace('/[^A-Z0-9]/', '', strtoupper('CONTENIDO')));
                $injected = false;
                if ($isContenido && !empty($contenido) && class_exists('ZipArchive') && method_exists($templateProcessor, 'setComplexBlock')) {
                    try {
                        // Crear un TextRun y poblarlo con el HTML usando Html::addHtml
                        try {
                            $textRun = new \PhpOffice\PhpWord\Element\TextRun();
                            $prev = libxml_use_internal_errors(true);
                            \PhpOffice\PhpWord\Shared\Html::addHtml($textRun, $contenido, false, false);
                            libxml_clear_errors();
                            libxml_use_internal_errors($prev);
                            $templateProcessor->setComplexBlock($var, $textRun);
                            $injected = true;
                        } catch (Exception $e) {
                            error_log('HTML->TextRun injection failed: ' . $e->getMessage());
                            $injected = false;
                        }
                    } catch (Exception $e) {
                        // Si algo falla, seguiremos con la sustitución simple
                        error_log('HTML->PhpWord injection failed for CONTENIDO: ' . $e->getMessage());
                        $injected = false;
                    }
                }

                if (!$injected) {
                    try {
                        $templateProcessor->setValue($var, $replace);
                    } catch (Exception $e) {
                        try {
                            $templateProcessor->setValue(strtoupper($var), $replace);
                        } catch (Exception $e2) {
                            // ignore
                        }
                    }
                }
            }

            $templateProcessor->saveAs($tempFile);
            $generatedFile = $tempFile;
            error_log('Template processed and saved to: ' . $tempFile);
        } catch (Exception $e) {
            error_log('TemplateProcessor failed: ' . $e->getMessage());
            // Si venimos en modo debug, mostramos el error para ayudar al diagnóstico
            if (!empty($_GET['debug'])) {
                http_response_code(500);
                echo '<h2>TemplateProcessor error</h2>';
                echo '<p>Mensaje: ' . htmlspecialchars($e->getMessage()) . '</p>';
                echo '<p>Archivo plantilla: ' . htmlspecialchars($templateFile) . '</p>';
                exit;
            }
            // Forzamos fallback silencioso en producción
            $templateFile = null;
        }
    }

    // Si no usamos plantilla, generamos programáticamente
    if (!$templateFile) {
        error_log('Generating guide programmatically (fallback)');
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection(['marginTop' => 600, 'marginLeft' => 600, 'marginRight' => 600]);
        $section->addTitle('GUÍA DIDÁCTICA', 1);
        $section->addTextBreak(1);
        $section->addText('Curso: ' . $values['CURSO'], ['bold' => true]);
        $section->addText('Alumno: ' . $values['ALUMNO']);
        $section->addText('NIF: ' . $values['NIF']);
        $section->addText('Fechas: ' . $values['FECHA_INICIO'] . ' - ' . $values['FECHA_FIN']);
        $section->addTextBreak(1);
        $section->addText('Contenidos:', ['bold' => true]);
        $section->addText($values['CONTENIDO']);
        if (class_exists('ZipArchive')) {
            $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $writer->save($tempFile);
            $generatedFile = $tempFile;
        } else {
            $plainFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'guia_' . $studentCursoID . '_' . time() . '.doc';
            $html = "<html><head><meta charset=\"utf-8\"></head><body>";
            $html .= "<h1>GUÍA DIDÁCTICA</h1>";
            $html .= "<p><strong>Curso:</strong> " . htmlentities($values['CURSO']) . "</p>";
            $html .= "<p><strong>Alumno:</strong> " . htmlentities($values['ALUMNO']) . "</p>";
            $html .= "<p><strong>NIF:</strong> " . htmlentities($values['NIF']) . "</p>";
            $html .= "<p><strong>Fechas:</strong> " . htmlentities($values['FECHA_INICIO'] . ' - ' . $values['FECHA_FIN']) . "</p>";
            $html .= "<h2>Contenidos</h2>";
            $cont = nl2br(htmlentities($values['CONTENIDO']));
            $html .= "<div>" . $cont . "</div>";
            $html .= "</body></html>";
            file_put_contents($plainFile, $html);
            $generatedFile = $plainFile;
            error_log('ZipArchive not available: generated .doc HTML fallback for GUIA DIDACTICA.');
        }
    }
} catch (Exception $e) {
    // Manejo de errores
    http_response_code(500);
    echo 'Error generando el documento: ' . $e->getMessage();
    exit;
}

// Forzar descarga del archivo generado
if (empty($generatedFile) || !file_exists($generatedFile)) {
    http_response_code(500);
    echo 'No se pudo generar el archivo.';
    exit;
}
// Ajustar cabeceras según el tipo de archivo generado
$ext = pathinfo($generatedFile, PATHINFO_EXTENSION);
$downloadName = 'GUIA_DIDACTICA_' . $datos['nombre'] . ' ' . $datos['apellidos'] . '.' . $ext;
if (strtolower($ext) === 'docx') {
    $mime = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
} elseif (strtolower($ext) === 'doc') {
    $mime = 'application/msword';
} else {
    $mime = 'application/octet-stream';
}
header('Content-Description: File Transfer');
header('Content-Type: ' . $mime);
header('Content-Disposition: attachment; filename="' . basename($downloadName) . '"');
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($generatedFile));
readfile($generatedFile);
// borrar temporal
@unlink($generatedFile);
exit;
