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
                <?php echo str_replace('.', ',', $curso['N_Horas']); ?>
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
</div>