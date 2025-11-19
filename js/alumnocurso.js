function changeSeguimentoDates(id){
    SEGMENT_COUNT = 6;

    startElement = $(`#${id} > .cursos .Fecha_Inicio`).get(0);
    endElement = $(`#${id} > .cursos .Fecha_Fin`).get(0);
    startDate = startElement.value;
    endDate = endElement.value;

    if(startDate == '' || endDate == ''){
        startElement.style.backgroundColor = "red";
        endElement.style.backgroundColor = "red";
        return;
    }

    startDate = new Date(startDate);
    endDate = new Date(endDate);

    if(startDate >= endDate){
        startElement.style.backgroundColor = "red";
        endElement.style.backgroundColor = "red";
        return;
    }

    //calculate day distance
    timeBetween = endDate - startDate;
    interval = timeBetween/(SEGMENT_COUNT - 1);

    for(i = 0; i < SEGMENT_COUNT; i++){
        
        let date = new Date(new Date(startDate).getTime() + i * interval);

        if(date.getDay() == 6){
            date = new Date(new Date(date).getTime() + 2 * 86400000);
            $(`#${id} > .cursos .seguimento${i}`).get(0).style.backgroundColor = "lightblue";
        }else if(date.getDay() == 0){
            date = new Date(new Date(date).getTime() + 86400000);
            $(`#${id} > .cursos .seguimento${i}`).get(0).style.backgroundColor = "lightblue";
        }

        $(`#${id} > .cursos .seguimento${i}`).get(0).value = date.toISOString().split('T')[0];
    }

    //reset error colors if any exist
    startElement.style.backgroundColor = "white";
    endElement.style.backgroundColor = "white";
}

function changeSeguimentoDatesMultiple(id){
    const SEGMENT_COUNT = 6;

    let startElement = $(`#${id} .Fecha_Inicio`).get(0);
    let endElement = $(`#${id} .Fecha_Fin`).get(0);
    let startDate = startElement.value;
    let endDate = endElement.value;

    if(startDate == '' || endDate == ''){
        startElement.style.backgroundColor = "red";
        endElement.style.backgroundColor = "red";
        return;
    }

    startDate = new Date(startDate);
    endDate = new Date(endDate);

    if(startDate >= endDate){
        startElement.style.backgroundColor = "red";
        endElement.style.backgroundColor = "red";
        return;
    }

    //calculate day distance
    let timeBetween = endDate - startDate;
    let interval = timeBetween/(SEGMENT_COUNT - 1);

    for(let i = 0; i < SEGMENT_COUNT; i++){
        let date = new Date(new Date(startDate).getTime() + i * interval);
        $(`#${id} .seguimento${i}`).get(0).value = date.toISOString().split('T')[0];
    }

    //reset error colors if any exist
    startElement.style.backgroundColor = "white";
    endElement.style.backgroundColor = "white";
}

function changeCourseSelectionMode(id){
    let checked = $(`#${id} input[name="selectFromCourseList"]`).get(0).checked;
    
    let denominacionElement = $(`#${id} input[name="Denominacion"]`).get(0);
    let horasElement = $(`#${id} input[name="N_Horas"]`).get(0);

    if(checked){
        denominacionElement.readOnly = true;
        
        selectedCourseid = $(`#${id} select[name="idCurso"]`).get(0).value;

        denominacionElement.value = CourseArray[selectedCourseid].nombreCurso;
        horasElement.value = CourseArray[selectedCourseid].horasCurso
    }else{
        denominacionElement.readOnly = false;
    }
}
function selectTypeOfCourses(id){
    //hide every course option first
    let allCourses = $(`#${id} .courseOptions`);
    for (let index = 0; index < allCourses.length; index++) {
            allCourses.get(index).style.display = 'none';
    }

    //get selected course id
    let coursetype = $(`#${id} select[name="type"]`).get(0).value;

    //only display course options for chosen type
    let selectedTypeCourses = $(`#${id} .class${coursetype}`);
    for (let index = 0; index < selectedTypeCourses.length; index++) {
            selectedTypeCourses.get(index).style.display = 'block';
    }
}
function selectCourse(id){ //change denominacion field when course is selected from list
    let checked = $(`#${id} input[name="selectFromCourseList"]`).get(0).checked;

    if(checked){
        let denominacionElement = $(`#${id} input[name="Denominacion"]`).get(0);
        let horasElement = $(`#${id} input[name="N_Horas"]`).get(0);
        
        let selectedCourseid = $(`#${id} select[name="idCurso"]`).get(0).value;

        denominacionElement.value = CourseArray[selectedCourseid].nombreCurso
        horasElement.value = CourseArray[selectedCourseid].horasCurso
    }
}
function addFilter(){
    let filterID = Math.floor(Math.random() * 10000000000);
    let filterHTML = `
        <div id="filter${filterID}" class="filters mx-auto col-md-8 p-3 col-12 border border-2">
            <select class="form-control" name="filterName[]" onchange="changeFieldType('filter${filterID}')">
                <option value="Denominacion">Denominacion</option>
                <option value="Anno">Año</option>
                <option value="N_Accion">N_Accion</option>
                <option value="N_Grupo">N_Grupo</option>
                <option value="N_Horas">N_Horas</option>
                <option value="Modalidad">Modalidad</option>
                <option value="DOC_AF">DOC_AF</option>
                <option value="Fecha_Inicio">Fecha_Inicio</option>
                <option value="Fecha_Fin">Fecha_Fin</option>
                <option value="tutor">tutor</option>
                <option value="idEmpresa">idEmpresa</option>
                <option value="Tipo_Venta">Tipo_Venta</option>
                <option value="Diploma_Status">Diploma_Status</option>
                <option value="CC">CC</option>
                <option value="RLT">RLT</option>
                <option value="Recibi_Material">Recibi_Material</option>
                <option value="status_curso">status_curso</option>
                <option value="idEmpresa">Empresa</option>
            </select>
            <div class="valuefield"><input type="text" class="form-control" name="filterValue[]"></div>
            <button type='button' class="btn btn-danger" onclick="RemoveFilter('filter${filterID}')"> REMOVE FILTER </button>
        </div>
    `;
    element = document.createElement('div');
    element.innerHTML = filterHTML;

    document.getElementById("FiltersWrapper").append(element);
    changeFieldType(`filter${filterID}`);
    return filterID;
}
function RemoveFilter(filterID){
    document.getElementById(filterID).outerHTML = '';
}
async function changeFieldType(filterID){
    let criteriaType = {
        Denominacion: ['text'],
        Anno: ['number'],
        N_Accion: ['number'],
        N_Grupo: ['number'],
        N_Horas: ['text'],
        Modalidad: ['choice', ['Teleformación', 'Presencial', 'Mixto']],
        DOC_AF: ['text'],
        Fecha_Inicio: ['dateRange'],
        Fecha_Fin: ['dateRange'],
        tutor: ['text'],
        idEmpresa: ['relation'],
        Tipo_Venta: ['choice', ['Bonificado',  'Privado']],
        Diploma_Status: ['choice', ['No hecho', 'Impreso',  'Entregado', 'Copia recibida']],
        CC: ['choice', ['1', '0']],
        RLT: ['choice', ['1', '0']],
        Recibi_Material: ['choice', ['1', '0']],
        status_curso: ['choice', ["en curso", "finalizado", "descargado", "cerrado", "baja"]],
    }
    const defaultValues = {
        Anno: (new Date()).getFullYear(),
        Tipo_Venta:'Bonificado'
    };
    let criteriaName = $(`#${filterID} select`).get(0).value;
    let criteria = criteriaType[criteriaName];
    let defaultValue = !defaultValues[criteriaName]?'':defaultValues[criteriaName];
    if(criteria[0] == 'choice'){
        let HTML = '<select class="form-control" name="filterValue[]">';
        criteria[1].forEach(choice => {
            HTML += `<option value="${choice}">${choice}</option>`;
        });
        HTML += `</select><input type="hidden" value="=" class="form-control" name="filterOperator[]">`;

        $(`#${filterID} .valuefield`).get(0).innerHTML = HTML;
    }else if(criteria[0] == 'dateRange'){
        
        $(`#${filterID} .valuefield`).get(0).innerHTML = `
            <input type="date" class="form-control" name="filterValue[]">
            <input type="hidden" value=">=" class="form-control" name="filterOperator[]">

            <input type="hidden" value="${criteriaName}" class="form-control" name="filterName[]">
            <input type="date" class="form-control" name="filterValue[]">
            <input type="hidden" value="<=" class="form-control" name="filterOperator[]">
        `;
        
        $(`#${filterID} .valuefield input[type="date"]`).val(hoy||'');
    }else if(criteria[0] == 'relation'){
        const f = await fetch(`/funciones/ajax.php?action=getEmpresasList`);
        const r = await f.json();
        let HTML = '<select class="form-control" name="filterValue[]">';
        r.map(empresa=>{
            HTML += `<option value="${empresa.idempresa}">${empresa.nombre}</option>`;
        })
        HTML += `</select><input type="hidden" value="=" class="form-control" name="filterOperator[]">`;
        
        const v = $(`#${filterID} [name="filterValue[]"]`).val()
        
        $(`#${filterID} .valuefield`).get(0).innerHTML = HTML;
        $(`#${filterID} select[name="filterValue[]"]`).val(!v||v==''?defaultValue:v)
    }else{
        $(`#${filterID} .valuefield`).get(0).innerHTML = `
            <input type="${criteria[0]}" class="form-control" name="filterValue[]" value="${defaultValue}">
            <input type="hidden" value="LIKE" class="form-control" name="filterOperator[]">
            
        `;
    }
}
