
function enviarConsulta(idempresa, tipo, fechaInicio, fechaFin, provincia, poblacion) {

    window.location.href = "consultarEmpresa.php?idEmpresa=" + idempresa + "&tipo=" + tipo + "&fechaInicio=" + fechaInicio + "&fechaFin=" + fechaFin + "&poblacion=" + poblacion + "&provincia=" + provincia;


}

function consultaAdministracion(idempresa) {

    window.location.href = "insertarVenta.php?idEmpresa=" + idempresa;

}

function enviarConsultaPedirCita(idempresa, idllamada, $tipo) {

    window.location.href = "pedirCitaForm.php?idEmpresa=" + idempresa + "&idLlamada=" + idllamada + "&tipo=" + $tipo;

}

function ventasComercial(idempresa) {

    window.location.href = "ventasComercial.php?idEmpresa=" + idempresa;

}