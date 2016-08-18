<?php

/*
 * Menu
 * 
 * Descripcion del aplicativo
 * 
 * @package controller
 * @author Nombre programador <correo@ejemplo.com>
 * @create date 06-09-2014
 * @update date 
 */

class Menu extends MY_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model("Sistema/Menu_model");
        $this->load->library("ViewModels/Menu_ViewModel");
    }

    // <editor-fold defaultstate="collapsed" desc="Metodos Catalogo">

    /**
     * Pantalla del listado de Menu
     * 
     * @access public
     * @return void 
     */
    public function listado()
    {
        try {
            $oMenu = new Menu_ViewModel();
            $oMenu = $this->seguridad->getPost($oMenu);
            
            FindSessionHelper::add("FindMenu", $oMenu, $this->aAmbito);

            $aParams = array();
            $aParams["oMenu"] = $oMenu;
            $aParams["cTitulo"] = lang("Menu_titulo");
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
            $oMenu = FindSessionHelper::get("FindMenu");
            if (!$oMenu)
            {
                $oMenu = new Menu_ViewModel();
            }
            
            $oMenu = $this->seguridad->getPost($oMenu);
            $oMenu->cBuscar = $this->input->post('sSearch');
            $oMenu->iOrdenadoPor = $this->input->post('iSortCol_0');
            $oMenu->iODireccion = strtoupper($this->input->post('sSortDir_0'));
            
            FindSessionHelper::add("FindMenu", $oMenu, $this->aAmbito);

            $aData = $this->Menu_model->getAll($oMenu, true);
            $aResponse = parent::paginacion($aData);

            echo json_encode($aResponse);
        } catch (Exception $exc) {
            $this->message->clearMessages();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $exc, FALSE);
        }
    }
    
    public function getParams()
    {
        $oMenu= FindSessionHelper::get("FindMenu");
        if ($oMenu == NULL)
        {
            $oMenu = new Menu_ViewModel();
        }
        if (!$oMenu->iODireccion)
        {
            $oMenu->iODireccion = 'ASC';
        }

        $result = parent::getParams();
        $result['filters'] = $oMenu;

        if ($oMenu->iOrdenadoPor !== false)
        {
            $result['sort'] = $oMenu->iOrdenadoPor;
        }
        else
        {
            $result['sort'] = '';
        }
        $result['sortAlign'] = $oMenu->iODireccion;
        $result['find'] = $oMenu->cBuscar;
        
        $result['tituloReporte'] = lang("Menu_titulo");
        return $result;
    }

    /**
     * Metodo que visualiza la forma al momento de agregar o editar un nuevo registro
     * 
     * @access public
     * @param numeric $idAccion Identificador del Menu a editar
     * @return void 
     */
    public function forma()
    {
        try {
            $idMenu = $this->uri->segment(3);
            $oMenu = new Menu_ViewModel();

            if (!$idMenu)
            {
                $this->message->addError(lang("general_error_registro_no_encontrado"));
                $this->session->set_flashdata('_MESSAGES', json_encode($this->message->toJsonObject()));
                redirect($this->cModulo . "/listado");
            }

            $oMenu->idMenu = $idMenu;
            $dbMenu = $this->Menu_model->get($oMenu);

            if (is_object($dbMenu))
            {
                $oMenu = $this->seguridad->dbToView($oMenu, $dbMenu);
            }
            else
            {
                $this->message->addError(lang("general_error_registro_no_encontrado"));
                $this->session->set_flashdata('_MESSAGES', json_encode($this->message->toJsonObject()));
                redirect($this->cModulo . "/listado");
            }

            $aParams = array();
            $aParams['oMenu'] = $oMenu;
            $aParams["cTitulo"] = lang("Menu_titulo");
            $aParams['aMigajaPan'] = array(lang("general_accion_forma"));
            $aParams['aMenuCatalogo'] = $this->seguridad->getMenu(MENU_CONFIGURACION);

            $this->layout->view($this->cModulo . '/forma_view', $aParams);
        } catch (Exception $exc) {
            $this->message->clearMessages();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $exc);
        }
    }

    /**
     * Metodo encargado de actualizar un Menu en la base de datos
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
            $oMenu = new Menu_ViewModel();
            $oMenu = $this->seguridad->getPost($oMenu);

            if ($this->_validarForma())
            {
                $this->seguridad->startTransaction();
                $dbMenu = $this->Menu_model->find(array("idMenu" => $oMenu->idMenu));
                if (is_object($dbMenu))
                {
                    $this->Menu_model->actualizar($oMenu);

                    $this->message->addExito(lang("general_evento_actualizar"));
                    $this->session->set_flashdata('_MESSAGES', json_encode($this->message->toJsonObject()));
                    $this->seguridad->setBitacora($this->cModulo, $this->cAccion, lang("bitacora_evento_actualizar"), array(UsuarioHelper::get('cNombreCompleto'), $this->cModulo, $oMenu->cNombre));

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
     * Metodo encargado de habilitar Menu en la base de datos
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
            $this->seguridad->startTransaction();
            $oMenu = new Menu_ViewModel();
            $oMenu->idMenu = $this->input->post("id", true);

            $oMenu = $this->Menu_model->get($oMenu);
            if (is_object($oMenu))
            {
                $this->Menu_model->habilitar($oMenu);


                $this->message->addExito(lang("general_evento_habilitar"));
                $this->seguridad->setBitacora($this->cModulo, $this->cAccion, lang("bitacora_evento_habilitar"), array(UsuarioHelper::get('cNombreCompleto'), $this->cModulo, $oMenu->cNombre));

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

    /**
     * Metodo encargado de deshabilitar un Menu en la base de datos
     * 
     * @access public
     * @return json objeto json 
     *      [success] booelan true si la el proceso se realizo con exito false si no 
     *      [failuare] boolean true si hubo un fallo al momento de realizar el proceso
     *      [noLogin] boolean true si la sesion del usuario se ha terminado
     *      [data] array datos a devolver a la vista para continuar con el proceso
     */
    public function deshabilitar()
    {
        $aResponse = array("success" => false, "failure" => true, "noLogin" => false, "data" => array());

        try {
            $this->seguridad->startTransaction();
            $oMenu = new Menu_ViewModel();
            $oMenu->idMenu = (int) $this->input->post("id", true);

            $oMenu = $this->Menu_model->get($oMenu);
            if (is_object($oMenu))
            {
                $this->Menu_model->deshabilitar($oMenu);


                $this->message->addExito(lang("general_evento_deshabilitar"));
                $this->seguridad->setBitacora($this->cModulo, $this->cAccion, lang("bitacora_evento_deshabilitar"), array(UsuarioHelper::get('cNombreCompleto'), $this->cModulo, $oMenu->cNombre));

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

        $this->form_validation->set_rules("cNombre", lang("Menu_nombre"), "trim|required|max_length[45]|callback_verificarNombre");
        $this->form_validation->set_rules("cDescripcion", lang("Menu_descripcion_menu"), "trim|required|max_length[240]");

        $this->form_validation->set_message("verificarNombre", lang("Menu_verificarNombre"));

        $bValidacion = $this->form_validation->run();
        if (!$bValidacion)
        {
            $aErrores = $this->form_validation->getErrores();
            $this->message->addErrors($aErrores);
        }
        return $bValidacion;
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Otros Metodos">
    public function verificarNombre()
    {
        $aParams = array();
        $aParams["cNombre"] = $this->input->post("cNombre");

        if ($this->input->post("idMenu"))
        {
            $aParams["idMenu <>"] = $this->input->post("idMenu");
        }

        $iExiste = $this->Menu_model->findCount($aParams);
        return ($iExiste > 0) ? FALSE : TRUE;
    }

    // </editor-fold>
}
