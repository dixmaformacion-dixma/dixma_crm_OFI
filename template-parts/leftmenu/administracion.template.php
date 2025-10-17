<?php 
$file = basename($_SERVER['PHP_SELF']);
?>
<div class="col-md-2 col-12 align-items-start text-justify" style="background-color:#e4e4e4;">
    <nav class="navbar-nav nav-pills flex-column mt-2 mb-2">
        <a class="nav-link <?php if($file == 'buscarVenta.php' || $file == 'insertarVenta.php'){echo 'active text-bg-secondary';}; ?>"
        href="buscarVenta.php">
            <img class="ms-3" src="images/iconos/file-earmark-plus.svg">
            <b> Insertar venta </b>
        </a>
        <a class="nav-link <?php if($file == 'asignarCitas.php'){echo 'active text-bg-secondary';}; ?>"
        href="asignarCitas.php">
            <img class="ms-3" src="images/iconos/check-circle.svg">
            <b> Asignar cita </b>
        </a>
        <a class="nav-link <?php if($file == 'usuarios.php' || $file == 'eliminarUsuario.php'){echo 'active text-bg-secondary';}; ?>"
        href="usuarios.php">
            <img class="ms-3" src="images/iconos/person.svg">
            <b> Usuarios </b>
        </a>
        <a class="nav-link <?php if($file == 'empresas.php' || $file == 'eliminarEmpresa.php'){echo 'active text-bg-secondary';}; ?>"
        href="empresas.php">
            <img class="ms-3" src="images/iconos/building.svg">
            <b> Eliminar empresas </b>
        </a>
        <a class="nav-link <?php if($file == 'listadoVentas.php'){echo 'active text-bg-secondary';}; ?>"
        href="listadoVentas.php">
            <img class="ms-3" src="images/iconos/list.svg">
            <b> Listado ventas </b>
        </a>
        <a class="nav-link <?php if($file == 'administracion_crearContrato.php'){echo 'active text-bg-secondary';}; ?>"
        href="administracion_crearContrato.php">
            <img class="ms-3" src="images/iconos/filetype-pdf.svg">
            <b> Crear contrato </b>
        </a>
        <a class="nav-link <?php if($file == 'administracion_crearCurso.php'){echo 'active text-bg-secondary';}; ?>"
        href="administracion_crearCurso.php">
            <img class="ms-3" src="images/iconos/book.svg">
            <b> Crear curso </b>
        </a>
        <a class="nav-link <?php if($file == 'administracion_crearFactura.php'){echo 'active text-bg-secondary';}; ?>"
        href="administracion_crearFactura.php">
            <img class="ms-3" src="images/iconos/filetype-pdf.svg">
            <b> Crear factura </b>
        </a>
        <a class="nav-link <?php if($file == 'administracion_buscarAlumno.php'){echo 'active text-bg-secondary';}; ?>"
        href="administracion_buscarAlumno.php">
            <img class="ms-3" src="images/iconos/file-earmark-person.svg">
            <b> Ficha de alumno </b>
        </a>
        <a class="nav-link <?php if($file == 'administracion_fichaEmpresa.php'){echo 'active text-bg-secondary';}; ?>"
        href="administracion_fichaEmpresa.php" >
            <img class="ms-3" src="images/iconos/file-earmark-person.svg">
            <b> Ficha empresa </b>
        </a>
    </nav> 
</div>