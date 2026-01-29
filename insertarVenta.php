<?php

    include "funciones/conexionBD.php";
    include "funciones/funcionesEmpresa.php";
    include "funciones/funcionesLlamadas.php";
    include "funciones/funcionesCursos.php";
    include "funciones/funcionesCitas.php";
    include "funciones/funcionesVentas.php";
    include "funciones/cargarComerciales.php";

    session_start();

    if(empty($_SESSION)){

        header("Location: index.php");

    }

    date_default_timezone_set("Europe/Madrid");
    setlocale(LC_ALL, "spanish");
    $fechaHoy = date('d-m-Y');
    $horaActual = date('H:i');

    if(isset($_POST['insertar']) && $_SERVER['REQUEST_METHOD'] == 'POST'){

        if(empty($_POST['creditoGuardado'])){

            $_POST['creditoGuardado'] = "";

        }

        if($_POST['creditoGuardado'] == "No"){

            $_POST['creditoGuardado'] = "NO";
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

        if(strtolower($_POST['nuevaVenta']) == "si"){

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
            'idempresa' => $_GET['idEmpresa'],
            'fecha' => $fechaHoy,
            'hora' => $horaActual,

        ];

        if(insertarVenta($datosVenta)){

        }

    }


       //header('Refresh: 1; URL=administracion.php');

    };
    
    if($empresa = cargarEmpresa($_GET['idEmpresa'])){

    }

    if($listadoVentas = listadoVentas($_GET['idEmpresa'])){

    }

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
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

            <div class="col-md-10 col-12" id="datosEmpresa">

                <h2 class="text-center mt-2 pt-2 pb-3 mb-md-2 mb-3 border border-5 rounded" style="background-color: #b0d588; letter-spacing: 7px;">DATOS DE LA EMPRESA</h2>

                <div class="container-fluid">

                    <div class="row d-flex justify-content-center">

                        <form class="col-12" method="POST">

                            <input name="idEmpresa" value="<?php echo $_GET['idEmpresa'] ?>" hidden></input>

                            <?php require_once './template-parts/components/empresaFormDatosBasicos.php' ?>

                            <div class="row">
                                <div class="col-md-12 col-12">
                                    <h5 class="text-center mt-2 pt-2 pb-2 border border-5 rounded" style="background-color: #b0d588;"> ¿INSERTAR NUEVA VENTA? 

                                        <label class="form-check-label">Si</label>
                                        <input type="radio" id="nuevaVentaSi" value="si" name="nuevaVenta" class="form-check-input"></input>
                                        <label class="form-check-label">No</label>
                                        <input id="nuevaVentaNo" type="radio" value="no" name="nuevaVenta" class="form-check-input" checked></input>

                                    </h5>
                                </div>
                            </div>

                            <div id="formNuevaVenta" hidden="true">

                                <div class="row border-bottom border-3 border-secondary">
                                    <div class="col-md-3 col-12">
                                        <label><b>Curso 1:</b></label>
                                        <select class="form-select" name="formacionCurso1">
                                            <option value="Programada" selected>Formacion programada</option>
                                            <option value="Privada">Formacion privada</option>
                                        </select>
                                    </div>

                                    <div class="col-md-5 col-12">
                                        <label class="form-check-label"><b>Nombre:</b></label>
                                        <input id="nombreCurso1" class="form-control" type="text" name="nombreCurso1"></input>
                                    </div>

                                    <div class="col-md-1 col-12">
                                        <label class="form-check-label"><b>Horas:</b></label>
                                        <input id="horasCurso1" class="form-control" type="text" name="horasCurso1"></input>
                                    </div>

                                    <div class="col-md-3 col-12">
                                        <label class="form-check-label"><b>Modalidad:</b></label>
                                        <select class="form-select mb-2" name="modalidadCurso1">
                                            <option value="teleformacion" selected>Teleformacion</option>
                                            <option value="presencial">Presencial</option>
                                            <option value="mixta">Mixta</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row border-bottom border-3 border-secondary">
                                    <div class="col-md-3 col-12">
                                        <label><b>Curso 2:</b></label>
                                        <select class="form-select" name="formacionCurso2">
                                            <option value="Programada" selected>Formacion programada</option>
                                            <option value="Privada">Formacion privada</option>
                                        </select>
                                    </div>

                                    <div class="col-md-5 col-12">
                                        <label class="form-check-label"><b>Nombre:</b></label>
                                        <input id="nombreCurso2" class="form-control" type="text" name="nombreCurso2"></input>
                                    </div>

                                    <div class="col-md-1 col-12">
                                        <label class="form-check-label"><b>Horas:</b></label>
                                        <input id="horasCurso2" class="form-control" type="text" name="horasCurso2"></input>
                                    </div>

                                    <div class="col-md-3 col-12">
                                        <label class="form-check-label"><b>Modalidad:</b></label>
                                        <select class="form-select mb-2" name="modalidadCurso2">
                                            <option value="teleformacion" selected>Teleformacion</option>
                                            <option value="presencial">Presencial</option>
                                            <option value="mixta">Mixta</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row border-bottom border-3 border-secondary">
                                    <div class="col-md-3 col-12">
                                        <label><b>Curso 3:</b></label>
                                        <select class="form-select" name="formacionCurso3">
                                            <option value="Programada" selected>Formacion programada</option>
                                            <option value="Privada">Formacion privada</option>
                                        </select>
                                    </div>

                                    <div class="col-md-5 col-12">
                                        <label class="form-check-label"><b>Nombre:</b></label>
                                        <input id="nombreCurso3" class="form-control" type="text" name="nombreCurso3"></input>
                                    </div>

                                    <div class="col-md-1 col-12">
                                        <label class="form-check-label"><b>Horas:</b></label>
                                        <input id="horasCurso3" class="form-control" type="text" name="horasCurso3"></input>
                                    </div>

                                    <div class="col-md-3 col-12">
                                        <label class="form-check-label"><b>Modalidad:</b></label>
                                        <select class="form-select mb-2" name="modalidadCurso3">
                                            <option value="teleformacion" selected>Teleformacion</option>
                                            <option value="presencial">Presencial</option>
                                            <option value="mixta">Mixta</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-12 col-12">
                                        <label class="form-check-label"><b>Email (para factura bonificacion):</b></label>
                                        <input id="emailFacturacion" class="form-control" type="text" name="emailFacturacion" value="<?php echo  $emailFactura = !empty($listadoVentas) ? $listadoVentas[0]['emailfactura'] : "" ?>"></input>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 col-12">
                                        <label class="form-check-label"><b>Asesoria:</b></label>
                                        <input id="asesoria" class="form-control" type="text" name="asesoria" value="<?php echo  $asesoria = !empty($listadoVentas) ? $listadoVentas[0]['nombreasesoria'] : "" ?>"></input>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <label class="form-check-label"><b>Telefono asesoria:</b></label>
                                        <input id="telefAsesoria" class="form-control" type="text" name="telefAsesoria" value="<?php echo  $telefono = !empty($listadoVentas) ? $listadoVentas[0]['telfasesoria'] : "" ?>"></input>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <label class="form-check-label"><b>Email asesoria:</b></label>
                                        <input id="emailAsesoria" class="form-control" type="text" name="emailAsesoria" value="<?php echo  $email = !empty($listadoVentas) ? $listadoVentas[0]['mailasesoria'] : "" ?>"></input>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 col-12">
                                        <label class="form-check-label"><b>Importe:</b></label>
                                        <input id="importe" class="form-control" type="text" name="importe"></input>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <label class="form-check-label"><b>Fecha de cobro:</b></label>
                                        <input id="fechaCobro" class="form-control" type="date" name="fechaCobro"></input>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <label class="form-check-label"><b>Forma de pago:</b></label>
                                        <select class="form-select" name="formaPago">
                                            <option value="Transferencia" selected>Transferencia</option>
                                            <option value="Domiciliacion">Domiciliacion</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-8 col-12">
                                        <label class="form-check-label"><b>Numero de cuenta:</b></label>
                                        <input id="numeroCuenta" class="form-control"type="text" name="numeroCuenta" value="<?php echo  $numeroCuenta = !empty($listadoVentas) ? $listadoVentas[0]['numerocuenta'] : "" ?>"></input>   
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <label class="form-check-label"><b>Comercial:</b></label>
                                        <select class="form-select" id="comerciales" name="comercial">

                                            <?php 

                                                $comerciales = cargarComerciales();

                                                for($i=0; $i < count($comerciales); $i++){

                                                    if($comerciales[$i]['activo'] == 1){

                                                    echo "<option value='" . $comerciales[$i]['codigousuario'] . "'>" . $comerciales[$i]['nombre'] . "</option>";

                                                    }

                                                }

                                            ?>

                                        </select>
                                    </div>
                                </div>

                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                        <label><b>Observaciones:</b></label>
                                        <textarea class="form-control" name="observacionesVenta"></textarea>
                                        </div>
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
                                    <h5 class='text-center pt-2 pb-2 border border-5 rounded' style="background-color: #b0d588;">LISTADO DE VENTAS ANTERIORES: </h5>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-12 col-12">
                                    <?php

                                        if(!empty($listadoVentas)){

                                            echo "<div class='container-fluid mt-2 mb-3'>";
                                            

                                            for($i=0; $i < count($listadoVentas); $i++){

                                                echo "<table class='table table-bordered table-striped table-sm text-center mt-2 align-middle'>";

                                                echo "<tr>";
                                                echo "<td> ID venta: </td>";
                                                echo "<th>" . $listadoVentas[$i]['idventa'] . "</th>";
    
                                                echo "<td> Fecha: </td>";
                                                echo "<th>" . $listadoVentas[$i]['fecha'] . "</th>";
    
                                                echo "<td> Hora: </td>";
                                                echo "<th>" . $listadoVentas[$i]['hora'] . "</th>";
    
                                                echo "<td> ID comercial: </td>";
                                                echo "<th>" . $listadoVentas[$i]['idcomercial'] . "</th>";
                                                echo "</tr>";
    
                                                echo "<tr>";
                                                echo "<td>" . $listadoVentas[$i]['curso1'] . "</td>";
                                                echo "<th colspan=2> Curso 1: " . $listadoVentas[$i]['nombrecurso1'] . "</th>";
    
                                                echo "<td> Horas: </td>";
                                                echo "<th>" . $listadoVentas[$i]['horascurso1'] . "</th>";
    
                                                echo "<td> Modalidad: </td>";
                                                echo "<th colspan=2>" . $listadoVentas[$i]['modalidadcurso1'] . "</th>";
                                                echo "</tr>";
    
                                                echo "<tr>";
                                                echo "<td>" . $listadoVentas[$i]['curso2'] . "</td>";
                                                echo "<th colspan=2> Curso 2: " . $listadoVentas[$i]['nombrecurso2'] . "</th>";
    
                                                echo "<td> Horas: </td>";
                                                echo "<th>" . $listadoVentas[$i]['horascurso2'] . "</th>";
    
                                                echo "<td> Modalidad: </td>";
                                                echo "<th colspan=2>" . $listadoVentas[$i]['modalidadcurso2'] . "</th>";
                                                echo "</tr>";
    
                                                echo "<tr>";
                                                echo "<td>" . $listadoVentas[$i]['curso3'] . "</td>";
                                                echo "<th colspan=2> Curso 3: " . $listadoVentas[$i]['nombrecurso3'] . "</th>";
    
                                                echo "<td> Horas: </td>";
                                                echo "<th>" . $listadoVentas[$i]['horascurso3'] . "</th>";
    
                                                echo "<td> Modalidad: </td>";
                                                echo "<th colspan=2>" . $listadoVentas[$i]['modalidadcurso3'] . "</th>";
                                                echo "</tr>";
    
                                                echo "<tr>";
                                                echo "<td> Observaciones: </td>";
                                                echo "<th colspan=7>" . $listadoVentas[$i]['observacionesventa'] . "</th>";
                                                echo "</tr>";
    
                                                echo "<tr>";
                                                echo "<td> Email factura: </td>";
                                                echo "<th colspan=7>" . $listadoVentas[$i]['emailfactura'] . "</th>";
                                                echo "</tr>";
    
                                                echo "<tr>";
                                                echo "<td> Nombre asesoria: </td>";
                                                echo "<th colspan=2>" . $listadoVentas[$i]['nombreasesoria'] . "</th>";
    
                                                echo "<td> Telf asesoria: </td>";    
                                                echo "<th>" . $listadoVentas[$i]['telfasesoria'] . "</th>"; 
                                                
                                                echo "<td> Email asesoria: </td>";
                                                echo "<th colspan=2>" . $listadoVentas[$i]['mailasesoria'] . "</th>";
                                                echo "</tr>";
    
                                                echo "<tr>";
                                                echo "<td> Importe: </td>";
                                                echo "<th>" . $listadoVentas[$i]['importe'] . "</th>";
    
                                                echo "<td> Fecha cobro: </td>";
                                                echo "<th colspan=2>" . $listadoVentas[$i]['fechacobro'] . "</th>";
                                                
                                                echo "<td> FP: </td>";
                                                echo "<th colspan=2>" . $listadoVentas[$i]['formapago'] . "</th>";
                                                echo "</tr>";
    
                                                echo "<tr>";
                                                echo "<td> Numero de cuenta: </td>";
                                                echo "<th colspan=7>" . $listadoVentas[$i]['numerocuenta'] . "</th>";
                                                echo "</tr>";
    
                                                echo "</div>";

                                                echo "</table>";

                                                echo "<td colspan=8> <hr class='border border-success border-5'> </td>";

                                            } 

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