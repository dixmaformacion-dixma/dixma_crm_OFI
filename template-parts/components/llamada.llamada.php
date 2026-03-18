<?php
$completed = (
        (strtotime($llamada['seguimento0']) <= strtotime($date) && $llamada['seguimento0check'] == "0") ||
        (strtotime($llamada['seguimento1']) <= strtotime($date) && $llamada['seguimento1check'] == "0") ||
        (strtotime($llamada['seguimento2']) <= strtotime($date) && $llamada['seguimento2check'] == "0") ||
        (strtotime($llamada['seguimento3']) <= strtotime($date) && $llamada['seguimento3check'] == "0") ||
        (strtotime($llamada['seguimento4']) <= strtotime($date) && $llamada['seguimento4check'] == "0") ||
        (strtotime($llamada['seguimento5']) <= strtotime($date) && $llamada['seguimento5check'] == "0")
);
$alumnoSearchValue = !empty($llamada['nif']) ? $llamada['nif'] : $llamada['apellidos'];
$alumnoSearchUrl = 'administracion_buscarAlumno.php?valor=' . urlencode($alumnoSearchValue) . '&consultar=Buscar';
?>
<div class="col-md-12 col-12 container mt-3 border border-4 rounded">
        <div class='row mx-auto my-2 align-items-center'>
                <div class='col'>
                        <b>Nombre:</b>
                        <a href="<?php echo htmlspecialchars($alumnoSearchUrl); ?>" class="text-uppercase text-decoration-none text-reset">
                                <?php echo htmlspecialchars($llamada['nombre'] . " " . $llamada['apellidos']); ?>
                        </a>
                </div>
                <?php if(!$completed){?>
                        <b class='col-auto'>Todas las llamadas ya fueron hechas</b>
                        <a 
                                class="btn btn-primary col-auto"
                                data-bs-toggle="collapse"
                                href="#infoLlamada<?php echo $llamada['StudentCursoID']; ?>">
                                mas detalle
                        </a>
                <?php } ?>
                <div class='col-auto ms-auto d-flex gap-2'>
                        <?php $empresaHeader = cargarEmpresa($llamada['idEmpresa']); ?>
                        <a href="buscarVenta.php?valor=<?php echo urlencode($empresaHeader['nombre']); ?>&consultar=Buscar" target="_blank" class="btn btn-sm btn-info">
                                Información Empresa
                        </a>
                        <a href="tutoria_buscarCursos.php?filterName[]=idEmpresa&filterOperator[]=%3D&filterValue[]=<?php echo urlencode($llamada['idEmpresa']); ?>&consultar=Buscar" target="_blank" class="btn btn-sm" style="background-color:#6f42c1; color:#fff; border-color:#6f42c1;">
                                Cursos Empresa
                        </a>
                        <button type="button" class="btn btn-sm btn-success" 
                                onclick="loginByCourse('<?php echo addslashes($llamada['N_Accion']); ?>', '<?php echo date('Y', strtotime($llamada['Fecha_Inicio'])); ?>', 'profesor')" 
                                title="Acceso Campus">
                                Acceso Campus
                        </button>
                </div>
        </div>
        <div class="collapse <?php if($completed){ echo "show";} ?>" id="infoLlamada<?php echo $llamada['StudentCursoID']; ?>" >
                <div class='row mx-auto my-2'>
                        <label class='col-md-4 col-12'>
                                <b>telefono:</b>
                                <?php echo $llamada['telefono']; ?>
                        </label>
                        <label class='col-md-4 col-12'>
                                <b>email:</b>
                                <?php echo $llamada['email']; ?>
                        </label>
                        <label class='col-md-4 col-12'>
                                <b>fechaNacimiento:</b>
                                <?php echo formattedDate($llamada['fechaNacimiento']); ?>
                        </label>
                </div>
                <div class="col-md-12 col-12 container mt-3 mb-2 border border-2 rounded" style="background-color:white">
                        <label class='col-md-12 col-12'>
                                <b>Denominacion:</b>
                                <?php echo $llamada['Denominacion']; ?>
                        </label>
                        <label class='col-md-4 col-12'>
                                <b>N Accion:</b>
                                <span style="background-color:#28D700; color:white; font-weight:bold; padding:1px 6px; font-size:0.95rem; border:1px solid #000; border-radius:0;"><?php echo $llamada['N_Accion']; ?></span>
                        </label>
                        <label class='col-md-4 col-12'>
                                <b>N Grupo:</b>
                                <span style="background-color:#28D700; color:white; font-weight:bold; padding:1px 6px; font-size:0.95rem; border:1px solid #000; border-radius:0;"><?php echo $llamada['N_Grupo']; ?></span>
                        </label>
                        <label class='col-md-4 col-12'>
                                <b>N Horas:</b>
                                <span style="background-color:#ffc107; color:#212529; font-weight:bold; padding:1px 6px; font-size:0.95rem; border:1px solid #000; border-radius:0;"><?php echo $llamada['N_Horas']; ?></span>
                        </label>
                        <label class='col-md-4 col-12'>
                                <b>Modalidad:</b>
                                <?php echo $llamada['Modalidad']; ?>
                        </label>
                        <label class='col-md-4 col-12'>
                                <b>Tipo Venta:</b>
                                <?php echo $llamada['Tipo_Venta']; ?>
                        </label>
                        <label class='col-md-4 col-12'>
                                <b>Tutor:</b>
                                <?php echo $llamada['tutor']; ?>
                        </label>
                        <label class='col-md-12 col-12 mt-2'>
                                <b>Empresa:</b>
                                <?php $empresa = cargarEmpresa($llamada['idEmpresa']);
                                echo !empty($llamada['nombre_empresa_seleccionada']) ? htmlspecialchars($llamada['nombre_empresa_seleccionada']) : htmlspecialchars($empresa['nombre']); ?>
                                [Tel: <b><?php echo $empresa['telef1']; ?></b>]
                                [Persona de contacto:: <b><?php echo $empresa['personacontacto']; ?></b>]
                        </label>
                        <label class='col-md-4 col-12'>
                                <b>Fecha_Inicio:</b>
                                <?php echo date("d/m/Y",strtotime($llamada['Fecha_Inicio'])); ?>
                        </label>
                        <label class='col-md-4 col-12'>
                                <b>Fecha_Fin:</b>
                                <?php echo date("d/m/Y",strtotime($llamada['Fecha_Fin'])); ?>
                        </label>
                        <?php 
                                $curso = $llamada;
                                require("template-parts/components/seguimentosAndComments.(curso.listadoCursos).php");
                        ?>
                </div>
                <?php
                // Cursos anteriores del alumno
                if (isset($llamada['idAlumno'])) {
                                        $yearFilter = isset($year) ? $year : null;
                                        $cursosPrevios = obtenerCursosPreviosAlumno($llamada['idAlumno'], $yearFilter, $llamada['StudentCursoID']);
                    if (!empty($cursosPrevios)) {
                        echo '<div class="col-md-12 col-12 container mt-3 mb-2 border border-2 rounded" style="background-color:#fff3cd;">';
                        echo '<div class="p-2">';
                        echo '<h6 class="mb-2"><b>📚 CURSOS ANTERIORES (' . count($cursosPrevios) . ')</b></h6>';
                        echo '<div class="table-responsive">';
                        echo '<table class="table table-sm table-striped table-bordered">';
                        echo '<thead style="background-color:#88c743;">';
                        echo '<tr>';
                        echo '<th>Año</th>';
                        echo '<th>Denominación</th>';
                        echo '<th>Fecha Inicio</th>';
                        echo '<th>Fecha Fin</th>';
                                                echo '<th>№ Horas</th>';
                        echo '<th>Tipo Venta</th>';
                        echo '<th>Estado Diploma</th>';
                                                echo '<th>Comentarios</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';
                                                $previousCoursesCommentModals = '';
                        foreach ($cursosPrevios as $cp) {
                            $yearCurso = date('Y', strtotime($cp['Fecha_Inicio']));
                            $statusStyle = '';
                            if ($cp['status_curso'] == 'finalizado' || $cp['status_curso'] == 'cerrado') {
                                $statusStyle = 'background-color: lightblue;';
                            } elseif ($cp['status_curso'] == 'baja') {
                                $statusStyle = 'background-color: #ffcccc;';
                            }
                            $diplomaStyle = '';
                            $diplomaStatus = $cp['Diploma_Status'] ?? '';
                            if ($diplomaStatus == 'Copia recibida' || $diplomaStatus == 'Entregado') {
                                $diplomaStyle = 'background-color: #28D700; color: white;';
                            }
                            $previousCourseModalId = 'previousCourseCommentsModal' . $cp['StudentCursoID'];
                            echo '<tr style="' . $statusStyle . '">';
                            echo '<td>' . htmlspecialchars($yearCurso) . '</td>';
                            echo '<td class="text-uppercase"><small>' . htmlspecialchars($cp['Denominacion']) . '</small></td>';
                            echo '<td>' . formattedDate($cp['Fecha_Inicio']) . '</td>';
                            echo '<td>' . formattedDate($cp['Fecha_Fin']) . '</td>';
                            echo '<td>' . htmlspecialchars(isset($cp['N_Horas']) ? $cp['N_Horas'] : '') . '</td>';
                            echo '<td class="text-uppercase"><small>' . htmlspecialchars($cp['Tipo_Venta']) . '</small></td>';
                            echo '<td class="text-uppercase" style="' . $diplomaStyle . '"><small>' . htmlspecialchars($diplomaStatus) . '</small></td>';
                            echo '<td class="text-center">';
                            echo '<button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#' . htmlspecialchars($previousCourseModalId) . '">';
                            echo '📝 Mostrar Comentarios';
                            echo '</button>';
                            echo '</td>';
                            echo '</tr>';

                            ob_start();
                            ?>
                            <div class="modal fade" id="<?php echo htmlspecialchars($previousCourseModalId); ?>" tabindex="-1" aria-labelledby="<?php echo htmlspecialchars($previousCourseModalId); ?>Label" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                            <div class="modal-content">
                                                    <div class="modal-header" style="background-color: #b0d588;">
                                                            <h5 class="modal-title" id="<?php echo htmlspecialchars($previousCourseModalId); ?>Label">
                                                                    <b>COMENTARIOS - <?php echo htmlspecialchars($llamada['nombre'] . ' ' . $llamada['apellidos']); ?></b><br>
                                                                    <small class="text-muted"><?php echo htmlspecialchars($cp['Denominacion']); ?></small>
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                            <?php
                                                            $cursoComentarioTarget = $cp;
                                                            include("template-parts/components/commentSection.(seguimentosAndComments.(curso.listadoCursos)).php");
                                                            unset($cursoComentarioTarget);
                                                            ?>
                                                    </div>
                                                    <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                    </div>
                                            </div>
                                    </div>
                            </div>
                            <?php
                            $previousCoursesCommentModals .= ob_get_clean();
                        }
                        echo '</tbody>';
                        echo '</table>';
                        echo '</div>';
                        echo $previousCoursesCommentModals;
                        echo '</div>';
                        echo '</div>';
                    }
                }
                ?>
        </div>
</div>