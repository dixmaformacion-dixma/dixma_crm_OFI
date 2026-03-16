<?php
//next code block sets up courses
if(!isset($tipoCursosArray)){
        $tipoCursosArray = cargarTipoCurso();
        if($tipoCursosArray){
                foreach($tipoCursosArray as $tipo){
                        $cursosArray[$tipo] = listaCursos($tipo);
                }
        }

        echo '<script>var CourseArray = {';
        foreach($tipoCursosArray as $tipo){
                foreach($cursosArray[$tipo] as $coursetemp){
                        echo ''.$coursetemp['idCurso'].': {nombreCurso: "'.$coursetemp['nombreCurso'].'", horasCurso: "'.$coursetemp['horasCurso'].'"},';
                }
        }
        echo '}</script>';
}
?>
<div class="col-md-12 col-12 collapse container p-2 border rounded adjuntar" id="AttachTo<?php echo $alumno['idAlumno']; ?>">
    <div class="row">
        <h3>Insertar curso:</h3>
    </div>
    <form method="post" action="tutoria_insertarCurso.php">
        <input type="hidden" name="idAlumno" value="<?php echo $alumno['idAlumno']; ?>">
        <?php
        $empresaData = cargarEmpresa($alumno['idEmpresa']);
        $empresaSeleccion = obtenerSeleccionEmpresa($empresaData);
        $empresaOpciones = $empresaSeleccion['opciones'];
        $esGrupo = $empresaSeleccion['esGrupo'];
        $alumnoId = $alumno['idAlumno'];
        ?>
        <div class="row mb-2">
            <input name="idEmpresa" type="hidden" value="<?php echo $alumno['idEmpresa']; ?>">
            <?php if ($esGrupo): ?>
                <?php
                // Build a JS array to auto-sync CIF when nombre changes
                $empresaOpcionesJS = json_encode($empresaOpciones);
                ?>
                <script>
                var empresaOpciones_<?php echo $alumnoId; ?> = <?php echo $empresaOpcionesJS; ?>;
                function syncCif_<?php echo $alumnoId; ?>(sel) {
                    var idx = sel.selectedIndex - 1; // -1 because index 0 is the disabled placeholder
                    var opcion = empresaOpciones_<?php echo $alumnoId; ?>[idx] || null;
                    var cif = opcion ? opcion.cif : '';
                    document.getElementById('cif_display_<?php echo $alumnoId; ?>').textContent = cif || '—';
                    document.getElementById('cif_hidden_<?php echo $alumnoId; ?>').value = cif;
                }
                </script>
                                <div class="col-12 mb-1">
                                        <span class="badge bg-warning text-dark">Grupo (<?php echo count($empresaOpciones); ?>)</span>
                                </div>
                                <div class="col-md-6 col-12">
                                        <div class="d-flex align-items-center gap-2 h-100">
                                                <b class="mb-0 text-nowrap">Empresa:</b>
                                                <select name="nombre_empresa_seleccionada"
                                                                class="form-select form-select-sm"
                                                                onchange="syncCif_<?php echo $alumnoId; ?>(this)" required>
                                                        <option disabled selected value="">-- selecciona empresa --</option>
                                                        <?php foreach ($empresaOpciones as $empresaOpcion): ?>
                                                                <option value="<?php echo htmlspecialchars($empresaOpcion['nombre']); ?>">
                                                                        <?php echo htmlspecialchars($empresaOpcion['nombre']); ?>
                                                                </option>
                                                        <?php endforeach; ?>
                                                </select>
                                        </div>
                                </div>
                                <div class="col-md-4 col-12">
                                        <div class="d-flex align-items-center gap-2 h-100">
                                                <b class="mb-0 text-nowrap">CIF:</b>
                                                <span id="cif_display_<?php echo $alumnoId; ?>" class="fw-bold text-secondary">—</span>
                                        </div>
                                        <input type="hidden"
                                                   id="cif_hidden_<?php echo $alumnoId; ?>"
                                                   name="cif_seleccionado"
                                                   value="">
                                </div>
            <?php else: ?>
                <label class="col-md-6 col-12">
                    <b>Empresa:</b>
                                        <span class="ms-2"><?php echo htmlspecialchars($empresaSeleccion['seleccionada']['nombre']); ?></span>
                    <input type="hidden" name="nombre_empresa_seleccionada"
                                                   value="<?php echo htmlspecialchars($empresaSeleccion['seleccionada']['nombre']); ?>">
                </label>
                <label class="col-md-4 col-12">
                    <b>CIF:</b>
                                        <span class="ms-2"><?php echo htmlspecialchars($empresaSeleccion['seleccionada']['cif']); ?></span>
                    <input type="hidden" name="cif_seleccionado"
                                                   value="<?php echo htmlspecialchars($empresaSeleccion['seleccionada']['cif']); ?>">
                </label>
            <?php endif; ?>
        </div>
        <div class="row">
                <label class="d-flex align-items-center">
                <input type="checkbox" name="selectFromCourseList" onchange='changeCourseSelectionMode("Alumno<?php echo $alumno['idAlumno']; ?>")'>
                <b class="ms-2">Type:</b>
                        <?php
                        if($tipoCursosArray){
                                ?>
                                <select class="select col-md-4 col-12" name="type" onchange='selectTypeOfCourses("Alumno<?php echo $alumno['idAlumno']; ?>")'>
                                <option disabled selected value> -- select an option -- </option>
                                <?php
                                foreach($tipoCursosArray as $tipo){
                                        echo '
                                        <option value="'.$tipo.'">
                                                '.$tipo.'
                                        </option>
                                        ';
                                }
                                echo '</select>
                                ';
                                
                                ?>
                                <select class="select col-md-6 col-12" name="idCurso" onchange='selectCourse("Alumno<?php echo $alumno['idAlumno']; ?>")'>
                                        <option disabled selected value> -- select an option -- </option>
                                <?php
                                foreach($tipoCursosArray as $tipo){
                                        foreach($cursosArray[$tipo] as $coursetemp){
                                                echo '<option style="display:none" class="courseOptions class'.$tipo.'" value="'.$coursetemp['idCurso'].'">'.$coursetemp['nombreCurso'].'</option>';
                                        }
                                }
                                echo '</select>';
                        }
                        ?>
                </label>
        </div>
        <div class="row">
            <label class='col-md-8 col-12'>
                    <b>Denominacion:</b>
                    <input name="Denominacion" class="form-control form-control-sm text-uppercase" type="text"></input>
            </label>
        </div>
        <div class="row">
            <label class='col-md-4 col-12'>
                    <b>N Accion:</b>
                    <input name="N_Accion" class="form-control form-control-sm text-uppercase" type="number" required></input>
            </label>
            <label class='col-md-4 col-12'>
                    <b>N Grupo:</b>
                    <input name="N_Grupo" class="form-control form-control-sm text-uppercase" type="number" required></input>
            </label>
            <label class='col-md-4 col-12'>
                    <b>N Horas:</b>
                    <input name="N_Horas" class="form-control form-control-sm text-uppercase" type="text"></input>
            </label>
            <label class='col-md-4 col-12'>
                    <b>Observaciones/DOC A.F:</b> 
                    <input name="DOC_AF" class="form-control form-control-sm text-uppercase" type="text"></input>
            </label>
            <label class='col-md-4 col-12'>
                    <b>tutor:</b>
                    <input name="tutor" class="form-control form-control-sm text-uppercase" type="text"></input>
            </label>
        </div>
        <div class="row">
                <label class='col-md-6 col-12'>
                        <b>Fecha Inicio:</b>
                        <input onchange="changeSeguimentoDates('Alumno<?php echo $alumno['idAlumno']; ?>')" name="Fecha_Inicio" class="form-control form-control-sm text-uppercase Fecha_Inicio" type="date"></input>
                </label>
                <label class='col-md-6 col-12'>
                        <b>Fecha Fin:</b>
                        <input onchange="changeSeguimentoDates('Alumno<?php echo $alumno['idAlumno']; ?>')" name="Fecha_Fin" class="form-control form-control-sm text-uppercase Fecha_Fin" type="date"></input>
                </label>
        </div>
        <div class="row">
            <label class='col-md-6 col-12'>
                    <b>Modalidad:</b>
                    <input class="form-check-input" type="radio" name="Modalidad" Value="Teleformación" checked>
                    <label class="form-check-label" for="Teleformación">
                        Teleformación
                    </label>
                    <input class="form-check-input" type="radio" name="Modalidad" Value="Presencial">
                    <label class="form-check-label" for="Presencial">
                        Presencial
                    </label>
                    <input class="form-check-input" type="radio" name="Modalidad" Value="Mixto">
                    <label class="form-check-label" for="Mixto">
                        Mixto
                    </label>
            </label>
        </div>
        <div class="row">
            <label class='col-md-6 col-12'>
                    <b>Tipo venta:</b>
                    <input class="form-check-input" type="radio" name="Tipo_Venta" Value="Bonificado" checked>
                    <label class="form-check-label" for="Bonificado">
                        Bonificado
                    </label>
                    <input class="form-check-input" type="radio" name="Tipo_Venta" Value="Privado">
                    <label class="form-check-label" for="Privado">
                        Privado
                    </label>
                    </input>
            </label>
        </div>
        <div class="row m-4">
                <label class='col-md-4 col-12'>
                        <b>1º TUTORÍA:</b>
                        <input name="seguimento0" class="seguimento0 form-control form-control-sm text-uppercase" type="date"></input>
                </label>
                <label class='col-md-4 col-12'>
                        <b>SEGUIMIENTO 1:</b>
                        <input name="seguimento1" class="seguimento1 form-control form-control-sm text-uppercase" type="date"></input>
                </label>
                <label class='col-md-4 col-12'>
                        <b>SEGUIMIENTO 2:</b>
                        <input name="seguimento2" class="seguimento2 form-control form-control-sm text-uppercase" type="date"></input>
                </label>
                <label class='col-md-4 col-12'>
                        <b>SEGUIMIENTO 3:</b>
                        <input name="seguimento3" class="seguimento3 form-control form-control-sm text-uppercase" type="date"></input>
                </label>
                <label class='col-md-4 col-12'>
                        <b>SEGUIMIENTO 4:</b>
                        <input name="seguimento4" class="seguimento4 form-control form-control-sm text-uppercase" type="date"></input>
                </label>
                <label class='col-md-4 col-12'>
                        <b>SEGUIMIENTO 5:</b>
                        <input name="seguimento5" class="seguimento5 form-control form-control-sm text-uppercase" type="date"></input>
                </label>
        </div>
        <div class="row col-4 mx-auto">
                <input class="form-control btn btn-primary" style="background-color:#1e989e" type="submit" value="Insertar"></input>
        </div>
    </form>
</div>