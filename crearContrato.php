
<?php

  include "funciones/conexionBD.php";
  include "funciones/funcionesEmpresa.php";
  include "funciones/funcionesVentas.php";

  setlocale(LC_ALL, 'ES_es');

  session_start();

  if(empty($_SESSION)){

      header("Location: index.php");

  }

  date_default_timezone_set("Europe/Madrid");
  setlocale(LC_ALL, "spanish");

  $empresa = cargarEmpresa($_GET['idEmpresa']);

  $venta = cargarVenta($_GET['idEmpresa']);

 
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PDF</title>
    <link href="css/bootstrap.min.css" rel="stylesheet"></link>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/crearPDF.js"></script>
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
        font-size: 10px;
      }

    </style>

    <style media='print'>

      /* Para Safari, Chrome, Opera:
        -webkit-appearance: none;

      Para Firefox:
        -moz-appearance: none; */

    #volver{display:none;} /* esto oculta los input cuando imprimes */
    #noMostrar{border:none;}
    #flechaMes{border:none; appearance: none; -moz-appearance: none; -webkit-appearance: none;}

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
            <button class="col-5 btn btn-danger text-center" onclick="volverAtras()"><img class="me-3" src="../images/iconos/arrow-left.svg">VOLVER</button>
          </div>
        </div>

        <div class="row pt-3 pb-2" style="border: #b0d588 2px solid; border-radius: 6px;">

          <div class="col-8">

            <img src="images/logoWord.jpg" height=75px; width="230px">

          </div>

          <div class="col-1 ms-5">

            <label>Consultor:</label>

          </div>

          <div class="col-1">

            <input class="border border-2" style="width:100px;" type="text"></input>

          </div>

        </div>

        <div class="row">

          <div class="col-12 text-center">

            <label class="mt-2 me-5 fw-bold" style="font-size:14px;">CONTRATO DE FORMACIÓN</label>
          
          </div>

        </div>

        <div class="row">
          <label class="fw-bold border border-2 w-50 mt-1 py-1 rounded" id="color" style="font-size:12px;background: #bcbcbc;">DATOS DE LA EMPRESA (DEUDOR)</label>
        </div>

        <div class="row" style="border: #b0d588 2px solid; border-radius: 6px;">

        <div class="row mt-2">

          <label class="col-2 col-form-label">RAZÓN SOCIAL:</label>
            <div class="col-6">
              <input class="form-control form-control-sm" value="<?php echo $empresa['nombre'] ?>" type="text"></input>
            </div>        
        
          <label class="col-1 col-form-label ">CIF:</label>
            <div class="col-3">
              <input class="form-control form-control-sm" type="text" value="<?php echo $empresa['cif'] ?>"></input>
            </div>    

        </div>

          <div class="row mt-1">
            <label class="col-2 col-form-label">DOMICILIO SOCIAL:</label>
              <div class="col-10">
                <input class="form-control form-control-sm" value="<?php echo $empresa['calle'] ?>" type="text"></input>
              </div>

          </div>

          <div class="row mt-1">

          <label class="col-2 col-form-label">POBLACIÓN:</label>
            <div class="col-3">
              <input class="form-control form-control-sm" value="<?php echo $empresa['poblacion'] ?>" type="text"></input>
            </div>

            <label class="col-2 col-form-label">CÓDIGO POSTAL:</label>
            <div class="col-2"  >
              <input class="form-control form-control-sm" value="<?php echo $empresa['cp'] ?>" type="text"></input>
            </div>

            <label class="col-1 col-form-label">PROVINCIA:</label>
            <div class="col-2"  >
              <input class="form-control form-control-sm" value="<?php echo $empresa['provincia'] ?>" type="text"></input>
            </div>

          </div> 
            
          <div class="row mt-1">

          <label class="col-2 col-form-label">TELÉFONO:</label>
            <div class="col-3"  >
              <input class="form-control form-control-sm" value="<?php echo $empresa['telef1'] ?>" type="text"></input>
            </div>
            
            <label class="col-2">EMAIL: (Para factura de boniﬁcación):</label>
            <div class="col-5"  >
              <input class="form-control form-control-sm" value="<?php echo $resultado = !empty($venta['emailfactura']) ? $venta['emailfactura'] : "" ?>" type="text"></input>
            </div>

          </div>          
            
          <div class="row mt-1">

          <label class="col-2">PERSONA DE CONTACTO EMPRESA:</label>
            <div class="col-3"  >
              <input class="form-control form-control-sm" value="<?php echo $empresa['personacontacto'] ?>" type="text"></input>
            </div>
            
            <label class="col-1 col-form-label">TELÉFONO:</label>
            <div class="col-6"  >
              <input class="form-control form-control-sm" type="text"></input>
            </div>            
          
          </div>      
            
          <div class="row mt-1">

          <label class="col-2 col-form-label">ASESORÍA:</label>
            <div class="col-3"  >
              <input class="form-control form-control-sm" value="<?php echo $resultado = !empty($venta['nombreasesoria']) ? $venta['nombreasesoria'] : "" ?>" type="text"></input>
            </div>
            
            <label class="col-1">TELÉFONO ASESORÍA:</label>
            <div class="col-2" >
              <input class="form-control form-control-sm" value="<?php echo $resultado = !empty($venta['telfasesoria']) ? $venta['telfasesoria'] : "" ?>" type="text"></input>
            </div>    
            
            <label class="col-1">EMAIL ASESORÍA:</label>
            <div class="col-3" >
              <input class="form-control form-control" value="<?php echo $resultado = !empty($venta['mailasesoria']) ? $venta['mailasesoria'] : ""?>" type="text"></input>
            </div>                

          </div>    
            
          <div class="row mt-1">

            <label class="col-2">SOLICITA LA CONTRATACIÓN DE LA FORMACIÓN:</label>
            <div class="col-10"  >
              <input class="form-control form-control-sm" type="text"></input>
            </div>    

          </div> 
            
          <div class="row mt-1 mb-2">

              <label class="col-2">DURACIÓN DE LA FORMACIÓN:</label>
              <div class="col-3 offset-0"  >
                <input class="form-control form-control-sm" type="text"></input>
              </div>

              <label class="col-1 col-form-label">MODALIDAD:</label>
              <div class="col-6"  >
                <input class="form-control form-control-sm" type="text"></input>
              </div>

          </div> 

        </div>
        <p class="text-center">Habiendo recibido, previamente a la celebración del contrato información detallada sobre las características del mismo/s, su duración, contenido y forma de entrega.</p>


        <div class="row">
          <label class="fw-bold border border-2 w-50 py-1 rounded" style="font-size:12px; background-color: #bcbcbc;">FORMA DE PAGO</label>
        </div>

        <div class="row pb-1" style="border: #b0d588 2px solid; border-radius: 6px;">
        
          <div class="row mt-2">

              <label class="col-2 col-form-label fw-bold">IMPORTE TOTAL:</label>
              <div class="col-3">
                <input class="form-control form-control-sm" type="text"></input>
              </div>

              <label class="col-3 col-form-label fw-bold"></label>
              <label class="col-4 col-form-label text-center text-end"> <b>TRANSFERENCIA/DOMICILIACIÓN</b> <br> (TACHE EL QUE NO PROCEDA) </label>

          </div>

          <div class="row mt-2">

              <label class="col-2 col-form-label fw-bold">FECHA DE COBRO:</label>
              <div class="col-3">
                <input type="date" value="<?php echo date('Y-m-d') ?>" class="form-control form-control-sm"></input>
              </div>

              <label class="col-3 col-form-label text-center"> <b>PAGO FRACCIONADO</b> <br> (IMPORTE Y FECHA COBRO)</label>
              <div class="col-4 border">
                      <input id="noMostrar" class="form-control form-control-sm" type="text"></input>
                      <input id="noMostrar" type="text" class="form-control form-control-sm"></input>
                </div>

          </div>          

        </div>

        <div class="row">
          <label class="fw-bold border border-2 w-50 mt-1 py-1 rounded" style="font-size:12px; background-color: #bcbcbc;">ORDEN DE DOMICILIACIÓN DE ADEUDO DIRECTO (SEPA)</label>
        </div>

        <div class="row" style="border: #b0d588 2px solid; border-radius: 6px;">

          <div class="row mt-2">

            <label class="col-2"> Referencia Orden de Domiciliación:</label>
              <div class="col-6">
                <input class="form-control form-control-sm" type="text"></input>
              </div>

            <label class="col-1 col-form-label">Identificación:</label>
              <div class="col-3">
                <input class="form-control form-control-sm fw-bold" type="text" value="ES74001E27876325" readonly></input>
              </div>              

          </div>

          <div class="row mt-1">

            <label class="col-1 col-form-label">Nombre:</label>
              <div class="col-2">
                <input class="form-control form-control-sm" type="text" value="DIXMA"></input>
              </div>

            <label class="col-1 col-form-label">CIF:</label>
              <div class="col-2">
                <input class="form-control form-control-sm" type="text" value="E27876325"></input>
              </div>      
              
            <label class="col-1 col-form-label">Dirección:</label>
              <div class="col-5">
                <input class="form-control form-control-sm" type="text" style="font-size:10px;" value="Ctra. de Madrid, 152 – 36318 – Vigo - Pontevedra- España"></input>
              </div>                 

          </div>
          
          <div class="row mt-4">

            <label class="col col-form-label">Número de cuenta-IBAN:</label>

              <div class="col-12">
                <table>
                  <tr>
                    <td class="border border-secondary"><input class="text-center fw-bold" value="<?php echo $resultado = !empty($venta['numerocuenta']) ? $venta['numerocuenta'][0] : "E" ?>" maxlength="1" style="width: 25px;"></input></td>
                    <td class="border border-secondary"><input class="text-center fw-bold" value="<?php echo $resultado = !empty($venta['numerocuenta']) ? $venta['numerocuenta'][1] : "S" ?>" maxlength="1" style="width: 25px;"></input></td>
                    <td class="border border-secondary"><input class="text-center fw-bold" value="<?php echo $resultado = !empty($venta['numerocuenta']) ? $venta['numerocuenta'][2] : "" ?>" maxlength="1" style="width: 25px;"></input></td>
                    <td class="border border-secondary"><input class="text-center fw-bold" value="<?php echo $resultado = !empty($venta['numerocuenta']) ? $venta['numerocuenta'][3] : "" ?>" maxlength="1" style="width: 25px;"></input></td>

                    <td>&nbsp&nbsp</td>

                    <td class="border border-secondary"><input class="text-center fw-bold" value="<?php echo $resultado = !empty($venta['numerocuenta']) ? $venta['numerocuenta'][4] : "" ?>" maxlength="1" style="width: 25px;"></input></td>
                    <td class="border border-secondary"><input class="text-center fw-bold" value="<?php echo $resultado = !empty($venta['numerocuenta']) ? $venta['numerocuenta'][5] : "" ?>" maxlength="1" style="width: 25px;"></input></td>
                    <td class="border border-secondary"><input class="text-center fw-bold" value="<?php echo $resultado = !empty($venta['numerocuenta']) ? $venta['numerocuenta'][6] : "" ?>" maxlength="1" style="width: 25px;"></input></td>
                    <td class="border border-secondary"><input class="text-center fw-bold" value="<?php echo $resultado = !empty($venta['numerocuenta']) ? $venta['numerocuenta'][7] : "" ?>" maxlength="1" style="width: 25px;"></input></td>

                    <td>&nbsp&nbsp</td>

                    <td class="border border-secondary"><input class="text-center fw-bold" value="<?php echo $resultado = !empty($venta['numerocuenta']) ? $venta['numerocuenta'][8] : "" ?>" maxlength="1" style="width: 25px;"></input></td>
                    <td class="border border-secondary"><input class="text-center fw-bold" value="<?php echo $resultado = !empty($venta['numerocuenta']) ? $venta['numerocuenta'][9] : "" ?>" maxlength="1" style="width: 25px;"></input></td>
                    <td class="border border-secondary"><input class="text-center fw-bold" value="<?php echo $resultado = !empty($venta['numerocuenta']) ? $venta['numerocuenta'][10] : "" ?>" maxlength="1" style="width: 25px;"></input></td>
                    <td class="border border-secondary"><input class="text-center fw-bold" value="<?php echo $resultado = !empty($venta['numerocuenta']) ? $venta['numerocuenta'][11] : "" ?>" maxlength="1" style="width: 25px;"></input></td>

                    <td>&nbsp&nbsp</td>

                    <td class="border border-secondary"><input class="text-center fw-bold" value="<?php echo $resultado = !empty($venta['numerocuenta']) ? $venta['numerocuenta'][12] : "" ?>" maxlength="1" style="width: 25px;"></input></td>
                    <td class="border border-secondary"><input class="text-center fw-bold" value="<?php echo $resultado = !empty($venta['numerocuenta']) ? $venta['numerocuenta'][13] : "" ?>" maxlength="1" style="width: 25px;"></input></td>

                    <td>&nbsp&nbsp</td>

                    <td class="border border-secondary"><input class="text-center fw-bold" value="<?php echo $resultado = !empty($venta['numerocuenta']) ? $venta['numerocuenta'][14] : "" ?>" maxlength="1" style="width: 25px;"></input></td>
                    <td class="border border-secondary"><input class="text-center fw-bold" value="<?php echo $resultado = !empty($venta['numerocuenta']) ? $venta['numerocuenta'][15] : "" ?>" maxlength="1" style="width: 25px;"></input></td>
                    <td class="border border-secondary"><input class="text-center fw-bold" value="<?php echo $resultado = !empty($venta['numerocuenta']) ? $venta['numerocuenta'][16] : "" ?>" maxlength="1" style="width: 25px;"></input></td>
                    <td class="border border-secondary"><input class="text-center fw-bold" value="<?php echo $resultado = !empty($venta['numerocuenta']) ? $venta['numerocuenta'][17] : "" ?>" maxlength="1" style="width: 25px;"></input></td>                    
                    <td class="border border-secondary"><input class="text-center fw-bold" value="<?php echo $resultado = !empty($venta['numerocuenta']) ? $venta['numerocuenta'][18] : "" ?>" maxlength="1" style="width: 25px;"></input></td>                    
                    <td class="border border-secondary"><input class="text-center fw-bold" value="<?php echo $resultado = !empty($venta['numerocuenta']) ? $venta['numerocuenta'][19] : "" ?>" maxlength="1" style="width: 25px;"></input></td>                    
                    <td class="border border-secondary"><input class="text-center fw-bold" value="<?php echo $resultado = !empty($venta['numerocuenta']) ? $venta['numerocuenta'][20] : "" ?>" maxlength="1" style="width: 25px;"></input></td>                    
                    <td class="border border-secondary"><input class="text-center fw-bold" value="<?php echo $resultado = !empty($venta['numerocuenta']) ? $venta['numerocuenta'][21] : "" ?>" maxlength="1" style="width: 25px;"></input></td>                    
                    <td class="border border-secondary"><input class="text-center fw-bold" value="<?php echo $resultado = !empty($venta['numerocuenta']) ? $venta['numerocuenta'][22] : "" ?>" maxlength="1" style="width: 25px;"></input></td>                    
                    <td class="border border-secondary"><input class="text-center fw-bold" value="<?php echo $resultado = !empty($venta['numerocuenta']) ? $venta['numerocuenta'][23] : "" ?>" maxlength="1" style="width: 25px;"></input></td>                    

                  </tr>

                  <tr>
                    <th colspan=4 class="text-center" style="background: #bcbcbc;">IBAN</th>
                    <th></th>
                    <th colspan=4 class="text-center" style="background: #bcbcbc;">ENTIDAD</th>
                    <th></th>
                    <th colspan=4 class="text-center" style="background: #bcbcbc;">OFICINA</th>
                    <th></th>
                    <th colspan=2 class="text-center" style="background: #bcbcbc;">DC</th>
                    <th></th>
                    <th colspan=10 class="text-center" style="background: #bcbcbc;">NÚMERO DE CUENTA</th>
                  </tr>


                </table>
              </div>



          </div>
          
          <div class="row mt-4">

            <label class="col-2">Tipo de pago:</label>
              <div class="col-5">
                <input type="checkbox" class="form-check-input" value="DIXMA" readonly></input>
                <label class="form-check-label">Pago recurrente</label>
              </div>

              <div class="col-5">
                <input type="checkbox" class="form-check-input" value="DIXMA" readonly></input>
                <label class="form-check-label">Pago unico</label>
              </div>
                

          </div>           
          
          <p><sub> <b>Mandato de adeudo directo SEPA:</b> Mediante la ﬁrma de esta orden de domiciliación, el deudor autoriza al acreedor a enviar instrucciones 
            a la entidad del deudor para adeudar su cuenta y a la entidad para efectuar los adeudos en su cuenta siguiendo las instrucciones del acreedor. 
            Como parte de sus derechos, el deudor está legitimado al reembolso por su entidad en los términos y condiciones del contrato suscrito con la misma. 
            La solicitud de reembolso deberá efectuarse dentro de las ocho semanas que siguen a la fecha de adeudo en su cuenta. Puede obtener información adicional 
            sobre sus derechos en su entidad financiera. Todos los campos han de ser cumplimentados obligatoriamente. Una vez firmada esta orden de domiciliación 
            debe ser enviada al acreedor para su custodia. </sub> </p>
          
        </div>

        <p class="text-center mt-2">En prueba de conformidad con los datos que se recogen, se acuerda con DIXMA, la formalización de este contrato de matrícula del cual recibo copia.</p>

        <div class="row">
          
          <div class="col-12 text-center">
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

        <div class="row mt-2">
        
          <div class="col-4">
            <label class="col-8 ">FIRMA DEL INTERESADO O <br> REPRESENTANTE LEGAL DE LA EMPRESA</label>
          </div>

          <div class="col-4">
            <label class="col-8">FIRMA DEL CONSULTOR</label>
            <img class="mb-0" src="images/selloDixma.png" style="height: 70px;">
          </div>

          <div class="col-4 border">
            <label class="col-8 w-auto">TITULAR DE LA CUENTA PARA AUTORIZACIÓN DE DOMICILIACIÓN DE ADEUDO DIRECTO SEPA. <br> NOMBRE: <input id="noMostrar"></input> <br> DNI: <input id="noMostrar" style="margin-left:23px;"></input> </label>
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



  </body>
</html>