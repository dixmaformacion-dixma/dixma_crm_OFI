<div class="mb-5 courseWrapper">
        <div class="col-md-12 col-12 container border border-2" style="background-color:#88c743">
        <div class='row p-0' style="display: flex; flex-wrap: nowrap; align-items: center;">
                <div style="width:4%; flex-shrink: 0;"><b>
                    <input type="checkbox" class="selectable" value="all">
                    #
                </b></div>
                <div style="width:18%;"><b>Nombre</b></div>
                <div style="width:9%;"><b>Fecha_Inicio</b></div>
                <div style="width:9%;"><b>Fecha_Fin</b></div>
                <div style="width:18%;"><b>Denominacion</b></div>
                <div style="width:9%;"><b>Horas</b></div>
                <div style="width:14%;"><b>Empresa</b></div>
                <div style="width:10%; flex-shrink: 0;"><b>Acciones</b></div>
        </div>
        </div>
        <?php
        $numr = 1;
        foreach($cursos as $curso){
                require("template-parts/components/curso.(cursolist.cursosPendientes).php");
                $numr++;
        }
        ?>
</div>
<style>
        .courseWrapper .container:nth-of-type(even){
        background-color: #e7e9e8;
        }
</style>