<style type="text/css" >
div.modal {
    position: fixed;
    top: 10%;
    left: 47% !important;
    z-index: 1050;
    width: 780px !important;
    margin-left: -280px;
    background-color: #fff;
    border: 1px solid #999;
    border: 1px solid rgba(0,0,0,0.3);
    -webkit-border-radius: 6px;
    -moz-border-radius: 6px;
    border-radius: 6px;
    -webkit-box-shadow: 0 3px 7px rgba(0,0,0,0.3);
    -moz-box-shadow: 0 3px 7px rgba(0,0,0,0.3);
    box-shadow: 0 3px 7px rgba(0,0,0,0.3);
    -webkit-background-clip: padding-box;
    -moz-background-clip: padding-box;
    background-clip: padding-box;
    outline: none;
}
 .dropdown-menu {
    position: absolute;    
    top: 82.8888%;
    left: 0;
    z-index: 1000;
    display: none;
    float: left;
    min-width: 160px;
    padding: 5px 0;
    margin: 2px 20px 0;
    list-style: none;
    background-color: #fff;
    border: 1px solid #ccc;
    border: 1px solid rgba(0,0,0,0.2);
    -webkit-border-radius: 6px;
    -moz-border-radius: 6px;
    border-radius: 6px;
    -webkit-box-shadow: 0 5px 10px rgba(0,0,0,0.2);
    -moz-box-shadow: 0 5px 10px rgba(0,0,0,0.2);
    box-shadow: 0 5px 10px rgba(0,0,0,0.2);
    -webkit-background-clip: padding-box;
    -moz-background-clip: padding;
    background-clip: padding-box;
}

#content-pie-paginator{
    text-align: center;
    margin: 0;
    padding: 0;
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
                <div class="span12"></div>
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
            <div class="span12">
                <div class="row-fluid">
                    <div class="alert alert-info">
                        <h2>
                            <?php echo lang("dashboard_bienvenido_titulo"); ?>:&nbsp;<?php echo UsuarioHelper::get("cNombreCompleto"); ?>					
                        </h2>
                        <p>
                            <?php echo lang("dashboard_bienvenido_mensaje"); ?>
                        </p>
                    </div>
                </div>

                <div class="row-fluid">
                    <!-- INICIA SECCION AVISOS -->
                    <?php if ($this->seguridad->verificarAcceso("dashboard", "avisos")): ?>
                        <div class="well well-small span6 " style="height: 300px;">
                            <h2 class="module-title nav-header">
                                <img src="<?php echo getRutaImagen(DIRECTORIO_IMAGENES_ICON . "carta.png"); ?>" style="width: 32px;" />
                                <?php echo lang("dashboard_avisos"); ?>
                            </h2>

                            <div class="row-striped"style="height: 200px;overflow-y: auto;">
                                <?php if (count($aAvisos) > 0): ?>
                                    <?php foreach ($aAvisos as $oAviso): ?>
                                        <div class="row-fluid">
                                            <div class="span1">
                                                <?php if ($oAviso->bLeido == 1): ?>
                                                    <img id="aviso-<?php echo $oAviso->idReceptor; ?>" src="<?php echo getRutaImagen(DIRECTORIO_IMAGENES_ICON . "aviso_leido.png"); ?>" style="width: 22px;" />
                                                <?php else: ?>
                                                    <img id="aviso-<?php echo $oAviso->idReceptor; ?>" src="<?php echo getRutaImagen(DIRECTORIO_IMAGENES_ICON . "aviso.png"); ?>" style="width: 22px;" />
                                                <?php endif; ?>

                                            </div>
                                            <div class="span9">
                                                <strong class="row-title">
                                                    <a href="#" onclick="dashboard.btnVisualizarAviso(<?php echo $oAviso->idReceptor; ?>);">

                                                        <?php echo $oAviso->cTitulo; ?>
                                                    </a>
                                                </strong>



                                                <small class="hasTooltip" title="" data-original-title="Created By">

                                                </small>
                                            </div>

                                            <div class="span2">
                                                <button class="btn btn-secondary" onclick="dashboard.btnVisualizarAviso(<?php echo $oAviso->idReceptor; ?>);">
                                                    <?php echo lang("dashboard_avisos_ver"); ?>
                                                </button>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="row-fluid">
                                        <div class="span9">
                                            <strong class="row-title">
                                                <a href="javascript:void(0);">
                                                    <?php echo lang("dashboard_no_hay_avisos"); ?>
                                                </a>
                                            </strong>

                                            <small class="hasTooltip" title="" data-original-title="Created By">

                                            </small>
                                        </div>
                                        <div class="span3">
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <!-- FIN SECCION AVISOS -->
                    <?php endif; ?>


                    <?php if ($this->seguridad->verificarAcceso("dashboard", "empleados")): ?>
                        <!-- INICIA SECCION EMPRESAS -->
                        <div class="well well-small span6 contenedor-gris-dashboard-empleados" style="height: 300px;">
                            <div class="row-fluid">
                                <div class="span5">
                                    <h2 class="module-title nav-header">
                                        <img src="<?php echo getRutaImagen(DIRECTORIO_IMAGENES_ICON . "roles.png"); ?>" style="width: 32px;" />
                                        <?php echo lang("dashboard_busqueda_empleados"); ?>
                                    </h2>
                                </div>
                                
                                <div class="span7">
                                    <div class="input-group">
                                        <form id="forma-buscar-empleado" action="<?php echo base_url("dashboard/buscarEmpleados"); ?>" method="post" style="margin: 0;padding: 0;">
                                            <input title="<?php echo lang("dashboard_buscar_empleado_ayuda"); ?>" maxlength="45" id="cBuscarEmpleado" name="cBuscarEmpleado" type="text" class="form-control buscar-empleado show-tooltip" placeholder="<?php echo lang("general_accion_buscar"); ?> ..." style="height: 20px;">
                                            <span class="input-group-btn">
                                                <button class="btn btn-primary btn-buscar-empleado" type="button" style="height: 30px;margin-top: -10px;" onclick="dashboard.btnBuscarEmpleado();"><span class="icon-search"></span></button>
                                                <button class="btn btn-secundary btn-buscar-empleado" type="button" style="height: 30px;margin-top: -10px;" onclick="dashboard.btnCancelarBusquedaEmpleado();"><span class="icon-cancel-2"></span></button>
                                            </span>
                                            <label id="cBuscarEmpleado-error" class="error" for="cBuscarEmpleado" style="display: none;"></label>
                                        </form>
                                    </div><!-- /input-group -->
                                </div>
                            </div>

                            <div id="content-body-paginator" class="row-striped contenedor-listado-empleados" style="height: 200px;overflow-y: auto;">
                                    <div class="row-fluid">
                                        <div class="span12">					
                                            <strong class="row-title">
                                                <a href="javascript:void(0);">
                                                    <?php echo lang("dashboard_mensaje_no_hay_empleados"); ?>
                                                </a>
                                            </strong>
                                        </div>
                                    </div>
                            </div>
                            
                            <div id="content-pie-paginator">
                             
                                        </div>
                        </div>
                        <!-- FIN SECCION EMPRESAS -->
                    <?php endif; ?>
                </div>
            </div>
        </div>


        <div class="row-fluid">
            <div class="span12">
                <ul class="nav nav-tabs">
                    
                    
                    <?php if($this->seguridad->verificarAcceso("dashboard", "listadoPeriodosNuevosAjax")): ?>
                    <li class="active" onclick="dashboard.initListadoNuevos();">
                            <a href="#pagos-nuevos" role="tab" data-toggle="tab"><?php echo lang("dashboard_pagos_nuevos"); ?></a>
                        </li>
                    <?php endif; ?>
                        

                    <?php if($this->seguridad->verificarAcceso("dashboard", "listadoPeriodosProcesoAjax")): ?>
                        <li onclick="dashboard.initListadoProceso();">
                            <a href="#pagos-en-proceso" role="tab" data-toggle="tab"><?php echo lang("dashboard_pagos_en_proceso"); ?></a>
                        </li>
                    <?php endif; ?>
                        
                        
                    <?php if($this->seguridad->verificarAcceso("dashboard", "listadoPeriodosTerminadosAjax")): ?>
                        <li class="active" onclick="dashboard.initListadoTerminados();">
                            <a href="#pagos-terminados" role="tab" data-toggle="tab"><?php echo lang("dashboard_pagos_terminados"); ?></a>
                        </li>
                    <?php endif; ?>
                        
                        
                </ul>

                <div class="tab-content">
                    
                    <?php if ($this->seguridad->verificarAcceso("dashboard", "listadoPeriodosNuevosAjax")): ?>
                        <div class="tab-pane active" id="pagos-nuevos">

                            <h4 style="text-align: center;"><?php echo lang("dashboard_pagos_nuevos"); ?></h4>
                            <table width="100%" cellpadding="2" cellspacing="1" class="data-grid">
                                    <tr>
                                        <td>
                                            <div class="ui-corner-all envoltura-tabla">
                                                <table cellpadding="0" cellspacing="0" border="0" class="display" id="listado-pagos-nuevos">
                                                    <thead>
                                                        <tr>
                                                            <th><?php echo lang('pagos_numero'); ?></th>
                                                            <th><?php echo lang('pagos_periodo'); ?></th>
                                                            <th><?php echo lang('pagos_fecha_inicio'); ?></th>
                                                            <th><?php echo lang('pagos_fecha_fin'); ?></th>
                                                            <th><?php echo lang('pagos_persepciones'); ?></th>
                                                            <th><?php echo lang('pagos_deducciones'); ?></th>
                                                            <th><?php echo lang('pagos_neto_a_pagar'); ?></th>
                                                            <th><?php echo lang('pagos_numero_empleado'); ?></th>
                                                            <th><?php echo lang('general_acciones') ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>																	
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($this->seguridad->verificarAcceso("dashboard", "listadoPeriodosProcesoAjax")): ?>
                        <div class="tab-pane" id="pagos-en-proceso">
                            <h4 style="text-align: center;"><?php echo lang("dashboard_pagos_en_proceso"); ?></h4>
                            
                            <table width="100%" cellpadding="2" cellspacing="1" class="data-grid">
                                    <tr>
                                        <td>
                                            <div class="ui-corner-all envoltura-tabla">
                                                <table cellpadding="0" cellspacing="0" border="0" class="display" id="listado-pagos-en-proceso">
                                                    <thead>
                                                        <tr>
                                                            <th><?php echo lang('pagos_numero'); ?></th>
                                                            <th><?php echo lang('pagos_periodo'); ?></th>
                                                            <th><?php echo lang('pagos_fecha_inicio'); ?></th>
                                                            <th><?php echo lang('pagos_fecha_fin'); ?></th>
                                                            <th><?php echo lang('pagos_persepciones'); ?></th>
                                                            <th><?php echo lang('pagos_deducciones'); ?></th>
                                                            <th><?php echo lang('pagos_neto_a_pagar'); ?></th>
                                                            <th><?php echo lang('pagos_numero_empleado'); ?></th>
                                                            <th><?php echo lang('general_acciones') ?></th>
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

                    <?php endif; ?>
                    
                    <?php if ($this->seguridad->verificarAcceso("dashboard", "listadoPeriodosTerminadosAjax")): ?>
                        <div class="tab-pane active" id="pagos-terminados">

                            <h4 style="text-align: center;"><?php echo lang("dashboard_pagos_terminados"); ?></h4>
                            <table width="100%" cellpadding="2" cellspacing="1" class="data-grid">
                                    <tr>
                                        <td>
                                            <div class="ui-corner-all envoltura-tabla">
                                                <table cellpadding="0" cellspacing="0" border="0" class="display" id="listado-pagos-terminados">
                                                    <thead>
                                                        <tr>
                                                            <th><?php echo lang('pagos_numero'); ?></th>
                                                            <th><?php echo lang('pagos_periodo'); ?></th>
                                                            <th><?php echo lang('pagos_fecha_inicio'); ?></th>
                                                            <th><?php echo lang('pagos_fecha_fin'); ?></th>
                                                            <th><?php echo lang('pagos_persepciones'); ?></th>
                                                            <th><?php echo lang('pagos_deducciones'); ?></th>
                                                            <th><?php echo lang('pagos_neto_a_pagar'); ?></th>
                                                            <th><?php echo lang('pagos_numero_empleado'); ?></th>
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
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- End Content -->
    </section>
</div>

<!-- FIN CONTENIDO DEL CATALOGO  -->
<?php $this->load->view("dashboard/detalle_empleado_modal_view");  ?>

<!--  modales  -->
<?php $this->load->view("avisos/forma_modal_view"); ?>

<!-- INICIA AREA JAVASCRIPT -->
<?php $this->load->view("layout/javascript_base_view"); ?>

<!-- FIN AREA JAVASCRIPT -->
<?php incluye_componente_datatables(); ?>
<?php incluye_componente_validate(); ?>
<?php script_tag("plugins/ajaxForm/jquery.ajaxForm"); ?>
<?php script_tag("modulos/dashboard"); ?>
<?php script_tag("modulos/avisos"); ?>

<script type="text/javascript">    
    var acciones = {        
        "procesarPago": "<?php echo incluye_icono("configurar", lang("dashboard_accion_procesar_nomina"), "float:left;", "#", "onclick=\'dashboard.configurarPagoAjax(@@@@,????)\'", true, "configurarPagoAjax", "nomina"); ?>",
    };
    
    <?php if ($this->seguridad->verificarAcceso("dashboard", "listadoPeriodosNuevosAjax")): ?>
        var dataTableNuevosUI = new CoreUI.CacheDataTable({
            delay: 500,
            complete: function (json) {
                if (json.noLogin == 1) {
                    dashboard.irIniciarSesion();
                }
            }
        }); 
    <?php endif; ?>
    
    <?php if ($this->seguridad->verificarAcceso("dashboard", "listadoPeriodosProcesoAjax")): ?>
        var dataTableProcesoUI = new CoreUI.CacheDataTable({
            delay: 800,
            complete: function (json) {
                if (json.noLogin == 1) {
                    dashboard.irIniciarSesion();
                }
            }
        });
    <?php endif; ?>
    
    <?php if ($this->seguridad->verificarAcceso("dashboard", "listadoPeriodosTerminadosAjax")): ?>
        var dataTableTerminadoUI = new CoreUI.CacheDataTable({
            delay: 500,
            complete: function (json) {
                if (json.noLogin == 1) {
                    dashboard.irIniciarSesion();
                }
            }
        });
    <?php endif; ?>

    <?php if ($this->seguridad->verificarAcceso("dashboard", "listadoPeriodosNuevosAjax")): ?>
        var oTablePagosNuevos = true;
    <?php endif; ?>
        
    <?php if ($this->seguridad->verificarAcceso("dashboard", "listadoPeriodosProcesoAjax")): ?>
        var oTablePagosEnProceso = true;
    <?php endif; ?>
        
    <?php if ($this->seguridad->verificarAcceso("dashboard", "listadoPeriodosTerminadosAjax")): ?>
        var oTablePagosTerminados = true;
    <?php endif; ?>
    
    dashboard.TEXT_BTN_DETALLES = "<?php echo lang("general_accion_detalles"); ?>";
    dashboard.TEXT_NO_HAY_EMPLEADOS = "<?php echo lang("dashboard_mensaje_no_hay_empleados");  ?>";
    
    $(document).ready(function () {
        $("#forma-buscar-empleado").on('submit', function(e){
            e.preventDefault();
        });
        dashboard.initDashboard();
    });

</script>