<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" dir="ltr" >
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="IE=edge" />
            <meta http-equiv="content-type" content="text/html; charset=utf-8" />
            <title></title>
            <!--  <link href="/joomla/administrator/templates/isis/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />-->

            <link rel="stylesheet" href="<?php echo DOMINIO_APLICACION; ?>assets/css/template.css" type="text/css" />
            <link rel="stylesheet" href="<?php echo DOMINIO_APLICACION; ?>assets/css/system.css" type="text/css" />

            <style type="text/css">
                .container {
                    width: 805px !important;
                    position: absolute;
                    top: 20% !important;
                    left: 20% !important;
                    margin-top: 0px !important;
                    margin-left: 0px !important;
                }

                .texto-error{
                    width: 49%;
                    height: 272px;
                    float:left;
                    border-right-width: 1px;
                    border-right-style: dotted;
                    border-right-color: #e3d2d2;
                }

                .contenedor-imagen{
                    width: 50%;
                    float:right;
                }

                /* Large desktops and laptops */
                @media (min-width: 1200px) {

                }

                /* Landscape tablets and medium desktops */
                @media (min-width: 992px) and (max-width: 1199px) {
                    .container {
                        width: 605px !important;
                        position: absolute;
                        top: 20% !important;
                        left: 20% !important;
                        margin-top: 0px !important;
                        margin-left: 0px !important;
                    }
                }

                /* Portrait tablets and small desktops */
                @media (min-width: 768px) and (max-width: 991px) {
                    .container {
                        width: 605px !important;
                        position: absolute;
                        top: 20% !important;
                        left: 10% !important;
                        margin-top: 0px !important;
                        margin-left: 0px !important;
                    }
                }

                /* Landscape phones and portrait tablets */
                @media (max-width: 767px) {
                    .container {
                        width: 505px !important;
                        position: absolute;
                        top: 20% !important;
                        left: 5% !important;
                        margin-top: 0px !important;
                        margin-left: 0px !important;
                    }

                    .texto-error{
                        width: 100%;           
                        height: 200px;
                        border-bottom-width: 1px;
                        border-bottom-style: dotted;
                        border-bottom-color: #e3d2d2;
                        
                        border-right: 0;
                    }

                    .contenedor-imagen{
                        margin-top: 20px;
                        width: 100%;
                    }
                }

                /* Portrait phones and smaller */
                @media (max-width: 480px) {
                    .container {
                        width: 405px !important;
                        position: absolute;
                        top: 20% !important;
                        left: 5% !important;
                        margin-top: 0px !important;
                        margin-left: 0px !important;
                    }

                    .texto-error{
                        width: 100%;           
                        height: 200px;
                        border-bottom-width: 1px;
                        border-bottom-style: dotted;
                        border-bottom-color: #e3d2d2;
                        
                        border-right: 0;
                    }

                    .contenedor-imagen{
                        margin-top: 20px;
                        width: 100%;
                    }
                }
            </style>
            <!--[if lt IE 9]>
                    <script src="../media/jui/js/html5.js"></script>
            <![endif]-->
    </head>

    <body class="site com_login view-login layout-default task- itemid- ">

        <div class="container">

            <div class="row well" >
                <div class="texto-error">
                    <h1 >Oopss!</h1>
                    <h3><?php echo (isset($heading) ? $heading : ''); ?></h3>
                    <p><?php echo (isset($message) ? $message : ''); ?></p>
                    <p><?php echo ((isset($detalles)) && ($detalles != null)) ? $detalles : ""; ?></p>

                    <a href="<?php echo DOMINIO_APLICACION . MENU_LINK_DEFAULT; ?>" class="btn btn-primary" >Dashboard</a>
                </div>

                <div class="contenedor-imagen">
                    <img src="<?php echo DOMINIO_APLICACION . DIRECTORIO_IMAGENES; ?>plantilla/error-img.png" />
                </div>
            </div>


        </div>



    </body>
</html>
