<?php

    include "funciones/conexionBD.php";
    include "funciones/funcionesAlumnosCursos.php";
    include "funciones/funcionesEmpresa.php";
    include "funciones/funcionesCursos.php";

    session_start();

    if(empty($_SESSION)){

        header("Location: index.php");

    }

    date_default_timezone_set("Europe/Madrid");
    setlocale(LC_ALL, "spanish");

    include "tutoria_editar_AlumnoCurso_function.php";
    include "tutoria_insertar_commentario_function.php";
    include "tutoria_editar_seguimentos_function.php";

    $date = date("Y-m-d");
    $year = date("Y");
    if(isset($_GET['year']) && $_GET['year'] != ''){
        $year = $_GET['year'];
    }
    $Tipo_Venta_Display = "Bonificado";
    if(isset($_GET['Tipo_Venta_Display']) && $_GET['Tipo_Venta_Display'] != ''){
        $Tipo_Venta_Display = $_GET['Tipo_Venta_Display'];
    }


?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es" lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutoria - Cursos Activos</title>
    <link href="css/bootstrap.min.css" rel="stylesheet"></link>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/tutoria.js"></script>
    <script src="js/alumnocurso.js"></script>
    <link rel="icon" href="images/favicon.ico">
</head>
<body style="background-color:#f3f6f4;">

    <?php require_once("template-parts/header/header.template.php"); ?>

    <!-- Menu lateral y formulario -->

    <div class="container-fluid">

        <div class="row">

            <?php require_once("template-parts/leftmenu/tutoria.template.php"); ?>

            <div class="col-md-10 col-12" id="formBusqueda">

                <form method="GET">

                    <h2 class="text-center mt-2 pt-2 pb-3 mb-md-5 mb-3 border border-5 rounded" style="background-color: #b0d588; letter-spacing: 7px;">LISTADO CURSOS ACTIVOS</h2>

                    <div class="container-fluid">
                        <form method="get">
                            <div class="mx-auto row col-md-7 col-12 mb-5">
                                <input name="year" class="form-control" value="<?php echo $year ?>" type="number"></input>
                                <div class="row">
                                    <label class='col-12'>
                                        <b>Tipo venta:</b>
                                        <input class="form-check-input" type="radio" name="Tipo_Venta_Display" Value="Todos" <?php if($Tipo_Venta_Display == "Todos"){echo "checked";} ?>>
                                        <label class="form-check-label" for="Todos">
                                            Todos
                                        </label>
                                        <input class="form-check-input" type="radio" name="Tipo_Venta_Display" Value="Bonificado" <?php if($Tipo_Venta_Display == "Bonificado"){echo "checked";} ?>>
                                        <label class="form-check-label" for="Bonificado">
                                            Bonificado
                                        </label>
                                        <input class="form-check-input" type="radio" name="Tipo_Venta_Display" Value="Privado" <?php if($Tipo_Venta_Display == "Privado"){echo "checked";} ?>>
                                        <label class="form-check-label" for="Privado">
                                            Privado
                                        </label>
                                    </label>
                                </div>
                                <input class="form-control btn btn-primary" style="background-color:#1e989e" type="submit" value="Buscar"></input>
                                <?php echo "RESULTADOS PARA LA FECHA: " . $year. " (Tipo venta: ".$Tipo_Venta_Display.")" ?>
                                <br>
                                Último número de acción:
                                <?php
                                $max_n_accion = 0;
                                if ($cursos_temp = cargarAlumnoCursosActivos($year, $Tipo_Venta_Display)) {
                                    foreach ($cursos_temp as $curso_item) {
                                        if (isset($curso_item['N_Accion']) && (int)$curso_item['N_Accion'] > $max_n_accion) {
                                            $max_n_accion = (int)$curso_item['N_Accion'];
                                        }
                                    }
                                }
                                echo $max_n_accion > 0 ? htmlspecialchars($max_n_accion) : 'N/A';
                                ?>
                                
                            </div>
                        </form>
                        <?php 
                        $page_from = "tutoria_listadoCursosActivos.php";
                        if($cursos = cargarAlumnoCursosActivos($year, $Tipo_Venta_Display)){
                            require("template-parts/components/cursolist.listadoCursos.php");
                        }
                        ?>
                        <div class="text-center">
                            <a href="tutoria_diplomaPDF_all.php" id="printAll" target="_blank" class="btn btn-info">
                                <i class="fa fa-print"></i> Imprimir Seleccionados</a>
                            </div>
                    </div>

                </form>

            </div>

        </div>

    </div>

    <footer class="border-top border-secondary" style="background-color:#e4e4e4; height: 75px;">

            <p class="text-center mt-md-4" style='color: #8fd247;'> <b> © Dixma Formación 2022. | Ctra. Madrid 152, Vigo 36318 | info@dixmaformacion.com | Tlf: +34 604 067 035 </b> </p>

    </footer>

    <script>
            $(document).on('click','.selectable',function(){
                let href = $('#printAll').attr('href').split('?')[0];
                if($(this).val()=='all'){
                    $('.selectable').prop('checked',$(this).prop('checked'))
                }
                let selectables = $(".selectable:checked").toArray().map(x=>$(x).val()).filter(x=>!isNaN(parseInt(x)))
                href+=`?ids=${selectables.join(',')}`
                $('#printAll').attr('href',href);
            })
    </script>
</body>
</html>