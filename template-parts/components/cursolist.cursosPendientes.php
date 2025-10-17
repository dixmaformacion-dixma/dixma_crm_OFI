<div class="mb-5 courseWrapper">
        <div class="col-md-12 col-12 container border border-2" style="background-color:#88c743">
        <div class='row p-0'>
                <div class='col-md-2 border-right'><b>Nombre</b></div>
                <div style="width:10%"><b>Fecha_Inicio</b></div>
                <div style="width:10%"><b>Fecha_Fin</b></div>
                <div class='col-md-2 border-right'><b>Denominacion</b></div>
                <div class='col-md-2 border-right'><b>Horas</b></div>
                <div class='col-md-2 border-right'><b>Empresa</b></div>
                <div class="col-md-1"></div>
        </div>
        </div>
        <?php
        foreach($cursos as $curso){
                require("template-parts/components/curso.(cursolist.cursosPendientes).php");
        }
        ?>
</div>
<style>
        .courseWrapper .container:nth-of-type(even){
        background-color: #e7e9e8;
        }
</style>