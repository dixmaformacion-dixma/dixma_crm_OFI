
$(document).ready(function() {

    const sectores = [
        "FARMACIA, CLINICA DENTAL Y SANIDAD",
        "PESCA, MARITIMO Y ACTIVIDADES PORTUARIAS",
        "ELECTRICIDAD, AGUA Y ENERGÍA",
        "EDUCACIÓN Y ACTIVIDADES DEPORTIVAS",
        "CARPINTERIA METALICA",
        "MADERA Y MUEBLE",
        "GASOLINERAS Y DISTRIBUIDORES",
        "CANTERÍA E INDUSTRIAS EXTRACTIVAS",
        "MAYORISTAS Y GRANDES ALMACENES",
        "COMERCIO AL POR MENOR",
        "CONSTRUCCIÓN Y REFORMAS",
        "Inmobiliaria",
        "Limpieza",
        "Administración y gestión",
        "Agricultura y ganadería",
        "Industria alimentaria",
        "Finanzas y seguros",
        "Hostelería y turismo",
        "Servicios medioambientales",
        "Metal",
        "Industria química y vidrio",
        "Otros servicios",
        "Servicios a las empresas",
        "Telecomunicaciones",
        "Textil, confección y piel",
        "Transporte y logística",
        "Empresas colaboradoras",
        "Fabricacion de componentes y maquinaria",
        "Estetica y peluqueria",
        "Marketing, comunicacion y artes graficas",
        "Taller automovil",

    ].sort();

    for(var i=0; i < sectores.length; i++){
   
        $('#sectores').append("<option value='" + sectores[i].toUpperCase() + "'>" + sectores[i].toUpperCase() + "</option>");

    }

});
