

<?php

include "funciones/conexionBD.php";
include "funciones/funcionesAlumnos.php";
include "funciones/funcionesEmpresa.php";
include "funciones/funcionesAlumnosCursos.php";

setlocale(LC_ALL, 'ES_es');

session_start();

if(empty($_SESSION)){

    header("Location: index.php");

}

date_default_timezone_set("Europe/Madrid");
setlocale(LC_ALL, "spanish");

if(!isset($_GET['idAlumno']) or !isset($_GET['idEmpresa'])){
  die("Parameters idAlumno or idEmpresa  are missing!");
}

$alumno = cargarAlumno($_GET['idAlumno']);
$empresa = cargarEmpresa($_GET['idEmpresa']);

$alumnoCurso = false;
if(isset($_GET['StudentCursoID'])){
  $alumnoCurso = cargarAlumnoCurso($_GET['StudentCursoID']);
}
?>

<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PDF</title>
  <link href="css/bootstrap.min.css" rel="stylesheet"></link>
  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/jquery.min.js"></script>
  <script src="js/crearPDF.js"></script>
  <script src="js/tutoriaCargarDatos.js"></script>
  <link rel="icon" href="images/favicon.ico">

  <style>

    body{

      width: 210mm;
      height: 297mm;
      margin-left: auto;
      margin-right: auto;
      font-size: 10px;

    }

    div input[type=text]{
      font-size: 11px;
    }

    div input[type=date]{
      font-size: 11px;
    }

  </style>

  <style media='print'>

    /* Para Safari, Chrome, Opera:
      -webkit-appearance: none;

    Para Firefox:
      -moz-appearance: none; */
  #volver{display:none;} /* esto oculta los input cuando imprimes */
  #flechaMes{border:none; appearance: none; -moz-appearance: none; -webkit-appearance: none;}
  #prueba{border:none; appearance: none; -moz-appearance: none; -webkit-appearance: none;}
  #noMostrar{border:none;}

  @page{
    margin: 0px;
    margin-top: 10px;
    margin-left: auto;
    margin-right: auto;

  }


  </style>


</head>
<body>

<div class="container-fluid">

      <div class="row mb-2 mt-2" id="volver" >
        <div class="col-12 text-center">
          <button class="col-5 btn btn-success text-center" onclick="imprimir()"><img class="me-3" src="../images/iconos/printer.svg">IMPRIMIR</button>
          <button class="col-5 btn btn-danger text-center" onclick="volverAtrasAlumno()"><img class="me-3" src="../images/iconos/arrow-left.svg">VOLVER</button>
        </div>
      </div>

      <div class="row pt-3 pb-2" style="border: #b0d588 2px solid; border-radius: 6px;">

        <div class="col-4">

          <img src="images/logoWord.jpg" height=75px; width="230px">

        </div>

        <div class="col-8 d-flex justify-content-center">
            <label class="fw-bold align-self-center fs-6">DATOS DEL PLAN DE FORMACIÓN DEL <input id="noMostrar" class="fw-bold w-25"></input></label>
        </div>

      </div>

      <div class="row">

        <div class="col-12 text-center">

          <label class="mt-1 me-5 fw-bold" style="font-size:14px;">ENTIDAD ORGANIZADORA: DIXMA</label>
        
        </div>

      </div>

      <div class="row">
        <label class="fw-bold border border-2 w-100 mt-1 py-1 rounded" id="color" style="font-size:12px;background: #bcbcbc;">DATOS DEL PARTICIPANTE:</label>
      </div>

      <div class="row" style="border: #b0d588 2px solid; border-radius: 6px;">

      <div class="row mt-1">

        <label class="col-2 col-form-label">NOMBRE Y APELLIDOS:</label>
          <div class="col-6">
            <input class="form-control form-control-sm text-uppercase" value="<?php echo $alumno['nombre'] . " " . $alumno['apellidos'] ?>" type="text"></input>
          </div>        
      
        <label class="col-1 col-form-label ">NIF:</label>
          <div class="col-3">
            <input class="form-control form-control-sm" type="text" value="<?php echo $alumno['nif'] ?>"></input>
          </div>    

      </div>

        <div class="row">
          <label class="col-2 col-form-label">TELEFONO:</label>
            <div class="col-2">
              <input class="form-control form-control-sm" value="<?php echo $alumno['telefono'] ?>" type="text"></input>
            </div>

            <label class="col-1 col-form-label">EMAIL:</label>
            <div class="col-3">
              <input class="form-control form-control-sm" value="<?php echo $alumno['email'] ?>" type="text"></input>
            </div>

        </div>

        <div class="row">

        <!-- <label class="col-2 col-form-label">Nº Segurida Social:</label>
          <div class="col-4">
            <input class="form-control form-control-sm" value="<?php echo $alumno['numeroSeguridadSocial'] ?>" type="text"></input>
          </div> -->

          <label class="col-2 col-form-label">F. NACIMIENTO:</label>
            <div class="col-3">
              <input type="text" class="form-control form-control-sm" value=""></input>
            </div>

          <label class="col-1 col-form-label">SEXO:</label>
          <div class="col-2 col-form-label">
            <label>Hombre</label>
            <input id="sexoHombre" type="checkbox" class="form-check-input" type="text"></input>

            <label>Mujer</label>
            <input id="sexoMujer" type="checkbox" class="form-check-input" type="text"></input>
          </div>

          <label class="col-2 col-form-label">DISCAPACIDAD:</label>
          <div class="col-2 col-form-label">
            <label>Si</label>
            <input id="discapacidadSi" type="checkbox" class="form-check-input" type="text"></input>

            <label>No</label>
            <input id="discapacidadNo" type="checkbox" class="form-check-input" type="text"></input>
          </div>

        </div> 
          
        <div class="row">

        <label class="col-3 col-form-label fw-bold">CATEGORIA PROFESIONAL:</label>
          <div class="col-9 col-form-label"  >
            <label>Directivo</label>
            <input id="directivo" type="checkbox" id="directivo" class="form-check-input" type="text"></input>

            <label class="ms-3">Mando intermedio</label>
            <input id="intermedio" type="checkbox" class="form-check-input" type="text"></input>

            <label class="ms-3">Técnico</label>
            <input id="tecnico" type="checkbox" class="form-check-input" type="text"></input>

            <label class="ms-3">Trabajador cualificado</label>
            <input id="cualificado" type="checkbox" class="form-check-input" type="text"></input>

            <label class="ms-3">Trabajador con baja cualificación</label>
            <input id="bajaCualificacion" type="checkbox" class="form-check-input" type="text"></input>
          </div>

        </div>          
          
        <div class="row">

          <label class="col-3 col-form-label fw-bold">COLECTIVO:</label>
            <div class="col-9 col-form-label"  >
              <label class="">Régimen general</label>
              <input id="regimenGeneral" type="checkbox" class="form-check-input" type="text"></input>

              <label class="ms-3">Fijo discontinuo</label>
              <input id="fijoDiscontinuo" type="checkbox" class="form-check-input" type="text"></input>

              <label class="ms-3">Otros</label>
              <input id="otros" type="checkbox" class="form-check-input" type="text"></input>
            </div>

        </div>      
          
        <div class="row">

        <label class="col-form-label fw-bold">GRUPO DE COTIZACIÓN A LA TGSS:</label>
          <div class="col-6 ">
            <input id="1" type="checkbox" class="form-check-input" type="text"></input>
            <label class="me-3">(1) Ingenieros y licenciados</label>
            <br>
            <input id="2" type="checkbox" class="form-check-input" type="text"></input>
            <label class="me-3">(2) Ingenieros técnicos, Peritos y Ayudantes titulados</label>
            <br>
            <input id="3" type="checkbox" class="form-check-input" type="text"></input>
            <label class="me-3">(3) Jefes administrativos y de taller</label>
            <br>
            <input id="4" type="checkbox" class="form-check-input" type="text"></input>
            <label class="me-3">(4) Ayudantes no titulados</label>
            <br>
            <input id="5" type="checkbox" class="form-check-input" type="text"></input>
            <label class="me-3">(5) Oficiales administrativos</label>
            <br>
            <input id="6" type="checkbox" class="form-check-input" type="text"></input>
            <label class="me-3">(6) Subalternos</label>
        </div>    

        <div class="col-6"  >
            <input id="7" type="checkbox" class="form-check-input" type="text"></input>
            <label class="me-3">(7) Auxiliares administrativos</label>
            <br>
            <input id="8" type="checkbox" class="form-check-input" type="text"></input>
            <label class="me-3">(8) Oficiales de primera y segunda</label>
            <br>
            <input id="9" type="checkbox" class="form-check-input" type="text"></input>
            <label class="me-3">(9) Oficiales de tercera y especialistas</label>
            <br>
            <input id="10" type="checkbox" class="form-check-input" type="text"></input>
            <label class="me-3">(10) Trabajadores mayores de 18 años no cualificados</label>
            <br>
            <input id="11" type="checkbox" class="form-check-input" type="text"></input>
            <label class="me-3">(11) Trabajadores menores de 18 años</label>
        </div>  

        </div>
          
        <div class="row">

          <label class="col-form-label fw-bold">NIVEL DE ESTUDIOS:</label>
          <div class="col-6"  >
            <input id="menosPrimaria" type="checkbox" class="form-check-input" type="text"></input>
            <label class="me-3">Menos que primaria</label>
            <br>
            <input id="primaria" type="checkbox" class="form-check-input" type="text"></input>
            <label class="me-3">Educación primaria</label>
            <br>
            <label class="me-3">
            <input id="educacionSecundaria1" type="checkbox" class="form-check-input" type="text"></input>
            1ª etapa de Educacion Secundaria (1º y 2º ciclo de la ESO, Graduado Escolar, Certificado Profesional de nivel 1 y 2)</label>
            <br>
            <label class="me-3">
            <input id="educacionSecundaria2" type="checkbox" class="form-check-input" type="text"></input>
            2ª etapa de Educacion Secundaria (Bachillerato, FP de grado medio, BUP, FP 1 y FP 2)</label>
            <br>
            <input id="educacionPostsecundaria" type="checkbox" class="form-check-input" type="text"></input>
            <label class="me-3">Educacion Postsecundaria no superior (Certificado Profesional de nivel 3)</label>
          </div>    

          <div class="col-6"  >
            <input id="tecnicoSuperior" type="checkbox" class="form-check-input" type="text"></input>
            <label class="me-3">Tecnico superior / FP de grado superior y equivalentes</label>
            <br>
            <input id="universitarios1" type="checkbox" class="form-check-input" type="text"></input>
            <label class="me-3">E. Universitarios 1º ciclo (Diplomatura-Grados)</label>
            <br>
            <input id="universitarios2" type="checkbox" class="form-check-input" type="text"></input>
            <label class="me-3">E. Universitarios 2º ciclo (Licenciatura-Máster)</label>
            <br>
            <input id="universitarios3" type="checkbox" class="form-check-input" type="text"></input>
            <label class="me-3">E. Universitarios 3º ciclo (Doctorado)</label>
            <br>
            <input id="otras" type="checkbox" class="form-check-input" type="text"></input>
            <label class="me-3">Otras titulaciones: Especificar</label>
          </div>  

        </div> 
          
        <div class="row mt-2 mb-2">

            <label class="col-3">COSTE HORA PARTICIPANTE:</label>
            <div class="col-4"  >
              <input class="form-control form-control-sm" value="<?php echo $alumno['costeHora'] ?>" type="text"></input>
            </div>

            <label class="col-2 col-form-label">HORARIO LABORAL:</label>
            <div class="col-3"  >
              <input class="form-control form-control-sm" value="<?php echo $alumno['horarioLaboral'] ?>" type="text"></input>
            </div>

        </div> 

        <label class="text-center">(Salario Bruto anual + seguridad Social a cargo de la empresa + Dietas + Variables) / Nº horas trabajadas Anual (1750)</label>
      </div>

      <div class="row mt-1">
        <label class="fw-bold border border-2 w-100 py-1 rounded" style="font-size:12px; background-color: #bcbcbc;">DATOS DE LA EMPRESA</label>
      </div>

      <div class="row pb-1" style="border: #b0d588 2px solid; border-radius: 6px;">
      
        <div class="row mt-1">

            <label class="col-3 col-form-label">NOMBRE / RAZÓN SOCIAL:</label>
            <div class="col-9">
              <input class="form-control form-control-sm" value="<?php echo $empresa['nombre'] ?>" type="text"></input>
            </div>

        </div>

        <div class="row">

            <label class="col-3 col-form-label">CIF:</label>
            <div class="col-3">
              <input type="text" value="<?php echo $empresa['cif'] ?>" class="form-control form-control-sm"></input>
            </div>

            <label class="col-3 col-form-label text-center">SEG. SOCIAL EMPRESA (CCC):</label>
            <div class="col-3">
              <input id="noMostrar" class="form-control form-control-sm" type="text"></input>
            </div>

        </div>
        
        <div class="row">
          <label class="col-3 col-form-label">DOMICILIO:</label>
          <div class="col-9">
            <input id="noMostrar" value="<?php echo $empresa['calle'] ?>" class="form-control form-control-sm" type="text"></input>
          </div>
        </div>

        <div class="row">
          <label class="col-3 col-form-label">LOCALIDAD:</label>
          <div class="col-2">
            <input id="noMostrar" value="<?php echo $empresa['poblacion'] ?>" class="form-control form-control-sm" type="text"></input>
          </div>

          <label class="col-1 col-form-label">CP:</label>
          <div class="col-2">
            <input id="noMostrar" value="<?php echo $empresa['cp'] ?>" class="form-control form-control-sm" type="text"></input>
          </div>

          <label class="col-1 col-form-label">PROVINCIA:</label>
          <div class="col-3">
            <input id="noMostrar" value="<?php echo $empresa['provincia'] ?>" class="form-control form-control-sm" type="text"></input>
          </div>

        </div>

        <div class="row">
          <label class="col-3 col-form-label">EMAIL:</label>
          <div class="col-4">
            <input id="noMostrar" value="<?php echo $empresa['email'] ?>" class="form-control form-control-sm" type="text"></input>
          </div>

          <label class="col-2 col-form-label">TELÉFONO/S:</label>
          <div class="col-3">
            <input id="noMostrar" value="<?php echo $empresa['telef1'] ?>" class="form-control form-control-sm" type="text"></input>
          </div>
        </div>

      </div>

      <div class="row">
        <label class="fw-bold border border-2 w-100 mt-1 py-1 rounded" style="font-size:12px; background-color: #bcbcbc;">DATOS DE LA ACCIÓN FORMATIVA (A cumplimentar por la Entidad Organizadora)</label>
      </div>

      <div class="row" style="border: #b0d588 2px solid; border-radius: 6px;">

        <div class="row mt-1">

          <label class="col-2 col-form-label"> Nº ACCIÓN:</label>
            <div class="col-2">
              <input class="form-control form-control-sm" 
              type="text" 
              value="<?php if($alumnoCurso) echo $alumnoCurso['N_Accion'] ?>"></input>
            </div>

            <label class="col-2 col-form-label">DENOMINACIÓN:</label>
            <div class="col-6 border border-2 border-bottom-0 rounded-top">
              <input id="noMostrar" class="form-control form-control-sm" type="text"
              <input id="noMostrar" class="form-control form-control-sm text-uppercase" type="text"
              value="<?php if($alumnoCurso) echo $alumnoCurso['Denominacion'] ?>"></input>
            </div>             

        </div>

        <div class="row">

          <label class="col-2 col-form-label">Nº GRUPO:</label>
            <div class="col-2">
              <input class="form-control form-control-sm" type="text"
              value="<?php if($alumnoCurso) echo $alumnoCurso['N_Grupo'] ?>"></input>
            </div>

            <label class="col-2 col-form-label"></label>
            <div class="col-6 border border-2 border-top-0 rounded-bottom mb-1">
              <input id="noMostrar" class="form-control form-control-sm" type="text"></input>
            </div> 
            
        </div>
        
        <div class="row">
          <label class="col-2 col-form-label">Nº HORAS:</label>
            <div class="col-2">
              <input class="form-control form-control-sm" type="text"
              value="<?php if($alumnoCurso) echo $alumnoCurso['N_Horas'] ?>"></input>
            </div>
            
            <label class="col-2 col-form-label">MODALIDAD:</label>
            <div class="col-2">
              <input class="form-control form-control-sm" type="text"
              value="<?php if($alumnoCurso) echo $alumnoCurso['Modalidad'] ?>"></input>
            </div>

            <label class="col-1 col-form-label">HORARIO:</label>
            <div class="col-3">
              <input class="form-control form-control-sm" type="text"></input>
            </div>

        </div>

        <div class="row">
          <label class="col-2 col-form-label">FECHA INICIO:</label>
            <div class="col-4">
              <input class="form-control form-control-sm" type="text"
              value="<?php if($alumnoCurso) echo $alumnoCurso['Fecha_Inicio'] ?>"></input>
            </div>

            <label class="col-2 col-form-label">FECHA FIN:</label>
            <div class="col-4">
              <input class="form-control form-control-sm" type="text"
              value="<?php if($alumnoCurso) echo $alumnoCurso['Fecha_Fin'] ?>"></input>
            </div>
        </div>

        <div class="row">
          <label class="col-2">LUGAR DE LA IMPARTICIÓN:</label>
            <div class="col-6">
              <input class="form-control form-control-sm" type="text"></input>
            </div>

            <label class="col-1 col-form-label">DOC A.F:</label>
            <div class="col-3">
              <input class="form-control form-control-sm" type="text"
              value="<?php if($alumnoCurso) echo $alumnoCurso['DOC_AF'] ?>"></input>
            </div>
        </div>

        <div class="row">
          <label class="col-2">TELEFONO LUGAR DE IMPARTICIÓN:</label>
            <div class="col-4">
              <input class="form-control form-control-sm" type="text"></input>
            </div>

            <label class="col-3 col-form-label">LOCALIDAD Y PROVINCIA:</label>
            <div class="col-3">
              <input class="form-control form-control-sm" type="text"></input>
            </div>
        </div>

        <div class="row text-center">
          <div class="col-12 d-flex justify-content-center mt-2 mb-1">
            <table class="">
              <tr>
                <th class="border border-secondary" style="width: 30px;">L</th>
                <th class="border border-secondary" style="width: 30px;">M</th>
                <th class="border border-secondary" style="width: 30px;">X</th>
                <th class="border border-secondary" style="width: 30px;">J</th>
                <th class="border border-secondary" style="width: 30px;">V</th>
                <th class="border border-secondary" style="width: 30px;">S</th>
                <th class="border border-secondary" style="width: 30px;">D</th>
              </tr>
              <tr>
                <td class="border border-secondary" style="height: 20px;"></td>
                <td class="border border-secondary" style="height: 20px;"></td>
                <td class="border border-secondary" style="height: 20px;"></td>
                <td class="border border-secondary" style="height: 20px;"></td>
                <td class="border border-secondary" style="height: 20px;"></td>
                <td class="border border-secondary" style="height: 20px;"></td>
                <td class="border border-secondary" style="height: 20px;"></td>

              </tr>
            </table>
          </div>
        </div>

      </div>

      <div class="row">
        
        <div class="col-12 text-center mt-2">
          <label class="col-md-auto col-form-label">En <input id="noMostrar" class="text-center" value="Vigo" style="width: 50px"> </input>
            a <input id="noMostrar" class="text-center"  style="width: 30px"> </input> de 
            <select  id="flechaMes" class="text-center"> 
              <option>Enero</option>
              <option>Febrero</option>
              <option>Marzo</option>
              <option>Abril</option>
              <option>Mayo</option>
              <option>Junio</option>
              <option>Julio</option>
              <option>Agosto</option>
              <option>Septiembre</option>
              <option>Octubre</option>
              <option>Noviembre</option>
              <option>Diciembre</option>
            </select> 
            de<input id="noMostrar" class="text-center" style="width: 50px"> </input> </label>
        </div>

      </div>

      <div class="row mt-4">

        <div class="col-12" style="font-size: 8px;">

          <p class="lh-1"><small>Datos del responsable del tratamiento: 
            Identidad: DIXMA - NIF: E27876325 - Dirección postal: CTRA. DE MADRID, 152, 36318, VIGO, PONTEVEDRA - Teléfono: 604067035 - 
            Correo electrónico: info@dixmaformacion.com “Le informamos que tratamos la información que nos facilita con el fin de prestarles 
            el servicio solicitado y realizar su facturación. Los datos proporcionados se conservarán mientras se mantenga la relación comercial 
            o durante el tiempo necesario para cumplir con las obligaciones legales y atender las posibles responsabilidades que pudieran derivar 
            del cumplimiento de la finalidad para la que los datos fueron recabados. Los datos no se cederán a terceros salvo en los casos en que
            exista una obligación legal. Usted tiene derecho a obtener información sobre si en DIXMA estamos tratando sus datos personales, por lo
            que puede ejercer sus derechos de acceso, rectificación, supresión y portabilidad de datos y oposición y limitación a su tratamiento 
            ante DIXMA, CTRA. DE MADRID, 152, 36318, VIGO, PONTEVEDRA o en la dirección de correo electrónico info@dixmaformacion.com, 
            adjuntando copia de su DNI o documento equivalente.  Asimismo, y especialmente si considera que no ha obtenido satisfacción plena en 
            el ejercicio de sus derechos, podrá presentar una reclamación ante la autoridad nacional de control dirigiéndose a estos efectos a la 
            Agencia Española de Protección de Datos, C/ Jorge Juan, 6 - 28001 Madrid.
            Asimismo, solicitamos su autorización para ofrecerle productos y servicios relacionados con los contratados y fidelizarle como cliente.  
            SI <input type="checkbox" class="form-check-input"></input> NO <input type="checkbox" class="form-check-input"></input></small>
          </p>

        </div>

      </div>

</div>
<?php

  $sexoAlumno = $alumno['sexo'];
  $categoriaProfesional = $alumno['categoriaProfesional'];
  $colectivo = $alumno['colectivo'];
  $grupoCotizacion = $alumno['grupoCotizacion'];
  $nivelEstudios = $alumno['nivelEstudios'];
  $discapacidad = $alumno['discapacidad'];

?>
<script>
  cargarDatos(<?php echo "'" . $sexoAlumno . "', '" . $categoriaProfesional . "', '" . $colectivo . "', '" . $grupoCotizacion . "', '" . $nivelEstudios . "', '" . $discapacidad . "'"; ?>);

</script>

</body>
</html>