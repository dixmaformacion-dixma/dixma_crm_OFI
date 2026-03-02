<?php
// Carica prioridad dalla llamada esistente
$prioridadActual = null; // Default: nessuna priorità
if(isset($_GET['idLlamada']) && !empty($_GET['idLlamada'])){
    $conexion = realizarConexion();
    $sql = "SELECT prioridad FROM llamadas WHERE idllamada = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$_GET['idLlamada']]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($resultado && !empty($resultado['prioridad'])){
        $prioridadActual = $resultado['prioridad'];
    }
}
?>
<div class='col-md-5 col-12'>
    <label><b>Pedir cita: </b></label>
    <input type='radio' class='form-check-input ms-md-1' name='estadoLlamada' value='pasarCita' id='pasarCita'>
    <select class='form-select' name='pedirCita' id='selectPedirCita' disabled>
        <?php for($i=0; $i < count($comerciales); $i++): ?>
            <?php if($comerciales[$i]['codigousuario'][0] == "2"): ?>
                <option value='<?php echo $comerciales[$i]['codigousuario'] ?>'><?php echo $comerciales[$i]['nombre'] ?></option>
            <?php endif ?>
        <?php endfor ?>
    </select>
    <input type="number" class="form-control" name="anoPedirCita" id="anoPedirCita" value="<?php echo Date("Y") ?>" disabled></input>
    <select name="mesPedirCita" id="mesPedirCita" class="form-control" disabled>
        <option value="1"> enero </option>
        <option value="2"> febrero </option>
        <option value="3"> marzo </option>
        <option value="4"> abril </option>
        <option value="5"> mayo </option>
        <option value="6"> junio </option>
        <option value="7"> julio </option>
        <option value="8"> agosto </option>
        <option value="9"> septiembre </option>
        <option value="10"> octubre </option>
        <option value="11"> noviembre </option>
        <option value="12"> diciembre </option>
    </select>
    <select name="prioridadCita" id="prioridadCita" class="form-control" disabled>
        <option value="" <?php echo $prioridadActual === null ? 'selected' : ''; ?>>-- Prioridad no encontrada --</option>
        <option value="BAJO" <?php echo $prioridadActual == 'BAJO' ? 'selected' : ''; ?>> BAJO </option>
        <option value="MEDIO" <?php echo $prioridadActual == 'MEDIO' ? 'selected' : ''; ?>> MEDIO </option>
        <option value="ALTO" <?php echo $prioridadActual == 'ALTO' ? 'selected' : ''; ?>> ALTO </option>
    </select>
</div>