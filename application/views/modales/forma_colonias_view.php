<?php echo form_open("colonias/insertar", "id=\"". ((isset($cNombreModal)) ? "forma-colonias-".$cNombreModal : "forma-colonias") ."\" class=\"form-horizontal\""); ?>
<input id="<?php  echo (isset($cNombreModal) ? "idHiddenCiudad" . $cNombreModal : "idHiddenCiudad") ?>" type="hidden" name="idCiudad" value="" />
<div id="<?php echo (isset($cNombreModal)) ? "my-modal-colonias-" . $cNombreModal : "my-modal-colonias";  ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="<?php echo (isset($cNombreModal)) ? "my-modal-colonias-" . $cNombreModal : "my-modal-colonias";  ?>Label" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="<?php echo (isset($cNombreModal)) ? "my-modal-colonias-" . $cNombreModal : "my-modal-colonias";  ?>Label"><?php echo lang("empresas_modal_titulo_colonia");  ?></h3>
    </div>
    
    <div class="modal-body">
        <div class="row-fluid" >
            <div class="span12">
               
                <div class="control-group">
                    <label class="control-label" for="cNombreColonia"><span class="requerido">*</span> <?php echo lang("colonias_nombre"); ?> :</label>
                    <div class="controls">
                        <?php echo form_input("cNombre", "", "id=\"cNombreColonia\"class=\"input-xlarge\" maxlength=\"45\" "); ?> 
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="cDescripcionColonia"> <?php echo lang("colonias_descripcion"); ?> :</label>
                    <div class="controls">
                        <?php echo form_textarea("cDescripcion", "", "id=\"cDescripcionColonia\"class=\"input-xlarge\" maxlength=\"255\"  rows=5"); ?>                        
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