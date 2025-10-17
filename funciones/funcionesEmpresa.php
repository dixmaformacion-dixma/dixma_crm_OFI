<?php
    function buscarPorAccionGrupoAno($N_Accion, $N_Grupo, $Ano) {
        $startDate = (new \DateTime($Ano.'-01-01'))->format('Y-m-d');
        $endDate = (new \DateTime(($Ano+1).'-01-01'))->format('Y-m-d');

        $conexionPDO = realizarConexion();
        $sql = 'SELECT empresas.idempresa, empresas.nombre FROM `alumnocursos` inner join empresas on alumnocursos.`idEmpresa` = empresas.idempresa WHERE
        `N_Accion` = ? AND `N_Grupo` = ? AND `Fecha_Fin` >= ? AND `Fecha_Fin` < ?';

        $stmt = $conexionPDO->prepare($sql);
            
        $stmt->bindValue(1, $N_Accion, PDO::PARAM_INT);
        $stmt->bindValue(2, $N_Grupo, PDO::PARAM_INT);
        $stmt->bindValue(3, $startDate, PDO::PARAM_STR);
        $stmt->bindValue(4, $endDate, PDO::PARAM_STR);

        $stmt->execute();

        if($alumnocurso = $stmt->fetchAll()){
            unset($conexionPDO);
            return $alumnocurso;
        } else {
            return false;
        }

    }
    function buscarEmpresas($valor){

            $empresas = [];

            $conexionPDO = realizarConexion();
            $sql = "SELECT * FROM empresas WHERE telef1 LIKE '%$valor%' OR telef2 LIKE '%$valor%' OR telef3 LIKE '%$valor%' OR nombre LIKE '%$valor%' OR idempresa = '$valor' OR email = '$valor' OR email2 = '$valor'";
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
        provincia = ?, poblacion = ?, sector = ?, pais = ?, telef1 = ?, telef2 = ?, telef3 = ?, 
        observacionesempresa = ?, creditoGuardado = ?, creditoCaducar = ?, referencia = ?, 
        codigo = ?, horario = ?
        WHERE idempresa = ?";
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
                $stmt->bindValue(22, $arrayDatos['referencia'], PDO::PARAM_STR);
                $stmt->bindValue(23, $arrayDatos['codigo'], PDO::PARAM_STR);
                $stmt->bindValue(24, $arrayDatos['horario'], PDO::PARAM_STR);
                $stmt->bindValue(25, $id, PDO::PARAM_INT);
                
       
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

                $sql = "INSERT INTO empresas 
                (nombre, cif, numeroempleados, calle, cp, provincia, poblacion, pais, telef1, telef2, email, personacontacto, cargo, observacionesempresa, sector, credito, email2, telef3, creditoAnhoAnterior, fecha, creditoGuardado, hora, nss, horacontactodesde, horacontactohasta, protecciondedatos, codigousuario, creditoCaducar) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
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
                $stmt->bindValue(12, $datosEmpresa['personaContacto'], PDO::PARAM_STR);
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
                $stmt->bindValue(27, $datosEmpresa['codigoUsuario'], PDO::PARAM_STR);
                $stmt->bindValue(28, $datosEmpresa['creditoCaducar'], PDO::PARAM_STR);
                //$stmt->bindValue(29, "", PDO::PARAM_STR);
                
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
            $sqlEmpresa = "SELECT idempresa, nombre, cp, poblacion, provincia, IF(empresas.idempresa IN (SELECT idempresa from ventas),'SI','NO') as cliente FROM empresas WHERE 1=1 ";

            $stmt = $conexionPDO->query($sqlLlamada);

            while($empresa = $stmt->fetch()){

                array_push($empresas, $empresa);

            }

            for($i=0; $i < count($empresas); $i++){

                $idempresa = $idempresa . $empresas[$i][0] . ", ";

            }

            $idempresa = rtrim($idempresa, ', ');

            if($poblacion == 'todas'){

                $sqlEmpresa = $sqlEmpresa . " AND provincia = '$provincia' ORDER BY idempresa ASC";

            } else {

                $sqlEmpresa = $sqlEmpresa . " AND poblacion = '$poblacion' AND provincia = '$provincia' ORDER BY idempresa ASC";

            }            
            //echo $sqlEmpresa;
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

    function getReferencias(){
        $conexionPDO = realizarConexion();
        $sql = "SELECT referencia FROM empresas GROUP BY referencia";
        $stmt = $conexionPDO->query($sql);
        $referencias = [];
        while($ref = $stmt->fetch()){
            $referencias[] = $ref['referencia'];
        }
        return $referencias;
    }

    function getAnnosEmpresaCliente($idempresa){
        $conexionPDO = realizarConexion();
        $sql = "SELECT YEAR(str_to_date(fecha,'%d-%m-%Y')) as anno FROM ventas WHERE ventas.idempresa = '{$idempresa}' GROUP BY YEAR(str_to_date(fecha,'%d-%m-%Y'))";
        
        $stmt = $conexionPDO->query($sql);
        $annos = [];
        while($ref = $stmt->fetch()){
            $annos[] = $ref['anno'];
        }
        return $annos;
    }

    

?>