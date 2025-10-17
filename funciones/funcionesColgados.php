<?php 

function getList(){
    $conexionPDO = realizarConexion();
    $sql = "SELECT empresas.*, '' as acciones FROM llamadas INNER JOIN empresas ON empresas.idempresa = llamadas.idempresa WHERE llamadas.idllamada IN (SELECT MAX(llamadas.idllamada) as ultimo_id FROM `llamadas` GROUP BY idempresa) AND llamadas.estadollamada = 'cita' AND STR_TO_DATE(fechacita,'%d-%m-%Y')<NOW()";
    $stmt = $conexionPDO->query($sql);
    $data = [];
    while($row = $stmt->fetch()){
        $data[] = $row;
    }
    return $data;
}