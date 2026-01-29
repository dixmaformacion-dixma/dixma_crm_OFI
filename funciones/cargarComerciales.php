<?php

    function cargarComerciales(){

            $comerciales = [];

            $conexionPDO = realizarConexion();
            $sql = "SELECT nombre, codigousuario, activo,idusuario FROM usuarios";
            $sql.= " WHERE activo = 1";
            //if($_SESSION['rol']=='callcenter'){
            //    $sql.= " AND tipo = 'comercial'";
            //}
            //if($_SESSION['rol']=='comercial'){
            //    $sql.= " AND tipo = 'callcenter'";
            //}
            $sql.= " AND tipo IN ('callcenter','comercial')";
            $stmt = $conexionPDO->query($sql);

            while($comercial = $stmt->fetch()){

                array_push($comerciales, $comercial);

            }

            unset($conexionPDO);

            return $comerciales;

    }

    function nombreComercial($codigoUsuario){

        $conexionPDO = realizarConexion();
        $sql = "SELECT nombre FROM usuarios WHERE codigousuario = $codigoUsuario ";
        $stmt = $conexionPDO->query($sql);

        while($comercial = $stmt->fetch()){

            return $comercial;

        }

        unset($conexionPDO);

    }

?>