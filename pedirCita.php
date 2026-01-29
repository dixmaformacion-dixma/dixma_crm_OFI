<?php

    include "funciones/conexionBD.php";
    include "funciones/funcionesEmpresa.php";
    include "funciones/funcionesLlamadas.php";

    session_start();

    if(empty($_SESSION)){

        header("Location: index.php");

    }
    if(!empty($_POST['modificarFecha'])){
        modificarFecha($_POST);
        echo json_encode(['success'=>true]);
        die;
    }

    $pagina = 1;
    $limite = 100;

    if(isset($_GET['pagina'])){

        $pagina = $_GET['pagina'];

    } else {

        $pagina = 1;

    }

    $offset = ($pagina - 1) * $limite;

    date_default_timezone_set("Europe/Madrid");
    setlocale(LC_ALL, "spanish");

    $ano = "Todas";
    $mes = "Todas";
    $poblacion = "Todas";
    $provincia = "Todas";
    $prioridad = "";
    $search = "";

    if(isset($_GET['busqueda'])){
        $search = $_GET['busqueda'];
    }
    if(isset($_GET['ano'])){
        $ano = $_GET['ano'];
    }
    if(isset($_GET['mes'])){
        $mes = $_GET['mes'];
    }
    if(isset($_GET['provincia'])){
        $provincia = $_GET['provincia'];
    }
    if(isset($_GET['poblacion'])){
        $poblacion = $_GET['poblacion'];
    }
    if(isset($_GET['prioridad'])){
        $prioridad = $_GET['prioridad'];
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
    <script src="js/botonConsultar.js"></script>
    <script src="js/botonWord.js"></script>
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

            <div class="col-md-10 col-12" id="formBusqueda">

                <form method="GET">

                    <h2 class="text-center mt-2 pt-2 pb-3 mb-md-2 mb-3 border border-5 rounded" style="background-color: #b0d588; letter-spacing: 7px;">PEDIR CITA</h2>

                    <div class="container-fluid">

                        <div class="row ms-auto mb-2">

                            <div class="col-md-3 col-10">
                                <label><b>ID empresa || Observaciones:</b></label> <br>
                                <input type="search" class="form-control" placeholder="Buscar" name="busqueda" <?php if(isset($_GET['busqueda'])){echo 'value="'.$_GET['busqueda'].'"';} ?>></input>
                            </div>
                            <div class="col-md-2 col-12">
                                <label><b>Provincia:</b></label> <br>
                                    <select class="form-select" name="provincia" id="selectProvincia" required>
                                        <option value="Todas" <?php if($provincia == "Todas"){echo ' selected ';} ?>>Todas</option>
                                        <option value="Pontevedra" <?php if($provincia == "Pontevedra"){echo ' selected ';} ?>>Pontevedra</option>
                                        <option value="Orense" <?php if($provincia == "Orense"){echo ' selected ';} ?>>Orense</option>
                                        <option value="Lugo" <?php if($provincia == "Lugo"){echo ' selected ';} ?>>Lugo</option>
                                        <option value="Coruña" <?php if($provincia == "Coruña"){echo ' selected ';} ?>>Coruña</option>
                                    </select>
                            </div>
                            <div class="col-md-2 col-12">
                                <label><b>Poblacion:</b></label> <br>
                                <select class="form-select" name="poblacion" id="selectPoblacion" required>
                                    <?php echo '<option selected value="'.$poblacion.'">'.$poblacion.'</option>'; ?>
                                </select>
                            </div>
                            <div class="col-md-2 col-10">
                                <label><b>Año:</b></label> <br>
                                <select name="ano" id="anoPedirCita" class="form-control">
                                    <option value="Todas" <?php if($ano == "Todas"){echo ' selected ';} ?>> Todas </option>
                                    <?php
                                    for($i = 2021; $i < intval(Date("Y"))+5; $i++){
                                        if($ano == $i){
                                            echo '<option selected value="'.$i.'">'.$i.'</option>';
                                        }else{
                                            echo '<option value="'.$i.'">'.$i.'</option>';
                                        }
                                    } 
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-1 col-10">
                                <label><b>Mes:</b></label> <br>
                                <select name="mes" id="mesPedirCita" class="form-control">
                                    <option value="Todas" <?php if($mes == "Todas" or $mes == "todas"){echo ' selected ';} ?>> Todas </option>
                                    <option value="1" <?php if($mes == 1){echo ' selected ';} ?>> enero </option>
                                    <option value="2" <?php if($mes == 2){echo ' selected ';} ?>> febrero </option>
                                    <option value="3" <?php if($mes == 3){echo ' selected ';} ?>> marzo </option>
                                    <option value="4" <?php if($mes == 4){echo ' selected ';} ?>> abril </option>
                                    <option value="5" <?php if($mes == 5){echo ' selected ';} ?>> mayo </option>
                                    <option value="6" <?php if($mes == 6){echo ' selected ';} ?>> junio </option>
                                    <option value="7" <?php if($mes == 7){echo ' selected ';} ?>> julio </option>
                                    <option value="8" <?php if($mes == 8){echo ' selected ';} ?>> agosto </option>
                                    <option value="9" <?php if($mes == 9){echo ' selected ';} ?>> septiembre </option>
                                    <option value="10" <?php if($mes == 10){echo ' selected ';} ?>> octubre </option>
                                    <option value="11" <?php if($mes == 11){echo ' selected ';} ?>> noviembre </option>
                                    <option value="12" <?php if($mes == 12){echo ' selected ';} ?>> diciembre </option>
                                </select>
                            </div>
                             <div class="col-md-2 col-12">
                                <label><b>PRIORIDAD:</b></label> <br>
                                    <select class="form-select" name="prioridad" id="selectProvincia" required>
                                        <option value="Todas" <?php if($prioridad == 'Todas'){echo ' selected ';} ?>>TODAS</option>
                                        <option value="BAJO" <?php if($prioridad == 'BAJO'){echo ' selected ';} ?>>BAJO</option>
                                        <option value="MEDIO" <?php if($prioridad == 'MEDIO'){echo ' selected ';} ?>>MEDIO</option>
                                        <option value="ALTO" <?php if($prioridad == 'ALTO'){echo ' selected ';} ?>>ALTO</option>
                                    </select>
                            </div>

                            <div class="col-md-12 text-center">
                                <label><b>&nbsp</b></label> <br>
                                <button class="btn btn-success" type="submit"> <img src="images/iconos2/search.svg"> BUSCAR</button>
                            </div>

  
                        </div>
                </form>

                        <?php
                        if($llamadas = busquedaAnoMesPoblacion($search, $ano, $mes, $poblacion,$provincia,$prioridad, $limite, $offset)){
                            $totalPaginas = ceil($llamadas[0]['full_count'] / $limite);
                        ?>
                            <div class="row d-flex justify-content-center">

                            <div class="col-md-12 col-12">

                                <?php

                                    if(!empty($llamadas)){

                                        echo "<table class='table table-striped table-bordered table-sm text-center align-middle'>";
                                        echo "<tr style='background-color: #8fd247;'>";
                                        echo "<th> ID </th>";
                                        echo "<th> Nombre </th>";
                                        echo "<th> Poblacion </th>";
                                        echo "<th> Observaciones </th>";
                                        echo "<th> Año/Mes </th>";
                                        echo "<th> Prioridad </th>";
                                        echo "<th> </th>";
                                        echo "</tr>";
                                        $redirectTo="/pedirCita.php?busqueda=$search&provincia=$provincia&poblacion=$poblacion&ano=$ano&mes=$mes&prioridad=$prioridad";

                                        foreach($llamadas as $llamada){
                                            echo "<tr>";
                                            echo "<td>" . $llamada['idempresa'] . "</td>";
                                            echo "<td>" . $llamada['nombre'] . "</td>";
                                            echo "<td>" . $llamada['poblacion'] . "</td>";
                                            echo "<td>" . $llamada['observacionesinterlocutor'] . "</td>";
                                            echo "<td>";
                                            if($llamada['anoPedirCita'] != "" and $llamada['mesPedirCita'] != ""){
                                                echo " " . $llamada['anoPedirCita'] . " " . ["ENE","FEB","MAR","ABR","MAY","JUN","JUL","AGO","SEP","OCT","NOV","DIC"][intval($llamada['mesPedirCita']) - 1]; 
                                                echo ' | <a href="javascript:editarFecha('.$llamada['idllamada'].','.$llamada['mesPedirCita'].','.$llamada['anoPedirCita'].',\''.$llamada['prioridad'].'\')">Editar</a>';
                                            };
                                            echo "</td>";
                                            echo "<td class='bg-".@['BAJO'=>'success','MEDIO'=>'warning','ALTO'=>'danger'][$llamada['prioridad']]."'>
                                                " . $llamada['prioridad'];
                                            echo ' | <a href="javascript:editarFecha('.$llamada['idllamada'].','.$llamada['mesPedirCita'].','.$llamada['anoPedirCita'].',\''.$llamada['prioridad'].'\')">Editar</a>';
                                            echo "</td>";
                                            echo "<td> 
                                                <form action='pedirCitaForm.php'>
                                                    <input type='hidden' name='idEmpresa' value='{$llamada['idempresa']}'/>
                                                    <input type='hidden' name='idLlamada' value='{$llamada['idllamada']}'/>
                                                    <input type='hidden' name='tipo' value='cita'/>
                                                    <input type='hidden' name='redirect' value='{$redirectTo}'/>
                                                    <button type='submit' class='btn' style='background-color: #1e989e;'>Consultar <img src='images/iconos/info-circle.svg' class='ml-5'> </button> 
                                                </form>
                                            </td>";

                                            echo "</tr>";
                                        }

                                        echo "</table>";

                                    }

                                ?>

                                

                            </div>

                            <div class="col-md-12 col-12 text-center">

                            <p>
                             
                            <form method="get">
                            <?php
                            
                                if($pagina > 1) {

                                    echo '<button class="btn btn-primary" type="submit" name="pagina" value="' . ($pagina-1) . '">Anterior</button>';

                                }

                                echo " Pagina " . $pagina . " de " . $totalPaginas . " ";

                                if($pagina < $totalPaginas) {
                                    echo '<button class="btn btn-primary" type="submit" name="pagina" value="' . ($pagina+1) . '">Siguiente</button>';

                                }

    
                            ?>
                            </form>

                            </p>

                            </div>

                        </div>
                        
                        <?php }else{
                               echo "No results or Something went wrong when querying the database";
                        }
                        ?>
                        

                    </div>

                

            </div>

        </div>

    </div>

    <footer class="border-top border-secondary" style="background-color:#e4e4e4; height: 75px;">

            <p class="text-center mt-md-4" style='color: #8fd247;'> <b> © Dixma Formación 2022. | Ctra. Madrid 152, Vigo 36318 | info@dixmaformacion.com | Tlf: +34 604 067 035 </b> </p>

    </footer>

    <div class="modal" tabindex="-1" id="editarFecha">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" onsubmit="modificarFecha(this);return false;">
                    <div class="modal-header">
                        <h5 class="modal-title">Modificar Fecha de Cita</h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Mes</label>
                            <select name="mesPedirCita" id="" class="form-control">
                                <option value="1">Enero</option>
                                <option value="2">Febrero</option>
                                <option value="3">Marzo</option>
                                <option value="4">Abril</option>
                                <option value="5">Mayo</option>
                                <option value="6">Junio</option>
                                <option value="7">Julio</option>
                                <option value="8">Agosto</option>
                                <option value="9">Septiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Año</label>
                            <input type="number" name="anoPedirCita" value="" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Prioridad</label>
                            <select class="form-select" name="prioridad">
                                <option value=""></option>
                                <option value="BAJO">BAJO</option>
                                <option value="MEDIO">MEDIO</option>
                                <option value="ALTO">ALTO</option>
                            </select>
                        </div>
                        <input type="hidden" name="idllamada">
                        <div class="alert alert-success" id="successMsj">Datos almacenados con exito</div>
                        <div class="alert alert-danger" id="dangerMsj">Ocurrio un error al modificar la fecha, por favor intente nuevamente</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="$('#editarFecha').modal('hide')">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
        </div>
    <script>
        function editarFecha(id,mes,ano,prioridad){
            $('#successMsj').hide();
            $('#dangerMsj').hide();
            $('#editarFecha').modal('show')
            $("select[name='mesPedirCita']").val(mes);
            $("input[name='anoPedirCita']").val(ano);
            $("input[name='idllamada']").val(id);
            $("#editarFecha select[name='prioridad']").val(prioridad);
        }
        function modificarFecha(form){
            $('#successMsj').hide();
            $('#dangerMsj').hide();
            $.post(`/pedirCita.php`,{
                mesPedirCita:$("select[name='mesPedirCita']").val(),
                anoPedirCita:$("input[name='anoPedirCita']").val(),
                idllamada:$("input[name='idllamada']").val(),
                prioridad:$("#editarFecha select[name='prioridad']").val(),
                modificarFecha:1
            },function(d){
                d = JSON.parse(d);
                if(d.success){
                    $('#successMsj').show();
                    document.location.reload();
                }else{
                    $('#dangerMsj').show();
                }
            });
        }
        window.onload = function(){
            setTimeout(function(){
                poblacion = $('#selectPoblacion').val();
                $('#selectProvincia').change();
                $('#selectPoblacion').val(poblacion)
            },600)
        }
    </script>
</body>
</html>