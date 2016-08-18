<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 
 */
abstract class IdiomaHelper {
    
    /**
     * 
     * @return type
     */
    public static function get($cItem = ""){
        $oIdioma = (isset($_SESSION["_IDIOMA"])) ? $_SESSION["_IDIOMA"] : null;
        if (!empty($cItem))
        {
            if (isset($oIdioma->$cItem))
            {
                return $oIdioma->$cItem;
            }
        }

        return $oIdioma;
    }
    
    /**
     * 
     * @param type $idioma
     */
    public static function set($idioma)
    {
        if(is_object($idioma))
        {
            self::remove();
            $_SESSION["_IDIOMA"] = $idioma;
        }
        
        if(is_numeric($idioma))
        {
            $CI = &get_instance();
            $CI->load->model("Sistema/Idioma_model");
            $CI->load->library("ViewModels/Idioma_ViewModel");

            $dbIdioma = $CI->Idioma_model->find(array("idIdioma" => $idioma));
            
            self::remove();
            
            $_SESSION["_IDIOMA"] = $dbIdioma;
        }
    }

    /**
     * 
     */
    public static function remove()
    {
        if(isset($_SESSION["_IDIOMA"]))
       {
           unset($_SESSION["_IDIOMA"]);
       }
    }
}