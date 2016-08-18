<?php

/**
 * pintarMenu
 * 
 * Funcion que se encarga de dibujar el menu principal del sistema dentro del template
 *  de acuerdo a los permisos que tiene cada usuario
 */
if (!function_exists("pintarMenu"))
{

    function pintarMenu()
    {
        $CI = &get_instance();
        $cMenuHtml = "";
        $aMenu = (isset($_SESSION["_MENU"])) ? $_SESSION["_MENU"] : array();
        
//        debug($aMenu);

        if (count($aMenu) > 0)
        {
            foreach ($aMenu as $oMenu)
            {
                $cMenuHtml .= pintarItems($oMenu);
            }
        }

        echo $cMenuHtml;
    }

}


/**
 * pintarItems
 * 
 * Funcion recursiva que se encarga depintar los items del menu princial dentro del sistema
 */
if (!function_exists("pintarItems"))
{

    function pintarItems($oItem, $bSubmenu = FALSE)
    {
        $CI = &get_instance();
        $cController = $CI->uri->segment(1);
        $cMenuHtml = "<li " . ((count($oItem->aHijos) > 0) ? "data-menu-padre=\"true\"" : "data-nombre-item=\"{$oItem->cNombreModulo}\"") . " onclick=\"" . ((count($oItem->aHijos) == 0) ? "javascript:CoreUI.Menu('" . base_url($oItem->cLink) . "');" : "") . "\" class=\"dropdown" . ((count($oItem->aHijos) > 0 && $bSubmenu) ? "-submenu" : "") . "\">"
                . "<a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\">   "
                . ""
                . "<span>" . lang($oItem->cEtiquetaTitulo) . "</span> ";

        if (count($oItem->aHijos) > 0 && $bSubmenu == FALSE)
        {
            $cMenuHtml.="<b class=\"caret\"></b>";
        }
        $cMenuHtml.= "</a>";

        if (count($oItem->aHijos) > 0)
        {
            $cMenuHtml .="<ul class=\"dropdown-menu\">";
            foreach ($oItem->aHijos as $oItemHijo)
            {
                $cMenuHtml .= pintarItems($oItemHijo, TRUE);
            }
            $cMenuHtml .= "</ul>";
        }

        $cMenuHtml .= "</li>";
        return $cMenuHtml;
    }

}

/**
 * pintarItemsCatalogo
 * 
 */
if (!function_exists("pintarItemsCatalogo"))
{

    function pintarItemsCatalogo($aItems)
    {
        $cHtml = "";
        if (is_array($aItems) && count($aItems) > 0)
        {
            foreach ($aItems as $oItem)
            {
                $cHtml .= pintarItemCatalogo($oItem);
            }
        }
        echo $cHtml;
    }

}

/**
 * pintarItemsCatalogo
 * 
 */
if (!function_exists("pintarItemCatalogo"))
{

    function pintarItemCatalogo($oItem)
    {
        $cHtml = "<li id=\"list_{$oItem->idItems}\" class=\"mjs-nestedSortable-branch mjs-nestedSortable-expanded\" data-idItem=\"{$oItem->idItems}\">";
        $cHtml .= "<div class=\"cont_sortable\"><span class=\"disclose\"><span></span></span> "
                . "<span class=\"cTitulo\">" . lang($oItem->cEtiquetaTitulo) . "</span>"
                . "<span class=\"icon_tools\">"
                . "<a href=\"javascript:Items.btnEditar({$oItem->idItems});\" class=\"icono-editar\">"
                . "<img src=\" " . getRutaImagen(DIRECTORIO_IMAGENES_ICON . "16_x_16/btn_edit.png") . " \" />"
                . "</a>"
                . "<a href=\"javascript:Items.btnEliminar({$oItem->idItems}, '" . lang($oItem->cEtiquetaTitulo) . "');\" class=\"icono-eliminar\">"
                . "<img src=\" " . getRutaImagen(DIRECTORIO_IMAGENES_ICON . "16_x_16/cross_circle.png") . " \" />"
                . "</a>"
                . "</span>"
                . "</div>";

        if (is_array($oItem->aHijos) && count($oItem->aHijos) > 0)
        {
            $cHtml .= "<ol>";
            foreach ($oItem->aHijos as $oItemHijo)
            {
                $cHtml .= pintarItemCatalogo($oItemHijo);
            }
            $cHtml .= "</ol>";
        }

        $cHtml .= "</li>";
        return $cHtml;
    }

}


if (!function_exists("pintarMenuCatalogo"))
{
    function pintarMenuCatalogo($aItems, $cSeleccionar = "")
    {
        $CI = &get_instance();
        $cHtml = "";
        
        if(count($aItems) > 0)
        {
            $cModulo = (!empty($cSeleccionar)) ? $cSeleccionar : strtolower(trim($CI->uri->segment(1)));
            
            foreach($aItems  as $oItem)
            {
                $cNombreItem = strtolower(trim($oItem->cNombreModulo));
                $cHtml .= "<li class=\" " . (($cModulo === $cNombreItem) ? "active": "") . " \">
                                <a href=\" ". (base_url($oItem->cLink)) ." \">" . (lang($oItem->cEtiquetaTitulo)) .  "</a>
                </li>";
            }
        }
        echo $cHtml;
    }

}