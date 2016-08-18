<!-- 

Vista de acceso forma restablecer

@author DEVELOPER 1 <correo@developer1> cel: <1111111111>
@creado 19-12-2014

-->

<div style="text-align: center;margin-bottom: 30px;">
    <img src="<?php echo base_url(DIRECTORIO_CONFIGURACION . ConfigHelper::get("cLogo")); ?>" alt="<?php echo lang("general_empresa"); ?>" width="300"/>
</div>


<div id="element-box" class="login well" style="text-align: center">
    <div id="Acceder">
        <h3><?php echo lang("acceso_restablecer_titulo"); ?></h3>
        <p><?php echo lang("acceso_restablecer_actualizar_instrucciones"); ?></p>

        <hr />

        <?php echo form_open("acceso/actulizarContrasena", array("id" => "forma-restablecer", "class" => "form-inline")); ?>
        <input type="hidden" id="cRecuperar" name="cRecuperar" value ="<?php echo $this->uri->segment(3); ?>" />
        <fieldset class="loginform">
            <div class="control-group">
                <div class="controls">
                    <div class="input-prepend input-append">
                        <span class="add-on" style="height:30px;">
                            <i class="icon-lock hasTooltip" title="Password" style="margin-top: 10px;"></i>
                        </span>
                        <?php echo form_password('cContrasena', '', 'id="cContrasena" placeholder="' . lang("acceso_contrasena") . '" class="login password-field input-xlarge" maxlength="45" style="height:30px;"'); ?>
                        <a href="javascript:void(0);" class="btn width-auto show-tooltip" title="<?php echo lang("acceso_restablecer_contrasena_help"); ?>" style="height:30px;">
                            <i class="icon-help" style="margin-top: 10px;"></i>
                        </a>
                    </div>
                </div>
                <label id="cContrasena_validate" class="error" for="cContrasena" style="display: block; text-align: left; width: 100%;"></label>
            </div>

            <div class="control-group">
                <div class="controls">
                    <div class="input-prepend input-append">
                        <span class="add-on" style="height:30px;">
                            <i class="icon-lock hasTooltip" title="Password" style="margin-top: 10px;"></i>
                        </span>
                        <?php echo form_password('cConfirmarContrasena', '', 'id="cConfirmarContrasena" placeholder="' . lang("acceso_restablecer_confiramar_contrasena") . '" class="login password-field input-xlarge" maxlength="45" style="height:30px;"'); ?>
                        <a href="javascript:void(0);" class="btn width-auto show-tooltip" title="<?php echo lang("acceso_restablecer_confirma_contrasena_help"); ?>" style="height:30px;">
                            <i class="icon-help" style="margin-top: 10px;"></i>
                        </a>
                    </div>
                </div>
                <label id="cConfirmarContrasena_validate" class="error" for="cConfirmarContrasena" style="display: none; text-align: left; width: 100%;"></label>
            </div>

            <div class="control-group">
                <div class="controls">
                    <div class=" pull-left" style="margin-top: 35px;">
                        <a href="<?php echo base_url("acceso/forma"); ?>" ><?php echo lang("acceso_acceder_sistema"); ?></a>
                    </div>
                    <div class=" pull-right">
                        <button type="button" class="btn btn-primary btn-large" onclick="acceso.btnActualizarContrasena();">
                            <i class="icon-apply icon-white"></i> <?php echo lang("acceso_restablecer_titulo"); ?>				
                        </button>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</div>

<?php $this->load->view("layout/javascript_base_view"); ?>
<?php incluye_componente_validate(); ?>

<?php script_tag("plugins/ajaxForm/jquery.ajaxForm"); ?>
<?php script_tag("modulos/acceso"); ?>

<script type="text/javascript">
    $(document).ready(function () {
        acceso.initFormaRestablecer({
            'paramsForma': {
                rules: {
                    cContrasena: {
                        required: true,
                        maxlength:<?php echo ConfigHelper::get("iMaxContrasena"); ?>,
                        minlength:<?php echo ConfigHelper::get("iMinContrasena"); ?>
                    },
                    cConfirmarContrasena: {
                        required: true,
                        equalTo: "#cContrasena"
                    }
                },
                errorPlacement: function (error, element) {
                    var name = $(element).attr("name");
                    error.appendTo($("#" + name + "_validate"));
               },
            }
        });
    });
</script>

</body>
</html>
