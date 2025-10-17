<?php

include "funciones/conexionBD.php";
include "funciones/funcionesCursos.php";

    session_start();

    if(empty($_SESSION)){

        header("Location: index.php");

    }

    date_default_timezone_set("Europe/Madrid");
    setlocale(LC_ALL, "spanish");

    if(isset($_GET['consultar']) && $_SERVER['REQUEST_METHOD'] == 'GET'){

        if(empty($_GET['tipoCurso'])){

            echo "<div class='alert alert-danger' role='alert'> El Tipo de curso no puede estar vacio </div>";

        } else {

            if($cursos = cargarCursos2( $_GET['tipoCurso'])){


            } else {

                echo "<div class='d-flex alert alert-danger' role='alert'>No se encuentra ningun curso</a></div>";

            }

        }

    } else if(isset($_GET['buscarCurso']) && $_SERVER['REQUEST_METHOD'] == 'GET') {

        $curso = $_GET['curso'];
        header("Location: listaCursosInteresados.php?curso=$curso");

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
    <script src="js/botonDesinteresado.js"></script>
    <script src="js/botonConsultar.js"></script>
    <script src="js/arrayCursos.js"></script>
    <link rel="icon" href="images/favicon.ico">

</head>
<body style="background-color:#f3f6f4;">

    <!-- Menu cabecera -->

    <nav class="navbar navbar-expand-lg justify-content-center border-bottom border-secondary" style="background-color:#e4e4e4;">

        <div class="container-fluid">

            <a class="navbar-brand" href="inicio.php"><img src="images/logo.gif" id="logo" class="img-fluid" style="width: 200px; heigth: 50px"></a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">

                <div class="navbar-nav nav-pills">

                    <a class="nav-link active text-bg-secondary" href="inicio.php" aria-current="page"><b> Call Center </b></a>

                <?php

                    if($_SESSION['rol'] == "admin"){

                    echo "<a class='nav-link' href='administracion.php'><b> Administracion </b></a>";

                    }

                ?>

                    <a class="nav-link" href="comercial.php"><b> Comercial </b></a>

                <?php

                    if($_SESSION['rol'] == "admin" || $_SESSION['codigoUsuario'][0] == "3"){

                    echo "<a class='nav-link' href='tutoria.php'><b> Tutoria </b></a>";

                    }

                ?>

                    <a class="nav-link disabled me-5" href=""><b> Estadisticas </b></a>
                    
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <b> <?php echo $_SESSION['usuario'] ?> </b>
                        </a>

                        <div class="dropdown-menu" style="background-color: #e4e4e4">
                            <a class="dropdown-item " href="perfilUsuario.php"><b> Perfil </b></a>
                            <hr class="dropdown-divider">
                            <a class="dropdown-item " href="funciones/cerrarSesion.php"><b> Cerrar sesion </b></a>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </nav>

    <!-- Menu lateral y formulario -->

    <div class="container-fluid">

        <div class="row">

            <?php require_once './template-parts/leftmenu/callcenter.template.php'; ?>

                <div class="col-md-10 col-12" id="formCursos">

                    <form method="GET">

                        <h2 class="text-center mt-2 pt-2 pb-3 mb-md-5 mb-3 border border-5 rounded" style="background-color: #b0d588; letter-spacing: 7px;">CURSOS INTERESADOS</h2>

                        <div class="container-fluid">

                            <div class="row d-flex justify-content-center">

                                <div class="form-group col-12 col-md-4 text-center">
                                        
                                    <label class="form-label"><b>Tipo curso:</b></label>
                                    <select class="form-select" name="tipoCurso" id="selectTipoCurso" required>
                                        <option hidden="true" selected></option>
                                        <option value="TPM">TPM</option>
                                        <option value="TPC">TPC</option>
                                        <option value="TPCMADERA">TPC MADERA Y MUEBLE</option>
                                        <option value="TPCVIDREO">TPC VIDREO Y ROTULACIÓN</option>
                                        <option value="OTROS">OTROS</option>
                                    </select>

                                </div>

                                <div class="row d-flex justify-content-center">

                                    <input type="submit" class="btn mb-3 mt-3 col-12 col-md-4" style="background-color: #1e989e" value="Buscar" name="consultar">

                                </div>
                                    
                            </div>

                        </div>

                    </form>

                </div>

                <?php

                    if(!empty($cursos)){

                        echo "<script>";
                        echo "$('#formCursos').remove();";
                        echo "</script>";

                        echo "<div class='col-md-10 col-12 mt-2 table-responsive'>";

                        echo " <h2 class='text-center mt-2 pt-2 pb-3 mb-md-3 mb-3 border border-5 rounded' style='background-color: #b0d588; letter-spacing: 7px;'>CURSOS INTERESADOS</h2>";

                        echo "<div class='row d-flex justify-content-center'>";
                        echo "<div class='form-group col-12 col-md-4 text-center'>";
                        echo "<form method='GET'>";

                        echo "<label class='form-label'><b>Curso:</b></label>";
                        echo "<select class='form-select' name='curso'>";
                        
                        for($i=0; $i < count($cursos); $i++) {

                            echo "<option value='" . $cursos[$i]['nombreCurso'] . "'>" . $cursos[$i]['nombreCurso'] . "</option>";

                        }

                        echo "</select>";

                        echo "<input type='submit' class='btn mb-3 mt-3 col-12 col-md-12' style='background-color: #1e989e' value='Buscar' name='buscarCurso'>";

                        echo "</form>";
                        echo "</div>";

                        echo "</div>";

                    }
                        
                ?>


        </div>

    </div>

    <div class="container-fluid">

        <div class="row">

            <footer class="col-12 border-top border-secondary" style="background-color:#e4e4e4;">

                <p class="text-center mt-md-4" style='color: #8fd247;'> <b> © Dixma Formación 2022. | Ctra. Madrid 152, Vigo 36318 | info@dixmaformacion.com | Tlf: +34 604 067 035 </b> </p>

            </footer>
        
        </div>

    </div>

</body>
</html>