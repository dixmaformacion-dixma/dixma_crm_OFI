<?php

    function listadoVentas($idempresa) {
  
        $listadoVentas = [];

        $conexionPDO = realizarConexion();
        $sql = "SELECT * FROM ventas WHERE idempresa = '$idempresa' ORDER BY idventa DESC";
       
        $stmt = $conexionPDO->query($sql);

        while($row = $stmt->fetch()){

            array_push($listadoVentas, $row);

        }

        unset($conexionPDO);
        return $listadoVentas;

    }

    function insertarVenta($datosVenta) {
  
        $listadoVentas = [];

        $conexionPDO = realizarConexion();
        $sql = "INSERT INTO ventas (fecha, hora, idcomercial, idempresa, curso1, nombrecurso1, horascurso1, modalidadcurso1, curso2, nombrecurso2, horascurso2, modalidadcurso2, curso3, nombrecurso3, horascurso3, modalidadcurso3, observacionesventa, emailfactura, nombreasesoria, telfasesoria, mailasesoria, importe, fechacobro, formapago, numerocuenta) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conexionPDO->prepare($sql);

        if($stmt){

            $stmt->bindValue(1, $datosVenta['fecha'], PDO::PARAM_STR);
            $stmt->bindValue(2, $datosVenta['hora'], PDO::PARAM_STR);
            $stmt->bindValue(3, $datosVenta['comercial'], PDO::PARAM_STR);
            $stmt->bindValue(4, $datosVenta['idempresa'], PDO::PARAM_STR);
            $stmt->bindValue(5, $datosVenta['formacionCurso1'], PDO::PARAM_STR);
            $stmt->bindValue(6, $datosVenta['nombreCurso1'], PDO::PARAM_STR);
            $stmt->bindValue(7, $datosVenta['horasCurso1'], PDO::PARAM_STR);
            $stmt->bindValue(8, $datosVenta['modalidadCurso1'], PDO::PARAM_STR);
            $stmt->bindValue(9, $datosVenta['formacionCurso2'], PDO::PARAM_STR);
            $stmt->bindValue(10, $datosVenta['nombreCurso2'], PDO::PARAM_STR);
            $stmt->bindValue(11, $datosVenta['horasCurso2'], PDO::PARAM_STR);
            $stmt->bindValue(12, $datosVenta['modalidadCurso2'], PDO::PARAM_STR);
            $stmt->bindValue(13, $datosVenta['formacionCurso3'], PDO::PARAM_STR);
            $stmt->bindValue(14, $datosVenta['nombreCurso3'], PDO::PARAM_STR);
            $stmt->bindValue(15, $datosVenta['horasCurso3'], PDO::PARAM_STR);
            $stmt->bindValue(16, $datosVenta['modalidadCurso3'], PDO::PARAM_STR);
            $stmt->bindValue(17, $datosVenta['observacionesVenta'], PDO::PARAM_STR);
            $stmt->bindValue(18, $datosVenta['emailFacturacion'], PDO::PARAM_STR);
            $stmt->bindValue(19, $datosVenta['asesoria'], PDO::PARAM_STR);
            $stmt->bindValue(20, $datosVenta['telefAsesoria'], PDO::PARAM_STR);
            $stmt->bindValue(21, $datosVenta['emailAsesoria'], PDO::PARAM_STR);
            $stmt->bindValue(22, $datosVenta['importe'], PDO::PARAM_STR);
            $stmt->bindValue(23, $datosVenta['fechaCobro'], PDO::PARAM_STR);
            $stmt->bindValue(24, $datosVenta['formaPago'], PDO::PARAM_STR);
            $stmt->bindValue(25, $datosVenta['numeroCuenta'], PDO::PARAM_STR);

            $stmt->execute();

        }

        unset($conexionPDO);

    }
    function editarVenta($datosVenta, $idventa) {

        $conexionPDO = realizarConexion();
        $sql = "UPDATE `ventas` SET idcomercial = ?, curso1 = ?, nombrecurso1 = ?, horascurso1 = ?, modalidadcurso1 = ?, curso2 = ?, nombrecurso2 = ?, horascurso2 = ?, modalidadcurso2 = ?, curso3 = ?, nombrecurso3 = ?, horascurso3 = ?, modalidadcurso3 = ?, observacionesventa = ?, emailfactura = ?, nombreasesoria = ?, telfasesoria = ?, mailasesoria = ?, importe = ?, fechacobro = ?, formapago = ?, numerocuenta = ? 
        WHERE idventa = ?";

        $stmt = $conexionPDO->prepare($sql);

        if($stmt){

            $stmt->bindValue(1, $datosVenta['comercial'], PDO::PARAM_STR);
            $stmt->bindValue(2, $datosVenta['formacionCurso1'], PDO::PARAM_STR);
            $stmt->bindValue(3, $datosVenta['nombreCurso1'], PDO::PARAM_STR);
            $stmt->bindValue(4, $datosVenta['horasCurso1'], PDO::PARAM_STR);
            $stmt->bindValue(5, $datosVenta['modalidadCurso1'], PDO::PARAM_STR);
            $stmt->bindValue(6, $datosVenta['formacionCurso2'], PDO::PARAM_STR);
            $stmt->bindValue(7, $datosVenta['nombreCurso2'], PDO::PARAM_STR);
            $stmt->bindValue(8, $datosVenta['horasCurso2'], PDO::PARAM_STR);
            $stmt->bindValue(9, $datosVenta['modalidadCurso2'], PDO::PARAM_STR);
            $stmt->bindValue(10, $datosVenta['formacionCurso3'], PDO::PARAM_STR);
            $stmt->bindValue(11, $datosVenta['nombreCurso3'], PDO::PARAM_STR);
            $stmt->bindValue(12, $datosVenta['horasCurso3'], PDO::PARAM_STR);
            $stmt->bindValue(13, $datosVenta['modalidadCurso3'], PDO::PARAM_STR);
            $stmt->bindValue(14, $datosVenta['observacionesVenta'], PDO::PARAM_STR);
            $stmt->bindValue(15, $datosVenta['emailFacturacion'], PDO::PARAM_STR);
            $stmt->bindValue(16, $datosVenta['asesoria'], PDO::PARAM_STR);
            $stmt->bindValue(17, $datosVenta['telefAsesoria'], PDO::PARAM_STR);
            $stmt->bindValue(18, $datosVenta['emailAsesoria'], PDO::PARAM_STR);
            $stmt->bindValue(19, $datosVenta['importe'], PDO::PARAM_STR);
            $stmt->bindValue(20, $datosVenta['fechaCobro'], PDO::PARAM_STR);
            $stmt->bindValue(21, $datosVenta['formaPago'], PDO::PARAM_STR);
            $stmt->bindValue(22, $datosVenta['numeroCuenta'], PDO::PARAM_STR);
            $stmt->bindValue(23, $idventa, PDO::PARAM_INT);

            return $stmt->execute();

        }

        unset($conexionPDO);

    }

    function cargarVenta($idempresa) {
  
        $conexionPDO = realizarConexion();
        $sql = "SELECT * FROM ventas WHERE idempresa = '$idempresa' ORDER BY idventa DESC";
       
        $stmt = $conexionPDO->query($sql);

        if($venta = $stmt->fetch()) {

           unset($conexionPDO);
           return $venta;

        }

    }
    function cargarVentaPorID($idVenta) {
  
        $conexionPDO = realizarConexion();
        $sql = "SELECT * FROM ventas WHERE idventa = '$idVenta'";
       
        $stmt = $conexionPDO->query($sql);

        if($venta = $stmt->fetch()) {

           unset($conexionPDO);
           return $venta;

        }
        return false;
    }
    function eliminarVenta($idventa) {
  
        $conexionPDO = realizarConexion($idventa);
        $sql = "DELETE FROM `ventas` WHERE `idventa` = '$idventa'";
       
        $stmt= $conexionPDO->prepare($sql);
        return $stmt->execute();
    }

	function listadoVentasEmpresasComercial($fechaInicio, $fechaFin, $empresa = '') {

        $ventas = [];
        $empresas = [];

        $where = "";
        if(!empty($empresa)){
            $where.= " AND (ventas.idempresa='{$empresa}' OR empresas.nombre LIKE '%{$empresa}%')";
        }
        if(!empty($fechaInicio) && !empty($fechaFin)){
            $where.= " AND STR_TO_DATE(ventas.fecha, '%d-%m-%Y') BETWEEN STR_TO_DATE('$fechaInicio', '%d-%m-%Y') AND STR_TO_DATE('$fechaFin', '%d-%m-%Y')";
        }
        if($_SESSION['rol'] != "admin"){
            $codigoUsuario = $_SESSION['codigoUsuario'];
            $where.= " AND idcomercial = '$codigoUsuario'";
        }

        $conexionPDO = realizarConexion();
        $sql = "SELECT 
            ventas.idventa,
            ventas.idempresa, 
            ventas.importe, 
            ventas.fechacobro, 
            ventas.fecha 
            FROM ventas 
            INNER JOIN empresas ON empresas.idempresa = ventas.idempresa 
            WHERE 1=1 $where AND ventas.idventa IN (SELECT MAX(idventa) FROM ventas GROUP BY idempresa)
        ";

        $stmt = $conexionPDO->query($sql);

        while($venta = $stmt->fetch()){

            array_push($ventas, $venta);

        }

        return $ventas;
    }

    function listadoVentasEmpresas($fechaInicio, $fechaFin, $empresa = '') {

        $ventas = [];
        $empresas = [];

        $where = "";
        if(!empty($empresa)){
            $where.= " AND (ventas.idempresa='{$empresa}' OR empresas.nombre LIKE '%{$empresa}%')";
        }
        if(!empty($fechaInicio) && !empty($fechaFin)){
            $where.= " AND STR_TO_DATE(ventas.fecha, '%d-%m-%Y') BETWEEN STR_TO_DATE('$fechaInicio', '%d-%m-%Y') AND STR_TO_DATE('$fechaFin', '%d-%m-%Y')";
        }
        if($_SESSION['rol'] != "admin"){
            $codigoUsuario = $_SESSION['codigoUsuario'];
            $where.= " AND idcomercial = '$codigoUsuario'";
        }

        $conexionPDO = realizarConexion();
        $sql = "SELECT 
            ventas.idventa,
            ventas.idempresa, 
            ventas.importe, 
            ventas.fechacobro, 
            ventas.fecha 
            FROM ventas 
            INNER JOIN empresas ON empresas.idempresa = ventas.idempresa 
            WHERE 1=1 $where
        ";

        $stmt = $conexionPDO->query($sql);

        while($venta = $stmt->fetch()){

            array_push($ventas, $venta);

        }

        return $ventas;
    }

?>