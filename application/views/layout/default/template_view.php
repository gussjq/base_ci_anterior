<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es-es" lang="es-es" dir="ltr">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="IE=edge" />
            <meta http-equiv="content-type" content="text/html; charset=utf-8" />
            
            <title><?php echo (isset($cTitulo)) ? $cTitulo : ''; ?></title>

            <!--<link href="/administrator/templates/isis/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />-->

            <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/template.css" type="text/css" />
            <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/system.css" type="text/css" />
            
            <?php link_tag("jquery-ui"); ?>
            <?php link_tag("plugins/datatables/demo_table_jui"); ?>
            <?php link_tag("plugins/datatables/TableTools"); ?>

            <style type="text/css">
                .menu-icon-16-wf {
                    background: url(../media/com_phipayrollbanks/images/icon-16-wf.png) no-repeat;
                }

                .menu-icon-16-wf {
                    background: url(../media/com_phipayrolllocalities/images/icon-16-wf.png) no-repeat;
                }

                .menu-icon-16-wf {
                    background: url(../media/com_phipayrollwages/images/icon-16-wf.png) no-repeat;
                }

                .menu-icon-16-wf {
                    background: url(../media/com_phipayrolltables/images/icon-16-wf.png) no-repeat;
                }

                .menu-icon-16-wf {
                    background: url(../media/com_phipayrollpayments/images/icon-16-wf.png) no-repeat;
                }
            </style>

            <!--[if lt IE 9]>
                <script src="<?php echo base_url(); ?>assets/js/html5.js"></script>
            <![endif]-->
    </head>

    <body class="admin " data-spy="scroll" data-target=".subhead" data-offset="87">
        <!-- Top Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container-fluid">
                    
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>

                    <a class="admin-logo" href="<?php echo base_url(MENU_LINK_DEFAULT); ?>">
                        <img src="<?php echo base_url(LOGO_FAVICON); ?>" />
                    </a>

                    <a class="brand hidden-desktop hidden-tablet" href="<?php echo base_url(); ?>" title="Vista previa Workforce" target="_blank">
                            <?php echo ConfigHelper::get("cNombreEmpresa"); ?><span class="icon-out-2 small"></span>
                    </a>

                    <div class="nav-collapse">
                        <!-- INICIA MENU SISTEMA -->
                        <?php  $this->load->view(LAYOUT_DEFAULT . "seccion_nav_view");  ?>
                        <!-- FIN MENU SISTEMA -->

                        <!--  INICIA SECCION DEL USUARIO  -->
                        <?php   $this->load->view(LAYOUT_DEFAULT . "seccion_usuario_view", array("bMuestraSeccionUsuario" => TRUE));  ?>
                        <!--  FIN SECCION DEL USUARIO  -->
                    </div>
                    <!--/.nav-collapse -->
                </div>
            </div>
        </nav>
        
        <!--  INICIA HEADER  -->
        <header class="header">
            <?php $this->load->view(LAYOUT_DEFAULT . "header_view"); ?>
        </header>
        <!--  FIN HEADER  -->
        
        <!-- INICIA CONTENIDO -->
            <?php echo (isset($sContenidoLayout) ? $sContenidoLayout : ''); ?>
        <!-- FIN CONTENIDO  -->
        
        <div id="status" class="navbar navbar-fixed-bottom hidden-phone">
		<?php $this->load->view(LAYOUT_DEFAULT . "footer_view");  ?>
	</div>
        
        <div id="overley-loading" style="display: none;"></div>
        <div id="content-img-loading" style="display: none;"><img id="img-loading" src="<?php echo base_url() . DIRECTORIO_IMAGENES_ICON; ?>loading.gif" /></div>
    </body>
</html>
