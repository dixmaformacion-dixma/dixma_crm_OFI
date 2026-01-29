<?php
    session_start();
    function insertarNuevaLlamada($datosLlamada, $id){
            $conexionPDO = realizarConexion();
            $sql = "INSERT INTO llamadas (idempresa,
                interlocutor,
                observacionesinterlocutor,
                fecha,
                hora,
                fechacita,
                horacita,
                estadollamada,
                fechapendiente,
                horapendiente,
                otrosnul,
                observacionesOtros,
                recibidopor,
                piloto,
                curso,
                nombrecurso,
                diacita,
                anoPedirCita,
                mesPedirCita,
                codigo_llamada,
                fecha_seguimiento,
                tipo_seguimiento,
                usuario_seguimiento,
                prioridad,
                usuario_asignador
            )
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $conexionPDO->prepare($sql);

            if($stmt){

                $stmt->bindValue(1, $id, PDO::PARAM_INT);
                $stmt->bindValue(2, $datosLlamada['interlocutor'], PDO::PARAM_STR);
                $stmt->bindValue(3, $datosLlamada['observacionesInterlocutor'], PDO::PARAM_STR);
                $stmt->bindValue(4, $datosLlamada['fechaActual'], PDO::PARAM_STR);
                $stmt->bindValue(5, $datosLlamada['horaActual'], PDO::PARAM_STR);
                $stmt->bindValue(6, $datosLlamada['fechaCita'], PDO::PARAM_STR);
                $stmt->bindValue(7, $datosLlamada['horaCita'], PDO::PARAM_STR);
                $stmt->bindValue(8, $datosLlamada['estadoLlamada'], PDO::PARAM_STR);
                $stmt->bindValue(9, $datosLlamada['fechaPendiente'], PDO::PARAM_STR);
                $stmt->bindValue(10, $datosLlamada['horaPendiente'], PDO::PARAM_STR);
                $stmt->bindValue(11, $datosLlamada['nulos'], PDO::PARAM_STR);
                $stmt->bindValue(12, $datosLlamada['observacionesOtros'], PDO::PARAM_STR);
                $stmt->bindValue(13, $datosLlamada['operador'], PDO::PARAM_STR);

                //piloto, curso, nombrecurso, diacita
                $stmt->bindValue(14, "", PDO::PARAM_STR);
                $stmt->bindValue(15, "", PDO::PARAM_STR);
                $stmt->bindValue(16, "", PDO::PARAM_STR);
                $stmt->bindValue(17, "", PDO::PARAM_STR);
                
                $stmt->bindValue(18, $datosLlamada['anoPedirCita'], PDO::PARAM_INT);
                $stmt->bindValue(19, $datosLlamada['mesPedirCita'], PDO::PARAM_INT);
                $stmt->bindValue(20, $datosLlamada['codigo_llamada'], PDO::PARAM_STR);
                $stmt->bindValue(21, $datosLlamada['fecha_seguimiento'], PDO::PARAM_STR);
                $stmt->bindValue(22, $datosLlamada['tipo_seguimiento'], PDO::PARAM_STR);
                $stmt->bindValue(23, $datosLlamada['usuario_seguimiento'], PDO::PARAM_STR);
                $stmt->bindValue(24, $datosLlamada['prioridad'], PDO::PARAM_STR);
                $stmt->bindValue(25, $_SESSION['usuario'], PDO::PARAM_STR);


                $stmt->execute();
                return true;

            } else {

                return false;

            }

            unset($conexionPDO);
           
    }

    function controlLlamadas($datosOperador) {

        $datosLlamadas = [
            'numeroEmpresa' => "",
            'numeroLlamadas' => "",
            'pendientes' => "",
            'citas' => "",
            'credito' => "",
            'otraEmpresa' => "",
            'autonomos' => "",
            'noInteresa' => "",
            'noTelefono' => "",
            'noLOPD' => "",
            'otros' => ""
        ];
        $operador = $datosOperador['operador'];
        $fecha = $datosOperador['fecha'];
        $fecha = date('d-m-Y', strtotime($fecha));

        $conexionPDO = realizarConexion();

        $sqlEmpresas = "SELECT COUNT(DISTINCT idempresa) FROM llamadas WHERE fecha = '$fecha' AND recibidopor = '$operador'";
        $sqlLlamadas = "SELECT COUNT(idllamada) FROM llamadas WHERE fecha = '$fecha' AND recibidopor = '$operador'";
        $sqlPendientes = "SELECT DISTINCT COUNT(estadollamada) FROM llamadas WHERE fecha = '$fecha' AND recibidopor = '$operador' AND estadollamada = 'pendiente'";
        $sqlCitas = "SELECT DISTINCT COUNT(estadollamada) FROM llamadas WHERE fecha = '$fecha' AND recibidopor = '$operador' AND estadollamada = 'cita'";

        $sqlCredito = "SELECT COUNT(otrosnul) FROM llamadas WHERE fecha = '$fecha' AND recibidopor = '$operador' AND otrosnul = 'hanGastadoCredito'";
        $sqlOtraEmpresa = "SELECT COUNT(otrosnul) FROM llamadas WHERE fecha = '$fecha' AND recibidopor = '$operador' AND otrosnul = 'loRealizanConOtraEmpresa'";
        $sqlAutonomos = "SELECT COUNT(otrosnul) FROM llamadas WHERE fecha = '$fecha' AND recibidopor = '$operador' AND otrosnul = 'autonomos'";
        $sqlNoInteresa = "SELECT COUNT(otrosnul) FROM llamadas WHERE fecha = '$fecha' AND recibidopor = '$operador' AND otrosnul = 'noLesInteresa'";
        $sqlNoTelefono = "SELECT COUNT(otrosnul) FROM llamadas WHERE fecha = '$fecha' AND recibidopor = '$operador' AND otrosnul = 'telefonoNoExiste'";
        $sqlNoLOPD = "SELECT COUNT(otrosnul) FROM llamadas WHERE fecha = '$fecha' AND recibidopor = '$operador' AND otrosnul = 'noConsienteTratamientoDeSusDat'";
        $sqlOtros = "SELECT COUNT(otrosnul) FROM llamadas WHERE fecha = '$fecha' AND recibidopor = '$operador' AND otrosnul = 'otros'";

       
        $stmt = $conexionPDO->query($sqlEmpresas);

        if($cuenta = $stmt->fetch()){

            $datosLlamadas['numeroEmpresas'] = $cuenta;

        }

        $stmt = $conexionPDO->query($sqlLlamadas);

        if($cuenta = $stmt->fetch()){

            $datosLlamadas['numeroLlamadas'] = $cuenta;

        }

        $stmt = $conexionPDO->query($sqlPendientes);

        if($cuenta = $stmt->fetch()){

            $datosLlamadas['pendientes'] = $cuenta;

        }
        
        $stmt = $conexionPDO->query($sqlCitas);

        if($cuenta = $stmt->fetch()){

            $datosLlamadas['citas'] = $cuenta;

        }

        $stmt = $conexionPDO->query($sqlCredito);

        if($cuenta = $stmt->fetch()){

            $datosLlamadas['credito'] = $cuenta;

        }

        $stmt = $conexionPDO->query($sqlOtraEmpresa);

        if($cuenta = $stmt->fetch()){

            $datosLlamadas['otraEmpresa'] = $cuenta;

        }

        $stmt = $conexionPDO->query($sqlAutonomos);

        if($cuenta = $stmt->fetch()){

            $datosLlamadas['autonomos'] = $cuenta;

        }

        $stmt = $conexionPDO->query($sqlNoInteresa);

        if($cuenta = $stmt->fetch()){

            $datosLlamadas['noInteresa'] = $cuenta;

        }

        $stmt = $conexionPDO->query($sqlNoTelefono);

        if($cuenta = $stmt->fetch()){

            $datosLlamadas['noTelefono'] = $cuenta;

        }

        $stmt = $conexionPDO->query($sqlNoLOPD);

        if($cuenta = $stmt->fetch()){

            $datosLlamadas['noLOPD'] = $cuenta;

        }

        $stmt = $conexionPDO->query($sqlOtros);

        if($cuenta = $stmt->fetch()){

            $datosLlamadas['otros'] = $cuenta;

        }

        unset($conexionPDO);
        return $datosLlamadas;

    }

    function pendientes($datosPendientes) {

        $operador = $_SESSION['codigoUsuario'];

        $fechaInicio = date('d-m-Y', strtotime($datosPendientes['fechaInicio']));
        $fechaFin = date('d-m-Y', strtotime($datosPendientes['fechaFin']));
        $provincia = $datosPendientes['provincia'];
        $poblacion = $datosPendientes['poblacion'];

        $conexionPDO = realizarConexion();

        $idempresas = [];
        $idllamadas = [];
        $llamadasPendientes = [];
        $empresasPendientes = [];
        $empresasLlamadas = [];

        //sacamos todas las IDs de empresas entre dos fechas

        $sql = "SELECT DISTINCT idempresa FROM `llamadas` WHERE STR_TO_DATE(fechapendiente, '%d-%m-%Y') BETWEEN STR_TO_DATE('$fechaInicio', '%d-%m-%Y') AND STR_TO_DATE('$fechaFin', '%d-%m-%Y') AND recibidopor = $operador";

        if($_SESSION['rol'] == "admin"){

            $sql = "SELECT DISTINCT idempresa FROM `llamadas` WHERE STR_TO_DATE(fechapendiente, '%d-%m-%Y') BETWEEN STR_TO_DATE('$fechaInicio', '%d-%m-%Y') AND STR_TO_DATE('$fechaFin', '%d-%m-%Y')";

        }

        $stmt = $conexionPDO->query($sql);

        while($idempresa = $stmt->fetch()){

            array_push($idempresas, $idempresa);

        }

        if(!empty($idempresas)){

            for($i=0; $i < count($idempresas); $i++){

                $id = $idempresas[$i][0];
                $sql = "SELECT idllamada FROM llamadas WHERE idempresa = '$id' ORDER BY idllamada DESC LIMIT 1";

                $stmt = $conexionPDO->query($sql);

                if($row = $stmt->fetch()){

                    array_push($idllamadas, $row);

                }

            }

            for($i=0; $i < count($idllamadas); $i++){

                $id = $idllamadas[$i][0];

                $sql = "SELECT idempresa, horapendiente , observacionesinterlocutor FROM llamadas WHERE idllamada = '$id' AND STR_TO_DATE(fechapendiente, '%d-%m-%Y') BETWEEN STR_TO_DATE('$fechaInicio', '%d-%m-%Y') AND STR_TO_DATE('$fechaFin', '%d-%m-%Y') AND recibidopor = $operador ORDER BY idllamada DESC LIMIT 1";

                if($_SESSION['rol'] == "admin"){

                $sql = "SELECT idempresa, horapendiente , observacionesinterlocutor FROM llamadas WHERE idllamada = '$id' AND STR_TO_DATE(fechapendiente, '%d-%m-%Y') BETWEEN STR_TO_DATE('$fechaInicio', '%d-%m-%Y') AND STR_TO_DATE('$fechaFin', '%d-%m-%Y') ORDER BY idllamada DESC LIMIT 1";

                }

                $stmt = $conexionPDO->query($sql);

                if($row = $stmt->fetch()){

                    array_push($llamadasPendientes, $row);

                }

            }

            for($i=0; $i < count($llamadasPendientes); $i++){

                    $id = $llamadasPendientes[$i][0];
                    $sql = "SELECT idempresa, nombre, poblacion FROM empresas WHERE idempresa = '$id'";

                    if($provincia != "" && $poblacion != "todas"){

                        $sql = $sql . " AND provincia = '$provincia' AND poblacion = '$poblacion'";

                    }
                        
                    $stmt = $conexionPDO->query($sql);

                    if($row = $stmt->fetch()){

                        array_push($empresasPendientes, $row); 

                    }

                }

                if(!empty($empresasPendientes)){

                    $mode = current($empresasPendientes);
                    $mode2 = current($llamadasPendientes);

                    for($i=0; $i < count($empresasPendientes); $i++) {
                    if($mode['idempresa'] == $mode2['idempresa']){

                    $mezla = array_merge($mode, $mode2);
                    array_push($empresasLlamadas, $mezla);

                    while($mode = next($empresasPendientes)){

                        $mode2 = next($llamadasPendientes);

                            $mezla = array_merge($mode, $mode2);
                            array_push($empresasLlamadas, $mezla);

                    }

                    usort($empresasLlamadas, "ordenarArray");

                    }  else {

                        $mode2 = next($llamadasPendientes);

                    }


                }



                }
        }

        
        unset($conexionPDO);
        return $empresasLlamadas;


    }

    function ordenarArray($a, $b){

        if($a['horapendiente'] > $b['horapendiente']){

            return $a;

        }

    }

    function listadoLlamadas($idempresa) {
  
        $listadoLlamadas = [];
        $listadoCursos = [];
        $llamadasCursos = [];

        $conexionPDO = realizarConexion();
        $sql = "SELECT * FROM llamadas WHERE idempresa = '$idempresa' ORDER BY idllamada DESC";
       
        $stmt = $conexionPDO->query($sql);

        while($row = $stmt->fetch()){

            array_push($listadoLlamadas, $row);

        }


        unset($conexionPDO);
        return $listadoLlamadas;

    }

    function cogerIDNuevaLlamada() {

        $conexionPDO = realizarConexion();
        $sql = "SELECT idllamada FROM llamadas ORDER BY idllamada DESC LIMIT 1";
        $stmt = $conexionPDO->query($sql);

        if($idllamada = $stmt->fetch()){

            return $idllamada[0];

        }

        unset($conexionPDO);


    }

    function pedirCitasCallCenter() {

        $idempresas = [];

        $conexionPDO = realizarConexion();
        $sql = "SELECT * FROM llamadas WHERE observacionesOtros = 'pedircita'";
        $stmt = $conexionPDO->query($sql);

        while($idempresa = $stmt->fetch()){

            array_push($idempresas, $idempresa);

        }

        unset($conexionPDO);
        return $idempresas;


    }

    function actualizarLlamadaCallCenter($idllamada){

        $conexionPDO = realizarConexion();
        $sql = "UPDATE llamadas SET observacionesOtros = ? WHERE idllamada = ?";
        $stmt = $conexionPDO->prepare($sql);

        if($stmt){

            $stmt->bindValue(1, "PedirCitaRealizada", PDO::PARAM_STR);
            $stmt->bindValue(2, $idllamada, PDO::PARAM_STR);

            $stmt->execute();

        }

        unset($conexionPDO);

    }

    function cambiarFechasPendientes($idEmpresas, $nuevaFecha){

        $conexionPDO = realizarConexion();

        for($i=0; $i < count($idEmpresas); $i++){

            $sql = "UPDATE llamadas SET fechapendiente = ? WHERE idempresa = ? ORDER BY idllamada DESC LIMIT 1";
            $stmt = $conexionPDO->prepare($sql);    

            if($stmt){

                $stmt->bindValue(1, $nuevaFecha, PDO::PARAM_STR);
                $stmt->bindValue(2, $idEmpresas[$i], PDO::PARAM_STR);

                $stmt->execute();

            }

        }

    }

    function hacerSeguimientoCallCenter() {
        
        $idempresas = [];

        $conexionPDO = realizarConexion();
        //$sql = "SELECT * FROM llamadas WHERE observacionesOtros = 'seguimiento'";
        $sql = "SELECT * FROM llamadas 
            WHERE llamadas.idllamada IN (SELECT MAX(idllamada) as id FROM llamadas GROUP BY idempresa) AND 
                  observacionesOtros LIKE '%seguimiento%' AND 
                  DATE(str_to_date(fechapendiente,'%d-%m-%Y'))<=DATE(NOW()) AND
                  usuario_seguimiento = '{$_SESSION['codigoUsuario']}'
            ORDER BY DATE(str_to_date(fechapendiente,'%d-%m-%Y')) ASC
        ";
        $stmt = $conexionPDO->query($sql);

        while($idempresa = $stmt->fetch()){

            array_push($idempresas, $idempresa);

        }

        unset($conexionPDO);
        return $idempresas;

    }

    function actualizarSeguimientoCallCenter($idllamada){

        $conexionPDO = realizarConexion();
        $sql = "UPDATE llamadas SET observacionesOtros = ? WHERE idllamada = ?";
        $stmt = $conexionPDO->prepare($sql);

        if($stmt){

            $stmt->bindValue(1, "seguimientoRealizado", PDO::PARAM_STR);
            $stmt->bindValue(2, $idllamada, PDO::PARAM_STR);

            $stmt->execute();

        }

        unset($conexionPDO);

    }

    function paginacion($limite, $offset){

        $conexionPDO = realizarConexion();
        $sql = "SELECT COUNT(*) FROM llamadas WHERE observacionesOtros = 'pedircita'";
        $stmt = $conexionPDO->query($sql);

        if($nEmpresas = $stmt->fetch()){}

        $paginas = ceil($nEmpresas[0] / $limite);
        
        return $paginas;


    }

    function paginacion2($limite, $offset){

        $empresas = [];

        $conexionPDO = realizarConexion();
        //$sql = "SELECT * FROM llamadas WHERE observacionesOtros = 'pedircita' LIMIT $limite OFFSET $offset";
        $sql = "SELECT llamadas.idempresa, llamadas.idllamada, llamadas.observacionesinterlocutor, empresas.nombre, empresas.poblacion FROM llamadas INNER JOIN empresas ON llamadas.idempresa = empresas.idempresa AND llamadas.observacionesOtros = 'pedircita' LIMIT $limite OFFSET $offset";
        $stmt = $conexionPDO->query($sql);

        while($empresa = $stmt->fetch()){

            array_push($empresas, $empresa);

        }

        return $empresas;

    }
    
    function pendientes2($datosPendientes) {

        $operador = $_SESSION['codigoUsuario'];

        $fechaInicio = date('d-m-Y', strtotime($datosPendientes['fechaInicio']));
        $fechaFin = date('d-m-Y', strtotime($datosPendientes['fechaFin']));
        $provincia = $datosPendientes['provincia'];
        $poblacion = $datosPendientes['poblacion'];

        $conexionPDO = realizarConexion();

        $idempresas = [];
        $idllamadas = [];
        $llamadasPendientes = [];
        $empresasPendientes = [];
        $empresasLlamadas = [];

        $sql = "SELECT DISTINCT idempresa FROM `llamadas` WHERE STR_TO_DATE(fechapendiente, '%d-%m-%Y') BETWEEN STR_TO_DATE('$fechaInicio', '%d-%m-%Y') AND STR_TO_DATE('$fechaFin', '%d-%m-%Y') AND recibidopor = $operador";
        
        if($_SESSION['rol'] == "admin"){

            $sql = "SELECT DISTINCT idempresa FROM `llamadas` WHERE STR_TO_DATE(fechapendiente, '%d-%m-%Y') BETWEEN STR_TO_DATE('$fechaInicio', '%d-%m-%Y') AND STR_TO_DATE('$fechaFin', '%d-%m-%Y')";

        }

        if($_SESSION['codigoUsuario'] == "200"){

            $sql.= " AND usuario_asignador = '".$_SESSION['usuario']."'";

        }

        $stmt = $conexionPDO->query($sql);

        while($idempresa = $stmt->fetch()){

            array_push($idempresas, $idempresa);

        }

        //cogemos el ID de la ultima llamada

        if(!empty($idempresas)){

            for($i = 0; $i < count($idempresas); $i++){

                $id = $idempresas[$i][0];
                $sql = "SELECT idllamada, idempresa 
                        FROM llamadas 
                        INNER JOIN ( 
                            SELECT MAX(idllamada) as id,recibidopor,usuario_seguimiento 
                            FROM llamadas 
                            GROUP BY idempresa )
                         as c ON c.id = llamadas.idllamada 
                         WHERE idempresa = '$id' AND (
                            c.usuario_seguimiento = '$operador' OR (
                                c.usuario_seguimiento IS NULL AND 
                                c.recibidopor = '$operador'
                            )
                        ) ORDER BY idllamada DESC LIMIT 1
                ";

                //if($_SESSION['rol'] == "admin"){
                    $sql = "SELECT idllamada, idempresa FROM llamadas WHERE idempresa = '$id' ORDER BY idllamada DESC LIMIT 1";
                //}
                
                $stmt = $conexionPDO->query($sql);

                while($idllamada = $stmt->fetch()){

                    array_push($idllamadas, $idllamada);

                }

            }

        }

        if(!empty($idllamadas)){

            for($i = 0; $i < count($idllamadas); $i++){

                $id = $idllamadas[$i][0];

                $sql = "SELECT llamadas.idempresa, llamadas.idllamada, empresas.nombre, empresas.codigo, empresas.horario, llamadas.horapendiente, empresas.provincia, empresas.poblacion, llamadas.codigo_llamada,llamadas.tipo_seguimiento FROM llamadas INNER JOIN empresas ON llamadas.idllamada = '$id' AND llamadas.idempresa = empresas.idempresa AND STR_TO_DATE(fechapendiente, '%d-%m-%Y') BETWEEN STR_TO_DATE('$fechaInicio', '%d-%m-%Y') AND STR_TO_DATE('$fechaFin', '%d-%m-%Y') AND recibidopor = $operador";

                if($_SESSION['rol'] == "admin"){
    
                    $sql = "SELECT llamadas.idempresa, llamadas.idllamada, empresas.nombre, empresas.codigo, empresas.horario, llamadas.horapendiente, empresas.provincia, empresas.poblacion, llamadas.codigo_llamada,llamadas.tipo_seguimiento  FROM llamadas INNER JOIN empresas ON llamadas.idllamada = '$id' AND llamadas.idempresa = empresas.idempresa AND STR_TO_DATE(fechapendiente, '%d-%m-%Y') BETWEEN STR_TO_DATE('$fechaInicio', '%d-%m-%Y') AND STR_TO_DATE('$fechaFin', '%d-%m-%Y')";

                }

                if($provincia != "" && $poblacion != "todas"){

                    $sql = $sql . " AND provincia LIKE '$provincia%' AND poblacion = '$poblacion'";
        
                }

                $stmt = $conexionPDO->query($sql);

                while($pendiente = $stmt->fetch()){
                    array_push($llamadasPendientes, $pendiente);
                }
            }
        }

        usort($llamadasPendientes, "ordenarArray");

        unset($conexionPDO);
        return $llamadasPendientes;

    }

    function listadoLlamadas2() {

        $listadoLlamadas = [];

        $conexionPDO = realizarConexion();
        $sql = "SELECT llamadas.*, cursos.Curso FROM llamadas INNER JOIN cursos ON llamadas.idempresa = cursos.idempresa
        AND llamadas.idllamada = cursos.idllamada
        ORDER BY llamadas.idllamada DESC";
       
        $stmt = $conexionPDO->query($sql);

        while($row = $stmt->fetch()){

            array_push($listadoLlamadas, $row);

        }

        unset($conexionPDO);
        return $listadoLlamadas;

    }

    function listadoCurso($id) {

        $listadoCurso = [];

        $conexionPDO = realizarConexion();
        $sql = "SELECT * FROM cursos WHERE idempresa = $id";
       
        $stmt = $conexionPDO->query($sql);

        while($row = $stmt->fetch()){

            array_push($listadoCurso, $row);

        }

        unset($conexionPDO);
        return $listadoCurso;

    }

    function busqueda($palabra, $limite, $offset) {

        $empresas = [];

        $conexionPDO = realizarConexion();

        $p = substr($palabra, 0, 2);
        $p = strtoupper($p);

        if(is_numeric($palabra)){

            $sql = "SELECT llamadas.idempresa, llamadas.idllamada, llamadas.observacionesinterlocutor, empresas.nombre, empresas.poblacion FROM llamadas INNER JOIN empresas ON llamadas.idempresa = empresas.idempresa AND llamadas.observacionesOtros = 'pedircita' AND llamadas.idempresa = $palabra LIMIT $limite OFFSET $offset"; 

        } else {

            $sql = "SELECT llamadas.idempresa, llamadas.idllamada, llamadas.observacionesinterlocutor, empresas.nombre, empresas.poblacion FROM llamadas INNER JOIN empresas ON llamadas.idempresa = empresas.idempresa AND llamadas.observacionesOtros = 'pedircita' AND llamadas.observacionesinterlocutor LIKE '%$palabra%' LIMIT $limite OFFSET $offset";

        }

        if($p == "P:"){

            $palabra = substr($palabra, 2);
            $sql = "SELECT llamadas.idempresa, llamadas.idllamada, llamadas.observacionesinterlocutor, empresas.nombre, empresas.poblacion FROM llamadas INNER JOIN empresas ON llamadas.idempresa = empresas.idempresa AND llamadas.observacionesOtros = 'pedircita' AND empresas.poblacion = '$palabra' LIMIT $limite OFFSET $offset";
            
        }

        $stmt = $conexionPDO->query($sql);

        while($empresa = $stmt->fetch()){

            array_push($empresas, $empresa);

        }

        return $empresas;

    }
    function busquedaAnoMesPoblacion($palabra, $ano, $mes, $poblacion,$provincia, $prioridad, $limite, $offset) {

        $filter = 'WHERE llamadas.estadoLlamada LIKE "Pedir cita%"';

        if($provincia != "Todas" and $provincia != "todas"){
            $filter = $filter . ' AND empresas.provincia LIKE "'.$provincia.'"';
        }

        if($poblacion != "Todas" and $poblacion != "todas"){
            $filter = $filter . ' AND empresas.poblacion LIKE "'.$poblacion.'"';
        }

        if($ano != "Todas" and $ano != "todas"){
            $filter = $filter . ' AND llamadas.anoPedirCita = "'.$ano.'"';
        }
        if($mes != "Todas" and $mes != "todas"){
            $filter = $filter . ' AND llamadas.mesPedirCita = "'.$mes.'"';
        }
        if($prioridad != "Todas"){
            $filter = $filter . ' AND llamadas.prioridad = "'.$prioridad.'"';
        }

        if($palabra != "Todas" and $palabra != "todas" && $palabra != ""){
            if(is_numeric($palabra)){
                $filter = $filter . ' AND (llamadas.idempresa = "'.$palabra.'")';
            }else{
                $filter = $filter . ' AND (llamadas.observacionesinterlocutor LIKE "%'.$palabra.'%")';
            }
        }

        $empresas = [];

        $conexionPDO = realizarConexion();

        $getLatestLlamadaSQL = "select * from 
            (SELECT 
            idempresa as Aidempresa, 
            MAX(str_to_date(CONCAT(`fecha`, '@', `hora`), '%d-%m-%Y@%H:%i')) as maxEmpresaFecha 
            FROM `llamadas` group by `idempresa`
            ) as A 
        join llamadas B 
        on A.maxEmpresaFecha = str_to_date(CONCAT(`fecha`, '@', `hora`), '%d-%m-%Y@%H:%i') and Aidempresa = B.idempresa  
        ";

        $sql = 'Select
        llamadas.anoPedirCita,
        llamadas.mesPedirCita,
        llamadas.idempresa,
        llamadas.idllamada,
        llamadas.observacionesinterlocutor,
        empresas.nombre,
        empresas.poblacion,
        llamadas.estadoLlamada,
        llamadas.prioridad
        FROM ('.$getLatestLlamadaSQL.') as llamadas
        INNER JOIN empresas ON llamadas.idempresa = empresas.idempresa
        '.$filter.' order by anoPedirCita ASC LIMIT '.$limite.' OFFSET '.$offset;

        //echo $sql;
        //echo "<hr>";

        $stmt = $conexionPDO->query($sql);

        $empresa = $stmt->fetchAll();
        
        $result = $conexionPDO->query('SELECT COUNT(*) as amount FROM ('.$getLatestLlamadaSQL.') as llamadas INNER JOIN empresas ON llamadas.idempresa = empresas.idempresa '.$filter.'');
        $amount = $result->fetch()['amount'];
        if($amount != 0){
            $empresa[0]['full_count'] = $amount;
        }

        //echo "<pre>";
        //print_r($empresa);
        //echo "</pre>";

        return $empresa;

    }

    function modificarFecha($post){
        if(
            empty($post['mesPedirCita']) ||
            empty($post['anoPedirCita']) ||
            empty($post['idllamada']) ||
            empty($post['prioridad'])
        ){
            echo json_encode(['success'=>false]);
            die;
        }
        $conexionPDO = realizarConexion();
        $sql = "UPDATE llamadas SET mesPedirCita = ?,anoPedirCita = ?, prioridad=? WHERE idllamada = ?";
        $stmt = $conexionPDO->prepare($sql);

        if($stmt){

            $stmt->bindValue(1, $post['mesPedirCita'], PDO::PARAM_STR);
            $stmt->bindValue(2, $post['anoPedirCita'], PDO::PARAM_STR);
            $stmt->bindValue(3, $post['prioridad'], PDO::PARAM_STR);
            $stmt->bindValue(4, $post['idllamada'], PDO::PARAM_STR);
            $stmt->execute();
        }

        unset($conexionPDO);
    }

?>