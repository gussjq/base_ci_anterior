<?php

/**
 * crearArbolAcciones
 * 
 * Funcion que se encarga de crear el arbol de acciones para la forma de roles
 */
if (!function_exists("crearArbolAcciones"))
{

    function crearArbolAcciones($aModulos)
    {
        $cHtml = "";
        $iConta = 1;
        foreach ($aModulos as $oModulo)
        {
            $cHtml .= pintarFila("1." . $iConta, 1, $oModulo);
            $iConta++;
        }
        echo $cHtml;
    }

}

if (!function_exists("pintarFila"))
{

    function pintarFila($iConta, $iContaPadre, $oItem, $bModulo = true)
    {
        $cHtml = "<tr row-id=\"{$iConta}\" parent-id=\"{$iContaPadre}\" style=\"" . (($bModulo) ? "background-color: #E2E4FF;" : "") . "\">
                    <td>";

        if ($bModulo)
        {
            $cHtml .= "<input type=\"checkbox\" name=\"padre_{$oItem->idModulo}\" value=\"Si\" id=\"padre_{$oItem->idModulo}\" onclick=\"roles.checkPadre(this)\" class=\"padre_checkbox checkbox\" data-idModulo=\"{$oItem->idModulo}\"/>";
        }
        else
        {
            $cHtml .= "<input type=\"checkbox\" name=\"idAccion[]\" value=\"{$oItem->idAccion}\" id=\"{$oItem->cNombre}_checkbox_{$oItem->idAccion}\" onclick=\"roles.verificaCheckBox()\" class=\"padre_{$oItem->idModulo} checkbox\" data-idModulo=\"{$oItem->idModulo}\"/>";
        }

        $cHtml .="" . (($bModulo) ? lang($oItem->cEtiquetaTitulo) : $oItem->cAlias) . "";

        $cHtml .="</td>
                    <td class=\"data\">" . (($bModulo) ? lang($oItem->cEtiquetaDescripcion) : $oItem->cDescripcion) . "</td>
                </tr>";

        if (isset($oItem->aAcciones) && count($oItem->aAcciones) > 0)
        {
            $iContaAccion = 1;
            foreach ($oItem->aAcciones as $oItemHijo)
            {
                $cHtml .= pintarFila($iConta . "." . $iContaAccion, $iConta, $oItemHijo, false);
                $iContaAccion++;
            }
        }

        return $cHtml;
    }

}



