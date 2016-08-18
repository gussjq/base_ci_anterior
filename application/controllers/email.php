<?php

/*
 * Email
 * 
 * Catalogo de administracion de correo electronicos a nivel sistema.
 * 
 * @package controller
 * @author DEVELOPER 1 <correo@developer1>
 * @created 06-09-2014
 */

class Email extends MY_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model("Sistema/Email_model");
        $this->load->model("Sistema/EtiquetasEmail_model");
        $this->load->model("Sistema/TipoEmail_model");

        $this->load->library("ViewModels/EtiquetasEmail_ViewModel");
        $this->load->library("ViewModels/Email_ViewModel");
        $this->load->library("ViewModels/TipoEmail_ViewModel");
    }

    // <editor-fold defaultstate="collapsed" desc="Metodos Catalogo">

    /**
     * Pantalla del listado de Email
     * 
     * @access public
     * @return void 
     */
    public function listado()
    {
        try {
            $oEmail = new Email_ViewModel();
            $oEmail = $this->seguridad->getPost($oEmail);
            
             FindSessionHelper::add("FindEmail", $oEmail, $this->aAmbito);

            $aParams = array();
            $aParams["oEmail"] = $oEmail;
            $aParams["cTitulo"] = lang("email_titulo");
            $aParams["aCombosForma"] = $this->_getCombosForma($oEmail);
            $aParams['aMigajaPan'] = array(lang("general_accion_listado"));
            $aParams['aMenuCatalogo'] = $this->seguridad->getMenu(MENU_CONFIGURACION);

            $this->layout->view($this->cModulo . '/listado_view', $aParams);
        } catch (Exception $exc) {
            $this->message->clearMessages();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $exc);
        }
    }

    /**
     * Metodo que se encarga de cargar los datos en el listado del grid
     * 
     * @access public
     * @return json array Devuelve los datos del listado en formato json
     */
    public function listadoAjax()
    {
        try {
            $oEmail = FindSessionHelper::get("FindEmail");
            if (!$oEmail)
            {
                  $oEmail = new Email_ViewModel();
            }
            
            $oEmail = $this->seguridad->getPost($oEmail);
            $oEmail->cBuscar = $this->input->post('sSearch');
            $oEmail->iOrdenadoPor = $this->input->post('iSortCol_0');
            $oEmail->iODireccion = strtoupper($this->input->post('sSortDir_0'));
            
            FindSessionHelper::add("FindEmail", $oEmail, $this->aAmbito);
            
            $aData = $this->Email_model->getAll($oEmail, true);
            $aResponse = parent::paginacion($aData);
            echo json_encode($aResponse);
        } catch (Exception $exc) {

            $this->message->clearMessages();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $exc, FALSE);
        }
    }
    
    public function getParams()
    {
        $oEmail= FindSessionHelper::get("FindEmail");
        if ($oEmail == NULL)
        {
            $oEmail = new Email_ViewModel();
        }
        if (!$oEmail->iODireccion)
        {
            $oEmail->iODireccion = 'ASC';
        }

        $result = parent::getParams();
        $result['filters'] = $oEmail;

        if ($oEmail->iOrdenadoPor !== false)
        {
            $result['sort'] = $oEmail->iOrdenadoPor;
        }
        else
        {
            $result['sort'] = '';
        }
        $result['sortAlign'] = $oEmail->iODireccion;
        $result['find'] = $oEmail->cBuscar;
        
        $result['tituloReporte'] = lang("email_titulo");
        return $result;
    }

    /**
     * Metodo que visualiza la forma al momento de agregar o editar un nuevo registro
     * 
     * @access public
     * @param numeric $idAccion Identificador del Email a editar
     * @return void 
     */
    public function forma()
    {
        try {

            $idEmail = $this->uri->segment(3);
            $oEmail = new Email_ViewModel();

            if ($idEmail)
            {
                $oEmail->idEmail = $idEmail;
                $dbEmail = $this->Email_model->get($oEmail);
                if (is_object($dbEmail))
                {
                    $oEmail = $this->seguridad->dbToView($oEmail, $dbEmail);
                }
                else
                {
                    $this->message->addError(lang("general_error_registro_no_encontrado"));
                    $this->session->set_flashdata('_MESSAGES', json_encode($this->message->toJsonObject()));
                    redirect($this->cModulo . "/listado");
                }
            }
            else
            {
                $this->message->addError(lang("general_error_registro_no_encontrado"));
                $this->session->set_flashdata('_MESSAGES', json_encode($this->message->toJsonObject()));
                redirect($this->cModulo . "/listado");
            }

            $aParams = array();
            $aParams['oEmail'] = $oEmail;
            $aParams["cTitulo"] = lang("email_titulo");
            $aParams["aCombosForma"] = $this->_getCombosForma($oEmail);
            $aParams['aMigajaPan'] = array(lang("general_accion_forma"));
            $aParams['aMenuCatalogo'] = $this->seguridad->getMenu(MENU_CONFIGURACION);

            $this->layout->view($this->cModulo . '/forma_view', $aParams);
        } catch (Exception $exc) {
            $this->message->clearMessages();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $exc);
        }
    }

    /**
     * Metodo encargado de actualizar un Email en la base de datos
     * 
     * @access public
     * @return json objeto json 
     *      [success] booelan true si la el proceso se realizo con exito false si no 
     *      [failuare] boolean true si hubo un fallo al momento de realizar el proceso
     *      [noLogin] boolean true si la sesion del usuario se ha terminado
     *      [data] array datos a devolver a la vista para continuar con el proceso
     */
    public function actualizar()
    {
        $aResponse = array("success" => false, "failure" => true, "noLogin" => false, "data" => array());
        try {
            $oEmail = new Email_ViewModel();
            $oEmail = $this->seguridad->getPost($oEmail);

            if ($this->_validarForma())
            {
                $this->seguridad->startTransaction();
                $dbEmail = $this->Email_model->find(array("idEmail" => $oEmail->idEmail));
                if (is_object($dbEmail))
                {
                    $this->Email_model->actualizar($oEmail);

                    $this->message->addExito(lang("general_evento_actualizar"));
                    $this->session->set_flashdata('_MESSAGES', json_encode($this->message->toJsonObject()));
                    $this->seguridad->setBitacora($this->cModulo, $this->cAccion, lang("bitacora_evento_actualizar"), array(UsuarioHelper::get('cNombreCompleto'), $this->cModulo, $oEmail->cTitulo));

                    $aResponse["success"] = true;
                    $aResponse["failure"] = false;
                }
                else
                {
                    $this->message->addError(lang("general_error_registro_no_encontrado"));
                }
                $this->seguridad->commitTransaction();
            }
        } catch (Exception $exc) {
            $this->message->clearMessages();
            $this->message->addError(lang("general_error"));
            $this->seguridad->rollbackTransaction();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $exc, FALSE);
        }

        $aResponse["data"]["messages"] = $this->message->toJsonObject();
        echo json_encode($aResponse);
    }

    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="Metodos Privados">

    /**
     * Metodo que se encarga de validar la forma cuendo se agrega o edita un registro,
     * retorna un valor boolean
     * 
     * @access private
     * @return boolean $bValidation retorna true si pasa las reglas de validacion
     */
    private function _validarForma()
    {
        $this->load->library("form_validation");

        $this->form_validation->set_rules("cTitulo", lang("email_titulo"), "trim|required|max_length[100]|callback_verificarTitulo");
        $this->form_validation->set_rules("txCuerpo", lang("email_cuerpo"), "trim|required");
        $this->form_validation->set_rules("cDescripcion", lang("email_descripcion_email"), "trim|required|max_length[200]");
        $this->form_validation->set_rules("idIdioma", lang("email_idioma"), "trim|required");

        $this->form_validation->set_message("verificarTitulo", lang("email_verificarTitulo"));

        $bValidacion = $this->form_validation->run();
        if (!$bValidacion)
        {
            $aErrores = $this->form_validation->getErrores();
            $this->message->addErrors($aErrores);
        }
        return $bValidacion;
    }

    private function _getCombosForma($oEmail)
    {
        $aCombos = array();

        $this->load->model("Sistema/Idioma_model");
        $this->load->library("ViewModels/Idioma_ViewModel");

        $oIdioma = new Idioma_ViewModel();
        $aIdioma = $this->Idioma_model->getAll($oIdioma);
        $aCombos["aIdiomas"] = getComboForma("idIdioma", "cAlias", $aIdioma);
        
        $oTipoEmail = new TipoEmail_ViewModel();
        $aTipoEmail = $this->TipoEmail_model->getAll($oTipoEmail);
        
        if (count($aTipoEmail) > 0)
        {
            foreach ($aTipoEmail as $key => $oTipoEmail)
            {
                $oTipoEmail->cEtiqueta = lang($oTipoEmail->cEtiqueta);
                $aTipoEmail[$key] = $oTipoEmail;
            }
        }

        $aCombos["aTipoEmail"] = getComboForma("idTipoEmail", "cEtiqueta", $aTipoEmail);

        $oEtiquetas = new EtiquetasEmail_ViewModel();
        $oEtiquetas->idEmail = $oEmail->idEmail;
        $aCombos["aEtiquetasEmail"] = $this->EtiquetasEmail_model->getAll($oEtiquetas);

        return $aCombos;
    }

    // </editor-fold>

    public function verificarTitulo()
    {
        $aParams = array();
        $aParams["cTitulo"] = $this->input->post("cTitulo");

        if ($this->input->post("idEmail"))
        {
            $aParams["idEmail <>"] = $this->input->post("idEmail");
        }

        $iExiste = $this->Email_model->findCount($aParams);
        return ($iExiste > 0) ? FALSE : TRUE;
    }

}
