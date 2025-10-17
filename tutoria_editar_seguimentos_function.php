<?php

if($_SERVER['REQUEST_METHOD'] === 'POST' and ($_POST['function'] == "editar_seguimentos" or $_POST['function'] == "editar_seguimentos_Insertar_commentario")){
    if(isset($_POST['seguimento0']) and
        isset($_POST['seguimento1']) and
        isset($_POST['seguimento2']) and
        isset($_POST['seguimento3']) and
        isset($_POST['seguimento4']) and
        isset($_POST['seguimento5']) and
        isset($_POST['StudentCursoID'])
    ){
        $datosAlumnoCurso = [
            'seguimento0' => $_POST['seguimento0'],
            'seguimento1' => $_POST['seguimento1'],
            'seguimento2' => $_POST['seguimento2'],
            'seguimento3' => $_POST['seguimento3'],
            'seguimento4' => $_POST['seguimento4'],
            'seguimento5' => $_POST['seguimento5'],
            'StudentCursoID' => $_POST['StudentCursoID'],
            'seguimento0check' => 0,
            'seguimento1check' => 0,
            'seguimento2check' => 0,
            'seguimento3check' => 0,
            'seguimento4check' => 0,
            'seguimento5check' => 0
        ];

        if(isset($_POST['seguimento0check'])){
            $datosAlumnoCurso['seguimento0check'] = ($_POST['seguimento0check'] == "on");
        }
        if(isset($_POST['seguimento1check'])){
            $datosAlumnoCurso['seguimento1check'] = ($_POST['seguimento1check'] == "on");
        }
        if(isset($_POST['seguimento2check'])){
            $datosAlumnoCurso['seguimento2check'] = ($_POST['seguimento2check'] == "on");
        }
        if(isset($_POST['seguimento3check'])){
            $datosAlumnoCurso['seguimento3check'] = ($_POST['seguimento3check'] == "on");
        }
        if(isset($_POST['seguimento4check'])){
            $datosAlumnoCurso['seguimento4check'] = ($_POST['seguimento4check'] == "on");
        }
        if(isset($_POST['seguimento5check'])){
            $datosAlumnoCurso['seguimento5check'] = ($_POST['seguimento5check'] == "on");
        }


        if(editarFetchaSeguimentos($datosAlumnoCurso)){
            echo "<div class='alert alert-success mb-0'>las fechas del segmento se cambiaron con éxito</div>";
        } else {
            echo "<div class='alert alert-danger mb-0'> ERROR: las fechas de los segmentos no se pudieron cambiar </div>";
        }
    }else{
        echo "<div class='alert alert-danger mb-0'> ERROR: No se llenaron todos los campos necesarios </div>";
    }
}
?>