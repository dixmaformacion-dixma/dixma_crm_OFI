<div class="col-md-2 col-12 align-items-start text-justify" style="background-color:#e4e4e4;">
    <nav class="navbar-nav nav-pills flex-column mt-2 mb-2">
        <a class="nav-link active text-bg-secondary" href="buscarEmpresa.php"> <img class="ms-3" src="images/iconos/search.svg"> <b> Insertar / Buscar </b></a>
        <a class="nav-link" href="pendientes.php"> <img class="ms-3" src="images/iconos/exclamation-triangle.svg"> <b> Pendientes </b></a>
        <a class="nav-link" href="listado.php"> <img class="ms-3" src="images/iconos/list.svg"> <b> Listado </b></a>
        <a class="nav-link" href="sectores.php"> <img class="ms-3" src="images/iconos/briefcase.svg"> <b> Sectores </b></a>
        <a class="nav-link" href="control_llamadas.php"> <img class="ms-3" src="images/iconos/telephone.svg"> <b> Control de llamadas </b></a>
        <a class="nav-link" href="citas.php"> <img class="ms-3" src="images/iconos/calendar-day.svg"> <b> Citas </b></a>
        <a class="nav-link" href="listadoCitas.php"> <img class="ms-3" src="images/iconos/calendar-date.svg"> <b> Listado de Citas </b></a>
        <a class="nav-link" href="cursosInteresados.php"> <img class="ms-3" src="images/iconos/book.svg"> <b> Cursos interesados </b></a>

    <?php 
        
        if($_SESSION['codigoUsuario'][0] == "2"){

            echo "<hr class='border border-dark'>";
            echo "<a class='nav-link' href='pedirCita.php'> <img class='ms-3' src='images/iconos/calendar-plus.svg'> <b> Pedir Cita </b></a>";
            echo "<a class='nav-link' href='hacerSeguimiento.php'> <img class='ms-3' src='images/iconos/box-arrow-in-right.svg'> <b> Hacer seguimiento </b></a>";

        }

        if($_SESSION['codigoUsuario'][0] == "1"){

            echo "<hr class='border border-dark'>";
            echo "<a class='nav-link' href='Callcenter_crearCurso.php'> <img class='ms-3' src='images/iconos/book.svg'> <b> Crear curso </b></a>";

        }

    ?>

    </nav> 
</div>