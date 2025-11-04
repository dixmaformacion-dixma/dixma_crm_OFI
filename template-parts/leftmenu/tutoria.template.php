<?php 
$file = basename($_SERVER['PHP_SELF']);
?>
<div class="col-md-2 col-12 align-items-start text-justify" style="background-color:#e4e4e4;">
    <nav class="navbar-nav nav-pills flex-column mt-2 mb-2">
        <a class="nav-link <?php
        if($file == 'tutoria_buscarEmpresa.php' or $file == 'tutoria_NIFcheck.php' or $file == 'tutoria_insertarAlumno.php'){
            echo 'active text-bg-secondary';
        }; 
        ?>"
        href="tutoria_buscarEmpresa.php">
            <img class="ms-3" src="images/iconos/person-plus.svg">
            <b> Insertar alumnos </b>
        </a>
        <a class="nav-link <?php
        if($file == 'tutoria_listadoAlumno.php' or $file == 'tutoria_editarAlumno.php'){
            echo 'active text-bg-secondary';
        }; 
        ?>" href="tutoria_listadoAlumno.php">
            <img class="ms-3" src="images/iconos/list.svg">
            <b> Listado Alumno </b>
        </a>
        <a class="nav-link <?php if($file == 'tutoria_insertarCurso.php'){echo 'active text-bg-secondary';}; ?>"
        href="tutoria_insertarCurso.php">
            <img class="ms-3" src="images/iconos/book.svg">
            <b> Insertar curso </b>
        </a>
        <a class="nav-link <?php if($file == 'tutoria_llamada.php'){echo 'active text-bg-secondary';}; ?>"
        href="tutoria_llamada.php">
            <img class="ms-3" src="images/iconos/telephone.svg">
            <b> llamada </b>
        </a>
        <a class="nav-link <?php if($file == 'tutoria_listadoCursos.php'){echo 'active text-bg-secondary';}; ?>"
        href="tutoria_listadoCursos.php">
            <img class="ms-3" src="images/iconos/list.svg">
            <b> Listado cursos </b>
        </a>
        <a class="nav-link <?php if($file == 'tutoria_listadoCursosActivos.php'){echo 'active text-bg-secondary';}; ?>"
        href="tutoria_listadoCursosActivos.php">
            <img class="ms-3" src="images/iconos/play-circle.svg">
            <b> Activos </b>
        </a>
        <a class="nav-link <?php if($file == 'tutoria_buscarCursos.php'){echo 'active text-bg-secondary';}; ?>"
        href="tutoria_buscarCursos.php">
            <img class="ms-3" src="images/iconos/search.svg">
            <b> Buscar cursos </b>
        </a>
        <a class="nav-link <?php if($file == 'tutoria_cursosPendientes.php'){echo 'active text-bg-secondary';}; ?>"
        href="tutoria_cursosPendientes.php">
            <img class="ms-3" src="images/iconos/exclamation-triangle-fill.svg">
            <b> Cursos Pendientes </b>
        </a>
        <a class="nav-link <?php if($file == 'tutoria_cursosContenido.php' || $file == 'tutoria_nuevoContenido.php' || $file == 'tutoria_editarContenido.php'){echo 'active text-bg-secondary';}; ?>"
            href="tutoria_cursosContenido.php">
            <img class="ms-3" src="images/iconos/list-ul.svg">
            <b> Contenido curso </b>
        </a>
        <a class="nav-link <?php if($file == 'tutoria_buscarAlumno.php'){echo 'active text-bg-secondary';}; ?>"
        href="tutoria_buscarAlumno.php">
            <img class="ms-3" src="images/iconos/file-earmark-person.svg">
            <b> Buscar alumno </b>
        </a>
    </nav>
</div>