<div class="container border rounded mt-2 mb-3 border-5 ">
        <label class='col-md-4 col-12'>
                <b>Denominacion:</b>
                <?php echo $curso['Denominacion']; ?>
        </label>
        <label class='col-md-4 col-12'>
                <b>N Accion:</b>
                <?php echo $curso['N_Accion']; ?>
        </label>
        <label class='col-md-4 col-12'>
                <b>N_Grupo:</b>
                <?php echo $curso['N_Grupo']; ?>
        </label>
        <label class='col-md-4 col-12'>
                <b>N_Horas:</b>
                <?php echo $curso['N_Horas']; ?>
        </label>
        <label class='col-md-4 col-12'>
                <b>Modalidad:</b>
                <?php echo $curso['Modalidad']; ?>
        </label>
        <label class='col-md-4 col-12'>
                <b>Tipo Venta:</b>
                <?php echo $curso['Tipo_Venta']; ?>
        </label>
        <label class='col-md-4 col-12'>
                <b>DOC_AF:</b>
                <?php echo $curso['DOC_AF']; ?>
        </label>
        <label class='col-md-4 col-12'>
                <b>Tutor:</b>
                <?php echo $curso['tutor']; ?>
        </label>
        <label class='col-md-12 col-12'>
                <b>idEmpresa (en el momento del entrenamiento):</b>
                <?php echo cargarEmpresa($curso['idEmpresa'])['nombre']; ?>
                (ID: <?php echo $curso['idEmpresa']; ?>)
        </label>
        <label class='col-md-4 col-12'>
                <b>Fecha_Inicio:</b>
                <?php echo date("d/m/Y",strtotime($curso['Fecha_Inicio'])); ?>
        </label>
        <label class='col-md-4 col-12'>
                <b>Fecha_Fin:</b>
                <?php echo date("d/m/Y",strtotime($curso['Fecha_Fin'])); ?>
        </label>
        <label class='col-md-4 col-12'>
                <b>1º TUTORÍA:</b>
                <?php echo date("d/m/Y",strtotime($curso['seguimento0'])); ?>
        </label>
        <label class='col-md-4 col-12'>
                <b>SEGUIMIENTO 1:</b>
                <?php echo date("d/m/Y",strtotime($curso['seguimento1'])); ?>
        </label>
        <label class='col-md-4 col-12'>
                <b>SEGUIMIENTO 2:</b>
                <?php echo date("d/m/Y",strtotime($curso['seguimento2'])); ?>
        </label>
        <label class='col-md-4 col-12'>
                <b>SEGUIMIENTO 3:</b>
                <?php echo date("d/m/Y",strtotime($curso['seguimento3'])); ?>
        </label>
        <label class='col-md-4 col-12'>
                <b>SEGUIMIENTO 4:</b>
                <?php echo date("d/m/Y",strtotime($curso['seguimento4'])); ?>
        </label>
        <label class='col-md-4 col-12'>
                <b>SEGUIMIENTO 5:</b>
                <?php echo date("d/m/Y",strtotime($curso['seguimento5'])); ?>
        </label>
        <a type='button' 
        class='btn col-md-2 col-12 mx-auto mb-2'
        style='background-color: #8fd247;'
        href="administracion_fichaAlumno.php?idEmpresa=<?php echo $alumno["idEmpresa"]; ?>&idAlumno=<?php echo $alumno["idAlumno"]; ?>&StudentCursoID=<?php echo $curso["StudentCursoID"]; ?>">
                Crear PDF
                <img src='images/iconos/file-earmark-pdf.svg' class='ml-5'>
        </a>
</div>