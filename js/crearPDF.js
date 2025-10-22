
function crearPDF(idempresa) {

    window.location.href = "crearContrato.php?idEmpresa=" + idempresa;

}

function crearRLT(idempresa) {

    window.location.href = "crearRLT.php?idEmpresa=" + idempresa;

}

function volverAtras() {

    window.location.href = "administracion.php";

}

function volverAtrasAlumno() {

    window.location.href = "tutoria.php";

}

function imprimir() {

    window.print(1);

}