<?php

    include "funciones/conexionBD.php";
    include "funciones/funcionesCursos.php";
    include "funciones/crearJSON.php";

    session_start();

    if(empty($_SESSION)){

        header("Location: index.php");

    }

    date_default_timezone_set("Europe/Madrid");
    setlocale(LC_ALL, "spanish");
    $fechaHoy = date('d-m-Y');
    $horaActual = date('H:i');

    if(isset($_POST['insertar']) && $_SERVER['REQUEST_METHOD'] == 'POST'){

        $datosCurso = [
            'nombreCurso' => $_POST['nombreCurso'],
            'tipoCurso' => $_POST['tipoCurso'],
            'horasCurso' => $_POST['horasCurso'],
        ];

        if(crearCurso($datosCurso)){

            echo "<div class='alert alert-success'>El curso se inserto correctamente</div>";

        }

    }


?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es" lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Call Center</title>
    <link href="css/bootstrap.min.css" rel="stylesheet"></link>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/arrayCursos.js"></script>
    <link rel="icon" href="images/favicon.ico">

</head>
<body style="background-color:#f3f6f4;">

    <!-- Menu cabecera -->

    <?php 
        $menuaction = 'callcenter';
        require_once './template-parts/header/menu_top.php' 
    ?>

    <!-- Menu lateral y formulario -->

    <div class="container-fluid">

        <div class="row">

            <?php require_once './template-parts/leftmenu/callcenter.template.php'; ?>

            <div class="col-md-10 col-12" id="datosEmpresa">

                <h2 class="text-center mt-2 pt-2 pb-3 mb-md-2 mb-3 border border-5 rounded" style="background-color: #b0d588; letter-spacing: 7px;">NUEVO CURSO</h2>

                <div class="container-fluid">

                    <div class="row d-flex justify-content-center">

                        <form class="col-12" method="POST">

                            <div id="formNuevoCurso">

                                <div class="row">
                                    <div class="col-md-9 col-12">
                                        <label class="form-label"><b>Tipo de curso:</b></label>
                                            <select class="form-select" name="tipoCurso" id="selectTipoCurso">
                                                <option hidden="true" selected>--- Seleccione ---</option>
                                                <?php foreach(getTiposCursos() as $tipo): ?>
                                                <option value="<?php echo $tipo['codigo'] ?>"><?php echo $tipo['nombre'] ?></option>
                                                <?php endforeach ?>
                                            </select>
                                    </div>

                                    <div class="col-3">
                                        <label class="form-label"><b>Horas:</b></label>
                                        <input class="form-control" type="text" name="horasCurso"></input>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-12 col-12">
                                        <label class="form-label"><b>Curso:</b></label>
                                        <input name="nombreCurso" type="text" class="form-control"></input>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-12 col-12">
                                    <input class="btn btn-primary form-control mt-5 mb-5" type="submit" name="insertar" value="Insertar"></input>
                                </div>
                            </div>

                        </form>

                    </div>

                </div>

            </div>

    <footer class="border-top border-secondary" style="background-color:#e4e4e4; height: 75px;">

            <p class="text-center mt-md-4" style='color: #8fd247;'> <b> © Dixma Formación 2022. | Ctra. Madrid 152, Vigo 36318 | info@dixmaformacion.com | Tlf: +34 604 067 035 </b> </p>

    </footer>
    
</body>
</html>