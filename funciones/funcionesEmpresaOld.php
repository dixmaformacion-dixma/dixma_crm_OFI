<?php

    function buscarEmpresas($valor){

            $empresas = [];

            $conexionPDO = realizarConexion();
            $sql = "SELECT * FROM empresas WHERE telef1 LIKE '%$valor%' OR telef2 LIKE '%$valor%' OR nombre LIKE '%$valor%' OR idempresa = '$valor' OR email = '$valor'";
            $stmt = $conexionPDO->query($sql);

            while($empresa = $stmt->fetch()){

                array_push($empresas, $empresa);

            }

            unset($conexionPDO);
            return $empresas;

    }

    function cargarEmpresa($valor){

        $empresas = [];

        $conexionPDO = realizarConexion();
        $sql = "SELECT * FROM empresas WHERE idempresa = '$valor'";
        $stmt = $conexionPDO->query($sql);

        if($empresa = $stmt->fetch()){

            return $empresa;

        } else {

            return false;

        }

        unset($conexionPDO);

}

    function actualizarEmpresa($arrayDatos, $id) {

        $conexionPDO = realizarConexion();
        $sql = "UPDATE empresas SET nombre = ?, cif = ?, personacontacto = ?, cargo = ?, email = ?, 
        email2 = ?, credito = ?, creditoAnhoAnterior = ?, numeroempleados = ?, calle = ?, cp = ?, 
        provincia = ?, poblacion = ?, sector = ?, pais = ?, telef1 = ?, telef2 = ?, telef3 = ?, observacionesempresa = ?, creditoGuardado = ?, creditoCaducar = ? WHERE idempresa = ?";
        $stmt = $conexionPDO->prepare($sql);

            if($stmt){
    
                $stmt->bindValue(1, $arrayDatos['nombreEmpresa'], PDO::PARAM_STR);
                $stmt->bindValue(2, $arrayDatos['CIF'], PDO::PARAM_STR);
                $stmt->bindValue(3, $arrayDatos['personaContacto'], PDO::PARAM_STR);
                $stmt->bindValue(4, $arrayDatos['cargoPersonaContacto'], PDO::PARAM_STR);
                $stmt->bindValue(5, $arrayDatos['email'], PDO::PARAM_STR);
                $stmt->bindValue(6, $arrayDatos['email2'], PDO::PARAM_STR);
                $stmt->bindValue(7, $arrayDatos['creditoVigente'], PDO::PARAM_STR);
                $stmt->bindValue(8, $arrayDatos['creditoAnhoAnterior'], PDO::PARAM_STR);
                $stmt->bindValue(9, $arrayDatos['nEmpleados'], PDO::PARAM_STR);
                $stmt->bindValue(10, $arrayDatos['calle'], PDO::PARAM_STR);
                $stmt->bindValue(11, $arrayDatos['codigoPostal'], PDO::PARAM_STR);
                $stmt->bindValue(12, $arrayDatos['provincia'], PDO::PARAM_STR);
                $stmt->bindValue(13, $arrayDatos['poblacion'], PDO::PARAM_STR);
                $stmt->bindValue(14, $arrayDatos['sector'], PDO::PARAM_STR);
                $stmt->bindValue(15, $arrayDatos['pais'], PDO::PARAM_STR);
                $stmt->bindValue(16, $arrayDatos['telefono'], PDO::PARAM_STR);
                $stmt->bindValue(17, $arrayDatos['telefono2'], PDO::PARAM_STR);
                $stmt->bindValue(18, $arrayDatos['telefono3'], PDO::PARAM_STR);
                $stmt->bindValue(19, $arrayDatos['observacionesEmpresa'], PDO::PARAM_STR);
                $stmt->bindValue(20, $arrayDatos['creditoGuardado'], PDO::PARAM_STR);
                $stmt->bindValue(21, $arrayDatos['creditoCaducar'], PDO::PARAM_STR);
                $stmt->bindValue(22, $id, PDO::PARAM_INT);

                
       
                $stmt->execute();
                return true;
    
            } else {
    
                return false;
    
            }
    
            unset($conexionPDO);        

    }

    function insertarNuevaEmpresa($datosEmpresa) {

            $conexionPDO = realizarConexion();


            $sql = "SELECT * FROM empresas WHERE nombre = '" . $datosEmpresa['nombreEmpresa'] . "'";
            $stmt = $conexionPDO->query($sql);

        if($stmt->fetch()){

            return false;

        } else {

                $sql = "INSERT INTO empresas (nombre, cif, numeroempleados, calle, cp, provincia, poblacion, pais, telef1, telef2, email, personacontacto, cargo, observacionesempresa, sector, credito, email2, telef3, creditoAnhoAnterior, fecha, creditoGuardado, hora, nss, horacontactodesde, horacontactohasta, protecciondedatos, codigousuario, creditoCaducar, codigousuario) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conexionPDO->prepare($sql);
           
            if($stmt){
    
                $stmt->bindValue(1, $datosEmpresa['nombreEmpresa'], PDO::PARAM_STR);
                $stmt->bindValue(2, $datosEmpresa['CIF'], PDO::PARAM_STR);
                $stmt->bindValue(3, $datosEmpresa['nEmpleados'], PDO::PARAM_STR);
                $stmt->bindValue(4, $datosEmpresa['calle'], PDO::PARAM_STR);
                $stmt->bindValue(5, $datosEmpresa['codigoPostal'], PDO::PARAM_STR);
                $stmt->bindValue(6, $datosEmpresa['provincia'], PDO::PARAM_STR);
                $stmt->bindValue(7, $datosEmpresa['poblacion'], PDO::PARAM_STR);
                $stmt->bindValue(8, $datosEmpresa['pais'], PDO::PARAM_STR);
                $stmt->bindValue(9, $datosEmpresa['telefono'], PDO::PARAM_STR);
                $stmt->bindValue(10, $datosEmpresa['telefono2'], PDO::PARAM_STR);
                $stmt->bindValue(11, $datosEmpresa['email'], PDO::PARAM_STR);
                $stmt->bindValue(12, $datosEmpresa['personaContacto'], PDO::PARAM_INT);
                $stmt->bindValue(13, $datosEmpresa['cargoPersonaContacto'], PDO::PARAM_STR);
                $stmt->bindValue(14, $datosEmpresa['observacionesEmpresa'], PDO::PARAM_STR);
                $stmt->bindValue(15, $datosEmpresa['sector'], PDO::PARAM_STR);
                $stmt->bindValue(16, $datosEmpresa['creditoVigente'], PDO::PARAM_STR);
                $stmt->bindValue(17, $datosEmpresa['email2'], PDO::PARAM_STR);
                $stmt->bindValue(18, $datosEmpresa['telefono3'], PDO::PARAM_STR);
                $stmt->bindValue(19, $datosEmpresa['creditoAnhoAnterior'], PDO::PARAM_STR);
                $stmt->bindValue(20, $datosEmpresa['fecha'], PDO::PARAM_STR);
                $stmt->bindValue(21, $datosEmpresa['creditoGuardado'], PDO::PARAM_STR);

                //hora, nss, horacontactodesde, horacontactohasta, protecciondedatos, codigousuario
                $stmt->bindValue(22, "", PDO::PARAM_STR);
                $stmt->bindValue(23, "", PDO::PARAM_STR);
                $stmt->bindValue(24, "", PDO::PARAM_STR);
                $stmt->bindValue(25, "", PDO::PARAM_STR);
                $stmt->bindValue(26, "", PDO::PARAM_STR);
                $stmt->bindValue(27, "", PDO::PARAM_STR);
                $stmt->bindValue(28, $datosEmpresa['creditoCaducar'], PDO::PARAM_STR);
                $stmt->bindValue(29, $datosEmpresa['codigoUsuario'], PDO::PARAM_STR);
    
                $stmt->execute();
                return true;

            }

        }

            unset($conexionPDO); 

        }

        function cogerIDNuevaEmpresa(){

            $conexionPDO = realizarConexion();
            $sql = "SELECT idempresa FROM empresas ORDER BY idempresa DESC LIMIT 1";
            $stmt = $conexionPDO->query($sql);

            if($id = $stmt->fetch()){

                return $id[0];

            }

            unset($conexionPDO);

        }

        function buscarPorSectores($sector) {

            $empresas = [];

            $conexionPDO = realizarConexion();
            $sql = "SELECT * FROM empresas WHERE sector = '$sector'";
            $stmt = $conexionPDO->query($sql);

            while($empresa = $stmt->fetch()){

                array_push($empresas, $empresa);

            }

            unset($conexionPDO);

            return $empresas;

        }

        //Busca por provincia y las empresas que no recibieron llamada en el año actual
        function buscarPorProvincia($datosProvincia) {

            $empresas = [];
            $idempresas = [];           
            $datos = [];
            $idempresa = "";

            $provincia = $datosProvincia['provincia'];
            $poblacion = $datosProvincia['poblacion'];
            $añoActual = date('Y');

            $conexionPDO = realizarConexion();
            $sqlLlamada = "SELECT DISTINCT idempresa FROM llamadas WHERE fecha LIKE '%$añoActual'";
            $sqlEmpresa = "SELECT idempresa, nombre, cp, poblacion, provincia FROM empresas WHERE idempresa NOT IN (";

            $stmt = $conexionPDO->query($sqlLlamada);

            while($empresa = $stmt->fetch()){

                array_push($empresas, $empresa);

            }

            for($i=0; $i < count($empresas); $i++){

                $idempresa = $idempresa . $empresas[$i][0] . ", ";

            }

            $idempresa = rtrim($idempresa, ', ');

            if($poblacion == 'todas'){

                $sqlEmpresa = $sqlEmpresa . $idempresa . ") AND provincia = '$provincia' ORDER BY idempresa ASC";

            } else {

                $sqlEmpresa = $sqlEmpresa . $idempresa . ") AND poblacion = '$poblacion' AND provincia = '$provincia' ORDER BY idempresa ASC";

            }            

            $stmt = $conexionPDO->query($sqlEmpresa);

            while($dato = $stmt->fetch()){

                array_push($datos, $dato);

            }

            unset($conexionPDO);

            return $datos;

        }

        function buscarEmpresasPorID($valor){

            $conexionPDO = realizarConexion();
            $sql = "SELECT * FROM empresas WHERE idempresa = '$valor'";
            $stmt = $conexionPDO->query($sql);

            if($empresa = $stmt->fetch()){

                unset($conexionPDO);
                return $empresa;

            }

    }

    function eliminarEmpresa($idempresa){

        $conexionPDO = realizarConexion();
        $sql = "DELETE FROM empresas WHERE idempresa = '$idempresa'";
        $stmt = $conexionPDO->query($sql);

        if($stmt->fetch()){

            $stmt->execute();

        }

        unset($conexionPDO);

    }

?>