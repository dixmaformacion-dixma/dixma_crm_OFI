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

    //include "tutoria_editar_AlumnoCurso_function.php";
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es" lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutoria</title>
    <link href="css/bootstrap.min.css" rel="stylesheet"></link>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/tutoria.js"></script>
    <script src="js/alumnocurso.js"></script>
    <link rel="icon" href="images/favicon.ico">
</head>
<body style="background-color:#f3f6f4;">

    <?php 
        $menuaction = 'tutoria';
        require_once './template-parts/header/menu_top.php' 
    ?>

    <!-- Menu lateral y formulario -->

    <div class="container-fluid">

        <div class="row">

            <?php require_once("template-parts/leftmenu/tutoria.template.php"); ?>

            <div class="col-md-10 col-12" id="formBusqueda">

                <form method="GET">

                    <h2 class="text-center mt-2 pt-2 pb-3 mb-md-5 mb-3 border border-5 rounded" style="background-color: #b0d588; letter-spacing: 7px;">BUSCAR CURSOS</h2>

                    <div class="container-fluid">
                        <form method="get">
                            <div id="FiltersWrapper">
                            </div>
                            <div class="mx-auto row col-md-7 col-7 mb-5">
                                <button type='button' class="form-control" onclick="addFilter()"> ADD FILTER </button>
                                <input class="btn btn-primary" style="background-color:#1e989e" type="submit" value="Buscar"></input>
							<?php
							$cursos = false;
							if(/*$_SERVER['REQUEST_METHOD'] === 'GET' and*/ isset($_GET['filterValue']) and isset($_GET['filterName'])){
							$cursos = buscarAlumnoCursos($_GET['filterName'], $_GET['filterOperator'], $_GET['filterValue']);
							}
								$ncur = 0;
							if(is_array($cursos))
								$ncur = count($cursos);
							
							$showAsistenciaButton = false;
                                $n_accion = null;
                                $n_grupo = null;

                                if (isset($_GET['filterName']) && is_array($_GET['filterName']) && isset($_GET['filterValue'])) {
                                    $filterNames = $_GET['filterName'];
                                    $filterValues = $_GET['filterValue'];

                                    $accion_key = array_search('N_Accion', $filterNames);
                                    $grupo_key = array_search('N_Grupo', $filterNames);

                                    if ($accion_key !== false && $grupo_key !== false) {
                                        $showAsistenciaButton = true;
                                        $n_accion = $filterValues[$accion_key];
                                        $n_grupo = $filterValues[$grupo_key];
                                    }
                                }
							
							
							?>
                                <?php echo "<table><tr><td><b>$ncur</b></td><td> RESULTADOS PARA LA FECHA:</td></tr> </table>"; ?>
                            </div>
                        </form>
                        <div class="mb-5">
                        <?php
							$page_from = 'tutoria_buscarCursos.php'."?".$_SERVER['QUERY_STRING'];
                            if(/*$_SERVER['REQUEST_METHOD'] === 'GET' and*/ isset($_GET['filterValue']) and isset($_GET['filterName'])){
                                if($cursos){
									
                                    require("template-parts/components/cursolist.listadoCursos.php");
                                }
                            }
							
							if ($showAsistenciaButton) {
                                $baseUrl = "tutoria_controlAsistencia.php?N_Accion=" . urlencode($n_accion) . "&N_Grupo=" . urlencode($n_grupo);
                                echo '<div class="text-center mt-4">';
                                echo '<a href="' . $baseUrl . '" id="controlAsistenciaBtn" class="btn btn-success">Control de Asistencia</a>';
                                echo '<a href="tutoria_diplomaPDF_all.php" id="printAll" target="_blank" class="btn btn-danger ms-2">';
                                echo '<i class="fa fa-print"></i> Imprimir Diplomas Seleccionados</a>';
                                echo '</div>';
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
<script>
	
	$(document).ready(function() {
        $('#controlAsistenciaBtn').on('click', function(e) {
            e.preventDefault(); // Evita que el enlace funcione de inmediato

            let selectedIds = [];
            $('input.selectable:checked').each(function() {
                if ($(this).val() !== 'all') { // Ignoramos el checkbox "seleccionar todo"
                    selectedIds.push($(this).val());
                }
            });

            if (selectedIds.length === 0) {
                alert('Por favor, selecciona al menos un alumno para generar el control de asistencia.');
                return;
            }

            let baseUrl = $(this).attr('href');
            window.location.href = baseUrl + '&ids=' + selectedIds.join(',');
        });
		
		
    });

	$(document).on('click', '.selectable', function(){
        let href = $('#printAll').attr('href').split('?')[0];
        
        if($(this).val() == 'all'){
            $('.selectable').prop('checked', $(this).prop('checked'));
        }
        
        let selectables = $(".selectable:checked").toArray()
            .map(x => $(x).val())
            .filter(x => !isNaN(parseInt(x)));
        
        href += `?ids=${selectables.join(',')}`;
        $('#printAll').attr('href', href);
	
    });
	
    const hoy = '<?= date("Y-m-d") ?>';
<?php
	
	if(isset($_REQUEST['filterName'])){
		
		if(is_array($_REQUEST['filterName'])){
			
			
			$nval = 0;
			$Fecha_ = false;
			foreach($_REQUEST['filterName'] as $idx => $filter){
			if($Fecha_){
				$Fecha_ = false;
				continue;
			}
			?>
			var filtid = addFilter();
			document.querySelector("#filter"+filtid + " select[name='filterName[]']").value = '<?php echo $filter;?>';
			
			changeFieldType(`filter${filtid}`);

			if(document.querySelector("#filter"+filtid + " select[name='filterValue[]']"))
				document.querySelector("#filter"+filtid + " select[name='filterValue[]']").value = '<?php echo $_REQUEST['filterValue'][$nval];?>';
			
			if(document.querySelector("#filter"+filtid + " input[name='filterValue[]']"))
                document.querySelector("#filter"+filtid + " input[name='filterValue[]']").value = '<?php echo $_REQUEST['filterValue'][$nval];?>';
	
			<?php
			if($filter == "Fecha_Inicio" || $filter == "Fecha_Fin"){
				$Fecha_ = true;
				$nval++;?>
	
			if(document.querySelectorAll("#filter"+filtid + " input[name='filterValue[]']")[1])
				document.querySelectorAll("#filter"+filtid + " input[name='filterValue[]']")[1].value = '<?php echo $_REQUEST['filterValue'][$nval];?>';
	
			<?php }?>
	
			
			
	
			<?php
			$nval++;
		}
			
		}
		
	}else{
        ?> 
            var filtid = addFilter();
            console.log(filtid);
            document.querySelectorAll("[name='filterName[]']")[0].value = 'Anno';
            document.querySelectorAll("[name='filterValue[]']")[0].value = '<?= date("Y") ?>';
            filtid = addFilter();
            document.querySelectorAll("[name='filterName[]']")[1].value = 'Tipo_Venta';
            document.querySelectorAll("[name='filterValue[]']")[1].value = 'Bonificado';
        <?php 
    }
?>
	
</script>
</html>
