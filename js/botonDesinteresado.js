
async function desinteresado (idempresa, codigoCurso) {

    //window.location.href = "eliminarCurso.php?idEmpresa=" + idempresa + "&codigoCurso=" + codigoCurso;
    if(confirm(`Seguro que desea eliminar el curso con ID: ${codigoCurso}`)){
        await fetch("eliminarCurso.php?idEmpresa=" + idempresa + "&codigoCurso=" + codigoCurso+ "&eliminar="+idempresa);
        window.location.reload()
    }
}

function noEliminar() {

    window.location.href = "cursosInteresados.php";

}


