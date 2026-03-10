<div class='container-fluid border rounded mt-3 mb-3 border-5' id='Alumno<?php echo $alumno["idAlumno"]; ?>'>

<div class='row mx-auto my-2'>
    <label class='col-md-12 col-12'>
            <b>nombre:</b>
            <?php echo $alumno['nombre']." ".$alumno['apellidos']; ?>
    </label>
    <label class='col-md-12 col-12'>
            <b>empresa:</b>
    <?php
                // show company name next to student on one line, truncated if long
                if(!empty($alumno['idEmpresa'])){
                    $empresa = buscarEmpresasPorID($alumno['idEmpresa']);
                    $empNombre = $empresa ? $empresa['nombre'] : '-';
                } else {
                    $empNombre = '-';
                }
            ?>
            <style>
                .empresa-link { color: #000; transition: color .15s ease; }
                .empresa-link:hover { color: #1e989e; text-decoration: none; }
            </style>
            <a class="empresa-link" title="<?php echo htmlspecialchars($empNombre); ?>" href="buscarVenta.php?valor=<?php echo urlencode($empNombre); ?>&consultar=Buscar" target="_blank" rel="noopener noreferrer" style="display:inline-block; max-width:40%; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; vertical-align:middle; margin-left:6px;"><?php echo htmlspecialchars($empNombre); ?></a>
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
            <?php echo $alumno['fechaNacimiento']; ?>
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
            <b>numeroSeguridadSocial:</b>
            <?php echo $alumno['numeroSeguridadSocial']; ?>
    </label>
    <label class='col-md-4 col-12'>
            <b>categoriaProfesional:</b>
            <?php echo $alumno['categoriaProfesional']; ?>
    </label>
    <label class='col-md-4 col-12'>
            <b>colectivo:</b>
            <?php echo $alumno['colectivo']; ?>
    </label>
</div>


<div class='row mx-auto'>
    <button type='button' 
    class='btn col-md-3 col-12 mx-auto mb-2'
    style='background-color: #8fd247;'
    onclick='crearPDFAlumno(<?php echo " " . $alumno["idEmpresa"] . ", " . $alumno["idAlumno"] ?>)'>
        Crear PDF (curso vacio)
        <img src='images/iconos/file-earmark-pdf.svg' class='ml-5'>
    </button>
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
                        class="btn btn-primary"
                        data-bs-toggle="collapse"
                        href="#cursolistado'.$alumno['idAlumno'].'">
                            mostrar
                    </a>
                    ';
                }
            ?>
        </h5>
    </div>
    <?php
    //display courses
    if($cursos){
        echo '<div class="collapse" id="cursolistado'.$alumno['idAlumno'].'">';
        foreach($cursos as $curso){
            require("template-parts/components/alumnoCurso.buscarAlumno.php");
        }
        echo '</div>';
    }
    
    ?>
</div>

</div>