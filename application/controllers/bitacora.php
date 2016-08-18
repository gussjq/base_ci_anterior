<?php

class Bitacora extends MY_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model('Sistema/Bitacora_model');
        $this->load->library('ViewModels/Bitacora_ViewModel');

        $this->load->model('Sistema/Modulo_model');
        $this->load->library('ViewModels/Modulo_ViewModel');

        $this->load->model('Sistema/Accion_model');
        $this->load->library('ViewModels/Accion_ViewModel');

        $this->load->model('Sistema/Usuario_model');
        $this->load->library('ViewModels/Usuario_ViewModel');

    }

    // <editor-fold defaultstate="collapsed" desc="Metodos Catalogo">

    /**
     * Metodo que muestra el listado del catalogo 
     * @access public
     * @return void 
     */
    public function listado()
    {

        try {
            $oBitacora = new Bitacora_ViewModel();
            $oBitacora = $this->seguridad->getPost($oBitacora);

            FindSessionHelper::add("FindBitacora", $oBitacora, $this->aAmbito);

            $aParams = array();
            $aParams["aCombosForma"] = $this->_getCombosForma();
            $aParams["oBitacora"] = $oBitacora;
            $aParams["cTitulo"] = lang("bitacora_titulo");
            $aParams['aMenuCatalogo'] = $this->seguridad->getMenu(MENU_CONFIGURACION);
            $aParams["aMigajaPan"] = array(lang("general_accion_listado"));

            $this->layout->view($this->cModulo . '/listado_view', $aParams);
        } catch (Exception $ex) {
            $this->message->clearMessages();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $ex);
        }
    }

    /**
     * 
     */
    public function listadoAjax()
    {
        $aResponse = array();
        try {
            $oBitacora = FindSessionHelper::get("FindBitacora");

            if (!$oBitacora)
            {
                $oBitacora = new Bitacora_ViewModel();
            }

            $oBitacora = $this->seguridad->getPost($oBitacora);
            $oBitacora->cBuscar = $this->input->post('sSearch');
            $oBitacora->iOrdenadoPor = $this->input->post('iSortCol_0');
            $oBitacora->iODireccion = strtoupper($this->input->post('sSortDir_0'));

            FindSessionHelper::add("FindBitacora", $oBitacora, $this->aAmbito);

            $aData = $this->Bitacora_model->getAll($oBitacora, true);
            $aResponse = parent::paginacion($aData);
        } catch (Exception $exc) {
            $this->message->clearMessages();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $exc, false);
        }

        echo json_encode($aResponse);
    }

    public function getParams()
    {
        $oBitacora = FindSessionHelper::get("FindBitacora");
        if ($oBitacora == NULL)
        {
            $oBitacora = new Bitacora_ViewModel();
        }
        if (!$oBitacora->iODireccion)
        {
            $oBitacora->iODireccion = 'ASC';
        }

        $result = parent::getParams();
        $result['filters'] = $oBitacora;

        if ($oBitacora->iOrdenadoPor !== false)
        {
            $result['sort'] = $oBitacora->iOrdenadoPor;
        }
        else
        {
            $result['sort'] = '';
        }
        $result['sortAlign'] = $oBitacora->iODireccion;
        $result['find'] = $oBitacora->cBuscar;
        
        $result['tituloReporte'] = lang("bitacora_titulo");
        return $result;
    }

    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="Metodos Ajax">

    public function getAccionesAjax()
    {
        $aResponse = array("success" => FALSE, "noLogin" => FALSE, "data" => array());
        try {
            $idModulo = (int) $this->input->post("idModulo");
            if ($idModulo > 0)
            {
                $oAccion = new Accion_ViewModel();
                $oAccion->sortBy = "ASC";
                $oAccion->order = "a.cNombre";
                $oAccion->idModulo = $idModulo;
                $aAcciones = $this->Accion_model->getAll($oAccion);

                $aResponse["success"] = TRUE;
                $aResponse["data"]["options"] = getComboMultiSelect('idAccion', 'cAlias', $aAcciones);
            }
        } catch (Exception $ex) {
            $this->message->clearMessages();
            $this->message->addError(lang("general_error"));
            $aResponse["data"]["message"] = $this->message->toJsonObject();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $ex, false);
        }

        echo json_encode($aResponse);
    }

    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="Metodos privados">

    private function _getCombosForma()
    {

        $oModulo = new Modulo_ViewModel();
        $oModulo->sortBy = "ASC";
        $oModulo->order = "m.cAlias";
        $aModulos = $this->Modulo_model->getAll($oModulo);
        $aCombosForma["aModulos"] = getComboForma('idModulo', 'cAlias', $aModulos);


        $oUsuario = new Usuario_ViewModel();
        $oUsuario->sortBy = "ASC";
        $oUsuario->order = "u.cNombre";
        $aUsuarios = $this->Usuario_model->getAll($oUsuario);
        $aCombosForma["aUsuarios"] = getComboForma('idUsuario', 'cNombreCompleto', $aUsuarios);

        return $aCombosForma;
    }

    // </editor-fold>
}
