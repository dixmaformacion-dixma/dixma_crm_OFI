<?php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  include "funciones/conexionBD.php";
  include "funciones/funcionesAlumnos.php";
  include "funciones/funcionesEmpresa.php";
  include "funciones/funcionesAlumnosCursos.php";
  include "funciones/funcionesContenidos.php";

  setlocale(LC_ALL, 'ES_es');

  session_start();

  if(empty($_SESSION)){

      header("Location: index.php");

  }

  date_default_timezone_set("Europe/Madrid");
  setlocale(LC_ALL, "spanish");

  if(!isset($_GET['StudentCursoID'])){
    die("Parameters are missing!");
  }


  ##procedure to get the courses which belong to this company

    $conexionPDO = realizarConexion();
    $sql = '
    SELECT
    alumnos.nombre as nombre,
    alumnos.apellidos as apellidos,
    alumnos.nif as nif,
    empresas.nombre as nombreEmpresa,
    empresas.cif as cif,
    alumnocursos.*
    FROM `alumnocursos` join alumnos on alumnocursos.idAlumno = alumnos.idAlumno 
    join empresas on alumnocursos.idEmpresa = empresas.idempresa 
    WHERE `StudentCursoID` = ?
    ';

    $conexionPDO->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
    $stmt = $conexionPDO->prepare($sql);
        
    $stmt->bindValue(1, $_GET['StudentCursoID'], PDO::PARAM_INT);

    $stmt->execute();

    if($alumnocurso = $stmt->fetch()){
      unset($conexionPDO);
    } else {
      //echo $sql;
      die("Error occured while fetching course information");;
    }
  ##end of procedure

   $contenido = cargarContenidoAccion($alumnocurso['N_Accion'], date('Y',strtotime($alumnocurso['Fecha_Inicio'])));
$fecha_inicio             = date('Y-m-d', strtotime($alumnocurso['Fecha_Inicio']));
$fecha_fin                = date('Y-m-d', strtotime($alumnocurso['Fecha_Fin']));
$same_date                = ($fecha_inicio === $fecha_fin);
$fecha_expedicion         = date("Y-m-d", strtotime($fecha_fin . ' +5 days'));
$fecha_inicio_display     = formattedDate($fecha_inicio);
$fecha_fin_display        = formattedDate($fecha_fin);
$day_of_week = date('N', strtotime($fecha_expedicion)); 
if ($day_of_week == 6) {
    $fecha_expedicion = date("Y-m-d", strtotime($fecha_expedicion . ' +2 days'));
} elseif ($day_of_week == 7) {
    $fecha_expedicion = date("Y-m-d", strtotime($fecha_expedicion . ' +1 day'));
}
$fecha_expedicion_display = formattedDate($fecha_expedicion);
?>

<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php
  // Prepara el nombre del archivo para descargar 
  $filename = $alumnocurso['nombre'] . ' ' . $alumnocurso['apellidos'];
  // Convertir caracteres acentuados en versiones sin acento
  $filename = str_replace(
    ['á', 'é', 'í', 'ó', 'ú', 'ñ', 'Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ', 'ü', 'Ü'],
    ['a', 'e', 'i', 'o', 'u', 'n', 'A', 'E', 'I', 'O', 'U', 'N', 'u', 'U'],
    $filename
  );
  $filename = str_replace(' ', '_', $filename);
  // Agregar N_Accion y N_Grupo al inicio
  $filename = $alumnocurso['N_Accion'] . '_' . $alumnocurso['N_Grupo'] . '_' . $filename;
  echo $filename;
?></title>

  <link href="css/bootstrap.min.css" rel="stylesheet"></link>
  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/jquery.min.js"></script>
  <link rel="icon" href="images/favicon.ico">

  <style>
    @font-face {
      font-family: nostalgia;
      src: url(images/diploma/NostalgiaScript-Regular.ttf);
    }
    @font-face {
      font-family: liana;
      src: url(images/diploma/Liana_Regular.ttf);
    }
    @font-face {
      font-family: andasia;
      src: url(images/diploma/Andasia_Regular.ttf);
    }
    *{
      box-sizing: border-box;
    }
    .body1{
      width: 297mm;
      max-width: 297mm;
      margin-left: auto;
      margin-right: auto;
      font-size: 10px;
      -webkit-print-color-adjust: exact;
      print-color-adjust: exact;
    }
    
    .body2{
      width: 209mm;
      max-width: 209mm;
      margin-left: auto;
      margin-right: auto;
      font-size: 10px;
      -webkit-print-color-adjust: exact;
      print-color-adjust: exact;
    }

    .pagewrapper{
      width: 297mm;
      height: 209mm;
      max-height: 210mm;
    }
    
    .pagewrapper2{
      height: 297mm;
      width: 209mm;
      max-height: 298mm;
    }
    
    .page{
      background-color:#ecfdfc;
      background-repeat: no-repeat;
      background-size: cover;
      height: 100%;
      padding: 0.75cm;
      position:relative
    }

    .inputrow{
      display: flex;
      align-items: center;
    }
    .single-input{
      display: flex;
      align-items: center;
    }
    .label{
      font-size: 3.6mm;
      color:#083670;
    }
    .single-input input, .single-input .dato, .single-input textarea{
      font-size: 4.25mm;
      font-weight: bold;
      border:0;
      border-radius:0px;
      border-bottom: 2px dashed #00000059;
      padding: 0px;
      margin-left:0.4cm;

    }
    #contenidos{
      width:100%;
      height:100%;
      border:2px solid #083670;
      padding-left:6mm;
      padding-right:6mm;

      color:#083670;
      font-weight:bold;
      resize:none;
    }
    #contenidosprivado{
      border:0px solid #083670;
      height: 100%;
      width:100%;
      padding: 22mm 23mm;
      font-size:6mm;
      resize:none;
      background-color: rgba(0,0,0,0);
    }
    .estetica_info{
      color: #696246;
    }
    #contenidosestetica{
      border:0px solid #083670;
      height: 100%;
      width:100%;
      padding: 42mm 43mm;
      font-size:6mm;
      resize:none;
      background-color: rgba(0,0,0,0);
      color: #696246;
    }
    
    #contenidossoldadura{
      border:0px solid #083670;
      height: 100%;
      width:100%;
      padding: 45mm 25mm;
      font-size:6mm;
      resize:none;
      background-color: rgba(0,0,0,0);
    }


  </style>

  <style media='print'>
    #contenidos{
      overflow:hidden;
    }
    #contenidosprivado{
      overflow:hidden;
    }
    #volver{display:none;} /* esto oculta los input cuando imprimes */
  </style>
  <style id="media_print" media='print'>
    @page {
      size: landscape;
      margin: 0 !important; 
      padding: 0 !important;
      height:100%;
    }
  </style>

<script>
function MostrarDiploma(tipo, aditional = false){
  var diplomas = ['bonificado', 'privado', 'estetica', 'soldadura'];
  for(diploma of diplomas){
    console.log(diploma);
    document.getElementById(diploma).style.display = 'none'
  }
  document.getElementById(tipo).style.display = 'block';
  if(tipo == "soldadura"){
    document.querySelector("#bydo").classList.add('body2');
    document.querySelector("#bydo").classList.remove('body1');
    document.querySelector("#media_print").innerHTML = '@page {size: portrait; margin: 0 !important; padding: 0 !important;height:100%;}';
  }
  else
  {
    if(tipo == "privado"){
      document.getElementById("privado-back").style.backgroundImage = 'url(images/diploma/privado'+aditional+'.png)';
      
      if(aditional=="1"){
        $('.firma_docente').hide();
        document.getElementById("fecha_privado").style.left="138mm";
        for(var i of document.getElementsByClassName("entidadText")){
         i.style.left="5.5cm"; 
        }
      }
      else{
        $('.firma_docente').show();
        document.getElementById("fecha_privado").style.left="107mm";
        for(var i of document.getElementsByClassName("entidadText")){
         i.style.left="4.5cm"; 
        }
      }
      
    }
    document.querySelector("#bydo").classList.add('body1');
    document.querySelector("#bydo").classList.remove('body2');
    document.querySelector("#media_print").innerHTML = '@page {size: landscape; margin: 0 !important; padding: 0 !important;height:100%;}';
  }
  

  
}
</script>
</head>
<body id="bydo" class="body1">
<div class="row mb-2 mt-2" id="volver" >
  <div class="col-12 text-center">
    <button class="col-5 btn btn-success text-center" onclick="window.print(1)"><img class="me-3" src="./images/iconos/printer.svg">IMPRIMIR</button>
  </div>
  <div class="py-2 d-flex" style="justify-content:center">
    <button class="col-2 btn text-center"
    onclick="MostrarDiploma('bonificado');"
    >Bonificado</button>
    <button class="col-2 btn text-center"
    onclick="MostrarDiploma('privado', '1')"
    >Privado</button>
    <button class="col-2 btn text-center"
    onclick="MostrarDiploma('privado', '3')"
    >Privado firma docente</button>
    <button class="col-2 btn text-center"
    onclick="MostrarDiploma('estetica')"
    >Estética</button>
    <button class="col-2 btn text-center"
    onclick="MostrarDiploma('soldadura')"
    >Soldadura</button>
  </div>
</div>
<div id="bonificado">
  
  <div class="pagewrapper" style="padding:1cm">
    <div class="page" style="background:url(images/diploma/template.jpg);">
      <div class="d-flex justify-content-end">
        <img src="images/logoWord.jpg" style="height:1.5cm; margin:0.25cm">
      </div>
      <div style="margin-top: 5.85cm; margin-left:0.85cm; margin-right:0.85cm">
        <div class="inputrow">
          <div class="single-input">
            <div class="label" class="width:1.2cm">D./Dña.</div>
            <input class="form-control" style="width:17.5cm" value="<?php echo $alumnocurso['nombre']." ".$alumnocurso['apellidos'] ?>" type="text"></input>
          </div>
          <div class="single-input" style="margin-left:0.3cm">
            <div class="label">con NIF</div>
            <input class="form-control" style="width:2.6cm" value="<?php echo $alumnocurso['nif'] ?>" type="text"></input>
          </div>
        </div>

        <div class="inputrow">
          <div class="single-input">
            <div class="label" class="width:6cm">que presta sus servicios en la Empresa</div>
            <input class="form-control" style="width:12.7cm" value="<?php echo $alumnocurso['nombreEmpresa'] ?>" type="text"></input>
          </div>
          <div class="single-input">
            <div class="label" style="margin-left:0.3cm">con CIF</div>
            <input class="form-control" style="width:2.6cm" value="<?php echo $alumnocurso['cif'] ?>" type="text"></input>
          </div>
        </div>

        <div class="inputrow" style="margin-top: 0.75cm">
          <div class="single-input">
            <div class="label">Ha superado con evaluación positiva la Acción Formativa</div>
            <div class="dato" style="width:14.2cm;" contenteditable="true"><?php echo mb_strtoupper($alumnocurso['Denominacion'],'UTF-8') ?></div>
            <!--<input class="form-control" style="width:14.2cm;" value="<?php echo mb_strtoupper($alumnocurso['Denominacion'],'UTF-8') ?>" type="text"></input>-->
          </div>
        </div>

        <div class="inputrow">
          <div class="single-input">
            <div class="label">Código AF / Grupo</div>
            <input class="form-control" style="width:2cm; text-align:center" value="<?php echo $alumnocurso['N_Accion'] ?>" type="text"></input>
            <div class="label" tyle="margin-right:1mm">/</div>
            <input class="form-control" style="width:2.5cm; margin-left:0px; text-align:center" value="<?php echo $alumnocurso['N_Grupo'] ?>" type="text"></input>
          </div>
          <div class="single-input" style="margin-left:0.3cm">
            <div class="label">Durante los días</div>
            <input class="form-control" style="width:3cm; text-align:center" value="<?php echo formattedDate($alumnocurso['Fecha_Inicio']) ?>" type="text"></input>
            <div class="label" style="margin-left:5mm; margin-right:5mm">al</div>
            <input class="form-control" style="width:3cm; margin-left:0px; text-align:center" value="<?php echo formattedDate($alumnocurso['Fecha_Fin']) ?>" type="text"></input>
          </div>
        </div>

        <div class="inputrow">
          <div class="single-input">
            <div class="label">con una duración total de</div>
            <input class="form-control" style="width:2cm; text-align:center" value="<?php if($alumnocurso['Modalidad'] == "Teleformación"){echo $alumnocurso['N_Horas'];}else{echo "0";} ?>" type="text"></input>
          </div>
          <div class="single-input" style="margin-left:0.3cm">
            <div class="label">horas en la modalidad formativa</div>
            <input class="form-control" style="width:6.25cm" value="Teleformación" type="text"></input>
          </div>
        </div>
        <div class="inputrow">
          <div class="single-input">
            <div class="label" style="width: 4.1cm"></div>
            <input class="form-control" style="width:2cm; text-align:center" value="<?php if($alumnocurso['Modalidad'] == "Presencial"){echo $alumnocurso['N_Horas'];}else{echo "0";} ?>" type="text"></input>
          </div>
          <div class="single-input" style="margin-left:0.3cm">
            <div class="label">horas en la modalidad formativa</div>
            <input class="form-control" style="width:6.25cm" value="Presencial" type="text"></input>
          </div>
        </div>

        <div class="inputrow" style="justify-content:start">
          <div class="label" style="margin-top: 0.25cm"><b>Contenidos impartidos (Ver dorso)</b></div>
        </div>

        <!-- signatures -->
        <div class="inputrow" style="justify-content:space-between; margin-top: 1.5cm; align-items: baseline; margin-left: 2.5cm; margin-right:4.5cm">
          <div>
            <div class="label" style="width:6.5cm; font-size:3mm; text-align:center"><b>Firma y sello de la entidad responsable de impartir la formación</b></div>
			        <?php if($alumnocurso['diploma_sin_firma']==0): ?>
                <div style="position:absolute; bottom:-8mm;"><img src="images/selloDixma.png" style="height: 4.5cm; position:relative; left:0.5cm"></div>
              <?php endif ?>
          </div>
          <div>
            <div class="label" style="font-size:3mm; text-align:center"><b>Fecha de expedición</b></div>
            <div class="d-flex" style="text-align:center; justify-content:center;"><input class="form-control border-0" style="width:7.25cm; text-align:center; font-weight:bold" value="<?php echo $fecha_expedicion_display; ?>" type="text"></input></div>
          </div>
          <div>
            <div class="label" style="font-size:3mm; text-align:center"><b>Firma del trabajador/a</b></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="pagewrapper" style="padding:1cm">
    <div class="page" style="background:white">
      <div class="inputrow">
        <div class="label" style="font-weight:bold; text-decoration:underline">Contenidos impartidos:</div>
      </div>
      <!--<textarea id="contenidos"></textarea>-->
      <div id="contenidos"><?php echo isset($contenido["Contenido"]) && !empty($contenido["Contenido"]) ? $contenido["Contenido"] : ""; ?></div>
    </div>
  </div>
  <div class="row mb-2 mt-2" id="volver">
    <script>
      let contenidosElement = document.getElementById("contenidos");
      resetContenidosFontSize();


      function changeContenidosFontSize(by){
        currentsize = parseFloat(contenidosElement.style.fontSize.split("mm")[0]);
        console.log(currentsize);
        contenidosElement.style.fontSize = (currentsize + by) + "mm";
      }
      function resetContenidosFontSize(){
        contenidosElement.style.fontSize = "6mm";
      }
    </script>
    <div class="col-12 text-center">
      Change Font size for page 2:
      <button class="col-1 btn btn-success text-center" onclick="changeContenidosFontSize(0.1)">(+)</button>
      <button class="col-1 btn btn-primary text-center" onclick="resetContenidosFontSize(0.1)">Reset</button>
      <button class="col-1 btn btn-danger text-center" onclick="changeContenidosFontSize(-0.1)">(-)</button>
    </div>
  </div>  

</div>
<div id="privado" style="display:none">
  <div class="pagewrapper" style="padding:0mm;">
    <div class="page" id="privado-back" style="background:url(images/diploma/privado1.png); background-repeat: no-repeat; background-size: cover;">
      <div class="d-flex justify-content-center">
        <div id="nombre" style="text-align:center;font-family:andasia;font-size:2cm;padding-top:6.7cm;margin-bottom: 40px;text-transform: capitalize;"  contenteditable="true">
          <?php echo mb_strtolower($alumnocurso['nombre'].' '.$alumnocurso['apellidos']); ?>
        </div>
      </div>
      <div class="d-flex justify-content-center">
        <div style="text-align:center; font-size:6mm; margin-top:-1.26cm">con DNI: <span id="DNI"></span> ha realizado satisfactoriamente la formación de:</div>
      </div>
      <div class="d-flex justify-content-center">
        <div id="Denominacion" style="text-align:center; font-weight:bold; font-size:9mm;"></div>
      </div>
      <div class="d-flex justify-content-center">
        <div id="details" style="text-align:center; font-size:6mm; margin-top:0.75cm;"></div>
      </div>
      <div class="entidadText" style="text-align: center;font-size: 3.9mm;position: absolute;left: 5.4cm;width: 217px;bottom: 0.6cm;z-index: 10;font-weight: 300;" contentEditable="true">
        <b>Entidad Responsable de Impartir la Formación</b>
      </div>
      <div style="position:absolute; bottom:-1mm; left: 55mm">
		    <?php if($alumnocurso['diploma_sin_firma']==0): ?>
		    <img src="images/selloDixma.png" style="height: 4.5cm">
        <?php endif ?>
      </div>
      <div class="firma_docente" style="position:absolute;bottom: 0mm;left: 159mm;">
		    <?php if(!empty($alumnocurso['firma_docente'])): ?>
		    <img src="firmas/<?php echo $alumnocurso['firma_docente'] ?>" style="height: 4.5cm">
        <?php endif ?>
      </div>
      <div id="fecha_privado" style="position:absolute; bottom:22mm;">
        <div id="fecha" style="text-align:center; font-size:6mm; position:relative; left:0.5cm"></div>
      </div>
    </div>
  </div>
  <div class="row mb-2 mt-2" id="volver">
    <label>Nomre:</label>
    <input class="form-control" id="nombrefield" onchange="changefield('nombre')" value="<?php echo mb_strtolower($alumnocurso['nombre'].' '.$alumnocurso['apellidos']); ?>" type="text"></input>
    <label>DNI:</label>
    <input class="form-control" id="DNIfield" onchange="changefield('DNI')" value="<?php echo strtoupper($alumnocurso['nif']); ?>" type="text"></input>
    <label>Denominacion:</label>
    <input class="form-control" id="Denominacionfield" onchange="changefield('Denominacion')" value="<?php echo mb_strtoupper($alumnocurso['Denominacion'],'UTF-8'); ?>" type="text"></input>
    <label>Details:</label>


   <input class="form-control" id="detailsfield" onchange="changefield('details')" 
       value=" 
       <?php 
       if ($same_date) {
           echo "el $fecha_inicio_display"; 
       } else {
           echo "entre el $fecha_inicio_display y el $fecha_fin_display";
       } ?> 
       con una duración de <?php echo strtoupper($alumnocurso['N_Horas']); ?> horas en modalidad <?php echo $alumnocurso['Modalidad']; ?>" 
       type="text">
    </input>

    <?php 
      $fecha = $alumnocurso['Fecha_Fin'];
      $fecha = strtotime($fecha.' +2 days');
      $fecha_emision = date("Y-m-d",$fecha);
      if(in_array(date("w",$fecha),[6,0])){
        $fecha_emision = date("Y-m-d",strtotime($fecha_emision.' next monday'));
      }
    ?>

    <label>Fecha:</label>
    <input class="form-control" id="fechafield" onchange="changefield('fecha')" value="<?php echo formattedDate($fecha_emision) ?>" type="text"></input>
    <script>
    function changefield(id){
      let realElement = document.getElementById(id);
      let fieldElement = document.getElementById(id+"field");

      realElement.innerHTML = fieldElement.value;
      console.log(fieldElement.value);
    }
    changefield("nombre");
    changefield("DNI");
    changefield("Denominacion");
    changefield("details");
    changefield("fecha");
    </script>
  </div>
  <div class="pagewrapper" style="padding:0cm">
    <div class="page" style="background:url(images/diploma/privado2.png); background-repeat: no-repeat; background-size: cover;">
      <!--<textarea id="contenidosprivado"></textarea>-->
      <div id="contenidosprivado"><?php echo isset($contenido["Contenido"]) && !empty($contenido["Contenido"]) ? $contenido["Contenido"] : ""; ?></div>
    </div>
  </div>
  <div class="row mb-2 mt-2" id="volver">
    <script>
      let contenidosprivadosElement = document.getElementById("contenidosprivado");
      resetContenidosPrivadoFontSize();


      function changeContenidosPrivadoFontSize(by){
        currentsize = parseFloat(contenidosprivadosElement.style.fontSize.split("mm")[0]);
        console.log(currentsize);
        contenidosprivadosElement.style.fontSize = (currentsize + by) + "mm";
      }
      function resetContenidosPrivadoFontSize(){
        contenidosprivadosElement.style.fontSize = "6mm";
      }
    </script>
    <div class="col-12 text-center">
      Change Font size for page 2:
      <button class="col-1 btn btn-success text-center" onclick="changeContenidosPrivadoFontSize(0.1)">(+)</button>
      <button class="col-1 btn btn-primary text-center" onclick="resetContenidosPrivadoFontSize(0.1)">Reset</button>
      <button class="col-1 btn btn-danger text-center" onclick="changeContenidosPrivadoFontSize(-0.1)">(-)</button>
    </div>
  </div>
</div>

<div id="estetica" style="display:none">
  <div class="pagewrapper" style="padding:0mm;">
  <div class="page" style="background:url(images/diploma/estetica1.png); background-repeat: no-repeat; background-size: cover;">
    <div class="d-flex justify-content-center estetica_info">
    <div id="est_nombre" style="text-align:center; font-family:andasia; font-size:1.9cm; padding-top:8cm; text-transform: capitalize;" contenteditable="true">
      <?php echo mb_strtolower($alumnocurso['nombre'].' '.$alumnocurso['apellidos']); ?>
    </div>
    </div>
    <div class="d-flex justify-content-center estetica_info">
    <div style="text-align:center; font-size:5.5mm; margin-top:-0.10cm">con DNI: <span id="est_DNI"></span> ha realizado satisfactoriamente la formación de:</div>
    </div>
    <div class="d-flex justify-content-center estetica_info">
    <div id="est_Denominacion" style="text-align:center; font-weight:bold; font-size:8mm;  margin-top:0.50cm;"></div>
    </div>
    <div class="d-flex justify-content-center estetica_info">
    <div id="est_details" style="text-align:center; font-size:5.5mm; margin-top:0.75cm;"></div>
    </div>
    <div style="position:absolute; bottom:-1mm; left: 55mm">
      <?php if($alumnocurso['diploma_sin_firma']==0): ?>
		    <img src="images/selloDixma.png" style="height: 4.5cm">
      <?php endif ?>
    </div>
    <div style="position:absolute;bottom: 0mm;left: 156mm;">
      <?php if(!empty($alumnocurso['firma_docente'])): ?>
      <img src="firmas/<?php echo $alumnocurso['firma_docente'] ?>" style="height: 4.5cm">
      <?php endif ?>
    </div>
    <div class="estetica_info" style="position:absolute;bottom: 17mm;left: 121mm;">
    <div id="est_fecha" style="text-align:center; font-size:4mm; position:relative; left:0.5cm"></div>
    </div>
  </div>
  </div>
  <div class="row mb-2 mt-2" id="volver">
  <label>Nomre:</label>
  <input class="form-control estetica_info" id="est_nombrefield" onchange="changefield('est_nombre')" value="<?php echo mb_strtolower($alumnocurso['nombre'].' '.$alumnocurso['apellidos']); ?>" type="text"></input>
  <label>DNI:</label>
  <input class="form-control estetica_info" id="est_DNIfield" onchange="changefield('est_DNI')" value="<?php echo strtoupper($alumnocurso['nif']); ?>" type="text"></input>
  <label>Denominacion:</label>
  <input class="form-control estetica_info" id="est_Denominacionfield" onchange="changefield('est_Denominacion')" value="<?php echo mb_strtoupper($alumnocurso['Denominacion'],'UTF-8'); ?>" type="text"></input>
  <label>Details:</label>


  <input class="form-control estetica_info" id="est_detailsfield" onchange="changefield('est_details')" value=" entre el <?php echo formattedDate(strtoupper($alumnocurso['Fecha_Inicio'])); ?> y el <?php echo formattedDate(strtoupper($alumnocurso['Fecha_Fin'])); ?> con una duración de <?php echo strtoupper($alumnocurso['N_Horas']); ?> horas en modalidad <?php echo $alumnocurso['Modalidad']; ?>" type="text"></input>


  <label>Fecha:</label>
  <input class="form-control estetica_info" id="est_fechafield" onchange="changefield('est_fecha')" value="<?php echo formattedDate(Date("Y-m-d")); ?>" type="text"></input>
  <script>
  changefield("est_nombre");
  changefield("est_DNI");
  changefield("est_Denominacion");
  changefield("est_details");
  changefield("est_fecha");
  </script>
  </div>
  <div class="pagewrapper" style="padding:0cm">
  <div class="page" style="background:url(images/diploma/estetica2.png); background-repeat: no-repeat; background-size: cover;">
    <!--<textarea id="contenidosestetica"></textarea>-->
    <div id="contenidosestetica"><?php echo isset($contenido["Contenido"]) && !empty($contenido["Contenido"]) ? $contenido["Contenido"] : ""; ?></div>
  </div>
  </div>
  <div class="row mb-2 mt-2" id="volver">
  <script>
    let contenidosesteticasElement = document.getElementById("contenidosestetica");
    resetContenidosEsteticaFontSize();


    function changeContenidosEsteticaFontSize(by){
    currentsize = parseFloat(contenidosesteticasElement.style.fontSize.split("mm")[0]);
    console.log(currentsize);
    contenidosesteticasElement.style.fontSize = (currentsize + by) + "mm";
    }
    function resetContenidosEsteticaFontSize(){
    contenidosesteticasElement.style.fontSize = "6mm";
    }
  </script>
  <div class="col-12 text-center">
    Change Font size for page 2:
    <button class="col-1 btn btn-success text-center" onclick="changeContenidosEsteticaFontSize(0.1)">(+)</button>
    <button class="col-1 btn btn-primary text-center" onclick="resetContenidosEsteticaFontSize(0.1)">Reset</button>
    <button class="col-1 btn btn-danger text-center" onclick="changeContenidosEsteticaFontSize(-0.1)">(-)</button>
  </div>
  </div>
</div>

<div id="soldadura" style="display:none">
  <div class="pagewrapper2" style="padding:0mm;">
  <div class="page" style="background:url(images/diploma/soldadura1.png); background-repeat: no-repeat; background-size: cover;">
  <div class="d-flex justify-content-center soldadura_info">
  <div id="sold_nombre" style="text-align:center; font-family:andasia; font-size:1.3cm; padding-top:10.5cm; margin-left:1.50cm; margin-right:1.50cm;text-transform: capitalize;" contenteditable="true">
    <?php echo mb_strtolower($alumnocurso['nombre'].' '.$alumnocurso['apellidos']); ?>
  </div>
  </div>
  <div class="d-flex justify-content-center soldadura_info">
  <div style="text-align:center; font-size:5.5mm; margin-top:0.7cm; margin-left:1.50cm; margin-right:1.50cm;">CON DNI: <span id="sold_DNI"></span> HA REALIZADO SATISFACTORIAMENTE LA FORMACIÓN DE:</div>
  </div>
  <div class="d-flex justify-content-center soldadura_info">
  <div id="sold_Denominacion" style="text-align:center; font-weight:bold; font-size:8mm;  margin-top:0.30cm;"></div>
  </div>
  <div class="d-flex justify-content-center soldadura_info">
  <div id="sold_details" style="text-align:center; font-size:5.5mm; margin-top:0.50cm;  margin-left:1.50cm; margin-right:1.50cm;"></div>
  </div>
  <div style="position:absolute;bottom: 52mm;left: 135mm;">
    <?php if($alumnocurso['diploma_sin_firma']==0): ?>
    <img src="images/selloDixma.png" style="height: 4.5cm">
    <?php endif ?>
  </div>
  <div style="position:absolute;bottom: 53mm;left: 82mm;">
    <?php if(!empty($alumnocurso['firma_docente'])): ?>
    <img src="firmas/<?php echo $alumnocurso['firma_docente'] ?>" style="height: 4.5cm">
    <?php endif ?>
  </div>
  <div class="soldadura_info" style="position:absolute; bottom:37mm; left: 160mm">
  <div id="sold_fecha" style="text-align:center; font-size:4mm; position:relative; left:0.5cm"></div>
  </div>
  </div>
  </div>
  <div class="row mb-2 mt-2" id="volver">
  <label>Nomre:</label>
  <input class="form-control soldadura_info" id="sold_nombrefield" onchange="changefield('sold_nombre')" value="<?php echo mb_strtolower($alumnocurso['nombre'].' '.$alumnocurso['apellidos']); ?>" type="text"></input>
  <label>DNI:</label>
  <input class="form-control soldadura_info" id="sold_DNIfield" onchange="changefield('sold_DNI')" value="<?php echo strtoupper($alumnocurso['nif']); ?>" type="text"></input>
  <label>Denominacion:</label>
  <input class="form-control soldadura_info" id="sold_Denominacionfield" onchange="changefield('sold_Denominacion')" value="<?php echo mb_strtoupper($alumnocurso['Denominacion'],'UTF-8'); ?>" type="text"></input>
  <label>Details:</label>


  <input class="form-control soldadura_info" id="sold_detailsfield" onchange="changefield('sold_details')" value=" entre el <?php echo formattedDate(strtoupper($alumnocurso['Fecha_Inicio'])); ?> y el <?php echo formattedDate(strtoupper($alumnocurso['Fecha_Fin'])); ?> con una duración de <?php echo strtoupper($alumnocurso['N_Horas']); ?> horas en modalidad <?php echo $alumnocurso['Modalidad']; ?>" type="text"></input>


  <label>Fecha:</label>
  <input class="form-control soldadura_info" id="sold_fechafield" onchange="changefield('sold_fecha')" value="<?php echo formattedDate(Date("Y-m-d")); ?>" type="text"></input>
  <script>
  changefield("sold_nombre");
  changefield("sold_DNI");
  changefield("sold_Denominacion");
  changefield("sold_details");
  changefield("sold_fecha");
  </script>
  </div>
  <div class="pagewrapper2" style="padding:0cm">
  <div class="page" style="background:url(images/diploma/soldadura2.png); background-repeat: no-repeat; background-size: cover;">
  <!--<textarea id="contenidossoldadura"></textarea>-->
  <div id="contenidossoldadura"><?php echo isset($contenido["Contenido"]) && !empty($contenido["Contenido"]) ? $contenido["Contenido"] : ""; ?></div>
  </div>
  </div>
  <div class="row mb-2 mt-2" id="volver">
  <script>
  let contenidossoldadurasElement = document.getElementById("contenidossoldadura");
  resetContenidosSoldaduraFontSize();


  function changeContenidosSoldaduraFontSize(by){
  currentsize = parseFloat(contenidossoldadurasElement.style.fontSize.split("mm")[0]);
  console.log(currentsize);
  contenidossoldadurasElement.style.fontSize = (currentsize + by) + "mm";
  }
  function resetContenidosSoldaduraFontSize(){
  contenidossoldadurasElement.style.fontSize = "6mm";
  }
  </script>
  <div class="col-12 text-center">
  Change Font size for page 2:
  <button class="col-1 btn btn-success text-center" onclick="changeContenidosSoldaduraFontSize(0.1)">(+)</button>
  <button class="col-1 btn btn-primary text-center" onclick="resetContenidosSoldaduraFontSize(0.1)">Reset</button>
  <button class="col-1 btn btn-danger text-center" onclick="changeContenidosSoldaduraFontSize(-0.1)">(-)</button>
  </div>
  </div>
</div>

</body>
</html>