$(document).ready(function() {
    $(document).on('click','input[name="cursos"]',function(){
        $('#cursosSections input[type="text"],#cursosSections input[type="hidden"],#cursosSections select,#cursosSections textarea').attr('disabled',true);
        $(this).parents('.cursoSection').find('input[type="text"],input[type="hidden"],select,textarea').removeAttr('disabled');
    });
});