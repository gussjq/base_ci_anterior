<!-- 

Vista de forma del catalogo idiomas

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
                            <button onclick="idiomas.btnGuardar();" class="btn btn-small btn-success">
                                <span class="icon-new icon-white"></span>
                                <?php echo lang('general_accion_guardar'); ?>
                            </button>
                        </div>

                        <div class="btn-wrapper" id="toolbar-cancel">
                            <button onclick="idiomas.btnCancelar();" class="btn btn-small">
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
                            <div class="texto-encabezado"><?php echo lang('idiomas_titulo'); ?> </div>
                        </div>
                    </div>
                </div>
                
                <!-- INICIO AREA DEL FORMULARIO  -->
                <?php echo form_open("idiomas/insertar", "id=\"forma-idiomas\" class=\"form-horizontal\""); ?>
                <input type="hidden" id="id" name="idIdioma" value="<?php echo $oIdioma->idIdioma; ?>" />

                <fieldset>
                    <legend><?php echo lang("general_datos_generales"); ?></legend>

                    <div class="control-group">
                        <label class="control-label" for="cNombre"><?php echo lang("idiomas_nombre"); ?> :</label>
                        <div class="controls">
                            <?php echo form_input("cNombre", $oIdioma->cNombre, "id=\"cNombre\"class=\"input-large validate[required, maxSize[45]]\" maxlength=\"45\""); ?>
                            <span class="help-block"><?php echo lang("idiomas_nombre_help"); ?></span>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="cAlias"><?php echo lang("idiomas_alias"); ?> :</label>
                        <div class="controls">
                            <?php echo form_input("cAlias", $oIdioma->cAlias, "id=\"cAlias\"class=\"input-large validate[required, maxSize[45]]\" maxlength=\"45\""); ?>
                            <span class="help-block"><?php echo lang("idiomas_alias_help"); ?></span>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="cNombreArchivo"><?php echo lang("idiomas_archivo"); ?> :</label>
                        <div class="controls">
                            <?php echo form_input("cNombreArchivo", $oIdioma->cNombreArchivo, "id=\"cNombreArchivo\"class=\"input-large validate[required, maxSize[45]]\" maxlength=\"45\""); ?>
                            <span class="help-block"><?php echo lang("idiomas_archivo_help"); ?></span>
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
<?php script_tag("plugins/ajaxForm/jquery.ajaxForm"); ?>
<?php script_tag("modulos/idiomas"); ?>

<script type="text/javascript">

    $(document).ready(function() {
        idiomas.initForma({
            'paramsForma': {
                rules: {
                    cNombre: {
                        required: true,
                        lettersonly: true
                    },
                    cAlias: {
                        required: true
                    },
                    cNombreArchivo: {
                        required: true,
                        lettersunderscore: true
                    }
                }

            }
        });
    });
</script>
<!-- FIN AREA JAVASCRIPT  -->