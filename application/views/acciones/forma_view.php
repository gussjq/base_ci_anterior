<!-- 

Vista de forma del catalogo acciones, se muestra un formulario con los campos
necesarios para agregar una nueva accion o bien editar una accion existente
de un modulo determinado.

@author DEVELOPER 1 <correo@developer1> cel <1111111111>
@creado 30-12-2014

-->

<!-- INICIA ICONO TOOLBAR PARA LAS ACCIONES SOLO CUANDO ES RESPONSIVE DEBE DE INCLUIRSE EN TODOS LOS CATALOGOS -->
<a class="btn btn-subhead" data-toggle="collapse" data-target=".subhead-collapse">
    <?php echo lang("general_toolbar"); ?>		
    <i class="icon-wrench"></i>
</a>
<!-- FIN ICONO TOOLBAR PARA LAS ACCIONES SOLO CUANDO ES RESPONSIVE  -->

<!-- INICIA BARRA DE ACCIONES DEBE DE INCLUIRSE EN TODOS LOS CATALGOS -->
<div class="subhead-collapse collapse">
    <div class="subhead">
        <div class="container-fluid">
            <div id="container-collapse" class="container-collapse"></div>
            <div class="row-fluid">
                <div class="span12">
                    <div class="btn-toolbar" id="toolbar">
                        <!-- INICIA PARTE IZQUIERDA  -->
                        <div class="btn-wrapper" id="toolbar-new">
                            <button onclick="accion.btnGuardar(<?php echo $oAccion->idModulo; ?>);" class="btn btn-small btn-success">
                                <span class="icon-new icon-white"></span>
                                <?php echo lang('general_accion_guardar'); ?>
                            </button>
                        </div>

                        <div class="btn-wrapper" id="toolbar-cancel">
                            <button onclick="accion.btnCancelar(<?php echo $oAccion->idModulo; ?>);" class="btn btn-small">
                                <span class="icon-cancel"></span>
                                <?php echo lang('general_accion_cancelar'); ?>
                            </button>
                        </div>
          
                        <!-- FIN PARTE IZQUIERDA  -->

                        <!-- INICIA PARTE DERECHA  -->
                        <div class="btn-wrapper" id="toolbar-help">
                            <?php if($this->seguridad->verificarAcceso("empresas", "cambiarEmpresaAjax")): ?>
                            <button onclick="accion.btnMostrarEmpresas();" rel="help" class="btn btn-small">
                                <span class="icon-vcard"></span>
                                <?php echo lang("dashboard_empresas"); ?>
                            </button>
                            <?php endif; ?>
                        </div>
                        <!-- FIN PARTE DERECHA  -->
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- FIN BARRA DE ACCIONES  -->

<!-- INICIA CONTENIDO DEL CATALOGO DEBE INCLUIRSE EN TODOS LOS CATALOGOS -->
<div class="container-fluid container-main">
    <section id="content">
        <div class="row-fluid">
            <div id="j-sidebar-container" class="span2">
                <div id="sidebar">
                    <div class="sidebar-nav">
                        <ul id="submenu" class="nav nav-list">
                            <?php echo pintarMenuCatalogo($aMenuCatalogo); ?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="span10">
                <!-- INICIO AREA DEL FORMULARIO  -->
                <?php echo form_open("acciones/insertar", "id=\"forma-acciones\" class=\"form-horizontal\""); ?>
                <input type="hidden" id="id" name="idAccion" value="<?php echo $oAccion->idAccion; ?>" />
                <input type="hidden" id="idModulo" name="idModulo" value="<?php echo $oAccion->idModulo; ?>" />

                <fieldset>
                    <legend><?php echo lang("general_datos_generales"); ?></legend>
                    <div class="control-group">
                    <label class="control-label" for="cNombre"><?php echo lang("acciones_nombre"); ?> :</label>
                    <div class="controls">
                        <?php echo form_input("cNombre", $oAccion->cNombre, "id=\"cNombre\"class=\"input-large\" maxlength=\"45\""); ?>
                        <span class="help-block"><?php echo lang("acciones_nombre_help"); ?></span>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="cAlias"><?php echo lang("acciones_alias"); ?> :</label>
                    <div class="controls">
                        <?php echo form_input("cAlias", $oAccion->cAlias, "id=\"cAlias\"class=\"input-large\" maxlength=\"45\""); ?>
                        <span class="help-block"><?php echo lang("acciones_alias_help"); ?></span>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="cDescripcion"><?php echo lang("acciones_descripcion"); ?> :</label>
                    <div class="controls">
                        <textarea maxlength="255" cols="8" rows="5" id="cDescripcion" name="cDescripcion" class="validate[required, maxSize[250]]"><?php echo $oAccion->cDescripcion; ?></textarea>
                        <span class="help-block"><?php echo lang("acciones_nombre_help"); ?></span>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="idTipoAccion"><?php echo lang("acciones_tipo"); ?> :</label>
                    <div class="controls">
                        <?php echo form_dropdown("idTipoAccion", $aCombosForma["TipoAccion"], $oAccion->idTipoAccion, "id=\"idTipoAccion\"") ?>
                        <span class="help-block"><?php echo lang("acciones_tipo_help"); ?></span>
                    </div>
                </div>

                </fieldset>
                <?php echo form_close(); ?>
                <!-- FIN AREA DEL FORMULARIO  -->
            </div>
        </div>  
    </section>
</div>
<!-- FIN CONTENIDO DEL CATALOGO  -->


<!-- INICIA AREA JAVASCRIPT -->
<?php $this->load->view("layout/javascript_base_view"); ?>
<?php incluye_componente_validate(); ?>
<?php incluye_componente_uploadify(); ?>
<?php script_tag("plugins/ajaxForm/jquery.ajaxForm"); ?>
<?php script_tag("modulos/acciones"); ?>

<script type="text/javascript">

    $(document).ready(function() {
        accion.initForma({
            'paramsForma': {
               rules:{
                   cNombre:{
                       required:true,
                       lettersonly:true
                   },
                   cAlias:{
                       required:true,
                   },
                   cDescripcion:{
                       required:true,
                   },
                   idTipoAccion:{
                       required:true,
                   }
               }
            }
        });
    });
</script>
<!-- FIN AREA JAVASCRIPT -->