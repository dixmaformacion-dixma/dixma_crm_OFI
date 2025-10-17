<div class='container-fluid border rounded mt-3 mb-3 border-5' id='datosEmpresa'>
<div class='border row mt-2 mb-3 mx-2' style='background-color: #e8f0f7;'>
    <h5 class='col-md-4 col-4 my-md-auto'> ID:<?php echo $empresa['idempresa']; ?></h5>
    <h5 class='col-md-8 col-8 my-md-2'><?php echo $empresa['nombre']; ?></h5>
</div>

<div class='row mx-auto my-2'>
    <label class='col-md-4 col-12'> <b>CIF:</b> <?php echo $empresa['cif']; ?></label>
    <label class='col-md-4 col-12'> <b>Crédito:</b> <?php echo $empresa['credito']; ?>€</label>
    <label class='col-md-4 col-12'> <b>Nº Empleados:</b> <?php echo $empresa['numeroempleados']; ?></label>
</div>

<div class='row mx-auto my-2'>
    <label class='col-md-4 col-12'> <b>Calle:</b> <?php echo $empresa['calle']; ?></label>
    <label class='col-md-4 col-12'> <b>Codigo postal:</b> <?php echo $empresa['cp']; ?></label>
    <label class='col-md-4 col-12'> <b>Provincia:</b> <?php echo $empresa['provincia']; ?><b> Poblacion:</b> <?php echo $empresa['poblacion']; ?></label>
</div>

<div class='row mx-auto my-2'>
    <label class='col-md-4 col-12'> <b>Telefono:</b> <?php echo $empresa['telef1']; ?></label>
    <label class='col-md-4 col-12'> <b>Telefono 2:</b> <?php echo $empresa['telef2']; ?></label>
    <label class='col-md-4 col-12'> <b>Telefono 3:</b> <?php echo $empresa['telef3']; ?></label>
</div>

<div class='row mx-auto my-2'>
    <label class='col-md-4 col-12'> <b>Email:</b> <?php echo $empresa['email']; ?></label>
    <label class='col-md-4 col-12'> <b>Persona contacto:</b> <?php echo $empresa['personacontacto']; ?></label>
    <label class='col-md-4 col-12'> <b>Cargo persona contacto:</b> <?php echo $empresa['cargo']; ?></label>
</div>

<div class='row mx-auto my-2'>
    <label class='col-md-12 col-12 mb-3'> <b>Observaciones:</b> <?php echo $empresa['observacionesempresa']; ?></label>
</div>

<div class='row mx-auto'>
    <a type='button' class='btn col-md-5 col-12 mx-auto mb-2' style='background-color: #1e989e;' href="tutoria_NIFcheck.php?idEmpresa=<?php echo $empresa['idempresa']; ?>">INSERTAR ALUMNO <img src='images/iconos/person-add.svg' class='ml-5'> </a>
</div> 
<?php
if($alumnos = buscarAlumnosPorIDEmpresa($empresa['idempresa'])){
?>
<div class="border border-5 container">
    <div class="col-md-12 col-12">
        <h5 class="text-center mt-2 pt-2 pb-2 border border-5 rounded"
        style="background-color: #b0d588;">
        Alumnos (<?php echo count($alumnos) ?>)
        </h5>
    </div>

    <?php
    foreach($alumnos as $alumno){
        require("template-parts/components/alumno.(empresa.buscarEmpresa).php");
    }
    ?>

</div>
<?php
}
?>

</div>