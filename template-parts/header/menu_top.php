<nav class="navbar navbar-expand-lg justify-content-center border-bottom border-secondary" style="background-color:#e4e4e4;">
    <div class="container-fluid">
        <a class="navbar-brand" href="inicio.php"><img src="images/logo.gif" id="logo" class="img-fluid" style="width: 200px; heigth: 50px"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center"  id="navbarSupportedContent">
            <div class="navbar-nav nav-pills">
                <a class="nav-link <?= @$menuaction =='callcenter'?'active text-bg-secondary':'' ?>" href="inicio.php" aria-current="page"><b> Call Center </b></a>
            <?php if($_SESSION['rol'] == "admin"): ?>
                <a class='nav-link <?= @$menuaction =='administracion'?'active text-bg-secondary':'' ?>' href='administracion.php'><b> Administracion </b></a>
            <?php endif ?>
            <?php if(in_array($_SESSION['rol'],["admin","comercial"])): ?>
                <a class="nav-link <?= @$menuaction =='comercial'?'active text-bg-secondary':'' ?>" href="comercial.php"><b> Comercial </b></a>
            <?php endif ?>
            <?php if(in_array($_SESSION['rol'],["admin","callcenter","tutoria"])): ?>
                <a class='nav-link <?= @$menuaction =='tutoria'?'active text-bg-secondary':'' ?>' href='tutoria.php'><b> Tutoria </b></a>
            <?php endif ?>
            <?php if(in_array($_SESSION['rol'],["admin","comercial"])): ?>
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <b> Estadistícas </b>
                    </a>

                    <div class="dropdown-menu" style="background-color: #e4e4e4">
                        <a class="dropdown-item " href="control_llamadas.php"><b> Control de llamadas </b></a>
                    </div>
                </div>
            <?php endif ?>
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <b> <?php echo $_SESSION['usuario'] ?> </b>
                    </a>

                    <div class="dropdown-menu" style="background-color: #e4e4e4">
                        <a class="dropdown-item " href="perfilUsuario.php"><b> Perfil </b></a>
                        <hr class="dropdown-divider">
                        <a class="dropdown-item " href="funciones/cerrarSesion.php"><b> Cerrar sesion </b></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>