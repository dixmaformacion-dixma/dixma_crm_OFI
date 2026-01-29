<?php

    include "funciones/conexionBD.php";
    include "funciones/funcionesVentas.php";
    include "funciones/funcionesEmpresa.php";
    include "funciones/cargarComerciales.php";

    session_start();

    if(empty($_SESSION)){

        header("Location: index.php");

    }

    date_default_timezone_set("Europe/Madrid");
    setlocale(LC_ALL, "spanish");

    if(!isset($_GET['idventa'])){
        die("ERROR");
    }

    if(isset($_POST['Guardar'])){
        if(isset($_POST['formacionCurso1']) &&
            isset($_POST['nombreCurso1']) &&
            isset($_POST['horasCurso1']) &&
            isset($_POST['modalidadCurso1']) &&
            isset($_POST['formacionCurso2']) &&
            isset($_POST['nombreCurso2']) &&
            isset($_POST['horasCurso2']) &&
            isset($_POST['modalidadCurso2']) &&
            isset($_POST['formacionCurso3']) &&
            isset($_POST['nombreCurso3']) &&
            isset($_POST['horasCurso3']) &&
            isset($_POST['modalidadCurso3']) &&
            isset($_POST['emailFacturacion']) &&
            isset($_POST['asesoria']) &&
            isset($_POST['telefAsesoria']) &&
            isset($_POST['emailAsesoria']) &&
            isset($_POST['importe']) &&
            isset($_POST['formaPago']) &&
            isset($_POST['numeroCuenta']) &&
            isset($_POST['comercial']) &&
            isset($_POST['observacionesVenta'])
        ){
            $fechaCobro = date('d-m-Y', strtotime($_POST['fechaCobro']));
            if($fechaCobro == "01-01-1970"){

                $fechaCobro = "";

            }

            $datosVenta = [
                'formacionCurso1' => $_POST['formacionCurso1'],
                'nombreCurso1' => $_POST['nombreCurso1'],
                'horasCurso1' => $_POST['horasCurso1'],
                'modalidadCurso1' => $_POST['modalidadCurso1'],
                'formacionCurso2' => $_POST['formacionCurso2'],
                'nombreCurso2' => $_POST['nombreCurso2'],
                'horasCurso2' => $_POST['horasCurso2'],
                'modalidadCurso2' => $_POST['modalidadCurso2'],
                'formacionCurso3' => $_POST['formacionCurso3'],
                'nombreCurso3' => $_POST['nombreCurso3'],
                'horasCurso3' => $_POST['horasCurso3'],
                'modalidadCurso3' => $_POST['modalidadCurso3'],
                'emailFacturacion' => $_POST['emailFacturacion'],
                'asesoria' => $_POST['asesoria'],
                'telefAsesoria' => $_POST['telefAsesoria'],
                'emailAsesoria' => $_POST['emailAsesoria'],
                'importe' => $_POST['importe'],
                'fechaCobro' => $fechaCobro,
                'formaPago' => $_POST['formaPago'],
                'numeroCuenta' => $_POST['numeroCuenta'],
                'comercial' => $_POST['comercial'],
                'observacionesVenta' => $_POST['observacionesVenta'],
            ];

            if(editarVenta($datosVenta, $_GET['idventa'])){

            }else{
                die("error");
            }
        }else{
            die("Parameters are missing");
        }
    }

    if($venta = cargarVentaPorID($_GET['idventa'])){

    }else{
        die("ERROR while searching for the sale");
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
    <link rel="icon" href="images/favicon.ico">
</head>
<body style="background-color:#f3f6f4;">

    <!-- Menu cabecera -->

    <?php 
        $menuaction = 'administracion';
        require_once './template-parts/header/menu_top.php' 
    ?>

    <!-- Menu lateral y formulario -->

    <div class="container-fluid">

        <div class="row">
            <?php require_once("template-parts/leftmenu/administracion.template.php"); ?>
            
            <div class="col-md-10 col-12">
                <h2 class="text-center mt-2 pt-2 pb-3 mb-md-5 mb-3 border border-5 rounded" style="background-color: #b0d588; letter-spacing: 7px;">EDITAR VENTA</h2>
                <div id="editarVenta">
                    <form method="post">
                        <div class="row border-bottom border-3 border-secondary">
                            <div class="col-md-3 col-12">
                                <label><b>Curso 1:</b></label>
                                <select class="form-select" name="formacionCurso1">
                                    <option value="Programada" <?php if($venta['curso1'] == "Programada") {echo " selected ";} ?>>Formacion programada</option>
                                    <option value="Privada" <?php if($venta['curso1'] == "Privada") {echo " selected ";} ?>>Formacion privada</option>
                                </select>
                            </div>

                            <div class="col-md-5 col-12">
                                <label class="form-check-label"><b>Nombre:</b></label>
                                <input id="nombreCurso1" class="form-control" type="text" name="nombreCurso1" value="<?php echo $venta['nombrecurso1'];?>"></input>
                            </div>

                            <div class="col-md-1 col-12">
                                <label class="form-check-label"><b>Horas:</b></label>
                                <input id="horasCurso1" class="form-control" type="text" name="horasCurso1" value="<?php echo $venta['horascurso1'];?>"></input>
                            </div>

                            <div class="col-md-3 col-12">
                                <label class="form-check-label"><b>Modalidad:</b></label>
                                <select class="form-select mb-2" name="modalidadCurso1">
                                    <option value="teleformacion" <?php if($venta['modalidadcurso1'] == "teleformacion") {echo " selected ";} ?>>Teleformacion</option>
                                    <option value="presencial" <?php if($venta['modalidadcurso1'] == "presencial") {echo " selected ";} ?>>Presencial</option>
                                    <option value="mixta" <?php if($venta['modalidadcurso1'] == "teleformacion") {echo " selected ";} ?>>Mixta</option>
                                </select>
                            </div>
                        </div>

                        <div class="row border-bottom border-3 border-secondary">
                            <div class="col-md-3 col-12">
                                <label><b>Curso 2:</b></label>
                                <select class="form-select" name="formacionCurso2">
                                    <option value="Programada" <?php if($venta['curso2'] == "Programada") {echo " selected ";} ?>>Formacion programada</option>
                                    <option value="Privada" <?php if($venta['curso2'] == "Privada") {echo " selected ";} ?>>Formacion privada</option>
                                </select>
                            </div>

                            <div class="col-md-5 col-12">
                                <label class="form-check-label"><b>Nombre:</b></label>
                                <input id="nombreCurso2" class="form-control" type="text" name="nombreCurso2" value="<?php echo $venta['nombrecurso2'];?>"></input>
                            </div>

                            <div class="col-md-1 col-12">
                                <label class="form-check-label"><b>Horas:</b></label>
                                <input id="horasCurso2" class="form-control" type="text" name="horasCurso2" value="<?php echo $venta['horascurso2'];?>"></input>
                            </div>

                            <div class="col-md-3 col-12">
                                <label class="form-check-label"><b>Modalidad:</b></label>
                                <select class="form-select mb-2" name="modalidadCurso2">
                                    <option value="teleformacion" <?php if($venta['modalidadcurso2'] == "teleformacion") {echo " selected ";} ?>>Teleformacion</option>
                                    <option value="presencial" <?php if($venta['modalidadcurso2'] == "presencial") {echo " selected ";} ?>>Presencial</option>
                                    <option value="mixta" <?php if($venta['modalidadcurso2'] == "mixta") {echo " selected ";} ?>>Mixta</option>
                                </select>
                            </div>
                        </div>

                        <div class="row border-bottom border-3 border-secondary">
                            <div class="col-md-3 col-12">
                                <label><b>Curso 3:</b></label>
                                <select class="form-select" name="formacionCurso3">
                                    <option value="Programada" <?php if($venta['curso3'] == "Programada") {echo " selected ";} ?>>Formacion programada</option>
                                    <option value="Privada" <?php if($venta['curso3'] == "Privada") {echo " selected ";} ?>>Formacion privada</option>
                                </select>
                            </div>

                            <div class="col-md-5 col-12">
                                <label class="form-check-label"><b>Nombre:</b></label>
                                <input id="nombreCurso3" class="form-control" type="text" name="nombreCurso3" value="<?php echo $venta['nombrecurso3'];?>"></input>
                            </div>

                            <div class="col-md-1 col-12">
                                <label class="form-check-label"><b>Horas:</b></label>
                                <input id="horasCurso3" class="form-control" type="text" name="horasCurso3" value="<?php echo $venta['horascurso3'];?>"></input>
                            </div>

                            <div class="col-md-3 col-12">
                                <label class="form-check-label"><b>Modalidad:</b></label>
                                <select class="form-select mb-2" name="modalidadCurso3">
                                    <option value="teleformacion" <?php if($venta['modalidadcurso3'] == "teleformacion") {echo " selected ";} ?>>Teleformacion</option>
                                    <option value="presencial" <?php if($venta['modalidadcurso3'] == "presencial") {echo " selected ";} ?>>Presencial</option>
                                    <option value="mixta" <?php if($venta['modalidadcurso3'] == "mixta") {echo " selected ";} ?>>Mixta</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-12 col-12">
                                <label class="form-check-label"><b>Email (para factura bonificacion):</b></label>
                                <input id="emailFacturacion" class="form-control" type="text" name="emailFacturacion" value="<?php echo $venta['emailfactura']; ?>"></input>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 col-12">
                                <label class="form-check-label"><b>Asesoria:</b></label>
                                <input id="asesoria" class="form-control" type="text" name="asesoria" value="<?php echo $venta['nombreasesoria']; ?>"></input>
                            </div>

                            <div class="col-md-4 col-12">
                                <label class="form-check-label"><b>Telefono asesoria:</b></label>
                                <input id="telefAsesoria" class="form-control" type="text" name="telefAsesoria" value="<?php echo $venta['telfasesoria']; ?>"></input>
                            </div>

                            <div class="col-md-4 col-12">
                                <label class="form-check-label"><b>Email asesoria:</b></label>
                                <input id="emailAsesoria" class="form-control" type="text" name="emailAsesoria" value="<?php echo $venta['mailasesoria']; ?>"></input>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 col-12">
                                <label class="form-check-label"><b>Importe:</b></label>
                                <input id="importe" class="form-control" type="text" name="importe" value="<?php echo $venta['importe']; ?>"></input>
                            </div>

                            <div class="col-md-4 col-12">
                                <label class="form-check-label"><b>Fecha de cobro:</b></label>
                                <input id="fechaCobro" class="form-control" type="text" name="fechaCobro" value="<?php echo $venta['fechacobro'] ?>"></input>
                            </div>

                            <div class="col-md-4 col-12">
                                <label class="form-check-label"><b>Forma de pago:</b></label>
                                <select class="form-select" name="formaPago">
                                    <option value="Transferencia" <?php if($venta['formapago'] == "Transferencia") {echo " selected ";} ?>>Transferencia</option>
                                    <option value="Domiciliacion" <?php if($venta['formapago'] == "Domiciliacion") {echo " selected ";} ?>>Domiciliacion</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8 col-12">
                                <label class="form-check-label"><b>Numero de cuenta:</b></label>
                                <input id="numeroCuenta" class="form-control"type="text" name="numeroCuenta" value="<?php echo $venta['numerocuenta']; ?>"></input>   
                            </div>

                            <div class="col-md-4 col-12">
                                <label class="form-check-label"><b>Comercial:</b></label>
                                <select class="form-select" id="comerciales" name="comercial">

                                    <?php 

                                        $comerciales = cargarComerciales();

                                        for($i=0; $i < count($comerciales); $i++){

                                            if($comerciales[$i]['activo'] == 1){
                                                $selected = ($venta['idcomercial'] == $comerciales[$i]['codigousuario']) ? " selected " : " ";

                                            echo "<option value='" . $comerciales[$i]['codigousuario'] . "' ".$selected.">" . $comerciales[$i]['nombre'] . "</option>";

                                            }

                                        }

                                    ?>

                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 col-12">
                                <label><b>Observaciones:</b></label>
                                <textarea class="form-control" name="observacionesVenta"><?php echo $venta['observacionesventa']; ?></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 col-12">
                            <input type="submit" class="btn btn-primary mb-3 mt-3 col-12 col-md-12" style="background-color: #1e989e" value="Guardar" name="Guardar">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <footer class="border-top border-secondary" style="background-color:#e4e4e4; height: 75px;">

            <p class="text-center mt-md-4" style='color: #8fd247;'> <b> © Dixma Formación 2022. | Ctra. Madrid 152, Vigo 36318 | info@dixmaformacion.com | Tlf: +34 604 067 035 </b> </p>

    </footer>
    
</body>
</html>