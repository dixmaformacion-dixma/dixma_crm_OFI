<?php

    include "funciones/conexionBD.php";
    include "funciones/funcionesEmpresa.php";

    session_start();

    if(empty($_SESSION)){

        header("Location: index.php");

    }

    date_default_timezone_set("Europe/Madrid");
    setlocale(LC_ALL, "spanish");

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
    <script src="js/crearPDF.js"></script>
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

            <div class="col-md-10 col-12" id="formBusqueda">
            <h2 class="text-center mt-2 pt-2 pb-3 mb-md-5 mb-3 border border-5 rounded" style="background-color: #b0d588; letter-spacing: 7px;">CREAR FACTURA</h2>
                <?php if(isset($_GET["N_Accion"]) and
                isset($_GET["N_Grupo"]) and
                isset($_GET["Ano"])){
                    if($empresas = buscarPorAccionGrupoAno($_GET["N_Accion"], $_GET["N_Grupo"], $_GET["Ano"])){?>
                    <form method="GET" action="administracion_facturaPDF.php">
                        <div class="container-fluid">
                            <div class="row d-flex justify-content-center">
                                <div class="form-group col-md-6 col-12 text-center">
                                        <label class="form-label"><b>Empresa:</b></label>
                                        <input type="hidden" class="form-control" name="N_Accion" value="<?php echo $_GET["N_Accion"] ?>"></input>
                                        <input type="hidden" class="form-control" name="N_Grupo" value="<?php echo $_GET["N_Grupo"] ?>"></input>
                                        <input type="hidden" class="form-control" name="Ano" value="<?php echo $_GET["Ano"] ?>"></input>
                                        <select class="form-control col-md-4 col-12" name="idEmpresa">
                                            <?php foreach($empresas as $empresa){?>
                                                <option value="<?php echo $empresa['idempresa']; ?>"><?php echo $empresa['nombre']; ?></option>
                                            <?php } ?>
                                        </select>
                                        <input type="submit" class="btn btn-primary mb-3 mt-3 col-12 col-md-12" style="background-color: #1e989e" value="Buscar">
                                </div>
                            </div>
                        </div>
                    </form>
                    <?php
                    }else{
                        echo "<div class='alert alert-danger' role='alert'>No se encontraron empresas para este grupo.</div>";
                    }
                ?>

                <?php }else{?>
                    <form method="GET">
                        <div class="container-fluid">
                            <div class="row d-flex justify-content-center">
                                <div class="form-group col-md-6 col-12 text-center">
                                        <label class="form-label"><b>№ Accion:</b></label>
                                        <input type="number" class="form-control" name="N_Accion"></input>
                                        <label class="form-label"><b>№ Grupo:</b></label>
                                        <input type="number" class="form-control" name="N_Grupo"></input>
                                        <label class="form-label"><b>Año:</b></label>
                                        <input type="number" class="form-control" name="Ano" value="<?php echo Date("Y"); ?>"></input>
                                        <input type="submit" class="btn btn-primary mb-3 mt-3 col-12 col-md-12" style="background-color: #1e989e" value="Buscar">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            <?php } ?>
        </div>
    </div>

    <footer class="border-top border-secondary" style="background-color:#e4e4e4; height: 75px;">

            <p class="text-center mt-md-4" style='color: #8fd247;'> <b> © Dixma Formación 2022. | Ctra. Madrid 152, Vigo 36318 | info@dixmaformacion.com | Tlf: +34 604 067 035 </b> </p>

    </footer>

</body>
</html>