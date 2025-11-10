<?php
if($_SERVER['REQUEST_METHOD'] === 'POST' and $_POST['function'] == "editar_AlumnoCurso"){
    if(isset($_POST['StudentCursoID']) and
        isset($_POST['Denominacion']) and
        isset($_POST['N_Accion']) and
        isset($_POST['N_Grupo']) and
        isset($_POST['N_Horas']) and
        isset($_POST['Modalidad']) and
        isset($_POST['DOC_AF']) and
        isset($_POST['Fecha_Inicio']) and
        isset($_POST['Fecha_Fin']) and
        isset($_POST['tutor']) and
        isset($_POST['idEmpresa']) and
        isset($_POST['Tipo_Venta']) and
        isset($_POST['Diploma_Status']) and
        isset($_POST['Diploma_Status_Before']) and
        isset($_POST['Diploma_Status_Ultimo_Cambio']) and
        isset($_POST['Factura']) and
        isset($_POST['AP']) and
        isset($_POST['CC']) and
        isset($_POST['RLT']) and
        isset($_POST['Recibi_Material']) and
        isset($_POST['status_curso'])
    ){
        $datosAlumnoCurso = [
            'StudentCursoID' => $_POST['StudentCursoID'],
            'Denominacion' => $_POST['Denominacion'],
            'N_Accion' => $_POST['N_Accion'],
            'N_Grupo' => $_POST['N_Grupo'],
            'N_Horas' => str_replace(',', '.', $_POST['N_Horas']),
            'Modalidad' => $_POST['Modalidad'],
            'DOC_AF' => $_POST['DOC_AF'],
            'Fecha_Inicio' => $_POST['Fecha_Inicio'],
            'Fecha_Fin' => $_POST['Fecha_Fin'],
            'tutor' => $_POST['tutor'],
            'idCurso' => NULL,
            'idEmpresa' => $_POST['idEmpresa'],
            'Tipo_Venta' => $_POST['Tipo_Venta'],
            'AP' => $_POST['AP'],
            'Diploma_Status' => $_POST['Diploma_Status'],
            'Diploma_Status_Ultimo_Cambio' => NULL,
            'CC' => 0,
            'RLT' => 0,
            'Factura' => $_POST['Factura'],
            'Fecha_De_Envio_De_la_Factura' => $_POST['Fecha_De_Envio_De_la_Factura'],
            'Fecha_De_Recibido_De_La_Factura' => $_POST['Fecha_De_Recibido_De_La_Factura'],
            'Recibi_Material' => 0,
            'status_curso' => $_POST['status_curso'],
            'contenido_id' => $_POST['contenido_curso'],
            'diploma_sin_firma'=>$_POST['diploma_sin_firma'],
            'firma_docente'=>$_POST['firma_docente']
        ];
        if(isset($_POST['RLT']) && $_POST['RLT'] == "on"){
            $datosAlumnoCurso['RLT'] = 1;
        }
        if(isset($_POST['Recibi_Material']) && $_POST['Recibi_Material'] == "on"){
            $datosAlumnoCurso['Recibi_Material'] = 1;
        }
        if(isset($_POST['CC']) && $_POST['CC'] == "on"){
            $datosAlumnoCurso['CC'] = 1;
        }
        if(isset($_POST['selectFromCourseList']) && $_POST['selectFromCourseList'] == "on"){
            $datosAlumnoCurso['idCurso'] = $_POST['idCurso'];
        }
        if(isset($_FILES['firma_docente_file']) && !empty($_FILES['firma_docente_file'])){
            $path = './firmas/';
            $name = substr(md5($_FILES['firma_docente_file']['name']),0,8).'_'.$_FILES['firma_docente_file']['name'];
            if(move_uploaded_file($_FILES['firma_docente_file']['tmp_name'],$path.$name)){
                $datosAlumnoCurso['firma_docente'] = $name;
            }
        }

        if($_POST['Diploma_Status'] != $_POST['Diploma_Status_Before']){
            $datosAlumnoCurso['Diploma_Status_Ultimo_Cambio'] = date("Y-m-d");
        }
        if(alumnoCursoEditar($datosAlumnoCurso)){
            echo "<div class='alert alert-success mb-0'> Curso editado con éxito </div>";
        } else {
            echo "<div class='alert alert-danger mb-0'> ERROR: El curso no se pudo editar por alguna razón </div>";
        }
    }else{
        echo "<div class='alert alert-danger mb-0'> ERROR: No se llenaron todos los campos necesarios  </div>";
    }
}
?>