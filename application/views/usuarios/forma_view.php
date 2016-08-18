<!-- 

Vista de forma del catalogo Usuario

@author DEVELOPER 1 <correo@developer1> cel <1111111111>
@created 29-12-2014

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
                            <button onclick="usuario.btnGuardar();" class="btn btn-small btn-success">
                                <span class="icon-new icon-white"></span>
                                <?php echo lang('general_accion_guardar'); ?>
                            </button>
                        </div>

                        <div class="btn-wrapper" id="toolbar-cancel">
                            <button onclick="usuario.btnCancelar();" class="btn btn-small">
                                <span class="icon-cancel"></span>
                                <?php echo lang('general_accion_cancelar'); ?>
                            </button>
                        </div>
                        <!-- FIN PARTE IZQUIERDA  -->

                        
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
                            <div class="texto-encabezado"><?php echo lang('usuario_titulo'); ?> </div>
                        </div>
                    </div>
                </div>
                
                <!-- INICIO AREA DEL FORMULARIO  -->
                <div class="row-fluid" >
                    <?php echo form_open("usuarios/insertar", "id=\"forma-usuarios\" class=\"form-horizontal\""); ?>
                    <input type="hidden" id="id" name="idUsuario" value="<?php echo $oUsuario->idUsuario; ?>" />

                    <fieldset>
                        <legend><?php echo lang("usuario_datos_usuario"); ?></legend>
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label" for="cNombre"><?php echo lang("usuario_nombre"); ?> :</label>
                                    <div class="controls">
                                        <?php echo form_input("cNombre", $oUsuario->cNombre, "id=\"cNombre\"class=\"input-large validate[required, maxSize[45]\" maxlength=\"45\""); ?>
                                        <span class="help-block"><?php echo lang("usuario_nombre_help"); ?></span>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="cApellidoPaterno"><?php echo lang("usuario_apellido_paterno"); ?> :</label>
                                    <div class="controls">
                                        <?php echo form_input("cApellidoPaterno", $oUsuario->cApellidoPaterno, "id=\"cApellidoPaterno\"class=\"input-large validate[required, maxSize[45]\" maxlength=\"45\""); ?>
                                        <span class="help-block"><?php echo lang("usuario_apellido_paterno_help"); ?></span>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="cApellidoMaterno"><?php echo lang("usuario_apellido_materno"); ?> :</label>
                                    <div class="controls">
                                        <?php echo form_input("cApellidoMaterno", $oUsuario->cApellidoMaterno, "id=\"cApellidoMaterno\"class=\"input-large validate[required, maxSize[45]\" maxlength=\"45\""); ?>
                                        <span class="help-block"><?php echo lang("usuario_apellido_materno_help"); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                <div id="contenedor-archivo" style="margin:0 0 5px 120px;">
                                    <table><tr><td><img id="cImgUpload" src="<?php echo $cRutaImagen; ?> "  width="159" height="159" class="img-polaroid"/></td></tr></table>
                                    <span class="help-block"></span>
                                </div>
                                <label class="control-label" for="cImagen"><?php echo lang("usuario_imagen"); ?> :</label>
                                <div class="controls">
                                    <?php echo form_upload("cImagenUpload", "", "id=\"cImagenUpload\" class=\"input-large\" maxlength=\"45\""); ?>
                                    <input type="hidden" id="cImagen" name="cImagen" value="<?php echo $oUsuario->cImagen; ?>" />
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend><?php echo lang("usuario_datos_cuenta"); ?></legend>
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="control-group">
                                    <label class="control-label" for="idRol"><?php echo lang("usuario_rol"); ?> :</label>
                                    <div class="controls">
                                        <?php echo form_dropdown("idRol", $aCombosForma['aRoles'], $oUsuario->idRol, "id=\"idRol\"class=\"input-large validate[required]\" "); ?>
                                        <span class="help-block"><?php echo lang("usuario_rol_help"); ?></span>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="cCorreo"><?php echo lang("usuario_correo"); ?> :</label>
                                    <div class="controls">
                                        <?php echo form_input("cCorreo", $oUsuario->cCorreo, "id=\"cCorreo\"class=\"input-large validate[required, maxSize[45], custom[email]\" maxlength=\"45\""); ?>
                                        <span class="help-block"><?php echo lang("usuario_correo_help"); ?></span>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="idIdioma"><?php echo lang("usuario_idioma"); ?> :</label>
                                    <div class="controls">
                                        <?php echo form_dropdown("idIdioma", $aCombosForma['aIdiomas'], $oUsuario->idIdioma, "id=\"idIdioma\"class=\"input-large validate[required]\" "); ?>
                                        <span class="help-block"><?php echo lang("usuario_idioma_help"); ?></span>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="cContrasena"><?php echo lang("usuario_contrasena"); ?> :</label>
                                    <div class="controls">
                                        <?php echo form_password("cContrasena", "", "id=\"cContrasena\"class=\"input-large\" maxlength=\"" . (ConfigHelper::get('iMaxContrasena')) . "\""); ?>
                                        <span class="help-block"><?php echo lang("usuario_contrasena_help"); ?></span>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="cConfirmarContrasena"><?php echo lang("usuario_confirmar_contrasena"); ?> :</label>
                                    <div class="controls">
                                        <?php echo form_password("cConfirmarContrasena", "", "id=\"cConfirmarContrasena\"class=\"input-large\" maxlength=\"" . (ConfigHelper::get('iMaxContrasena')) . "\""); ?>
                                        <span class="help-block"><?php echo lang("usuario_confirmar_contrasena_help"); ?></span>
                                    </div>
                                </div>
                                
                                <div class="control-group">
                                    <label class="control-label" for="bAdministrador"><?php echo lang("usuario_es_administrador"); ?> :</label>
                                    <div class="controls">
                                        <?php echo form_checkbox("bAdministrador", SI, $oUsuario->bAdministrador, "id=\"bAdministrador\"class=\"input-large\" "); ?>
                                        <span class="help-block"><?php echo lang("usuario_es_administrador_help"); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <?php echo form_close(); ?>
                </div>
                <!-- FIN AREA DEL FORMULARIO  -->
            </div>
        </div>
    </section>
</div>
<!-- FIN CONTENIDO DEL CATALOGO  -->

<!-- INICIO AREA JAVASCRIPT  -->
<?php $this->load->view("layout/javascript_base_view"); ?>

<?php incluye_componente_validate(); ?>
<?php incluye_componente_uploadify(); ?>
<?php script_tag("plugins/ajaxForm/jquery.ajaxForm"); ?>
<?php script_tag("modulos/usuarios"); ?>

<script type="text/javascript">

    $(document).ready(function() {
        usuario.initForma({
            'paramsForma': {
                rules: {
                    cNombre: {
                        required: true,
                    },
                    cApellidoPaterno: {
                        required: true,
                    },
                    cApellidoMaterno: {
                        required: true,
                    },
                    idRol: {
                        required: true,
                    },
                    idIdioma: {
                        required: true,
                    },
                    cCorreo: {
                        required: true,
                        email: true
                    },
                    cContrasena: {
                        required: false,
                        maxlength:<?php echo ConfigHelper::get("iMaxContrasena"); ?>,
                        minlength:<?php echo ConfigHelper::get("iMinContrasena"); ?>
                    },
                    cConfirmarContrasena: {
                        required: false,
                        equalTo: "#cContrasena"
                    }
                }

            }
        });
    });
</script>
<!-- FIN AREA JAVASCRIPT  -->