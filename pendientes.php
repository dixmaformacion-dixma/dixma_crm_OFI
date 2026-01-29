<?php

    include "funciones/conexionBD.php";
    include "funciones/funcionesLlamadas.php";

    session_start();

    if(empty($_SESSION)){

        header("Location: index.php");

    }

    date_default_timezone_set("Europe/Madrid");
    setlocale(LC_ALL, "es_ES");
    $fechaHoy = date('Y-m-d');

    if(isset($_GET['consultar']) && $_SERVER['REQUEST_METHOD'] == 'GET'){

        if(empty($_GET['fechaInicio'])){

            echo "<div class='alert alert-danger' role='alert'> La <b>fecha de inicio</b> no puede estar vacia </div>";

        } else {

            $datosPendites = [
                'fechaInicio' => $_GET['fechaInicio'],
                'fechaFin' => $_GET['fechaFin'],
                'provincia' => $_GET['provincia'],
                'poblacion' => $_GET['poblacion'],
                'codigoUsuario' => $_SESSION['codigoUsuario'],

            ];
            

            if($llamadas = pendientes2($datosPendites)){


            } else {

                echo "<div class='alert alert-danger' role='alert'>No se encuentra ninguna pendiente</div>";

            }

        }



    }
    if(isset($_GET['cambiarFechaBoton'])){

        if(!empty($_GET['cambiarFechaCheck'])){

            $nuevaFecha = date('d-m-Y', strtotime($_GET['nuevaFecha']));

            cambiarFechasPendientes($_GET['cambiarFechaCheck'], $nuevaFecha);
            
            echo "<div class='alert alert-success' role='alert'>Fechas cambiadas con exito</div>";

            if(!empty($_GET['redirect'])){
                header("Refresh: 1; URL={$_GET['redirect']}");
            }

        } else {

            echo "<div class='alert alert-danger' role='alert'>No se ha seleccionado ninguna llamada</div>";

        }


    }



?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es" lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Call Center</title>
    <link href="css/bootstrap.min.css" rel="stylesheet"></link>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/botonConsultar.js"></script>
    <script src="js/arrayProvincias.js"></script>
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

            <div class="col-md-10 col-12" id="formPendientes">

                <form method="GET">

                    <h2 class="text-center mt-2 pt-2 pb-3 mb-md-5 mb-3 border border-5 rounded" style="background-color: #b0d588; letter-spacing: 7px;">PENDIENTES</h2>

                    <div class="container-fluid">

                        <div class="row d-flex justify-content-center">

                            <div class="form-group col-12 col-md-4 text-center">
                                
                                <label class="form-label"><b>Fecha de inicio:</b></label>
                                <input type="date" class="form-control col-6 col-md-1" value="<?php echo date('Y-m-d')?>" name="fechaInicio"></input>

                                <label class="form-label"><b>Provincia:</b></label>
                                <select class="form-select" name="provincia" id="selectProvincia">
                                    <option hidden="true" value="" selected></option>
                                    <option value="Pontevedra">Pontevedra</option>
                                    <option value="Orense">Orense</option>
                                    <option value="Lugo">Lugo</option>
                                    <option value="Coruña">Coruña</option>
                                </select>


                            </div>

                            <div class="form-group col-12 col-md-4 text-center">
                                
                                <label class="form-label mt-4 mt-md-0"><b>Fecha de fin:</b></label>
                                <input type="date" class="form-control col-6 col-md-1" value="<?php echo date('Y-m-d')?>" name="fechaFin"></input>

                                <label class="form-label"><b>Poblacion:</b></label>
                                <select class="form-select" name="poblacion" id="selectPoblacion">
                                    <option hidden="true" value="" selected></option>

                                </select>

                            </div>
                            
                            <div class="row d-flex justify-content-center">

                                <input type="submit" class="btn mb-3 mt-3 col-12 col-md-8" style="background-color: #1e989e" value="Buscar" name="consultar">

                            </div>
                            
                        </div>

                    </div>

                </form>

            </div>

            <?php

                if(!empty($llamadas)){

                    echo "<script>";
                    echo "$('#formPendientes').remove();";
                    echo "</script>";

                    echo "<div class='col-md-10 col-12 mt-2 table-responsive'>";

                    echo " <h2 class='text-center mt-2 pt-2 pb-3 mb-md-3 mb-3 border border-5 rounded' style='background-color: #b0d588; letter-spacing: 7px;'>PENDIENTES</h2>";

                    echo "<table class='table table-striped table-bordered table-sm text-center align-middle'>";
                    echo "<tr style='background-color: #8fd247;'>";
                    echo  "<th> ID </th>";
                    echo  "<th> Codigo </th>";
                    echo  "<th> Nombre </th>";
                    echo  "<th> Horario </th>";
                    echo  "<th> Poblacion </th>";
                    echo "<th>Cambiar fecha</th>";

                    echo  "<th></th>";
                    echo "</tr>";

                    for($i=0; $i < count($llamadas); $i++){

                        echo "<tr>";

                        echo "<td class='text-uppercase'>" . $llamadas[$i]['idempresa'] . "</td>";
                        echo "<td>" . $llamadas[$i]['tipo_seguimiento'].$llamadas[$i]['horapendiente'] . "</td>";
                        echo "<td>" . $llamadas[$i]['nombre'] . "</td>";
                        echo "<td>" . $llamadas[$i]['horario'] . "</td>";
                        echo "<td>" . $llamadas[$i]['poblacion'] . "</td>";
                        echo "<td> <input name='cambiarFechaCheck[]' value='" . $llamadas[$i]['idempresa'] . "' type='checkbox'></td>";

                        $fechaInicio = $_GET['fechaInicio'];
                        $fechaFin = $_GET['fechaFin'];
                        $provincia = $_GET['provincia'];
                        $poblacion = $_GET['poblacion'];
                        $redirectTo="pendientes.php?fechaInicio={$fechaInicio}&provincia={$provincia}&fechaFin={$fechaFin}&poblacion={$poblacion}&consultar=Buscar";
                        echo "<td> 
                            <form action='pedirCitaForm.php'>
                                <input type='hidden' name='idEmpresa' value='{$llamadas[$i]['idempresa']}'>
                                <input type='hidden' name='idLlamada' value='pendiente'>
                                <input type='hidden' name='tipo' value=''>
                                <input type='hidden' name='redirect' value='{$redirectTo}'>
                                <button type='submit' class='btn' style='background-color: #1e989e;'>Consultar <img src='images/iconos/info-circle.svg' class='ml-5'> </button> 
                            </form>
                        </td>";

                        echo "</tr>";

                    }

                    //if($_SESSION['codigoUsuario'] == "103"){

                    echo "<tr>";
                    echo "<th>Fecha</th>";
                    echo "<td> </td>";
                    echo "<td> </td>";
                    echo "<td> seleccionar todo:";
                    ?>
                    <input id="selectall" type='checkbox' onchange="
                    let checked = $('#selectall').get(0).checked;
                    console.log(checked);
                    
                    let checkboxes = $('[type=checkbox]');
                    for(let i = 0; i < checkboxes.length; i++){
                        checkboxes.get(i).checked = checked;
                    };
                    "></input>
                    <?php
                    echo "</td>";

                    echo "<td> <input class='form-control' name='nuevaFecha' value='$fechaHoy' type='date' required> </input> </td>";
                    echo "<td> <button class='btn btn-danger' onclick='cambiarFechaBtnClick()' name='cambiarFechaBoton' value='cambiarFecha'>Cambiar fecha <img src='images/iconos/arrow-repeat.svg' class='ml-5'> </button> </td>";

                    echo " </tr>";

                //}

                    echo "<tr>";
                    echo "<td colspan=6> <b> TOTAL PENDIENTES: $i </b> </td>";
                    echo "</tr>";

                    echo "</table>";
                    echo "</div>";

                }

                ?>

        </div>

    </div>

    <footer class="border-top border-secondary" style="background-color:#e4e4e4; height: 75px;">

            <p class="text-center mt-md-4" style='color: #8fd247;'> <b> © Dixma Formación 2022. | Ctra. Madrid 152, Vigo 36318 | info@dixmaformacion.com | Tlf: +34 604 067 035 </b> </p>

    </footer>

    <script>
        function cambiarFechaBtnClick(){
            let f = document.createElement('form')
            f.name = 'cambiarFechas';
            let n = null;
            $('input[name="cambiarFechaCheck[]"]:checked').toArray().map(x=>{
                n = document.createElement('input');
                n.type = 'hidden';
                n.name = 'cambiarFechaCheck[]';
                n.value = x.getAttribute('value');
                f.append(n);
            })
            n = document.createElement('input');
            n.type = 'hidden';
            n.name = 'cambiarFechaBoton';
            n.value = 'cambiarFecha';
            f.append(n);
            f.append(document.querySelectorAll('input[name="nuevaFecha"]')[0])

            n = document.createElement('input');
            n.type = 'hidden';
            n.name = 'redirect';
            n.value = document.location.href;
            f.append(n);

            f.action = document.location.href;
            document.body.append(f);
            f.submit();
        }
    </script>
    
</body>
</html>