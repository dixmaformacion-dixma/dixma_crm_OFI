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

function getCursosList(){
    $cursos = [];
    $conexionPDO = realizarConexion();
    $sql = "SELECT idCurso,nombreCurso,tipoCurso,horasCurso FROM listacursos ORDER BY TRIM(listacursos.nombreCurso) ASC";
    $stmt = $conexionPDO->query($sql);
    while($curso = $stmt->fetch()){
        $cursos[] = [
            "0"=>$curso['idCurso'],
            "1"=>$curso['nombreCurso'],
            "2"=>$curso['tipoCurso'],
            "3"=>"",
            "horasCurso"=>$curso['horasCurso'],
            "idCurso"=>$curso['idCurso'],
            "nombreCurso"=>$curso['nombreCurso'],
            "tipoCurso"=>$curso['tipoCurso']
        ];
    }
    echo json_encode($cursos);
}

$function = $_REQUEST['action'];
if(function_exists($function)){
    call_user_func($function,[]);
}