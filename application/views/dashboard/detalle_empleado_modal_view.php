<div id="<?php echo (isset($cNombreModal)) ? "my-modal-empleado-detalle-" . $cNombreModal : "my-modal-empleado-detalle";  ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="<?php echo (isset($cNombreModal)) ? "my-modal-empleado-detalle-" . $cNombreModal : "my-modal-empleado-detalle";  ?>Label" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="<?php echo (isset($cNombreModal)) ? "my-modal-empleado-detalle-" . $cNombreModal : "my-modal-empleado-detalle";  ?>Label"><?php echo lang("dashboard_empleado_detalle");  ?></h3>
    </div>
    
    <div class="modal-body">
        <div class="row-fluid" >
            <div class="span12">
                
                <?php echo form_open("empleados/insertar", "id=\"forma-empleados\" class=\"form-horizontal\""); ?>
               
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#personales" role="tab" data-toggle="tab" ><?php echo lang("empleados_tab_personales"); ?></a>
                    </li>
                    
                    <li >
                        <a href="#ingreso" role="tab" data-toggle="tab" ><?php echo lang("empleados_tab_ingreso"); ?></a>
                    </li>
                    
                    <li >
                        <a href="#contratacion" role="tab" data-toggle="tab" ><?php echo lang("empleados_tab_contratacion"); ?></a>
                    </li>
                    
                    <li >
                        <a href="#area" role="tab" data-toggle="tab" ><?php echo lang("empleados_tab_area"); ?></a>
                    </li>
                    
                    <li >
                        <a href="#salario" role="tab" data-toggle="tab" ><?php echo lang("empleados_tab_salario"); ?></a>
                    </li>
                    
                    <li >
                        <a href="#imss" role="tab" data-toggle="tab" ><?php echo lang("empleados_tab_imss"); ?></a>
                    </li>
                    
                    <li >
                        <a href="#infonavit" role="tab" data-toggle="tab" ><?php echo lang("empleados_tab_infonavit"); ?></a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active" id="personales">
                        <?php $this->load->view("dashboard/detalle_empleado_tab_personales_view"); ?>
                    </div>
                    
                    <div class="tab-pane" id="ingreso">
                        <?php $this->load->view("dashboard/detalle_empleado_tab_ingreso_view"); ?>
                    </div>
                    
                    <div class="tab-pane" id="contratacion">
                        <?php $this->load->view("dashboard/detalle_empleado_tab_contrato_view"); ?>
                    </div>
                    
                    <div class="tab-pane" id="area">
                        <?php $this->load->view("dashboard/detalle_empleado_tab_area_view"); ?>
                    </div>
                    
                    <div class="tab-pane" id="salario">
                        <?php $this->load->view("dashboard/detalle_empleado_tab_salario_view"); ?>
                    </div>
                    
                    <div class="tab-pane" id="imss">
                        <?php $this->load->view("dashboard/detalle_empleado_tab_imss_view"); ?>
                    </div>
                    
                    <div class="tab-pane" id="infonavit">
                        <?php $this->load->view("dashboard/detalle_empleado_tab_infonavit_view"); ?>
                    </div>

                </div>
                
                 <?php echo form_close(); ?>
                
            </div>
        </div>
    </div>
    
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal" id="<?php echo (isset($cNombreModal)) ? "idButtonDetalleEmpleadoAceptar" . $cNombreModal : "idButtonDetalleEmpleadoAceptar";  ?>"><?php echo lang("general_accion_aceptar");  ?></button>
    </div>
    
</div>
<?php echo form_close(); ?>