<?php

if($_SERVER['REQUEST_METHOD'] === 'POST' and ($_POST['function'] == "insertar_commentario" or $_POST['function'] == "editar_seguimentos_Insertar_commentario")){
    if(isset($_POST['idAlumno']) and
        isset($_POST['commentario']) and
        isset($_POST['StudentCursoID'])
    ){
        if($_POST['commentario'] == ""){
            return 0;
        }
        
        $datosCommentario = [
            'idAlumno' => $_POST['idAlumno'],
            'commentario' => $_POST['commentario'],
            'StudentCursoID' => $_POST['StudentCursoID'],
            'author' => $_SESSION['usuario'],
            'date' => date("Y-m-d")
        ];


        if(insertarCursoCommentario($datosCommentario)){
            echo "<div class='alert alert-success mb-0'> comentario agregado exitosamente</div>";

            //scroll to the student who we attached the course to
            echo '
                <script>
                window.addEventListener("load", (event) => {
                    document.getElementById("Alumno'.$_POST['idAlumno'].'").scrollIntoView(); 
                });
                </script>
                ';
        } else {
            echo "<div class='alert alert-danger mb-0'> ERROR: el comentario no se pudo agregar por alguna razón </div>";
        }
    }else{
        echo "<div class='alert alert-danger mb-0'> ERROR: No se llenaron todos los campos necesarios </div>";
    }
}