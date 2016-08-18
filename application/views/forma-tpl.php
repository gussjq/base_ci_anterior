<!-- 

Vista o capa de presentacion de la forma de captura del catálogo de ${modulo_singular_minuscula} en donde se mostraran  
los campos para su captura ya sea para su edición o bien para agregar nuevos registros, los campos obligatorios estan marcados con un (*), 
el usuario podra agregar o editar segun su nivel de acceso en el sistema

@author nombre del programador <correo@correo.com> cel <numero>
@created dd-mm-aaaa

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
                            <button onclick="${modulo_sigular_minuscula}.btnGuardar();" class="btn btn-small btn-success">
                                <span class="icon-new icon-white"></span>
                                <?php echo lang('general_accion_guardar'); ?>
                            </button>
                        </div>

                        <div class="btn-wrapper" id="toolbar-cancel">
                            <button onclick="${modulo_sigular_minuscula}.btnCancelar();" class="btn btn-small">
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
                <!-- INICIO AREA DEL FORMULARIO  -->
                <?php echo form_open("${modulo_plural_minuscula}/insertar", "id=\"forma-${modulo_singular_minuscula}\" class=\"form-horizontal\""); ?>
                <input type="hidden" id="id" name="id${modulo_singular_mayuscula}" value="<?php echo $o${modulo_singular_mayuscula}->id${modulo_singular_mayuscula}; ?>" />

                <fieldset>
                    <legend><?php echo lang("general_datos_generales"); ?></legend>

                    <div class="control-group">
                        <label class="control-label" for="cNombre"><?php echo lang("${modulo_singular_minuscula}_nombre"); ?> :</label>
                        <div class="controls">
                            <?php echo form_input("cNombre", $o${modulo_singular_mayuscula}->cNombre, "id=\"cNombre\"class=\"input-large\" maxlength=\"45\""); ?>
                            <span class="help-block"><?php echo lang("${modulo_singular_minuscula}_nombre_help"); ?></span>
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
<?php script_tag("modulos/${modulo_plural_minuscula}"); ?>

<script type="text/javascript">
    $(document).ready(function() {
        ${modulo_singular_minuscula}.initForma({
            'paramsForma': {
                rules: {
                    cNombre: {
                        required: true
                    },
                }

            }
        });
    });
</script>
<!-- FIN AREA JAVASCRIPT  -->