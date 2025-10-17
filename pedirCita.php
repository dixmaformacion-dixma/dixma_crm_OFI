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
    $limite = 10;

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
    if(isset($_GET['poblacion'])){
        $poblacion = $_GET['poblacion'];
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

    <nav class="navbar navbar-expand-lg justify-content-center border-bottom border-secondary" style="background-color:#e4e4e4;">

        <div class="container-fluid">

            <a class="navbar-brand" href="inicio.php"><img src="images/logo.gif" id="logo" class="img-fluid" style="width: 200px; heigth: 50px"></a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-center"  id="navbarSupportedContent">

                <div class="navbar-nav nav-pills">

                    <a class="nav-link active text-bg-secondary" href="inicio.php" aria-current="page"><b> Call Center </b></a>

                    <?php 

                        if($_SESSION['rol'] == "admin"){

                            echo " <a class='nav-link' href='administracion.php'><b> Administracion </b></a>";

                        }

                    ?>

                    <a class="nav-link" href="comercial.php"><b> Comercial </b></a>

                <?php

                    if($_SESSION['rol'] == "admin" || $_SESSION['codigoUsuario'][0] == "3"){

                    echo "<a class='nav-link' href='tutoria.php'><b> Tutoria </b></a>";

                    }

                ?>

                    <a class="nav-link disabled me-5" href=""><b> Estadisticas </b></a>
                    
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <b> <?php echo $_SESSION['usuario'] ?> </b>
                        </a>

                        <div class="dropdown-menu" style="background-color: #e4e4e4">
                            <a class="dropdown-item " href="perfilUsuario.php"><b> Perfil </b></a>
                            <hr class="dropdown-divider">
                            <a class="dropdown-item " href="funciones/cerrarSesion.php"><b> Cerrar sesion </b></a>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </nav>

    <!-- Menu lateral y formulario -->

    <div class="container-fluid">

        <div class="row">

            <div class="col-md-2 col-12 align-items-start text-justify" style="background-color:#e4e4e4;">
                <nav class="navbar-nav nav-pills flex-column mt-2 mb-2">
                    <a class="nav-link" href="buscarEmpresa.php"> <img class="ms-3" src="images/iconos/search.svg"> <b> Insertar / Buscar </b></a>
                    <a class="nav-link" href="pendientes.php"> <img class="ms-3" src="images/iconos/exclamation-triangle.svg"> <b> Pendientes </b></a>
                    <a class="nav-link" href="listado.php"> <img class="ms-3" src="images/iconos/list.svg"> <b> Listado </b></a>
                    <a class="nav-link" href="sectores.php"> <img class="ms-3" src="images/iconos/briefcase.svg"> <b> Sectores </b></a>
                    <a class="nav-link" href="control_llamadas.php"> <img class="ms-3" src="images/iconos/telephone.svg"> <b> Control de llamadas </b></a>
                    <a class="nav-link" href="citas.php"> <img class="ms-3" src="images/iconos/calendar-day.svg"> <b> Citas </b></a>
                    <a class="nav-link" href="listadoCitas.php"> <img class="ms-3" src="images/iconos/calendar-date.svg"> <b> Listado de Citas </b></a>
                    <a class="nav-link" href="cursosInteresados.php"> <img class="ms-3" src="images/iconos/book.svg"> <b> Cursos interesados </b></a>

                <?php 
                    
                    echo "<hr class='border border-dark'>";
                    echo "<a class='nav-link active text-bg-secondary' href='pedirCita.php'> <img class='ms-3' src='images/iconos/calendar-plus.svg'> <b> Pedir Cita </b></a>";
                    echo "<a class='nav-link' href='hacerSeguimiento.php'> <img class='ms-3' src='images/iconos/box-arrow-in-right.svg'> <b> Hacer seguimiento </b></a>";

                    if($_SESSION['codigoUsuario'][0] == "1"){

                        echo "<hr class='border border-dark'>";
                        echo "<a class='nav-link' href='Callcenter_crearCurso.php'> <img class='ms-3' src='images/iconos/book.svg'> <b> Crear curso </b></a>";

                    }

                ?>

                </nav> 
            </div>

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
                                <label><b>Ano:</b></label> <br>
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

                            <div class="col-md-2 col-2">
                                <label><b>&nbsp</b></label> <br>
                                <button class="btn btn-success" type="submit"> <img src="images/iconos2/search.svg"> </button>
                            </div>

  
                        </div>

                        <?php
                        if($llamadas = busquedaAnoMesPoblacion($search, $ano, $mes, $poblacion, $limite, $offset)){
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
                                        echo "<th> Ano/Mes </th>";
                                        echo "<th> </th>";
                                        echo "</tr>";

                                        foreach($llamadas as $llamada){
                                            echo "<tr>";
                                            echo "<td>" . $llamada['idempresa'] . "</td>";
                                            echo "<td>" . $llamada['nombre'] . "</td>";
                                            echo "<td>" . $llamada['poblacion'] . "</td>";
                                            echo "<td>" . $llamada['observacionesinterlocutor'] . "</td>";
                                            echo "<td>";
                                            if($llamada['anoPedirCita'] != "" and $llamada['mesPedirCita'] != ""){
                                                echo " " . $llamada['anoPedirCita'] . " " . ["ENE","FEB","MAR","ABR","MAY","JUN","JUL","AGO","SEP","OCT","NOV","DIC"][intval($llamada['mesPedirCita']) - 1]; 
                                                echo ' | <a href="javascript:editarFecha('.$llamada['idllamada'].','.$llamada['mesPedirCita'].','.$llamada['anoPedirCita'].')">Editar</a>';
                                            };
                                            echo "</td>";
                                            echo "<td> <button type='button' class='btn' style='background-color: #1e989e;' onclick='enviarConsultaPedirCita(" . $llamada['idempresa'] . ", " . $llamada['idllamada'] . ', "cita"' . ")'>Consultar <img src='images/iconos/info-circle.svg' class='ml-5'> </button> </td>";

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
                                die("No results or Something went wrong when querying the database");
                        }
                        ?>
                        

                    </div>

                </form>

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
        function editarFecha(id,mes,ano){
            $('#successMsj').hide();
            $('#dangerMsj').hide();
            $('#editarFecha').modal('show')
            $("select[name='mesPedirCita']").val(mes);
            $("input[name='anoPedirCita']").val(ano);
            $("input[name='idllamada']").val(id);
        }
        function modificarFecha(form){
            $('#successMsj').hide();
            $('#dangerMsj').hide();
            $.post(`/pedirCita.php`,{
                mesPedirCita:$("select[name='mesPedirCita']").val(),
                anoPedirCita:$("input[name='anoPedirCita']").val(),
                idllamada:$("input[name='idllamada']").val(),
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
    </script>
</body>
</html>