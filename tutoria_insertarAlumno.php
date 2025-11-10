
<?php

include "funciones/conexionBD.php";
include "funciones/funcionesAlumnos.php";

session_start();

if(empty($_SESSION)){

    header("Location: index.php");

}

date_default_timezone_set("Europe/Madrid");
setlocale(LC_ALL, "spanish");

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['insertar'])){

    if($_POST['colectivo'] == "otros"){

        $_POST['colectivo'] = $_POST['otrosColectivos'];

    }

    if($_POST['nivelEstudios'] == "otras"){

        $_POST['nivelEstudios'] = $_POST['estudiosOtros'];

    }

    $datosAlumno = [
        'nombre' => $_POST['nombre'],
        'apellidos' => $_POST['apellidos'],
        'telefono' => $_POST['telefono'],
        'email' => $_POST['email'],
        'fechaNacimiento' => date('Y-m-d',strtotime($_POST['fechaNacimiento'])),
        'nif' => $_POST['nif'],
        'numeroSeguridadSocial' => "",
        'categoriaProfesional' => $_POST['categoriaProfesional'],
        'colectivo' => $_POST['colectivo'],
        'grupoCotizacion' => $_POST['grupoCotizacion'],
        'nivelEstudios' => $_POST['nivelEstudios'],
        'costeHora' => $_POST['costeHora'],
        'horarioLaboral' => $_POST['horarioLaboral'],
        'idEmpresa' => $_POST['idEmpresa'],
        'sexo' => $_POST['sexo'],
        'discapacidad' => $_POST['discapacidad'],

    ];

    if(insertarNuevoAlumno($datosAlumno)){
        echo "<div class='alert alert-success'> Success </div>";
        header("Location: tutoria_buscarEmpresa.php?");
    }else{
        echo "<div class='alert alert-danger mb-0'> ERROR: <pre>";
        print_r($datosAlumno);
        echo "</div>";
    };


}

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tutoria</title>
<link href="css/bootstrap.min.css" rel="stylesheet"></link>
<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/jquery.min.js"></script>
<script src="js/tutoria.js"></script>
<link rel="icon" href="images/favicon.ico">

</head>
<body style="background-color: #f3f6f4;">

<?php require_once("template-parts/header/header.template.php"); ?>

<!-- Menu lateral y formulario -->
<div class="container-fluid">

    <div class="row">

        <?php require_once("template-parts/leftmenu/tutoria.template.php"); ?>

        <div class="col-10" id="formNuevoAlumno">
            <h2 class="text-center mt-2 pt-2 pb-3 mb-md-5 mb-3 border border-5 rounded" style="background-color: #b0d588; letter-spacing: 7px;">INSERTAR NUEVO ALUMNO</h2>

            <form method="POST">

            <input name="idEmpresa" value="<?php echo $_GET['idEmpresa']?>" hidden></input>

            <div class="row">

                <div class="col-4">
                    <label class="fw-bold">Nombre:</label>
                    <input name="nombre" class="form-control" required></input>
                </div> 

                <div class="col-4">
                    <label class="fw-bold">Apellidos:</label>
                    <input name="apellidos" class="form-control" required></input>
                </div>

            </div>

            <div class="row mt-2">

                <div class="col-4">
                    <label class="fw-bold">Telefono:</label>
                    <input name="telefono" class="form-control" type="tel"></input>
                </div> 

                <div class="col-4">
                    <label class="fw-bold">Email:</label>
                    <input name="email" class="form-control" type="text"></input>
                </div>

                <div class="col-4">
                    <label class="fw-bold">F. Nacimiento:</label>
                    <input name="fechaNacimiento" class="form-control" type="date"></input>
                </div>

            </div>

            <div class="row mt-2">

                <div class="col-4">
                    <label class="fw-bold">NIF:</label>
                    <input name="nif" class="form-control" type="text" value="<?php echo $_GET['nif']?>" readonly></input>
                </div>

                <!-- <div class="col-5">
                    <label class="fw-bold">Nº Seguridad Social:</label>
                    <input name="numeroSeguridadSocial" class="form-control" type="text"></input>
                </div> -->

                <div class="col-4">
                    <label class="fw-bold">Sexo:</label>
                    <select name="sexo" class="form-select">
                        <option value="hombre">Hombre</option>
                        <option value="mujer">Mujer</option>
                    </select>
                </div>

                <div class="col-4">
                    <label class="fw-bold">Discapacidad:</label>
                    <select name="discapacidad" class="form-select">
                        <option value="Si">Si</option>
                        <option selected="selected" value="No">No</option>
                        <option value=""></option>
                    </select>
                </div>

            </div>

            <div class="row mt-2">

                <div class="col-4">
                    <label class="fw-bold">CATEGORIA PROFESIONAL:</label>
                    <select name="categoriaProfesional" class="form-select">
                        <option value=""></option>
                        <option value="directivo">Directivo</option>
                        <option value="mandoIntermedio">Mando intermedio</option>
                        <option value="tecnico">Técnico</option>
                        <option value="trabajadorCualificado">Trabajador cualificado</option>
                        <option value="trabajadorConBajaCualificacion">Trabajador con baja cualificación</option>
                    </select>
                </div>

                <div class="col-4">
                    <label class="fw-bold">COLECTIVO:</label>
                    <select id="colectivo" name="colectivo" class="form-select">
                        <option value=""></option>
                        <option value="regimenGeneral">Régimen general</option>
                        <option value="fijoDiscontinuo">Fijo discontinuo</option>
                        <option value="autonomo">Autónomo</option>
                        <option value="otros">Otros</option>
                    </select>
                </div>

                <div class="col-4" id="otrosColectivo" hidden>
                    <label class="fw-bold">OTROS:</label>
                    <input name="otrosColectivos" class="form-control" type="text"></input>
                </div>

            </div>

            <div class="row mt-2">
                <div class="col-12">
                    <label class="fw-bold">GRUPO DE COTIZACIÓN A LA TGSS::</label>
                    <select name="grupoCotizacion" class="form-select">
                        <option value=""></option>
                        <option value="ingenierosLicenciados">(1) Ingenieros y licenciados</option>
                        <option value="ingenierosTecnicos">(2) Ingenieros técnicos, Peritos y Ayudantes titulados</option>
                        <option value="jefesAdministrativos">(3) Jefes administrativos y de taller</option>
                        <option value="ayudantesNoTitulados">(4) Ayudantes no titulados</option>
                        <option value="oficialesAdministrativos">(5) Oficiales administrativos</option>
                        <option value="subalternos">(6) Subalternos</option>
                        <option value="auxiliares">(7) Auxiliares administrativos</option>
                        <option value="oficialesDePrimera">(8) Oficiales de primera y segunda</option>
                        <option value="oficialesDeTercera">(9) Oficiales de tercera y especialistas</option>
                        <option value="mayores18">(10) Trabajadores mayores de 18 años no cualificados</option>
                        <option value="menores18">(11) Trabajadores menores de 18 años</option>
                    </select>
                </div>

            </div>

            <div class="row mt-2">

                <div class="col-8">
                    <label class="fw-bold">NIVEL DE ESTUDIOS:</label>
                    <select id="nivelEstudios" name="nivelEstudios" class="form-select">
                        <option value=""></option>
                        <option value="menosPrimaria">Menos que primaria</option>
                        <option value="primaria">Educación primaria</option>
                        <option value="educacionSecundaria1">1ª etapa de Educacion Secundaria (1º y 2º ciclo de la ESO, Graduado Escolar, Certificado Profesional de nivel 1 y 2)</option>
                        <option value="educacionSecundaria2">2ª etapa de Educacion Secundaria (Bachillerato, FP de grado medio, BUP, FP 1 y FP 2)</option>
                        <option value="educacionPostsecundaria">Educacion Postsecundaria no superior (Certificado Profesional de nivel 3)</option>
                        <option value="tecnicoSuperior">Tecnico superior / FP de grado superior y equivalentes</option>
                        <option value="universitarios1">E. Universitarios 1º ciclo (Diplomatura-Grados)</option>
                        <option value="universitarios2">E. Universitarios 2º ciclo (Licenciatura-Máster)</option>
                        <option value="universitarios3">E. Universitarios 3º ciclo (Doctorado)</option>
                        <option value="otras">Otras titulaciones: Especificar</option>
                    </select>
                </div>

                <div id="otrosNivelEstudios" class="col-4" hidden>
                    <label class="fw-bold">OTROS:</label>
                    <input name="estudiosOtros" class="form-control"></input>
                </div>

            </div>

            <div class="row mt-2">

                <div class="col-6">
                    <label class="fw-bold">COSTE HORA PARTICIPANTE:</label>
                    <input name="costeHora" type="text" class="form-control"></input>
                </div>

                <div class="col-md-6">
                    <label class="fw-bold">HORARIO LABORAL:</label>
                    <input name="horarioLaboral" type="text" class="form-control"></input>
                </div>

            </div>

            <div class="row mt-3 mb-3">
                <div class="col-12 text-center">
                    <button name="insertar" class="col-6 btn btn-primary text-center">INSERTAR</button>
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