<?php
function insertarNuevoAlumno($datosAlumno){

        $conexionPDO = realizarConexion();
        $sql = "INSERT INTO alumnos (nombre, apellidos, telefono, email, fechaNacimiento, nif, numeroSeguridadSocial, categoriaProfesional, colectivo, grupoCotizacion, nivelEstudios, costeHora, horarioLaboral, idEmpresa, sexo, discapacidad) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conexionPDO->prepare($sql);

        if($stmt){

            $stmt->bindValue(1, $datosAlumno['nombre'], PDO::PARAM_STR);
            $stmt->bindValue(2, $datosAlumno['apellidos'], PDO::PARAM_STR);
            $stmt->bindValue(3, $datosAlumno['telefono'], PDO::PARAM_STR);
            $stmt->bindValue(4, $datosAlumno['email'], PDO::PARAM_STR);
            $stmt->bindValue(5, $datosAlumno['fechaNacimiento'], PDO::PARAM_STR);
            $stmt->bindValue(6, $datosAlumno['nif'], PDO::PARAM_STR);
            $stmt->bindValue(7, $datosAlumno['numeroSeguridadSocial'], PDO::PARAM_STR);
            $stmt->bindValue(8, $datosAlumno['categoriaProfesional'], PDO::PARAM_STR);
            $stmt->bindValue(9, $datosAlumno['colectivo'], PDO::PARAM_STR);
            $stmt->bindValue(10, $datosAlumno['grupoCotizacion'], PDO::PARAM_STR);
            $stmt->bindValue(11, $datosAlumno['nivelEstudios'], PDO::PARAM_STR);
            $stmt->bindValue(12, $datosAlumno['costeHora'], PDO::PARAM_STR);
            $stmt->bindValue(13, $datosAlumno['horarioLaboral'], PDO::PARAM_STR);
            $stmt->bindValue(14, $datosAlumno['idEmpresa'], PDO::PARAM_INT);
            $stmt->bindValue(15, $datosAlumno['sexo'], PDO::PARAM_STR);
            $stmt->bindValue(16, $datosAlumno['discapacidad'], PDO::PARAM_STR);


            unset($conexionPDO);
            if($stmt->execute()){
                return true;
            }else{
                print_r($stmt->errorInfo());
                return false;
            }

        } else {

            return false;

        }
        
}
function editarAlumno($datosAlumno, $idAlumno){

    $conexionPDO = realizarConexion();
    $sql = "UPDATE alumnos SET nombre = ?, apellidos = ?, telefono = ?, email = ?, fechaNacimiento = ?, nif = ?, numeroSeguridadSocial = ?, categoriaProfesional = ?, colectivo = ?, grupoCotizacion = ?, nivelEstudios = ?, costeHora = ?, horarioLaboral = ?, idEmpresa = ?, sexo = ?, discapacidad = ? WHERE idAlumno = ?";
    $stmt = $conexionPDO->prepare($sql);

    if($stmt){

        $stmt->bindValue(1, $datosAlumno['nombre'], PDO::PARAM_STR);
        $stmt->bindValue(2, $datosAlumno['apellidos'], PDO::PARAM_STR);
        $stmt->bindValue(3, $datosAlumno['telefono'], PDO::PARAM_STR);
        $stmt->bindValue(4, $datosAlumno['email'], PDO::PARAM_STR);
        $stmt->bindValue(5, $datosAlumno['fechaNacimiento'], PDO::PARAM_STR);
        $stmt->bindValue(6, $datosAlumno['nif'], PDO::PARAM_STR);
        $stmt->bindValue(7, $datosAlumno['numeroSeguridadSocial'], PDO::PARAM_STR);
        $stmt->bindValue(8, $datosAlumno['categoriaProfesional'], PDO::PARAM_STR);
        $stmt->bindValue(9, $datosAlumno['colectivo'], PDO::PARAM_STR);
        $stmt->bindValue(10, $datosAlumno['grupoCotizacion'], PDO::PARAM_STR);
        $stmt->bindValue(11, $datosAlumno['nivelEstudios'], PDO::PARAM_STR);
        $stmt->bindValue(12, $datosAlumno['costeHora'], PDO::PARAM_STR);
        $stmt->bindValue(13, $datosAlumno['horarioLaboral'], PDO::PARAM_STR);
        $stmt->bindValue(14, $datosAlumno['idEmpresa'], PDO::PARAM_INT);
        $stmt->bindValue(15, $datosAlumno['sexo'], PDO::PARAM_STR);
        $stmt->bindValue(16, $datosAlumno['discapacidad'], PDO::PARAM_STR);
        $stmt->bindValue(17, $idAlumno, PDO::PARAM_INT);


        unset($conexionPDO);
        if($stmt->execute()){
            return true;
        }else{
            print_r($stmt->errorInfo());
            return false;
        }

    } else {

        return false;

    }
}

    function buscarAlumno($valor){

        $conexionPDO = realizarConexion();
        $sql = "SELECT * FROM alumnos WHERE apellidos LIKE '%".$valor."%' OR nif = '$valor' OR email = '$valor' OR telefono = '$valor'";
        $stmt = $conexionPDO->query($sql);

        if($alumno = $stmt->fetch()){

            unset($conexionPDO);
            return $alumno;

        } else {

            return false;

        }

    }
    function buscarAlumnos($valor){

        $conexionPDO = realizarConexion();
        $sql = "SELECT * FROM alumnos WHERE apellidos LIKE '%".$valor."%' OR nif = '$valor' OR email = '$valor' OR telefono = '$valor'";
        $stmt = $conexionPDO->query($sql);

        if($alumno = $stmt->fetchAll()){

            unset($conexionPDO);
            return $alumno;

        } else {

            return false;

        }

    }
    function buscarAlumnosPorIDEmpresa($id){

        $conexionPDO = realizarConexion();
        $sql = "SELECT * FROM alumnos WHERE idEmpresa = '$id'";
        $stmt = $conexionPDO->query($sql);

        if($alumno = $stmt->fetchAll()){

            unset($conexionPDO);
            return $alumno;

        } else {

            return false;

        }

    }

    //moves student with idAlumno to company with idEmpresa
    function moveCompany($idAlumno, $idEmpresa){
        $conexionPDO = realizarConexion();
        $sql = "UPDATE `alumnos` SET `idEmpresa`='$idEmpresa' WHERE `idAlumno` = '$idAlumno'";
        $stmt = $conexionPDO->query($sql);

        $count = $stmt->rowCount();

        if($count =='0'){
            return false;
        }
        else{
            return true;
        }
    }
    function checkWithNIF($valor){
        $conexionPDO = realizarConexion();
        $sql = "SELECT * FROM alumnos WHERE nif = '$valor'";
        $stmt = $conexionPDO->query($sql);

        if($alumno = $stmt->fetch()){
            unset($conexionPDO);
            return $alumno;
        } else {
            return false;
        }
    }
    function todoAlumno(){

        $conexionPDO = realizarConexion();
        $sql = "SELECT * FROM alumnos";
        $stmt = $conexionPDO->query($sql);

        if($alumno = $stmt->fetchAll()){

            unset($conexionPDO);
            return $alumno;

        } else {

            return false;

        }

    }
    function todoAlumnoPorEmpresa($idEmpresa){

        $conexionPDO = realizarConexion();
        $sql = "SELECT * FROM alumnos where idEmpresa=".$idEmpresa;
        $stmt = $conexionPDO->query($sql);

        if($alumno = $stmt->fetchAll()){

            unset($conexionPDO);
            return $alumno;

        } else {

            return false;

        }

    }

    function cargarAlumno($id){

        $conexionPDO = realizarConexion();
        $sql = "SELECT * FROM alumnos WHERE idAlumno = $id";
        $stmt = $conexionPDO->query($sql);

        if($alumno = $stmt->fetch()){

            unset($conexionPDO);
            return $alumno;

        } else {

            return false;

        }

    }
    function eliminarAlumno($idAlumno) {
  
        $conexionPDO = realizarConexion();
        $sql = "DELETE FROM `alumnos` WHERE `idAlumno` = '$idAlumno'";
       
        $stmt= $conexionPDO->prepare($sql);
        return $stmt->execute();
    }
?>