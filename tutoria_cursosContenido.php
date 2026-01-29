<?php

    include "funciones/conexionBD.php";
    include "funciones/funcionesContenidos.php";

    session_start();

    if(empty($_SESSION)){

        header("Location: index.php");

    }

    date_default_timezone_set("Europe/Madrid");
    setlocale(LC_ALL, "spanish");

    if(isset($_GET['nuevoContenido']) && $_SERVER['REQUEST_METHOD'] == 'GET'){

        header("Location: tutoria_nuevoContenido.php");

    }
    
    if(isset($_GET['consultar']) && $_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['consultar'] == "Eliminar" && isset($_GET['idContenido'])){
        if(eliminarContenido($_GET['idContenido'])){
            echo "<div class='sucess'>Contenido eliminado exitosamente</div>";
        }else{
            echo "<div class='alert alert-danger' role='alert'>No se pudo eliminar este contenido</div>";
        }
    }
    
    if(isset($_GET['consultar']) && $_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['consultar'] == "Buscar"){

        if(empty($_GET['valor'])){
            echo "<div class='alert alert-danger' role='alert'> El campo de busqueda no puede estar vacío </div>";
        } else {
            if($contenidos = buscarContenidos($_GET['valor'])){

            } else {

                echo "<div class='alert alert-danger' role='alert'>No se encuentra ningún contenido</div>";

            }

        }

    }
    else{
        $contenidos = buscarContenidos(date('Y'));
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
    <script src="js/botonEliminar.js"></script>
    <link rel="icon" href="images/favicon.ico">
</head>
<body style="background-color:#f3f6f4;">

    <!-- Menu cabecera -->

    <?php 
        $menuaction = 'tutoria';
        require_once './template-parts/header/menu_top.php' 
    ?>

    <!-- Menu lateral y formulario -->

    <div class="container-fluid">

        <div class="row">

            <?php require_once("template-parts/leftmenu/tutoria.template.php"); ?>

                <div class="col-md-10 col-12 table-responsive">
    
                    <h2 class="text-center mt-2 pt-2 pb-3 mb-md-2 mb-2 border border-5 rounded" style="background-color: #b0d588; letter-spacing: 7px;">CONTENIDO CURSO</h2>
                    
                    <div class="col-md-10 col-12" id="formBusqueda">
        
                        <form method="GET">
        
                            <div class="container-fluid">
        
                                <div class="row d-flex justify-content-center">
        
                                    <div class="form-group col-md-4 col-12 text-center">
        
                                        <!--<label class="form-label">Inserta <b>Numero / Nombre</b> de la empresa:</label>-->
                                        <label class="form-label"><b>Acción / Año</b>:</label>
                                        <input type="text" class="form-control col-10 col-md-3" name="valor"></input>
                                        <input type="submit" class="btn mb-3 mt-3 col-12 col-md-12" style="background-color: #1e989e" value="Buscar" name="consultar">
        
                                    </div>
        
                                </div>
        
                            </div>
        
                        </form>
        
                    </div>

                    <form method="GET">

                    <button class="btn" style="background-color: #1e989e" name="nuevoContenido">AGREGAR NUEVO CONTENIDO</button>

                    </form>

                <?php

                    if(isset($contenidos) && is_array($contenidos) && count($contenidos) > 0){

                        echo "<table class='table table-striped table-bordered table-sm text-center mt-2 align-middle'>";

                        echo "<tr style='background-color: #8fd247;'>";
                        echo "<th> ID </th>";
                        echo "<th> Acción </th>";
                        echo "<th> Año </th>";
                        echo "<th></th>";
                        echo "</tr>";

                        for($i=0; $i < count($contenidos); $i++){
                            
                            $onclick = 'onclick=\'
                            if (confirm("Esta acción es irreversible, ¿Estás seguro de que deseas eliminar este contenido?")) {
                                window.location.href = "tutoria_cursosContenido.php?idContenido='.$contenidos[$i]['idcontenido'].'&consultar=Eliminar";
                            }
                        \'';

                            echo "<tr>";

                            echo "<td>" . $contenidos[$i]['idcontenido'] .  "</td>";
                            echo "<td>" . $contenidos[$i]['N_Accion'] .  "</td>";
                            echo "<td>" . $contenidos[$i]['Anno'] .  "</td>";

                          

                            echo "<td>  <label class='col-4'>";
                            echo "<a href='tutoria_editarContenido.php?idContenido=".$contenidos[$i]['idcontenido']."' type='button' class='btn btn-primary'><img src='images/iconos2/pencil-square.svg' class='ml-5' style='filter: invert(1);'></a> ";
                            echo "<button type='button' class='btn btn-danger' ".$onclick."><img src='images/iconos/x-circle-fill.svg' class='ml-5' style='filter: invert(1);'> </button>";
                            echo "</label> </td>";

                            
                            echo "</tr>";

                        }

                        echo "</table>";
                    }
                    echo "</div>";

                    ?>
            </div>
        </div>

    </div>

    <footer class="border-top border-secondary" style="background-color:#e4e4e4; height: 75px;">

            <p class="text-center mt-md-4" style='color: #8fd247;'> <b> © Dixma Formación 2022. | Ctra. Madrid 152, Vigo 36318 | info@dixmaformacion.com | Tlf: +34 604 067 035 </b> </p>

    </footer>

</body>
</html>