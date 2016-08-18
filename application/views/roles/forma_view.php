<!-- 

Vista de forma del catalogo roles

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
                            <button onclick="roles.btnGuardar();" class="btn btn-small btn-success">
                                <span class="icon-new icon-white"></span>
                                <?php echo lang('general_accion_guardar'); ?>
                            </button>
                        </div>

                        <div class="btn-wrapper" id="toolbar-cancel">
                            <button onclick="roles.btnCancelar();" class="btn btn-small">
                                <span class="icon-cancel"></span>
                                <?php echo lang('general_accion_cancelar'); ?>
                            </button>
                        </div>

                        <!-- FIN PARTE IZQUIERDA  -->

                        <!-- INICIA PARTE DERECHA  -->
                        <div class="btn-wrapper" id="toolbar-help">
                            <?php if ($this->seguridad->verificarAcceso("empresas", "cambiarEmpresaAjax")): ?>
                                <button onclick="roles.btnMostrarEmpresas();" rel="help" class="btn btn-small">
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
                            <?php pintarMenuCatalogo($aMenuCatalogo); ?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="span10">
                <br />
                <div class="row-fluid" >
                    <div class="span11">
                        <div class="center encabezado-catalogo">
                            <div class="texto-encabezado"><?php echo lang('roles_titulo'); ?> </div>
                        </div>
                    </div>
                </div>
                
                <!-- INICIO AREA DEL FORMULARIO  -->
                <?php echo form_open("roles/insertar", "id=\"forma-roles\" class=\"form-horizontal\""); ?>
                <input type="hidden" id="id" name="idRol" value="<?php echo $oRol->idRol; ?>" />

                <fieldset>
                    <legend><?php echo lang("general_datos_generales"); ?></legend>

                    <div class="row-fluid">
                        <div class="span12">
                            <div class="control-group">
                                <label class="control-label" for="cNombre"><?php echo lang("roles_nombre"); ?> :</label>
                                <div class="controls">
                                    <?php echo form_input("cNombre", $oRol->cNombre, "id=\"cNombre\"class=\"input-large\" maxlength=\"45\""); ?>
                                    <span class="help-block"><?php echo lang("roles_nombre_help"); ?></span>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="cDescripcion"><?php echo lang("roles_descripcion_roles"); ?> :</label>
                                <div class="controls">
                                    <textarea id="cDescripcion" name="cDescripcion" rows="5" cols="5" class="validate[required, maxSize[250]" maxlength="250"><?php echo $oRol->cDescripcion; ?></textarea>
                                    <span class="help-block"><?php echo lang("roles_descripcion_help"); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row-fluid">
                        <div class="span12">
                            <div id="content_acciones" class="ui-tabs ui-widget ui-widget-content ui-corner-all" >
                                <table border="0" cellpadding="4" cellspacing="0" id="acciones" width="100%">	
                                    <thead>
                                        <tr class="ui-state-default">
                                            <th align="left" class="borderLim" style="width: 300px;"><?php echo lang('roles_operaciones') ?></th>
                                            <th align="left" class="borderLim" ><?php echo lang('roles_descripcion_roles') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr row-id="1">
                                            <td >
                                                <input type="checkbox" name="padre_0" value="Si" id="padre_0" onclick="roles.checkRoot(this)" class="root_checkbox" />
                                                <?php echo lang("roles_permisos"); ?>
                                            </td>
                                            <td>
                                                <?php echo lang("roles_permisos_descripcion"); ?>
                                            </td>
                                        </tr>

                                        <?php crearArbolAcciones($aModulos); ?>

                                    </tbody>
                                </table>
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
<?php incluye_componente_treetable(); ?>
<?php script_tag("plugins/ajaxForm/jquery.ajaxForm"); ?>
<?php script_tag("modulos/roles"); ?>

<script type="text/javascript">

    roles.aAcciones = <?php echo $aRolAccion; ?>;
    $(document).ready(function() {
        $('#acciones').tbltree({initState: 'expand'});
        roles.initForma({
            'paramsForma': {
                rules: {
                    cNombre: {
                        required: true,
                    },
                    cDescripcion: {
                        required: true
                    }
                }
            }
        });
    });
</script>
<!-- FIN AREA JAVASCRIPT  -->