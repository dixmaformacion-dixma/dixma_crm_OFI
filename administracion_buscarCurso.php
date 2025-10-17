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

        $listaCursos = listaCursos($_GET['tipoCurso']);

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
    <script src="js/botonEliminar.js"></script>
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

            <div class="collapse navbar-collapse justify-content-center"  id="navbarSupportedContent">

                <div class="navbar-nav nav-pills">

                    <a class="nav-link" href="inicio.php" aria-current="page"><b> Call Center </b></a>
                    <a class="nav-link active text-bg-secondary" href="administracion.php"><b> Administracion </b></a>
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

            <div class="col-md-2 col-12 align-items-start text-justify" style="background-color:#e4e4e4;">
                <nav class="navbar-nav nav-pills flex-column mt-2 mb-2">
                    <a class="nav-link" href="buscarVenta.php"> <img class="ms-3" src="images/iconos/file-earmark-plus.svg"> <b> Insertar venta </b></a>
                    <a class="nav-link" href="asignarCitas.php"> <img class="ms-3" src="images/iconos/check-circle.svg"> <b> Asignar cita </b></a>
                    <a class="nav-link active text-bg-secondary" href="usuarios.php"> <img class="ms-3" src="images/iconos/person.svg"> <b> Usuarios </b></a>
                    <a class="nav-link" href="empresas.php"> <img class="ms-3" src="images/iconos/building.svg"> <b> Eliminar empresas </b></a>
                    <a class="nav-link" href="listadoVentas.php"> <img class="ms-3" src="images/iconos/list.svg"> <b> Listado ventas </b></a>
                    <a class="nav-link" href="administracion_crearContrato.php"> <img class="ms-3" src="images/iconos/filetype-pdf.svg"> <b> Crear contrato </b></a>
                </nav> 
            </div>

            <div class="col-md-10 col-10" id="formBusquedaCursos">
            
                <h2 class="text-center mt-2 pt-2 pb-3 mb-md-2 mb-2 border border-5 rounded" style="background-color: #b0d588; letter-spacing: 7px;">CURSOS</h2>

                <form method="GET">

                    <label class="form-label">Tipo de curso:</label>
                    <select class="form-select" name="tipoCurso">
                        <option value="TPC">TPC</option>
                        <option value="TPM">TPM</option>
                        <option value="TPCMADERA">TPC MADERA</option>
                        <option value="TPCVIDREO">TPC VIDREO</option>
                        <option value="OTROS">OTROS</option>
                    </select>

                    <button class="btn btn-success" name="consultar" value="">Enviar</button>

                </form>

            </div>


                <?php

                    if(!empty($listaCursos)){

                        echo "<script>";
                        echo "$('#formBusquedaCursos').remove();";
                        echo "</script>";

                        echo "<div class='col-md-10 col-12 table-responsive'>";
                        echo "<h2 class='text-center mt-2 pt-2 pb-3 mb-md-2 mb-2 border border-5 rounded' style='background-color: #b0d588; letter-spacing: 7px;'>CURSOS</h2>";

                        echo "<button class='btn btn-primary'>AGREGAR NUEVO CURSO</button>";
                        echo "<table class='table table-striped table-bordered table-sm text-center mt-2 align-middle'>";

                        echo "<tr style='background-color: #8fd247;'>";
                        echo "<th> ID </th>";
                        echo "<th> Nombre </th>";
                        echo "<th> Tipo </th>";
                        echo "<th> Horas </th>";
                        echo "<th></th>";
                        echo "</tr>";

                        for($i=0; $i < count($listaCursos); $i++){

                            echo "<tr>";

                            echo "<td>" . $listaCursos[$i]['idCurso'] .  "</td>";
                            echo "<td>" . $listaCursos[$i]['nombreCurso'] .  "</td>";
                            echo "<td>" . $listaCursos[$i]['tipoCurso'] .  "</td>";
                            echo "<td>" . $listaCursos[$i]['horasCurso'] .  "</td>";

                            echo "<td> <button type='button' class='btn btn-danger' onclick='eliminarUsuario(" . $listaCursos[$i]['idCurso'] . ")'>Eliminar <img src='images/iconos/x-circle.svg' class='ml-5'> </button> </td>";

                            echo "<td></td>";

                            echo "</tr>";

                        }

                    echo "</table>";
                    echo "</div>";

                    }
                    ?>
            
        </div>

    </div>

    <footer class="border-top border-secondary" style="background-color:#e4e4e4; height: 75px;">

            <p class="text-center mt-md-4" style='color: #8fd247;'> <b> © Dixma Formación 2022. | Ctra. Madrid 152, Vigo 36318 | info@dixmaformacion.com | Tlf: +34 604 067 035 </b> </p>

    </footer>

</body>
</html>