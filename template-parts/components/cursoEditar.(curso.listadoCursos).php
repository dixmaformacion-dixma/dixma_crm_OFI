<?php
require_once "funciones/funcionesContenidos.php";
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
<div class="col-md-12 col-12 collapse container p-2 border rounded" id="infoEdit<?php echo $curso['StudentCursoID']; ?>" style="background-color:white">
    <div class="row">
        <h3>Editar curso:</h3>
    </div>
    <form method="post" action="<?php echo $page_from;?>" enctype="multipart/form-data">
        <input type="hidden" name="StudentCursoID" value="<?php echo $curso['StudentCursoID']; ?>">
        <input type="hidden" name="function" value="editar_AlumnoCurso">
        <div class="row">
                <div>
                        <input type="checkbox" name="selectFromCourseList" class="form-check-input" onchange='changeCourseSelectionMode("infoEdit<?php echo $curso['StudentCursoID']; ?>")' <?php if($curso['idCurso'] != null){echo "checked";} ?>>
                </div>
                <div>
                <b>Type:</b>
                        <?php
                        if($tipoCursosArray){
                                ?>
                                <select class="select col-md-4 col-12" name="type" onchange='selectTypeOfCourses("infoEdit<?php echo $curso['StudentCursoID']; ?>")'>
                                <?php
                                if($curso['idCurso'] != null){
                                        foreach($tipoCursosArray as $tipo){
                                                foreach($cursosArray[$tipo] as $coursetemp){
                                                        if($coursetemp['idCurso'] == $curso['idCurso']){
                                                                echo '<option disabled selected value='.$tipo.'>'.$tipo.'</option>';
                                                        }
                                                }
                                        }
                                }else{
                                        echo "<option disabled selected value> -- select an option -- </option>";
                                }
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
                                <select class="select col-md-6 col-12" name="idCurso" onchange='selectCourse("infoEdit<?php echo $curso['StudentCursoID']; ?>")'>
                                <?php 
                                if($curso['idCurso'] != null){
                                        foreach($tipoCursosArray as $tipo){
                                                foreach($cursosArray[$tipo] as $coursetemp){
                                                        if($coursetemp['idCurso'] == $curso['idCurso']){
                                                                echo '<option disabled selected value='.$curso['idCurso'] .'>'.$coursetemp['nombreCurso'].'</option>';
                                                        }
                                                }
                                        }
                                }else{
                                        echo "<option disabled selected value> -- select an option -- </option>";
                                }
                                ?>
                                <?php
                                foreach($tipoCursosArray as $tipo){
                                        foreach($cursosArray[$tipo] as $coursetemp){
                                                echo '<option style="display:none" class="courseOptions class'.$tipo.'" value="'.$coursetemp['idCurso'].'">'.$coursetemp['nombreCurso'].'</option>';
                                        }
                                }
                                echo '</select>';
                        }
                        ?>
                </div>
        </div>
        <div class="row">
                <input name="idEmpresa" type="hidden" value="<?php echo $curso['idEmpresa']; ?>"></input>
            <label class='col-md-8 col-12'>
                    <b>Denominacion:</b>
                    <input name="Denominacion" <?php if($curso['idCurso'] != null){echo " readonly ";} ?> class="form-control form-control-sm" type="text" value="<?php echo $curso['Denominacion']; ?>"></input>
            </label>
        </div>
        <div class="row">
            <label class='col-md-4 col-12'>
                    <b>N Accion:</b>
                    <input name="N_Accion" class="form-control form-control-sm" type="number" value="<?php echo $curso['N_Accion']; ?>"></input>
            </label>
            <label class='col-md-4 col-12'>
                    <b>N Grupo:</b>
                    <input name="N_Grupo" class="form-control form-control-sm" type="number" value="<?php echo $curso['N_Grupo']; ?>"></input>
            </label>
            <label class='col-md-4 col-12'>
                    <b>N Horas:</b>
                    <input name="N_Horas" class="form-control form-control-sm" type="text" value="<?php echo str_replace('.', ',', $curso['N_Horas']); ?>"></input>
            </label>
            <label class='col-md-4 col-12'>
                    <b>DOC A.F:</b>
                    <input name="DOC_AF" class="form-control form-control-sm" type="text" value="<?php echo $curso['DOC_AF']; ?>"></input>
            </label>
            <label class='col-md-4 col-12'>
                    <b>tutor:</b>
                    <input name="tutor" class="form-control form-control-sm" type="text" value="<?php echo $curso['tutor']; ?>"></input>
            </label>
        </div>
        <div class="row">
                <label class='col-md-6 col-12'>
                        <b>Fecha Inicio:</b>
                        <input name="Fecha_Inicio" class="form-control form-control-sm Fecha_Inicio" type="date" value="<?php echo $curso['Fecha_Inicio']; ?>"></input>
                </label>
                <label class='col-md-6 col-12'>
                        <b>Fecha Fin:</b>
                        <input name="Fecha_Fin" class="form-control form-control-sm Fecha_Fin" type="date" value="<?php echo $curso['Fecha_Fin']; ?>"></input>
                </label>
        </div>
        <div class="row">
            <label class='col-md-6 col-12'>
                    <b>Modalidad:</b>
                    <input class="form-check-input" type="radio" name="Modalidad" Value="Teleformación" <?php if($curso['Modalidad'] == "Teleformación"){ echo "checked"; } ?>>
                    <label class="form-check-label" for="Teleformación">
                        Teleformación
                    </label>
                    <input class="form-check-input" type="radio" name="Modalidad" Value="Presencial" <?php if($curso['Modalidad'] == "Presencial"){ echo "checked"; } ?>>
                    <label class="form-check-label" for="Presencial">
                        Presencial
                    </label>
                    <input class="form-check-input" type="radio" name="Modalidad" Value="Mixto" <?php if($curso['Modalidad'] == "Mixto"){ echo "checked"; } ?>>
                    <label class="form-check-label" for="Mixto">
                        Mixto
                    </label>
            </label>
        </div>
        <div class="row">
            <label class='col-md-6 col-12'>
                    <b>Tipo venta:</b>
                    <input class="form-check-input" type="radio" name="Tipo_Venta" Value="Bonificado" <?php if($curso['Tipo_Venta'] == "Bonificado"){ echo "checked"; } ?>>
                    <label class="form-check-label" for="Bonificado">
                        Bonificado
                    </label>
                    <input class="form-check-input" type="radio" name="Tipo_Venta" Value="Privado" <?php if($curso['Tipo_Venta'] == "Privado"){ echo "checked"; } ?>>
                    <label class="form-check-label" for="Privado">
                        Privado
                    </label>
                    </input>
            </label>
        </div>
        <div class="row pt-4">
                <label class='col-md-4 col-12'>
                        <b>Factura:</b>
                        <select class="form-control col-md-4 col-12" name="Factura">
                                <option <?php if($curso['Factura'] == "No Enviada"){ echo " selected ";} ?> value="No Enviada">No Enviada</option>
                                <option <?php if($curso['Factura'] == "Enviada"){ echo " selected ";} ?> value="Enviada">Enviada</option>
                                <option <?php if($curso['Factura'] == "Confirmado Empresa"){ echo " selected ";} ?> value="Confirmado Empresa">Confirmado Empresa</option>
                        </select>
                </label>
                <label class='col-md-4 col-12'>
                    <b>Fecha de envío de la factura:</b>
                    <input name="Fecha_De_Envio_De_la_Factura" class="form-control form-control-sm text-uppercase" type="date" value="<?php echo $curso['Fecha_De_Envio_De_la_Factura']; ?>"></input>
                </label>
                <label class='col-md-4 col-12'>
                    <b>Fecha de recibido de la factura:</b>
                    <input name="Fecha_De_Recibido_De_La_Factura" class="form-control form-control-sm text-uppercase" type="date" value="<?php echo $curso['Fecha_De_Recibido_De_La_Factura']; ?>"></input>
                </label>
        </div>
        <div class="row pt-4">
                <input type="hidden" value="<?php echo $curso['Diploma_Status']; ?>" name="Diploma_Status_Before">
                <input type="hidden" value="<?php echo $curso['Diploma_Status_Ultimo_Cambio']; ?>" name="Diploma_Status_Ultimo_Cambio">
                <label class='col-12'>
                        <b>Diploma Status:</b>
                        <select class="form-control" name="Diploma_Status">
                                
                                <option <?php if($curso['Diploma_Status'] == "No hecho"){ echo " selected ";} ?> value="No hecho">No hecho</option>
                                <option <?php if($curso['Diploma_Status'] == "Hecho"){ echo " selected ";} ?> value="Hecho">Hecho</option>
                                <option <?php if($curso['Diploma_Status'] == "Impreso"){ echo " selected ";} ?> value="Impreso">Impreso</option>
                                <option <?php if($curso['Diploma_Status'] == "Entregado"){ echo " selected ";} ?> value="Entregado">Entregado</option>
                                <option <?php if($curso['Diploma_Status'] == "Copia recibida"){ echo " selected ";} ?> value="Copia recibida">Copia recibida</option>
                                <option <?php if($curso['Diploma_Status'] == "Enviado por Mail"){ echo " selected ";} ?> value="Enviado por Mail">Enviado por Mail</option>
                        </select>
                        Última vez que este estado cambió: <?php echo formattedDate($curso['Diploma_Status_Ultimo_Cambio']); ?>
                </label>
        </div>
        <div class="row pt-4">
                <label class='col-md-4 col-12'>
                    <b>A.P:</b>
                    <input name="AP" class="form-control form-control-sm" type="text" value="<?php echo $curso['AP']; ?>"></input>
                </label>
                <label class='col-md-2 col-12'>
                    <b>Recibi Material:</b>
                    <input type="hidden" name="Recibi_Material" value="off">
                    <input type="checkbox" name="Recibi_Material" <?php if($curso['Recibi_Material'] == 1){echo "checked";} ?>>
                </label>
                <label class='col-md-1 col-12'>
                    <b>CC:</b>
                    <input type="hidden" name="CC" value="off">
                    <input type="checkbox" name="CC" <?php if($curso['CC'] == 1){echo "checked";} ?>>
                </label>
                <label class='col-md-4 col-12'>
                    <b>RLT:</b>
                    <input type="hidden" name="RLT" value="off">
                    <input type="checkbox" name="RLT" <?php if($curso['RLT'] == 1){echo "checked";} ?>>
                </label>
                
        </div>
        <div class="row pt-4">
                <label class='col-md-12 col-12'>
                        <b>Status Curso:</b>
                        <select class="form-control col-md-4 col-12" name="status_curso">
                                <option <?php if($curso['status_curso'] == "en curso"){ echo " selected ";} ?> value="en curso">en curso</option>
                                <option <?php if($curso['status_curso'] == "finalizado"){ echo " selected ";} ?> value="finalizado">finalizado</option>
                                <option <?php if($curso['status_curso'] == "descargado"){ echo " selected ";} ?> value="descargado">descargado</option>
                                <option <?php if($curso['status_curso'] == "cerrado"){ echo " selected ";} ?> value="cerrado">cerrado</option>
                                <option <?php if($curso['status_curso'] == "baja"){ echo " selected ";} ?> value="baja">baja</option>
                                <option <?php if($curso['status_curso'] == "problem"){ echo " selected ";} ?> value="problem">problem</option>
                        </select>
                </label>
        </div>
        <div class="row pt-4">
                <label class='col-md-12 col-12'>
                        <b>Firma:</b>
                        <select class="form-control col-md-4 col-12" name="diploma_sin_firma">
                                <option value="1" <?php echo 1==$curso['diploma_sin_firma']?'selected':'' ?>>Sin Firma</option>
                                <option value="0" <?php echo 0==$curso['diploma_sin_firma']?'selected':'' ?>>Con Firma</option>
                        </select>
                </label>
        </div>
        <div class="row pt-4">
                <label class='col-md-12 col-12'>
                        <b>Firma Docente:</b>
                </label>
                <?php if(!empty($curso['firma_docente'])): ?>
                        <div class="firmaDocente">
                                <a href="/firmas/<?php echo $curso['firma_docente'] ?>" target="_blank"><?php echo $curso['firma_docente'] ?></a>
                                <a href="javascript:borrarFirmaDocente();" class="text-danger">Borrar</a>
                        </div>
                <?php endif ?>
                <input type="hidden" name="firma_docente" value="<?php echo $curso['firma_docente'] ?>">
                <input type="file" name="firma_docente_file" class="form-control" accept=".jpg,.png,.svg,.gif,.jpeg"/>
        </div>
        <div class="row pt-4 d-none">
                <label class='col-md-12 col-12'>
                        <b>Contenido:</b>
                        <select class="form-control col-md-4 col-12" name="contenido_curso">
                                <option value="">---</option>
                                <?php foreach(buscarContenidos(date('Y')) as $contenido): ?>
                                        <option <?php echo $contenido['idcontenido']==$curso['contenido_id']?'selected':'' ?> value="<?php echo $contenido['idcontenido'] ?>"><?php echo $contenido['N_Accion'].' - '.$contenido['Anno'] ?></option>
                                <?php endforeach ?>
                        </select>
                </label>
        </div>
        <div class="row col-4 mx-auto mt-3">
                <input class="form-control" type="submit" value="Guardar"></input>
        </div>
    </form>
</div>

<script>
        function borrarFirmaDocente(){
                $('.firmaDocente').remove();
                $('input[name="firma_docente"]').val('')
        }
</script>