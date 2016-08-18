
<!-- 

Vista de listado del catalogo email

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
                        
                        
                        <div class="btn-wrapper" id="toolbar-publish">
                            <button onclick="email.btnListado();" class="btn btn-small">
                                <span class="icon-circle"></span>
                                <?php echo lang('general_accion_recargar'); ?>
                            </button>
                        </div>
                        <!-- FIN PARTE IZQUIERDA  -->
                        
                        <!-- INICIA PARTE DERECHA  -->
                        <?php if ($this->seguridad->verificarAcceso("email", "exportar")): ?>
                            <div class="btn-wrapper" id="toolbar-help">
                                <a href="<?php echo base_url("email/exportar/word"); ?>" rel="help" class="btn btn-small" target="_blank">
                                    <span class="icon-export-word"></span>
                                    <?php echo lang("general_accion_word"); ?>
                                </a>
                            </div>

                            <div class="btn-wrapper" id="toolbar-help">
                                <a href="<?php echo base_url("email/exportar/xls"); ?>" rel="help" class="btn btn-small" target="_blank">
                                    <span class="icon-export-excel"></span>
                                    <?php echo lang("general_accion_excel"); ?>
                                </a>
                            </div>

                            <div class="btn-wrapper" id="toolbar-help">
                                <a href="<?php echo base_url("email/exportar/csv"); ?>" rel="help" class="btn btn-small" target="_blank">
                                    <span class="icon-export-csv"></span>
                                    <?php echo lang("general_accion_csv"); ?>
                                </a>
                            </div>

                            <div class="btn-wrapper" id="toolbar-help">
                                <a href="<?php echo base_url("email/exportar/pdf"); ?>" rel="help" class="btn btn-small" target="_blank">
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
                <br />
                <div class="row-fluid" >
                    <div class="span11">
                        <div class="center encabezado-catalogo">
                            <div class="texto-encabezado"><?php echo lang('email_titulo'); ?> </div>
                        </div>
                    </div>
                </div>
                
                <br />

                <!-- INICIA AREA LISTADO DE REGISTROS -->
                <table width="100%" cellpadding="2" cellspacing="1" class="data-grid">
                    <tr>
                        <td>
                            <div class="ui-corner-all envoltura-tabla">
                                <table cellpadding="0" cellspacing="0" border="0" class="display" id="listado">
                                    <thead>
                                        <tr>
                                            <th><?php echo lang('email_titulo_email'); ?></th>
                                            <th><?php echo lang('email_descripcion_email'); ?></th>
                                            <th><?php echo lang('email_idioma'); ?></th>
                                            <th><?php echo lang('general_acciones'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>																	
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>
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
<?php script_tag("modulos/email"); ?>

<script type="text/javascript" >
    var acciones = {
        "editar": "<?php echo incluye_icono("editar", lang("general_accion_editar"), "float:left;", base_url() . "email/forma/@@@@", "", true, "actualizar") ?>"
    };
    
    var dataTableUI = new CoreUI.CacheDataTable({
        delay: 500,
        complete: function(json) {
            if (json.noLogin == 1) {
                email.irIniciarSesion();
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
                {"sName": "cTitulo"},
                {"sName": "cDescripcion"},
                {"sName": "cAlias"},
                {"sName": "idEmail", "sWidth":"10%"},
            ],
            "sAjaxSource": Generic.BASE_URL + Generic.CONTROLLER + "/listadoAjax",
            "fnServerData": function(sSource, aoData, fnCallback) {
                aoData[aoData.length] = {name: "cTitulo", value: email.cTitulo};
                aoData[aoData.length] = {name: "idIdioma", value: email.idIdioma};
                aoData[aoData.length] = {name: "idTipoEmail", value: email.idTipoEmail};
                dataTableUI.tablePipeLine(sSource, aoData, fnCallback);
            },
            "fnRowCallback": function(nRow, aData, iDisplayIndex) {
                var cActions = '<div class="fg-buttonset">';
                cActions += acciones["editar"].replace("@@@@", aData[3]) + "\n";
                cActions += '</div>';
                $('td:eq(3)', nRow).html(cActions);
                return nRow;
            }
        });
    });
    
</script>
<!-- FIN AREA JAVASCRIPT -->