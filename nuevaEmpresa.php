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
            'sector' => implode('|!!|',$_POST['sector']),
            'pais' => "ESP",
            'telefono' => $_POST['telefono'],
            'telefono2' => $_POST['telefono2'],
            'telefono3' => $_POST['telefono3'],
            'observacionesEmpresa' => $_POST['observacionesEmpresa'],
            'fecha' => $fechaHoy,
            'creditoGuardado' => $_POST['creditoGuardado'],
            'creditoCaducar' => $_POST['creditoCaducar'],
            'codigoUsuario' => $_SESSION['codigoUsuario'],
            'pdte_bonificar'=> $_POST['pdte_bonificar'],
            'horario' => $_POST['horario'],
            'referencia'=>$_POST['referencia'],
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
                'operador' => $_SESSION['codigoUsuario'],
                'codigo_llamada' => $_POST['codigo_llamada'],
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

    <?php 
        $menuaction = 'callcenter';
        require_once './template-parts/header/menu_top.php' 
    ?>

    <!-- Menu lateral y formulario -->

    <div class="container-fluid">

<div class="row">

    <?php require_once './template-parts/leftmenu/callcenter.template.php'; ?>

    <div class="col-md-10 col-12" id="datosEmpresa">

        <h2 class="text-center mt-2 pt-2 pb-3 mb-md-2 mb-3 border border-5" style="background-color: #b0d588; letter-spacing: 7px;">DATOS DE LA EMPRESA</h2>

        <div class="container-fluid">

            <div class="row d-flex justify-content-center">

                <form class="col-12" method="POST" id="formNuevaEmpresa">

                    <?php require_once './template-parts/components/empresaFormDatosBasicos.php' ?>

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

                </form>

            </div>

        </div>

    </div>

<footer class="border-top border-secondary" style="background-color:#e4e4e4; height: 75px;">

    <p class="text-center mt-md-4" style='color: #8fd247;'> <b> © Dixma Formación 2022. | Ctra. Madrid 152, Vigo 36318 | info@dixmaformacion.com | Tlf: +34 604 067 035 </b> </p>

</footer>

</body>
</html>