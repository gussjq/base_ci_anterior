<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" dir="ltr" >
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="IE=edge" />
            <meta http-equiv="content-type" content="text/html; charset=utf-8" />
            <title><?php echo $cTitulo; ?></title>
            <!--  <link href="/joomla/administrator/templates/isis/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />-->

            <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/template.css" type="text/css" />
            <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/system.css" type="text/css" />

            <style type="text/css">
                /* Responsive Styles */
                @media (max-width: 480px) {
                    .view-login .container {
                        margin-top: -170px;
                    }
                    .btn {
                        font-size: 13px;
                        padding: 4px 10px 4px;
                    }
                }
            </style>
            <!--[if lt IE 9]>
                    <script src="../media/jui/js/html5.js"></script>
            <![endif]-->
    </head>

    <body class="site com_login view-login layout-default task- itemid- ">

        <div class="container">
             <?php echo (isset($sContenidoLayout) ? $sContenidoLayout : ''); ?>
        </div>
        
        <div id="overley-loading" style="display: none;"></div>
        <div id="content-img-loading" style="display: none;"><img id="img-loading" src="<?php echo base_url() . DIRECTORIO_IMAGENES_ICON; ?>loading.gif" /></div>
        
    </body>
</html>
