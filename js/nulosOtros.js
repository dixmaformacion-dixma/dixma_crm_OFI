$(document).ready(function() {

    $("#nulos").change(function() {

        var nulos = $("#nulos").val();

            if(nulos == "otros"){

                $("#areaNulos").removeAttr("hidden");

            } else {

                $("#areaNulos").attr('hidden', 'true');

            }

    });

    $("#nuevaLlamada").click(function() {

        $("#formNuevaLlamada").removeAttr("hidden");
        $('#interlocutorLlamada').attr('required', "");
        $('#cita').attr('required', "");
        $('#llamadaPendiente').attr('required', "");


    });

    $("#nuevaLlamadaNo").click(function() {

        $("#formNuevaLlamada").attr("hidden", "true");
        $('#interlocutorLlamada').removeAttr('required', "");
        $('#cita').removeAttr('required', "");
        $('#llamadaPendiente').removeAttr('required', "");

    });

    $("#nuevoCurso").click(function() {

        $("#formNuevoCurso").removeAttr("hidden");

    });

    $("#nuevoCursoNo").click(function() {

        $("#formNuevoCurso").attr("hidden", "true");

    });

    $("#guardaCredito").click(function() {

        $("#cajaGuardaCredito").removeAttr("disabled", "");


    });

    $("#guardaCreditoNo").click(function() {

        $("#cajaGuardaCredito").attr("disabled", "");


    });

    $("#cita").click(function() {

        $('#fechaCita').removeAttr("disabled", "");
        $('#horaCita').removeAttr("disabled", "");
        $('#fechaLlamada').attr("disabled", "");
        $('#horaLlamada').attr("disabled", "");
        $('#nulos').attr("disabled", "");
        $('#selectPedirCita').attr("disabled", "");
        $('#anoPedirCita').attr("disabled", "");
        $('#mesPedirCita').attr("disabled", "");
        $('#selectHacerSeguimiento,input[name="fecha_seguimiento"],input[name="tipo_seguimiento"]').attr("disabled", "");

    });

    $("#pendiente").click(function() {

        $('#fechaLlamada').removeAttr("disabled", "");
        $('#horaLlamada').removeAttr("disabled", "");
        $('#fechaCita').attr("disabled", "");
        $('#horaCita').attr("disabled", "");
        $('#nulos').attr("disabled", "");
        $('#selectPedirCita').attr("disabled", "");
        $('#anoPedirCita').attr("disabled", "");
        $('#mesPedirCita').attr("disabled", "");
        $('#selectHacerSeguimiento,input[name="fecha_seguimiento"],input[name="tipo_seguimiento"]').attr("disabled", "");

    });

    $("#formNulos").click(function() {

        $('#nulos').removeAttr("disabled", "");
        $('#fechaLlamada').attr("disabled", "");
        $('#horaLlamada').attr("disabled", "");
        $('#fechaCita').attr("disabled", "");
        $('#horaCita').attr("disabled", "");
        $('#selectPedirCita').attr("disabled", "");
        $('#anoPedirCita').attr("disabled", "");
        $('#mesPedirCita').attr("disabled", "");
        $('#selectHacerSeguimiento,input[name="fecha_seguimiento"],input[name="tipo_seguimiento"]').attr("disabled", "");

    });

    $("#nuevaVentaSi").click(function() {

        $("#formNuevaVenta").removeAttr("hidden");

    });

    $("#nuevaVentaNo").click(function() {

        $("#formNuevaVenta").attr("hidden", "true");

    });

    $("#tipoUsuario").change(function() {

        var tipoUsuario = $('#tipoUsuario').val();
        var codigo = "";

            if(tipoUsuario == "admin"){

                codigo = $('#admin').val();
                $('#codigoUsuario').val(0 + codigo);

            }

            if(tipoUsuario == "comercial"){

                codigo = $('#comercial').val();
                $('#codigoUsuario').val(codigo);

            }

            if(tipoUsuario == "callcenter"){

                codigo = $('#callcenter').val();
                $('#codigoUsuario').val(codigo);

            }

    });

    $("#pasarCita").click(function() {

        $('#selectPedirCita').removeAttr("disabled");
        $('#anoPedirCita').removeAttr("disabled");
        $('#mesPedirCita').removeAttr("disabled");
        $('#selectPedirCita').attr("required", "");
        $('#selectHacerSeguimiento,input[name="fecha_seguimiento"],input[name="tipo_seguimiento"]').attr("disabled", "");
        $('#fechaLlamada').attr("disabled", "");
        $('#horaLlamada').attr("disabled", "");
        $('#fechaCita').attr("disabled", "");
        $('#horaCita').attr("disabled", "");
        $('#nulos').attr("disabled", "");

    });

    $("#hacerSeguiminento").click(function() {

        $('#selectHacerSeguimiento,input[name="fecha_seguimiento"],input[name="tipo_seguimiento"]').removeAttr("disabled");
        $('#selectHacerSeguimiento,input[name="fecha_seguimiento"],input[name="tipo_seguimiento"]').attr("required", "");
        $('#selectPedirCita').attr("disabled", "");
        $('#anoPedirCita').attr("disabled", "");
        $('#mesPedirCita').attr("disabled", "");
        $('#fechaLlamada').attr("disabled", "");
        $('#horaLlamada').attr("disabled", "");
        $('#fechaCita').attr("disabled", "");
        $('#horaCita').attr("disabled", "");
        $('#nulos').attr("disabled", "");

    });


});