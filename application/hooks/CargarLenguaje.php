<?php

class CargarLenguaje {

    /** @var object Objecto del core de Codeigniter */
    protected $CI;


    public function __construct()
    {
        $this->CI = &get_instance();
    }

    /**
     * 
     */
    public function iniciar()
    {
        $oIdioma = IdiomaHelper::get();
        if (is_object($oIdioma))
        {
            $this->CI->lang->load($oIdioma->cNombreArchivo, $oIdioma->cNombre);
            $this->CI->config->set_item('language', $oIdioma->cNombre);
        }
        else
        {
            $this->CI->load->model("Sistema/Idioma_model");
            $this->CI->load->library("ViewModels/Idioma_ViewModel");

            $dbIdioma = $this->CI->Idioma_model->find(array("idIdioma" => ConfigHelper::get('idIdioma')));
            $oIdioma = new Idioma_ViewModel();
            $oIdioma = $this->CI->seguridad->dbToView($oIdioma, $dbIdioma);
            
            IdiomaHelper::set($oIdioma);
            
            $this->CI->lang->load($oIdioma->cNombreArchivo, $oIdioma->cNombre);
            $this->CI->config->set_item('language', $oIdioma->cNombre);
        }
    }

}
