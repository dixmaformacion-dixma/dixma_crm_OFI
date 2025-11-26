<?php
    if(!function_exists('nuevoContenido')){
        function nuevoContenido($datosContenido){

            $conexionPDO = realizarConexion();

            $sql = "INSERT INTO contenidos (N_Accion, Anno, Contenido) VALUES (?, ?, ?)";
        
            $stmt = $conexionPDO->prepare($sql);

            if($stmt){

                $stmt->bindValue(1, $datosContenido['N_Accion'], PDO::PARAM_INT);
                $stmt->bindValue(2, $datosContenido['Anno'], PDO::PARAM_INT);
                $stmt->bindValue(3, $datosContenido['Contenido'], PDO::PARAM_STR);
                
                $stmt->execute();

            }

            unset($conexionPDO);

        }
    }
    if(!function_exists('editarContenido')){
        function editarContenido($datosContenido, $idContenido){
        
            $conexionPDO = realizarConexion();
            $sql = "UPDATE contenidos SET N_Accion = ? , Anno = ? , Contenido = ? WHERE idcontenido = ?";
            $stmt = $conexionPDO->prepare($sql);
        
            if($stmt){
        
                $stmt->bindValue(1, $datosContenido['N_Accion'], PDO::PARAM_INT);
                $stmt->bindValue(2, $datosContenido['Anno'], PDO::PARAM_INT);
                $stmt->bindValue(3, $datosContenido['Contenido'], PDO::PARAM_STR);
                $stmt->bindValue(4, $idContenido, PDO::PARAM_INT);
                
                /*echo "<pre>";
                print_r($stmt);
                exit;*/
                
                
                
                if($stmt->execute()){
                    return true;
                }else{
                    print_r($stmt->errorInfo());
                    return false;
                }
                
                unset($conexionPDO);
        
            } else {
        
                return false;
        
            }
        }
    }
    
    function cargarContenidoAccion($N_Accion, $Anno){

 
        $conexionPDO = realizarConexion();

        $sql = "SELECT * FROM contenidos WHERE N_Accion = '".$N_Accion."' AND Anno = ".$Anno;
       
        $stmt = $conexionPDO->query($sql);

        $contenido = $stmt->fetch();

        unset($conexionPDO);
        return $contenido;

    }

    function buscarContenidos($valor){

        $usuarios = [];

        $conexionPDO = realizarConexion();

        $sql = "SELECT * FROM contenidos WHERE N_Accion = '".$valor."' OR Anno = '$valor'";
       
        $stmt = $conexionPDO->query($sql);

        while($usuario = $stmt->fetch()){

            array_push($usuarios, $usuario);

        }

        unset($conexionPDO);
        return $usuarios;

    }

    function eliminarContenido($idContenido){

        
        $conexionPDO = realizarConexion($idContenido);
        $sql = "DELETE FROM contenidos WHERE idcontenido = '$idContenido'";
       
        $stmt= $conexionPDO->prepare($sql);
        return $stmt->execute();

    }

    function cargarContenido($idContenido){

        $conexionPDO = realizarConexion();

        $sql = "SELECT * FROM contenidos WHERE idcontenido = $idContenido";
       
        $stmt = $conexionPDO->query($sql);

        if($contenido = $stmt->fetch()){
            unset($conexionPDO);
            return $contenido;
        }
        unset($conexionPDO);
    }

    function ultimoAdministrador(){

        $conexionPDO = realizarConexion();

        $sql = "SELECT codigousuario FROM usuarios WHERE tipo = 'admin' ORDER BY codigousuario DESC";
       
        $stmt = $conexionPDO->query($sql);

        if($codigo = $stmt->fetch()){

            unset($conexionPDO);
            return $codigo;

        }

    }

    function ultimoComercial(){

        $conexionPDO = realizarConexion();

        $sql = "SELECT codigousuario FROM usuarios WHERE tipo = 'comercial' ORDER BY codigousuario DESC";
       
        $stmt = $conexionPDO->query($sql);

        if($codigo = $stmt->fetch()){

            unset($conexionPDO);
            return $codigo;

        }

    }

    function ultimoCallCenter(){

        $conexionPDO = realizarConexion();

        $sql = "SELECT codigousuario FROM usuarios WHERE tipo = 'callcenter' ORDER BY codigousuario DESC";
       
        $stmt = $conexionPDO->query($sql);

        if($codigo = $stmt->fetch()){

            unset($conexionPDO);
            return $codigo;

        }

    }

    function sqlToHtml($qry,$columns = array(),$labels = array(),$callbacks = array()){
        if(count($qry)>0): ?>
            <table class="table table-bordered table-responsive">
                <thead class="thin-border-bottom">
                    <tr>  
                        <?php foreach($qry[0] as $n=>$q): if(empty($columns) || in_array($n,$columns)): ?>
                            <th class="woocommerce-orders-table__header" data-name="<?php echo $n ?>" style="cursor:pointer">
                                <span class="nobr">
                                    <?= empty($labels) || !array_key_exists($n,$labels)?ucfirst(str_replace('_',' ',$n)):$labels[$n] ?>
                                    <?php 
                                        if(!empty($_GET['order_by']) && $_GET['order_by']==$n):
                                    ?>
                                        <i class="fa fa-chevron-up"></i>
                                    <?php endif ?>
                                </span>
                            </th>
                        <?php endif; endforeach;   ?>
                    </tr>
                </thead>
    
                <tbody>
                    <?php $x = 0; foreach($qry as $nn=>$qq): ?>
                        <tr class="woocommerce-orders-table__row order">
                            <?php foreach($qq as $n=>$q): if(empty($columns) || in_array($n,$columns)): ?>
                                 <td class="woocommerce-orders-table__cell"><?php 
                                    $out = $q;
                                    if(!empty($callbacks && array_key_exists($n,$callbacks))){
                                        $out = call_user_func($callbacks[$n],$q,$qq,$x);
                                    }
                                    echo $out;
    
                                ?></td>
                            <?php endif;  endforeach; ?>
                        </tr>
                    <?php $x++; endforeach;   ?>
                </tbody>
            </table>
        <?php else: ?>
            Sin datos para mostrar
        <?php endif;
    }


?>