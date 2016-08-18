<!-- 

Vista de listado bitacora del sistema 

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
                        <div class="btn-wrapper" id="toolbar-edit">
                            <button onclick="bitacora.btnBusquedaAvanzada();" class="btn btn-small">
                                <span class="icon-zoom-in"></span>
                                <?php echo lang('general_accion_busqueda_avanzada'); ?>
                            </button>
                        </div>
                        
                        <div class="btn-wrapper" id="toolbar-publish">
                            <button onclick="bitacora.btnListado();" class="btn btn-small">
                                <span class="icon-circle"></span>
                                <?php echo lang('general_accion_recargar'); ?>
                            </button>
                        </div>
                        <!-- FIN PARTE IZQUIERDA  -->
                        
                        <!-- INICIA PARTE DERECHA  -->
                        <?php if ($this->seguridad->verificarAcceso("bitacora", "exportar")): ?>
                            <div class="btn-wrapper" id="toolbar-help">
                                <a href="<?php echo base_url("bitacora/exportar/word"); ?>" rel="help" class="btn btn-small" target="_blank">
                                    <span class="icon-export-word"></span>
                                    <?php echo lang("general_accion_word"); ?>
                                </a>
                            </div>

                            <div class="btn-wrapper" id="toolbar-help">
                                <a href="<?php echo base_url("bitacora/exportar/xls"); ?>" rel="help" class="btn btn-small" target="_blank">
                                    <span class="icon-export-excel"></span>
                                    <?php echo lang("general_accion_excel"); ?>
                                </a>
                            </div>

                            <div class="btn-wrapper" id="toolbar-help">
                                <a href="<?php echo base_url("bitacora/exportar/csv"); ?>" rel="help" class="btn btn-small" target="_blank">
                                    <span class="icon-export-csv"></span>
                                    <?php echo lang("general_accion_csv"); ?>
                                </a>
                            </div>

                            <div class="btn-wrapper" id="toolbar-help">
                                <a href="<?php echo base_url("bitacora/exportar/pdf"); ?>" rel="help" class="btn btn-small" target="_blank">
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
                
                <div class="row-fluid" >
                    <div class="span11">
                        <div class="center encabezado-catalogo">
                            <div class="texto-encabezado"><?php echo lang('bitacora_titulo'); ?> </div>
                        </div>
                    </div>
                </div>
                <br />
                <!-- INICIA AREA BUSQUEDA AVANZADA -->
                <div class="row-fluid">
                    <div id="busqueda-avanzada" style="display: none;" >
                        <div class="contenedor_gris">
                            <?php echo form_open("bitacora/busqueda-avanzada", "id=\"forma-bitacora-busqueda-avanzada\" class=\"\""); ?>
                            <table class="tbl-busqueda-avanzada">
                                <tbody>
                                    <tr>
                                        <td colspan=""><?php echo form_label(lang('bitacora_modulo'), "idModulo") ?></td>
                                        <td colspan="3"><?php echo form_dropdown("idModulo", $aCombosForma['aModulos'], $oBitacora->idModulo, "id=\"idModulo\" style=\"width:100%\""); ?></td>
                                    </tr>

                                    <tr>
                                        <td colspan=""><?php echo form_label(lang('bitacora_accion'), "idAccion") ?></td>
                                        <td colspan="3"><?php echo form_dropdown("idAccion", array("" => lang("general_seleccionar")), $oBitacora->idAccion, "id=\"idAccion\" style=\"width:100%\""); ?></td>
                                    </tr>

                                    <tr>
                                        <td colspan=""><?php echo form_label(lang('bitacora_usuario'), "idUsuario") ?></td>
                                        <td colspan="3"><?php echo form_dropdown("idUsuario", $aCombosForma['aUsuarios'], $oBitacora->idUsuario, "id=\"idUsuario\" style=\"width:100%\""); ?></td>
                                    </tr>

                                    <tr>
                                        <td><?php echo form_label(lang('bitacora_fecha_inicio'), "idUsuario") ?></td>
                                        <td><?php echo form_input("dtFechaInicio", $oBitacora->dtFechaInicio, "id=\"dtFechaInicio\" class=\"input-small\" disabled=\"disabled\"") ?></td>
                                        <td><?php echo form_label(lang('bitacora_fecha_fin'), "idUsuario") ?></td>
                                        <td><?php echo form_input("dtFechaFin", $oBitacora->dtFechaFin, "id=\"dtFechaFin\" class=\"input-small\" disabled=\"disabled\"") ?></td>
                                    </tr>

                                    <tr>
                                        <td>&nbsp;</td>
                                        <td colspan="3">
                                            <?php echo form_button('cCancelar', lang("general_accion_cancelar"), 'id="cCancelar" class="btn btn-secundary " onclick="bitacora.btnCancelarBusqueda();"'); ?>
                                            &nbsp;
                                            <?php echo form_button('cBuscar', lang("general_accion_buscar"), 'id="cGuardar" class="btn btn-primary " onclick="bitacora.btnBuscar();"'); ?>
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
                                                <th><?php echo lang('bitacora_fecha'); ?></th>
                                                <th><?php echo lang('bitacora_usuario'); ?></th>
                                                <th><?php echo lang('bitacora_modulo'); ?></th>
                                                <th><?php echo lang('bitacora_accion'); ?></th>
                                                <th><?php echo lang('bitacora_descripcion_bitacora'); ?></th>
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
<?php script_tag("modulos/" . getController()); ?>

<script type="text/javascript" >

   bitacora.DayNames = <?php echo lang('general_dias'); ?>;
   bitacora.MonthNames = <?php echo lang('general_meses'); ?>;
   bitacora.dtFechaFinDayPicker = '<?php echo date('Y');  ?>';
   
   var dataTableUI = new CoreUI.CacheDataTable({
      delay: 500,
      complete: function(json){
         if (json.noLogin) {
            bitacora.irIniciarSesion();
         }
      }
   });

   var oTable;
   $(document).ready(function(){
       
      bitacora.init(); 
      
//      $.fn.dataTable.TableTools.DEFAULTS.aButtons = [ "csv", "xls", "pdf" ];
//      $.fn.dataTable.TableTools.DEFAULTS.sSwfPath = Generic.BASE_URL + Generic.DIRECTORIO_JAVASCRIPT + 'plugins/Datatables/extras/TableTools/media/swf/copy_csv_xls_pdf.swf';
      oTable = $('#listado').dataTable({
         "sDom": '<"top"lf<"clear">>rt<"bottom"ip<"clear">',
         "aaSorting": [[ 0, "desc" ]],
         "bSort": true,
         "bPaginate": true,
         "bJQueryUI": true,
         "sPaginationType": "full_numbers",
         "iDisplayLength":10,
         "bServerSide": true,
         "bProcessing": true,
         "aoColumns": [
            {"sName": "dtFecha", "sWidth": "15%"},
            {"sName": "cNombreUsuario", "sWidth": "18%"},
	    {"sName": "cModulo", "sWidth": "15%"},
	    {"sName": "cAccion", "sWidth": "15%"},
            {"sName": "cDescripcion", "sWidth": "20%"},
         ],
         "sAjaxSource": Generic.BASE_URL + Generic.CONTROLLER + "/listadoAjax",
         "fnServerData": function ( sSource, aoData, fnCallback ) {
            aoData[aoData.length] = { name: "idModulo", value: bitacora.idModulo };
            aoData[aoData.length] = { name: "idAccion", value: bitacora.idAccion };
            aoData[aoData.length] = { name: "idUsuario", value: bitacora.idUsuario };
            aoData[aoData.length] = { name: "cModulo", value: bitacora.cModulo };
            aoData[aoData.length] = { name: "cAccion", value: bitacora.cAccion };
            aoData[aoData.length] = { name: "dtFechaInicio", value: bitacora.dtFechaInicio };
            aoData[aoData.length] = { name: "dtFechaFin", value: bitacora.dtFechaFin };
            dataTableUI.tablePipeLine(sSource, aoData, fnCallback);
         }
      });
      
      bitacora.btnBuscar();
   });
</script>
<!-- FIN AREA JAVASCRIPT -->