<?php echo form_open("departamentos/insertar", "id=\"". ((isset($cNombreModal)) ? "forma-".$cNombreModal : "forma") ."\" class=\"form-horizontal\""); ?>
<div id="<?php echo (isset($cNombreModal)) ? "my-modal-" . $cNombreModal : "my-modal";  ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="<?php echo (isset($cNombreModal)) ? "my-modal-" . $cNombreModal : "my-modal";  ?>Label" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="<?php echo (isset($cNombreModal)) ? "my-modal-" . $cNombreModal : "my-modal";  ?>Label"><?php echo lang("modales_agregar_departamentos");  ?></h3>
    </div>
    
    <div class="modal-body">
        <div class="row-fluid" >
            <div class="span12">
                
                <div class="control-group">
                    <label class="control-label" for="cCodigoDepartamento"><span class="requerido">*</span> <?php echo lang("departamentos_codigo"); ?> :</label>
                    <div class="controls">
                        <?php echo form_input("cCodigo", "", "id=\"cCodigoDepartamento\"class=\"input-small convertir-mayusculas\" maxlength=\"45\""); ?>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="cNombreDepartamento"><span class="requerido">*</span> <?php echo lang("departamentos_nombre"); ?> :</label>
                    <div class="controls">
                        <?php echo form_input("cNombre", "", "id=\"cNombreDepartamento\"class=\"input-xlarge\" maxlength=\"45\" "); ?> 
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="<?php echo (isset($cNombreModal)) ? "idButtonCatalogoCancel" . $cNombreModal : "idButtonCatalogoCancel";  ?>"><?php echo lang("general_accion_cancelar");  ?></button>
        <button type="button" class="btn btn-primary" id="<?php echo (isset($cNombreModal)) ? "idButtonCatalogoAceptar" . $cNombreModal : "idButtonCatalogoAceptar";  ?>"><?php echo lang("general_accion_aceptar");  ?></button>
    </div>
    
</div>
<?php echo form_close(); ?>