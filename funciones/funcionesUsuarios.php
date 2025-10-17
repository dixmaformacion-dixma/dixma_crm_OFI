<?php

    function cargarUsuarios(){

        $usuarios = [];

        $conexionPDO = realizarConexion();

        $sql = "SELECT * FROM usuarios WHERE activo = 1";
       
        $stmt = $conexionPDO->query($sql);

        while($usuario = $stmt->fetch()){

            array_push($usuarios, $usuario);

        }

        unset($conexionPDO);
        return $usuarios;

    }

    function eliminarUsuario($idUsuario){

        
        $conexionPDO = realizarConexion();

        $sql = "UPDATE usuarios SET activo = ? WHERE idusuario = ?";
       
        $stmt = $conexionPDO->prepare($sql);

        if($stmt){

            $stmt->bindValue(1, 0, PDO::PARAM_STR);
            $stmt->bindValue(2, $idUsuario, PDO::PARAM_STR);

            $stmt->execute();

        }

        unset($conexionPDO);

    }

    function cargarUsuario($idUsuario){

        $conexionPDO = realizarConexion();

        $sql = "SELECT * FROM usuarios WHERE idusuario = $idUsuario";
       
        $stmt = $conexionPDO->query($sql);

        if($usuario = $stmt->fetch()){

            unset($conexionPDO);
            return $usuario;

        }

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

    function nuevoUsuario($datosUsuario){

        $conexionPDO = realizarConexion();

        $sql = "INSERT INTO usuarios (nombre, contrasena, tipo, codigousuario) VALUES (?, ?, ?, ?)";
       
        $stmt = $conexionPDO->prepare($sql);

        if($stmt){

            $stmt->bindValue(1, $datosUsuario['nombre'], PDO::PARAM_STR);
            $stmt->bindValue(2, $datosUsuario['password'], PDO::PARAM_STR);
            $stmt->bindValue(3, $datosUsuario['tipo'], PDO::PARAM_STR);
            $stmt->bindValue(4, $datosUsuario['codigo'], PDO::PARAM_STR);

            $stmt->execute();

        }

        unset($conexionPDO);

    }


    ?>