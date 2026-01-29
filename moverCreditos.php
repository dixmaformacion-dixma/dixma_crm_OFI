<?php 
include "funciones/conexionBD.php";
if(date("d")==1 && date("m")=='01'){
    $conexionPDO = realizarConexion();
    $sql = "UPDATE empresas SET
        empresas.creditoCaducar = empresas.creditoAnhoAnterior,
        empresas.creditoAnhoAnterior = empresas.credito,
        empresas.credito = '',
        empresas.creditoGuardado = 'NO'
    ";
    $stmt = $conexionPDO->query($sql);
} 