var am_visibleIds = [];

document.addEventListener('DOMContentLoaded', function() {

    var modal = document.getElementById('accionesMasivasModal');
    if (!modal) return;

    modal.addEventListener('show.bs.modal', function(event) {
        var btn = event.relatedTarget;
        document.getElementById('am_naccion').textContent = btn.getAttribute('data-naccion');
        document.getElementById('am_ngrupo').textContent  = btn.getAttribute('data-ngrupo');
        document.getElementById('am_feedback').classList.add('d-none');
        document.getElementById('am_diploma_status').value = 'no_change';
        document.getElementById('am_status_curso').value   = 'no_change';

        // Raccoglie solo i StudentCursoID dei checkbox spuntati (escluso 'all')
        am_visibleIds = Array.from(document.querySelectorAll('input.selectable:checked'))
            .map(function(cb){ return cb.value; })
            .filter(function(v){ return v !== 'all' && !isNaN(parseInt(v)); })
            .map(function(v){ return parseInt(v); });

        document.getElementById('am_countLabel').textContent = am_visibleIds.length;
    });

    document.getElementById('am_submitBtn').addEventListener('click', function() {
        var diploma_status = document.getElementById('am_diploma_status').value;
        var status_curso   = document.getElementById('am_status_curso').value;
        var feedback       = document.getElementById('am_feedback');

        feedback.classList.remove('d-none', 'alert-success', 'alert-danger', 'alert-warning');

        if (diploma_status === 'no_change' && status_curso === 'no_change') {
            feedback.classList.add('alert-warning');
            feedback.textContent = 'Por favor selecciona al menos un campo a modificar.';
            return;
        }

        if (am_visibleIds.length === 0) {
            feedback.classList.add('alert-warning');
            feedback.textContent = 'No hay registros seleccionados. Marca al menos un alumno con el checkbox antes de aplicar.';
            return;
        }

        var formData = new FormData();
        formData.append('accionesMasivasSubmit', '1');
        formData.append('diploma_status', diploma_status);
        formData.append('status_curso',   status_curso);
        formData.append('student_ids',    JSON.stringify(am_visibleIds));

        document.getElementById('am_submitBtn').disabled = true;

        fetch('funciones/funcionesAccionesMasivas.php', {
            method: 'POST',
            body: formData
        })
        .then(function(r){ return r.json(); })
        .then(function(data){
            if (data.ok) {
                feedback.classList.add('alert-success');
                feedback.textContent = 'Actualización correcta: ' + data.affected + ' de ' + am_visibleIds.length + ' registro(s) seleccionado(s) modificado(s). Recarga la página para ver los cambios.';
            } else {
                feedback.classList.add('alert-danger');
                feedback.textContent = 'Error: ' + (data.msg || 'desconocido');
            }
        })
        .catch(function(){
            feedback.classList.add('alert-danger');
            feedback.textContent = 'Error de comunicación con el servidor.';
        })
        .finally(function(){
            document.getElementById('am_submitBtn').disabled = false;
        });
    });

});
