<?php

    include "funciones/conexionBD.php";
    include "funciones/funcionesEmpresa.php";

    session_start();

    if(empty($_SESSION)){

        header("Location: index.php");

    }

    date_default_timezone_set("Europe/Madrid");
    setlocale(LC_ALL, "spanish");

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

            <a class="navbar-brand" href="inicio.php"><img src="images/logo.gif" id="logo" class="img-fluid" style="width: 200px; heigth: 50px"></a>

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
            <h2 class="text-center mt-2 pt-2 pb-3 mb-md-5 mb-3 border border-5 rounded" style="background-color: #b0d588; letter-spacing: 7px;">CREAR FACTURA</h2>
                <?php if(isset($_GET["N_Accion"]) and
                isset($_GET["N_Grupo"]) and
                isset($_GET["Ano"])){
                    if($empresas = buscarPorAccionGrupoAno($_GET["N_Accion"], $_GET["N_Grupo"], $_GET["Ano"])){?>
                    <form method="GET" action="administracion_facturaPDF.php">
                        <div class="container-fluid">
                            <div class="row d-flex justify-content-center">
                                <div class="form-group col-md-6 col-12 text-center">
                                        <label class="form-label"><b>Empresa:</b></label>
                                        <input type="hidden" class="form-control" name="N_Accion" value="<?php echo $_GET["N_Accion"] ?>"></input>
                                        <input type="hidden" class="form-control" name="N_Grupo" value="<?php echo $_GET["N_Grupo"] ?>"></input>
                                        <input type="hidden" class="form-control" name="Ano" value="<?php echo $_GET["Ano"] ?>"></input>
                                        <select class="form-control col-md-4 col-12" name="idEmpresa">
                                            <?php foreach($empresas as $empresa){?>
                                                <option value="<?php echo $empresa['idempresa']; ?>"><?php echo $empresa['nombre']; ?></option>
                                            <?php } ?>
                                        </select>
                                        <input type="submit" class="btn btn-primary mb-3 mt-3 col-12 col-md-12" style="background-color: #1e989e" value="Buscar">
                                </div>
                            </div>
                        </div>
                    </form>
                    <?php
                    }else{
                        echo "<div class='alert alert-danger' role='alert'>No se encontraron empresas para este grupo.</div>";
                    }
                ?>

                <?php }else{?>
                    <form method="GET">
                        <div class="container-fluid">
                            <div class="row d-flex justify-content-center">
                                <div class="form-group col-md-6 col-12 text-center">
                                        <label class="form-label"><b>№ Accion:</b></label>
                                        <input type="number" class="form-control" name="N_Accion"></input>
                                        <label class="form-label"><b>№ Grupo:</b></label>
                                        <input type="number" class="form-control" name="N_Grupo"></input>
                                        <label class="form-label"><b>Año:</b></label>
                                        <input type="number" class="form-control" name="Ano" value="<?php echo Date("Y"); ?>"></input>
                                        <input type="submit" class="btn btn-primary mb-3 mt-3 col-12 col-md-12" style="background-color: #1e989e" value="Buscar">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            <?php } ?>
        </div>
    </div>

    <footer class="border-top border-secondary" style="background-color:#e4e4e4; height: 75px;">

            <p class="text-center mt-md-4" style='color: #8fd247;'> <b> © Dixma Formación 2022. | Ctra. Madrid 152, Vigo 36318 | info@dixmaformacion.com | Tlf: +34 604 067 035 </b> </p>

    </footer>

</body>
</html>