<?php
// ══ DEBUG TEMPORANEO - rimuovere dopo il test ══
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (isset($_GET['debug'])) {
    echo "<pre style='background:#222;color:#0f0;padding:15px;font-size:13px;'>";
    echo "PHP Version: " . PHP_VERSION . "\n";
    echo "vendor/autoload.php          : " . (file_exists(__DIR__ . '/vendor/autoload.php')                             ? "✅ OK" : "❌ MANCANTE") . "\n";
    echo "funciones/conexionBD.php     : " . (file_exists(__DIR__ . '/funciones/conexionBD.php')                        ? "✅ OK" : "❌ MANCANTE") . "\n";
    echo "funciones/funcionesAlumnos   : " . (file_exists(__DIR__ . '/funciones/funcionesAlumnos.php')                  ? "✅ OK" : "❌ MANCANTE") . "\n";
    echo "menu_top.php                 : " . (file_exists(__DIR__ . '/template-parts/header/menu_top.php')              ? "✅ OK" : "❌ MANCANTE") . "\n";
    echo "tutoria.template.php         : " . (file_exists(__DIR__ . '/template-parts/leftmenu/tutoria.template.php')   ? "✅ OK" : "❌ MANCANTE") . "\n";
    echo "</pre>";
    die();
}
// ══ FINE DEBUG ══

$vendorAutoload = __DIR__ . '/vendor/autoload.php';
$vendor_available = false;
if (file_exists($vendorAutoload)) {
    require_once $vendorAutoload;
    $vendor_available = true;
}
include "funciones/conexionBD.php";
include "funciones/funcionesAlumnos.php";

session_start();

if (empty($_SESSION)) {
    header("Location: index.php");
    exit();
}

date_default_timezone_set("Europe/Madrid");

use PhpOffice\PhpSpreadsheet\IOFactory;

$idEmpresa  = $_GET['idEmpresa'] ?? $_POST['idEmpresa'] ?? '';
$stage      = 'upload';   // upload | preview | result
$rows       = [];         // parsed rows from file
$raw_rows   = [];         // raw rows as numeric arrays
$orig_header = [];        // original header names
$colMap     = [];
$insertedCount  = 0;
$skippedCount   = 0;
$errors         = [];
$warnings       = [];
$invalidNifRows = [];

// ─── FUNZIONE: rileva indice colonna dall'header ───────────────────────────
function mapHeader(array $header): array {
    $map = [];
    $aliases = [
        'apellidos'       => ['apellidos','cognome','surname','lastname','apellido'],
        'nombre'          => ['nombre','nome','name','firstname','nombre(s)'],
        'nif'             => ['nif','dni','document','documento','dni/nif','nº documento'],
        'telefono'        => ['telefono','telefon','phone','tel','telefono movil','movil'],
        'email'           => ['email','e-mail','correo','mail'],
        'fechaNacimiento' => ['fecha_nacimiento','fechanacimiento','birth','nacimiento','fecha nacimiento','data nascita','f.nacimiento','fecha de nacimiento'],
        'numeroSeguridadSocial' => ['numero_seguridad_social','nss','seguridadsocial','numero seguridad social','num seguridad social','numseguridad'],
        'categoriaProfesional' => ['categoria_profesional','categoria profesional','categoria','categoriaProfesional'],
        'colectivo'       => ['colectivo','tipo','tipo contrato','colectivo laboral'],
        'grupoCotizacion' => ['grupo_cotizacion','grupo cotizacion','grupo','grupo de cotizacion','grupo cotización'],
        'nivelEstudios'   => ['nivel_estudios','nivel estudios','nivel','estudios','nivelestudios'],
        'costeHora'       => ['coste_hora','coste hora','costehora','precio hora','costo hora'],
        'horarioLaboral'  => ['horario_laboral','horario laboral','horario','jornada'],
        'idEmpresa'       => ['id_empresa','empresa','idempresa','id empresa'],
        'sexo'            => ['sexo','genero','gender'],
        'discapacidad'    => ['discapacidad','discap','discapacitado'],
    ];

    // helper to normalize: lower + remove non-alnum
    $normalize = function($s) {
        $s = mb_strtolower(trim((string)$s));
        $s = preg_replace('/[^a-z0-9]/u', '', $s);
        return $s;
    };

    // prepare normalized alias map
    $normAliases = [];
    foreach ($aliases as $field => $words) {
        $normAliases[$field] = array_map($normalize, $words);
        // also include canonical field name normalized
        $normAliases[$field][] = $normalize($field);
    }

    foreach ($header as $idx => $cell) {
        $n = $normalize($cell);
        foreach ($normAliases as $field => $norms) {
            foreach ($norms as $a) {
                if ($a === '') continue;
                // exact match or contains
                if ($n === $a || strpos($n, $a) !== false || strpos($a, $n) !== false) {
                    if (!isset($map[$field])) {
                        $map[$field] = $idx;
                    }
                }
            }
        }
    }
    return $map;
}

// ─── STEP 2: processa file caricato ────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel_file'])) {
    // If the page was posted back with raw rows (apply mapping), rebuild state
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['raw_rows_json'])) {
        $raw_rows = json_decode($_POST['raw_rows_json'], true) ?: [];
        if (!empty($_POST['orig_header_json'])) {
            $orig_header = json_decode($_POST['orig_header_json'], true) ?: [];
        }
        // Recalculate detected colMap from original header if present
        if (!empty($orig_header)) {
            $colMap = mapHeader($orig_header);
        }
    }

    $file     = $_FILES['excel_file'];
    $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed  = ['xlsx','xls','csv','ods'];

    if (!in_array($ext, $allowed)) {
        $errors[] = "Formato no válido. Usa .xlsx, .xls o .csv.";
    } elseif ($file['size'] > 5 * 1024 * 1024) {
        $errors[] = "Archivo demasiado grande (máx. 5 MB).";
    } else {
        try {
            if ($ext === 'csv') {
                // Parsing CSV
                $handle = fopen($file['tmp_name'], 'r');
                $header = fgetcsv($handle);
                $orig_header = $header;
                $colMap = mapHeader($header);
                while (($line = fgetcsv($handle)) !== false) {
                    $raw_rows[] = $line;
                    $row = [];
                    foreach ($colMap as $field => $idx) {
                        $row[$field] = isset($line[$idx]) ? trim($line[$idx]) : '';
                    }
                    if (!empty($row['nif'])) $rows[] = $row;
                }
                fclose($handle);
                } else {
                    // Parsing XLSX / XLS / ODS require PhpSpreadsheet (vendor)
                    if (!$vendor_available) {
                        $errors[] = "La importación de archivos .xlsx/.xls/.ods no está disponible: falta la librería (vendor). Sube vendor/ o usa CSV.";
                    } else {
                        // Parsing XLSX / XLS / ODS
                        $spreadsheet = IOFactory::load($file['tmp_name']);
                        $sheet       = $spreadsheet->getActiveSheet();
                        $data        = $sheet->toArray(null, true, true, false);
                        if (empty($data)) throw new \Exception("El archivo está vacío.");
                        $header = array_map(function($c) { return (string)$c; }, $data[0]);
                        $orig_header = $header;
                        $colMap = mapHeader($header);
                        foreach (array_slice($data, 1) as $line) {
                            $raw_rows[] = $line;
                            $row = [];
                            foreach ($colMap as $field => $idx) {
                                $row[$field] = isset($line[$idx]) ? trim((string)$line[$idx]) : '';
                            }
                            // Saltar filas completamente vacías
                            if (array_filter($row)) $rows[] = $row;
                        }
                    }
            }

            if (empty($rows)) {
                $errors[] = "No se encontraron filas en el archivo (comprueba las cabeceras).";
            } elseif (!isset($colMap['nif'])) {
                $errors[] = "Columna NIF no encontrada. Asegúrate de que la cabecera contenga 'NIF' o 'DNI'.";
            } else {
                // Controlla duplicati NIF nel DB
                $allNifs = array_filter(array_column($rows, 'nif'));
                // we'll compute duplicates later against previewRows
                $stage = 'preview';
            }

        } catch (\Throwable $e) {
            $errors[] = "Error al leer el archivo: " . $e->getMessage();
        }
    }
}

// ─── STEP 3: conferma import ────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_import'])) {
    // Support either pre-mapped rows_json or raw_rows_json + mapping
    $pdo = realizarConexion();
    $pdo->beginTransaction();
    try {
        $toImport = [];
        if (!empty($_POST['rows_json'])) {
            $toImport = json_decode($_POST['rows_json'], true);
        } elseif (!empty($_POST['raw_rows_json']) && !empty($_POST['mapping'])) {
            $raw = json_decode($_POST['raw_rows_json'], true) ?: [];
            $mapping = $_POST['mapping'];
            foreach ($raw as $line) {
                $rec = [];
                foreach ($mapping as $idx => $field) {
                    if ($field === 'ignore') continue;
                    $val = isset($line[(int)$idx]) ? trim((string)$line[(int)$idx]) : '';
                    if ($field === 'nif') $val = strtoupper(str_replace(' ', '', $val));
                    $rec[$field] = $val;
                }
                if (array_filter($rec)) $toImport[] = $rec;
            }
        }

        foreach ($toImport as $row) {
            $nif = trim($row['nif'] ?? '');
            if (empty($nif)) { $skippedCount++; continue; }

            $check = $pdo->prepare("SELECT COUNT(*) FROM alumnos WHERE nif = ?");
            $check->execute([$nif]);
            if ($check->fetchColumn() > 0) { $skippedCount++; continue; }

            $fechaNac = '';
            if (!empty($row['fechaNacimiento'])) {
                $ts = strtotime($row['fechaNacimiento']);
                $fechaNac = $ts ? date('Y-m-d', $ts) : '';
            }

            $stmt = $pdo->prepare("INSERT INTO alumnos (nombre, apellidos, telefono, email, fechaNacimiento, nif, numeroSeguridadSocial, categoriaProfesional, colectivo, grupoCotizacion, nivelEstudios, costeHora, horarioLaboral, idEmpresa, sexo, discapacidad)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $row['nombre']                    ?? '',
                $row['apellidos']                 ?? '',
                $row['telefono']                  ?? '',
                $row['email']                     ?? '',
                $fechaNac,
                $nif,
                $row['numeroSeguridadSocial']     ?? '',
                $row['categoriaProfesional']      ?? '',
                $row['colectivo']                 ?? '',
                $row['grupoCotizacion']           ?? '',
                $row['nivelEstudios']             ?? '',
                $row['costeHora']                 ?? '',
                $row['horarioLaboral']            ?? '',
                $_POST['idEmpresa'] ?: null,
                $row['sexo']                      ?? '',
                $row['discapacidad']              ?? 'No',
            ]);
            $insertedCount++;
        }
        $pdo->commit();
        $stage = 'result';
    } catch (\Throwable $e) {
        $pdo->rollBack();
        $errors[] = "Error durante la importación: " . $e->getMessage();
    }
}
// Ensure mapping/preview variables available when rendering
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['raw_rows_json']) && empty($raw_rows)) {
    $raw_rows = json_decode($_POST['raw_rows_json'], true) ?: [];
    if (!empty($_POST['orig_header_json'])) {
        $orig_header = json_decode($_POST['orig_header_json'], true) ?: [];
    }
    if (!empty($orig_header)) {
        $colMap = mapHeader($orig_header);
    }
}

// Build column list for mapping
$columns = [];
if (!empty($orig_header)) {
    foreach ($orig_header as $i => $h) $columns[$i] = $h;
} elseif (!empty($raw_rows)) {
    $first = $raw_rows[0];
    foreach ($first as $i => $v) $columns[$i] = 'Col ' . ($i+1);
}

// Limit to 40 columns
$maxCols = 40;
$totalDetected = count($columns);
if ($totalDetected > $maxCols) {
    $warnings[] = "El archivo contiene $totalDetected columnas. Solo se muestran las primeras $maxCols columnas para el mapeo.";
    $columns = array_slice($columns, 0, $maxCols, true);
}

// Determine active mapping
$activeMapping = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['mapping']) && is_array($_POST['mapping'])) {
    foreach ($_POST['mapping'] as $k => $v) $activeMapping[(int)$k] = $v;
} elseif (!empty($colMap)) {
    foreach ($colMap as $f => $idx) $activeMapping[(int)$idx] = $f;
}

// Remove any column that was mapped to idEmpresa from the visible columns
foreach (array_keys($columns) as $ci) {
    if (($activeMapping[$ci] ?? '') === 'idEmpresa') {
        unset($columns[$ci]);
    }
}

// Build previewRows applying active mapping
$previewRows = [];
foreach ($raw_rows as $line) {
    $mapped = [];
    foreach ($columns as $i => $colName) {
        $field = $activeMapping[$i] ?? null;
        if ($field && $field !== 'ignore') {
            $mapped[$field] = isset($line[$i]) ? trim((string)$line[$i]) : '';
        }
        // unmapped columns are skipped
    }
    if (array_filter($mapped)) $previewRows[] = $mapped;
}

// Controlla NIF nulli o con lunghezza diversa da 9
foreach ($previewRows as $i => $row) {
    $nif = trim($row['nif'] ?? '');
    if ($nif === '' || strlen($nif) !== 9) {
        $invalidNifRows[$i] = $nif;
    }
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Alumnos desde Excel</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <link rel="icon" href="images/favicon.ico">
    <style>
        .row-new      { background-color: #d4edda; }
        .row-duplicate{ background-color: #f8d7da; }
        .row-invalid  { background-color: #fff3cd; }
        .badge-new    { background-color: #28a745; }
        .badge-dup    { background-color: #dc3545; }
        .badge-inv    { background-color: #fd7e14; }
        .table-preview th { background-color: #b0d588; }
    </style>
</head>
<body style="background-color: #f3f6f4;">

<?php
    $menuaction = 'tutoria';
    require_once './template-parts/header/menu_top.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php require_once("template-parts/leftmenu/tutoria.template.php"); ?>

        <div class="col-10 mt-3">
            <h2 class="text-center mt-2 pt-2 pb-3 mb-md-4 mb-3 border border-5 rounded"
                style="background-color: #b0d588; letter-spacing: 5px;">
                IMPORTAR ALUMNOS DESDE EXCEL
            </h2>

            <?php foreach ($errors as $err): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($err) ?></div>
            <?php endforeach; ?>

            <!-- ══════════════ STAGE: UPLOAD ══════════════ -->
            <?php if ($stage === 'upload'): ?>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Cargar archivo Excel / CSV</h5>
                    <p class="text-muted small">
                        El archivo debe tener una fila de cabecera con al menos las columnas:
                        <strong>Apellidos</strong>, <strong>Nombre</strong>, <strong>NIF</strong>.<br>
                        Columnas opcionales: Telefono, Email, Fecha_Nacimiento.<br>
                        Formatos aceptados: <code>.xlsx</code>, <code>.xls</code>, <code>.ods</code>, <code>.csv</code> (máx. 5 MB).
                    </p>
                    <a href="download_template_alumnos.php" class="btn btn-outline-secondary btn-sm mb-3">
                        ⬇ Descargar plantilla .xlsx
                    </a>

                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="idEmpresa" value="<?= htmlspecialchars($idEmpresa) ?>">

                        <?php if (empty($idEmpresa)): ?>
                        <div class="mb-3 col-md-4">
                            <label class="fw-bold">ID Empresa (opcional):</label>
                            <input type="number" name="idEmpresa" class="form-control"
                                   placeholder="Déjalo vacío si se desconoce">
                        </div>
                        <?php endif; ?>

                        <div class="mb-3 col-md-6">
                            <label class="fw-bold">Archivo Excel / CSV:</label>
                            <input type="file" name="excel_file" class="form-control"
                                   accept=".xlsx,.xls,.csv,.ods" required>
                        </div>

                        <button type="submit" class="btn btn-primary px-5">
                            Analizar archivo
                        </button>
                    </form>
                </div>
            </div>

            <!-- ══════════════ STAGE: PREVIEW ══════════════ -->
            <?php elseif ($stage === 'preview'): ?>

            <?php
                // Recalculate duplicates based on previewRows' NIFs (do before rendering badges)
                $allNifs = array_filter(array_column($previewRows, 'nif'));
                $dupNifs = [];
                if (!empty($allNifs)) {
                    $placeholders = implode(',', array_fill(0, count($allNifs), '?'));
                    $pdo = realizarConexion();
                    $stmt = $pdo->prepare("SELECT nif FROM alumnos WHERE nif IN ($placeholders)");
                    $stmt->execute(array_values($allNifs));
                    $dupNifs = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'nif');
                }

                $newCount = 0; $dupCount = 0;
                foreach ($previewRows as $r) {
                    $n = trim($r['nif'] ?? '');
                    if ($n !== '' && in_array($n, $dupNifs)) $dupCount++; else $newCount++;
                }
            ?>

            <div class="d-flex gap-3 mb-3 align-items-center flex-wrap">
                <span class="badge fs-6 badge-new text-white">✔ Nuevos: <?= $newCount ?></span>
                <span class="badge fs-6 badge-dup text-white">✖ Duplicados (NIF ya en BD): <?= $dupCount ?></span>
                <?php if (count($invalidNifRows) > 0): ?>
                <span class="badge fs-6 badge-inv text-white">⚠ NIF inválido: <?= count($invalidNifRows) ?></span>
                <?php endif; ?>
                <span class="badge fs-6 bg-secondary text-white">Total filas: <?= count($previewRows) ?></span>
            </div>

            <?php if ($dupCount > 0): ?>
            <div class="alert alert-warning">
                <strong>Atención:</strong> <?= $dupCount ?> alumno/s tienen un NIF ya presente en la base de datos
                (marcados en rojo). Serán excluidos de la importación.
            </div>
            <?php endif; ?>

            <?php if (count($invalidNifRows) > 0): ?>
            <div class="alert alert-danger">
                <strong>Error:</strong> <?= count($invalidNifRows) ?> fila/s tienen un NIF inválido
                (vacío o con un número de caracteres distinto de 9, marcadas en amarillo).
                La importación está bloqueada. Corrige el archivo y vuelve a cargarlo.
                <ul class="mb-0 mt-2">
                <?php foreach ($invalidNifRows as $ri => $nif): ?>
                    <li>Fila <?= $ri + 1 ?>:
                        <?php if ($nif === ''): ?>NIF vacío
                        <?php else: ?>"<?= htmlspecialchars($nif) ?>" — <?= strlen($nif) ?> caracteres (se esperan 9)
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            

            <form method="POST">
                <input type="hidden" name="idEmpresa" value="<?= htmlspecialchars($idEmpresa) ?>">
                <input type="hidden" name="raw_rows_json" value="<?= htmlspecialchars(json_encode($raw_rows)) ?>">
                <input type="hidden" name="orig_header_json" value="<?= htmlspecialchars(json_encode($orig_header)) ?>">
                <?php foreach ($columns as $i => $colName):
                    $mval = $activeMapping[$i] ?? 'ignore';
                ?>
                    <input type="hidden" name="mapping[<?= $i ?>]" value="<?= htmlspecialchars($mval) ?>">
                <?php endforeach; ?>
                

                <div class="table-responsive mb-3">
                    <table class="table table-bordered table-sm table-preview align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Estado</th>
                                <?php foreach ($columns as $c): ?>
                                    <th><?= htmlspecialchars($c) ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($previewRows as $i => $row):
                            $isDup     = in_array($row['nif'] ?? '', $dupNifs);
                            $isInvalid = isset($invalidNifRows[$i]);
                            $rowClass  = $isInvalid ? 'row-invalid' : ($isDup ? 'row-duplicate' : 'row-new');
                            if ($isInvalid) {
                                $badge = '<span class="badge bg-warning text-dark">NIF inválido</span>';
                            } elseif ($isDup) {
                                $badge = '<span class="badge bg-danger">Duplicado</span>';
                            } else {
                                $badge = '<span class="badge bg-success">Nuevo</span>';
                            }
                        ?>
                            <tr class="<?= $rowClass ?>">
                                <td><?= $i + 1 ?></td>
                                <td><?= $badge ?></td>
                                <?php foreach ($columns as $idx => $colName):
                                        $field = $activeMapping[$idx] ?? null;
                                        if ($field && $field !== 'ignore') {
                                            $cell = $row[$field] ?? '';
                                        } else {
                                            $cell = '';
                                        }
                                ?>
                                    <td><?= htmlspecialchars($cell) ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex gap-3">
                    <?php if ($newCount > 0 && count($invalidNifRows) === 0): ?>
                    <button type="submit" name="confirm_import" class="btn btn-success px-5">
                        Importar <?= $newCount ?> alumno/s nuevo/s
                    </button>
                    <?php else: ?>
                    <div class="alert alert-info mb-0">Todos los registros ya están presentes en la BD. No hay nada que insertar.</div>
                    <?php endif; ?>

                    <a href="tutoria_importarAlumnos.php?idEmpresa=<?= urlencode($idEmpresa) ?>"
                       class="btn btn-outline-secondary">
                        ← Cargar otro archivo
                    </a>
                </div>
            </form>

            <!-- ══════════════ STAGE: RESULT ══════════════ -->
            <?php elseif ($stage === 'result'): ?>

            <div class="alert alert-success fs-5">
                ✔ Importación completada:<br>
                <strong><?= $insertedCount ?></strong> alumno/s insertados,
                <strong><?= $skippedCount ?></strong> omitidos (duplicados o NIF vacío).
            </div>

            <div class="d-flex gap-3 mt-3">
                <a href="tutoria_importarAlumnos.php?idEmpresa=<?= urlencode($idEmpresa) ?>"
                   class="btn btn-primary">
                    Importar otro archivo
                </a>
                <a href="tutoria_listadoAlumno.php" class="btn btn-outline-secondary">
                    Ver listado de alumnos
                </a>
            </div>

            <?php endif; ?>

        </div>
    </div>
</div>

<footer class="border-top border-secondary mt-4" style="background-color:#e4e4e4; height: 75px;">
    <p class="text-center mt-md-4" style="color: #8fd247;">
        <b>© Dixma Formación 2022. | Ctra. Madrid 152, Vigo 36318 | info@dixmaformacion.com | Tlf: +34 604 067 035</b>
    </p>
</footer>

</body>
</html>
