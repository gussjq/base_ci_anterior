<div class="container-logo">
        <a href="javascript:void(0);">
            <img src="<?php echo base_url(DIRECTORIO_CONFIGURACION . ConfigHelper::get('cLogo')); ?>" alt="<?php echo lang("general_empresa"); ?>" class="logo"/>
        </a>
</div>

<div class="container-title">
    <h1 id="modulo-sistema" class="page-title "  data-placement="bottom">
        <!--<img src="<?php //echo getRutaImagen(DIRECTORIO_MODULOS . getModulo('cIcono')) ?>" class="icon_modulo" />-->
        <?php echo lang(getModulo('cEtiquetaTitulo')); ?>
        <?php echo (isset($aMigajaPan)) ? migaja_pan($aMigajaPan) : "" ?>
    </h1>
</div>