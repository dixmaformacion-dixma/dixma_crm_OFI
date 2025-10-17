<div class="mb-5 courseWrapper">
        <div class="col-md-12 col-12 container border border-2" style="background-color:#88c743">
        <div class='row p-0'>
				<div style="width:5%"><b>
                    <input type="checkbox" class="selectable" value="all">
                    #
                </b></div>
                <div class='col-md-2 border-right'><b>Nombre</b></div>
                <div style="width:9%"><b>Fecha_Inicio</b></div>
                <div style="width:9%"><b>Fecha_Fin</b></div>
                <div class='col-md-2 border-right'><b>Denominacion</b></div>
                <div style="width:3%"><b>A/G</b></div>
				<div style="width:3%"><b>RM</b></div>
                <div style="width:3%"><b>CC</b></div>
                <div class='col-md-1 border-right'><b>Empresa</b></div>
                <div class='col-md-1 border-right'><b>Diploma</b></div>
                <div class="col-md-1"></div>
        </div>
        </div>
        <?php
		$numr = 1;
        foreach($cursos as $curso){
        	require("template-parts/components/curso.(cursolist.listadoCursos).php");
			$numr++;
        }
        ?>
</div>
<style>
        .courseWrapper .container:nth-of-type(even){
        background-color: #e7e9e8;
        }
</style>
