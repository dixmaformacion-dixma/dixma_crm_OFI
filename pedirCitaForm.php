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
            'sector' => implode('|!!|',$_POST['sector']),
            'pais' => "ESP",
            'telefono' => $_POST['telefono'],
            'telefono2' => $_POST['telefono2'],
            'telefono3' => $_POST['telefono3'],
            'observacionesEmpresa' => $_POST['observacionesEmpresa'],
            'creditoGuardado' => $_POST['creditoGuardado'],
            'creditoCaducar' => $_POST['creditoCaducar'],
            'referencia'=>$_POST['referencia'],
            'codigo'=>$_POST['codigo'],
            'pdte_bonificar'=>$_POST['pdte_bonificar']
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
            $operador = $_SESSION['codigoUsuario'];
            
            $observaciones = $_POST['observacionesOtros'];
            $usuario_seguimiento = NULL;
            $operador = $_SESSION['codigoUsuario'];
            $estadoLlamada = $_POST['estadoLlamada'];
            $prioridad = "BAJO";

            
            if(isset($_POST['pedirCita'])){

                $nombre = nombreComercial($_POST['pedirCita']);
                $observaciones = "pedirCita";
                $estadoLlamada = "Pedir cita " . $nombre[0];
                $anoPedirCita = $_POST['anoPedirCita'];
                $mesPedirCita = $_POST['mesPedirCita'];
                $prioridad = $_POST['prioridadCita'];

            }

            if(isset($_POST['seguimiento'])){

                $nombre = nombreComercial($_POST['seguimiento']);
                $usuario_seguimiento = $_POST['seguimiento'];
                $operador = $_POST['seguimiento'];
                $observaciones = "devolver seguimiento";
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
                'usuario_seguimiento'=>$usuario_seguimiento,
                'prioridad'=>$prioridad
            ];

            if(insertarNuevaLlamada($datosLlamada, $_GET['idEmpresa'])){

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
        if(isset($_POST['estadoLlamada']) || $_POST['tipoCurso'] != '--- Seleccione ---'){
            if($_GET['tipo'] == 'seguimiento'){

                actualizarSeguimientoCallCenter($_GET['idLlamada']);

            } else {

                actualizarLlamadaCallCenter($_GET['idLlamada']);

            }
        }

        $redirect = "inicio.php";

        if(!empty($_GET['redirect'])){
            $redirect = $_GET['redirect'];
        }
        header("Refresh: 1; URL={$redirect}");

    };
    
    if($empresa = cargarEmpresa($_GET['idEmpresa'])){
        $empresa['sector'] = explode('|!!|',$empresa['sector']);
    }

    if($listadoLlamadas = listadoLlamadas($_GET['idEmpresa'])){

    }
    if($listaCursos = listadoCursos()){

    }

    if($cursosInteresados = cursosInteresados($_GET['idEmpresa'])){

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
    <script src="js/selectCursos.js"></script>
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

                <h2 class="text-center mt-2 pt-2 pb-3 mb-md-2 mb-3 border border-5 rounded" style="background-color: #b0d588; letter-spacing: 7px;">DATOS DE LA EMPRESA</h2>

                <div class="container-fluid">

                    <div class="row d-flex justify-content-center">

                        <form class="col-12" method="POST">

                            <input value="<?php echo $_GET['tipo'] ?>" hidden> </input>

                            <input name="idEmpresa" value="<?php echo $_GET['idEmpresa'] ?>" hidden></input>

                            <?php require_once './template-parts/components/empresaFormDatosBasicos.php' ?>

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
                                $comerciales = cargarComerciales();
                                echo "<div class='row d-flex justify-content-center mt-md-2'>";
                                require_once './template-parts/components/empresaPedirCitaForm.php';

                                echo "<div class='col-md-5 col-12'>";
                                        echo "<label><b>Hacer seguimiento: </b></label>";
                                        echo "<input type='radio' class='form-check-input ms-md-1' name='estadoLlamada' id='hacerSeguiminento'>";
                                        echo "<select class='form-select' name='seguimiento' id='selectHacerSeguimiento' disabled>";

                                    for($i=0; $i < count($comerciales); $i++){

                                        if(
                                            $comerciales[$i]['codigousuario']!=$_SESSION['codigoUsuario']
                                        ){

                                            echo "<option hidden='true'></option>";
                                            echo "<option value='" . $comerciales[$i]['codigousuario'] . "'>" . $comerciales[$i]['nombre'] . "</option>";
                                            
                                        }

                                    }
                                        echo "</select>"; ?>
                                        <label><b>Fecha de Seguimiento: </b></label>
                                        <input type="date" name="fecha_seguimiento" class="form-control" disabled>
                                        <label><b>Tipo de llamada: </b></label>
                                        <input type="text" name="tipo_seguimiento" class="form-control" disabled>
                                    </div>
                                    <?php 
                                echo "</div>";
                                echo "</div>";
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
                                <div id="cursosSections">
                                <?php foreach(getTiposCursos() as $index=>$tipoCurso): ?>
                                    <div class="row cursoSection">

                                        <div class="col-md-1 col-1">
                                            <input type="radio" name="cursos" id="radio<?php echo $tipoCurso['codigo'] ?>" <?php echo $index==0?'checked':''?> ></input>
                                        </div>

                                        <div class="col-md-2 col-4">
                                            <input type="text" readonly="true" class="form-control readonly text-center" id="<?php echo $tipoCurso['codigo'] ?>f" value="<?php echo $tipoCurso['nombre'] ?>" <?php echo $index>0?'disabled':''?>>
                                            <input type="text" class="form-control readonly text-center d-none" name="tipoCurso" id="<?php echo $tipoCurso['codigo'] ?>" value="<?php echo $tipoCurso['codigo'] ?>" <?php echo $index>0?'disabled':''?>>
                                        </div>
                                    
                                        <div class="col-md-2 col-3">
                                            <input type="text" class="form-control text-center" name="horasCurso" id="horas<?php echo $tipoCurso['codigo'] ?>" <?php echo $index>0?'disabled':''?>>
                                        </div>

                                        <div class="col-md-7 col-4">
                                            <select class="form-select mb-2" name="nombreCurso" id="select<?php echo $tipoCurso['codigo'] ?>" <?php echo $index>0?'disabled':''?>>
                                            <option hidden="true" selected></option>
                                                <?php 
                                                
                                                for($i=0; $i < count($listaCursos); $i++) {
                                                    
                                                    if($listaCursos[$i]['tipoCurso'] == $tipoCurso['codigo']){

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

                            </div>

                            <div class="row">
                                <div class="col-md-12 col-12">
                                    <input class="btn btn-primary form-control mt-5 mb-5" type="submit" name="insertar" value="Insertar"></input>
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
                                                echo "<label class='col-12 col-md-3'> <b>Ano/Mes:</b> ";
                                                if($listadoLlamadas[$i]['anoPedirCita'] != "" and $listadoLlamadas[$i]['mesPedirCita'] != ""){
                                                    echo " " . $listadoLlamadas[$i]['anoPedirCita'] . " " . ["ENE","FEB","MAR","ABR","MAY","JUN","JUL","AGO","SEP","OCT","NOV","DIC"][intval($listadoLlamadas[$i]['mesPedirCita']) - 1]; 
                                                }
                                                //Desactivado el boton de editar momentaneamente
                                                if(1!=1 && $listadoLlamadas[$i]['anoPedirCita'] != "" and $listadoLlamadas[$i]['mesPedirCita'] != ""){
                                                    echo ' | <a href="javascript:editarFecha('.$listadoLlamadas[$i]['idllamada'].','.$listadoLlamadas[$i]['mesPedirCita'].','.$listadoLlamadas[$i]['anoPedirCita'].')">Editar</a>';
                                                };
                                                echo "</label>";
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
                            <?php if(!empty($_REQUEST['redirect'])): ?>
                                <input type="hidden" name="redirect" value="<?php echo $_REQUEST['redirect'] ?>">
                            <?php endif ?>

                        </form>

                    </div>

                </div>

            </div>

    <footer class="border-top border-secondary" style="background-color:#e4e4e4; height: 75px;">

            <p class="text-center mt-md-4" style='color: #8fd247;'> <b> © Dixma Formación 2022. | Ctra. Madrid 152, Vigo 36318 | info@dixmaformacion.com | Tlf: +34 604 067 035 </b> </p>

    </footer>

    <div class="modal" tabindex="-1" id="editarFecha">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" onsubmit="modificarFecha(this);return false;">
                    <div class="modal-header">
                        <h5 class="modal-title">Modificar Fecha de Cita</h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Mes</label>
                            <select name="mesPedirCita" id="" class="form-control">
                                <option value="1">Enero</option>
                                <option value="2">Febrero</option>
                                <option value="3">Marzo</option>
                                <option value="4">Abril</option>
                                <option value="5">Mayo</option>
                                <option value="6">Junio</option>
                                <option value="7">Julio</option>
                                <option value="8">Agosto</option>
                                <option value="9">Septiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Año</label>
                            <input type="number" name="anoPedirCita" value="" class="form-control">
                        </div>
                        <input type="hidden" name="idllamada">
                        <div class="alert alert-success" id="successMsj">Datos almacenados con exito</div>
                        <div class="alert alert-danger" id="dangerMsj">Ocurrio un error al modificar la fecha, por favor intente nuevamente</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="$('#editarFecha').modal('hide')">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                    <?php if(!empty($_REQUEST['redirect'])): ?>
                        <input type="hidden" name="redirect" value="<?php echo $_REQUEST['redirect'] ?>">
                    <?php endif ?>
                </form>
            </div>
        </div>
        </div>
    <script>
        function editarFecha(id,mes,ano){
            $('#successMsj').hide();
            $('#dangerMsj').hide();
            $('#editarFecha').modal('show')
            $("select[name='mesPedirCita']").val(mes);
            $("input[name='anoPedirCita']").val(ano);
            $("input[name='idllamada']").val(id);
        }
        function modificarFecha(form){
            $('#successMsj').hide();
            $('#dangerMsj').hide();
            $.post(`/pedirCita.php`,{
                mesPedirCita:$("select[name='mesPedirCita']").val(),
                anoPedirCita:$("input[name='anoPedirCita']").val(),
                idllamada:$("input[name='idllamada']").val(),
                modificarFecha:1
            },function(d){
                d = JSON.parse(d);
                if(d.success){
                    $('#successMsj').show();
                    document.location.reload();
                }else{
                    $('#dangerMsj').show();
                }
            });
        }
    </script>
    
</body>
</html>