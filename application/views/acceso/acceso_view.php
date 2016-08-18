<!-- 

Vista de acceso al sistema

@author DEVELOPER 1 <correo@developer1>
@created 19-12-2014

-->

<div style="text-align: center;margin-bottom: 30px;">
    <img src="<?php echo base_url(DIRECTORIO_CONFIGURACION . ConfigHelper::get("cLogo")); ?>" alt="<?php echo lang("general_empresa"); ?>" width="300"/>
</div>

<div id="element-box" class="login well" style="text-align: center">
    <!-- INICIA AREA FORMULARIO DE ACCESO -->
    
    <div id="divAcceder">
        
        <h3><?php echo lang("acceso_titulo"); ?></h3>
        <p><?php echo lang("acceso_instrucciones"); ?></p>
        
        <hr />
        
        <?php echo form_open("acceso/ingresar", array("id" => "forma-acceso", "class" => "form-inline")); ?>
        <fieldset class="loginform">
            <div class="control-group">
                <div class="controls">
                    <div class="input-prepend input-append">
                        <span class="add-on" style="height:30px;">
                            <i class="icon-user hasTooltip" title="Username" style="margin-top: 10px;"></i>
                        </span>
                        <?php echo form_input('cCorreo', '', 'id="cCorreo" placeholder="' . lang("acceso_email") . '" class="input-xlarge" style="height:30px;"'); ?>
                        <a href="javascript:void(0);" class="btn width-auto  show-tooltip" title="<?php echo lang("acceso_email_help"); ?>" style="height:30px;">
                            <i class="icon-help" style="margin-top: 10px;"></i>
                        </a>
                    </div>
                </div>
                <label id="cCorreo-error" class="error" for="cCorreo" style="display: none; text-align: left; width: 100%;"></label>
            </div>
            
            <div class="control-group">
                <div class="controls">
                    <div class="input-prepend input-append">
                        <span class="add-on" style="height:30px;">
                            <i class="icon-lock hasTooltip" title="Password" style="margin-top: 10px;"></i>
                        </span>
                        <?php echo form_password('cContrasena', '', 'id="cContrasena" placeholder="' . lang("acceso_contrasena") . '" class=" input-xlarge" maxlength="45" style="height:30px;"'); ?>
                        <a href="javascript:void(0);" class="btn width-auto show-tooltip" title="<?php echo lang("acceso_contrasena_help"); ?>" style="height:30px;">
                            <i class="icon-help" style="margin-top: 10px;"></i>
                        </a>
                    </div>
                </div>
                <label id="cContrasena-error" class="error" for="cContrasena" style="display: none; text-align: left; width: 100%;"></label>
            </div>
            
            <div class="control-group">
                <div class="controls">
                    <div class=" pull-left" style="margin-top: 35px;">
                        <a href="javascript:acceso.mostrarRecuperar();" ><?php echo lang("acceso_olvidar_contrasena"); ?></a>
                    </div>
                    <div class=" pull-right">
                        <button type="button" class="btn btn-primary btn-large" onclick="acceso.btnAcceder();">
                            <i class="icon-lock icon-white"></i> <?php echo lang("acceso_entrar"); ?>				
                        </button>
                    </div>
                </div>
            </div>
        </fieldset>
        <?php echo form_close(); ?>
    </div>
    <!-- FIN AREA FORMULARIO DE ACCESO -->
    
    <!-- INICIA AREA FORMULARIO DE RECUPERAR CONTRASENA -->
    <div id="divRecuperar" style="display: none;">
        
        <h3><?php echo lang("acceso_restablecer_titulo"); ?></h3>
        <p><?php echo lang("acceso_restablecer_instrucciones"); ?></p>
        
        <hr />
            
        <?php echo form_open("acceso/restablecerContrasenaAjax", array("id" => "forma-restablecer-contrasena", "class" => "form-inline")); ?>
        <fieldset class="loginform">
            <div class="control-group">
                <div class="controls">
                    <div class="input-prepend input-append">
                        <span class="add-on" style="height:30px;">
                            <i class="icon-user hasTooltip" title="cCorreo" style="margin-top: 10px;"></i>
                        </span>
                        <?php echo form_input('cCorreo', '', 'id="cCorreoRecuperar" placeholder="' . lang("acceso_email") . '" class="input-xlarge required" style="height:30px;"'); ?>
                        <a href="#" class="btn width-auto hasTooltip show-tooltip" title="<?php echo lang("acceso_restablecer_email_help"); ?>" style="height:30px;">
                            <i class="icon-help" style="margin-top: 10px;"></i>
                        </a>
                    </div>
                </div>
                <label id="cCorreoRecuperar_validate" class="error" for="cCorreoRecuperar" style="display: none; text-align: left; width: 100%;"></label>
            </div>

            <div class="control-group">
                <div class="controls">
                    <div class=" pull-left" style="margin-top: 35px;">
                        <a href="javascript:acceso.mostrarAcceso();" style="margin-top: 30px;"><?php echo lang("acceso_acceder_sistema"); ?></a>
                    </div>
                    <div class="pull-right">
                        <button type="button" class="btn btn-primary btn-large" onclick="acceso.btnRestablecer();">
                            <i class="icon-arrow-right icon-white"></i> <?php echo lang("acceso_enviar"); ?>				
                        </button>
                    </div>
                </div>
            </div>
        </fieldset>
        <?php echo form_close(); ?>
    </div>
    <!-- FIN AREA FORMULARIO DE RECUPERAR CONTRASENA -->
</div>

<!-- INICIA MENSAJE DE USUARIO HABILITAR JAVASCRIPT -->
<noscript>
<?php echo lang("general_noscript");  ?>		
</noscript>
<!-- FIN MENSAJE DE USUARIO HABILITAR JAVASCRIPT -->

<!-- INICIA AREA JAVASCRIPT -->
<?php $this->load->view("layout/javascript_base_view"); ?>
<?php incluye_componente_validate(); ?>

<?php script_tag("plugins/ajaxForm/jquery.ajaxForm"); ?>
<?php script_tag("modulos/acceso"); ?>

<script type="text/javascript">
    $(document).ready(function() {
        acceso.initForma({
            'paramsForma': {
               rules:{
                   cCorreo:{
                       required:true,
                       email:true
                   },
                   cContrasena:{
                       required:true,
                   }
               },
               errorPlacement: function (error, element) {
                    var name = $(element).attr("name");
                    error.appendTo($("#" + name + "_validate"));
               },
            },
            'paramsFormaRecuperar': {
               rules:{
                   cCorreoRecuperar:{
                       required:true,
                       email:true
                   },
               },
               errorPlacement: function (error, element) {
                    var name = $(element).attr("name");
                    error.appendTo($("#" + name + "_validate"));
               },
            }
        });
    });
</script>
<!-- FIN AREA JAVASCRIPT -->
