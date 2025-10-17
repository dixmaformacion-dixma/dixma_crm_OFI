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
    //setlocale(LC_ALL, "spanish");
    $fechaHoy = date('d-m-Y');
    $horaActual = date('H:i');

    if(isset($_GET['idEmpresa'])) {    
        $fechaInicio = @$_GET['fechaInicio'];
        $fechaFin = @$_GET['fechaFin'];
        $provincia = @$_GET['provincia'];
        $poblacion = @$_GET['poblacion'];
        $idempresa = $_GET['idEmpresa'];
        $params = "idEmpresa=".$idempresa."&fechaInicio=" . $fechaInicio . "&provincia=" . $provincia . "&fechaFin=" . $fechaFin . "&poblacion=" . $poblacion . "&consultar=Buscar";
    }

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
            'horario' => $_POST['horario'],
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
            'referencia'=>$_POST['referencia'],
            'codigo'=>$_POST['codigo']
            
        ];

        if(actualizarEmpresa($datosEmpresa, $_GET['idEmpresa'])){

            echo "<div class='alert alert-success mb-0'> Empresa actualizada con exito </div>";

        } else {

            echo "<div class='alert alert-danger mb-0'> ERROR: No se pudo actualizar </div>";

        }

        if(isset($_POST['estadoLlamada'])){

            $anoPedirCita = NULL;
            $mesPedirCita = NULL;

            $fechaCita = "";
            $fechaLlamada = "";
            $horaCita = "";
            $horaLlamada = "";
            $estadoLlamada = $_POST['estadoLlamada'];
            $usuario_seguimiento = NULL;
            $operador = $_SESSION['codigoUsuario'];

            if($_POST['estadoLlamada'] == "on" && !empty($_POST['nulos'])){

                $estadoLlamada = $_POST['nulos'];

            }

            $observaciones = $_POST['observacionesOtros'];
            
            if(isset($_POST['pedirCita'])){

                $nombre = nombreComercial($_POST['pedirCita']);
                $observaciones = "pedirCita";
                $estadoLlamada = "Pedir cita " . $nombre[0];
                $anoPedirCita = $_POST['anoPedirCita'];
                $mesPedirCita = $_POST['mesPedirCita'];

            }

            if(isset($_POST['seguimiento'])){

                $nombre = nombreComercial($_POST['seguimiento']);
                $usuario_seguimiento = $_POST['seguimiento'];
                $operador = $_POST['seguimiento'];
                $observaciones = "seguimiento";
                $estadoLlamada = "Hacer seguimiento " . $nombre[0]." ".@$_POST['tipo_seguimiento'];
                $fechaLlamada = date("d-m-Y",strtotime($_POST['fecha_seguimiento']));
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
                'estadoLlamada' => $estadoLlamada,
                'fechaCita' => $fechaCita,
                'horaCita' => $horaCita,
                'fechaPendiente' => $fechaLlamada,
                'horaPendiente' => $horaLlamada,
                'nulos' => $_POST['nulos'],
                'observacionesOtros' => $observaciones,
                'operador' => $operador,
                'anoPedirCita' => $anoPedirCita,
                'mesPedirCita' => $mesPedirCita,
                'codigo_llamada' => $_POST['codigo_llamada'],
                'fecha_seguimiento' => @$_POST['fecha_seguimiento'],
                'tipo_seguimiento' => @$_POST['tipo_seguimiento'],
                'usuario_seguimiento'=>$usuario_seguimiento
            ];

            if(insertarNuevaLlamada($datosLlamada, $_GET['idEmpresa'])){

                echo "<div class='alert alert-success mb-0'> Llamada insertada con exito </div>";
    
            } else {
    
                echo "<div class='alert alert-danger mb-0'> ERROR: No se pudo inserta la llamada </div>";
    
            }

            if($_POST['estadoLlamada'] == "cita"){

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

                if(empty($_POST['horaCita'])){

                    $horaCita = "";


                } else {

                    $horaCita = $_POST['horaCita'];

                }

                $datosCita = [
                    'diaCita' => $diaCita,
                    'fechaCita' => $fechaCita,
                    'horaCita' => $horaCita,
                    'operador' => $_SESSION['codigoUsuario'],
                    'idllamada' => $idNuevaLlamada,

                ];

                if(insertarNuevaCita($datosCita, $_GET['idEmpresa'])){

                    echo "<div class='alert alert-success mb-0'> Cita insertada con exito </div>";

                } else {

                    echo "<div class='alert alert-danger mb-0'> ERROR: No se pudo inserta la cita </div>";

                }

            }

        }

        if(!empty($_POST['nombreCurso'])){

            $datoCurso = [
                'tipoCurso' => $_POST['tipoCurso'],
                'curso' => $_POST['nombreCurso'],
                'horasCurso' => $_POST['horasCurso'],
            ];

            if(insertarNuevoCurso($datoCurso, $_GET['idEmpresa'])){

                echo "<div class='alert alert-success mb-0'> Curso insertado con exito </div>";

            } else {

                echo "<div class='alert alert-danger mb-0'> ERROR: No se pudo inserta la llamada </div>";

            }

 

        }

        if(isset($_GET['tipo']) && $_GET['tipo'] == "pendiente") {                                 
            header("Refresh: 1; URL=pendientes.php?{$params}");
        } else {

            //header('Refresh: 1; URL=inicio.php');

        }

    };
    
    if($empresa = cargarEmpresa($_GET['idEmpresa'])){

    }

    if($listadoLlamadas = listadoLlamadas($_GET['idEmpresa'])){

    }

    if(isset($_GET['borrarCurso'])){
        borrarCurso($_GET['borrarCurso']);
    }

    if($cursosInteresados = cursosInteresados($_GET['idEmpresa'])){

    }

   

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

    <?php 
    

    
    ?>

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

            <?php require_once './template-parts/leftmenu/callcenter.template.php'; ?>

            <div class="col-md-10 col-12" id="datosEmpresa">

                <h2 class="text-center mt-2 pt-2 pb-3 mb-md-2 mb-3 border border-5 rounded" style="background-color: #b0d588; letter-spacing: 7px;">DATOS DE LA EMPRESA</h2>

                <div class="container-fluid">

                    <div class="row d-flex justify-content-center">

                        <form class="col-12" method="POST">

                            <input name="idEmpresa" value="<?php echo $_GET['idEmpresa'] ?>" hidden></input>

                            <div class="row">
                                <div class="col-md-8 col-12">
                                    <label><b>Nombre empresa:</b></label>
                                    <input class="form-control text-uppercase" name="nombreEmpresa" value="<?php echo $empresa['nombre'] ?>" required></input>
                                </div>

                                <div class="col-md-4 col-12">
                                    <label><b>CIF:</b></label>
                                    <input class="form-control" name="CIF" value="<?php echo $empresa['cif'] ?>"></input>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2 col-12">
                                    <label><b>Credito vigente:</b></label>
                                    <input class="form-control" name="creditoVigente" type="text" value="<?php echo $empresa['credito'] ?>"></input>
                                </div>

                                <div class="col-md-2 col-12">
                                    <label><b>Credito año anterior:</b></label>
                                    <input class="form-control" name="creditoAnhoAnterior" type="text" value="<?php echo $empresa['creditoAnhoAnterior'] ?>"></input>
                                </div>

                                <div class="col-md-3 col-12">        
                                    <label><b>Importe crédito hace dos años :</b></label>
                                    <input class="form-control" name="creditoCaducar" type="text" value="<?php echo $empresa['creditoCaducar'] ?>"></input>
                                </div>

                                <div class="col-md-3 col-12">
                                    <label><b>Credito guardado:</b></label>
                                    <select name="creditoGuardado" id="" class="form-control">
                                        <option value=""> --- </option>
                                        <option value="NO" <?php echo $empresa['creditoGuardado']=='NO'?'selected="true"':'' ?>>NO</option>
                                        <option value="SI" <?php echo $empresa['creditoGuardado']=='SI'?'selected="true"':'' ?>>SI</option>
                                    </select>
                                    <input type="hidden" name="guardaCredito" value="">
                                    <!--<label>Si:</label>
                                    <input type="radio" id="guardaCredito" name="guardaCredito" checked>

                                    <label>No:</label>
                                    <input type="radio" id="guardaCreditoNo" name="guardaCredito" value="No">

                                    <input class="form-control" id="cajaGuardaCredito" value="<?php echo $empresa['creditoGuardado'] ?>" name="creditoGuardado" type="text"></input>-->

                                </div>

                                

                                <div class="col-md-2 col-12">        
                                    <label><b>Nº Empleados:</b></label>
                                    <input class="form-control" name="nEmpleados" value="<?php echo $empresa['numeroempleados'] ?>"></input>
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
                                <div class="col-md-4 col-12">
                                    <label><b>Email:</b></label>
                                    <input class="form-control" name="email" value="<?php echo $empresa['email'] ?>"></input>
                                </div>

                                <div class="col-md-4 col-12">
                                    <label><b>Email 2:</b></label>
                                    <input class="form-control" name="email2" value="<?php echo $empresa['email2'] ?>"></input>
                                </div>

                                <div class="col-md-4 col-12">
                                    <label><b>Horario:</b></label>
                                    <input class="form-control" name="horario" value="<?php echo $empresa['horario'] ?>"></input>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 col-12">
                                    <label><b>Persona de contacto:</b></label>
                                    <input class="form-control" name="personaContacto" value="<?php echo $empresa['personacontacto'] ?>"></input>
                                </div>

                                <div class="col-md-4 col-12">
                                    <label><b>Cargo persona de contacto:</b></label>
                                    <input class="form-control" name="cargoPersonaContacto" value="<?php echo $empresa['cargo'] ?>"></input>
                                </div>

                                <div class="col-md-4 col-12">
                                    <label><b>Referencia:</b></label>
                                    <input class="form-control" name="referencia" value="<?php echo $empresa['referencia'] ?>" list="referencias"></input>
                                    <datalist id="referencias">
                                        <?php foreach(getReferencias() as $ref): ?>
                                            <option value="<?php echo $ref ?>" />
                                        <?php endforeach ?>
                                    </datalist>
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
                                    <label for=""><b>CLIENTE EN AÑO</b></label>
                                    <input type="text" class="form-control" readonly value="<?php echo implode(', ',getAnnosEmpresaCliente($_GET['idEmpresa'])) ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-12">
                                    <label><b>Código:</b></label>
                                    <input id="codigo" class="form-control" name="codigo" value="<?php echo $empresa['codigo']?>"></input>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-12">
                                    <label><b>Observaciones:</b></label>
                                    <textarea class="form-control" name="observacionesEmpresa" rows="10"><?php echo $empresa['observacionesempresa']?></textarea>
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
                                    <div class="col-md-12 col-12 d-none">
                                        <label><b>Código:</b></label>
                                        <input id="codigo_llamada" class="form-control" name="codigo_llamada"></input>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 col-12">
                                        <h4 class="text-center">Estado llamada:</h4>
                                        <div class="row d-flex justify-content-center">
                                            <div class="col-12 col-md-5 border border-warning border-2 my-atuo mx-auto">
                                                <label class="form-check-label"><b>Llamada pendiente:</b></label>
                                                <input type="radio" class="form-check-input" name="estadoLlamada" value="pendiente" id="pendiente">
                                                <input type="date" class="form-control mb-2" name="fechaLlamada" value="<?php echo date('Y-m-d', strtotime($fechaHoy)) ?>" id="fechaLlamada" disabled>
                                                <input type="text" class="form-control mb-2" name="horaLlamada" id="horaLlamada" disabled>
                                            </div>

                                        
                                            <div class="col-12 col-md-5 mt-1 border border-success border-2 my-auto mx-auto">
                                                <label class="form-check-label"><b>Cita:</b></label>
                                                <input type="radio" class="form-check-input" name="estadoLlamada" value="cita" id="cita">
                                                <input type="date" class="form-control mb-2" value="<?php echo date('Y-m-d', strtotime($fechaHoy. "+ 1 day")) ?>" name="fechaCita" id="fechaCita" disabled>
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
                                //if(in_array($_SESSION['codigoUsuario'][0],[0,1,2])){

                                    $comerciales = cargarComerciales();

                                    echo "<div class='row d-flex justify-content-center mt-md-2'>";
                                        if(in_array($_SESSION['codigoUsuario'][0],[1,0])){
                                        echo "<div class='col-md-5 col-12'>";
                                            echo "<label><b>Pedir cita: </b></label>";
                                            echo "<input type='radio' class='form-check-input ms-md-1' name='estadoLlamada' id='pasarCita'>";
                                            echo "<select class='form-select' name='pedirCita' id='selectPedirCita' disabled>";

                                        for($i=0; $i < count($comerciales); $i++){

                                            if($comerciales[$i]['codigousuario'][0] == "2"){

                                                echo "<option value='" . $comerciales[$i]['codigousuario'] . "'>" . $comerciales[$i]['nombre'] . "</option>";

                                            }

                                        }
                                            echo "</select>";
                                            echo '<input type="number" class="form-control" name="anoPedirCita" id="anoPedirCita" value="'.Date("Y").'" disabled></input>';
                                            echo '
                                            <select name="mesPedirCita" id="mesPedirCita" class="form-control" disabled>
                                                <option value="1"> enero </option>
                                                <option value="2"> febrero </option>
                                                <option value="3"> marzo </option>
                                                <option value="4"> abril </option>
                                                <option value="5"> mayo </option>
                                                <option value="6"> junio </option>
                                                <option value="7"> julio </option>
                                                <option value="8"> agosto </option>
                                                <option value="9"> septiembre </option>
                                                <option value="10"> octubre </option>
                                                <option value="11"> noviembre </option>
                                                <option value="12"> diciembre </option>
                                            </select>
                                            ';
                                        echo "</div>";
                                        }

                                        echo "<div class='col-md-5 col-12'>";
                                            echo "<label><b>Hacer seguimiento: </b></label>";
                                            echo "<input type='radio' class='form-check-input ms-md-1' name='estadoLlamada' id='hacerSeguiminento'>";
                                            echo "<select class='form-select' name='seguimiento' id='selectHacerSeguimiento' disabled>";

                                        for($i=0; $i < count($comerciales); $i++){

                                            //if(in_array($_SESSION['codigoUsuario'][0],[2]) || $comerciales[$i]['codigousuario'][0] == "2"){

                                                echo "<option hidden='true'></option>";
                                                echo "<option value='" . $comerciales[$i]['codigousuario'] . "'>" . $comerciales[$i]['nombre'] . "</option>";
                                                
                                            //}

                                        }
                                            echo "</select>"; ?>
                                            <label><b>Fecha de Seguimiento: </b></label>
                                            <input type="date" name="fecha_seguimiento" class="form-control" disabled>
                                            <label><b>Tipo de llamada: </b></label>
                                            <input type="text" name="tipo_seguimiento" class="form-control" disabled>
                                        </div>
                                        <?php 
                                    echo "</div>";

                                //}

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

                                    <div class="col-md-1 col-1">
                                    </div>

                                    <div class="col-md-2 col-4 text-center">
                                        <label class="form-label"><b>Tipo de curso:</b></label>
                                    </div>

                                    <div class="col-md-2 col-3 text-center">
                                        <label class="form-label"><b>Alumnos / Duración:</b></label>
                                    </div>

                                    <div class="col-md-7 col-4">
                                        <label class="form-label"><b>Curso:</b></label>
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-md-1 col-1">
                                         <input type="radio" name="cursos" id="radioTPM" checked ></input>
                                    </div>

                                    <div class="col-md-2 col-4">
                                        <input class="form-control readonly text-center" name="tipoCurso" id="TPM" value="TPM">
                                    </div>
                                 
                                    <div class="col-md-2 col-3">
                                        <input class="form-control text-center" name="horasCurso" id="horasTPM">
                                    </div>

                                    <div class="col-md-7 col-4">
                                        <select class="form-select mb-2" name="nombreCurso" id="selectTPM">
                                        <option hidden="true" selected></option>
                                            <?php 
                                            
                                            for($i=0; $i < count($listaCursos); $i++) {
                                                
                                                if($listaCursos[$i]['tipoCurso'] == "TPM"){

                                                    echo "<option>";
                                                    echo $listaCursos[$i]['nombreCurso'];
                                                    echo "</option>";

                                                }
                                                
                                            }

                                            ?>
 
                                        </select>
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-md-1 col-1">
                                         <input type="radio" name="cursos" value="TPC" id="radioTPC"></input>
                                    </div>

                                    <div class="col-md-2 col-4">
                                        <input class="form-control readonly text-center" name="tipoCurso" id="TPC" value="TPC" disabled>
                                    </div>

                                    <div class="col-md-2 col-3">
                                        <input class="form-control text-center" name="horasCurso" id="horasTPC" disabled>
                                    </div>

                                    <div class="col-md-7 col-4">
                                        <select class="form-select mb-2" name="nombreCurso" id="selectTPC" disabled>
                                        <option hidden="true" selected></option>
                                            <?php 
                                            
                                            for($i=0; $i < count($listaCursos); $i++) {
                                                
                                                if($listaCursos[$i]['tipoCurso'] == "TPC"){

                                                    echo "<option>";
                                                    echo $listaCursos[$i]['nombreCurso'];
                                                    echo "</option>";

                                                }
                                                
                                            }

                                            ?>
 
                                        </select>
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-md-1 col-1">
                                         <input type="radio" name="cursos" id="radioTPCMADERA"></input>
                                    </div>

                                    <div class="col-md-2 col-4">
                                        <input class="form-control readonly text-center" name="tipoCurso" value="TPC MADERA" id="TPCMADERA" disabled>
                                    </div>

                                    <div class="col-md-2 col-3">
                                        <input class="form-control text-center" name="horasCurso" id="horasTPCMADERA" disabled>
                                    </div>

                                    <div class="col-md-7 col-4">
                                        <select class="form-select  mb-2" name="nombreCurso" id="selectTPCMADERA" disabled>
                                        <option hidden="true" selected></option>
                                            <?php 
                                            
                                            for($i=0; $i < count($listaCursos); $i++) {
                                                
                                                if($listaCursos[$i]['tipoCurso'] == "TPCMADERA"){

                                                    echo "<option>";
                                                    echo $listaCursos[$i]['nombreCurso'];
                                                    echo "</option>";

                                                }
                                                
                                            }

                                            ?>
 
                                        </select>
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-md-1 col-1">
                                         <input type="radio" name="cursos" id="radioTPCVIDREO"></input>
                                    </div>

                                    <div class="col-md-2 col-4">
                                        <input class="form-control readonly text-center" name="tipoCurso" value="TPC VIDREO" id="TPCVIDREO" disabled>
                                    </div>

                                    <div class="col-md-2 col-3">
                                        <input class="form-control text-center" name="horasCurso" id="horasTPCVIDREO" disabled>
                                    </div>

                                    <div class="col-md-7 col-4">
                                        <select class="form-select  mb-2" name="nombreCurso" id="selectTPCVIDREO" disabled>
                                        <option hidden="true" selected></option>
                                            <?php 
                                            
                                            for($i=0; $i < count($listaCursos); $i++) {
                                                
                                                if($listaCursos[$i]['tipoCurso'] == "TPCVIDREO"){

                                                    echo "<option>";
                                                    echo $listaCursos[$i]['nombreCurso'];
                                                    echo "</option>";

                                                }
                                                
                                            }

                                            ?>
 
                                        </select>
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-md-1 col-1">
                                         <input type="radio" name="cursos" id="radioOTROS"></input>
                                    </div>

                                    <div class="col-md-2 col-4">
                                        <input class="form-control readonly text-center" name="tipoCurso" value="OTROS" id="OTROS" disabled>
                                    </div>

                                    <div class="col-md-2 col-3">
                                        <input class="form-control text-center" name="horasCurso" id="horasOTROS" disabled>
                                    </div>

                                    <div class="col-md-7 col-4">
                                        <select class="form-select mb-2" name="nombreCurso" id="selectOTROS" disabled>
                                        <option hidden="true" selected></option>
                                            <?php 
                                            
                                            for($i=0; $i < count($listaCursos); $i++) {
                                                
                                                if($listaCursos[$i]['tipoCurso'] == "OTROS"){

                                                    echo "<option>";
                                                    echo $listaCursos[$i]['nombreCurso'];
                                                    echo "</option>";

                                                }
                                                
                                            }

                                            ?>
 
                                        </select>
                                    </div>

                                </div>


                            </div>

                            <div class="row">
                                <div class="col-md-12 col-12">
                                    <input class="btn form-control mt-5 mb-5" style="background-color: #1e989e" type="submit" name="insertar" value="Insertar"></input>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-12">
                                    <h5 class='text-center pt-2 pb-2 border border-5 rounded' style="background-color: #b0d588; letter-spacing: 7px;">LISTADO CURSOS: </h5>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-12">
                                    <?php

                                        if(!empty($cursosInteresados)){

                                            echo "<div>";

                                            for($i=0; $i < count($cursosInteresados); $i++){

                                                echo "<div class='container-fluid border rounded mt-2 mb-3 border-5'>";

                                                echo "<div class='row mt-2'>";
                                                echo "<div class='col-12 col-md-2'> <a href='/consultarEmpresa.php?{$params}&borrarCurso=".$cursosInteresados[$i]['Codigo']."' class='text-danger' title='Borrar'>X</a> <b>Estado:</b> " . $cursosInteresados[$i]['estadoCurso'] . "</div>";
                                                echo "<div class='col-12 col-md-5'><b>Alumnos / Duración: </b> " . $cursosInteresados[$i]['horasCurso'] . "</div>";
                                                echo "<div class='col-12 col-md-5'><b>Curso interesado:</b> " . $cursosInteresados[$i]['Curso'] . "</div>";
                                                echo "</div>";

                                                echo "</div>";

                                            }

                                            echo "</div>";

                                        }

                                    ?>
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

                                               $curso = listadoCurso($listadoLlamadas[$i]['idempresa']);

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

                                                echo "<div class='col-12 mt-2'>";
                                                echo "<label class='col-12 mb-3'> <b>Observaciones interlocutor:</b> " . $listadoLlamadas[$i]['observacionesinterlocutor'] . "</label>";
                                                echo "</div>";

                                                echo "<div class='col-12 mt-2'>";
                                                ?><label class='col-5 mb-3'>
                                                    <b>Ano/mes Pedir Cita:</b><?php
                                                    if($listadoLlamadas[$i]['anoPedirCita'] != "" and $listadoLlamadas[$i]['mesPedirCita'] != ""){
                                                        echo " " . $listadoLlamadas[$i]['anoPedirCita'] . " / " . ["enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre"][intval($listadoLlamadas[$i]['mesPedirCita']) - 1]; 
                                                    }
                                                    ?>
                                                    </label>
                                                <?php
                                                
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