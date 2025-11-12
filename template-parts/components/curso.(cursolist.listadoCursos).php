<?php
if (!isset($statusColor) or !isset($statusDateColor)) {
        $statusColor = [
                "en curso" => "",
                "finalizado" => "background-color:  lightblue;",
                "descargado" => "background-color: lightblue;",
                "cerrado" => "background-color:  lightblue;",
                "baja" => "background-color:  #c30d0d; color:white;",
                "problem" => "background-color:  Gold;"
        ];
        $statusDateColor = [
                "en curso" => "",
                "finalizado" => "",
                "descargado" => "background-color: #c30d0d; color:white;",
                "cerrado" => "background-color: #00693E; color:white;",
                "baja" => "background-color:  #c30d0d; color:white;",
                "problem" => "background-color:  Gold;"
        ];
}

if (!isset($statusDiplomaColor)) {
        $statusDiplomaColor = [
                "Copia recibida" => "background-color: #28D700;",
                "Entregado" => "background-color: #28D700;",
        ];
}
?>
<style>
        .actions {
                display: flex;
                justify-content: center;
        }

        .actions>a,
        .actions>.dropdown>a {
                padding: 2px;
                border: 1px solid black;
                border-radius: 25%;
                width: 30px;
                height: 30px;
                display: flex;
                justify-content: space_between;
                padding-left: 2px;
                background-color: white;
        }
</style>

<div class="col-md-12 col-12 container border border-2 text-uppercase">
        <div class='row p-0' style="<?php echo $statusColor[$curso['status_curso']] ?>">
                <div style="width:5%">
                        <input type="checkbox" class="selectable" value="<?php echo $curso['StudentCursoID'] ?>">
                        <?php echo $numr; ?>
                </div>
                <?php $empresa = cargarEmpresa($curso['idEmpresa']); ?>
                <div class='col-md-2 border-right'>
                        <?php echo $curso['nombre'] . " " . $curso['apellidos']; ?>
                </div>
                <div style="width:9%">
                        <?php echo formattedDate($curso['Fecha_Inicio']); ?>
                </div>
                <div style="width:9%; <?php echo $statusDateColor[$curso['status_curso']] ?>">
                        <?php echo formattedDate($curso['Fecha_Fin']); ?>
                </div>
                <div class='col-md-2 border-right'>
                        <?php echo $curso['Denominacion']; ?>
                </div>
                <div style="width:3%">
                        <?php echo $curso['N_Accion']; ?><?php echo "/"; ?><?php echo $curso['N_Grupo']; ?>
                </div>
                <div style="width:3%">
                        <input type="checkbox" <?php if ($curso['Recibi_Material'] == 1) {
                                                        echo "checked";
                                                } ?> disabled>
                </div>
                <div style="width:3%">
                        <input type="checkbox" <?php if ($curso['CC'] == 1) {
                                                        echo "checked";
                                                } ?> disabled>
                </div>
                <div class='col-md-1 border-right'>
                        <?php echo $empresa['nombre']; ?>
                </div>
                <div class='col-md-1 border-right' style="<?php echo @$statusDiplomaColor[$curso['Diploma_Status']] ?>">
                        <?php echo $curso['Diploma_Status']; ?>
                </div>
                <div class="col actions">
                        <a class="colapse-toggle"
                                data-bs-toggle="collapse"
                                href="#infoCurso<?php echo $curso['StudentCursoID']; ?>">
                                <img src="images/iconos2/aspect-ratio.svg">
                        </a>
                        <a class="colapse-toggle"
                                data-bs-toggle="collapse"
                                href="#infoEdit<?php echo $curso['StudentCursoID']; ?>">
                                <img src="images/iconos2/pencil-square.svg">
                        </a>
                        <div class="dropdown d-flex">
                                <a href="#" class="dropdown-toggle no-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                        <img src="images/iconos/filetype-pdf.svg" alt="PDF">
                                </a>
                                <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="tutoria_diplomaPDF.php?StudentCursoID=<?php echo $curso['StudentCursoID']; ?>" target="_blank">Diploma</a></li>
                                        <li><a class="dropdown-item" href="tutoria_recepcionPDF.php?StudentCursoID=<?php echo $curso['StudentCursoID']; ?>" target="_blank">Recepción</a></li>
                                        <li><a class="dropdown-item" href="tutoria_guiaDidactica.php?StudentCursoID=<?php echo $curso['StudentCursoID']; ?>" target="_blank">Guía</a></li>
                                </ul>
                        </div>

                </div>

        </div>
        <div class="collapse" id="infoCurso<?php echo $curso['StudentCursoID']; ?>">
                <div class='row mx-auto my-2 container border border-5 m-2' style="background-color:white">
                        <label class='col-md-6 col-12'>
                                <b>Empresa:</b>
                                <?php echo $empresa['nombre']; ?>
                        </label>
                        <label class='col-md-6 col-12'>
                                <b>Email Empresa:</b>
                                <span class="text-lowercase"><?php echo $empresa['email']; ?></span>
                        </label>
                        <label class='col-md-6 col-12'>
                                <b>telefono Empresa:</b>
                                <?php echo $empresa['telef1'] . " | " . $empresa['telef2']; ?>
                        </label>
                        <label class='col-md-6 col-12'>
                                <b>Persona de contacto:</b>
                                <?php echo $empresa['personacontacto']; ?>
                        </label>
                        <div class="text-center mt-2 mb-2">
                                <a href="buscarVenta.php?valor=<?php echo urlencode($empresa['nombre']); ?>&consultar=Buscar" target="_blank" class="btn btn-info">Información Empresa</a>
                        </div>
                </div>
                <div class='row mx-auto my-2'>
                        <div class='row mx-auto my-2 container border border-5 m-2' style="background-color:white">
                                <label class='col-md-4 col-12'>
                                        <b>telefono alumno:</b>
                                        <?php echo $curso['telefono']; ?>
                                </label>
                                <label class='col-md-4 col-12'>
                                        <b>email alumno:</b>
                                        <span class="text-lowercase"><?php echo $curso['email']; ?></span>
                                </label>
                                <label class='col-md-4 col-12'>
                                        <b>DNI/NIE:</b>
                                        <?php echo $curso['nif']; ?>
                                </label>
                        </div>
                </div>
                <div class="col-md-12 col-12 container mt-3 mb-2 border border-2 rounded" style="background-color:white">
                        <label class='col-md-12 col-12'>
                                <b>Denominacion:</b>
                                <?php echo $curso['Denominacion']; ?>
                        </label>
                        <label class='col-md-3 col-12'>
                                <b>№ Accion:</b>
                                <?php echo $curso['N_Accion']; ?>
                        </label>
                        <label class='col-md-4 col-12'>
                                <b>№ Grupo:</b>
                                <?php echo $curso['N_Grupo']; ?>
                        </label>
                        <label class='col-md-4 col-12'>
                                <b>№ Horas:</b>
                                <?php echo $curso['N_Horas']; ?>
                        </label>
                        <label class='col-md-3 col-12'>
                                <b>Modalidad:</b>
                                <?php echo $curso['Modalidad']; ?>
                        </label>
                        <label class='col-md-4 col-12'>
                                <b>Tipo Venta:</b>
                                <?php echo $curso['Tipo_Venta']; ?>
                        </label>
                        <label class='col-md-4 col-12'>
                                <b>Tutor:</b>
                                <?php echo $curso['tutor']; ?>
                        </label>
                        <label class='col-md-12 col-12 mt-2'>
                                <b>Empresa (en el momento del formacion):</b>
                                <?php $empresa = cargarEmpresa($curso['idEmpresa']);
                                echo $empresa['nombre']; ?>
                                [Tel: <b><?php echo $empresa['telef1']; ?></b>]
                        </label>
                        <div class="row">
                                <label class='col-md-3 col-12'>
                                        <b>A.P:</b>
                                        <?php echo $curso['AP']; ?>
                                </label>
                                <label class='col-md-4 col-12'>
                                        <b>Recibi_Material:</b>
                                        <input type="checkbox" <?php if ($curso['Recibi_Material'] == 1) {
                                                                        echo "checked";
                                                                } ?> disabled>
                                </label>
                                <label class='col-md-2 col-12'>
                                        <b>CC:</b>
                                        <input type="checkbox" <?php if ($curso['CC'] == 1) {
                                                                        echo "checked";
                                                                } ?> disabled>
                                </label>
                                <label class='col-md-2 col-12'>
                                        <b>RLT:</b>
                                        <input type="checkbox" <?php if ($curso['RLT'] == 1) {
                                                                        echo "checked";
                                                                } ?> disabled>
                                </label>
                        </div>
                        <div class="row">
                                <label class='col-md-12 col-12'>
                                        <b>Diploma:</b>
                                        <?php echo $curso['Diploma_Status']; ?>
                                        <i>(la última vez que cambió el estado del diploma): <?php echo formattedDate($curso['Diploma_Status_Ultimo_Cambio']); ?></i>
                                </label>
                        </div>
                        <?php
                        require("template-parts/components/seguimentosAndComments.(curso.listadoCursos).php");
                        ?>
                </div>
        </div>
        <?php
        require("template-parts/components/cursoEditar.(curso.listadoCursos).php");
        ?>
</div>
<script>
        // Toggle custom PDF menus (no Bootstrap)
        document.addEventListener('click', function(e) {
                var btn = e.target.closest('.print-pdf-btn');
                if (btn) {
                        var container = btn.closest('.print-pdf-dropdown');
                        var menu = container.querySelector('.print-pdf-menu');
                        // close others
                        document.querySelectorAll('.print-pdf-menu.show').forEach(function(m) {
                                if (m !== menu) m.classList.remove('show');
                        });
                        menu.classList.toggle('show');
                        return;
                }
                // close if click outside any dropdown
                if (!e.target.closest('.print-pdf-dropdown')) {
                        document.querySelectorAll('.print-pdf-menu.show').forEach(function(m) {
                                m.classList.remove('show');
                        });
                }
        });
</script>