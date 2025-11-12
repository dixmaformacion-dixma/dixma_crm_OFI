<div class='container-fluid border rounded mt-3 mb-3 border-5' id='Alumno<?php echo $alumno["idAlumno"]; ?>'>

<div class='row mx-auto my-2'>
    <label class='col-md-6 col-12'>
            <b>nombre:</b>
            <?php echo $alumno['nombre']." ".$alumno['apellidos']; ?>
    </label>
    <label class='col-md-6 col-12'>
            <b>NIF:</b>
            <?php echo $alumno['nif']; ?>
    </label>
    <label class='col-md-6 col-12'>
            <b>telefono:</b>
            <?php echo $alumno['telefono']; ?>
    </label>
    <label class='col-md-6 col-12'>
            <b>email:</b>
            <?php echo $alumno['email']; ?>
    </label>
</div>
<div class="row mx-auto">
<?php
if($cursos = fetchAttachedCourses($alumno["idAlumno"])){
        echo '<h5 class="text-center mt-2 pt-2 pb-2 border border-5 rounded" style="background-color: #b0d588;">Cursos (' . count($cursos) . ')</h5>';
        foreach($cursos as $curso){
                echo "<div class='container-fluid border rounded mt-2 mb-2 pt-2 pb-2' style='background-color: #f8f9fa;'>";
                echo "  <div class='row'>";
                echo "      <label class='col-md-12'><b>Nombre de Curso:</b> " . htmlspecialchars($curso['Denominacion']) . "</label>";
                echo "      <label class='col-md-6'><b>Fecha de Inicio:</b> " . formattedDate($curso['Fecha_Inicio']) . "</label>";
                echo "      <label class='col-md-6'><b>Fecha de Fin:</b> " . formattedDate($curso['Fecha_Fin']) . "</label>";
                echo "      <label class='col-md-4'><b>Horas:</b> " . htmlspecialchars($curso['N_Horas']) . "</label>";
                echo "      <label class='col-md-4'><b>Modalidad:</b> " . htmlspecialchars($curso['Modalidad']) . "</label>";
                echo "      <label class='col-md-4'><b>Status de Diploma:</b> " . htmlspecialchars($curso['Diploma_Status']) . "</label>";
                echo "  </div>";
                echo "</div>";
        }
}
?>
</div>

</div>