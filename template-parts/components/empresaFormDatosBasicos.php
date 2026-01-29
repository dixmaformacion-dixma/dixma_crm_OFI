<?php 
    if(empty($empresa)){
        $empresa = [
            'nombre'=>!is_numeric($_GET['nombreEmpresa'])?$_GET['nombreEmpresa']:'',
            'cif'=>'',
            'numeroempleados'=>'',
            'credito'=>'',
            'creditoAnhoAnterior'=>'',
            'creditoCaducar'=>'',
            'creditoGuardado'=>'',
            'pdte_bonificar'=>'',
            'calle'=>'',
            'provincia'=>'',
            'poblacion'=>'',
            'cp'=>'',
            'telef1'=>'',
            'telef2'=>'',
            'telef3'=>'',
            'email'=>'',
            'email2'=>'',
            'horario'=>'',
            'personacontacto'=>'',
            'cargo'=>'',
            'referencia'=>'',
            'sector'=>'',
            'codigo'=>'',
            'observacionesempresa'=>'',
        ];
    }
?>
<div class="row">
    <div class="col-md-8 col-12">
        <label><b>Nombre empresa:</b></label>
        <input class="form-control text-uppercase" name="nombreEmpresa" value="<?php echo $empresa['nombre'] ?>" required></input>
    </div>

    <div class="col-md-2 col-12">
        <label><b>CIF:</b></label>
        <input class="form-control" name="CIF" value="<?php echo $empresa['cif'] ?>"></input>
    </div>
    <div class="col-md-2 col-12">        
        <label><b>Nº Empleados:</b></label>
        <input class="form-control" name="nEmpleados" value="<?php echo $empresa['numeroempleados'] ?>"></input>
    </div>
</div>

<div class="row">
    <div class="col-md-5 col-12">
        <label><b>Credito vigente:</b></label>
        <input class="form-control" name="creditoVigente" type="text" value="<?php echo $empresa['credito'] ?>"></input>
    </div>

    <div class="col-md-3 col-12">
        <label><b>Credito año anterior:</b></label>
        <input class="form-control" name="creditoAnhoAnterior" type="text" value="<?php echo $empresa['creditoAnhoAnterior'] ?>"></input>
    </div>

    <div class="col-md-3 col-12">        
        <label><b>Importe crédito hace dos años :</b></label>
        <input class="form-control" name="creditoCaducar" type="text" value="<?php echo $empresa['creditoCaducar'] ?>"></input>
    </div>

    <div class="col-md-1 col-12">
        <label title="Credito Guardado"><b>Credito G.:</b></label>
        <select name="creditoGuardado" id="" class="form-control" title="Credito Guardado">
            <option value=""> --- </option>
            <option value="NO" <?php echo $empresa['creditoGuardado']=='NO'?'selected="true"':'' ?>>NO</option>
            <option value="SI" <?php echo $empresa['creditoGuardado']=='SI'?'selected="true"':'' ?>>SI</option>
        </select>
        <input type="hidden" name="guardaCredito" value="">
        <!--<label>Si:</label>
        <input type="radio" id="guardaCredito" name="guardaCredito" checked>

        <label>No:</label>
        <input type="radio" id="guardaCreditoNo" name="guardaCredito" value="No">

        <input class="form-control" id="cajaGuardaCredito" value="<?php echo $empresa['creditoGuardado'] ?>" name="creditoGuardado" type="text"></input>-->

    </div>
</div>

<div class="row">
    <div class="col-md-5 col-12">
        <label><b>Calle:</b></label>
        <input class="form-control text-uppercase" name="calle" value="<?php echo $empresa['calle'] ?>" required></input>
    </div>

    <div class="col-md-2 col-12">
        <label><b>Provincia:</b></label> <br>
            <select class="form-select" name="provincia" id="selectProvincia" required>
                <option hidden="true" selected> <?php echo $empresa['provincia'] ?> </option>
                <option value="Pontevedra">Pontevedra</option>
                <option value="Orense">Orense</option>
                <option value="Lugo">Lugo</option>
                <option value="Coruña">Coruña</option>
            </select>
    </div>

    <div class="col-md-3 col-12">
        <label><b>Poblacion:</b></label> <br>
            <select class="form-select" name="poblacion" id="selectPoblacion" required>
                <option hidden="true" selected> <?php echo $empresa['poblacion'] ?> </option>
            </select>
    </div>

    <div class="col-md-2 col-12">
        <label><b>Codigo postal:</b></label>
        <input class="form-control" name="codigoPostal" value="<?php echo $empresa['cp'] ?>" required></input>
    </div>
</div>

<div class="row">
    <div class="col-md-4 col-12">
        <label><b>Telefono:</b></label>
        <input class="form-control" name="telefono" value="<?php echo $empresa['telef1'] ?>" required></input>
    </div>

    <div class="col-md-4 col-12">
        <label><b>Telefono 2:</b></label>
        <input class="form-control" name="telefono2" value="<?php echo $empresa['telef2'] ?>"></input>
    </div>

    <div class="col-md-4 col-12">
        <label><b>Telefono 3:</b></label>
        <input class="form-control" name="telefono3" value="<?php echo $empresa['telef3'] ?>"></input>
    </div>

</div>

<div class="row">
    <div class="col-md-4 col-12">
        <label><b>Email:</b></label>
        <input class="form-control" name="email" value="<?php echo $empresa['email'] ?>"></input>
    </div>

    <div class="col-md-4 col-12">
        <label><b>Email 2:</b></label>
        <input class="form-control" name="email2" value="<?php echo $empresa['email2'] ?>"></input>
    </div>

    <div class="col-md-4 col-12">
        <label><b>Horario:</b></label>
        <input class="form-control" name="horario" value="<?php echo $empresa['horario'] ?>"></input>
    </div>
</div>

<div class="row">
    <div class="col-md-6 col-12">
        <label><b>Persona de contacto:</b></label>
        <input class="form-control" name="personaContacto" value="<?php echo $empresa['personacontacto'] ?>"></input>
    </div>

    <div class="col-md-3 col-12">
        <label><b>Cargo persona de contacto:</b></label>
        <input class="form-control" name="cargoPersonaContacto" value="<?php echo $empresa['cargo'] ?>"></input>
    </div>

    <div class="col-md-3 col-12">
        <label><b>Referencia:</b></label>
        <input class="form-control" name="referencia" value="<?php echo $empresa['referencia'] ?>" list="referencias"></input>
        <datalist id="referencias">
            <?php foreach(getReferencias() as $ref): ?>
                <option value="<?php echo $ref ?>" />
            <?php endforeach ?>
        </datalist>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-12">
        <label><b>Sector:</b></label>
        <select class="form-select sectores" name="sector[]" id="sectores">
            <option hidden="true" selected> <?php echo @$empresa['sector'][0] ?> </option>
        </select>
        <select class="form-select sectores" name="sector[]" id="sectores">
            <option hidden="true" selected> <?php echo @$empresa['sector'][1] ?> </option>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-12">
        <label for=""><b>CLIENTE EN AÑO</b></label>
        <input type="text" class="form-control" readonly value="<?php echo isset($_GET['idEmpresa'])?implode(', ',getAnnosEmpresaCliente($_GET['idEmpresa'])):'' ?>">
    </div>
</div>

<div class="row d-none">
    <div class="col-md-12 col-12">
        <label><b>CODIGO:</b></label>
        <input id="codigo" class="form-control" name="codigo" value="<?php echo $empresa['codigo']?>"></input>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-12">
        <label><b>PENDIENTE DE BONIFICAR:</b></label>
        <input id="pdte_bonificar" class="form-control" name="pdte_bonificar" value="<?php echo $empresa['pdte_bonificar']?>"></input>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-12">
        <label><b>Observaciones:</b></label>
        <textarea class="form-control" name="observacionesEmpresa" rows="10"><?php echo $empresa['observacionesempresa']?></textarea>
    </div>
</div>