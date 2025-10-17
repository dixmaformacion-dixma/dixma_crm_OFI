<?php

    include "funciones/conexionBD.php";
    include "funciones/funcionesAlumnos.php";
    include "funciones/funcionesAlumnosCursos.php";
    include "funciones/funcionesCursos.php";

    session_start();

    if(empty($_SESSION)){

        header("Location: index.php");

    }

    date_default_timezone_set("Europe/Madrid");
    setlocale(LC_ALL, "spanish");

    if(isset($_GET['idEmpresa']) and isset($_GET['idAlumno'])){
        if(moveCompany($_GET['idAlumno'], $_GET['idEmpresa'])){
            header("Location: tutoria_insertarCurso.php?valor=".$_GET['idAlumno']);
        }else{
            echo "<div class='alert alert-danger mb-0'> Error de la base de datos: SQL UPDATE UNSUCCESSFUL</div>";
            die("Error with sql query");
        }
    }

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es" lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutoria</title>
    <link href="css/bootstrap.min.css" rel="stylesheet"></link>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/tutoria.js"></script>
    <link rel="icon" href="images/favicon.ico">
</head>
<body style="background-color:#f3f6f4;">

    <?php require_once("template-parts/header/header.template.php"); ?>

    <!-- Menu lateral y formulario -->

    <div class="container-fluid">

        <div class="row">

            <?php require_once("template-parts/leftmenu/tutoria.template.php"); ?>

            <div class="col-md-10 col-12" id="formBusqueda">

            <?php
            if(isset($_GET['idEmpresa']) and isset($_GET['nif'])){
                if($alumno = checkWithNIF($_GET['nif'])){
                    if($alumno['idEmpresa'] == $_GET['idEmpresa']){
                        echo "<div class='alert alert-danger mb-0'> ERROR: tal estudiante con esta empresa ya existe</div>";
                        require("template-parts/components/alumno.insertarCurso.php");
                    }else{
                        echo "<div class='alert alert-danger mb-0'> ERROR: dicho alumno existe en la base de datos pero está asociado a otra empresa</div>";
                        require("template-parts/components/alumno.insertarCurso.php");
                        echo '<a type="button" class="btn col-md-5 col-12 mx-auto mb-2" style="background-color: #1e989e;" href="tutoria_NIFcheck.php?idEmpresa='.$_GET['idEmpresa'].'&idAlumno='.$alumno['idAlumno'].'">Mover a este estudiante a esta empresa. (El estudiante ya no estará asociado con su antigua empresa)</a>';
                    }
                }
                else{
                        echo '<script type="text/javascript">
                            window.location = "tutoria_insertarAlumno.php?idEmpresa='.$_GET['idEmpresa'].'&nif='.$_GET['nif'].'";
                            </script>';
                }
            }else{
                ?>
                <form method="GET">
                <h2 class="text-center mt-2 pt-2 pb-3 mb-md-5 mb-3 border border-5 rounded" style="background-color: #b0d588; letter-spacing: 7px;">BUSCADOR DE ALUMNOS</h2>

                <div class="container-fluid">

                    <div class="row d-flex justify-content-center">

                        <div class="form-group col-md-4 col-12 text-center">

                            <!--<label class="form-label">Inserta <b>Numero / Nombre</b> de la empresa:</label>-->
                            <label class="form-label"><b>NIF</label>
                            <input type="hidden" name="idEmpresa" value="<?php echo $_GET['idEmpresa']; ?>"></input>
                            <input type="text" class="form-control col-10 col-md-3" name="nif"></input>
                            <input type="submit" class="btn mb-3 mt-3 col-12 col-md-12" style="background-color: #1e989e">

                        </div>

                    </div>

                </div>

                </form>
                <?php
            }
            ?>
            
            </div>
        </div>

    </div>

    <footer class="border-top border-secondary" style="background-color:#e4e4e4; height: 75px;">

            <p class="text-center mt-md-4" style='color: #8fd247;'> <b> © Dixma Formación 2022. | Ctra. Madrid 152, Vigo 36318 | info@dixmaformacion.com | Tlf: +34 604 067 035 </b> </p>

    </footer>

</body>
</html>