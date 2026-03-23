<div>
    <?php 
    $cursoComentarios = isset($cursoComentarioTarget) ? $cursoComentarioTarget : $curso;
    $idAlumnoComentario = isset($cursoComentarios['idAlumno']) ? $cursoComentarios['idAlumno'] : (isset($curso['idAlumno']) ? $curso['idAlumno'] : '');
    $cursoComentarioModalTargetId = isset($cursoComentarioModalTarget) ? $cursoComentarioModalTarget : '';
    if($commentarios = cargarCursoCommentario($cursoComentarios['StudentCursoID'])){
            ?>
                <div class="row p-1 mt-2">
                                <b style="font-size:0.95rem; color:#5f6b63; font-weight:600;">comentarios:</b>
                </div>
            
            <?php
            foreach($commentarios as $commentario){
            $commentarioCollapseId = 'editarCommentario' . $cursoComentarios['StudentCursoID'] . '_' . $commentario['idCommentario'];
            $commentarioContainerId = 'commentarioItem' . $cursoComentarios['StudentCursoID'] . '_' . $commentario['idCommentario'];
            $commentarioDataLabel = !empty($commentario['created_at'])
                    ? date("d/m/Y H:i", strtotime($commentario['created_at']))
                    : date("d/m/Y", strtotime($commentario['date']));
                    ?>
            <div class="row py-1 px-2 mx-0 my-1 align-items-center" style="background-color:#fcfdfb; border-bottom:1px solid #dde6d8;" id="<?php echo htmlspecialchars($commentarioContainerId); ?>">
                <div class="col d-flex align-items-center gap-2" style="min-width:0; padding-left:0;">
                    <span style="white-space:nowrap; color:#6f7c73; font-size:0.85rem;">
                        (<?php echo htmlspecialchars($commentarioDataLabel); ?>)
                        <?php echo htmlspecialchars($commentario['author']); ?>:
                    </span>
                    <span style="white-space:pre-wrap; word-break:break-word; flex:1; color:#2f3a33; font-size:0.93rem;"><?php echo htmlspecialchars($commentario['commentario']); ?></span>
                </div>
                <div class="col-auto ps-1 text-end" style="padding-right:0;">
                    <a
                        class="d-inline-flex align-items-center justify-content-center"
                        style="width:20px; height:20px; border:none; border-radius:3px; background-color:transparent; opacity:0.7;"
                        data-bs-toggle="collapse"
                        href="#<?php echo htmlspecialchars($commentarioCollapseId); ?>"
                        role="button"
                        aria-expanded="false"
                        aria-controls="<?php echo htmlspecialchars($commentarioCollapseId); ?>"
                        title="Editar comentario">
                        <img src="images/iconos2/pencil-square.svg" alt="Editar comentario" style="width:12px; height:12px;">
                    </a>
                </div>
                <div class="col-12 collapse mt-2" id="<?php echo htmlspecialchars($commentarioCollapseId); ?>">
                    <form method="POST" class="rounded p-2" style="background-color:#f8faf7; border:1px solid #dde6d8;">
                        <input type="hidden" name="function" value="editar_commentario">
                        <input type="hidden" name="StudentCursoID" value="<?php echo htmlspecialchars($cursoComentarios['StudentCursoID']); ?>">
                        <input type="hidden" name="idCommentario" value="<?php echo htmlspecialchars($commentario['idCommentario']); ?>">
                        <input type="hidden" name="idAlumno" value="<?php echo htmlspecialchars($idAlumnoComentario); ?>">
                        <input type="hidden" name="openCourseCollapseId" value="<?php echo htmlspecialchars('infoCurso' . $cursoComentarios['StudentCursoID']); ?>">
                        <input type="hidden" name="openCommentCollapseId" value="<?php echo htmlspecialchars($commentarioCollapseId); ?>">
                        <input type="hidden" name="openCommentContainerId" value="<?php echo htmlspecialchars($commentarioContainerId); ?>">
                        <input type="hidden" name="openModalId" value="<?php echo htmlspecialchars($cursoComentarioModalTargetId); ?>">
                        <div class="row">
                            <div class="col-12">
                                <textarea name="commentario" class="form-control" rows="4"><?php echo htmlspecialchars($commentario['commentario']); ?></textarea>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-auto ms-auto">
                                <input class="btn btn-primary btn-sm" type="submit" style="background-color:#1e989e" value="Guardar comentario">
                            </div>
                        </div>
                    </form>
                </div>
                    </div>
                    <?php
            }
    }
    ?>
</div>