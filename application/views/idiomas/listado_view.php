<!-- 

Vista de listado del catalogo idiomas

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
                            <?php if($this->seguridad->verificarAcceso("idiomas", "insertar")): ?>
                                <button onclick="idiomas.btnAgregar();" class="btn btn-small btn-success">
                                    <span class="icon-new icon-white"></span>
                                     <?php echo lang('general_accion_agregar'); ?>
                                </button>
                            <?php endif; ?>
                        </div>
                        
                        <div class="btn-wrapper" id="toolbar-publish">
                            <button onclick="idiomas.btnListado();" class="btn btn-small">
                                <span class="icon-circle"></span>
                                <?php echo lang('general_accion_recargar'); ?>
                            </button>
                        </div>
                        <!-- FIN PARTE IZQUIERDA  -->
                        
                        <!-- INICIA PARTE DERECHA  -->
                        <?php if ($this->seguridad->verificarAcceso("idiomas", "exportar")): ?>
                            <div class="btn-wrapper" id="toolbar-help">
                                <a href="<?php echo base_url("idiomas/exportar/word"); ?>" rel="help" class="btn btn-small" target="_blank">
                                    <span class="icon-export-word"></span>
                                    <?php echo lang("general_accion_word"); ?>
                                </a>
                            </div>

                            <div class="btn-wrapper" id="toolbar-help">
                                <a href="<?php echo base_url("idiomas/exportar/xls"); ?>" rel="help" class="btn btn-small" target="_blank">
                                    <span class="icon-export-excel"></span>
                                    <?php echo lang("general_accion_excel"); ?>
                                </a>
                            </div>

                            <div class="btn-wrapper" id="toolbar-help">
                                <a href="<?php echo base_url("idiomas/exportar/csv"); ?>" rel="help" class="btn btn-small" target="_blank">
                                    <span class="icon-export-csv"></span>
                                    <?php echo lang("general_accion_csv"); ?>
                                </a>
                            </div>

                            <div class="btn-wrapper" id="toolbar-help">
                                <a href="<?php echo base_url("idiomas/exportar/pdf"); ?>" rel="help" class="btn btn-small" target="_blank">
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
                <div class="row-fluid" >
                    <div class="span11">
                        <div class="center encabezado-catalogo">
                            <div class="texto-encabezado"><?php echo lang('idiomas_titulo'); ?> </div>
                        </div>
                    </div>
                </div>
                <br />

                <!-- INICIA AREA LISTADO DE REGISTROS -->
                <div class="row-fluid" >
                    <table width="100%" cellpadding="2" cellspacing="1" class="data-grid">
                        <tr>
                            <td>
                                <div class="ui-corner-all envoltura-tabla">
                                    <table cellpadding="0" cellspacing="0" border="0" class="display" id="listado">
                                        <thead>
                                            <tr>
                                                <th><?php echo lang('idiomas_nombre'); ?></th>
                                                <th><?php echo lang('idiomas_alias'); ?></th>
                                                <th><?php echo lang('idiomas_archivo'); ?></th>
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
    </section>
</div>
<!-- FIN CONTENIDO DEL CATALOGO  -->


<!-- INICIA AREA JAVASCRIPT -->
<?php $this->load->view("layout/javascript_base_view"); ?>
<?php incluye_componente_datatables(); ?>
<?php incluye_componente_tabletools(); ?>
<?php script_tag("plugins/ajaxForm/jquery.ajaxForm"); ?>
<?php script_tag("modulos/idiomas"); ?>

<script type="text/javascript" >
    var idIdioma = <?php echo $idIdiomaConf; ?>;
    var acciones = {
        "editar": "<?php echo incluye_icono("editar", lang("general_accion_editar"), "float:left;", base_url() . "idiomas/forma/@@@@", "", true, "actualizar") ?>",
        "habilitar": "<?php echo incluye_icono("habilitar", lang("general_accion_habilitar"), "float:left;", "#", "onclick=\'idiomas.btnCmd(@@@@, @@@@@)\'", true, "habilitar"); ?>",
        "deshabilitar": "<?php echo incluye_icono("deshabilitar", lang("general_accion_deshabilitar"), "float:left;", "#", "", true, "deshabilitar"); ?>",
    };
    
    var dataTableUI = new CoreUI.CacheDataTable({
        delay: 500,
        complete: function(json) {
            if (json.noLogin == 1) {
                idiomas.irIniciarSesion();
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
                {"sName": "cNombreArchivo"},
                {"sName": "idIdioma", "sWidth":"10%"},
            ],
            "sAjaxSource": Generic.BASE_URL + Generic.CONTROLLER + "/listadoAjax",
            "fnServerData": function(sSource, aoData, fnCallback) {
                aoData[aoData.length] = {name: "cNombre", value: idiomas.cNombre};
                aoData[aoData.length] = {name: "cAlias", value: idiomas.cAlias};
                aoData[aoData.length] = {name: "cNombreArchivo", value: idiomas.cNombreArchivo};
                dataTableUI.tablePipeLine(sSource, aoData, fnCallback);
            },
            "fnRowCallback": function(nRow, aData, iDisplayIndex) {
                var cActions = '<div class="fg-buttonset">';
                cActions += acciones["editar"].replace("@@@@", aData[3]) + "\n";
                
                if (aData[3] == idIdioma) {
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