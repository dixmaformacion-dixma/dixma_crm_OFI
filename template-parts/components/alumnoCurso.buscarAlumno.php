<div class="card shadow-sm mb-3">
    <div class="card-header fw-bold" style="background-color: #f8d7da;">
        <img src="images/iconos/book.svg" class="me-2">
        <?php echo htmlspecialchars($curso['Denominacion']); ?>
    </div>
    <div class="card-body" style="background-color: #fff5f6;">
        <div class="row g-3">
            <!-- Fechas -->
            <div class="col-md-6">
                <img src="images/iconos/calendar-plus.svg" class="me-2 text-muted">
                <b>Fecha Inicio:</b> <?php echo date("d/m/Y", strtotime($curso['Fecha_Inicio'])); ?>
            </div>
            <div class="col-md-6">
                <img src="images/iconos/calendar-check.svg" class="me-2 text-muted">
                <b>Fecha Fin:</b> <?php echo date("d/m/Y", strtotime($curso['Fecha_Fin'])); ?>
            </div>

            <!-- Detalles del Curso -->
            <div class="col-12"><hr class="my-2"></div>
            <div class="col-md-4"><img src="images/iconos/hash.svg" class="me-2 text-muted"><b>Nº Acción:</b> <?php echo htmlspecialchars($curso['N_Accion']); ?></div>
            <div class="col-md-4"><img src="images/iconos/people.svg" class="me-2 text-muted"><b>Nº Grupo:</b> <?php echo htmlspecialchars($curso['N_Grupo']); ?></div>
            <div class="col-md-4"><img src="images/iconos/clock.svg" class="me-2 text-muted"><b>Nº Horas:</b> <?php echo htmlspecialchars($curso['N_Horas']); ?></div>
            
            <div class="col-md-4"><img src="images/iconos/display.svg" class="me-2 text-muted"><b>Modalidad:</b> <?php echo htmlspecialchars($curso['Modalidad']); ?></div>
            <div class="col-md-4"><img src="images/iconos/tag.svg" class="me-2 text-muted"><b>Tipo Venta:</b> <?php echo htmlspecialchars($curso['Tipo_Venta']); ?></div>
            <div class="col-md-4"><img src="images/iconos/person-video3.svg" class="me-2 text-muted"><b>Tutor:</b> <?php echo htmlspecialchars($curso['tutor']); ?></div>
            <div class="col-md-4">
                <img src="images/iconos/award.svg" class="me-2 text-muted">
                <b>Diploma:</b> 
                <?php 
                    $status = htmlspecialchars($curso['Diploma_Status']);
                    $color_class = ($status == 'Copia recibida') ? 'bg-success' : 'bg-warning text-dark';
                    echo "<span class='badge $color_class'>$status</span>";
                ?>
            </div>

            <div class="col-md-4"><img src="images/iconos/file-earmark-text.svg" class="me-2 text-muted"><b>DOC A.F:</b> <?php echo htmlspecialchars($curso['DOC_AF']); ?></div>

            <!-- Empresa -->
            <div class="col-12"><hr class="my-2"></div>
            <div class="col-12">
                <img src="images/iconos/building.svg" class="me-2 text-muted">
                <b>Empresa (en el momento del curso):</b>
                <?php 
                    $empresaCurso = cargarEmpresa($curso['idEmpresa']);
                    echo htmlspecialchars($empresaCurso['nombre']); 
                ?>
                <span class="text-muted">(ID: <?php echo $curso['idEmpresa']; ?>)</span>
            </div>
        </div>

        <!-- Botón de Acción -->
        <div class="text-center mt-4">
            <a type='button' 
               class='btn btn-outline-danger col-md-4 col-12'
               href="administracion_fichaAlumno.php?idEmpresa=<?php echo $alumno["idEmpresa"]; ?>&idAlumno=<?php echo $alumno["idAlumno"]; ?>&StudentCursoID=<?php echo $curso["StudentCursoID"]; ?>">
                <img src='images/iconos/file-earmark-pdf.svg' class='me-2'>
                Crear PDF del Curso
            </a>
        </div>
    </div>
</div>