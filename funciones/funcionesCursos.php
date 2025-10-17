<?php

    function insertarNuevoCurso($datosCurso, $id){

            $conexionPDO = realizarConexion();
            $sql = "INSERT INTO cursos (idempresa, curso, tipodeCurso, sector, horasCurso) 
            VALUES (?, ?, ?, ?, ?)";
            $stmt = $conexionPDO->prepare($sql);

            if($stmt){

                $stmt->bindValue(1, $id, PDO::PARAM_INT);
                $stmt->bindValue(2, $datosCurso['curso'], PDO::PARAM_STR);
                $stmt->bindValue(3, $datosCurso['tipoCurso'], PDO::PARAM_STR);
                $stmt->bindValue(4, $datosCurso['tipoCurso'], PDO::PARAM_STR);
                $stmt->bindValue(5, $datosCurso['horasCurso'], PDO::PARAM_STR);

                $stmt->execute();
                return true;

            } else {

                return false;

            }

            unset($conexionPDO);
           
    }

    function cargarCursos($curso){

        $cursos = [];
        $nombreEmpresas = [];

        $cursosEmpresas = [];

        $conexionPDO = realizarConexion();
        $sql = "SELECT Curso, Sector, tipodeCurso, horasCurso, idempresa, Codigo FROM cursos WHERE Curso = '$curso' AND estadoCurso = 'interesado'";
        $stmt = $conexionPDO->query($sql);

        while($row = $stmt->fetch()){

            array_push($cursos, $row);

        }

        if(!empty($cursos)){

            for($i=0; $i < count($cursos); $i++){

                $id = $cursos[$i]['idempresa'];

                $sql = "SELECT nombre FROM empresas WHERE idempresa = '$id'";
                $stmt = $conexionPDO->query($sql);

                if($row = $stmt->fetch()){

                    array_push($nombreEmpresas, $row);

                }

            }

            $mode = current($cursos);
            $mode2 = current($nombreEmpresas);

            $mezcla = array_merge($mode, $mode2);
            array_push($cursosEmpresas, $mezcla);

            while($mode = next($cursos)){

                $mode2 = next($nombreEmpresas);

                $mezcla = array_merge($mode, $mode2);
                array_push($cursosEmpresas, $mezcla);

            }

        }

        unset($conexionPDO);
        return $cursosEmpresas;

    }

    function desinteresado($idllamada, $codigoCurso) {

        $conexionPDO = realizarConexion();

        $sql = "UPDATE cursos SET estadoCurso = ? WHERE idempresa = ? AND Codigo = ?";
        $stmt = $conexionPDO->prepare($sql);

        if($stmt){

            $stmt->bindValue(1, 'desinteresado', PDO::PARAM_STR);
            $stmt->bindValue(2, $idllamada, PDO::PARAM_INT);
            $stmt->bindValue(3, $codigoCurso, PDO::PARAM_INT);


            $stmt->execute();

        } else {

            return false;

        }

        unset($conexionPDO);

    }

    function agregarCurso($datosCurso){

        $conexionPDO = realizarConexion();
        $sql = "INSERT INTO listacursos (nombreCurso, tipoCurso, horasCurso) VALUES (?, ?, ?)";
        $stmt = $conexionPDO->prepare($sql);

        if($stmt){

            $stmt->bindValue(1, $datosCurso['nombreCurso'], PDO::PARAM_STR);
            $stmt->bindValue(2, $datosCurso['tipoCurso'], PDO::PARAM_STR);
            $stmt->bindValue(3, $datosCurso['horasCurso'], PDO::PARAM_STR);

            $stmt->execute();
            return true;

        } else {

            return false;

        }

        unset($conexionPDO);

    }

    function listaCursos($tipoCurso){

        $listaCursos = [];

        $conexionPDO = realizarConexion();
        $sql = "SELECT * FROM listacursos WHERE tipoCurso = '$tipoCurso'";
        $stmt = $conexionPDO->query($sql);

        while($curso = $stmt->fetch()){

            array_push($listaCursos, $curso);

        }

        return $listaCursos;
        unset($conexionPDO);

    }

    function cargarTipoCurso(){

        $tipoCurso = [];

        $conexionPDO = realizarConexion();
        $sql = "SELECT DISTINCT tipoCurso FROM listacursos";
        $stmt = $conexionPDO->query($sql);

        if($fetchedresult = $stmt->fetchAll()){
            foreach($fetchedresult as $type){
                array_push($tipoCurso, $type['tipoCurso']);
            }
        };

        return $tipoCurso;
        unset($conexionPDO);


    }

    function cursosInteresados($idEmpresa){

        $listaCursos = [];

        $conexionPDO = realizarConexion();
        $sql = "SELECT * FROM cursos WHERE idempresa = $idEmpresa AND estadoCurso = 'interesado' ORDER BY codigo DESC";
        $stmt = $conexionPDO->query($sql);

        while($curso = $stmt->fetch()){

            array_push($listaCursos, $curso);

        }

        return $listaCursos;
        unset($conexionPDO);

    }

    function crearCurso($datosCurso){

        $conexionPDO = realizarConexion();
        $sql = "INSERT INTO listacursos (nombreCurso, tipoCurso, horasCurso) 
        VALUES (?, ?, ?)";
        $stmt = $conexionPDO->prepare($sql);

        if($stmt){

            $stmt->bindValue(1, $datosCurso['nombreCurso'], PDO::PARAM_STR);
            $stmt->bindValue(2, $datosCurso['tipoCurso'], PDO::PARAM_STR);
            $stmt->bindValue(3, $datosCurso['horasCurso'], PDO::PARAM_STR);

            $stmt->execute();
            return true;

        } else {

            return false;

        }

        unset($conexionPDO);


    }

    function cargarCursos2($tipo) {

        $cursos = [];

        $conexionPDO = realizarConexion();
        $sql = "SELECT * FROM listacursos WHERE tipoCurso = '$tipo'";
        $stmt = $conexionPDO->query($sql);

        while($curso = $stmt->fetch()){

            array_push($cursos, $curso);

        }

        return $cursos;

    }

    function prueba($curso) {

        $cursos = [];

        $conexionPDO = realizarConexion();
        $sql = "SELECT cursos.*, empresas.nombre FROM cursos INNER JOIN empresas ON cursos.idempresa = empresas.idempresa AND Curso = '$curso' AND estadoCurso = 'interesado'";
        $stmt = $conexionPDO->query($sql);

        while($curso = $stmt->fetch()){

            array_push($cursos, $curso);

        }

        return $cursos;


    }

    function listadoCursos() {

        $cursos = [];

        $conexionPDO = realizarConexion();
        $sql = "SELECT * FROM listacursos";
        $stmt = $conexionPDO->query($sql);

        while($curso = $stmt->fetch()){

            array_push($cursos, $curso);

        }

        return $cursos;


    }

    function borrarCurso($id) {

        $cursos = [];

        $conexionPDO = realizarConexion();
        $sql = "DELETE FROM cursos WHERE Codigo= ?";
        $stmt = $conexionPDO->prepare($sql);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();
    }
?>