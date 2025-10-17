<?php

function crearJSON() {

$cursos = listadoCursos();
$cursos_json = json_encode($cursos);

file_put_contents("dixmaformacion.com/crm.dixmaformacion.com/json/cursos.json", $cursos_json);

}

?>
