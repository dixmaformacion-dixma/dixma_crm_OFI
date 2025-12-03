

<?php

include "funciones/conexionBD.php";
include "funciones/funcionesAlumnos.php";
include "funciones/funcionesEmpresa.php";
include "funciones/funcionesAlumnosCursos.php";
include "funciones/funcionesVentas.php";

setlocale(LC_ALL, 'ES_es');

session_start();

if(empty($_SESSION)){

    header("Location: index.php");

}

date_default_timezone_set("Europe/Madrid");
setlocale(LC_ALL, "spanish");

if(!isset($_GET['idEmpresa']) or !isset($_GET['N_Accion']) or !isset($_GET['Ano']) or !isset($_GET['N_Grupo'])){
  die("Parameters are missing!");
}

##get the details of the company
$empresa = cargarEmpresa($_GET['idEmpresa']);

##procedure to get the courses which belong to this company
  $startDate = (new \DateTime($_GET['Ano'].'-01-01'))->format('Y-m-d');
  $endDate = (new \DateTime(($_GET['Ano']+1).'-01-01'))->format('Y-m-d');

  $conexionPDO = realizarConexion();
  $sql = 'SELECT * FROM `alumnocursos` WHERE `N_Accion` = ? AND `N_Grupo` = ? AND `Fecha_Fin` >= ? AND `Fecha_Fin` < ? AND `idEmpresa` = ?';

  $conexionPDO->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
  $stmt = $conexionPDO->prepare($sql);
      
  $stmt->bindValue(1, $_GET['N_Accion'], PDO::PARAM_INT);
  $stmt->bindValue(2, $_GET['N_Grupo'], PDO::PARAM_INT);
  $stmt->bindValue(3, $startDate, PDO::PARAM_STR);
  $stmt->bindValue(4, $endDate, PDO::PARAM_STR);
  $stmt->bindValue(5, $_GET['idEmpresa'], PDO::PARAM_INT);

  $stmt->execute();

  if($alumnocurso = $stmt->fetchAll()){
    unset($conexionPDO);
  } else {
    die("Error occured while fetching course information");;
  }
##end of procedure

##procedure to get the sale information
  // Use the helper function which returns the latest sale for the company
  $venta = cargarVenta($_GET['idEmpresa']);
##end of procedure
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
  <link rel="icon" href="images/favicon.ico">

  <style>

    body{
      width: 210mm;
      height: 297mm;
      margin-left: auto;
      margin-right: auto;
      font-size: 10px;
      -webkit-print-color-adjust: exact;
    }

    div input[type=text]{
      font-size: 11px;
    }

    div input[type=date]{
      font-size: 11px;
    }

    table{
      border: black 1px solid;
      font-size:15px;
    }
    th{
      background-color: #2e75b5;
      color:white;
      text-align:center;
      padding: 3px;
    }
    table td>div{
      width: 100%;
      display:flex;
      justify-content: center;
    }
    table td{
      border-right: 1px solid black;
      border-bottom: 1px solid black;
    }
    td input{
      text-transform: uppercase;
    }

    #companyinfo{
      font-size:14px;
      font-weight: bold;
    }
    #companyinfo input{
      font-size:14px;
    }
    #companyinfo textarea{
      font-size:14px;
    }

  </style>

  <style media='print'>

    /* Para Safari, Chrome, Opera:
      -webkit-appearance: none;

    Para Firefox:
      -moz-appearance: none; */
    #volver{display:none;} /* esto oculta los input cuando imprimes */
    #flechaMes{border:none; -moz-appearance: none; -webkit-appearance: none;}
    #prueba{-moz-appearance: none; -webkit-appearance: none;}
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
      <button class="col-5 btn btn-success text-center" onclick="window.print(1)"><img class="me-3" src="../images/iconos/printer.svg">IMPRIMIR</button>
      <a class="col-5 btn btn-danger text-center" href="administracion_crearFactura.php"><img class="me-3" src="../images/iconos/arrow-left.svg">VOLVER</a>
    </div>
  </div>

  <div class="row pt-3 pb-2">

    <div class="col-4">

      <img src="images/logoWord.jpg" height=75px; width="230px">

    </div>

    <div class="col-8 d-flex" style="justify-content: right">
        <label class="fw-bold align-self-center fs-1" style="color: #2e75b5">FACTURA</label>
    </div>

  </div>

  <div class="row">
    <div class="col-6 d-flex align-items-center">
      <div class="fw-bold fs-6">
        <div>CIF: E27876325</div>
        <div>CTRA. DE MADRID, 152</div>
        <div>36318 VIGO</div>
        <div>TELEFONO: 604 067 035</div>
        <div>MAIL: gestion@dixmaformacion.com</div>
      </div>
    </div>
    <div class="col-6" id="companyinfo">
      <div class="row d-flex align-items-center">
        <label class="fw-bold py-1 mb-2 fs-6" id="color" style="background-color: #2e75b5; color:white">FACTURAR A:</label>
      </div>
      <div class="row d-flex align-items-center">
        <label class="col-auto col-form-label p-0">Razón Social:</label>
        <div class="col px-0">
          <input class="form-control form-control-sm border-0" style="padding: 0px; padding-left:5px;" value="<?php echo $empresa['nombre'] ?>" type="text"></input>
        </div>
      </div>
      <div class="row d-flex align-items-center">
        <label class="col-auto col-form-label p-0">CIF:</label>
        <div class="col px-0">
          <input class="form-control form-control-sm border-0" style="padding: 0px; padding-left:5px;" value="<?php echo $empresa['cif'] ?>" type="text"></input>
        </div>
      </div>
      <div class="row d-flex align-items-top">
        <label class="col-auto col-form-label p-0">Dirección:</label>
        <div class="col px-0">
          <textarea rows="2" class="form-control form-control-sm border-0" style="padding: 0px; padding-left:5px;"><?php echo $empresa['calle'].'&#13;&#10;'.$empresa['cp']." - ".$empresa['poblacion']." (".$empresa['provincia'].")"  ?></textarea>
        </div>
      </div>
      <div class="row d-flex align-items-center">
        <label class="col-auto col-form-label p-0">Teléfono:</label>
        <div class="col px-0">
          <input class="form-control form-control-sm border-0" style="padding: 0px; padding-left:5px;" value="<?php echo $empresa['telef1'] ?>" type="text"></input>
        </div>
      </div>

      <div class="row d-flex" style="justify-content:right">
        <div class="col-5 px-0">
          <label class="fw-bold py-1 mb-1 mt-3 fs-6 col-12 text-center" id="color" style="background-color: #2e75b5; color:white;">N.º DE FACTURA</label>
          <input class="form-control form-control-sm fw-bold text-center border-0" value="/<?php echo Date("Y") ?>" type="text"></input>
        </div>
        <div class="col-5 px-0">
          <label class="fw-bold py-1 mb-1 mt-3 fs-6 col-12 text-center" id="color" style="background-color: #2e75b5; color:white;">FECHA</label>
          <input class="form-control form-control-sm fw-bold text-center border-0" value="<?php echo Date("d/m/Y") ?>" type="text"></input>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <input type="text" class="fw-bold py-1 mb-1 mt-3 fs-6 col-12 text-center border-0" style="background-color: #2e75b5; color:white;" id="color" style="font-size:12px;background: #bcbcbc;" value="DESCRIPCIÓN DEL SERVICIO:  ORGANIZACIÓN, IMPARTICIÓN Y GESTIÓN DE LA ACCIÓN FORMATIVA"></input>
  </div>

  <div class="row">
    <table class="col-12 mt-3">
      <tbody>
        <tr>
          <th>CANT.</th>
          <th>CONCEPTO</th>
          <th>PRECIO UNITARIO</th>
          <th>IMPORTE</th>
        </tr>
        <tr>
          <td><div><input class="form-control border-0" value="1" type="text" style="width:50px; text-align:center"></input></div></td>
          <td><div><textarea class="form-control border-0" style="width:470px; text-align:left; resize: none; font-size: 11px;"><?php echo "ACCION FORMATIVA: ".$alumnocurso[0]['Denominacion'] ?></textarea></div></td>
          <td><div><input id="mainprice" class="form-control border-0" onchange="changePrice()" value="<?php if($venta){echo floatval($venta['importe']);} ?>" type="text" style="width:150px; text-align:right"></input></div></td>
          <td><div><input id="importe" class="form-control border-0" type="text" style="width:100px; text-align:right"></input></div></td>
        </tr>
        <tr>
          <td><div><input class="form-control border-0" value="" type="text" style="width:50px; text-align:center"></input></div></td>
          <td><div><input class="form-control border-0" value="CÓDIGO ACCIÓN FORMATIVA/GRUPO: <?php echo $_GET['N_Accion']."/".$_GET['N_Grupo'] ?>" type="text" style="width:470px; text-align:left"></input></div></td>
          <td><div><input class="form-control border-0" value="" type="text" style="width:150px; text-align:right"></input></div></td>
          <td><div><input class="form-control border-0" value="" type="text" style="width:100px; text-align:right"></input></div></td>
        </tr>
        <tr>
          <td><div><input class="form-control border-0" value="" type="text" style="width:50px; text-align:center"></input></div></td>
          <td><div><input class="form-control border-0" value="NÚMERO DE PARTICIPANTES: <?php echo count($alumnocurso) ?>" type="text" style="width:470px; text-align:left"></input></div></td>
          <td><div><input class="form-control border-0" value="" type="text" style="width:150px; text-align:right"></input></div></td>
          <td><div><input class="form-control border-0" value="" type="text" style="width:100px; text-align:right"></input></div></td>
        </tr>
        <tr>
          <td><div><input class="form-control border-0" value="" type="text" style="width:50px; text-align:center"></input></div></td>
          <td><div><input class="form-control border-0" value="NÚMERO HORAS TOTALES: <?php echo $alumnocurso[0]['N_Horas'] ?>" type="text" style="width:470px; text-align:left"></input></div></td>
          <td><div><input class="form-control border-0" value="" type="text" style="width:150px; text-align:right"></input></div></td>
          <td><div><input class="form-control border-0" value="" type="text" style="width:100px; text-align:right"></input></div></td>
        </tr>
        <tr>
          <td><div><input class="form-control border-0" value="" type="text" style="width:50px; text-align:center"></input></div></td>
          <td><div><input class="form-control border-0" value="MODALIDAD: <?php echo $alumnocurso[0]['Modalidad'] ?>" type="text" style="width:470px; text-align:left"></input></div></td>
          <td><div><input class="form-control border-0" value="" type="text" style="width:150px; text-align:right"></input></div></td>
          <td><div><input class="form-control border-0" value="" type="text" style="width:100px; text-align:right"></input></div></td>
        </tr>
        <tr>
          <td><div><input class="form-control border-0" value="" type="text" style="width:50px; text-align:center"></input></div></td>
          <td><div><input class="form-control border-0" value="FECHA DE INICIO: <?php echo date("d/m/Y",strtotime($alumnocurso[0]['Fecha_Inicio'])); ?>" type="text" style="width:470px; text-align:left"></input></div></td>
          <td><div><input class="form-control border-0" value="" type="text" style="width:150px; text-align:right"></input></div></td>
          <td><div><input class="form-control border-0" value="" type="text" style="width:100px; text-align:right"></input></div></td>
        </tr>
        <tr>
          <td><div><input class="form-control border-0" value="" type="text" style="width:50px; text-align:center"></input></div></td>
          <td><div><input class="form-control border-0" value="FECHA DE FIN: <?php echo date("d/m/Y",strtotime($alumnocurso[0]['Fecha_Fin'])); ?>" type="text" style="width:470px; text-align:left"></input></div></td>
          <td><div><input class="form-control border-0" value="" type="text" style="width:150px; text-align:right"></input></div></td>
          <td><div><input class="form-control border-0" value="" type="text" style="width:100px; text-align:right"></input></div></td>
        </tr>
        <tr>
          <td><div><input class="form-control border-0" value="" type="text" style="width:50px; text-align:center"></input></div></td>
          <td><div><input class="form-control border-0" value="COSTES DIRECTOS:" type="text" style="width:470px; text-align:left"></input></div></td>
          <td><div><input id="costes_directos" class="form-control border-0" type="text" style="width:150px; text-align:right"></input></div></td>
          <td><div><input class="form-control border-0" value="" type="text" style="width:100px; text-align:right"></input></div></td>
        </tr>
        <tr>
          <td><div><input class="form-control border-0" value="" type="text" style="width:50px; text-align:center"></input></div></td>
          <td><div><input class="form-control border-0" value="COSTES ORGANIZACIÓN:" type="text" style="width:470px; text-align:left"></input></div></td>
          <td><div><input id="costes_organizacion" class="form-control border-0" type="text" style="width:150px; text-align:right"></input></div></td>
          <td><div><input class="form-control border-0" value="" type="text" style="width:100px; text-align:right"></input></div></td>
        </tr>
        <tr>
          <td><div><input class="form-control border-0" value="" type="text" style="width:50px; text-align:center"></input></div></td>
          <td><div><input class="form-control border-0" value="" type="text" style="width:470px; text-align:left"></input></div></td>
          <td><div><input class="form-control border-0" value="" type="text" style="width:150px; text-align:right"></input></div></td>
          <td><div><input class="form-control border-0" value="" type="text" style="width:100px; text-align:right"></input></div></td>
        </tr>
        <tr>
          <td><div><input class="form-control border-0" value="" type="text" style="width:50px; text-align:center"></input></div></td>
          <td><div><input class="form-control border-0" value="" type="text" style="width:470px; text-align:left"></input></div></td>
          <td><div><input class="form-control border-0" value="" type="text" style="width:150px; text-align:right"></input></div></td>
          <td><div><input class="form-control border-0" value="" type="text" style="width:100px; text-align:right"></input></div></td>
        </tr>
        <tr>
          <td><div><input class="form-control border-0" value="" type="text" style="width:50px; text-align:center"></input></div></td>
          <td><div><input class="form-control border-0" value="" type="text" style="width:470px; text-align:left"></input></div></td>
          <td><div><input class="form-control border-0" value="" type="text" style="width:150px; text-align:right"></input></div></td>
          <td><div><input class="form-control border-0" value="" type="text" style="width:100px; text-align:right"></input></div></td>
        </tr>
        <tr>
          <td><div><input class="form-control border-0" value="" type="text" style="width:50px; text-align:center"></input></div></td>
          <td><div><input class="form-control border-0" value="" type="text" style="width:470px; text-align:left"></input></div></td>
          <td><div><input class="form-control border-0" value="" type="text" style="width:150px; text-align:right"></input></div></td>
          <td><div><input class="form-control border-0" value="" type="text" style="width:100px; text-align:right"></input></div></td>
        </tr>
        <tr>
          <td><div><input class="form-control border-0" value="" type="text" style="width:50px; text-align:center"></input></div></td>
          <td><div><input class="form-control border-0" value="" type="text" style="width:470px; text-align:left"></input></div></td>
          <td><div><input class="form-control border-0" value="" type="text" style="width:150px; text-align:right"></input></div></td>
          <td><div><input class="form-control border-0" value="" type="text" style="width:100px; text-align:right"></input></div></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="row fst-italic">
    <label class="fw-bold py-1 fs-6 col-7 text-center d-flex align-items-center" style="background-color: #deeaf6; color:#2e75b5;" id="color" style="font-size:12px;background: #bcbcbc;">FORMA DE PAGO:  
      <div>
        <select onchange="changeFormaDePago()" id="formaDePago" class="form-control fst-italic text-uppercase fw-bold" style="width:200px; background-color: #deeaf6; color:#2e75b5; border: 0">
          <option <?php if($venta and $venta['formapago'] == "Domiciliación"){echo " selected ";}?>>Domiciliación</option>
          <option <?php if($venta and $venta['formapago'] == "Transferencia"){echo " selected ";}?>>Transferencia</option>
        </select>
        
      </div>
    </label>
    <label class="fw-bold py-1 fs-6 col-5 text-center d-flex align-items-center" style="background-color: #deeaf6; color:#2e75b5;" id="color" style="font-size:12px;background: #bcbcbc;">FECHA DE PAGO:  
      <div>
      <input class="form-control border-0 fst-italic text-uppercase fw-bold fs-6 m-0" type="text" style="width:150px; background-color: #deeaf6; color:#2e75b5;"></input>
      </div>
    </label>
  </div>
  <div class="row">
    <div class="col-8 fw-bold fs-6">Observaciones:</div>
    <div class="col-4 fw-bold fs-6" style="background-color: #bdd6ee">BASE IMPONIBLE EXENTA (*)</div>
  </div>
  <div class="row">
    <div class="col-8 fs-6">(*) Operación exenta según lo dispuesto en el apartado 9 del punto 1 del artículo 20 de la Ley 37/1992, de 28 de diciembre (Ley del IVA)</div>
    <div class="col-2 fw-bold fs-6" style="background-color: #bdd6ee"></div>
    <div class="col-2 fw-bold fs-6" style="background-color: #deeaf6"></div>
  </div>
  <div class="row">
    <div class="col-8 fs-6 fw-bold" style="background-color: #deeaf6; display: flex;color: #2e75b5; justify-content: center; align-items: center;">Gracias por su confianza</div>
    <div class="col-2 fw-bold fs-5" style="background-color: #bdd6ee">TOTAL</div>
    <div class="col-2 fw-bold fs-6" style="background-color: #deeaf6; text-align: right; justify-content:right; display:flex; align-items:center">
      <input id="total" class="form-control border-0 fst-italic text-uppercase fw-bold fs-6 m-0 p-0" type="text" style="text-align: right; width:90px; background-color: #deeaf6; color:#2e75b5;"></input>
      <div class="fst-italic text-uppercase fw-bold fs-6 m-0" style="width:90px; background-color: #deeaf6; color:#2e75b5;">€</div>
    </div>
  </div>
  <div id="bankDetails" class="row mt-2 mb-2" style="border: 2px solid black">
    <div class="col-7 fw-bold fs-6">Nº CUENTA:  BANCO SABADELL</div>
    <div class="col-5 fw-bold fs-6 text-center">ES92 0081 7620 4500 0229 3740</div>
  </div>
  <div class="row">
    <div class="col-12 p-0 pt-3">Si tiene alguna duda sobre esta factura, póngase en contacto con: gestion@dixmaformacion.com </div>
  </div>
</div>

<script>
function changeFormaDePago(){
  if($('#formaDePago').get(0).value == "Transferencia"){
    $('#bankDetails').get(0).style.display = "flex";
  }else{
    $('#bankDetails').get(0).style.display = "none";
  }
}
function changePrice(){
  price = $('#mainprice').get(0).value * 1.0;
  $('#mainprice').get(0).value = price.toFixed(2);
  $('#importe').get(0).value = price.toFixed(2);
  $('#costes_directos').get(0).value = (price/1.1).toFixed(2);
  $('#costes_organizacion').get(0).value = (price/1.1*0.1).toFixed(2);
  $('#total').get(0).value = price.toFixed(2);
}

changeFormaDePago();
changePrice();
</script>
</body>
</html>