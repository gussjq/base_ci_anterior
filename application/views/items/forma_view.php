<!-- 

Vista de forma del catalogo Items

@author DEVELOPER 1 <correo@developer1> cel <1111111111>
@creado 31-12-2014

-->

<style type="text/css">
    #ul-root {
        border: 1px solid #eee;
        width: 142px;
        min-height: 20px;
        list-style-type: none;
        margin: 0;
        padding: 5px 0 5px 0;
        margin-right: 10px;
    }
</style>

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
                            <button onclick="Items.btnGuardar();" class="btn btn-small btn-success">
                                <span class="icon-new icon-white"></span>
                                <?php echo lang('general_accion_guardar'); ?>
                            </button>
                        </div>

                        <div class="btn-wrapper" id="toolbar-cancel">
                            <button onclick="Items.btnCancelar();" class="btn btn-small">
                                <span class="icon-cancel"></span>
                                <?php echo lang('general_accion_cancelar'); ?>
                            </button>
                        </div>
                        
                        <div class="btn-wrapper" id="toolbar-cancel">
                            <button onclick="Items.btnRegresar();" class="btn btn-small">
                                <span class="icon-arrow-left"></span>
                                <?php echo lang('general_accion_regresar'); ?>
                            </button>
                        </div>
          
                        <!-- FIN PARTE IZQUIERDA  -->

                        <!-- INICIA PARTE DERECHA  -->
                        <div class="btn-wrapper" id="toolbar-help">
                            <?php if($this->seguridad->verificarAcceso("empresas", "cambiarEmpresaAjax")): ?>
                            <button onclick="idiomas.btnMostrarEmpresas();" rel="help" class="btn btn-small">
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
                <br />
                <div class="row-fluid" >
                    <div class="span11">
                        <div class="center encabezado-catalogo">
                            <div class="texto-encabezado"><?php echo lang('Items_titulo'); ?> </div>
                        </div>
                    </div>
                </div>
                
                <!-- INICIO AREA DEL FORMULARIO  -->
                <?php echo form_open("items/insertar", "id=\"forma-items\" class=\"form-horizontal\""); ?>
                <input type="hidden" id="idMenu" name="idMenu" value="<?php echo $oMenu->idMenu; ?>" />
                <input type="hidden" id="id" name="idItems" value="" />

                <fieldset>
                    <legend><?php echo lang("general_datos_generales"); ?> - <span id="estado-catalogo"></span></legend>

                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label-sin-margen" for="idModulo"><?php echo lang("Items_modulo"); ?> :</label>
                                <div class="controls-sin-margen">
                                    <?php echo form_dropdown("idModulo", $aCombosForma["aModulos"], "", "id=\"idModulo\" class=\"\""); ?>
                                    <span class="help-block"><?php echo lang("Items_modulo_help"); ?></span>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label-sin-margen" for="idAccion"><?php echo lang("Items_accion"); ?> :</label>
                                <div class="controls-sin-margen">
                                    <?php echo form_dropdown("idAccion", array("" => lang("general_seleccionar")), "", "id=\"idAccion\" onchange=\"Items.getLinkAjax();\""); ?>
                                    <span class="help-block"><?php echo lang("Items_accion_help"); ?></span>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label-sin-margen" for="cLink"><?php echo lang("Items_link"); ?> :</label>
                                <div class="controls-sin-margen">
                                    <?php echo form_input("cLink", $cLinkDefault, "id=\"cLink\" class=\"validate[maxSize[100]]\" "); ?>
                                    <span class="help-block"><?php echo lang("Items_link_help"); ?></span>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label-sin-margen" for="cEtiquetaTitulo"><?php echo lang("Items_etiqueta_titulo"); ?> :</label>
                                <div class="controls-sin-margen">
                                    <?php echo form_input("cEtiquetaTitulo", "", "id=\"cEtiquetaTitulo\" class=\"validate[maxSize[60]]\""); ?>
                                    <span class="help-block"><?php echo lang("Items_etiqueta_titulo_help"); ?></span>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label-sin-margen" for="cEtiquetaDescripcion"><?php echo lang("Items_etiqueta_descripcion"); ?> :</label>
                                <div class="controls-sin-margen">
                                    <?php echo form_input("cEtiquetaDescripcion", "", "id=\"cEtiquetaDescripcion\" class=\"validate[maxSize[60]]\""); ?>
                                    <span class="help-block"><?php echo lang("Items_etiqueta_descripcion_help"); ?></span>
                                </div>
                            </div>

                            <!--
                            <div class="control-group">
                                <label class="control-label-sin-margen" for="cIcono"><?php echo lang("Items_icono"); ?> :</label>
                                <div class="controls-sin-margen">
                                    <?php echo form_upload("cIconoUpload", "", "id=\"cIconoUpload\""); ?>
                                    <input type="hidden" id="cIcono" name="cIcono" value="" />
                                    <span class="help-block"><?php echo lang("Items_icono_help"); ?></span>


                                    <div id="contenedor-archivo">
                                        <table><tr><td><img id="cImgUpload" src="<?php echo $cRutaDefault; ?>"  width="100" height="100" class="img-polaroid"/></td></tr></table>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            
                            -->
                        </div>
                        <div class="span6">
                            <div id="contenedor-items" class="contenedor_gris_casi_blanco" style="max-width:400px;height: 540px;overflow: scroll;">
                                <ol class="sortable ui-sortable">
                                    <?php pintarItemsCatalogo($aItems); ?>
                                </ol>
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

<?php link_tag("plugins/nestedsortable/nestedSortable"); ?>
<?php script_tag("plugins/nestedsortable/jquery.mjs.nestedSortable"); ?>
<?php script_tag("plugins/ajaxForm/jquery.ajaxForm"); ?>
<?php script_tag("modulos/items"); ?>


<script type="text/javascript">
    Items.TEXT_NUEVO_ITEM = "<?php echo lang('Items_nuevo_item'); ?>";
    Items.TEXT_EDITAR_ITEM = "<?php echo lang('Items_editar_item'); ?>";
    
    $(document).ready(function() {
        $('.sortable').nestedSortable({
            forcePlaceholderSize: true,
            handle: 'div',
            helper: 'clone',
            items: 'li',
            opacity: .6,
            placeholder: 'placeholder',
            revert: 250,
            tabSize: 25,
            tolerance: 'pointer',
            toleranceElement: '> div',
//            maxLevels: 3,
            isTree: true,
            expandOnHover: 700,
            startCollapsed: true,
        });
        
        $( ".sortable" ).on( "sortstop", function( event, ui ) {
            Items.ordenarMenu();
        } );

        $('.disclose').on('click', function() {
            $(this).closest('li').toggleClass('mjs-nestedSortable-collapsed').toggleClass('mjs-nestedSortable-expanded');
        });
        
        Items.initForma({
            'paramsForma': {
                
            }
        });
    });
</script>
<!-- FIN AREA JAVASCRIPT  -->