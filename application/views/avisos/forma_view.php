<!-- 

Vista de forma del catalogo de avisos

@author DEVELOPER 1 <correo@developer1> cel <1111111111>
@creado 29-12-2014

-->
<style type="text/css">
    .ui-autocomplete-loading {
        background: white url("images/ui-anim_basic_16x16.gif") right center no-repeat;
    }
    
    #contenedor-usuarios{
        border-width: 1px;
        border-style: solid;
        border-color: #f3f3f3;
        
        min-height: 50px;
        max-height: 100px;
        
        overflow-y: scroll;
    }
    
    #lista-usuarios > li {
        margin-top: 5px;
        list-style: circle;
    }
    
    .color-blanco{
        color: #fff;
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
                <div class="span12">
                    <div class="btn-toolbar" id="toolbar">
                        <!-- INICIA PARTE IZQUIERDA  -->
                        <div class="btn-wrapper" id="toolbar-new">
                            <button onclick="avisos.btnGuardar();" class="btn btn-small btn-success">
                                <span class="icon-new icon-white"></span>
                                <?php echo lang('general_accion_guardar'); ?>
                            </button>
                        </div>

                        <div class="btn-wrapper" id="toolbar-cancel">
                            <button onclick="avisos.btnCancelar();" class="btn btn-small">
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
                
                <br />
                <div class="row-fluid" >
                    <div class="span11">
                        <div class="center encabezado-catalogo">
                            <div class="texto-encabezado"><?php echo lang('avisos_titulo'); ?> </div>
                        </div>
                    </div>
                </div> 
                
                <!-- INICIO AREA DEL FORMULARIO  -->
                <?php echo form_open("avisos/insertar", "id=\"forma-avisos\" class=\"form-vertical\""); ?>
                <input type="hidden" id="id" name="idAviso" value="<?php echo $oAviso->idAviso; ?>" />

                <fieldset>
                    <legend><?php echo lang("general_datos_generales"); ?></legend>

                    <div class="row-fluid">
                        <div class="span12">
                            
                            <div class="control-group">
                                <label class="control-label" for="cTitulo"><span class="requerido">*</span> <?php echo lang("avisos_nombre_titulo"); ?> :</label>
                                <div class="controls">
                                    <?php echo form_input("cTitulo", $oAviso->cTitulo, "id=\"cTitulo\"class=\"input-xxlarge\" maxlength=\"255\""); ?>
                                    <label id="cTitulo-error" class="error" for="cTitulo" style="display: none; text-align: left; "></label>
                                    <span class="help-block"><?php echo lang("avisos_nombre_titulo_help"); ?></span>
                                </div>
                            </div>
                            
                            <div class="control-group">
                                <label class="control-label" for="aUsuarios"><span class="requerido">*</span> <?php echo lang("avisos_para"); ?> :</label>
                                <div class="controls">
                                    
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <?php echo form_radio("iTipoUsuario", AVISOS_BUSCAR_USUARIO, (($oAviso->idAviso) ? $oAviso->iTipoUsuario == AVISOS_BUSCAR_USUARIO : TRUE),"id=\"tipousuario_usuario\"class=\"input-xxlarge\" onclick=\"avisos.limpiarContenedor();\""); ?>
                                                </td>
                                                <td>
                                                    &nbsp;<label for="tipousuario_usuario"><?php echo lang("avisos_usuarios");  ?></label>
                                                </td>
                                                <td>
                                                    &nbsp;<?php echo form_radio("iTipoUsuario", AVISOS_BUSCAR_NIVEL_ACCESO, (($oAviso->idAviso) ? $oAviso->iTipoUsuario == AVISOS_BUSCAR_NIVEL_ACCESO : FALSE),"id=\"tipousuario_nivel_acceso\"class=\"input-xxlarge\" onclick=\"avisos.limpiarContenedor();\""); ?>
                                                </td>
                                                <td>
                                                    &nbsp;<label for="tipousuario_nivel_acceso"><?php echo lang("avisos_nivel_acceso");  ?></label>
                                                </td>
                                                <td>
                                                    &nbsp;<?php echo form_radio("iTipoUsuario", AVISOS_BUSCAR_TODOS, (($oAviso->idAviso) ? $oAviso->iTipoUsuario == AVISOS_BUSCAR_TODOS : FALSE),"id=\"tipousuario_todos\"class=\"input-xxlarge\" onclick=\"avisos.agregarItemTodos(this);\""); ?>
                                                </td>
                                                <td>
                                                    &nbsp;<label for="tipousuario_todos"><?php echo lang("avisos_todos");  ?></label>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    
                                    <?php echo form_input("cUsuario", "", "id=\"cUsuario\"class=\"input-xxlarge\" "); ?>
                                    <input id="cListaUsuarios" name="cListaUsuarios" type hidden="" value="" />
                                    <div id="contenedor-usuarios" class="input-xxlarge">
                                        <ul id="lista-usuarios">
                                            <?php foreach($oAviso->aUsuarios as $idUsuario): ?>
                                                <li id="li-identificador-<?php echo $idUsuario; ?>" data-identificador="<?php echo $idUsuario; ?>">
                                                    <span class="label label-info"></span>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    <label id="cListaUsuarios-error" class="error" for="cListaUsuarios" style="display: none; text-align: left; "></label>
                                    <span class="help-block"><?php echo lang("avisos_usuarios_help"); ?></span>
                                </div>
                            </div>
                            
                            <div class="control-group">
                                <label class="control-label" for="txCuerpo"><span class="requerido">*</span> <?php echo lang("avisos_cuerpo"); ?> :</label>
                                <div class="controls">
                                    <?php echo form_textarea("txCuerpo", $oAviso->txCuerpo, "id=\"txCuerpo\"class=\"input-xlarge \" "); ?>
                                    <label id="txCuerpo-error" class="error" for="txCuerpo" style="display: none; text-align: left; "></label>
                                    <span class="help-block"><?php echo lang("avisos_cuerpo_help"); ?></span>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </fieldset>
                <?php echo form_close(); ?>
                <!-- FIN AREA DEL FORMULARIO  -->
            </div>
        </div>  
    </section>
</div>
<!-- FIN CONTENIDO DEL CATALOGO  -->

<!-- INICIO AREA JAVASCRIPT  -->
<?php $this->load->view("layout/javascript_base_view"); ?>
<?php incluye_componente_validate(); ?>
<?php script_tag("plugins/tinymce/tinymce"); ?>
<?php script_tag("plugins/ajaxForm/jquery.ajaxForm"); ?>
<?php script_tag("modulos/avisos"); ?>

<script type="text/javascript">

    avisos.TEXT_ETIQUETA_TODOS = "<?php  echo lang("avisos_todos"); ?>";
    avisos.TIPO_BUSQUEDA_USUARIO = <?php echo AVISOS_BUSCAR_USUARIO;  ?>;
    avisos.TIPO_BUSQUEDA_NIVEL_ACCESO = <?php echo AVISOS_BUSCAR_NIVEL_ACCESO;  ?>;
    
    $(document).ready(function() {
        avisos.initForma({
            'paramsForma': {
                rules:{
                     cTitulo:{
                        required:true,
                        maxlength:255,
                    },
                    txCuerpo:{
                        required:true,
                    },
                    cListaUsuarios:{
                        required:true
                    }
                }
            }
        });
    });
</script>
<!-- FIN AREA JAVASCRIPT  -->