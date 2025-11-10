
<?php

include "funciones/conexionBD.php";
include "funciones/funcionesAlumnos.php";

session_start();

if(empty($_SESSION)){

    header("Location: index.php");

}

date_default_timezone_set("Europe/Madrid");
setlocale(LC_ALL, "spanish");

if($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_POST['editar'])){
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

    if(editarAlumno($datosAlumno, $_POST['idAlumno'])){
        echo "<div class='alert alert-success'> Success </div>";
    }else{
        echo "<div class='alert alert-danger mb-0'> ERROR: <pre>";
        print_r($datosAlumno);
        echo "</div>";
    };
}

$chooseAlumnoDialog = false;

if(empty($_GET['idAlumno'])){
    echo "<div class='alert alert-danger' role='alert'> El campo de busqueda no puede estar vacio </div>";
    die();
} else {
    if($alumno = cargarAlumno($_GET['idAlumno'])){
    } else {
        echo "<div class='alert alert-danger' role='alert'>No se encuentra ningun alumno</div>";
        die();
    }
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

        <div class="col-10" id="formNuevoAlumno" <?php if($chooseAlumnoDialog) {echo "hidden";}?>>
            <h2 class="text-center mt-2 pt-2 pb-3 mb-md-5 mb-3 border border-5 rounded" style="background-color: #b0d588; letter-spacing: 7px;">EDITAR ALUMNO</h2>

            <form method="POST">

            <div class="row">

                <div class="col-4">
                    <label class="fw-bold">Nombre:</label>
                    <input name="nombre" class="form-control" required value="<?php echo $alumno['nombre'] ?>"></input>
                </div> 

                <div class="col-4">
                    <label class="fw-bold">Apellidos:</label>
                    <input name="apellidos" class="form-control" required value="<?php echo $alumno['apellidos'] ?>"></input>
                </div>

            </div>

            <div class="row mt-2">

                <div class="col-4">
                    <label class="fw-bold">Telefono:</label>
                    <input name="telefono" class="form-control" type="tel" value="<?php echo $alumno['telefono'] ?>"></input>
                </div> 

                <div class="col-4">
                    <label class="fw-bold">Email:</label>
                    <input name="email" class="form-control" type="text" value="<?php echo $alumno['email'] ?>"></input>
                </div>

                <div class="col-4">
                    <label class="fw-bold">F. Nacimiento:</label>
                    <input name="fechaNacimiento" class="form-control" type="date" value="<?php echo $alumno['fechaNacimiento'] ?>"></input>
                </div>

            </div>

            <div class="row mt-2">

                <div class="col-4">
                    <label class="fw-bold">NIF:</label>
                    <input name="nif" class="form-control" type="text" value="<?php echo $alumno['nif'] ?>"></input>
                </div>

                <!-- <div class="col-5">
                    <label class="fw-bold">Nº Seguridad Social:</label>
                    <input name="numeroSeguridadSocial" class="form-control" type="text"></input>
                </div> -->

                <div class="col-4">
                    <label class="fw-bold">Sexo:</label>
                    <select name="sexo" class="form-select">
                        <option value="hombre" <?php if($alumno['sexo'] == "hombre"){echo " selected ";} ?>>Hombre</option>
                        <option value="mujer" <?php if($alumno['sexo'] == "mujer"){echo " selected ";} ?>">Mujer</option>
                    </select>
                </div>

                <div class="col-4">
                    <label class="fw-bold">Discapacidad:</label>
                    <select name="discapacidad" class="form-select">
                        <option value="Si" <?php if($alumno['discapacidad'] == "Si"){echo " selected ";} ?>>Si</option>
                        <option value="No" <?php if($alumno['discapacidad'] == "No"){echo " selected ";} ?>>No</option>
                        <option value="" <?php if($alumno['discapacidad'] == ""){echo " selected ";} ?>></option>
                    </select>
                </div>

            </div>

            <div class="row mt-2">

                <div class="col-4">
                    <label class="fw-bold">CATEGORIA PROFESIONAL:</label>
                    <select name="categoriaProfesional" class="form-select">
                        <option value="directivo" <?php if($alumno['categoriaProfesional'] == "directivo"){echo " selected ";} ?>>Directivo</option>
                        <option value="mandoIntermedio" <?php if($alumno['categoriaProfesional'] == "mandoIntermedio"){echo " selected ";} ?>>Mando intermedio</option>
                        <option value="tecnico" <?php if($alumno['categoriaProfesional'] == "tecnico"){echo " selected ";} ?>>Técnico</option>
                        <option value="trabajadorCualificado" <?php if($alumno['categoriaProfesional'] == "trabajadorCualificado"){echo " selected ";} ?>>Trabajador cualificado</option>
                        <option value="trabajadorConBajaCualificacion" <?php if($alumno['categoriaProfesional'] == "trabajadorConBajaCualificacion"){echo " selected ";} ?> >Trabajador con baja cualificación</option>
                        <option value="" <?php if($alumno['categoriaProfesional'] == ""){echo " selected ";} ?> ></option>
                    </select>
                </div>

                <div class="col-4">
                    <label class="fw-bold">COLECTIVO:</label>
                    <select id="colectivo" name="colectivo" class="form-select">
                        <option value="regimenGeneral" <?php if($alumno['colectivo'] == "regimenGeneral"){echo " selected ";} ?>>Régimen general</option>
                        <option value="fijoDiscontinuo" <?php if($alumno['colectivo'] == "fijoDiscontinuo"){echo " selected ";} ?>>Fijo discontinuo</option>
                        <option value="autonomo" <?php if($alumno['colectivo'] == "autonomo"){echo " selected ";} ?>>Autónomo</option>
                        <option value="otros" <?php if($alumno['colectivo'] != "regimenGeneral" and $alumno['colectivo'] != "fijoDiscontinuo" and $alumno['colectivo'] != "" and $alumno['colectivo'] != "autonomo"){echo " selected ";} ?>>Otros</option>
                        <option value="" <?php if($alumno['colectivo'] == ""){echo " selected ";} ?>></option>
                    </select>
                </div>

                <div class="col-4" id="otrosColectivo" <?php if($alumno['colectivo'] == "regimenGeneral" or $alumno['colectivo'] == "fijoDiscontinuo"){echo " hidden ";} ?>>
                    <label class="fw-bold">OTROS:</label>
                    <input name="otrosColectivos" class="form-control" type="text" value="<?php echo $alumno['colectivo'] ?>"></input>
                </div>

            </div>

            <div class="row mt-2">
                <div class="col-12">
                    <label class="fw-bold">GRUPO DE COTIZACIÓN A LA TGSS::</label>
                    <select name="grupoCotizacion" class="form-select">
                        <option value="ingenierosLicenciados" <?php if($alumno['grupoCotizacion'] == "ingenierosLicenciados"){echo " selected ";} ?>>(1) Ingenieros y licenciados</option>
                        <option value="ingenierosTecnicos" <?php if($alumno['grupoCotizacion'] == "ingenierosTecnicos"){echo " selected ";} ?>>(2) Ingenieros técnicos, Peritos y Ayudantes titulados</option>
                        <option value="jefesAdministrativos" <?php if($alumno['grupoCotizacion'] == "jefesAdministrativos"){echo " selected ";} ?>>(3) Jefes administrativos y de taller</option>
                        <option value="ayudantesNoTitulados" <?php if($alumno['grupoCotizacion'] == "ayudantesNoTitulados"){echo " selected ";} ?>>(4) Ayudantes no titulados</option>
                        <option value="oficialesAdministrativos" <?php if($alumno['grupoCotizacion'] == "oficialesAdministrativos"){echo " selected ";} ?>>(5) Oficiales administrativos</option>
                        <option value="subalternos" <?php if($alumno['grupoCotizacion'] == "subalternos"){echo " selected ";} ?>>(6) Subalternos</option>
                        <option value="auxiliares" <?php if($alumno['grupoCotizacion'] == "auxiliares"){echo " selected ";} ?>>(7) Auxiliares administrativos</option>
                        <option value="oficialesDePrimera" <?php if($alumno['grupoCotizacion'] == "oficialesDePrimera"){echo " selected ";} ?>>(8) Oficiales de primera y segunda</option>
                        <option value="oficialesDeTercera" <?php if($alumno['grupoCotizacion'] == "oficialesDeTercera"){echo " selected ";} ?>>(9) Oficiales de tercera y especialistas</option>
                        <option value="mayores18" <?php if($alumno['grupoCotizacion'] == "mayores18"){echo " selected ";} ?>>(10) Trabajadores mayores de 18 años no cualificados</option>
                        <option value="menores18" <?php if($alumno['grupoCotizacion'] == "menores18"){echo " selected ";} ?>>(11) Trabajadores menores de 18 años</option>
                        <option value="" <?php if($alumno['grupoCotizacion'] == ""){echo " selected ";} ?>></option>
                    </select>
                </div>

            </div>

            <div class="row mt-2">

                <div class="col-8">
                    <label class="fw-bold">NIVEL DE ESTUDIOS:</label>
                    <select id="nivelEstudios" name="nivelEstudios" class="form-select">
                        <option value="menosPrimaria" <?php if($alumno['nivelEstudios'] == "menosPrimaria"){echo " selected ";} ?>>Menos que primaria</option>
                        <option value="primaria" <?php if($alumno['nivelEstudios'] == "primaria"){echo " selected ";} ?>>Educación primaria</option>
                        <option value="educacionSecundaria1" <?php if($alumno['nivelEstudios'] == "educacionSecundaria1"){echo " selected ";} ?>>1ª etapa de Educacion Secundaria (1º y 2º ciclo de la ESO, Graduado Escolar, Certificado Profesional de nivel 1 y 2)</option>
                        <option value="educacionSecundaria2" <?php if($alumno['nivelEstudios'] == "educacionSecundaria2"){echo " selected ";} ?>>2ª etapa de Educacion Secundaria (Bachillerato, FP de grado medio, BUP, FP 1 y FP 2)</option>
                        <option value="educacionPostsecundaria" <?php if($alumno['nivelEstudios'] == "educacionPostsecundaria"){echo " selected ";} ?>>Educacion Postsecundaria no superior (Certificado Profesional de nivel 3)</option>
                        <option value="tecnicoSuperior" <?php if($alumno['nivelEstudios'] == "tecnicoSuperior"){echo " selected ";} ?>>Tecnico superior / FP de grado superior y equivalentes</option>
                        <option value="universitarios1" <?php if($alumno['nivelEstudios'] == "universitarios1"){echo " selected ";} ?>>E. Universitarios 1º ciclo (Diplomatura-Grados)</option>
                        <option value="universitarios2" <?php if($alumno['nivelEstudios'] == "universitarios2"){echo " selected ";} ?>>E. Universitarios 2º ciclo (Licenciatura-Máster)</option>
                        <option value="universitarios3" <?php if($alumno['nivelEstudios'] == "universitarios3"){echo " selected ";} ?>>E. Universitarios 3º ciclo (Doctorado)</option>
                        <option value="otras" 
                        <?php if(
                            $alumno['nivelEstudios'] != "menosPrimaria" and
                            $alumno['nivelEstudios'] != "primaria" and
                            $alumno['nivelEstudios'] != "educacionSecundaria1" and
                            $alumno['nivelEstudios'] != "educacionSecundaria2" and
                            $alumno['nivelEstudios'] != "educacionPostsecundaria" and
                            $alumno['nivelEstudios'] != "tecnicoSuperior" and
                            $alumno['nivelEstudios'] != "universitarios1" and
                            $alumno['nivelEstudios'] != "universitarios2" and
                            $alumno['nivelEstudios'] != "universitarios3" and
                            $alumno['nivelEstudios'] != ""
                        ){echo " selected ";} ?>>Otras titulaciones: Especificar</option>
                        <option value="" <?php if($alumno['nivelEstudios'] == ""){echo " selected ";} ?></option>
                    </select>
                </div>

                <div id="otrosNivelEstudios" class="col-4">
                    <label class="fw-bold">OTROS:</label>
                    <input name="estudiosOtros" class="form-control" value="<?php echo $alumno['nivelEstudios']; ?>"></input>
                </div>

            </div>

            <div class="row mt-2">

                <div class="col-6">
                    <label class="fw-bold">COSTE HORA PARTICIPANTE:</label>
                    <input name="costeHora" type="text" class="form-control" value="<?php echo $alumno['costeHora']; ?>"></input>
                </div>

                <div class="col-md-6">
                    <label class="fw-bold">HORARIO LABORAL:</label>
                    <input name="horarioLaboral" type="text" class="form-control" value="<?php echo $alumno['horarioLaboral']; ?>"></input>
                </div>
                <div class="col-4">
                    <label class="fw-bold">idEmpresa:</label>
                    <input name="idEmpresa" class="form-control" value="<?php echo $alumno['idEmpresa'] ?>" type="text"></input>
                </div> 
                <input name="idAlumno" class="form-control" value="<?php echo $alumno['idAlumno'] ?>" type="text" hidden></input>
            </div>

            <div class="row mt-3 mb-3">
                <div class="col-12 text-center">
                    <button name="editar" class="col-6 btn btn-primary text-center">GUARDAR</button>
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