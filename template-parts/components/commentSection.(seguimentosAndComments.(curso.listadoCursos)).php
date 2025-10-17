<div>
    <?php 
    if($commentarios = cargarCursoCommentario($curso['StudentCursoID'])){
            ?>
                <div class="row p-1 mt-2">
                                <b>comentarios:</b>
                </div>
            
            <?php
            foreach($commentarios as $commentario){
                    ?>
                    <div class="row p-1 m-1">
                                (<?php echo date("d/m/Y",strtotime($commentario['date'])); ?>) 
                                <?php echo $commentario['author']; ?>:
                                <?php echo $commentario['commentario']; ?>
                    </div>
                    <?php
            }
    }
    ?>
</div>