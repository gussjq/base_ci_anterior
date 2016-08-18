<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Clase que se encarga de cargar los templates en el sistema
 * 
 * @author DEVELOPER 1
 * @dete 04-12-2013
 * @package libraries
 */
class Layout {

    private $_cLayout;
    private $_CI;

    public function __construct()
    {
        $this->_CI = &get_instance();
        $this->_cLayout = LAYOUT_DEFAULT . LAYOUT_SISTEMA;
    }

    /**
     * Metodo que se encarga de setear el layout o template a cargar
     * 
     * @access public
     * @param string $sLayout template a cargar
     * @return object
     */
    public function setLayout($sLayout = "")
    {
        $this->_cLayout = $sLayout;
        return $this;
    }

    /**
     * Metodo que se encarga de devolver una vista como string o de cargar una vista en el template 
     * 
     * @access public
     * @param string $sVista
     * @param array $aParams
     * @return object
     */
    public function view($sVista = "", $aParams = array(), $bReturn = false)
    {
        $aVista = array('sContenidoLayout' => '');

        if (!empty($sVista))
        {
            $aVista["sContenidoLayout"] = $this->_CI->load->view($sVista, $aParams, true);
        }

        $this->_CI->load->view($this->_cLayout, $aVista);
        return $this;
    }

}

// END Layout class

/* End of file Layout.php */
/* Location: ./application/libraries/Layout.php */