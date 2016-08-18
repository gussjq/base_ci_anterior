<!-- 

Vista de forma del catalogo de modulos

@author DEVELOPER 1 <correo@developer1> cel <1111111111>
@creado 29-12-2014

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
                            <button onclick="modulos.btnGuardar();" class="btn btn-small btn-success">
                                <span class="icon-new icon-white"></span>
                                <?php echo lang('general_accion_guardar'); ?>
                            </button>
                        </div>

                        <div class="btn-wrapper" id="toolbar-cancel">
                            <button onclick="modulos.btnCancelar();" class="btn btn-small">
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
                            <div class="texto-encabezado"><?php echo lang('modulos_titulo'); ?> </div>
                        </div>
                    </div>
                </div>
                
                <!-- INICIO AREA DEL FORMULARIO  -->
                <?php echo form_open("modulos/insertar", "id=\"forma-modulos\" class=\"form-horizontal\""); ?>
                <input type="hidden" id="id" name="idModulo" value="<?php echo $oModulo->idModulo; ?>" />

                <fieldset>
                    <legend><?php echo lang("modulos_datos_generales"); ?></legend>
                    <div class="row-fluid">
                        
                        <!--
                        <div class="span12">
                            <div class="control-group">
                                <label class="control-label" for="cIcono"><?php echo lang("modulos_icono"); ?> :</label>
                                <div class="controls">
                                    <?php echo form_upload("cIconoUpload", "", "id=\"cIconoUpload\" class=\"input-large\" maxlength=\"45\""); ?>
                                    <input type="hidden" id="cIcono" name="cIcono" value="<?php echo $oModulo->cIcono; ?>" />
                                    <span class="help-block"><?php echo lang("modulos_icono_help"); ?></span>
                                </div>
                            </div>
                        </div>
                        -->
                        <!--
                        <div class="span6" style="margin-bottom: 15px;">
                            <div id="contenedor-archivo">
                                <table><tr><td><img id="cImgUpload" src="<?php // echo $cRutaArchivo ?>"  width="100" height="100" class="img-polaroid"/></td></tr></table>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        -->
                    </div>

                    <div class="row-fluid">
                        <div class="span12">
                            <div class="control-group">
                                <label class="control-label" for="cNombre"><?php echo lang("modulos_nombre"); ?> :</label>
                                <div class="controls">
                                    <?php echo form_input("cNombre", $oModulo->cNombre, "id=\"cNombre\"class=\"input-large \" maxlength=\"45\""); ?>
                                    <span class="help-block"><?php echo lang("modulos_nombre_help"); ?></span>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="cAlias"><?php echo lang("modulos_alias"); ?> :</label>
                                <div class="controls">
                                    <?php echo form_input("cAlias", $oModulo->cAlias, "id=\"cAlias\"class=\"input-large\" maxlength=\"45\""); ?>
                                    <span class="help-block"><?php echo lang("modulos_alias_help"); ?></span>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="cDescripcion"><?php echo lang("modulos_descripcion_modulo"); ?> :</label>
                                <div class="controls">
                                    <textarea maxlength="255" cols="8" rows="5" id="cDescripcion" name="cDescripcion" class="validate[required, maxSize[250]]"><?php echo $oModulo->cDescripcion; ?></textarea>
                                    <span class="help-block"><?php echo lang("modulos_descripcion_help"); ?></span>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="cEtiquetaTitulo"><?php echo lang("modulos_etiqueta_titulo"); ?> :</label>
                                <div class="controls">
                                    <?php echo form_input("cEtiquetaTitulo", $oModulo->cEtiquetaTitulo, "id=\"cEtiquetaTitulo\"class=\"input-large\" maxlength=\"60\""); ?>
                                    <span class="help-block"><?php echo lang("modulos_etiqueta_titulo_help"); ?></span>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="cEtiquetaDescripcion"><?php echo lang("modulos_etiqueta_descripcion"); ?> :</label>
                                <div class="controls">
                                    <?php echo form_input("cEtiquetaDescripcion", $oModulo->cEtiquetaDescripcion, "id=\"cEtiquetaDescripcion\"class=\"input-large validate[required]\" maxlength=\"60\""); ?>
                                    <span class="help-block"><?php echo lang("modulos_etiqueta_descripcion_help"); ?></span>
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
<?php incluye_componente_uploadify(); ?>
<?php script_tag("plugins/ajaxForm/jquery.ajaxForm"); ?>
<?php script_tag("modulos/modulos"); ?>

<script type="text/javascript">

    $(document).ready(function() {
        modulos.initForma({
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
                        required:true
                    },
                    cEtiquetaTitulo:{
                        required:true,
                        lettersunderscore:true
                    },
                    cEtiquetaDescripcion:{
                        required:true,
                        lettersunderscore:true
                    }
                }
            }
        });
    });
</script>
<!-- FIN AREA JAVASCRIPT  -->