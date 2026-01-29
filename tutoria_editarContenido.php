<?php

    include "funciones/conexionBD.php";
    include "funciones/funcionesContenidos.php";


    session_start();

    if(empty($_SESSION)){

        header("Location: index.php");

    }

    date_default_timezone_set("Europe/Madrid");
    setlocale(LC_ALL, "spanish");

    if($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_POST['editar'])){
    
        $datosContenido = [
            'N_Accion' => $_POST['N_Accion'],
            'Anno' => $_POST['Anno'],
            'Contenido' => $_POST['Contenido'],
        ];
    
        if(editarContenido($datosContenido, $_POST['idContenido'])){
            echo "<div class='alert alert-success'> Success </div>";
        }else{
            echo "<div class='alert alert-danger mb-0'> ERROR: <pre>";
            print_r($datosContenido);
            echo "</div>";
        };
    }
    
    $chooseContenidoDialog = false;
    
    if(empty($_GET['idContenido'])){
        echo "<div class='alert alert-danger' role='alert'> El campo de busqueda no puede estar vacio </div>";
        die();
    } else {
        if($contenido = cargarContenido($_GET['idContenido'])){
        } else {
            echo "<div class='alert alert-danger' role='alert'>No se encuentra ningun contenido</div>";
            die();
        }
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
    <script src="js/nulosOtros.js"></script>
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

    <div class="col-md-10 col-12" id="datosConenido" <?php if($chooseContenidoDialog) {echo "hidden";}?>>

        <h2 class="text-center mt-2 pt-2 pb-3 mb-md-2 mb-3 border border-5" style="background-color: #b0d588; letter-spacing: 7px;">EDITAR CONTENIDO</h2>

        <div class="container-fluid">

            <div class="row d-flex justify-content-center">

                <form class="col-12" method="POST">

                <?php 
                
                $codigoAdmin = ultimoAdministrador();
                $codigoComercial = ultimoComercial();
                $codigoCallCenter = ultimoCallCenter();
                
                ?>

                <input name="admin" id="admin" value="<?php echo ($codigoAdmin[0] + 1) ?>" hidden="true"></input>
                <input name="admin" id="comercial" value="<?php echo ($codigoComercial[0] + 1) ?>" hidden="true"></input>
                <input name="admin" id="callcenter" value="<?php echo ($codigoCallCenter[0] + 1) ?>" hidden="true"></input>

                    <div class="row">

                        <div class="col-md-6 col-12">
                            <label><b>N Acción:</b></label>
                            <input class="form-control" name="N_Accion" value="<?php echo $contenido['N_Accion']?>" required></input>
                        </div>

                        <div class="col-md-6 col-12">
                            <label><b>Año:</b></label>
                            <input class="form-control" name="Anno" value="<?php echo $contenido['Anno']?>" required></input>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-12">
                            <label><b>Contenido:</b></label>
                        </div>
                        <div class="col-12">
                            <textarea id="Contenido" name="Contenido"><?php echo $contenido['Contenido']?></textarea>
                        </div>

                    </div>                    


                    <div class="row">
                        <div class="col-md-12 col-12">
                            <input name="idContenido" class="form-control" value="<?php echo $contenido['idcontenido'] ?>" type="text" hidden></input>
                            <input class="btn form-control mt-5 mb-5" style="background-color: #0d6efd; color:#fff" type="submit" name="editar" value="GUARDAR"></input>
                        </div>
                    </div>

                </form>

            </div>

        </div>

    </div>

<footer class="border-top border-secondary" style="background-color:#e4e4e4; height: 75px;">

    <p class="text-center mt-md-4" style='color: #8fd247;'> <b> © Dixma Formación 2022. | Ctra. Madrid 152, Vigo 36318 | info@dixmaformacion.com | Tlf: +34 604 067 035 </b> </p>

</footer>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" ></script>
<script src="https:////cdn.ckeditor.com/4.8.0/full-all/ckeditor.js"></script>
<script>

    CKEDITOR.replace("Contenido", {
        versionCheck: false,
        enterMode: CKEDITOR.ENTER_P,
        toolbar: [
            ['Bold', '-', 'NumberedList', 'BulletedList', '-', 'FontSize', 'TextColor', 'Styles']
        ],
        stylesSet: [
            { name: 'Interlineado 0.5', element: 'p', attributes: { style: 'line-height: 0.5;' } },
            { name: 'Interlineado 0.8', element: 'p', attributes: { style: 'line-height: 0.8;' } },
            { name: 'Interlineado 1.0', element: 'p', attributes: { style: 'line-height: 1.0;' } },
            { name: 'Interlineado 1.5', element: 'p', attributes: { style: 'line-height: 1.5;' } },
            { name: 'Interlineado 2.0', element: 'p', attributes: { style: 'line-height: 2.0;' } },
            { name: 'Interlineado 2.5', element: 'p', attributes: { style: 'line-height: 2.5;' } }
            
        ],
        contentsCss: 'body { line-height: 0.8; }'
    });

    
</script>
</body>
</html>