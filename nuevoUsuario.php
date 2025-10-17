<?php

    include "funciones/conexionBD.php";
    include "funciones/funcionesUsuarios.php";


    session_start();

    if(empty($_SESSION)){

        header("Location: index.php");

    }

    date_default_timezone_set("Europe/Madrid");
    setlocale(LC_ALL, "spanish");

    if(isset($_POST['insertar']) && $_SERVER['REQUEST_METHOD'] == 'POST'){

        $datosUsuario = [
            'nombre' => $_POST['nombreusuario'],
            'password' => $_POST['password'],
            'tipo' => $_POST['tipoUsuario'],
            'codigo' => $_POST['codigoUsuario'],
        ];

        nuevoUsuario($datosUsuario);

        header('Refresh: 1; URL=usuarios.php');

    };
    

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
    <script src="js/nulosOtros.js"></script>
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
                <a class="nav-link" href="administracion_crearCurso.php"> <img class="ms-3" src="images/iconos/book.svg"> <b> Crear curso </b></a>

        </nav> 
    </div>

    <div class="col-md-10 col-12" id="datosEmpresa">

        <h2 class="text-center mt-2 pt-2 pb-3 mb-md-2 mb-3 border border-5" style="background-color: #b0d588; letter-spacing: 7px;">NUEVO USUARIO</h2>

        <div class="container-fluid">

            <div class="row d-flex justify-content-center">

                <form class="col-12" method="POST">

                <?php 
                
                $codigoAdmin = ultimoAdministrador();
                $codigoComercial = ultimoComercial();
                $codigoCallCenter = ultimoCallCenter();
                
                ?>

                <input name="admin" id="admin" value="<?php echo ($codigoAdmin[0] + 1) ?>" hidden="true"></input>
                <input name="admin" id="comercial" value="<?php echo ($codigoComercial[0] + 1) ?>" hidden="true"></input>
                <input name="admin" id="callcenter" value="<?php echo ($codigoCallCenter[0] + 1) ?>" hidden="true"></input>

                    <div class="row">

                        <div class="col-md-6 col-12">
                            <label><b>Nombre usuario:</b></label>
                            <input class="form-control" name="nombreusuario" required></input>
                        </div>

                        <div class="col-md-6 col-12">
                            <label><b>Contraseña:</b></label>
                            <input class="form-control" name="password" required></input>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6 col-12">
                            <label><b>Tipo:</b></label>
                            <select name="tipoUsuario" id="tipoUsuario" class="form-select" required>
                                <option hidden="true" selected></option>
                                <option value="admin" id="tipoUsuario">Administrador</option>
                                <option value="comercial" id="tipoUsuario">Comercial</option>
                                <option value="callcenter" id="tipoUsuario">Call center</option>
                                <option value="otros" id="tipoUsuario">Otros</option>
                            </select>
                        </div>

                        <div class="col-md-6 col-12">
                            <label><b>Codigo usuario:</b></label>
                            <input class="form-control" id="codigoUsuario" name="codigoUsuario" required></input>
                        </div>

                    </div>                    


                    <div class="row">
                        <div class="col-md-12 col-12">
                            <input class="btn form-control mt-5 mb-5" style="background-color: #1e989e" type="submit" name="insertar" value="Insertar"></input>
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