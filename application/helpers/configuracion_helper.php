<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

abstract class ConfigHelper {
    
    public static function get($cItem = '', $aFiltro  = array())
    {
        $CI = &get_instance();
        $dbConfiguracion = $CI->Configuracion_model->findAll($aFiltro);

        if (empty($cItem))
        {
            return $dbConfiguracion;
        }

        return self::getCache($cItem, $dbConfiguracion);
    }
    
    public static function getCache($cItem, $aConfiguracion)
    {
        $cValor = null;
        if (!empty($cItem) && !empty($aConfiguracion))
        {
            foreach ($aConfiguracion as $oConfiguracion)
            {
                if ($oConfiguracion->cClave == $cItem)
                {
                    $cValor = $oConfiguracion->cValor;
                    break;
                }
            }
        }
        return $cValor;
    }
    
    public static function getItem($cItem)
    {
        $cValor = null;
        if (!empty($cItem))
        {
            $CI = &get_instance();
            $dbConfiguracion = $CI->Configuracion_model->find(array(
                "cClave" => $cItem
            ));
            
            if(is_object($dbConfiguracion))
            {
                $cValor = $dbConfiguracion->cValor;
            }
        }
        return $cValor;
    }
    
    public static function getSmtp()
    {
        $aConfiguracion = self::get();

        $config = array();
        $config['protocol'] = self::getCache('cProtocolSmtp', $aConfiguracion);
        $config['smtp_host'] = (self::getCache("bSslSmtp", $aConfiguracion) == 1) ? "ssl://" . self::getCache('cServidorSmtp', $aConfiguracion) : self::getCache('cServidorSmtp', $aConfiguracion);
        $config['smtp_port'] = self::getCache('cPuertoSmtp', $aConfiguracion);
        $config['smtp_user'] = self::getCache('cCorreoSmtp', $aConfiguracion);
        $config['smtp_pass'] = self::getCache('cPasswordSmtp', $aConfiguracion);
        $config['mailtype'] = self::getCache('cMailTypeSmtp', $aConfiguracion);
        $config['smtp_timeout'] = self::getCache('iTimeOutSmtp', $aConfiguracion);
        $config['charset'] = self::getCache('cCharsetSmtp', $aConfiguracion);
        $config['newline'] = "\r\n";

        return $config;
    }
}