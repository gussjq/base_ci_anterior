<?php

/*
 * Acciones
 * 
 * Clase encargada de administrar las acciones de los modulos del sistema
 * 
 * @package controller
 * @author DEVELOPER 1 <correo@developer1> cel <1111111111>
 * @creado 06-09-2014
 */

class Acciones extends MY_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model("Sistema/Modulo_model");
        $this->load->model("Sistema/Accion_model");
        $this->load->model("Sistema/Usuario_model");

        $this->load->library("ViewModels/Modulo_ViewModel");
        $this->load->library("ViewModels/Accion_ViewModel");
    }

    // <editor-fold defaultstate="collapsed" desc="Metodos Catalogo">

    /**
     * Pantalla del listado de ${aplicativo}
     * 
     * @access public
     * @param int $idModulo Identificador del modulo al cual pertenecen las acciones
     * @return void 
     */
    public function listado()
    {
        try {

            $idModulo = $this->uri->segment(3);
            $oModulo = $this->_moduloListado($idModulo);

            $oAccion = new Accion_ViewModel();
            $oAccion = $this->seguridad->getPost($oAccion);
            $oAccion->idModulo = $idModulo;
            
            FindSessionHelper::add("FindAccion", $oAccion, $this->aAmbito);

            $aParams = array();
            $aParams["oAccion"] = $oAccion;
            $aParams["cTitulo"] = lang("acciones_titulo");
            $aParams['aCombosForma'] = $this->_getCombosForma();
            $aParams['aMigajaPan'] = array($oModulo->cAlias, lang("general_accion_listado"));
            $aParams['aMenuCatalogo'] = $this->seguridad->getMenu(MENU_CONFIGURACION);

            $this->layout->view($this->cModulo . '/listado_view', $aParams);
        } catch (Exception $ex) {
            $this->message->clearMessages();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $ex);
        }
    }

    /**
     * Metodo que se encarga de cargarla informacion los datos ${aplicativo} en el listado del grid
     * 
     * @access public
     * @return json array Devuelve los datos del listado en formato json
     */
    public function listadoAjax()
    {
        try {
            $oAccion = FindSessionHelper::get("FindAccion");
            if (!$oAccion)
            {
                  $oAccion = new Accion_ViewModel();
            }
            
            $oAccion = $this->seguridad->getPost($oAccion);
            $oAccion->cBuscar = $this->input->post('sSearch');
            $oAccion->iOrdenadoPor = $this->input->post('iSortCol_0');
            $oAccion->iODireccion = strtoupper($this->input->post('sSortDir_0'));
            
            FindSessionHelper::add("FindAccion", $oAccion, $this->aAmbito);

            $aData = $this->Accion_model->getAll($oAccion, true);
            $aResponse = parent::paginacion($aData);

            echo json_encode($aResponse);
        } catch (Exception $exc) {
            $this->message->clearMessages();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $exc, FALSE);
        }
    }
    
    public function getParams()
    {
        $oAccion= FindSessionHelper::get("FindAccion");
        if ($oAccion == NULL)
        {
            $oAccion = new Accion_ViewModel();
        }
        if (!$oAccion->iODireccion)
        {
            $oAccion->iODireccion = 'ASC';
        }

        $result = parent::getParams();
        $result['filters'] = $oAccion;

        if ($oAccion->iOrdenadoPor !== false)
        {
            $result['sort'] = $oAccion->iOrdenadoPor;
        }
        else
        {
            $result['sort'] = '';
        }
        $result['sortAlign'] = $oAccion->iODireccion;
        $result['find'] = $oAccion->cBuscar;
        
        $result['tituloReporte'] = lang("acciones_titulo");
        return $result;
    }

    /**
     * Metodo que visualiza la forma al momento de agregar o editar un nuevo registro
     * 
     * @access public
     * @param numeric $idAccion Identificador del ${aplicativo} a editar
     * @return void 
     */
    public function forma()
    {
        try {

            $idModulo = $this->uri->segment(3);
            $oModulo = $this->_moduloListado($idModulo);

            $idAccion = $this->uri->segment(4);
            $oAccion = new Accion_ViewModel();
            $oAccion->idModulo = $idModulo;

            if ($idAccion)
            {
                $oAccion->idAccion = $idAccion;
                $dbAccion = $this->Accion_model->get($oAccion);

                if (is_object($dbAccion))
                {
                    $oAccion = $this->seguridad->dbToView($oAccion, $dbAccion);
                    $aParams["aMigajaPan"] = array($oModulo->cAlias, lang("general_accion_forma"));
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
                $aParams["aMigajaPan"] = array( $oModulo->cAlias, lang("general_accion_forma"));
            }

            $aParams['oAccion'] = $oAccion;
            $aParams['aCombosForma'] = $this->_getCombosForma();
            $aParams['aMenuCatalogo'] = $this->seguridad->getMenu(MENU_CONFIGURACION);
            $this->layout->view($this->cModulo . '/forma_view', $aParams);
        } catch (Exception $exc) {
            $this->message->clearMessages();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $exc);
        }
    }

    /**
     * Metodo encargado de insertar un nuevo ${aplicativo} en la base de datos
     * 
     * @access public
     * @return json objeto json 
     *      [success] booelan true si la el proceso se realizo con exito false si no 
     *      [failuare] boolean true si hubo un fallo al momento de realizar el proceso
     *      [noLogin] boolean true si la sesion del usuario se ha terminado
     *      [data] array datos a devolver a la vista para continuar con el proceso
     */
    public function insertar()
    {
        $aResponse = array("success" => false, "failure" => true, "nologin" => false, "data" => array());
        try {
            $oAccion = new Accion_ViewModel();
            $oAccion = $this->seguridad->getPost($oAccion);

            if ($this->_validarForma())
            {
                $this->seguridad->startTransaction();
                $this->Accion_model->insertar($oAccion);

                $this->message->addExito(lang("general_evento_insertar"));
                $this->session->set_flashdata('_MESSAGES', json_encode($this->message->toJsonObject()));
                $this->seguridad->setBitacora($this->cModulo, $this->cAccion, lang("bitacora_evento_insertar"), array(UsuarioHelper::get('cNombreCompleto'), $this->cModulo, $oAccion->cNombre));

                $aResponse["success"] = true;
                $aResponse["failure"] = false;
                $this->seguridad->commitTransaction();
            }
        } catch (Exception $ex) {
            $this->message->clearMessages();
            $this->message->addError(lang("general_error"));
            $this->seguridad->rollbackTransaction();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $ex, FALSE);
        }

        $aResponse["data"]["messages"] = $this->message->toJsonObject();
        echo json_encode($aResponse);
    }

    /**
     * Metodo encargado de actualizar un nuevo ${aplicativo} en la base de datos
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
        $aResponse = array("success" => false, "failure" => true, "nologin" => false, "data" => array());

        try {

            $oAccion = new Accion_ViewModel();
            $oAccion = $this->seguridad->getPost($oAccion);

            if ($this->_validarForma())
            {
                $this->seguridad->startTransaction();
                $dbAccion = $this->Accion_model->find(array("idAccion" => $oAccion->idAccion));
                if (is_object($dbAccion))
                {
                    $this->Accion_model->actualizar($oAccion);

                    $this->message->addExito(lang("general_evento_actualizar"));
                    $this->session->set_flashdata('_MESSAGES', json_encode($this->message->toJsonObject()));
                    $this->seguridad->setBitacora($this->cModulo, $this->cAccion, lang("bitacora_evento_actualizar"), array(UsuarioHelper::get('cNombreCompleto'), $this->cModulo, $oAccion->cNombre));

                    $aResponse["success"] = true;
                    $aResponse["failure"] = false;
                }
                else
                {
                    $this->message->addError(lang("general_error_registro_no_encontrado"));
                }
                $this->seguridad->commitTransaction();
            }
        } catch (Exception $ex) {
            $this->message->clearMessages();
            $this->message->addError(lang("general_error"));
            $this->seguridad->rollbackTransaction();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $ex, FALSE);
        }

        $aResponse["data"]["messages"] = $this->message->toJsonObject();
        echo json_encode($aResponse);
    }

    /**
     * Metodo encargado de habilitar un ${aplicativo} en la base de datos
     * 
     * @access public
     * @return json objeto json 
     *      [success] booelan true si la el proceso se realizo con exito false si no 
     *      [failuare] boolean true si hubo un fallo al momento de realizar el proceso
     *      [noLogin] boolean true si la sesion del usuario se ha terminado
     *      [data] array datos a devolver a la vista para continuar con el proceso
     */
    public function habilitar()
    {
        $aResponse = array("success" => false, "failure" => true, "noLogin" => false, "data" => array());

        try {
            $this->seguridad->startTransaction();
            $oAccion = new Accion_ViewModel();
            $oAccion->idAccion = $this->input->post("id", true);

            $oAccion = $this->Accion_model->get($oAccion);
            if (is_object($oAccion))
            {
                $this->Accion_model->habilitar($oAccion);

                $this->message->addExito(lang("general_evento_habilitar"));
                $this->seguridad->setBitacora($this->cModulo, $this->cAccion, lang("bitacora_evento_habilitar"), array(UsuarioHelper::get('cNombreCompleto'), $this->cModulo, $oAccion->cNombre));

                $aResponse["success"] = true;
                $aResponse["failure"] = false;
            }
            else
            {
                $this->message->addError(lang("general_error_registro_no_encontrado"));
            }
            $this->seguridad->commitTransaction();
        } catch (Exception $exc) {
            $this->message->clearMessages();
            $this->message->addError(lang("general_error"));
            $this->seguridad->rollbackTransaction();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $exc, FALSE);
        }

        $aResponse["data"]['messages'] = $this->message->toJsonObject();
        echo json_encode($aResponse);
    }

    public function deshabilitar()
    {
        $aResponse = array("success" => false, "failure" => true, "noLogin" => false, "data" => array());

        try {
            $this->seguridad->startTransaction();
            $oAccion = new Accion_ViewModel();
            $oAccion->idAccion = (int) $this->input->post("id", true);

            $oAccion = $this->Accion_model->get($oAccion);
            if (is_object($oAccion))
            {
                $this->Accion_model->deshabilitar($oAccion);

                $this->message->addExito(lang("general_evento_deshabilitar"));
                $this->seguridad->setBitacora($this->cModulo, $this->cAccion, lang("bitacora_evento_deshabilitar"), array(UsuarioHelper::get('cNombreCompleto'), $this->cModulo, $oAccion->cNombre));

                $aResponse["success"] = true;
                $aResponse["failure"] = false;
            }
            else
            {
                $this->message->addError(lang("general_error_registro_no_encontrado"));
            }
            $this->seguridad->commitTransaction();
        } catch (Exception $exc) {
            $this->message->clearMessages();
            $this->message->addError(lang("general_error"));
            $this->seguridad->rollbackTransaction();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $exc, FALSE);
        }

        $aResponse["data"]['messages'] = $this->message->toJsonObject();
        echo json_encode($aResponse);
    }

    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="Metodos Privados">

    /**
     * Metodo que se encarga de validar la forma de ${aplicativo}
     * 
     * @access private
     * @return boolean $bValudacion true si las reglas de validacion son correctas
     */
    private function _validarForma()
    {
        $this->load->library("form_validation");

        $this->form_validation->set_rules("cNombre", lang("acciones_nombre"), "trim|required|alpha_underscore|max_length[45]|callback_verificarNombre");
        $this->form_validation->set_rules("cAlias", lang("acciones_alias"), "trim|required|max_length[45]|callback_verificarAlias");
        $this->form_validation->set_rules("cDescripcion", lang("acciones_descripcion"), "trim|required|max_length[250]");
        $this->form_validation->set_rules("idTipoAccion", lang("acciones_tipo_accion"), "trim|required|max_length[45]");

        $this->form_validation->set_message("verificarNombre", lang("acciones_verificar_nombre"));
        $this->form_validation->set_message("verificarAlias", lang("acciones_verificar_alias"));

        $bValidacion = $this->form_validation->run();
        if (!$bValidacion)
        {
            $aErrores = $this->form_validation->getErrores();
            $this->message->addErrors($aErrores);
        }

        return $bValidacion;
    }

    /**
     * Retorna un array con los valores que tendran los campos Select 
     * @access public
     * @return array 
     *      ["TipoAccion"] array con los diferentes tipos de acciones
     */
    private function _getCombosForma()
    {

        $this->load->model("Sistema/TipoAccion_model");

        $aCombos = array();
        $aTipoAccion = $this->TipoAccion_model->findAll(array("bHabilitado" => SI));
        $aCombos["TipoAccion"] = getComboForma('idTipoAccion', 'cAlias', $aTipoAccion);

        return $aCombos;
    }

    /**
     * Metodo que se encarga de validar si el identificador del modulo sea un modulo valido
     * 
     * @access public
     * @param int $idModulo identidicador del modulo al que pertenecen las acciones
     * @return $oModulo
     */
    private function _moduloListado($idModulo = 0)
    {
        $dbModulo = null;
        if ($idModulo > 0)
        {
            $oModulo = new Modulo_ViewModel();
            $oModulo->idModulo = (int) $idModulo;
            $dbModulo = $this->Modulo_model->get($oModulo);

            if (!is_object($dbModulo))
            {
                $this->message->addError(lang("accion_error_modulo_no_existe"));
                $this->session->set_flashdata('_MESSAGES', json_encode($this->message->toJsonObject()));
                redirect("modulos/listado");
            }
        }
        else
        {
            $this->message->addError(lang("accion_error_modulo_no_existe"));
            $this->session->set_flashdata('_MESSAGES', json_encode($this->message->toJsonObject()));
            redirect("modulos/listado");
        }

        return $dbModulo;
    }

    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="Metodos Otros">

    public function verificarNombre()
    {
        $aParams = array();
        $aParams["idModulo"] = $this->input->post("idModulo");
        $aParams["cNombre"] = $this->input->post("cNombre");

        if ($this->input->post("idModulo"))
        {
            $aParams["idAccion <>"] = $this->input->post("idAccion");
        }

        $iExiste = $this->Accion_model->findCount($aParams);
        return ($iExiste > 0) ? FALSE : TRUE;
    }

    public function verificarAlias()
    {
        $aParams = array();
        $aParams["idModulo"] = $this->input->post("idModulo");
        $aParams["cAlias"] = $this->input->post("cAlias");

        if ($this->input->post("idModulo"))
        {
            $aParams["idAccion <>"] = $this->input->post("idAccion");
        }

        $iExiste = $this->Accion_model->findCount($aParams);
        return ($iExiste > 0) ? FALSE : TRUE;
    }

    // </editor-fold>
}
