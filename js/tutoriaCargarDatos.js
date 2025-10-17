function cargarDatos(sexo, categoriaProfesional, colectivo, grupoCotizacion, nivelEstudios, discapacidad) {

    if(sexo == "hombre"){

        $("#sexoHombre").attr('checked', true);


    } else {

        $("#sexoMujer").attr('checked', true);

    }

    if(discapacidad == "Si"){

        $("#discapacidadSi").attr('checked', true);


    } else {

        $("#discapacidadNo").attr('checked', true);

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