<div class='container-fluid border rounded mt-3 mb-3 border-5' id='Alumno<?php echo $alumno["idAlumno"]; ?>'>

<div class='row mx-auto my-2'>
    <label class='col-md-4 col-12'>
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
            <b>NIF:</b>
            <?php echo $alumno['nif']; ?>
    </label>
</div>
<div class="row mx-auto">
<?php
if($cursos = fetchAttachedCourses($alumno["idAlumno"])){
        echo '<div> <b>Cursos: </b>';
        foreach($cursos as $curso){
                echo "<div>" . $curso['Denominacion'] . " (" . formattedDate($curso['Fecha_Fin']) . ")</div>";
        }
        echo '</div>';
}
?>
</div>

</div>