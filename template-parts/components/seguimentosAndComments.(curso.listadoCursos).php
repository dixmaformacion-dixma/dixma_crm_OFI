<?php
if (!function_exists('primeraTutoriaFechaPartes')) {
        function primeraTutoriaFechaPartes($fecha)
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
}

if (!function_exists('renderPrimeraTutoriaText')) {
        function renderPrimeraTutoriaText($curso): string
        {
                static $template = null;

                if ($template === null) {
                        $templatePath = __DIR__ . '/../primera_tutoria_mail.txt';
                        $template = file_exists($templatePath) ? file_get_contents($templatePath) : '';
                }

                if ($template === false || $template === '') {
                        return 'No se ha encontrado el texto base de la primera tutoría.';
                }

                $fechaInicio = primeraTutoriaFechaPartes($curso['Fecha_Inicio'] ?? '');
                $fechaFin = primeraTutoriaFechaPartes($curso['Fecha_Fin'] ?? '');
                $nombreAlumno = trim((string) ($curso['nombre'] ?? ''));
                $nombreTutor = trim((string) ($curso['tutor'] ?? ''));

                if ($nombreTutor === '') {
                        $nombreTutor = 'Tutoría diXma';
                }

                return strtr($template, [
                        '{{ALUMNO}}' => $nombreAlumno,
                        '{{CURSO}}' => trim((string) ($curso['Denominacion'] ?? '')),
                        '{{HORAS}}' => trim((string) ($curso['N_Horas'] ?? '')),
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
        }
}

if (!function_exists('renderFinalTutoriaText')) {
        function renderFinalTutoriaText($curso): string
        {
                static $template = null;

                if ($template === null) {
                        $templatePath = __DIR__ . '/../mail_final.txt';
                        $template = file_exists($templatePath) ? file_get_contents($templatePath) : '';
                }

                if ($template === false || $template === '') {
                        return 'No se ha encontrado el texto base del mensaje final.';
                }

                $nombreAlumno = trim((string) ($curso['nombre'] ?? ''));

                return strtr($template, [
                        '{{ALUMNO}}' => $nombreAlumno
                ]);
        }
}

if (!function_exists('renderSeguimiento1Text')) {
        function renderSeguimiento1Text($curso): string
        {
                static $template = null;

                if ($template === null) {
                        $templatePath = __DIR__ . '/../seguimiento1.txt';
                        $template = file_exists($templatePath) ? file_get_contents($templatePath) : '';
                }

                if ($template === false || $template === '') {
                        return 'No se ha encontrado el texto base del seguimiento 1.';
                }

                $nombreAlumno = trim((string) ($curso['nombre'] ?? ''));

                return strtr($template, [
                        '{{ALUMNO}}' => $nombreAlumno
                ]);
        }
}

if (!function_exists('renderSeguimiento3Text')) {
        function renderSeguimiento3Text($curso): string
        {
                static $template = null;

                if ($template === null) {
                        $templatePath = __DIR__ . '/../seguimiento3.txt';
                        $template = file_exists($templatePath) ? file_get_contents($templatePath) : '';
                }

                if ($template === false || $template === '') {
                        return 'No se ha encontrado el texto base del seguimiento 3.';
                }

                $nombreAlumno = trim((string) ($curso['nombre'] ?? ''));

                return strtr($template, [
                        '{{ALUMNO}}' => $nombreAlumno
                ]);
        }
}

if (!function_exists('renderSeguimiento4Text')) {
        function renderSeguimiento4Text($curso): string
        {
                static $template = null;

                if ($template === null) {
                        $templatePath = __DIR__ . '/../seguimiento4.txt';
                        $template = file_exists($templatePath) ? file_get_contents($templatePath) : '';
                }

                if ($template === false || $template === '') {
                        return 'No se ha encontrado el texto base del seguimiento 4.';
                }

                $nombreAlumno = trim((string) ($curso['nombre'] ?? ''));

                return strtr($template, [
                        '{{ALUMNO}}' => $nombreAlumno
                ]);
        }
}

$primeraTutoriaModalId = 'primeraTutoriaTextModal' . $curso['StudentCursoID'];
$primeraTutoriaTextareaId = 'primeraTutoriaTextArea' . $curso['StudentCursoID'];
$primeraTutoriaTexto = renderPrimeraTutoriaText($curso);
$seguimiento1ModalId = 'seguimiento1TextModal' . $curso['StudentCursoID'];
$seguimiento1TextareaId = 'seguimiento1TextArea' . $curso['StudentCursoID'];
$seguimiento1Texto = renderSeguimiento1Text($curso);
$seguimiento3ModalId = 'seguimiento3TextModal' . $curso['StudentCursoID'];
$seguimiento3TextareaId = 'seguimiento3TextArea' . $curso['StudentCursoID'];
$seguimiento3Texto = renderSeguimiento3Text($curso);
$seguimiento4ModalId = 'seguimiento4TextModal' . $curso['StudentCursoID'];
$seguimiento4TextareaId = 'seguimiento4TextArea' . $curso['StudentCursoID'];
$seguimiento4Texto = renderSeguimiento4Text($curso);
$finalTutoriaModalId = 'finalTutoriaTextModal' . $curso['StudentCursoID'];
$finalTutoriaTextareaId = 'finalTutoriaTextArea' . $curso['StudentCursoID'];
$finalTutoriaTexto = renderFinalTutoriaText($curso);
?>
<style>
         .seguimiento-row {
                margin-top: 6px;
                margin-bottom: 6px;
        }

        .seguimiento-main {
                display: inline-block;
        }

        .seguimiento-main b {
                margin-right: 8px;
        }

        .seguimiento-main .form-check-input {
                margin-left: 8px;
                vertical-align: middle;
        }

        .seguimiento-action {
                width: 26px;
                height: 26px;
                padding: 0;
                background-color: #1e989e;
                border: none;
                color: #fff;
                display: inline-flex;
                align-items: center;
                justify-content: center;
        }

        .seguimiento-action img {
                width: 10px;
                height: 10px;
                filter: brightness(0) invert(1);
        }
</style>
<div class="container col-10 mt-3">
        <div class="row">
                <div class="col-12 text-center mt-2 pt-2 pb-2 border border-5 rounded" style="background-color: #b0d588;">
                        <b>SEGUIMIENTOS: </b>
                </div>
        </div>
        <div>
                <div class="row align-items-center seguimiento-row">
                        <div class="col-auto">
                                <span class="seguimiento-main">
                                        <b>1º TUTORÍA:</b>
                                        <?php echo checkAndHighlightDate($curso['seguimento0'], $curso['seguimento0check']); ?>
                                        <input type="checkbox" class="form-check-input" disabled <?php if ($curso['seguimento0check'] == 1) {
                                                                                                                echo "checked";
                                                                                                        } ?>>
                                </span>
                        </div>
                        <div class="col-auto ps-1">
                                <button type="button"
                                        class="btn btn-sm seguimiento-action"
                                        data-bs-toggle="modal"
                                        data-bs-target="#<?php echo $primeraTutoriaModalId; ?>">
                                        <img src="images/iconos/envelope-open-fill.svg" alt="Correo" title="Ver texto del correo" aria-label="Correo">
                                </button>
                        </div>
                </div>
                <div class="modal fade" id="<?php echo $primeraTutoriaModalId; ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                <div class="modal-content">
                                        <div class="modal-header" style="background-color: #b0d588;">
                                                <h5 class="modal-title"><b>TEXTO PRIMERA TUTORÍA</b></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                                <p class="mb-2">Texto personalizado listo para copiar y pegar.</p>
                                                <textarea id="<?php echo $primeraTutoriaTextareaId; ?>" class="form-control" rows="16" readonly><?php echo htmlspecialchars($primeraTutoriaTexto); ?></textarea>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                                <button type="button"
                                                        class="btn btn-outline-secondary"
                                                        onclick="(function(){var textarea=document.getElementById('<?php echo $primeraTutoriaTextareaId; ?>'); if(!textarea){return;} textarea.focus(); textarea.select(); try { document.execCommand('copy'); } catch (e) {} })();">
                                                        Copiar texto
                                                </button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        </div>
                                </div>
                        </div>
                </div>
                <div class="row align-items-center seguimiento-row">
                        <div class="col-auto">
                                <span class="seguimiento-main">
                                        <b>SEGUIMIENTO 1:</b>
                                        <?php echo checkAndHighlightDate($curso['seguimento1'], $curso['seguimento1check']); ?>
                                        <input type="checkbox" class="form-check-input" disabled <?php if ($curso['seguimento1check'] == 1) {
                                                                                                                echo "checked";
                                                                                                        } ?>>
                                </span>
                        </div>
                        <div class="col-auto ps-1">
                                <button type="button"
                                        class="btn btn-sm seguimiento-action"
                                        data-bs-toggle="modal"
                                        data-bs-target="#<?php echo $seguimiento1ModalId; ?>">
                                        <img src="images/iconos/envelope-open-fill.svg" alt="Correo seguimiento 1" title="Ver texto del seguimiento 1" aria-label="Correo seguimiento 1">
                                </button>
                        </div>
                </div>
                <div class="modal fade" id="<?php echo $seguimiento1ModalId; ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                <div class="modal-content">
                                        <div class="modal-header" style="background-color: #b0d588;">
                                                <h5 class="modal-title"><b>TEXTO SEGUIMIENTO 1</b></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                                <p class="mb-2">Texto listo para copiar y pegar.</p>
                                                <textarea id="<?php echo $seguimiento1TextareaId; ?>" class="form-control" rows="14" readonly><?php echo htmlspecialchars($seguimiento1Texto); ?></textarea>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                                <button type="button"
                                                        class="btn btn-outline-secondary"
                                                        onclick="(function(){var textarea=document.getElementById('<?php echo $seguimiento1TextareaId; ?>'); if(!textarea){return;} textarea.focus(); textarea.select(); try { document.execCommand('copy'); } catch (e) {} })();">
                                                        Copiar texto
                                                </button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        </div>
                                </div>
                        </div>
                </div>
                <div class="row align-items-center seguimiento-row">
                        <div class="col-auto">
                                <span class="seguimiento-main">
                                        <b>SEGUIMIENTO 2:</b>
                                        <?php echo checkAndHighlightDate($curso['seguimento2'], $curso['seguimento2check']); ?>
                                        <input type="checkbox" class="form-check-input" disabled <?php if ($curso['seguimento2check'] == 1) {
                                                                                                                echo "checked";
                                                                                                        } ?>>
                                </span>
                        </div>
                </div>
                <div class="row align-items-center seguimiento-row">
                        <div class="col-auto">
                                <span class="seguimiento-main">
                                        <b>SEGUIMIENTO 3:</b>
                                        <?php echo checkAndHighlightDate($curso['seguimento3'], $curso['seguimento3check']); ?>
                                        <input type="checkbox" class="form-check-input" disabled <?php if ($curso['seguimento3check'] == 1) {
                                                                                                                echo "checked";
                                                                                                        } ?>>
                                </span>
                        </div>
                        <div class="col-auto ps-1">
                                <button type="button"
                                        class="btn btn-sm seguimiento-action"
                                        data-bs-toggle="modal"
                                        data-bs-target="#<?php echo $seguimiento3ModalId; ?>">
                                        <img src="images/iconos/envelope-open-fill.svg" alt="Correo seguimiento 3" title="Ver texto del seguimiento 3" aria-label="Correo seguimiento 3">
                                </button>
                        </div>
                </div>
                <div class="modal fade" id="<?php echo $seguimiento3ModalId; ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                <div class="modal-content">
                                        <div class="modal-header" style="background-color: #b0d588;">
                                                <h5 class="modal-title"><b>TEXTO SEGUIMIENTO 3</b></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                                <p class="mb-2">Texto listo para copiar y pegar.</p>
                                                <textarea id="<?php echo $seguimiento3TextareaId; ?>" class="form-control" rows="14" readonly><?php echo htmlspecialchars($seguimiento3Texto); ?></textarea>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                                <button type="button"
                                                        class="btn btn-outline-secondary"
                                                        onclick="(function(){var textarea=document.getElementById('<?php echo $seguimiento3TextareaId; ?>'); if(!textarea){return;} textarea.focus(); textarea.select(); try { document.execCommand('copy'); } catch (e) {} })();">
                                                        Copiar texto
                                                </button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        </div>
                                </div>
                        </div>
                </div>
                <div class="row align-items-center seguimiento-row">
                        <div class="col-auto">
                                <span class="seguimiento-main">
                                        <b>SEGUIMIENTO 4:</b>
                                        <?php echo checkAndHighlightDate($curso['seguimento4'], $curso['seguimento4check']); ?>
                                        <input type="checkbox" class="form-check-input" disabled <?php if ($curso['seguimento4check'] == 1) {
                                                                                                                echo "checked";
                                                                                                        } ?>>
                                </span>
                        </div>
                        <div class="col-auto ps-1">
                                <button type="button"
                                        class="btn btn-sm seguimiento-action"
                                        data-bs-toggle="modal"
                                        data-bs-target="#<?php echo $seguimiento4ModalId; ?>">
                                        <img src="images/iconos/envelope-open-fill.svg" alt="Correo seguimiento 4" title="Ver texto del seguimiento 4" aria-label="Correo seguimiento 4">
                                </button>
                        </div>
                </div>
                <div class="modal fade" id="<?php echo $seguimiento4ModalId; ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                <div class="modal-content">
                                        <div class="modal-header" style="background-color: #b0d588;">
                                                <h5 class="modal-title"><b>TEXTO SEGUIMIENTO 4</b></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                                <p class="mb-2">Texto listo para copiar y pegar.</p>
                                                <textarea id="<?php echo $seguimiento4TextareaId; ?>" class="form-control" rows="14" readonly><?php echo htmlspecialchars($seguimiento4Texto); ?></textarea>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                                <button type="button"
                                                        class="btn btn-outline-secondary"
                                                        onclick="(function(){var textarea=document.getElementById('<?php echo $seguimiento4TextareaId; ?>'); if(!textarea){return;} textarea.focus(); textarea.select(); try { document.execCommand('copy'); } catch (e) {} })();">
                                                        Copiar texto
                                                </button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        </div>
                                </div>
                        </div>
                </div>
                <div class="row align-items-center seguimiento-row">
                        <div class="col-auto">
                                <span class="seguimiento-main">
                                        <b>SEGUIMIENTO 5:</b>
                                        <?php echo checkAndHighlightDate($curso['seguimento5'], $curso['seguimento5check']); ?>
                                        <input type="checkbox" class="form-check-input" disabled <?php if ($curso['seguimento5check'] == 1) {
                                                                                                                echo "checked";
                                                                                                        } ?>>
                                </span>
                        </div>
                        <div class="col-auto ps-1">
                                <button type="button"
                                        class="btn btn-sm seguimiento-action"
                                        data-bs-toggle="modal"
                                        data-bs-target="#<?php echo $finalTutoriaModalId; ?>">
                                        <img src="images/iconos/envelope-open-fill.svg" alt="Correo final" title="Ver texto del mensaje final" aria-label="Correo final">
                                </button>
                        </div>
                </div>
                <div class="modal fade" id="<?php echo $finalTutoriaModalId; ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                <div class="modal-content">
                                        <div class="modal-header" style="background-color: #b0d588;">
                                                <h5 class="modal-title"><b>TEXTO MENSAJE FINAL</b></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                                <p class="mb-2">Texto listo para copiar y pegar.</p>
                                                <textarea id="<?php echo $finalTutoriaTextareaId; ?>" class="form-control" rows="14" readonly><?php echo htmlspecialchars($finalTutoriaTexto); ?></textarea>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                                <button type="button"
                                                        class="btn btn-outline-secondary"
                                                        onclick="(function(){var textarea=document.getElementById('<?php echo $finalTutoriaTextareaId; ?>'); if(!textarea){return;} textarea.focus(); textarea.select(); try { document.execCommand('copy'); } catch (e) {} })();">
                                                        Copiar texto
                                                </button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        </div>
                                </div>
                        </div>
                </div>
                <div>
                        <?php
                        include("template-parts/components/commentSection.(seguimentosAndComments.(curso.listadoCursos)).php");
                        ?>
                </div>


                <div class="row">
                        <div class='col-auto mx-auto'>
                                <?php
                                include("template-parts/components/seguimentos.(seguimentosAndComments.(curso.listadoCursos)).php");
                                ?>
                        </div>
                </div>
        </div>
</div>