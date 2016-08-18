<?php

/*
 * Idiomas
 * 
 * Catalogo que administra los idiomas dados de alta en el sistema.
 * 
 * @package controller
 * @author DEVELOPER 1 <correo@developer1>
 * @create date 06-09-2014
 */

class Idiomas extends MY_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model("Sistema/Idioma_model");
        $this->load->library("ViewModels/Idioma_ViewModel");
    }

    // <editor-fold defaultstate="collapsed" desc="Metodos Catalogo">

    /**
     * Pantalla del listado de Idioma
     * 
     * @access public
     * @return void 
     */
    public function listado()
    {
        try {
            $oIdioma = new Idioma_ViewModel();
            $oIdioma = $this->seguridad->getPost($oIdioma);
            
            FindSessionHelper::add("FindIdioma", $oIdioma, $this->aAmbito);

            $aParams = array();
            $aParams["oIdioma"] = $oIdioma;
            $aParams["cTitulo"] = lang("idiomas_titulo");
            $aParams["idIdiomaConf"] = ConfigHelper::get("idIdioma");
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
            $oIdioma = FindSessionHelper::get("FindIdioma");
            if (!$oIdioma)
            {
                 $oIdioma = new Idioma_ViewModel();
            }
           
            $oIdioma = $this->seguridad->getPost($oIdioma);
            $oIdioma->cBuscar = $this->input->post('sSearch');
            $oIdioma->iOrdenadoPor = $this->input->post('iSortCol_0');
            $oIdioma->iODireccion = strtoupper($this->input->post('sSortDir_0'));
            
            FindSessionHelper::add("FindIdioma", $oIdioma, $this->aAmbito);

            $aData = $this->Idioma_model->getAll($oIdioma, true);
            $aResponse = parent::paginacion($aData);

            echo json_encode($aResponse);
        } catch (Exception $exc) {
            $this->message->clearMessages();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $exc, FALSE);
        }
    }
    
    public function getParams()
    {
        $oIdioma= FindSessionHelper::get("FindIdioma");
        if ($oIdioma == NULL)
        {
            $oIdioma = new Idioma_ViewModel();
        }
        if (!$oIdioma->iODireccion)
        {
            $oIdioma->iODireccion = 'ASC';
        }

        $result = parent::getParams();
        $result['filters'] = $oIdioma;

        if ($oIdioma->iOrdenadoPor !== false)
        {
            $result['sort'] = $oIdioma->iOrdenadoPor;
        }
        else
        {
            $result['sort'] = '';
        }
        $result['sortAlign'] = $oIdioma->iODireccion;
        $result['find'] = $oIdioma->cBuscar;
        
        $result['tituloReporte'] = lang("idiomas_titulo");
        return $result;
    }

    /**
     * Metodo que visualiza la forma al momento de agregar o editar un nuevo registro
     * 
     * @access public
     * @param numeric $idAccion Identificador del Idioma a editar
     * @return void 
     */
    public function forma()
    {
        try {
            $idIdioma = $this->uri->segment(3);
            $oIdioma = new Idioma_ViewModel();

            if ($idIdioma)
            {
                $oIdioma->idIdioma = $idIdioma;
                $dbIdioma = $this->Idioma_model->get($oIdioma);
                if (is_object($dbIdioma))
                {
                    $oIdioma = $this->seguridad->dbToView($oIdioma, $dbIdioma);
                }
                else
                {
                    $this->message->addError(lang("general_error_registro_no_encontrado"));
                    $this->session->set_flashdata('_MESSAGES', json_encode($this->message->toJsonObject()));
                    redirect($this->cModulo . "/listado");
                }
            }

            $aParams = array();
            $aParams['oIdioma'] = $oIdioma;
            $aParams["cTitulo"] = lang("idiomas_titulo");
            $aParams['aMigajaPan'] = array(lang("general_accion_forma"));
            $aParams['aMenuCatalogo'] = $this->seguridad->getMenu(MENU_CONFIGURACION);
            $this->layout->view($this->cModulo . '/forma_view', $aParams);
        } catch (Exception $exc) {
            $this->message->clearMessages();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $exc);
        }
    }

    /**
     * Metodo encargado de insertar un nuevo Idioma en la base de datos
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
            $oIdioma = new Idioma_ViewModel();
            $oIdioma = $this->seguridad->getPost($oIdioma);

            if ($this->_validarForma())
            {
                $this->seguridad->startTransaction();
                $this->Idioma_model->insertar($oIdioma);


                $this->message->addExito(lang("general_evento_insertar"));
                $this->session->set_flashdata('_MESSAGES', json_encode($this->message->toJsonObject()));
                $this->seguridad->setBitacora($this->cModulo, $this->cAccion, lang("bitacora_evento_insertar"), array(UsuarioHelper::get('cNombreCompleto'), $this->cModulo, $oIdioma->cNombre));

                $aResponse["success"] = true;
                $aResponse["failure"] = false;
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

    /**
     * Metodo encargado de actualizar un Idioma en la base de datos
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
            $oIdioma = new Idioma_ViewModel();
            $oIdioma = $this->seguridad->getPost($oIdioma);

            if ($this->_validarForma())
            {
                $this->seguridad->startTransaction();
                $dbIdioma = $this->Idioma_model->find(array("idIdioma" => $oIdioma->idIdioma));
                if (is_object($dbIdioma))
                {
                    $this->Idioma_model->actualizar($oIdioma);

                    $this->message->addExito(lang("general_evento_actualizar"));
                    $this->session->set_flashdata('_MESSAGES', json_encode($this->message->toJsonObject()));
                    $this->seguridad->setBitacora($this->cModulo, $this->cAccion, lang("bitacora_evento_actualizar"), array(UsuarioHelper::get('cNombreCompleto'), $this->cModulo, $oIdioma->cNombre));

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

    /**
     * Metodo encargado de habilitar Idioma en la base de datos
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
        $aResponse = array("success" => false, "failure" => true, "nologin" => false, "data" => array());

        try {
            $this->load->library("ViewModels/Configuracion_ViewModel");

            $this->seguridad->startTransaction();
            $oIdioma = new Idioma_ViewModel();
            $oIdioma->idIdioma = $this->input->post("id", true);

            $oIdioma = $this->Idioma_model->get($oIdioma);
            if (is_object($oIdioma))
            {

                $oConfiguracion = new Configuracion_ViewModel();
                $oConfiguracion->cClave = "idIdioma";
                $oConfiguracion->cValor = $oIdioma->idIdioma;
                $this->Configuracion_model->actualizar($oConfiguracion);

                $this->message->addExito(lang("idiomas_evento_seleccionar_idioma"));
                $this->seguridad->setBitacora($this->cModulo, $this->cAccion, lang("bitacora_evento_habilitar"), array(UsuarioHelper::get('cNombreCompleto'), $this->cModulo, $oIdioma->cNombre));

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
     * Metodo que se encarga de validar la forma cuendo se agrega o edita un registro,
     * retorna un valor boolean
     * 
     * @access private
     * @return boolean $bValidation retorna true si pasa las reglas de validacion
     */
    private function _validarForma()
    {
        $this->load->library("form_validation");

        $this->form_validation->set_rules("cNombre", lang("idiomas_nombre"), "trim|required|max_length[45]|callback_verificarNombre");
        $this->form_validation->set_rules("cAlias", lang("idiomas_alias"), "trim|required|max_length[45]|callback_verificarAlias");
        $this->form_validation->set_rules("cNombreArchivo", lang("idiomas_archivo"), "trim|required|max_length[45]|callback_verificarNombreArchivo");

        $this->form_validation->set_message("verificarNombre", lang("idiomas_error_existe_nombre"));
        $this->form_validation->set_message("verificarAlias", lang("idiomas_error_existe_alias"));
        $this->form_validation->set_message("verificarNombreArchivo", lang("idiomas_error_existe_archivo"));

        $bValidacion = $this->form_validation->run();
        if (!$bValidacion)
        {
            $aErrores = $this->form_validation->getErrores();
            $this->message->addErrors($aErrores);
        }
        return $bValidacion;
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Metodos Otros">
    public function verificarNombre()
    {

        $aParams = array();
        $aParams["cNombre"] = $this->input->post("cNombre");

        if ($this->input->post("idIdioma"))
        {
            $aParams["idIdioma <>"] = $this->input->post("idIdioma");
        }

        $iExiste = $this->Idioma_model->findCount($aParams);
        return ($iExiste > 0) ? FALSE : TRUE;
    }

    public function verificarAlias()
    {

        $aParams = array();
        $aParams["cAlias"] = $this->input->post("cAlias");

        if ($this->input->post("idIdioma"))
        {
            $aParams["idIdioma <>"] = $this->input->post("idIdioma");
        }

        $iExiste = $this->Idioma_model->findCount($aParams);
        return ($iExiste > 0) ? FALSE : TRUE;
    }

    public function verificarNombreArchivo()
    {
        $aParams = array();
        $aParams["cNombreArchivo"] = $this->input->post("cNombreArchivo");

        if ($this->input->post("idIdioma"))
        {
            $aParams["idIdioma <>"] = $this->input->post("idIdioma");
        }

        $iExiste = $this->Idioma_model->findCount($aParams);
        return ($iExiste > 0) ? FALSE : TRUE;
    }

    // </editor-fold>
}
