
$(document).ready(function() {

    $("#selectTipoCurso").change(function() {

        var tipoCurso = $("#selectTipoCurso").val();

        $.ajax({
            type: "GET",
            url: "funciones/ajax.php?action=getCursosList",
            data: "",
            dataType: "json",
            success: function (cursos) {
                
                $("#selectCurso").empty();
                cursos.map(curso=>{
                    if(tipoCurso.toUpperCase() == curso.tipoCurso.toUpperCase()){
                        $("#selectCurso").append("<option>" + curso.nombreCurso + "</option>");
                    }
                });

            }, error: function() {
                console.log('error');
            }
        });

    })


});

