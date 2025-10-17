<?php 
require_once __DIR__.'/conexionBD.php';
require_once __DIR__.'/funcionesEmpresa.php';
function getEmpresasList(){
    $empresas = [];
    $conexionPDO = realizarConexion();
    $sql = "SELECT idempresa,nombre FROM empresas ORDER BY TRIM(empresas.nombre) ASC";
    $stmt = $conexionPDO->query($sql);
    while($empresa = $stmt->fetch()){
        $empresas[] = $empresa;
    }
    echo json_encode($empresas);
}

$function = $_REQUEST['action'];
if(function_exists($function)){
    call_user_func($function,[]);
}