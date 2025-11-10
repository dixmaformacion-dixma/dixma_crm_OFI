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
                <input name="idEmpresa" type="hidden" value="<?php echo $alumno['idEmpresa']; ?>"></input>
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
                    <b>DOC A.F:</b>
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