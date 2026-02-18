$(document).ready(function() {

    $("#nivelEstudios").change(function() {

        valor = $("#nivelEstudios").val();

        if(valor == "otras"){

            $("#otrosNivelEstudios").removeAttr("hidden");

        } else {

            $("#otrosNivelEstudios").attr("hidden", "");

        }

    });

    $("#colectivo").change(function() {

        valor = $("#colectivo").val();
        
        if(valor == "otros"){

            $("#otrosColectivo").removeAttr("hidden");

        } else {

            $("#otrosColectivo").attr("hidden", "");

        }

    });


})

function agregarAlumno(idEmpresa){

    window.location.href = "tutoria_insertarAlumno.php?idEmpresa=" + idEmpresa;

}

function crearPDFAlumno(idEmpresa, idAlumno){

    window.location.href = "administracion_fichaAlumno.php?idEmpresa=" + idEmpresa + "&idAlumno=" + idAlumno;

}
function crearPDFAlumnoCurso(idEmpresa, idAlumno, StudentCursoID){

    window.location.href = "administracion_fichaAlumno.php?idEmpresa=" + idEmpresa + "&idAlumno=" + idAlumno + "&StudentCursoID=" + StudentCursoID;

}

function nuevaFichaAlumno(idEmpresa){

    window.location.href = "administracion_fichaAlumnoVacia.php?idEmpresa=" + idEmpresa;

}

function cargarDatos(sexo, categoriaProfesional, colectivo, grupoCotizacion, nivelEstudios) {


    if(sexo == "hombre"){

        $("#sexoHombre").attr('checked', true);


    } else {

        $("#sexoMujer").attr('checked', true);

    }

    switch(categoriaProfesional){

        case "directivo":
            $("#directivo").attr('checked', true);
            break;

        case "mandoIntermedio":
            $("#intermedio").attr('checked', true);
            break;

        case "tecnico":
            $("#tecnico").attr('checked', true);
            break;   
            
        case "trabajadorCualificado":
            $("#cualificado").attr('checked', true);
            break;       
            
        case "trabajadorConBajaCualificacion":
            $("#bajaCualificacion").attr('checked', true);
            break;   

    }

    switch(colectivo){

        case "regimenGeneral":
            $("#regimenGeneral").attr('checked', true);
            break;

        case "fijoDiscontinuo":
            $("#fijoDiscontinuo").attr('checked', true);
            break;

        case "otros":
            $("#otros").attr('checked', true);
            break;   

    }

    switch(grupoCotizacion){

        case "ingenierosLicenciados":
            $("#1").attr('checked', true);
            break;

        case "ingenierosTecnicos":
            $("#2").attr('checked', true);
            break;

        case "jefesAdministrativos":
            $("#3").attr('checked', true);
            break;
            
        case "ayudantesNoTitulados":
            $("#4").attr('checked', true);
            break;

        case "oficialesAdministrativos":
            $("#5").attr('checked', true);
            break;

        case "subalternos":
            $("#6").attr('checked', true);
            break;

        case "auxiliares":
            $("#7").attr('checked', true);
            break;

        case "oficialesDePrimera":
            $("#8").attr('checked', true);
            break;
            
        case "oficialesDeTercera":
            $("#9").attr('checked', true);
            break;

        case "mayores18":
            $("#10").attr('checked', true);
            break;

        case "menores18":
            $("#11").attr('checked', true);
            break;

    }

    switch(nivelEstudios){

        case "menosPrimaria":
            $("#menosPrimaria").attr('checked', true);
            break;

        case "primaria":
            $("#primaria").attr('checked', true);
            break;

        case "educacionSecundaria1":
            $("#educacionSecundaria1").attr('checked', true);
            break;       
                
        case "educacionSecundaria2":
            $("#educacionSecundaria2").attr('checked', true);
            break;

        case "educacionPostsecundaria":
            $("#educacionPostsecundaria").attr('checked', true);
            break;

        case "tecnicoSuperior":
            $("#tecnicoSuperior").attr('checked', true);
            break;

        case "universitarios1":
            $("#universitarios1").attr('checked', true);
            break;

        case "universitarios2":
            $("#universitarios2").attr('checked', true);
            break;

        case "universitarios3":
            $("#universitarios3").attr('checked', true);
            break;

        case "otras":
            $("#otras").attr('checked', true);
            break;

    }


}

function loginByCourse(numeroAccion, year, tipo) {
    // Apri finestra subito per evitare blocco popup
    var loginWindow = window.open('about:blank', '_blank');
    if (loginWindow) {
        loginWindow.document.write('<html><body style="font-family:Arial;text-align:center;padding:50px;"><h2>⏳ Generando acceso seguro...</h2></body></html>');
    }
    
    showToast('⏳ Buscando credenciales...', 'info');
    
    fetch('ajax/generate_login_token_by_course.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            numero_accion: numeroAccion,
            year: parseInt(year),
            tipo: tipo
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            if (loginWindow && !loginWindow.closed) {
                loginWindow.location.href = data.url;
                showToast('✅ Acceso generado: ' + data.curso, 'success');
            }
        } else {
            if (loginWindow) loginWindow.close();
            showToast('❌ ' + data.message, 'danger');
        }
    })
    .catch(error => {
        if (loginWindow) loginWindow.close();
        showToast('❌ Error: ' + error.message, 'danger');
    });
}

function showToast(message, type) {
    type = type || 'info';
    var toastEl = document.getElementById('loginToast');
    if (!toastEl) return;
    
    var colors = {
        'success': 'bg-success',
        'danger': 'bg-danger',
        'info': 'bg-info'
    };
    
    toastEl.className = 'toast align-items-center text-white border-0 ' + (colors[type] || 'bg-info');
    document.getElementById('toastMessage').textContent = message;
    
    new bootstrap.Toast(toastEl).show();
}
