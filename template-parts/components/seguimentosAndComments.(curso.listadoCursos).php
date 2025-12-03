<div class="container col-10 mt-3">
        <div class="row">
                <div class="col-12 text-center mt-2 pt-2 pb-2 border border-5 rounded" style="background-color: #b0d588;">
                        <b>SEGUIMIENTOS: </b>
                </div>
        </div>
        <div>
                <div class="row">
                        <label class='col-auto'>
                                <b>1º TUTORÍA:</b>
                                <?php echo checkAndHighlightDate($curso['seguimento0'], $curso['seguimento0check']); ?>
                                <input type="checkbox" class="form-check-input" disabled <?php if ($curso['seguimento0check'] == 1) {
                                                                                                        echo "checked";
                                                                                                } ?>>
                        </label>

                </div>
                <div class="row">
                        <label class='col-auto'>
                                <b>SEGUIMIENTO 1:</b>
                                <?php echo checkAndHighlightDate($curso['seguimento1'], $curso['seguimento1check']); ?>
                                <input type="checkbox" class="form-check-input" disabled <?php if ($curso['seguimento1check'] == 1) {
                                                                                                        echo "checked";
                                                                                                } ?>>
                        </label>

                </div>
                <div class="row">
                        <label class='col-auto'>
                                <b>SEGUIMIENTO 2:</b>
                                <?php echo checkAndHighlightDate($curso['seguimento2'], $curso['seguimento2check']); ?>
                                <input type="checkbox" class="form-check-input" disabled <?php if ($curso['seguimento2check'] == 1) {
                                                                                                        echo "checked";
                                                                                                } ?>>
                        </label>

                </div>
                <div class="row">
                        <label class='col-auto'>
                                <b>SEGUIMIENTO 3:</b>
                                <?php echo checkAndHighlightDate($curso['seguimento3'], $curso['seguimento3check']); ?>
                                <input type="checkbox" class="form-check-input" disabled <?php if ($curso['seguimento3check'] == 1) {
                                                                                                        echo "checked";
                                                                                                } ?>>
                        </label>

                </div>
                <div class="row">
                        <label class='col-auto'>
                                <b>SEGUIMIENTO 4:</b>
                                <?php echo checkAndHighlightDate($curso['seguimento4'], $curso['seguimento4check']); ?>
                                <input type="checkbox" class="form-check-input" disabled <?php if ($curso['seguimento4check'] == 1) {
                                                                                                        echo "checked";
                                                                                                } ?>>
                        </label>
                </div>
                <div class="row">
                        <label class='col-auto'>
                                <b>SEGUIMIENTO 5:</b>
                                <?php echo checkAndHighlightDate($curso['seguimento5'], $curso['seguimento5check']); ?>
                                <input type="checkbox" class="form-check-input" disabled <?php if ($curso['seguimento5check'] == 1) {
                                                                                                        echo "checked";
                                                                                                } ?>>
                        </label>
                </div>
                <div>
                        <?php
                        include("template-parts/components/commentSection.(seguimentosAndComments.(curso.listadoCursos)).php");
                        ?>
                </div>


                <div class="row">
                        <div class='col-auto mx-auto'>
                                <?php
                                include("template-parts/components/seguimentos.(seguimentosAndComments.(curso.listadoCursos)).php");
                                ?>
                        </div>
                </div>
        </div>
</div>