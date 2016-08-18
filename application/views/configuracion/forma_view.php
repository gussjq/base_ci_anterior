<!-- 

Vista de forma del catalogo configuracion del sistema

@author DEVELOPER 1 <correo@developer1> cel <1111111111>
@creado 29-12-2014

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
                            <button onclick="configuracion.btnGuardar();" class="btn btn-small btn-success">
                                <span class="icon-new icon-white"></span>
                                <?php echo lang('general_accion_guardar'); ?>
                            </button>
                        </div>

                        <div class="btn-wrapper" id="toolbar-cancel">
                            <button onclick="configuracion.btnRegresar();" class="btn btn-small">
                                <span class="icon-cancel"></span>
                                <?php echo lang('general_accion_cancelar'); ?>
                            </button>
                        </div>
                        <!-- FIN PARTE IZQUIERDA  -->

                        <!-- INICIA PARTE DERECHA  -->
                        <div class="btn-wrapper" id="toolbar-help">

                        </div>
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
                            <div class="texto-encabezado"><?php echo lang('configuracion_titulo'); ?> </div>
                        </div>
                    </div>
                </div>
                
                <!-- INICIO AREA DEL FORMULARIO  -->
                <?php echo form_open("configuracion/actualizar", "id=\"forma-configuracion\" class=\"form-horizontal\""); ?>
                <div class="row-fluid" >
                    <ul class="nav nav-tabs">
                        <li class="active">
                           <a href="#empresa" role="tab" data-toggle="tab"><?php echo lang("configuracion_empresa"); ?></a>
                       </li>
                        
                       <li>
                           <a href="#general" role="tab" data-toggle="tab"><?php echo lang("configuracion_sistema"); ?></a>
                       </li>
                       
                       <li>
                           <a href="#correo" role="tab" data-toggle="tab"><?php echo lang("configuracion_servidor_smtp_pestana"); ?></a>
                       </li>
                    </ul>
                    
                    <div class="tab-content">
                        <div class="tab-pane active" id="empresa">
                            <fieldset>
                                <legend><?php echo lang("configuracion_general_empresa"); ?></legend>
                                
                                <div class="row-fluid" >
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label" for="cNomnbreEmpresa"><span class="requerido">*</span> <?php echo lang("configuracion_general_nombre_empresa"); ?> :</label>
                                            <div class="controls">
                                                <?php echo form_input("cNombreEmpresa", ConfigHelper::getCache('cNombreEmpresa', $aConfiguracion), "id=\"cNombreEmpresa\"class=\"input-xlarge maxlength=\"255\" "); ?> 
                                                <span class="help-block"><?php echo lang("configuracion_general_nombre_empresa_descripcion"); ?></span>
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label" for="cLogo"><span class="requerido">*</span> <?php echo lang("configuracion_genelar_logo_empresa"); ?> :</label>
                                            <div class="controls">
                                                <?php echo form_upload("cLogoUpload", "", "id=\"cLogoUpload\" class=\"input-large\" maxlength=\"45\""); ?>
                                                <input type="hidden" id="cLogo" name="cLogo" value="<?php echo ConfigHelper::getCache('cLogo', $aConfiguracion); ?>" /> 
                                                <span class="help-block"><?php echo lang("configuracion_genelar_logo_empresa_descripcion"); ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="span6">
                                        <div id="contenedor-archivo">
                                            <?php if (!empty($cRutaLogo)): ?>
                                                <table><tr><td><img id="cImgUpload" src="<?php echo $cRutaLogo; ?> "  width="159" height="159" class="img-polaroid"/></td></tr></table>
                                                <span class="help-block"></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row-fluid">
                                    <div class="span12">
                                        <div class="control-group">
                                            <label class="control-label" for="cRazonSocial"><span class="requerido">*</span> <?php echo lang("configuracion_razon_social"); ?> :</label>
                                            <div class="controls">
                                                <?php echo form_input("cRazonSocial", ConfigHelper::getCache('cRazonSocial', $aConfiguracion), "id=\"cRazonSocial\"class=\"input-xlarge validate[required,maxSize[255]]\" maxlength=\"255\""); ?> 
                                                <span class="help-block "><?php echo lang("configuracion_razon_social_help"); ?></span>
                                            </div>
                                        </div>
                                        
                                        <div class="control-group">
                                            <label class="control-label" for="cCurp"><?php echo lang("configuracion_curp"); ?> :</label>
                                            <div class="controls">
                                                <?php echo form_input("cCurp", ConfigHelper::getCache('cCurp', $aConfiguracion), "id=\"cCurp\"class=\"input-xlarge validate[required, maxSize[45], custom[validCurp]]\" maxlength=\"45\""); ?>
                                                <span class="help-block"><?php echo lang("configuracion_curp_help"); ?></span>
                                            </div>
                                        </div>
                                        
                                        <div class="control-group">
                                            <label class="control-label" for="cResponsableLegal"><?php echo lang("configuracion_responsable_legal"); ?> :</label>
                                            <div class="controls">
                                                <?php echo form_input("cResponsableLegal", ConfigHelper::getCache('cResponsableLegal', $aConfiguracion), "id=\"cRepresentanteLegal\"class=\"input-xlarge validate[maxSize[45]]\" maxlength=\"45\""); ?>
                                                <span class="help-block "><?php echo lang("configuracion_responsable_legal_help"); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        
                        <div class="tab-pane" id="general">
                            <fieldset>
                                <legend><?php echo lang("configuracion_general_sistema"); ?></legend>
                                <div class="row-fluid" >
                                    <div class="span12">
                                        <div class="control-group">
                                            <label class="control-label" for="idIdioma"><span class="requerido">*</span> <?php echo lang("configuracion_general_idioma"); ?> :</label>
                                            <div class="controls">
                                                <?php echo form_dropdown("idIdioma", $aCombosForma["aIdiomas"], ConfigHelper::getCache('idIdioma', $aConfiguracion), "id=\"idIdioma\" class=\"validate[required]\""); ?>
                                                <span class="help-block"><?php echo lang("configuracion_general_idioma_descripcion"); ?></span>
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label" for="iMaxContrasena"><span class="requerido">*</span> <?php echo lang("configuracion_general_max_contrasena"); ?> :</label>
                                            <div class="controls">
                                                <?php echo form_input("iMaxContrasena", ConfigHelper::getCache('iMaxContrasena', $aConfiguracion), "id=\"iMaxContrasena\" maxlength=\"3\" class=\"validate[required, custom[integer]] input-mini\""); ?>
                                                <span class="help-block"><?php echo lang("configuracion_general_max_contrasena_descripcion"); ?></span>
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label" for="iMinContrasena"><span class="requerido">*</span> <?php echo lang("configuracion_general_min_contrasena"); ?> :</label>
                                            <div class="controls">
                                                <?php echo form_input("iMinContrasena", ConfigHelper::getCache('iMinContrasena', $aConfiguracion), "id=\"iMinContrasena\" maxlength=\"3\" class=\"validate[required, custom[integer]]] input-mini\""); ?>
                                                <span class="help-block"><?php echo lang("configuracion_general_min_contrasena_descripcion"); ?></span>
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label" for="iIntentosAcceso"><span class="requerido">*</span> <?php echo lang("configuracion_general_intentos_acceso"); ?> :</label>
                                            <div class="controls">
                                                <?php echo form_input("iIntentosAcceso", ConfigHelper::getCache('iIntentosAcceso', $aConfiguracion), "id=\"iIntentosAcceso\"class=\"input-mini validate[required, custom[integer]]\" maxlength=\"3\""); ?>
                                                <span class="help-block"><?php echo lang("configuracion_general_intentos_acceso_descripcion"); ?></span>
                                            </div>
                                        </div>
                                        
                                        <div class="control-group">
                                            <label class="control-label" for="iMinutosIntentosAcceso"><span class="requerido">*</span> <?php echo lang("configuracion_general_minutos_acceso"); ?> :</label>
                                            <div class="controls">
                                                <?php echo form_input("iMinutosIntentosAcceso", ConfigHelper::getCache('iMinutosIntentosAcceso', $aConfiguracion), "id=\"iMinutosIntentosAcceso\"class=\"input-mini validate[required,custom[integer],maxSize[3]]\" maxlength=\"3\""); ?>
                                                <span class="help-block"><?php echo lang("configuracion_general_minutos_acceso_descripcion"); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        
                        <div class="tab-pane" id="correo">
                            <fieldset>
                                <legend><?php echo lang("configuracion_servidor_smtp_datos"); ?></legend>
                                <div class="control-group">
                                    <label class="control-label" for="cServidorSmtp"><span class="requerido">*</span> <?php echo lang("configuracion_servidor_smtp_servidor"); ?> :</label>
                                    <div class="controls">
                                        <?php echo form_input("cServidorSmtp", ConfigHelper::getCache('cServidorSmtp', $aConfiguracion), "id=\"cServidorSmtp\"class=\"input-large validate[required,maxSize[45]]\" maxlength=\"45\""); ?>
                                        <span class="help-block"><?php echo lang("configuracion_servidor_smtp_servidor_descripcion"); ?></span>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="cCorreoSmtp"><span class="requerido">*</span> <?php echo lang("configuracion_servidor_smtp_correo"); ?> :</label>
                                    <div class="controls">
                                        <?php echo form_input("cCorreoSmtp", ConfigHelper::getCache('cCorreoSmtp', $aConfiguracion), "id=\"cCorreoSmtp\"class=\"input-large validate[required,maxSize[45], custom[email]]\" maxlength=\"45\""); ?>
                                        <span class="help-block"><?php echo lang("configuracion_servidor_smtp_correo_descripcion"); ?></span>
                                    </div>
                                </div>  

                                <div class="control-group">
                                    <label class="control-label" for="cPasswordSmtp"><span class="requerido">*</span> <?php echo lang("configuracion_servidor_smtp_password"); ?> :</label>
                                    <div class="controls">
                                        <?php echo form_input("cPasswordSmtp", ConfigHelper::getCache('cPasswordSmtp', $aConfiguracion), "id=\"cPasswordSmtp\"class=\"input-large validate[required, maxSize[45]]\" maxlength=\"45\""); ?>
                                        <span class="help-block"><?php echo lang("configuracion_servidor_smtp_password_descripcion"); ?></span>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="cPuertoSmtp"><span class="requerido">*</span> <?php echo lang("configuracion_servidor_smtp_puerto"); ?> :</label>
                                    <div class="controls">
                                        <?php echo form_input("cPuertoSmtp", ConfigHelper::getCache('cPuertoSmtp', $aConfiguracion), "id=\"cPuertoSmtp\"class=\"input-mini validate[required, custom[integer],maxSize[10]]\" maxlength=\"10\""); ?>
                                        <span class="help-block"><?php echo lang("configuracion_servidor_smtp_puerto_descripcion"); ?></span>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="bSslSmtp"><span class="requerido">*</span> <?php echo lang("configuracion_servidor_smtp_ssl"); ?> :</label>
                                    <div class="controls">
                                        <?php echo form_dropdown("bSslSmtp", $aCombosForma['si_no'], ConfigHelper::getCache('bSslSmtp', $aConfiguracion), "id=\"bSslSmtp\"class=\"input-large\""); ?>
                                        <span class="help-block"><?php echo lang("configuracion_servidor_smtp_ssl_descripcion"); ?></span>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="cProtocolSmtp"><span class="requerido">*</span> <?php echo lang("configuracion_servidor_smtp_protocolo"); ?> :</label>
                                    <div class="controls">
                                        <?php echo form_dropdown("cProtocolSmtp", $aCombosForma["aProtocolos"], ConfigHelper::getCache('cProtocolSmtp', $aConfiguracion), "id=\"cProtocolSmtp\" class=\"validate[required]\""); ?>
                                        <span class="help-block"><?php echo lang("configuracion_servidor_smtp_protocolo_descripcion"); ?></span>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset>
                                <legend><?php echo lang("configuracion_servidor_smtp_formato_datos"); ?></legend>
                                <div class="control-group">
                                    <label class="control-label" for="cMailTypeSmtp"><span class="requerido">*</span> <?php echo lang("configuracion_servidor_smtp_tipo_correo"); ?> :</label>
                                    <div class="controls">
                                        <?php echo form_dropdown("cMailTypeSmtp", $aCombosForma["aTipoCorreos"], ConfigHelper::getCache('cMailTypeSmtp', $aConfiguracion), "id=\"cMailTypeSmtp\" class=\"validate[required]\""); ?>
                                        <span class="help-block"><?php echo lang("configuracion_servidor_smtp_descripcion_tipo_correo"); ?></span>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="cCharsetSmtp"><span class="requerido">*</span> <?php echo lang("configuracion_servidor_smtp_charset"); ?> :</label>
                                    <div class="controls">
                                        <?php echo form_input("cCharsetSmtp", ConfigHelper::getCache('cCharsetSmtp', $aConfiguracion), "id=\"cCharsetSmtp\"class=\"input-large validate[required, maxSize[45]]\" maxlength=\"45\""); ?>
                                        <span class="help-block" ><?php echo lang("configuracion_servidor_smtp_charset_descripcion"); ?> </span>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="iTimeOutSmtp"><span class="requerido">*</span> <?php echo lang("configuracion_servidor_smtp_tiepo_envio"); ?> :</label>
                                    <div class="controls">
                                        <?php echo form_input("iTimeOutSmtp", ConfigHelper::getCache('iTimeOutSmtp', $aConfiguracion), "id=\"iTimeOutSmtp\"class=\"input-mini validate[required,custom[integer], maxSize[3]\" maxlength=\"3\""); ?>
                                        <span class="help-block" ><?php echo lang("configuracion_servidor_smtp_tiepo_envio_descripcion"); ?> </span>
                                    </div>
                                </div>

                            </fieldset>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
                <!-- FIN AREA DEL FORMULARIO  -->
                
<!--                <div class="row-fluid">
                    <div class="span12">
                        <span class="requerido-help"><?php echo lang("general_datos_requeridos"); ?></span>
                    </div>
                </div>-->
            </div>
        </div>
    </section>
</div>
<!-- FIN CONTENIDO DEL CATALOGO  -->

<!-- INICIO AREA JAVASCRIPT  -->
<?php $this->load->view("layout/javascript_base_view"); ?>

<?php incluye_componente_validate(); ?>
<?php incluye_componente_uploadify(); ?>
<?php incluye_componente_growl(); ?>
<?php script_tag("plugins/ajaxForm/jquery.ajaxForm"); ?>
<?php script_tag("modulos/configuracion"); ?>


<script type="text/javascript">
   $(document).ready(function(){
      configuracion.initForma({
              'paramsForma':{             
                  rules:{
                      cNombreEmpresa:{
                          required:true,
                      },
                      cLogo:"required",
                      cCurp:{
                          required:false,
                          curp:true
                      },
                      cRazonSocial:"required",
                      idIdioma:"required",
                      iMaxContrasena:{
                          required:true,
                          is_natural_no_zero:true,
                          maxlength:3
                      },
                      iMinContrasena:{
                          required:true,
                          is_natural_no_zero:true,
                          maxlength:3
                      },
                      iIntentosAcceso:{
                          required:true,
                          is_natural_no_zero:true,
                          maxlength:3
                      },
                      iMinutosIntentosAcceso:{
                          required:true,
                          maxlength:3,
                          is_natural_no_zero:true,
                      },
                      cServidorSmtp:"required",
                      cCorreoSmtp:{
                          required:true,
                          email:true,
                      },
                      cPasswordSmtp:"required",
                      cPuertoSmtp:{
                          required:true,
                          is_natural_no_zero:true,
                          integer:true,
                      },
                      cProtocolSmtp:"required",
                      cMailTypeSmtp:"required",
                      cCharsetSmtp:"required",
                      iTimeOutSmtp:{
                          required:true,
                          is_natural_no_zero:true,
                          maxlength:3
                      },
                  }
              },
      });
   });
</script>
<!-- FIN AREA JAVASCRIPT  -->