<?php
if (!isset($statusColor) or !isset($statusDateColor)) {
        $statusColor = [
                "en curso" => "",
                "finalizado" => "background-color:  lightblue;",
                "descargado" => "background-color: lightblue;",
                "cerrado" => "background-color:  lightblue;",
                "baja" => "background-color:  #c30d0d; color:white;",
                "problem" => "background-color:  Gold;"
        ];
        $statusDateColor = [
                "en curso" => "",
                "finalizado" => "",
                "descargado" => "background-color: #c30d0d; color:white;",
                "cerrado" => "background-color: #00693E; color:white;",
                "baja" => "background-color:  #c30d0d; color:white;",
                "problem" => "background-color:  Gold;"
        ];
}

if (!isset($statusDiplomaColor)) {
        $statusDiplomaColor = [
                "Copia recibida" => "background-color: #28D700;",
                "Entregado" => "background-color: #28D700;",
        ];
}
?>
<style>
        .actions {
                display: flex;
                justify-content: center;
        }

        .actions>a,
        .actions>.dropdown>a {
                padding: 2px;
                border: 1px solid black;
                border-radius: 25%;
                width: 30px;
                height: 30px;
                display: flex;
                justify-content: space_between;
                padding-left: 2px;
                background-color: white;
        }
</style>

<div class="col-md-12 col-12 container border border-2">
        <div class='row p-0 text-uppercase' style="<?php echo $statusColor[$curso['status_curso']]; ?>">
                <div style="width:5%">
                        <input type="checkbox" class="selectable" value="<?php echo $curso['StudentCursoID'] ?>">
                        <?php echo $numr; ?>
                </div>
                <?php $empresa = cargarEmpresa($curso['idEmpresa']); ?>
                <div class='col-md-2 border-right text-uppercase'>
                        <?php echo $curso['nombre'] . " " . $curso['apellidos']; ?>
                </div>
                <div style="width:9%">
                        <?php echo formattedDate($curso['Fecha_Inicio']); ?>
                </div>
                <div style="width:9%; <?php echo $statusDateColor[$curso['status_curso']] ?>">
                        <?php echo formattedDate($curso['Fecha_Fin']); ?>
                </div>
                <div class='col-md-2 border-right text-uppercase'>
                        <?php echo $curso['Denominacion']; ?>
                </div>
                <div style="width:3%">
                        <?php echo $curso['N_Accion']; ?><?php echo "/"; ?><?php echo $curso['N_Grupo']; ?>
                </div>
                <div style="width:3%">
                        <input type="checkbox" <?php if ($curso['Recibi_Material'] == 1) {
                                                        echo "checked";
                                                } ?> disabled>
                </div>
                <div style="width:3%">
                        <input type="checkbox" <?php if ($curso['CC'] == 1) {
                                                        echo "checked";
                                                } ?> disabled>
                </div>
                <?php
                    $empresaStyle = '';
                    // Inizializza se non esiste
                    if (!isset($empresasConPendientes)) {
                        $empresasConPendientes = [];
                    }
                    if (isset($curso['idEmpresa']) && in_array($curso['idEmpresa'], $empresasConPendientes)) {
                        $empresaStyle = 'background-color: #ffcccc;'; // Fondo rojo claro
                    }
                ?>
                <div class='col-md-1 border-right text-uppercase' style="<?php echo $empresaStyle; ?>">
                        <?php echo $empresa['nombre']; ?>
                </div>
                <div class='col-md-1 border-right text-uppercase' style="<?php echo @$statusDiplomaColor[$curso['Diploma_Status']] ?>">
                        <?php echo $curso['Diploma_Status']; ?>
                </div>
                <div class="col actions">
                        <a class="colapse-toggle"
                                data-bs-toggle="collapse"
                                href="#infoCurso<?php echo $curso['StudentCursoID']; ?>">
                                <img src="images/iconos2/aspect-ratio.svg">
                        </a>
                        <a class="colapse-toggle"
                                data-bs-toggle="collapse"
                                href="#infoEdit<?php echo $curso['StudentCursoID']; ?>">
                                <img src="images/iconos2/pencil-square.svg">
                        </a>
                        <?php if (!empty($_SESSION) && isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                        <a
                                onclick="return confirm('¿Estás seguro de que deseas eliminar este curso del alumno? Esta acción es irreversible.');"
                                href="tutoria_listadoCursos.php?eliminarCurso=<?php echo $curso['StudentCursoID']; ?>">
                                <img src="images/iconos/trash.svg" alt="Eliminar">
                        </a>
                        <?php endif; ?>
                        <div class="dropdown d-flex">
                                <a href="#" class="dropdown-toggle no-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                        <img src="images/iconos/filetype-pdf.svg" alt="PDF">
                                </a>
                                <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="tutoria_diplomaPDF.php?StudentCursoID=<?php echo $curso['StudentCursoID']; ?>" target="_blank">Diploma</a></li>
                                        <li><a class="dropdown-item" href="tutoria_recepcionPDF.php?StudentCursoID=<?php echo $curso['StudentCursoID']; ?>" target="_blank">Recepción</a></li>
                                        <li><a class="dropdown-item" href="tutoria_guiaDidactica.php?StudentCursoID=<?php echo $curso['StudentCursoID']; ?>" target="_blank">Guía</a></li>
                                </ul>
                        </div>

                </div>

        </div>
        <div class="collapse" id="infoCurso<?php echo $curso['StudentCursoID']; ?>">
                <div class='row mx-auto my-2 container border border-5 m-2' style="background-color:#e8f5e9; border-color:#88c743 !important;">
                        <label class='col-md-6 col-12'>
                                <b style="color:#2e7d32;" class="text-uppercase">Empresa:</b>
                                <?php echo $empresa['nombre']; ?>
                        </label>
                        <label class='col-md-6 col-12'>
                                <b style="color:#2e7d32;" class="text-uppercase">Email Empresa:</b>
                                <span style="text-transform:none;"><?php echo $empresa['email']; ?></span>
                        </label>
                        <label class='col-md-6 col-12'>
                                <b style="color:#2e7d32;">CIF:</b>
                                <span><?php echo $empresa['cif']; ?></span>
                        </label>
                        <label class='col-md-6 col-12'>
                                <b style="color:#2e7d32;" class="text-uppercase">telefono Empresa:</b>
                                <?php echo $empresa['telef1'] . " | " . $empresa['telef2']; ?>
                        </label>
                        <label class='col-md-6 col-12'>
                                <b style="color:#2e7d32;" class="text-uppercase">Persona de contacto:</b>
                                <?php echo $empresa['personacontacto']; ?>
                        </label>
                        <div class="text-center mt-2 mb-2">
                                <a href="buscarVenta.php?valor=<?php echo urlencode($empresa['nombre']); ?>&consultar=Buscar" target="_blank" class="btn btn-info">Información Empresa</a>
                                <button class="btn" 
                                        style="color:white; background-color:#2e7d32; border:none;"
                                        onclick="loginByCourse('<?php echo addslashes($curso['N_Accion']); ?>', '<?php echo date('Y', strtotime($curso['Fecha_Inicio'])); ?>', 'profesor')" 
                                        title="Acceso Campus">
                                        ACCESO CAMPUS
                                </button>
                        </div>
                </div>
                <div class='row mx-auto my-2'>
                        <div class='row mx-auto my-2 container border border-5 m-2' style="background-color:#e8f5e9; border-color:#88c743 !important;">
                                <label class='col-md-6 col-12'>
                                        <b style="color:#2e7d32;" class="text-uppercase">Telefono Alumno:</b>
                                        <?php echo $curso['telefono']; ?>
                                </label>
                                <label class='col-md-6 col-12'>
                                        <b style="color:#2e7d32;" class="text-uppercase">Email Alumno:</b>
                                        <span style="text-transform:none;"><?php echo $curso['email']; ?></span>
                                </label>
                                <label class='col-md-6 col-12'>
                                        <b style="color:#2e7d32;">DNI/NIE:</b>
                                        <?php echo $curso['nif']; ?>
                                </label>
                                <label class='col-md-6 col-12'>
                                        <b style="color:#2e7d32;" class="text-uppercase">Horario Laboral:</b>
                                        <?php echo $curso['horarioLaboral']; ?>
                                </label>
                        </div>
         <?php
                // Obtener cursos anteriores del alumno
                if (isset($curso['idAlumno']) && isset($year)) {
                    $cursosPrevios = obtenerCursosPreviosAlumno($curso['idAlumno'], $year, $curso['StudentCursoID']);
                    
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
                        echo '<th>Estado</th>';
                        echo '<th>Estado Diploma</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';
                        
                        foreach ($cursosPrevios as $cp) {
                            $yearCurso = date('Y', strtotime($cp['Fecha_Inicio']));
                            $statusStyle = '';
                            if ($cp['status_curso'] == 'finalizado' || $cp['status_curso'] == 'cerrado') {
                                $statusStyle = 'background-color: lightblue;';
                            } elseif ($cp['status_curso'] == 'baja') {
                                $statusStyle = 'background-color: #ffcccc;';
                            }
                            
                            // Estilo para estado diploma
                            $diplomaStyle = '';
                            $diplomaStatus = $cp['Diploma_Status'] ?? '';
                            if ($diplomaStatus == 'Copia recibida' || $diplomaStatus == 'Entregado') {
                                $diplomaStyle = 'background-color: #28D700; color: white;';
                            }
                            
                            echo '<tr style="' . $statusStyle . '">';
                            echo '<td>' . htmlspecialchars($yearCurso) . '</td>';
                            echo '<td class="text-uppercase"><small>' . htmlspecialchars($cp['Denominacion']) . '</small></td>';
                            echo '<td>' . formattedDate($cp['Fecha_Inicio']) . '</td>';
                            echo '<td>' . formattedDate($cp['Fecha_Fin']) . '</td>';
                            echo '<td class="text-uppercase"><small>' . htmlspecialchars($cp['status_curso']) . '</small></td>';
                            echo '<td class="text-uppercase" style="' . $diplomaStyle . '"><small>' . htmlspecialchars($diplomaStatus) . '</small></td>';
                            echo '</tr>';
                        }
                        
                        echo '</tbody>';
                        echo '</table>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                }
                ?>
                </div>
                <div class="col-md-12 col-12 container mt-3 mb-2 border border-2 rounded" style="background-color:#e8f5e9; border-color:#88c743 !important;">
                        <div class="text-uppercase">
                        <label class='col-md-12 col-12'>
                                <b style="color:#2e7d32;">Denominacion:</b>
                                <?php echo $curso['Denominacion']; ?>
                        </label>
                        <label class='col-md-3 col-12'>
                                <b style="color:#2e7d32;">№ Accion:</b>
                                <?php echo $curso['N_Accion']; ?>
                        </label>
                        <label class='col-md-4 col-12'>
                                <b style="color:#2e7d32;">№ Grupo:</b>
                                <?php echo $curso['N_Grupo']; ?>
                        </label>
                        <label class='col-md-4 col-12' style="background-color:#28D700; padding:8px; border-radius:5px; margin-top:5px; margin-bottom:5px;">
                                <b style="color:white; font-size:16px;">№ Horas:</b>
                                <span style="color:white; font-size:16px; font-weight:bold;"><?php echo $curso['N_Horas']; ?></span>
                        </label>
                        <label class='col-md-3 col-12'>
                                <b style="color:#2e7d32;">Modalidad:</b>
                                <?php echo $curso['Modalidad']; ?>
                        </label>
                        <label class='col-md-4 col-12'>
                                <b style="color:#2e7d32;">Tipo Venta:</b>
                                <?php echo $curso['Tipo_Venta']; ?>
                        </label>
                        <label class='col-md-4 col-12'>
                                <b style="color:#2e7d32;">Tutor:</b>
                                <?php echo $curso['tutor']; ?>
                        </label>
                        <label class='col-md-12 col-12 mt-2'>
                                <b style="color:#2e7d32;">Empresa (en el momento del formacion):</b>
                                <?php $empresa = cargarEmpresa($curso['idEmpresa']);
                                echo $empresa['nombre']; ?>
                                [Tel: <b><?php echo $empresa['telef1']; ?></b>]
                        </label>
                        <div class="row">
                                <label class='col-md-3 col-12'>
                                        <b style="color:#2e7d32;">A.P:</b>
                                        <?php echo $curso['AP']; ?>
                                </label>
                                <label class='col-md-4 col-12'>
                                        <b style="color:#2e7d32;">Recibi_Material:</b>
                                        <input type="checkbox" class="checkbox-green" <?php if ($curso['Recibi_Material'] == 1) {
                                                                        echo "checked";
                                                                } ?> disabled>
                                </label>
                                <label class='col-md-2 col-12'>
                                        <b style="color:#2e7d32;">CC:</b>
                                        <input type="checkbox" class="checkbox-green" <?php if ($curso['CC'] == 1) {
                                                                        echo "checked";
                                                                } ?> disabled>
                                </label>
                                <label class='col-md-2 col-12'>
                                        <b style="color:#2e7d32;">RLT:</b>
                                        <input type="checkbox" class="checkbox-green" <?php if ($curso['RLT'] == 1) {
                                                                        echo "checked";
                                                                } ?> disabled>
                                </label>
                        </div>
                        <div class="row">
                                <label class='col-md-12 col-12'>
                                        <b style="color:#2e7d32;">Diploma:</b>
                                        <?php echo $curso['Diploma_Status']; ?>
                                        <i>(la última vez que cambió el estado del diploma): <?php echo formattedDate($curso['Diploma_Status_Ultimo_Cambio']); ?></i>
                                </label>
                        </div>
                        </div>
                       <div style="background-color: #e8f5e9; padding: 10px; border-radius: 5px; border: 2px solid #88c743;">
                        <?php
                        require("template-parts/components/seguimentosAndComments.(curso.listadoCursos).php");
                        ?>
                        </div>
                </div>
        </div>
        <?php
        ?>
        <div class="text-uppercase">
        <?php require("template-parts/components/cursoEditar.(curso.listadoCursos).php"); ?>
        </div>
</div>
<script>
        // Toggle custom PDF menus (no Bootstrap)
        document.addEventListener('click', function(e) {
                var btn = e.target.closest('.print-pdf-btn');
                if (btn) {
                        var container = btn.closest('.print-pdf-dropdown');
                        var menu = container.querySelector('.print-pdf-menu');
                        // close others
                        document.querySelectorAll('.print-pdf-menu.show').forEach(function(m) {
                                if (m !== menu) m.classList.remove('show');
                        });
                        menu.classList.toggle('show');
                        return;
                }
                // close if click outside any dropdown
                if (!e.target.closest('.print-pdf-dropdown')) {
                        document.querySelectorAll('.print-pdf-menu.show').forEach(function(m) {
                                m.classList.remove('show');
                        });
                }
        });
</script>