<!-- 

Vista o capa de presentacion de la pantalla del catálogo de avisos
registradas en el sistema en un formato de listado, en donde  se cuenta con las opciones de exportar a Excel, CSV, Word, PDF, agregar, actualizar,
habilitar, deshabilitar, segun el nivel de acceso que tenga el usuario, ademas contara con un menu de acceso rapido a el proceso de configuracion de avisos

@author DEVELOPER 1 <correo@developer1> cel <1111111111>
@created 09-03-2015

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
                           
                        </div>

                        <div class="btn-wrapper" id="toolbar-publish">
                            <button onclick="avisos.btnListado();" class="btn btn-small">
                                <span class="icon-circle"></span>
                                <?php echo lang('general_accion_recargar'); ?>
                            </button>
                        </div>
                        <!-- FIN PARTE IZQUIERDA  -->

                        <!-- INICIA PARTE DERECHA  -->
                        
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
                            <div class="texto-encabezado"><?php echo lang('avisos_titulo'); ?> </div>
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
                                                <th><?php echo lang('avisos_estatus'); ?></th>
                                                <th><?php echo lang('avisos_nombre_titulo'); ?></th>
                                                <th><?php echo lang('general_acciones'); ?></th>   
                                                <th></th>
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


<!--  modales  -->
<?php  $this->load->view("avisos/forma_modal_view"); ?>


<!-- INICIA AREA JAVASCRIPT -->
<?php $this->load->view("layout/javascript_base_view"); ?>
<?php incluye_componente_datatables(); ?>
<?php incluye_componente_tabletools(); ?>
<?php script_tag("plugins/ajaxForm/jquery.ajaxForm"); ?>
<?php script_tag("modulos/avisos"); ?>

<script type="text/javascript" >
    var acciones = {
        "visualizar": "<?php echo incluye_icono("visualizar", lang("general_accion_editar"), "float:left;", "#", "onclick=\'avisos.btnVisualizar(@@@@)\'", true, "visualizar") ?>",
        "eliminar": "<?php echo incluye_icono("eliminar", lang("general_accion_eliminar"), "float:left;", "#", "onclick=\'avisos.btnEliminar(@@@@,????)\'", true, "eliminar"); ?>",
    };
    
    var dataTableUI = new CoreUI.CacheDataTable({
        delay: 500,
        complete: function (json) {
            if (json.noLogin) {
                avisos.irIniciarSesion();
            }
        }
    });
    
    var oTable;
    $(document).ready(function () {
        oTable = $('#listado').dataTable({
            "sDom": '<"top"lf<"clear">>rt<"bottom"ip<"clear">',
            "bSort": true,
            "aaSorting": [[ 3, "desc" ]],
            "bPaginate": true,
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "iDisplayLength": 10,
            "bServerSide": true,
            "bProcessing": true,
            "aoColumns": [
                {"sName": "bLeido", "sWidth": "10%"},
                {"sName": "cTitulo", "sWidth": "75%"},
                {"sName": "idReceptor", "sWidth": "10%"},
                {"sName": "dtFechaCreacion", "bSortable": true, "bSearchable": true, "bVisible":false }
            ],
            "sAjaxSource": Generic.BASE_URL + Generic.CONTROLLER + "/listadoAjax",
            "fnServerData": function (sSource, aoData, fnCallback) {
                
                dataTableUI.tablePipeLine(sSource, aoData, fnCallback);
            },
            "fnRowCallback": function (nRow, aData, iDisplayIndex) {
                var cActions = '<div class="fg-buttonset">';
                
                cActions += acciones["visualizar"].replace("@@@@", aData[2]) + "\n";
                
                cActions += acciones["eliminar"]
                        .replace("@@@@", aData[2]) 
                        .replace("????", "\"" + aData[1] + "\"");
                
                cActions += '</div>';
                $('td:eq(2)', nRow).html(cActions);
                
                
                var cLeido = Generic.BASE_URL + Generic.DIRECTORIO_ICON + "aviso_leido.png";
                var cNoLeido = Generic.BASE_URL + Generic.DIRECTORIO_ICON + "aviso.png";
                
                var cImagen = "<div style=\"text-align:center;\"><img width=\"28\" src=\"   "+ ((aData[0] == 1) ? cLeido : cNoLeido) +"  \" /></div>";
                
                $('td:eq(0)', nRow).html(cImagen);
                return nRow;
            }
        });
    });
    
</script>
<!-- FIN AREA JAVASCRIPT -->

