<?php

    include "funciones/conexionBD.php";
    include "funciones/funcionesEmpresa.php";

    session_start();

    if(empty($_SESSION)){

        header("Location: index.php");

    }

    date_default_timezone_set("Europe/Madrid");
    setlocale(LC_ALL, "spanish");

    if(isset($_GET['consultar']) && $_SERVER['REQUEST_METHOD'] == 'GET'){

        if(empty($_GET['valor'])){

            echo "<div class='alert alert-danger' role='alert'> El campo de busqueda no puede estar vacio </div>";

        } else {

            if($empresas = buscarEmpresas($_GET['valor'])){

               

            } else {

                echo "<div class='alert alert-danger' role='alert'>No se encuentra ninguna empresa, <a href='nuevaEmpresa.php?nombreEmpresa=" . $_GET['valor'] . "'>AGREGAR NUEVA</a></div>";

            }

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
    <script src="js/botonConsultar.js"></script>
    <script src="js/crearPDF.js"></script>
    <link rel="icon" href="images/favicon.ico">
</head>
<body style="background-color:#f3f6f4;">

    <!-- Menu cabecera -->

    <nav class="navbar navbar-expand-lg justify-content-center border-bottom border-secondary" style="background-color:#e4e4e4;">

        <div class="container-fluid">

            <a class="navbar-brand" href="inicio.php"><img src="images/logo.gif" id="logo" class="img-fluid" style="width: 200px; height: 50px"></a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-center"  id="navbarSupportedContent">

                <div class="navbar-nav nav-pills">

                    <a class="nav-link" href="inicio.php" aria-current="page"><b> Call Center </b></a>

                <?php

                    if($_SESSION['rol'] == "admin"){

                    echo "<a class='nav-link active text-bg-secondary' href='administracion.php'><b> Administracion </b></a>";

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
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" dropdown-link-active-color="black">
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

            <?php require_once("template-parts/leftmenu/administracion.template.php"); ?>

            <div class="col-md-10 col-12" id="formBusqueda">

                <form method="GET">

                    <h2 class="text-center mt-2 pt-2 pb-3 mb-md-5 mb-3 border border-5 rounded" style="background-color: #b0d588; letter-spacing: 7px;">BUSCADOR DE EMPRESAS</h2>

                    <div class="container-fluid">

                        <div class="row d-flex justify-content-center">

                            <div class="form-group col-md-4 col-12 text-center">

                                <!--<label class="form-label">Inserta <b>Numero / Nombre</b> de la empresa:</label>-->
                                <label class="form-label"><b>Nombre / Numero / ID / Correo</b> de la empresa:</label>
                                <input type="text" class="form-control col-10 col-md-3" name="valor"></input>
                                <input type="submit" class="btn mb-3 mt-3 col-12 col-md-12" style="background-color: #1e989e" value="Buscar" name="consultar">

                            </div>

                        </div>

                    </div>

                </form>

            </div>

                <?php

                    if(!empty($empresas)){

                        echo "<script>";
                        echo "$('#formBusqueda').remove();";
                        echo "</script>";

                        echo "<div class='col-md-10 col-12'>";

                        echo "<h2 class='text-center mt-2 pt-2 pb-2 border border-5' style='background-color: #b0d588; letter-spacing: 7px;'>CREAR CONTRATO</h2>";

                        for($i=0; $i < count($empresas); $i++){

                        echo "<div class='container-fluid border rounded mt-3 mb-3 border-5' id='datosEmpresa'>";

                        echo "<div class='border row mt-2 mb-3 mx-2' style='background-color: #e8f0f7;'>";
                            echo "<h5 class='col-md-4 col-4 my-md-auto'> ID:" . $empresas[$i]['idempresa'] . "</h5>";
                            echo "<h5 class='col-md-8 col-8 my-md-2'>" . $empresas[$i]['nombre'] . "</h5>";
                        echo "</div>";
                        
                        echo "<div class='row mx-auto my-2'>";
                            echo "<label class='col-md-4 col-12'> <b>CIF:</b> " . $empresas[$i]['cif'] . "</label>";
                            echo "<label class='col-md-4 col-12'> <b>Crédito:</b> " . $empresas[$i]['credito'] . "€</label>";
                            echo "<label class='col-md-4 col-12'> <b>Nº Empleados:</b> " . $empresas[$i]['numeroempleados'] . "</label>";
                        echo "</div>";

                        echo "<div class='row mx-auto my-2'>";
                            echo "<label class='col-md-4 col-12'> <b>Calle:</b> " . $empresas[$i]['calle'] . "</label>";
                            echo "<label class='col-md-4 col-12'> <b>Codigo postal:</b> " . $empresas[$i]['cp'] . "</label>";
                            echo "<label class='col-md-4 col-12'> <b>Provincia:</b> " . $empresas[$i]['provincia'] . "<b> Poblacion:</b> " . $empresas[$i]['poblacion'] .  "</label>";
                            //echo "<label class='col-md-4 col-12'> <b>Poblacion:</b> " . $empresas[$i]['poblacion'] . "</label>";
                        echo "</div>";

                        echo "<div class='row mx-auto my-2'>";
                            echo "<label class='col-md-4 col-12'> <b>Telefono:</b> " . $empresas[$i]['telef1'] . "</label>";
                            echo "<label class='col-md-4 col-12'> <b>Telefono 2:</b> " . $empresas[$i]['telef2'] . "</label>";
                            echo "<label class='col-md-4 col-12'> <b>Telefono 3:</b> " . $empresas[$i]['telef3'] . "</label>";
                        echo "</div>";

                        echo "<div class='row mx-auto my-2'>";
                            echo "<label class='col-md-4 col-12'> <b>Email:</b> " . $empresas[$i]['email'] . "</label>";
                            echo "<label class='col-md-4 col-12'> <b>Persona contacto:</b> " . $empresas[$i]['personacontacto'] . "</label>";
                            echo "<label class='col-md-4 col-12'> <b>Cargo persona contacto:</b> " . $empresas[$i]['cargo'] . "</label>";
                        echo "</div>";

                        echo "<div class='row mx-auto my-2'>";
                            echo "<label class='col-md-12 col-12 mb-3'> <b>Observaciones:</b> " . $empresas[$i]['observacionesempresa'] . "</label>";
                        echo "</div>";


                        echo "<div class='row justify-content-center mx-auto'>";
                            echo "<div class='col-md-5 col-12 mb-2'><button type='button' class='btn w-100' style='background-color: #8fd247;' onclick='crearPDF(" . $empresas[$i]['idempresa'] . ")'>Crear Contrato <img src='images/iconos/file-earmark-pdf.svg' class='ms-2'> </button></div>";
                            echo "<div class='col-md-5 col-12 mb-2'><button type='button' class='btn w-100' style='background-color: #8fd247;' onclick='crearRLT(" . $empresas[$i]['idempresa'] . ")'>Crear RLT <img src='images/iconos/file-earmark-pdf.svg' class='ms-2'> </button></div>";
                        echo "</div>";

                        echo "</div>";

                    }

                    echo "</div>";

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