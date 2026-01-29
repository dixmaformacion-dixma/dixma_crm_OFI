<div class="row">
        <a 
                class="btn col-auto btn-primary mx-auto "
                style="background-color:#1e989e"
                data-bs-toggle="collapse"
                href="#EditarFetcha<?php echo $curso['StudentCursoID']; ?>">
                Editar seguimientos
        </a>
</div>
        
<div id="EditarFetcha<?php echo $curso['StudentCursoID']; ?>" class="collapse">
        <form method="POST">
                <input type="hidden" name="function" value="editar_seguimentos_Insertar_commentario">
                <input type="hidden" name="StudentCursoID" value="<?php echo $curso['StudentCursoID']; ?>">
                <input type="hidden" name="idAlumno" value="<?php echo $curso['idAlumno']; ?>">
                <div class="row">
                        <label>
                                <div class="row">
                                        <div class="col">
                                                <b>1º TUTORÍA:</b>
                                        </div>
                                        <div class="col-5">
                                                <input name="seguimento0" value="<?php echo formattedDate($curso['seguimento0'], "Y-m-d"); ?>" class="seguimento0 form-control form-control-sm text-uppercase" type="date"></input>
                                        </div>
                                        <div class="col-2">
                                                <input type="checkbox" name="seguimento0check" class="form-check-input" <?php if($curso['seguimento0check'] == 1){ echo "checked"; } ?>>
                                        </div>
                                </div>
                                
                        </label>
                </div>
                <div class="row">
                        <label>
                                <div class="row">
                                        <div class="col">
                                                <b>SEGUIMIENTO 1:</b>
                                        </div>
                                        <div class="col-5">
                                                <input name="seguimento1" value="<?php echo formattedDate($curso['seguimento1'], "Y-m-d"); ?>" class="seguimento1 form-control form-control-sm text-uppercase" type="date"></input>
                                        </div>
                                        <div class="col-2">
                                                <input type="checkbox" name="seguimento1check" class="form-check-input" <?php if($curso['seguimento1check'] == 1){ echo "checked"; } ?>>
                                        </div>
                                </div>
                        </label>
                </div>
                <div class="row">
                        <label>
                                <div class="row">
                                        <div class="col">
                                                <b>SEGUIMIENTO 2:</b>
                                        </div>
                                        <div class="col-5">
                                                <input name="seguimento2" value="<?php echo formattedDate($curso['seguimento2'], "Y-m-d"); ?>" class="seguimento2 form-control form-control-sm text-uppercase" type="date"></input>
                                        </div>
                                        <div class="col-2">
                                                <input type="checkbox" name="seguimento2check" class="form-check-input" <?php if($curso['seguimento2check'] == 1){ echo "checked"; } ?>>
                                        </div>
                                </div>
                                
                        </label>                                       
                </div>
                <div class="row">
                        <label>
                                <div class="row">
                                        <div class="col">
                                                <b>SEGUIMIENTO 3:</b>
                                        </div>
                                        <div class="col-5">
                                                <input name="seguimento3" value="<?php echo formattedDate($curso['seguimento3'], "Y-m-d"); ?>" class="seguimento3 form-control form-control-sm text-uppercase" type="date"></input>
                                        </div>
                                        <div class="col-2">
                                                <input type="checkbox" name="seguimento3check" class="form-check-input" <?php if($curso['seguimento3check'] == 1){ echo "checked"; } ?>>
                                        </div>
                                </div>
                        </label>
                </div>
                <div class="row">
                        <label>
                                <div class="row">
                                        <div class="col">
                                                <b>SEGUIMIENTO 4:</b>
                                        </div>
                                        <div class="col-5">
                                                <input name="seguimento4" value="<?php echo formattedDate($curso['seguimento4'], "Y-m-d"); ?>" class="seguimento4 form-control form-control-sm text-uppercase" type="date"></input>
                                        </div>
                                        <div class="col-2">
                                                <input type="checkbox" name="seguimento4check" class="form-check-input" <?php if($curso['seguimento4check'] == 1){ echo "checked"; } ?>>
                                        </div>
                                </div>
                                
                        </label>
                </div>
                <div class="row">
                        <label>
                        <div class="row">
                                <div class="col">
                                        <b>SEGUIMIENTO 5:</b>
                                </div>
                                <div class="col-5">
                                        <input name="seguimento5" value="<?php echo formattedDate($curso['seguimento5'], "Y-m-d"); ?>" class="seguimento5 form-control form-control-sm text-uppercase" type="date"></input>
                                </div>
                                <div class="col-2">
                                        <input type="checkbox" name="seguimento5check" class="form-check-input" <?php if($curso['seguimento5check'] == 1){ echo "checked"; } ?>>
                                </div>
                        </div>
                        </label>
                        
                </div>
                <div class="row mx-auto">
                        <b>Insertar commentario:</b>
                        <textarea name="commentario" class="col-12" style="height:200px"></textarea>
                </div>
                
                <div class="row col-4 mx-auto mt-2 mb-2">
                        <input class="btn btn-primary" type="submit" style="background-color:#1e989e" value="Guardar"></input>
                </div>
        </form>
</div>