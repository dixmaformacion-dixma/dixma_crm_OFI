<?php

    include "funciones/conexionBD.php";
    include "funciones/funcionesEmpresa.php";
    include "funciones/funcionesLlamadas.php";
    include "funciones/funcionesCursos.php";
    include "funciones/funcionesCitas.php";
    include "funciones/cargarComerciales.php";

    session_start();

    if(empty($_SESSION)){

        header("Location: index.php");

    }

    date_default_timezone_set("Europe/Madrid");
    setlocale(LC_ALL, "spanish");
    $fechaHoy = date('d-m-Y');
    $horaActual = date('H:i');

    if(isset($_POST['insertar']) && $_SERVER['REQUEST_METHOD'] == 'POST'){

        if(isset($_POST['guardaCredito'])){

            if($_POST['guardaCredito'] == "No"){

                $_POST['creditoGuardado'] = "NO";
            }

        }

        $datosEmpresa = [
            'nombreEmpresa' => $_POST['nombreEmpresa'],
            'CIF' => $_POST['CIF'],
            'personaContacto' => $_POST['personaContacto'],
            'cargoPersonaContacto' => $_POST['cargoPersonaContacto'],
            'email' => $_POST['email'],
            'email2' => $_POST['email2'],
            'creditoVigente' => $_POST['creditoVigente'],
            'creditoAnhoAnterior' => $_POST['creditoAnhoAnterior'],
            'nEmpleados' => $_POST['nEmpleados'],
            'calle' => $_POST['calle'],
            'codigoPostal' => $_POST['codigoPostal'],
            'provincia' => $_POST['provincia'],
            'poblacion' => $_POST['poblacion'],
            'sector' => $_POST['sector'],
            'pais' => "ESP",
            'telefono' => $_POST['telefono'],
            'telefono2' => $_POST['telefono2'],
            'telefono3' => $_POST['telefono3'],
            'observacionesEmpresa' => $_POST['observacionesEmpresa'],
            'creditoGuardado' => $_POST['creditoGuardado'],
            'creditoCaducar' => $_POST['creditoCaducar'],

        ];

        if(actualizarEmpresa($datosEmpresa, $_GET['idEmpresa'])){

            echo "<div class='alert alert-success mb-0'> Empresa actualizada con exito </div>";

        } else {

            echo "<div class='alert alert-danger mb-0'> ERROR: No se pudo actualizar </div>";

        }

        if(isset($_POST['estadoLlamada'])){

            $fechaCita = "";
            $fechaLlamada = "";
            $horaCita = "";
            $horaLlamada = "";
            $operador = $_SESSION['codigoUsuario'];
            
            $observaciones = $_POST['observacionesOtros'];
            
            if(isset($_POST['pedirCita'])){

                $observaciones = "pedirCita";

            }

            if(isset($_POST['seguimiento'])){

                $observaciones = "seguimiento";

            }

            if(isset($_POST['fechaCita'])){

                $horaCita = $_POST['horaCita'];
                $fechaCita = date('d-m-Y', strtotime($_POST['fechaCita']));
            
            } 
            
            if(isset($_POST['fechaLlamada'])){

                $horaLlamada = $_POST['horaLlamada'];
                $fechaLlamada = date('d-m-Y', strtotime($_POST['fechaLlamada']));

            }

            if(empty($_POST['nulos'])){

                $_POST['nulos'] = "";

            }

            $datosLlamada = [
                'interlocutor' => $_POST['interlocutor'],
                'observacionesInterlocutor' => $_POST['observacionesInterlocutor'],
                'fechaActual' => $fechaHoy,
                'horaActual' => $horaActual,
                'estadoLlamada' => $_POST['estadoLlamada'],
                'fechaCita' => $fechaCita,
                'horaCita' => $horaCita,
                'fechaPendiente' => $fechaLlamada,
                'horaPendiente' => $horaLlamada,
                'nulos' => $_POST['nulos'],
                'observacionesOtros' => $observaciones,
                'operador' => $operador
            ];

            if(insertarNuevaLlamada($datosLlamada, $_GET['idEmpresa'])){

                echo "<div class='alert alert-success mb-0'> Llamada insertada con exito </div>";
    
            } else {
    
                echo "<div class='alert alert-danger mb-0'> ERROR: No se pudo inserta la llamada </div>";
    
            }

            if($_POST['estadoLlamada'] == "cita"){

                $idNuevaLlamada = cogerIDNuevaLlamada();

                setlocale(LC_ALL, "Spanish_Traditional_Sort");
                $diaCita = strftime('%A', strtotime($fechaCita));

                $datosCita = [
                    'diaCita' => $diaCita,
                    'fechaCita' => $fechaCita,
                    'horaCita' => $_POST['horaCita'],
                    'operador' => $_SESSION['codigoUsuario'],
                    'idllamada' => $idNuevaLlamada,

                ];

                insertarNuevaCita($datosCita, $_GET['idEmpresa']);

            }

        }

        if($_POST['tipoCurso'] != '--- Seleccione ---'){

            $datoCurso = [
                'tipoCurso' => $_POST['tipoCurso'],
                'curso' => $_POST['curso'],
            ];

            if(insertarNuevoCurso($datoCurso, $_GET['idEmpresa'])){

                echo "<div class='alert alert-success mb-0'> Curso insertado con exito </div>";

            } else {

                echo "<div class='alert alert-danger mb-0'> ERROR: No se pudo inserta la llamada </div>";

            }

        }

        if($_GET['tipo'] == 'seguimiento'){

            actualizarSeguimientoCallCenter($_GET['idLlamada']);

        } else {

            actualizarLlamadaCallCenter($_GET['idLlamada']);

        }

        header('Refresh: 1; URL=inicio.php');

    };
    
    if($empresa = cargarEmpresa($_GET['idEmpresa'])){

    }

    if($listadoLlamadas = listadoLlamadas($_GET['idEmpresa'])){

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
    <script src="js/arrayProvincias.js"></script>
    <script src="js/arraySector.js"></script>
    <script src="js/nulosOtros.js"></script>
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

            <div class="collapse navbar-collapse justify-content-center"  id="navbarSupportedContent">

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

            <div class="col-md-2 col-12 align-items-start text-justify" style="background-color:#e4e4e4;">
                <nav class="navbar-nav nav-pills flex-column mt-2 mb-2">
                    <a class="nav-link active text-bg-secondary" href="buscarEmpresa.php"> <img class="ms-3" src="images/iconos/search.svg"> <b> Insertar / Buscar </b></a>
                    <a class="nav-link" href="pendientes.php"> <img class="ms-3" src="images/iconos/exclamation-triangle.svg"> <b> Pendientes </b></a>
                    <a class="nav-link" href="listado.php"> <img class="ms-3" src="images/iconos/list.svg"> <b> Listado </b></a>
                    <a class="nav-link" href="sectores.php"> <img class="ms-3" src="images/iconos/briefcase.svg"> <b> Sectores </b></a>
                    <a class="nav-link" href="control_llamadas.php"> <img class="ms-3" src="images/iconos/telephone.svg"> <b> Control de llamadas </b></a>
                    <a class="nav-link" href="citas.php"> <img class="ms-3" src="images/iconos/calendar-day.svg"> <b> Citas </b></a>
                    <a class="nav-link" href="listadoCitas.php"> <img class="ms-3" src="images/iconos/calendar-date.svg"> <b> Listado de Citas </b></a>
                    <a class="nav-link" href="cursosInteresados.php"> <img class="ms-3" src="images/iconos/book.svg"> <b> Cursos interesados </b></a>

                <?php 
                    
                    if($_SESSION['codigoUsuario'][0] == "2"){

                        echo "<hr class='border border-dark'>";
                        echo "<a class='nav-link' href='pedirCita.php'> <img class='ms-3' src='images/iconos/calendar-plus.svg'> <b> Pedir Cita </b></a>";
                        echo "<a class='nav-link' href='hacerSeguimiento.php'> <img class='ms-3' src='images/iconos/box-arrow-in-right.svg'> <b> Hacer seguimiento </b></a>";

                    }

                    if($_SESSION['codigoUsuario'][0] == "1"){

                        echo "<hr class='border border-dark'>";
                        echo "<a class='nav-link' href='Callcenter_crearCurso.php'> <img class='ms-3' src='images/iconos/book.svg'> <b> Crear curso </b></a>";

                    }

                ?>

                </nav> 
            </div>

            <div class="col-md-10 col-12" id="datosEmpresa">

                <h2 class="text-center mt-2 pt-2 pb-3 mb-md-2 mb-3 border border-5 rounded" style="background-color: #b0d588; letter-spacing: 7px;">DATOS DE LA EMPRESA</h2>

                <div class="container-fluid">

                    <div class="row d-flex justify-content-center">

                        <form class="col-12" method="POST">

                            <input value="<?php echo $_GET['tipo'] ?>" hidden> </input>

                            <input name="idEmpresa" value="<?php echo $_GET['idEmpresa'] ?>" hidden></input>

                            <div class="row">
                                <div class="col-md-8 col-12">
                                    <label><b>Nombre empresa:</b></label>
                                    <input class="form-control" name="nombreEmpresa" value="<?php echo $empresa['nombre'] ?>" required></input>
                                </div>

                                <div class="col-md-4 col-12">
                                    <label><b>CIF:</b></label>
                                    <input class="form-control" name="CIF" value="<?php echo $empresa['cif'] ?>"></input>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2 col-12">
                                    <label><b>Credito vigente:</b></label>
                                    <input class="form-control" name="creditoVigente" type="number" value="<?php echo $empresa['credito'] ?>"></input>
                                </div>

                                <div class="col-md-2 col-12">
                                    <label><b>Credito año anterior:</b></label>
                                    <input class="form-control" name="creditoAnhoAnterior" type="number" value="<?php echo $empresa['credito'] ?>"></input>
                                </div>

                                <div class="col-md-3 col-12">
                                    <label><b>Credito guardado:</b></label>

                                    <label>Si:</label>
                                    <input type="radio" id="guardaCredito" name="guardaCredito" checked>

                                    <label>No:</label>
                                    <input type="radio" id="guardaCreditoNo" name="guardaCredito" value="No">

                                    <input class="form-control" id="cajaGuardaCredito" value="<?php echo $empresa['creditoGuardado'] ?>" name="creditoGuardado" type="text"></input>
                                </div>

                                <div class="col-md-3 col-12">        
                                    <label><b>Importe credito a caducar:</b></label>
                                    <input class="form-control" name="creditoCaducar" type="text" value="<?php echo $empresa['creditoCaducar'] ?>"></input>
                                </div>

                                <div class="col-md-2 col-12">        
                                    <label><b>Nº Empleados:</b></label>
                                    <input class="form-control" name="nEmpleados" type="number" value="<?php echo $empresa['numeroempleados'] ?>"></input>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5 col-12">
                                    <label><b>Calle:</b></label>
                                    <input class="form-control text-uppercase" name="calle" value="<?php echo $empresa['calle'] ?>" required></input>
                                </div>

                                <div class="col-md-2 col-12">
                                    <label><b>Provincia:</b></label> <br>
                                        <select class="form-select" name="provincia" id="selectProvincia" required>
                                            <option hidden="true" selected> <?php echo $empresa['provincia'] ?> </option>
                                            <option value="Pontevedra">Pontevedra</option>
                                            <option value="Orense">Orense</option>
                                            <option value="Lugo">Lugo</option>
                                            <option value="Coruña">Coruña</option>
                                        </select>
                                </div>

                                <div class="col-md-3 col-12">
                                    <label><b>Poblacion:</b></label> <br>
                                        <select class="form-select" name="poblacion" id="selectPoblacion" required>
                                            <option hidden="true" selected> <?php echo $empresa['poblacion'] ?> </option>
                                        </select>
                                </div>

                                <div class="col-md-2 col-12">
                                    <label><b>Codigo postal:</b></label>
                                    <input class="form-control" name="codigoPostal" value="<?php echo $empresa['cp'] ?>" required></input>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 col-12">
                                    <label><b>Telefono:</b></label>
                                    <input class="form-control" name="telefono" value="<?php echo $empresa['telef1'] ?>" required></input>
                                </div>

                                <div class="col-md-4 col-12">
                                    <label><b>Telefono 2:</b></label>
                                    <input class="form-control" name="telefono2" value="<?php echo $empresa['telef2'] ?>"></input>
                                </div>

                                <div class="col-md-4 col-12">
                                    <label><b>Telefono 3:</b></label>
                                    <input class="form-control" name="telefono3" value="<?php echo $empresa['telef3'] ?>" maxlength="9"></input>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <label><b>Email:</b></label>
                                    <input class="form-control" name="email" value="<?php echo $empresa['email'] ?>"></input>
                                </div>

                                <div class="col-md-6 col-12">
                                    <label><b>Email 2:</b></label>
                                    <input class="form-control" name="email2" value="<?php echo $empresa['email2'] ?>"></input>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <label><b>Persona de contacto:</b></label>
                                    <input class="form-control" name="personaContacto" value="<?php echo $empresa['personacontacto'] ?>"></input>
                                </div>

                                <div class="col-md-6 col-12">
                                    <label><b>Cargo persona de contacto:</b></label>
                                    <input class="form-control" name="cargoPersonaContacto" value="<?php echo $empresa['cargo'] ?>"></input>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-12">
                                    <label><b>Sector:</b></label>
                                    <select class="form-select" name="sector" id="sectores">
                                        <option hidden="true" selected> <?php echo $empresa['sector'] ?> </option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-12">
                                    <label><b>Observaciones:</b></label>
                                    <textarea class="form-control" name="observacionesEmpresa"><?php echo $empresa['observacionesempresa']?></textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-12">
                                    <h5 class="text-center mt-2 pt-2 pb-2 border border-5 rounded" style="background-color: #b0d588;"> ¿INSERTAR NUEVA LLAMADA? 

                                        <label class="form-check-label">Si</label>
                                        <input type="radio" id="nuevaLlamada" name="nuevaLlamada" class="form-check-input"></input>
                                        <label class="form-check-label">No</label>
                                        <input id="nuevaLlamadaNo" type="radio" name="nuevaLlamada" class="form-check-input" checked></input>

                                    </h5>
                                </div>
                            </div>

                            <div id="formNuevaLlamada" hidden="true">

                                <div class="row">
                                    <div class="col-md-12 col-12">
                                        <label><b>Interlocutor:</b></label>
                                        <input id="interlocutorLlamada" class="form-control" name="interlocutor"></input>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 col-12">
                                        <label><b>Observaciones interlocutor:</b></label>
                                        <textarea class="form-control" name="observacionesInterlocutor"></textarea>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 col-12">
                                        <h4 class="text-center">Estado llamada:</h4>
                                        <div class="row d-flex justify-content-center">
                                            <div class="col-12 col-md-5 border border-warning border-2 my-atuo mx-auto">
                                                <label class="form-check-label"><b>Llamada pendiente:</b></label>
                                                <input type="radio" class="form-check-input" name="estadoLlamada" value="pendiente" id="pendiente">
                                                <input type="date" class="form-control mb-2" name="fechaLlamada" id="fechaLlamada" disabled>
                                                <input type="text" class="form-control mb-2" name="horaLlamada" id="horaLlamada" disabled>
                                            </div>

                                        
                                            <div class="col-12 col-md-5 border border-success border-2 my-atuo mx-auto">
                                                <label class="form-check-label"><b>Cita:</b></label>
                                                <input type="radio" class="form-check-input" name="estadoLlamada" value="cita" id="cita">
                                                <input type="date" class="form-control mb-2" name="fechaCita" id="fechaCita" disabled>
                                                <input type="time" class="form-control mb-2" name="horaCita" id="horaCita" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row d-flex justify-content-center">
                                    <div class="col-md-11 col-12 border border-2 border-danger mt-2">
                                        <label class="form-label"><b>Nulos:</b></label>
                                        <input type="radio" class="form-check-input" id="formNulos" name="estadoLlamada">
                                            <select class="form-select mb-2" name="nulos" id="nulos" disabled>
                                                <option hidden="true" selected>--- Selecione ---</option>
                                                <option value="hanGastadoCredito">Han gastado credito</option>
                                                <option value="loRealizanConOtraEmpresa">La realizan con otra empresa</option>
                                                <option value="autonomos">Autonomos</option>
                                                <option value="noLesInteresa">No les interesa</option>
                                                <option value="telefonoNoExiste">Telefono no existe</option>
                                                <option value="noConsienteTratamientoDeSusDatos">No consiente tratamiento de sus datos</option>
                                                <option value="otros">Otros</option>
                                            </select>
                                        <textarea class="form-control mt-2 mb-2" name="observacionesOtros" hidden="true" id="areaNulos"></textarea>
                                    </div>
                                </div>

                            <?php

                                if($_SESSION['codigoUsuario'] == "103"){

                                    $comerciales = cargarComerciales();

                                    echo "<div class='row d-flex justify-content-center mt-md-2'>";
                                        echo "<div class='col-md-5 col-12'>";
                                            echo "<label><b>Pedir cita:</b></label>";
                                            echo "<input type='radio' class='form-check-input ms-md-1' name='estadoLlamada' id='pasarCita'>";
                                            echo "<select class='form-select' name='pedirCita' id='selectPedirCita' disabled>";

                                        for($i=0; $i < count($comerciales); $i++){

                                            if($comerciales[$i]['codigousuario'][0] == "2"){

                                                echo "<option hidden='true'></option>";
                                                echo "<option value='" . $comerciales[$i]['codigousuario'] . "'>" . $comerciales[$i]['nombre'] . "</option>";

                                            }

                                        }
                                            echo "</select>";
                                        echo "</div>";

                                        echo "<div class='col-md-5 col-12'>";
                                            echo "<label><b>Hacer seguimiento:</b></label>";
                                            echo "<input type='radio' class='form-check-input ms-md-1' name='estadoLlamada' id='hacerSeguiminento'>";
                                            echo "<select class='form-select' name='seguimiento' id='selectHacerSeguimiento' disabled>";

                                        for($i=0; $i < count($comerciales); $i++){

                                            if($comerciales[$i]['codigousuario'][0] == "2"){

                                                echo "<option hidden='true'></option>";
                                                echo "<option value='" . $comerciales[$i]['codigousuario'] . "'>" . $comerciales[$i]['nombre'] . "</option>";
                                                
                                            }

                                        }
                                            echo "</select>";
                                        echo "</div>";
                                    echo "</div>";

                                }

                            ?>

                            </div>

                            <div class="row">
                                <div class="col-md-12 col-12">
                                    <h5 class="text-center mt-2 pt-2 pb-2 border border-5 rounded" style="background-color: #b0d588;"> ¿INSERTAR NUEVO CURSO? 

                                        <label class="form-check-label">Si</label>
                                        <input type="radio" id="nuevoCurso" name="nuevoCurso" class="form-check-input"></input>
                                        <label class="form-check-label">No</label>
                                        <input id="nuevoCursoNo" type="radio" name="nuevoCurso" class="form-check-input" checked></input>

                                    </h5>
                                </div>
                            </div>

                            <div id="formNuevoCurso" hidden="true">

                                <div class="row">
                                    <div class="col-md-12 col-12">
                                        <label class="form-label"><b>Tipo de curso:</b></label>
                                            <select class="form-select" name="tipoCurso" id="selectTipoCurso">
                                                <option hidden="true" selected>--- Seleccione ---</option>
                                                <option value="tpm">TPM</option>
                                                <option value="tpc">TPC</option>
                                                <option value="tpcMadera">TPC MADERA Y MUEBLE</option>
                                                <option value="tpcVidreo">TPC VIDREO Y ROTULACIÓN</option>
                                            </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 col-12">
                                        <label class="form-label"><b>Curso:</b></label>
                                            <select class="form-select" name="curso" id="selectCurso">
                                                <option hidden="true" selected>--- Seleccione ---</option>
                                            </select>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-12 col-12">
                                    <input class="btn btn-primary form-control mt-5 mb-5" type="submit" name="insertar" value="Insertar"></input>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-12">
                                    <h5 class='text-center pt-2 pb-2 border border-5 rounded' style="background-color: #b0d588; letter-spacing: 7px;">LISTADO LLAMADAS ANTERIORES: </h5>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-12">
                                    <?php

                                        if(!empty($listadoLlamadas)){

                                            echo "<div>";

                                            for($i=0; $i < count($listadoLlamadas); $i++){

                                                echo "<br> <br>";

                                                echo "<div class='container-fluid border rounded mt-2 mb-3 border-5'>";

                                                echo "<div class='col mt-2'>";
                                                echo "<label class='col-12 text-end'> <b>Realizada por:</b> " . $listadoLlamadas[$i]['recibidopor'] . "</label>";
                                                echo "</div>";

                                                echo "<div class='col mt-2'>";
                                                echo "<label class='col-12 col-md-5' text-center'> <b>Fecha:</b> " . $listadoLlamadas[$i]['fecha'] . "</label>";
                                                echo "<label class='col-12 col-md-4'> <b>Hora:</b> " . $listadoLlamadas[$i]['hora'] . "</label>";
                                                echo "<label class=' text-center'> <b>Interlocutor:</b> " . $listadoLlamadas[$i]['interlocutor'] . "</label>";
                                                echo "</div>";

                                                echo "<div class='col mt-2'>";
                                                echo "<label class='col-12 col-md-5'> <b>Est. de llamada:</b> " . $listadoLlamadas[$i]['estadollamada'] . "</label>";
                                                echo "<label class='col-12 col-md-4'> <b>Cita:</b> " . $listadoLlamadas[$i]['fechacita'] . " " . $listadoLlamadas[$i]['horacita'] . "</label>";
                                                echo "<label class='col-12 col-md-3'> <b>Fecha pendiente:</b> " . $listadoLlamadas[$i]['fechapendiente'] . " " . $listadoLlamadas[$i]['horapendiente'] . "</label>";
                                                echo "</div>";

                                                //echo "<div class='col mt-2'>";
                                                //echo "<label class='col-12 col-md-5'> <b>Curso interesado:</b> " . $listadoLlamadas[$i]['Curso'] . "</label>";
                                                //echo "</div>";

                                                echo "<div class='col-12 mt-2'>";
                                                echo "<label class='col-12 mb-3'> <b>Observaciones interlocutor:</b> " . $listadoLlamadas[$i]['observacionesinterlocutor'] . "</label>";
                                                echo "</div>";

                                                echo "</div>";

                                            }

                                            echo "</div>";

                                        }

                                    ?>
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