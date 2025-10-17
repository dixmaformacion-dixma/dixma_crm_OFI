<?php

    function insertarNuevaCita($datosCita, $id) {

        $conexionPDO = realizarConexion();

        $sql = "INSERT INTO citas (idempresa, diacita, fechacita, horacita, idllamada, codigousuario, comercial) VALUES (?, ?, ?, ?, ?, ?, ?)";
       
        $stmt = $conexionPDO->prepare($sql);
        
        $comercial = "";

        if($_SESSION['codigoUsuario'][0] == "1"){

            $comercial = $_SESSION['codigoUsuario'];

        }

        if($stmt){

            $stmt->bindValue(1, $id, PDO::PARAM_INT);
            $stmt->bindValue(2, $datosCita['diaCita'], PDO::PARAM_STR);
            $stmt->bindValue(3, $datosCita['fechaCita'], PDO::PARAM_STR);
            $stmt->bindValue(4, $datosCita['horaCita'], PDO::PARAM_STR);
            $stmt->bindValue(5, $datosCita['idllamada'], PDO::PARAM_INT);
            $stmt->bindValue(6, $datosCita['operador'], PDO::PARAM_STR);
            $stmt->bindValue(7, $comercial, PDO::PARAM_STR);

            $stmt->execute();
            return true;

        } else {

            return false;

        }

        unset($conexionPDO);

    }

    function citas($fecha){

        $citas = [];
        $empresas = [];

        $citasEmpresas = [];

        $conexionPDO = realizarConexion();

        if($_SESSION['codigoUsuario'][0] == "0" || $_SESSION['codigoUsuario'][0] == "2"){

        $sql = "SELECT * FROM citas WHERE fechacita = '$fecha'";

        } else {

            $codigousuario = $_SESSION['codigoUsuario'];
            $sql = "SELECT * FROM citas WHERE fechacita = '$fecha' AND (codigousuario = '$codigousuario' OR comercial = '$codigousuario')";

        }
       
        $stmt = $conexionPDO->query($sql);

        while($cita = $stmt->fetch()){

            array_push($citas, $cita);

        }

        if(!empty($citas)){

            for($i=0; $i < count($citas); $i++){

                    $id = $citas[$i]['idempresa'];

                    $sql = "SELECT nombre, poblacion, calle, telef1, personacontacto FROM empresas WHERE idempresa = $id";

                    $stmt = $conexionPDO->query($sql);

                    if($empresa = $stmt->fetch()){

                        array_push($empresas, $empresa);

                    }

                }

        }

        if(!empty($empresas)){

            $mode = current($citas);
            $mode2 = current($empresas);
            
            $mezla = array_merge($mode, $mode2);
            array_push($citasEmpresas, $mezla);

            while($mode = next($citas)){

                $mode2 = next($empresas);

                if($mode2 != false){

                    $mezla = array_merge($mode, $mode2);
                    array_push($citasEmpresas, $mezla);

                }

            }

        }

        usort($citasEmpresas, "arrayCitas");

        unset($conexionPDO);
        return $citasEmpresas;
            
    }

    function listadoCitas($comercial){

        $listadoCitas = [];
        $citas = [];
        $empresas = [];

        $conexionPDO = realizarConexion();

        $sql = "SELECT * FROM citas WHERE comercial = $comercial";
       
        $stmt = $conexionPDO->query($sql);

        while($cita = $stmt->fetch()){

            array_push($citas, $cita);

        }

        if(!empty($citas)){

            for($i=0; $i < count($citas); $i++){

                    $id = $citas[$i]['idempresa'];

                    $sql = "SELECT nombre, poblacion, calle, telef1, personacontacto FROM empresas WHERE idempresa = $id";

                    $stmt = $conexionPDO->query($sql);

                    if($empresa = $stmt->fetch()){

                        array_push($empresas, $empresa);

                    } else {

                        array_push($empresas, "");

                    }

                }

        }

        if(!empty($empresas)){

            $mode = current($citas);
            $mode2 = current($empresas);
            
            $mezla = array_merge($mode, $mode2);
            array_push($listadoCitas, $mezla);

            while($mode = next($citas)){

                $mode2 = next($empresas);

                if($mode2 != false){

                    $mezla = array_merge($mode, $mode2);
                    array_push($listadoCitas, $mezla);

                }

            }

        }

        unset($conexionPDO);
        return $listadoCitas;
            
    }


    function asignarCita($idcita, $comercial){

        $conexionPDO = realizarConexion();

        $sql = "UPDATE citas SET comercial = $comercial WHERE idcita = $idcita";
       
        $stmt = $conexionPDO->prepare($sql);

        if($stmt){

            $stmt->execute();

        }


    }

    function listadoCitas2($comercial) {

        $listadoCitas = [];

        $conexionPDO = realizarConexion();

        $sql = "SELECT * FROM citas WHERE comercial = '$comercial'";
       
        $stmt = $conexionPDO->query($sql);

        if($row = $stmt->fetch()) {

            array_push($row, $listadoCitas);

        }

        return $listadoCitas;


    }

    function arrayCitas($a, $b){

        if($a['horacita'] > $b['horacita']){

            return $a;

        }

    }

?>
