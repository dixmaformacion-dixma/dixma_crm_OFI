<?php

    include "conexionBD.php";

    session_start();

    function iniciarSesion($usuario, $password){

        $conexionPDO = realizarConexion();       
        $sql = "SELECT * FROM usuarios WHERE nombre = '$usuario' AND contrasena = '$password'";
        $stmt = $conexionPDO->query($sql);

        if($usuario = $stmt->fetch()){

            $_SESSION['usuario'] = $usuario['nombre'];
            $_SESSION['rol'] = $usuario['tipo'];
            $_SESSION['codigoUsuario'] = $usuario['codigousuario'];

            return true;

        } else {

            return false;

        }

    }

?>