<?php 
include "funciones/conexionBD.php";
if(date("d")==1){
    $conexionPDO = realizarConexion();
    $sql = "UPDATE empresas SET
        empresas.creditoCaducar = empresas.creditoAnhoAnterior,
        empresas.creditoAnhoAnterior = empresas.credito,
        empresas.credito = ''
    ";
    $stmt = $conexionPDO->query($sql);
}