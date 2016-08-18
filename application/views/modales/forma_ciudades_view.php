<?php echo form_open("ciudades/insertar", "id=\"". ((isset($cNombreModal)) ? "forma-ciudades-".$cNombreModal : "forma-ciudades") . "\"class=\"form-horizontal\""); ?>
<input id="<?php echo (isset($cNombreModal) ? "idHiddenEstado" . $cNombreModal : "idHiddenEstado") ?>" type="hidden" name="idEstado" value="" />
<div id="<?php echo (isset($cNombreModal)) ? "my-modal-ciudades-" . $cNombreModal : "my-modal-ciudades";  ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="<?php echo (isset($cNombreModal)) ? "my-modal-ciudades-" . $cNombreModal : "my-modal-ciudades";  ?>Label" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="<?php echo (isset($cNombreModal)) ? "my-modal-ciudades-" . $cNombreModal : "my-modal-ciudades";  ?>Label"><?php echo lang("empresas_modal_titulo_ciudad");  ?></h3>
    </div>
    
    <div class="modal-body">
        <div class="row-fluid" >
            <div class="span12">
               
                <div class="control-group">
                    <label class="control-label" for="cNombreCiudad"><span class="requerido">*</span> <?php echo lang("ciudades_nombre"); ?> :</label>
                    <div class="controls">
                        <?php echo form_input("cNombre", "", "id=\"cNombreColonia\"class=\"input-xlarge\" maxlength=\"45\" "); ?> 
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="cAbreviacionCiudad"> <?php echo lang("ciudades_abreviacion"); ?> :</label>
                    <div class="controls">
                        <?php echo form_input("cAbreviacion", "", "id=\"cAbreviacionCiudad\"class=\"input-xlarge\" maxlength=\"45\""); ?> 
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="<?php echo (isset($cNombreModal)) ? "idButtonCatalogoCiudadCancel" . $cNombreModal : "idButtonCatalogoCiudadCancel";  ?>"><?php echo lang("general_accion_cancelar");  ?></button>
        <button type="button" class="btn btn-primary" id="<?php echo (isset($cNombreModal)) ? "idButtonCatalogoCiudadAceptar" . $cNombreModal : "idButtonCatalogoCiudadAceptar";  ?>"><?php echo lang("general_accion_aceptar");  ?></button>
    </div>
    
</div>
<?php echo form_close(); ?>