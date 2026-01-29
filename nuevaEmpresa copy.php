<?php

    include "funciones/conexionBD.php";
    include "funciones/funcionesEmpresa.php";
    include "funciones/funcionesLlamadas.php";
    include "funciones/funcionesCursos.php";
    include "funciones/funcionesCitas.php";

    session_start();

    if(empty($_SESSION)){

        header("Location: index.php");

    }

    date_default_timezone_set("Europe/Madrid");
    $fechaHoy = date('d-m-Y');
    $horaActual = date('H:i');

    if(isset($_POST['insertar']) && $_SERVER['REQUEST_METHOD'] == 'POST'){

        if(empty($_POST['creditoGuardado'])){

            $_POST['creditoGuardado'] = "";

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
            'fecha' => $fechaHoy,
            'creditoGuardado' => $_POST['creditoGuardado'],
            'creditoCaducar' => $_POST['creditoCaducar'],
            'codigoUsuario' => $_SESSION['codigoUsuario'],
        ];

        if(insertarNuevaEmpresa($datosEmpresa)){

            echo "<div class='alert alert-success mb-0'> Empresa insertada con exito </div>";

        } else {

            echo "<div class='alert alert-danger'>ERROR: Empresa repetida</div>";

        }

        $idEmpresa = cogerIDNuevaEmpresa();

        if(isset($_POST['estadoLlamada'])){

            $id = cogerIDNuevaEmpresa();

            $fechaCita = "";
            $fechaLlamada = "";

            if(!isset($_POST['fechaCita'])){

                $_POST['fechaCita'] = "";
                $_POST['horaCita'] = "";
                $fechaLlamada = date('d-m-Y', strtotime($_POST['fechaLlamada']));
                
            } else if(!isset($_POST['fechaLlamada'])){

                $_POST['fechaLlamada'] = "";
                $_POST['horaLlamada'] = "";
                $fechaCita = date('d-m-Y', strtotime($_POST['fechaCita']));
                
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
                'horaCita' => $_POST['horaCita'],
                'fechaPendiente' => $fechaLlamada,
                'horaPendiente' => $_POST['horaLlamada'],
                'nulos' => $_POST['nulos'],
                'observacionesOtros' => $_POST['observacionesOtros'],
                'operador' => $_SESSION['codigoUsuario']
            ];

            if(insertarNuevaLlamada($datosLlamada, $id)){

                echo "<div class='alert alert-success mb-0'> Llamada insertada con exito </div>";
    
            } else {
    
                echo "<div class='alert alert-danger mb-0'> ERROR: No se pudo inserta la llamada </div>";
    
            }

            if(strtolower($_POST['estadoLlamada']) == "cita"){

                $idNuevaLlamada = cogerIDNuevaLlamada();

                $diaCita = strftime('%A', strtotime($fechaCita));

                
                switch ($diaCita){
                    case 'Monday':
                        $diaCita = "Lunes";
                        break;

                    case 'Tuesday':
                        $diaCita = "Martes";
                        break;

                    case 'Wednesday':
                        $diaCita = "Miercoles";
                        break;

                    case 'Thursday':
                        $diaCita = "Jueves";
                        break;

                    case 'Friday':
                        $diaCita = "Viernes";
                        break;

                    case 'Saturday':
                        $diaCita = "Sabado";
                        break;

                    case 'Sunday':
                        $diaCita = "Domingo";
                        break;
                    
                }

                $datosCita = [
                    'diaCita' => $diaCita,
                    'fechaCita' => $fechaCita,
                    'horaCita' => $_POST['horaCita'],
                    'operador' => $_SESSION['codigoUsuario'],
                    'idllamada' => $idNuevaLlamada,

                ];

                insertarNuevaCita($datosCita, $id);

            }

        }

        if(!empty($_POST['nombreCurso'])){

            $id = cogerIDNuevaEmpresa();

            $datoCurso = [
                'tipoCurso' => $_POST['tipoCurso'],
                'curso' => $_POST['nombreCurso'],
                'horasCurso' => $_POST['horasCurso'],
            ];

            if(insertarNuevoCurso($datoCurso, $id)){

                echo "<div class='alert alert-success mb-0'> Curso insertado con exito </div>";

            } else {

                echo "<div class='alert alert-danger mb-0'> ERROR: No se pudo inserta la llamada </div>";

            }

 

        }

        
        header('Refresh: 1; URL=consultarEmpresa.php?idEmpresa=' . $idEmpresa . "&tipo=''");

    };

    
    if($listaCursos = listadoCursos()){

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
    <script src="js/selectCursos.js"></script>
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

    <div class="col-md-2 col-12 align-items-start text-justify" style="background-color:#e4e4e4;">
        <nav class="navbar-nav nav-pills flex-column mt-2 mb-2">
            <a class="nav-link active text-bg-secondary" href="buscarEmpresa.php"> <img class="ms-3" src="images/iconos/search.svg"> <b> Insertar / Buscar </b></a>
            <a class="nav-link" href="pendientes.php"> <img class="ms-3" src="images/iconos/exclamation-triangle.svg"> <b> Pendientes </b></a>
            <a class="nav-link" href="listado.php"> <img class="ms-3" src="images/iconos/list.svg"> <b> Listado </b></a>
            <a class="nav-link" href="sectores.php"> <img class="ms-3" src="images/iconos/briefcase.svg"> <b> Sectores </b></a>
            <a class="nav-link" href="control_llamadas.php"> <img class="ms-3" src="images/iconos/telephone.svg"> <b> Control de llamadas </b></a>
            <a class="nav-link" href="citas.php"> <img class="ms-3" src="images/iconos/calendar-day.svg"> <b> Citas </b></a>
            <a class="nav-link" href="listadoCitas.php"> <img class="ms-3" src="images/iconos/calendar-date.svg"> <b> Listado de Citas </b></a>
            <a class="nav-link" href="cursosInteresados.php"> <img class="ms-3" src="images/iconos/book.svg"> <b> Cursos / Eliminar </b></a>

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

        <h2 class="text-center mt-2 pt-2 pb-3 mb-md-2 mb-3 border border-5" style="background-color: #b0d588; letter-spacing: 7px;">DATOS DE LA EMPRESA</h2>

        <div class="container-fluid">

            <div class="row d-flex justify-content-center">

                <form class="col-12" method="POST" id="formNuevaEmpresa">

                    <div class="row">
                        <div class="col-md-8 col-12">
                            <label><b>Nombre empresa:</b></label>
                            <input class="form-control text-uppercase" value="<?php if(!is_numeric($_GET['nombreEmpresa'])){ echo $_GET['nombreEmpresa'];  }?>" name="nombreEmpresa" required></input>
                        </div>

                        <div class="col-md-4 col-12">
                            <label><b>CIF:</b></label>
                            <input class="form-control" name="CIF"></input>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2 col-12">
                            <label><b>Credito vigente:</b></label>
                            <input class="form-control" name="creditoVigente"></input>
                        </div>

                        <div class="col-md-2 col-12">
                            <label><b>Credito año anterior:</b></label>
                            <input class="form-control" name="creditoAnhoAnterior" type="number"></input>
                        </div>

                        <div class="col-md-3 col-12">
                            <label><b>Credito guardado:</b></label>

                            <label>Si:</label>
                            <input type="radio" id="guardaCredito" name="guardaCredito" checked>

                            <label>No:</label>
                            <input type="radio" id="guardaCreditoNo" name="guardaCredito" value="No">

                            <input class="form-control" id="cajaGuardaCredito" value="" name="creditoGuardado" type="text"></input>
                        </div>

                        <div class="col-md-3 col-12">        
                            <label><b>Importe credito a caducar:</b></label>
                            <input class="form-control" name="creditoCaducar" type="text"></input>
                        </div>

                        <div class="col-md-2 col-12">        
                            <label><b>Nº Empleados:</b></label>
                            <input class="form-control" name="nEmpleados"></input>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-5 col-12">
                            <label><b>Calle:</b></label>
                            <input class="form-control text-uppercase" name="calle" required></input>
                        </div>

                        <div class="col-md-2 col-12">
                            <label><b>Provincia:</b></label> <br>
                                <select class="form-select" name="provincia" id="selectProvincia" required>
                                    <option hidden="true" selected></option>
                                    <option value="Pontevedra">Pontevedra</option>
                                    <option value="Orense">Orense</option>
                                    <option value="Lugo">Lugo</option>
                                    <option value="Coruña">Coruña</option>
                                </select>
                        </div>

                        <div class="col-md-3 col-12">
                            <label><b>Poblacion:</b></label> <br>
                                <select class="form-select" name="poblacion" id="selectPoblacion" required>
                                    <option hidden="true" selected></option>
                                </select>
                        </div>

                        <div class="col-md-2 col-12">
                            <label><b>Codigo postal:</b></label>
                            <input class="form-control" name="codigoPostal" required></input>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-12">
                            <label><b>Telefono:</b></label>
                            <input class="form-control" value="<?php if(is_numeric($_GET['nombreEmpresa'])){ echo $_GET['nombreEmpresa'];  }?>" name="telefono" required></input>
                        </div>

                        <div class="col-md-4 col-12">
                            <label><b>Telefono 2:</b></label>
                            <input class="form-control" name="telefono2"></input>
                        </div>

                        <div class="col-md-4 col-12">
                            <label><b>Telefono 3:</b></label>
                            <input class="form-control" name="telefono3" maxlength="9"></input>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <label><b>Email:</b></label>
                            <input class="form-control" name="email"></input>
                        </div>

                        <div class="col-md-6 col-12">
                            <label><b>Email 2:</b></label>
                            <input class="form-control" name="email2" ></input>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <label><b>Persona de contacto:</b></label>
                            <input class="form-control" name="personaContacto"></input>
                        </div>

                        <div class="col-md-6 col-12">
                            <label><b>Cargo persona de contacto:</b></label>
                            <input class="form-control" name="cargoPersonaContacto" ></input>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-12">
                            <label><b>Sector:</b></label>
                            <select class="form-select" name="sector" id="sectores">
                                <option hidden="true" selected></option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-12">
                            <label><b>Observaciones:</b></label>
                            <textarea class="form-control" name="observacionesEmpresa"></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-12">
                            <h5 class="text-center mt-2 pt-2 pb-2 border border-5" style="background-color: #b0d588;"> ¿INSERTAR NUEVA LLAMADA? 

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

                    </div>

                    <div class="row">
                        <div class="col-md-12 col-12">
                            <h5 class="text-center mt-2 pt-2 pb-2 border border-5" style="background-color: #b0d588;"> ¿INSERTAR NUEVO CURSO? 

                                <label class="form-check-label">Si</label>
                                <input type="radio" id="nuevoCurso" name="nuevoCurso" class="form-check-input"></input>
                                <label class="form-check-label">No</label>
                                <input id="nuevoCursoNo" type="radio" name="nuevoCurso" class="form-check-input" checked></input>

                            </h5>
                        </div>
                    </div>

                    <div id="formNuevoCurso" hidden="true">
                    

                            
                                <div class="row">

                                    <div class="col-md-1 col-1">
                                    </div>

                                    <div class="col-md-2 col-4 text-center">
                                        <label class="form-label"><b>Tipo de curso:</b></label>
                                    </div>

                                    <div class="col-md-2 col-3 text-center">
                                        <label class="form-label"><b>Horas:</b></label>
                                    </div>

                                    <div class="col-md-7 col-4">
                                        <label class="form-label"><b>Curso:</b></label>
                                    </div>

                                </div>

                                <?php foreach([
                                    'TPM',
                                    'TPC',
                                    'TPCMADERA',
                                    'TPCVIDREO',
                                    'OTROS'
                                ] as $index=>$tipoCurso): ?>
                                <div class="row">

                                    <div class="col-md-1 col-1">
                                         <input type="radio" name="cursos" id="radio<?php echo $tipoCurso ?>" <?php echo $index==0?'checked':''?> ></input>
                                    </div>

                                    <div class="col-md-2 col-4">
                                        <input class="form-control readonly text-center" name="tipoCurso" id="<?php echo $tipoCurso ?>" value="<?php echo $tipoCurso ?>" <?php echo $index>0?'disabled':''?>>
                                    </div>
                                 
                                    <div class="col-md-2 col-3">
                                        <input class="form-control text-center" name="horasCurso" id="horas<?php echo $tipoCurso ?>" <?php echo $index>0?'disabled':''?>>
                                    </div>

                                    <div class="col-md-7 col-4">
                                        <select class="form-select mb-2" name="nombreCurso" id="select<?php echo $tipoCurso ?>" <?php echo $index>0?'disabled':''?>>
                                        <option hidden="true" selected></option>
                                            <?php 
                                            
                                            for($i=0; $i < count($listaCursos); $i++) {
                                                
                                                if($listaCursos[$i]['tipoCurso'] == $tipoCurso){

                                                    echo "<option>";
                                                    echo $listaCursos[$i]['nombreCurso'];
                                                    echo "</option>";

                                                }
                                                
                                            }

                                            ?>
 
                                        </select>
                                    </div>

                                </div>
                                <?php endforeach ?>



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