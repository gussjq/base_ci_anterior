<?php if($bMuestraSeccionUsuario): ?>
<ul class="nav nav-user pull-right">
    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            <span class="icon-cog"></span>
            <b class="caret"></b>
        </a>
        
        <ul class="dropdown-menu">
            <li>
                <span>
                    <span class="icon-user"></span>
                    <strong><?php echo UsuarioHelper::get("cNombre"); ?></strong>
                </span>
            </li>
            
            <li class="divider"></li>
            <?php if(isset($_SESSION['_MENU_SECCION_USUARIO']) && count($_SESSION['_MENU_SECCION_USUARIO']) > 0): ?>
            
                <?php 
                $iConta = 1;
                foreach($_SESSION['_MENU_SECCION_USUARIO'] as  $oMenu): ?>
            
                    <li onclick="javascript:CoreUI.Menu('<?php echo base_url($oMenu->cLink); ?>');">
                        <a href="#">
                            <?php echo lang($oMenu->cEtiquetaTitulo); ?>
                            <?php if ($iConta == 2): ?>
                                <?php if (isset($_SESSION['_NUMERO_AVISOS_USUARIO']) && $_SESSION['_NUMERO_AVISOS_USUARIO'] > 0):  ?>
                                        &nbsp;<span id="numero-avsos" class="label label-info" ><?php echo $_SESSION['_NUMERO_AVISOS_USUARIO']; ?></span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </a>
                    </li>
                    
                <?php $iConta++; endforeach; ?>    
                
            <?php endif;  ?>
            
            <li class="divider"></li>
            
            <li onclick="javascript:CoreUI.Menu('<?php echo base_url(); ?>acceso/cerrarSesion');">
                <a href="<?php echo base_url(); ?>acceso/cerrarSesion">
                    <?php  echo lang("general_accion_cerrarsession"); ?>
                </a>
            </li>
            
        </ul>
    </li>
</ul>
<?php endif; ?>

<a class="brand visible-desktop visible-tablet show-tooltip" data-placement="bottom" href="<?php echo base_url("perfil/forma"); ?>" title="<?php echo lang("perfil_instrucciones"); ?>" >
    <?php echo lang("general_bienvenido"); ?>&nbsp;<?php echo UsuarioHelper::get("cNombreCompleto"); ?>					
    <span class="icon-out-2 small"></span>
</a>

<?php if (isset($_SESSION['_NUMERO_AVISOS_USUARIO'])): ?>
    
    <a id="menu-numero-avisos" style="margin-right: 15px;" class="brand visible-desktop visible-tablet show-tooltip <?php echo ($_SESSION['_NUMERO_AVISOS_USUARIO']>0) ? "label label-info" : "";  ?>" data-placement="bottom" href="<?php echo base_url("avisos/listado"); ?>" title="<?php echo lang("avisos_instrucciones"); ?>" >
        <?php echo lang("avisos_titulo"); ?>&nbsp;<span id="avisos-usuario">(<?php echo $_SESSION['_NUMERO_AVISOS_USUARIO']; ?>)</span>			
        <span class="icon-mail small" ></span>
    </a>

<?php endif; ?>


