<?php 
if(!isset($categoriaProffesional)){
    $categoriaProffesional = [
        "directivo" => "Directivo",
        "mandoIntermedio" => "Mando intermedio",
        "tecnico" => "Técnico",
        "trabajadorCualificado" => "Trabajador cualificado",
        "trabajadorConBajaCualificacion" => "Trabajador con baja cualificación",
        "" => ""
    ];
    $colectivo = [
        "regimenGeneral" => "Régimen general",
        "fijoDiscontinuo" => "Fijo discontinuo",
        "otros" => "Otros",
        "" => ""
    ];
}

?>

<div class='container-fluid border rounded mb-3 border-5' id='Alumno<?php echo $alumno["idAlumno"]; ?>'>

<div class='row mx-auto my-2'>
    <label class='col-md-12 col-12'>
            <b>nombre:</b>
            <?php echo $alumno['nombre']." ".$alumno['apellidos']; ?>
    </label>
    <label class='col-md-4 col-12'>
            <b>telefono:</b>
            <?php echo $alumno['telefono']; ?>
    </label>
    <label class='col-md-4 col-12'>
            <b>email:</b>
            <?php echo $alumno['email']; ?>
    </label>
    <label class='col-md-4 col-12'>
            <b>fechaNacimiento:</b>
            <?php echo formattedDate($alumno['fechaNacimiento']); ?>
    </label>
    <label class='col-md-4 col-12'>
            <b>nif:</b>
            <?php echo $alumno['nif']; ?>
    </label>
    <label class='col-md-4 col-12'>
            <b>sexo:</b>
            <?php echo $alumno['sexo']; ?>
    </label>
    <label class='col-md-4 col-12'>
            <b>categoriaProfesional:</b>
            <?php echo $categoriaProffesional[$alumno['categoriaProfesional']]; ?>
    </label>
    <label class='col-md-4 col-12'>
            <b>colectivo:</b>
            <?php echo $colectivo[$alumno['colectivo']]; ?>
    </label>
</div>
<div class='row mx-auto cursos'>
    <?php
    //fetch courses
    $cursos = fetchAttachedCourses($alumno["idAlumno"]);
    ?>
    <div class="col-md-12 col-12">
        <h5 class="text-center mt-2 pt-2 pb-2 border border-5 rounded"
        style="background-color: #b0d588;">
            <img src='images/iconos/book.svg' class='ml-5'>
            Cursos (<?php if($cursos){echo count($cursos);}else{echo 0;} ?>)
            <?php 
                if($cursos){
                    echo '
                    <a 
                        class="btn btn-primary" style="background-color:#1e989e"
                        data-bs-toggle="collapse"
                        href="#cursolistado'.$alumno['idAlumno'].'">
                            mostrar
                    </a>
                    ';
                }
            ?>
            <a 
                class="btn btn-primary" style="background-color:#1e989e"
                data-bs-toggle="collapse"
                href="#AttachTo<?php echo $alumno['idAlumno']; ?>">
                    Insertar curso
            </a>
        </h5>
    </div>
    <?php require("template-parts/components/alumnoCursoInsertar.(alumno.insertarCurso).php"); ?>
    <?php
    //display courses
    if($cursos){
        echo '<div class="collapse" id="cursolistado'.$alumno['idAlumno'].'">';
        foreach($cursos as $curso){
            require("template-parts/components/alumnoCurso.insertarCurso.php");
        }
        echo '</div>';
    }
    
    ?>
</div>

</div>