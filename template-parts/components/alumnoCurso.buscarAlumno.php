<?php
    $header_style = "background-color: #1EAAAF;";
    $background_style = "background-color: #f0f5f6ff;";
    if (isset($curso['status_curso']) && $curso['status_curso'] === 'baja') {
        $header_style = "background-color: #c30d0d; color: white;";
        $background_style = "background-color: #ffe5e5;";
    } elseif (isset($curso['status_curso']) && (isset($curso['Tipo_Venta']) && $curso['Tipo_Venta'] === 'Privado')) {
        $header_style = "background-color: #F8A362;";
        $background_style = "background-color: #f4e2acff;";
    } elseif (empty($curso['Fecha_Inicio']) || $curso['Fecha_Inicio'] === '0000-00-00' || $curso['Fecha_Inicio'] === '1970-01-01') {
        $header_style = "background-color: #F7CA36;";
        $background_style = "background-color: #f7f7bbff;";
    } 
?>
<div class="card shadow-sm mb-3">
    <div class="card-header fw-bold" style="<?php echo $header_style; ?>">
        <img src="images/iconos/book.svg" class="me-2">
        <?php echo mb_strtoupper(htmlspecialchars($curso['Denominacion'])); ?>
    </div>
    <div class="card-body" style="<?php echo $background_style; ?>">
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
            <div class="col-md-4"><img src="images/iconos/clock.svg" class="me-2 text-muted"><b>Nº Horas:</b> <?php echo htmlspecialchars(str_replace('.', ',', $curso['N_Horas'])); ?></div>
            
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
            
            <!-- Pulsante per mostrare commenti -->
            <div class="col-12 text-end">
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#commentsModal<?php echo $curso['StudentCursoID']; ?>">
                    📝 Mostrar Comentarios
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal per Comentarios -->
<div class="modal fade" id="commentsModal<?php echo $curso['StudentCursoID']; ?>" tabindex="-1" aria-labelledby="commentsModalLabel<?php echo $curso['StudentCursoID']; ?>" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #b0d588;">
        <h5 class="modal-title" id="commentsModalLabel<?php echo $curso['StudentCursoID']; ?>">
            <b>COMENTARIOS - <?php echo htmlspecialchars($alumno['nombre'] . ' ' . $alumno['apellidos']); ?></b>
            <br>
            <small class="text-muted"><?php echo htmlspecialchars($curso['Denominacion']); ?></small>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php include("template-parts/components/commentSection.(seguimentosAndComments.(curso.listadoCursos)).php"); ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>