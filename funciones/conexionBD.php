<?php

    function realizarConexion(){

        $dbusuario = 'usercrmdixma';
        $dbpass= 'Madix0309*';


        //$dbusuario = 'usercrmdixma';
        //$dbpass= 'Madix0309';
        //dbname=crmdixma

            try {

               $conexionPDO = new PDO('mysql:host=localhost;dbname=crmdixma;charset=UTF8', $dbusuario, $dbpass);
            } catch (PDOException $ex) {
    
                echo $ex->getMessage();

            }
            return $conexionPDO;
    };

    function eliminarConexion($conexion){

        try {

           unset($conexion);

        } catch (PDOException $ex) {

            echo $ex->getMessage();

        }   
    };
    function formattedDate($date, $format = "d/m/Y"){
        if($date == "0000-00-00" or $date == NULL or $date == "0001-01-01" or $date == "1970-01-01"){
            return "";
        }

        return date($format, strtotime($date));
    }
?>