<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Helper que se encarga de guardar los criterios de busqueda de un catalogo
 * para despues retornarla al momento de exportar un documento exel, word, csv, pdf
 * 
 * @author DEVELOPER 1 <correo@developer1> cel: <1111111111>
 * @creado 16/01/2014
 * @package helpers
 */

abstract class FindSessionHelper {

    /**
     * Metodo que se encarga de agregar un objeto con los criterios de busqueda
     *
     * @access	public
     * @return	void
     */
    public static function add($key, $value, $scope)
    {
        $aFilterSession = NULL;
        if (array_key_exists('_FILTER_SESSION', $_SESSION))
        {
            $aFilterSession = & $_SESSION['_FILTER_SESSION'];
        }
        else
        {
            $aFilterSession = array();
            $_SESSION['_FILTER_SESSION'] = & $aFilterSession;
        }
        $aFilterSession[$key] = array('value' => serialize($value), 'scope' => $scope);
    }

    /**
     * Metodo que se encarga de retornar el criterio de busqueda
     *
     * @access	public
     * @return	void
     */
    public static function get($key)
    {
        $result = NULL;
        if (array_key_exists('_FILTER_SESSION', $_SESSION))
        {
            $aFilterSession = $_SESSION['_FILTER_SESSION'];
            if (array_key_exists($key, $aFilterSession))
            {
                $result = $aFilterSession[$key];
                $result = unserialize($result['value']);
            }
        }
        return $result;
    }

    /**
     *
     * Metodo que se encarga de remover un criterio de busqueda
     *
     * @access	public
     * @return	void
     */
    public static function remove($scope)
    {
        if (array_key_exists('_FILTER_SESSION', $_SESSION))
        {
            $aFilterSession = & $_SESSION['_FILTER_SESSION'];
            foreach ($aFilterSession as $key => $reg)
            {
                if (!in_array($scope, $reg['scope']))
                {
                    unset($aFilterSession[$key]);
                }
            }
        }
    }

    /**
     *
     * Metodo que se encarga de agregar un objeto con los criterios de busqueda
     *
     * @access	public
     * @return	void
     */
    public static function delete($key)
    {
        if (array_key_exists('_FILTER_SESSION', $_SESSION))
        {
            $aFilterSession = & $_SESSION['_FILTER_SESSION'];
            if (array_key_exists($key, $aFilterSession))
            {
                unset($aFilterSession[$key]);
            }
        }
    }

}
