<?php
$completed = (
        (strtotime($llamada['seguimento0']) <= strtotime($date) && $llamada['seguimento0check'] == "0") ||
        (strtotime($llamada['seguimento1']) <= strtotime($date) && $llamada['seguimento1check'] == "0") ||
        (strtotime($llamada['seguimento2']) <= strtotime($date) && $llamada['seguimento2check'] == "0") ||
        (strtotime($llamada['seguimento3']) <= strtotime($date) && $llamada['seguimento3check'] == "0") ||
        (strtotime($llamada['seguimento4']) <= strtotime($date) && $llamada['seguimento4check'] == "0") ||
        (strtotime($llamada['seguimento5']) <= strtotime($date) && $llamada['seguimento5check'] == "0")
);
?>
<div class="col-md-12 col-12 container mt-3 border border-4 rounded">
        <div class='row mx-auto my-2'>
                <label class='col-md-6 col-12'>
                        <b>nombre:</b>
                        <?php echo $llamada['nombre']." ".$llamada['apellidos']; ?>
                </label>
                <?php if(!$completed){?>
                        <b class='col-md-4 col-12'>Todas las llamadas ya fueron hechas</b>
                        <a 
                                class="btn btn-primary col-md-2 col-12"
                                data-bs-toggle="collapse"
                                href="#infoLlamada<?php echo $llamada['StudentCursoID']; ?>">
                                mas detalle
                        </a>
                <?php } ?>
        </div>
        <div class="collapse <?php if($completed){ echo "show";} ?>" id="infoLlamada<?php echo $llamada['StudentCursoID']; ?>" >
                <div class='row mx-auto my-2'>
                        <label class='col-md-4 col-12'>
                                <b>telefono:</b>
                                <?php echo $llamada['telefono']; ?>
                        </label>
                        <label class='col-md-4 col-12'>
                                <b>email:</b>
                                <?php echo $llamada['email']; ?>
                        </label>
                        <label class='col-md-4 col-12'>
                                <b>fechaNacimiento:</b>
                                <?php echo formattedDate($llamada['fechaNacimiento']); ?>
                        </label>
                </div>
                <div class="col-md-12 col-12 container mt-3 mb-2 border border-2 rounded" style="background-color:white">
                        <label class='col-md-12 col-12'>
                                <b>Denominacion:</b>
                                <?php echo $llamada['Denominacion']; ?>
                        </label>
                        <label class='col-md-4 col-12'>
                                <b>N Accion:</b>
                                <?php echo $llamada['N_Accion']; ?>
                        </label>
                        <label class='col-md-4 col-12'>
                                <b>N_Grupo:</b>
                                <?php echo $llamada['N_Grupo']; ?>
                        </label>
                        <label class='col-md-4 col-12'>
                                <b>N_Horas:</b>
                                <?php echo str_replace('.', ',', $llamada['N_Horas']); ?>
                        </label>
                        <label class='col-md-4 col-12'>
                                <b>Modalidad:</b>
                                <?php echo $llamada['Modalidad']; ?>
                        </label>
                        <label class='col-md-4 col-12'>
                                <b>Tipo Venta:</b>
                                <?php echo $llamada['Tipo_Venta']; ?>
                        </label>
                        <label class='col-md-4 col-12'>
                                <b>Tutor:</b>
                                <?php echo $llamada['tutor']; ?>
                        </label>
                        <label class='col-md-12 col-12 mt-2'>
                                <b>Empresa:</b>
                                <?php $empresa = cargarEmpresa($llamada['idEmpresa']);
                                echo $empresa['nombre']; ?>
                                [Tel: <b><?php echo $empresa['telef1']; ?></b>]
                                [Persona de contacto:: <b><?php echo $empresa['personacontacto']; ?></b>]
                        </label>
                        <label class='col-md-4 col-12'>
                                <b>Fecha_Inicio:</b>
                                <?php echo date("d/m/Y",strtotime($llamada['Fecha_Inicio'])); ?>
                        </label>
                        <label class='col-md-4 col-12'>
                                <b>Fecha_Fin:</b>
                                <?php echo date("d/m/Y",strtotime($llamada['Fecha_Fin'])); ?>
                        </label>
                        <?php 
                                $curso = $llamada;
                                require("template-parts/components/seguimentosAndComments.(curso.listadoCursos).php");
                        ?>
                </div>
        </div>
</div>