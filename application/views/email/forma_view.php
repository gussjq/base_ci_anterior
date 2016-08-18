<!-- 

Vista de forma del catalogo email

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
                            <button onclick="email.btnGuardar();" class="btn btn-small btn-success">
                                <span class="icon-new icon-white"></span>
                                <?php echo lang('general_accion_guardar'); ?>
                            </button>
                        </div>

                        <div class="btn-wrapper" id="toolbar-cancel">
                            <button onclick="email.btnCancelar();" class="btn btn-small">
                                <span class="icon-cancel"></span>
                                <?php echo lang('general_accion_cancelar'); ?>
                            </button>
                        </div>
          
                        <!-- FIN PARTE IZQUIERDA  -->

                        <!-- INICIA PARTE DERECHA  -->
                        <div class="btn-wrapper" id="toolbar-help">
                           
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
                <br />
                <div class="row-fluid" >
                    <div class="span11">
                        <div class="center encabezado-catalogo">
                            <div class="texto-encabezado"><?php echo lang('email_titulo'); ?> </div>
                        </div>
                    </div>
                </div>
                
                <!-- INICIO AREA DEL FORMULARIO  -->
                <?php echo form_open("email/insertar", "id=\"forma-email\" class=\"form-vertical\""); ?>
                <input type="hidden" id="id" name="idEmail" value="<?php echo $oEmail->idEmail; ?>" />

                <fieldset>
                    <legend><?php echo lang("general_datos_generales"); ?></legend>
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label" for="cTitulo"><?php echo lang("email_titulo_email"); ?> :</label>
                                <div class="controls">
                                    <?php echo form_input("cTitulo", $oEmail->cTitulo, "id=\"cTitulo\"class=\"input-xxlarge\" maxlength=\"45\""); ?>
                                    <span class="help-block"><?php echo lang("email_titulo_help"); ?></span>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="idIdioma"><?php echo lang("email_idioma"); ?> :</label>
                                <div class="controls">
                                    <?php echo form_dropdown("idIdioma", $aCombosForma["aIdiomas"], $oEmail->idIdioma, "id=\"idIdioma\"class=\"input-xlarge\""); ?>
                                    <span class="help-block"><?php echo lang("email_idioma_help"); ?></span>
                                </div>
                            </div>
                            
                            <div class="control-group">
                                <label class="control-label" for="idTipoEmail"><?php echo lang("email_tipo"); ?> :</label>
                                <div class="controls">
                                    <?php echo form_dropdown("idTipoEmail", $aCombosForma["aTipoEmail"], $oEmail->idTipoEmail, "id=\"idTipoEmail\"class=\"input-large validate[required]\""); ?>
                                    <span class="help-block"><?php echo lang("email_tipo_help"); ?></span>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="cDescripcion"><?php echo lang("email_descripcion_email"); ?> :</label>
                                <div class="controls">
                                    <textarea id="cDescripcion" name="cDescripcion" cols="5" rows="3" class="input-xlarge validate[required, maxSize[200]]" maxlength="200" ><?php echo $oEmail->cDescripcion; ?></textarea>
                                    <span class="help-block"><?php echo lang("email_descripcion_email_help"); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="span6">
                            <div class="ui-tabs ui-widget ui-widget-content ui-corner-all" style="margin-top: 150px; overflow-y: scroll; height: 180px;">
                                <?php echo lang("email_etiquetas"); ?>
                                <table width="100%" border="0" cellpadding="4" cellspacing="0">	
                                    <thead>
                                        <tr class="ui-state-default">
                                            <th align="left" class="borderLim" ><?php echo lang('roles_operaciones') ?></th>
                                            <th align="left" class="borderLim" ><?php echo lang('roles_descripcion_roles') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($aCombosForma["aEtiquetasEmail"])): ?>
                                            <?php foreach ($aCombosForma["aEtiquetasEmail"] as $oEtiquetaEmail): ?>
                                                <tr class="">
                                                    <td align="left" ><?php echo $oEtiquetaEmail->cEtiqueta; ?></td>
                                                    <td align="left" ><?php echo lang($oEtiquetaEmail->cEtiquetaDescripcion); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>        
                                            <tr class="">
                                                <td align="left" colspan="2"><?php echo lang("email_no_hay_etiquetas"); ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <br />
                    
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="control-group">
                                <label class="control-label" for="txCuerpo"><?php echo lang("email_cuerpo"); ?> :</label>
                                <div class="controls">
                                    <textarea id="txCuerpoTiny" name="txCuerpoTiny" cols="5" rows="3" class="input-large"><?php echo $oEmail->txCuerpo; ?></textarea>
                                    <input type="hidden" id="txCuerpo" name="txCuerpo" />
                                    <span class="help-block"><?php echo lang("email_cuerpo_help"); ?></span>
                                </div>
                            </div>
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


<!-- INICIO AREA JAVASCRIPT  -->
<?php $this->load->view("layout/javascript_base_view"); ?>

<?php incluye_componente_validate(); ?>
<?php script_tag("plugins/tinymce/tinymce"); ?>
<?php script_tag("plugins/ajaxForm/jquery.ajaxForm"); ?>
<?php script_tag("modulos/email"); ?>

<script type="text/javascript">
    
    $(document).ready(function() {
        email.initForma({
            'paramsForma': {
              rules:{
                  cTitulo:{
                      required:true,
                  },
                  idIdioma:{
                      required:true
                  },
                  cDescripcion:{
                      required:true
                  },
                  txCuerpoTiny:{
                      required:true
                  },
                  idTipoEmail:{
                      required:true
                  }
              }
            }
        });
    });
</script>
<!-- FIN AREA JAVASCRIPT  -->
