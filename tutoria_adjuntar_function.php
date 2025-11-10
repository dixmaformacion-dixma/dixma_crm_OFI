<?php

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['idAlumno']) and
        isset($_POST['Denominacion']) and
        isset($_POST['N_Accion']) and
        isset($_POST['N_Grupo']) and
        isset($_POST['Modalidad']) and
        isset($_POST['DOC_AF']) and
        isset($_POST['tutor']) and
        isset($_POST['idEmpresa']) and
        isset($_POST['Tipo_Venta'])
    ){
        $datosAlumnoCurso = [
            'idAlumno' => $_POST['idAlumno'],
            'Denominacion' => $_POST['Denominacion'],
            'N_Accion' => $_POST['N_Accion'],
            'N_Grupo' => $_POST['N_Grupo'],
            'N_Horas' => isset($_POST['N_Horas']) ? str_replace(',', '.', $_POST['N_Horas']) : NULL,
            'Modalidad' => $_POST['Modalidad'],
            'DOC_AF' => $_POST['DOC_AF'],
            'Fecha_Inicio' => NULL,
            'Fecha_Fin' => NULL,
            'tutor' => $_POST['tutor'],
            'idCurso' => NULL,
            'seguimento0' => NULL,
            'seguimento1' => NULL,
            'seguimento2' => NULL,
            'seguimento3' => NULL,
            'seguimento4' => NULL,
            'seguimento5' => NULL,
            'idEmpresa' => $_POST['idEmpresa'],
            'Tipo_Venta' => $_POST['Tipo_Venta']
        ];
        
        if(isset($_POST['Fecha_Inicio']) and $_POST['Fecha_Inicio'] != ""){
            $datosAlumnoCurso['Fecha_Inicio'] = $_POST['Fecha_Inicio'];
        }
        if(isset($_POST['Fecha_Fin']) and $_POST['Fecha_Fin'] != ""){
            $datosAlumnoCurso['Fecha_Fin'] = $_POST['Fecha_Fin'];
        }
        
        if(isset($_POST['seguimento0']) and $_POST['seguimento0'] != ""){
            $datosAlumnoCurso['seguimento0'] = $_POST['seguimento0'];
        }
        if(isset($_POST['seguimento1']) and $_POST['seguimento1'] != ""){
            $datosAlumnoCurso['seguimento1'] = $_POST['seguimento1'];
        }
        if(isset($_POST['seguimento2']) and $_POST['seguimento2'] != ""){
            $datosAlumnoCurso['seguimento2'] = $_POST['seguimento2'];
        }
        if(isset($_POST['seguimento3']) and $_POST['seguimento3'] != ""){
            $datosAlumnoCurso['seguimento3'] = $_POST['seguimento3'];
        }
        if(isset($_POST['seguimento4']) and $_POST['seguimento4'] != ""){
            $datosAlumnoCurso['seguimento4'] = $_POST['seguimento4'];
        }
        if(isset($_POST['seguimento5']) and $_POST['seguimento5'] != ""){
            $datosAlumnoCurso['seguimento5'] = $_POST['seguimento5'];
        }

        if(isset($_POST['selectFromCourseList']) && $_POST['selectFromCourseList'] == "on" && isset($_POST['idCurso'])){
            $datosAlumnoCurso['idCurso'] = $_POST['idCurso'];
        }


        if(alumnoCursoAdjuntar($datosAlumnoCurso)){
            echo "<div class='alert alert-success mb-0'> curso adjunto al estudiante con éxito </div>";

            //scroll to the student who we attached the course to
            echo '
                <script>
                window.addEventListener("load", (event) => {
                    document.getElementById("Alumno'.$_POST['idAlumno'].'").scrollIntoView(); 
                });
                </script>
                ';
        } else {
            echo "<div class='alert alert-danger mb-0'> ERROR: el curso no se pudo adjuntar al estudiante por alguna razón misteriosa </div>";
        }
    }else{
        echo "<div class='alert alert-danger mb-0'> ERROR: No se llenaron todos los campos necesarios </div>";
    }
}
?>