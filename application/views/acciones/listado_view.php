<!-- 

Vista de listado del catalogo acciones, muestra todas las acciones
dadas de alta para un modulo determinado.

@author DEVELOPER 1 <correo@developer1> cel <1111111111>
@fecha creacion 23-12-2014

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
                            <?php if($this->seguridad->verificarAcceso("modulos", "insertar")): ?>
                                <button onclick="accion.btnAgregar(<?php echo $oAccion->idModulo ?>);" class="btn btn-small btn-success">
                                    <span class="icon-new icon-white"></span>
                                     <?php echo lang('general_accion_agregar'); ?>
                                </button>
                            <?php endif; ?>
                        </div>
                        
                        <div class="btn-wrapper" id="toolbar-edit">
                            <button onclick="accion.btnBusquedaAvanzada();" class="btn btn-small">
                                <span class="icon-zoom-in"></span>
                                <?php echo lang('general_accion_busqueda_avanzada'); ?>
                            </button>
                        </div>
                        
                        <div class="btn-wrapper" id="toolbar-publish">
                            <button onclick="accion.btnListado(<?php echo $oAccion->idModulo ?>);" class="btn btn-small">
                                <span class="icon-circle"></span>
                                <?php echo lang('general_accion_recargar'); ?>
                            </button>
                        </div>
                        
                        <div class="btn-wrapper" id="toolbar-publish">
                            <button onclick="accion.btnRegresar();" class="btn btn-small">
                                <span class="icon-arrow-left"></span>
                                <?php echo lang('general_accion_regresar'); ?>
                            </button>
                        </div>
                        <!-- FIN PARTE IZQUIERDA  -->
                        
                        <!-- INICIA PARTE DERECHA  -->
                        <?php if ($this->seguridad->verificarAcceso("acciones", "exportar")): ?>
                            <div class="btn-wrapper" id="toolbar-help">
                                <a href="<?php echo base_url("acciones/exportar/word"); ?>" rel="help" class="btn btn-small" target="_blank">
                                    <span class="icon-export-word"></span>
                                    <?php echo lang("general_accion_word"); ?>
                                </a>
                            </div>

                            <div class="btn-wrapper" id="toolbar-help">
                                <a href="<?php echo base_url("acciones/exportar/xls"); ?>" rel="help" class="btn btn-small" target="_blank">
                                    <span class="icon-export-excel"></span>
                                    <?php echo lang("general_accion_excel"); ?>
                                </a>
                            </div>

                            <div class="btn-wrapper" id="toolbar-help">
                                <a href="<?php echo base_url("acciones/exportar/csv"); ?>" rel="help" class="btn btn-small" target="_blank">
                                    <span class="icon-export-csv"></span>
                                    <?php echo lang("general_accion_csv"); ?>
                                </a>
                            </div>

                            <div class="btn-wrapper" id="toolbar-help">
                                <a href="<?php echo base_url("acciones/exportar/pdf"); ?>" rel="help" class="btn btn-small" target="_blank">
                                    <span class="icon-export-pdf"></span>
                                    <?php echo lang("general_accion_pdf"); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        <!-- FIN PARTE DERECHA  -->
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- FIN BARRA DE ACCIONES  -->

<br />

<!-- INICIA CONTENIDO DEL CATALOGO DEBE INCLUIRSE EN TODOS LOS CATALOGOS -->
<div class="container-fluid container-main">
    <section id="content">
        <!-- Begin Content -->
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
                <!-- INICIA AREA BUSQUEDA AVANZADA -->
                <div class="row-fluid">
                    <div id="busqueda-avanzada" style="display: none;">
                        <div class="contenedor_gris">
                            <?php echo form_open("acciones/busqueda-avanzada", "id=\"forma-acciones-busqueda-avanzada\" "); ?>
                            <table class="tbl-busqueda-avanzada">
                                <tbody>
                                    <tr>
                                        <td><label class="control-label" for="cNombre"><?php echo lang("acciones_nombre"); ?> :</label></td>
                                        <td><?php echo form_input("cNombreBus", "", "id=\"cNombreBus\"class=\"input-large\" maxlength=\"45\""); ?></td>
                                    </tr>

                                    <tr>
                                        <td><label class="control-label" for="cAliasBus"><?php echo lang("acciones_alias"); ?> :</label></td>
                                        <td><?php echo form_input("cAliasBus", "", "id=\"cAliasBus\"class=\"input-large\" maxlength=\"45\""); ?></td>
                                    </tr>

                                    <tr>
                                        <td><label class="control-label" for="idTipoAccionBus"><?php echo lang("acciones_tipo"); ?> :</label></td>
                                        <td><?php echo form_dropdown("idTipoAccion", $aCombosForma["TipoAccion"], "", "id=\"idTipoAccionBus\"") ?></td>
                                    </tr>

                                    <tr>
                                        <td>&nbsp;</td>
                                        <td >
                                            <?php echo form_button('cCancelar', lang("general_accion_cancelar"), 'id="cCancelar" class="btn btn-secundary" onclick="accion.btnCancelarBusqueda();"'); ?>
                                            &nbsp;
                                            <?php echo form_button('cBuscar', lang("general_accion_buscar"), 'id="cGuardar" class="btn btn-primary" onclick="accion.btnBuscar();"'); ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
                <!-- FIN AREA BUSQUEDA AVANZADA -->

                <!-- INICIA AREA LISTADO DE REGISTROS -->
                <div class="row-fluid" >
                    <table width="100%" cellpadding="2" cellspacing="1" class="data-grid">
                        <tr>
                            <td>
                                <div class="ui-corner-all envoltura-tabla">
                                    <table cellpadding="0" cellspacing="0" border="0" class="display" id="listado">
                                        <thead>
                                            <tr>
                                                <th><?php echo lang('acciones_nombre'); ?></th>
                                                <th><?php echo lang('acciones_alias'); ?></th>
                                                <th><?php echo lang('acciones_descripcion'); ?></th>
                                                <th><?php echo lang('general_acciones'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>																	
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <!-- INICIA AREA LISTADO DE REGISTROS -->
            </div>
        </div>
        <!-- End Content -->
    </section>
</div>
<!-- FIN CONTENIDO DEL CATALOGO  -->


<!-- INICIA AREA JAVASCRIPT -->
<?php $this->load->view("layout/javascript_base_view"); ?>
<?php incluye_componente_datatables(); ?>
<?php incluye_componente_tabletools(); ?>
<?php script_tag("plugins/ajaxForm/jquery.ajaxForm"); ?>
<?php script_tag("modulos/acciones"); ?>

<script type="text/javascript" >
    var acciones = {
        "editar": "<?php echo incluye_icono("editar", lang("general_accion_editar"), "float:left;", base_url() . "acciones/forma/@@@@/????", "", true, "actualizar") ?>",
        "habilitar": "<?php echo incluye_icono("habilitar", lang("general_accion_habilitar"), "float:left;", "#", "onclick=\'accion.btnCmd(@@@@, @@@@@)\'", true, "habilitar"); ?>",
        "deshabilitar": "<?php echo incluye_icono("deshabilitar", lang("general_accion_deshabilitar"), "float:left;", "#", "onclick=\'accion.btnCmd(@@@@, @@@@@)\'", true, "deshabilitar"); ?>",
        "eliminar": "<?php echo incluye_icono("eliminar", lang("acciones_titulo"), "float:left;", "#", "onclick=\'accion.btnEliminar(@@@@)\'", true, "eliminar"); ?>",
    };
    
    var dataTableUI = new CoreUI.CacheDataTable({
        delay: 500,
        complete: function(json) {
            if (json.noLogin == 1) {
                accion.irIniciarSesion();
            }
        }
    });
    
    var oTable;
    $(document).ready(function() {
//        $.fn.dataTable.TableTools.DEFAULTS.aButtons = ["csv", "xls", "pdf"];
//        $.fn.dataTable.TableTools.DEFAULTS.sSwfPath = Generic.BASE_URL + Generic.DIRECTORIO_JAVASCRIPT + 'plugins/Datatables/extras/TableTools/media/swf/copy_csv_xls_pdf.swf';
        oTable = $('#listado').dataTable({
            "sDom": '<"top"lf<"clear">>rt<"bottom"ip<"clear">',
            "bSort": true,
            "bPaginate": true,
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "iDisplayLength": 10,
            "bServerSide": true,
            "bProcessing": true,
            "aoColumns": [
                {"sName": "cNombre"},
                {"sName": "cAlias"},
                {"sName": "cDescripcion"},
                {"sName": "idAccion", "sWidth":30},
                {"sName": "idModulo", bVisible:false},
                {"sName": "bHabilitado", bVisible:false}
            ],
            "sAjaxSource": Generic.BASE_URL + Generic.CONTROLLER + "/listadoAjax",
            "fnServerData": function(sSource, aoData, fnCallback) {
                aoData[aoData.length] = {name: "cNombre", value: accion.cNombre};
                aoData[aoData.length] = {name: "cAlias", value: accion.cAlias};
                aoData[aoData.length] = {name: "idTipoAccion", value: accion.idTipoAccion};
                aoData[aoData.length] = {name: "idModulo", value: <?php echo $oAccion->idModulo; ?>};
                dataTableUI.tablePipeLine(sSource, aoData, fnCallback);
            },
            "fnRowCallback": function(nRow, aData, iDisplayIndex) {
                var cActions = '<div class="fg-buttonset">';
                cActions += acciones["editar"]
                        .replace("????", aData[3])
                        .replace("@@@@", aData[4]) + "\n";
                
                if (aData[5] == 1) {
                    cActions += acciones["deshabilitar"]
                            .replace("@@@@", aData[3])
                            .replace("@@@@@", "\"" + Generic.BASE_URL + Generic.CONTROLLER + "/deshabilitar/\"");
                } else {
                    cActions += acciones["habilitar"]
                            .replace("@@@@", aData[3])
                            .replace("@@@@@", "\"" + Generic.BASE_URL + Generic.CONTROLLER + "/habilitar/\"");
                }
                cActions += '</div>';
                $('td:eq(3)', nRow).html(cActions);
                return nRow;
            }
        });
    });
    
</script>
<!-- FIN AREA JAVASCRIPT -->