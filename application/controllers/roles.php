<?php

/*
 * Roles
 * 
 * Descripcion del Rol
 * 
 * @package controller
 * @author Nombre programador <correo@ejemplo.com>
 * @create date 06-09-2014
 * @update date 
 */

class Roles extends MY_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model("Sistema/Rol_model");
        $this->load->model("Sistema/Modulo_model");
        $this->load->model("Sistema/Accion_model");
        $this->load->model("Sistema/RolAccion_model");

        $this->load->library("ViewModels/Rol_ViewModel");
        $this->load->library("ViewModels/Modulo_ViewModel");
        $this->load->library("ViewModels/Accion_ViewModel");
        $this->load->library("ViewModels/RolAccion_ViewModel");
    }

    // <editor-fold defaultstate="collapsed" desc="Metodos Catalogo">

    /**
     * Pantalla del listado de Rol
     * 
     * @access public
     * @return void 
     */
    public function listado()
    {
        try {
            $oRol = new Rol_ViewModel();
            $oRol = $this->seguridad->getPost($oRol);
            
             FindSessionHelper::add("FindRol", $oRol, $this->aAmbito);

            $aParams = array();
            $aParams["oRol"] = $oRol;
            $aParams["cTitulo"] = lang("roles_titulo");
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
        $aResponse = array();
        try {
            $oRol = FindSessionHelper::get("FindRol");
            if (!$oRol)
            {
                $oRol = new Rol_ViewModel();
            }
            
            $oRol = new Rol_ViewModel();
            $oRol = $this->seguridad->getPost($oRol);
            $oRol->cBuscar = $this->input->post('sSearch');
            $oRol->iOrdenadoPor = $this->input->post('iSortCol_0');
            $oRol->iODireccion = strtoupper($this->input->post('sSortDir_0'));
            
            FindSessionHelper::add("FindRol", $oRol, $this->aAmbito);

            $aData = $this->Rol_model->getAll($oRol, true);
            $aResponse = parent::paginacion($aData);
            
        } catch (Exception $exc) {
            $this->message->clearMessages();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $exc, FALSE);
        }
        
        echo json_encode($aResponse);
    }
    
    public function getParams()
    {
        $oRol= FindSessionHelper::get("FindRol");
        if ($oRol == NULL)
        {
            $oRol = new Rol_ViewModel();
        }
        if (!$oRol->iODireccion)
        {
            $oRol->iODireccion = 'ASC';
        }

        $result = parent::getParams();
        $result['filters'] = $oRol;

        if ($oRol->iOrdenadoPor !== false)
        {
            $result['sort'] = $oRol->iOrdenadoPor;
        }
        else
        {
            $result['sort'] = '';
        }
        $result['sortAlign'] = $oRol->iODireccion;
        $result['find'] = $oRol->cBuscar;
        
        $result['tituloReporte'] = lang("roles_titulo");
        return $result;
    }

    /**
     * Metodo que visualiza la forma al momento de agregar o editar un nuevo registro
     * 
     * @access public
     * @param numeric $idAccion Identificador del Rol a editar
     * @return void 
     */
    public function forma()
    {
        try {
            $this->load->helper("arbol_acciones");

            $idRol = $this->uri->segment(3);
            $oRol = new Rol_ViewModel();
            $aAcciones = array();

            if ($idRol)
            {
                $oRol->idRol = $idRol;
                $dbRol = $this->Rol_model->get($oRol);
                if (is_object($dbRol))
                {
                    $oRol = $this->seguridad->dbToView($oRol, $dbRol);

                    $oRolAccion = new RolAccion_ViewModel();
                    $oRolAccion->idRol = $oRol->idRol;
                    $aAcciones = $this->RolAccion_model->getAll($oRolAccion);
                }
                else
                {
                    $this->message->addError(lang("general_error_registro_no_encontrado"));
                    $this->session->set_flashdata('_MESSAGES', json_encode($this->message->toJsonObject()));
                    redirect($this->cModulo . "/listado");
                }
            }

            $aParams = array();
            $aParams['oRol'] = $oRol;
            $aParams['aModulos'] = $this->_cargaModulosAcciones();
            $aParams['aRolAccion'] = json_encode($aAcciones);
            $aParams["cTitulo"] = lang("roles_titulo");
            $aParams['aMigajaPan'] = array(lang("general_accion_forma"));
            $aParams['aMenuCatalogo'] = $this->seguridad->getMenu(MENU_CONFIGURACION);

            $this->layout->view($this->cModulo . '/forma_view', $aParams);
        } catch (Exception $exc) {
            $this->message->clearMessages();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $exc);
        }
    }

    /**
     * Metodo encargado de insertar un nuevo Rol en la base de datos
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
            $oRol = new Rol_ViewModel();
            $oRol = $this->seguridad->getPost($oRol);
            $aAcciones = $this->input->post("idAccion");

            if ($this->_validarForma())
            {
                $this->seguridad->startTransaction();
                $idRol = $this->Rol_model->insertar($oRol);
                $this->_insertarRolAccion($idRol, $aAcciones);

                $this->message->addExito(lang("general_evento_insertar"));
                $this->session->set_flashdata('_MESSAGES', json_encode($this->message->toJsonObject()));
                $this->seguridad->setBitacora($this->cModulo, $this->cAccion, lang("bitacora_evento_insertar"), array(UsuarioHelper::get('cNombreCompleto'), $this->cModulo, $oRol->cNombre));

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
     * Metodo encargado de actualizar un Rol en la base de datos
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
            $oRol = new Rol_ViewModel();
            $oRol = $this->seguridad->getPost($oRol);
            $aAcciones = $this->input->post("idAccion");

            if ($this->_validarForma())
            {
                $this->seguridad->startTransaction();
                $dbRol = $this->Rol_model->find(array("idRol" => $oRol->idRol));
                if (is_object($dbRol))
                {
                    $this->Rol_model->actualizar($oRol);
                    $this->_insertarRolAccion($oRol->idRol, $aAcciones);

                    $this->message->addExito(lang("general_evento_actualizar"));
                    $this->session->set_flashdata('_MESSAGES', json_encode($this->message->toJsonObject()));
                    $this->seguridad->setBitacora($this->cModulo, $this->cAccion, lang("bitacora_evento_actualizar"), array(UsuarioHelper::get('cNombreCompleto'), $this->cModulo, $oRol->cNombre));

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
     * Metodo encargado de elimiar un Rol en la base de datos
     * 
     * @access public
     * @return json objeto json 
     *      [success] booelan true si la el proceso se realizo con exito false si no 
     *      [failuare] boolean true si hubo un fallo al momento de realizar el proceso
     *      [noLogin] boolean true si la sesion del usuario se ha terminado
     *      [data] array datos a devolver a la vista para continuar con el proceso
     */
    public function eliminar()
    {
        $aResponse = array("success" => false, "failure" => true, "nologin" => false, "data" => array());

        try {
            $this->seguridad->startTransaction();
            $oRol = new Rol_ViewModel();
            $oRol->idRol = $this->input->post("id", true);

            $oRol = $this->Rol_model->get($oRol);
            if (is_object($oRol))
            {
                $this->Rol_model->eliminar($oRol);

                $this->message->addExito(lang("general_evento_eliminar"));
                $this->seguridad->setBitacora($this->cModulo, $this->cAccion, lang("bitacora_evento_eliminar"), array(UsuarioHelper::get('cNombreCompleto'), $this->cModulo, $oRol->cNombre));

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
     * Metodo encargado de habilitar Rol en la base de datos
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
            $oRol = new Rol_ViewModel();
            $oRol->idRol = $this->input->post("id", true);

            $oRol = $this->Rol_model->get($oRol);
            if (is_object($oRol))
            {
                $this->Rol_model->habilitar($oRol);

                $this->message->addExito(lang("general_evento_habilitar"));
                $this->seguridad->setBitacora($this->cModulo, $this->cAccion, lang("bitacora_evento_habilitar"), array(UsuarioHelper::get('cNombreCompleto'), $this->cModulo, $oRol->cNombre));

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
     * Metodo encargado de deshabilitar un Rol en la base de datos
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
            $oRol = new Rol_ViewModel();
            $oRol->idRol = (int) $this->input->post("id", true);

            $oRol = $this->Rol_model->get($oRol);
            if (is_object($oRol))
            {
                $this->Rol_model->deshabilitar($oRol);

                $this->message->addExito(lang("general_evento_deshabilitar"));
                $this->seguridad->setBitacora($this->cModulo, $this->cAccion, lang("bitacora_evento_deshabilitar"), array(UsuarioHelper::get('cNombreCompleto'), $this->cModulo, $oRol->cNombre));

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

        $this->form_validation->set_rules("cNombre", lang("roles_nombre"), "trim|required|max_length[45]|callback_verificarNombre");
        $this->form_validation->set_rules("cDescripcion", lang("roles_descripcion_roles"), "trim|required|max_length[250]");

        $this->form_validation->set_message("verificarNombre", lang("roles_verificarNombre"));
        $this->form_validation->set_message("verificarAlias", lang("roles_verificarAlias"));

        $bValidacion = $this->form_validation->run();
        if (!$bValidacion)
        {
            $aErrores = $this->form_validation->getErrores();
            $this->message->addErrors($aErrores);
        }
        return $bValidacion;
    }

    /**
     * Metodo tipo privado que encarga de insertar la relacion entre el rol y las acciones
     * que seran asociadas a dicho rol 
     * 
     * @access public
     * @param int $idRol identificador del rol al que se agregaran las acciones
     * @param array $aAcciones listado de acciones que se agregaran al rol
     * @return void
     */
    private function _insertarRolAccion($idRol, $aAcciones)
    {
        $this->RolAccion_model->eliminar($idRol);
        if (($aAcciones != false) && (count($aAcciones) > 0))
        {
            foreach ($aAcciones as $idAccion)
            {
                $oRolAccion = new RolAccion_ViewModel();
                $oRolAccion->idRol = $idRol;
                $oRolAccion->idAccion = $idAccion;
                $this->RolAccion_model->insertar($oRolAccion);
            }
        }
    }

    /**
     * Metodo que se encarga de generar un array de los modulos con sus acciones relacionadas
     * es usado en el catalogo de roles para cargar las acciones
     * 
     * @access public
     * @return array
     */
    public function _cargaModulosAcciones()
    {
        $this->load->model("Sistema/Modulo_model");
        $this->load->model("Sistema/Accion_model");

        $this->load->library("ViewModels/Modulo_ViewModel");
        $this->load->library("ViewModels/Accion_ViewModel");

        $oModuloVM = new Modulo_ViewModel();
        $dbModulos = $this->Modulo_model->getAll($oModuloVM);

        $aModulos = array();
        foreach ($dbModulos as $modulo)
        {
            $oModulo = new Modulo_ViewModel();
            $oModulo = $this->seguridad->dbToView($oModulo, $modulo);

            $oAccionVM = new Accion_ViewModel();
            $oAccionVM->idModulo = $oModulo->idModulo;
            $oAccionVM->not = array("a.idTipoAccion" => array(TIPO_ACCION_PUBLICA, TIPO_ACCION_PUBLICA_AJAX));
            $dbAcciones = $this->Accion_model->getAll($oAccionVM);

            $aAcciones = array();
            if (count($dbAcciones) > 0)
            {
                foreach ($dbAcciones AS $accion)
                {
                    $oAccion = new Accion_ViewModel();
                    $oAccion = $this->seguridad->dbToView($oAccion, $accion);

                    array_push($aAcciones, $oAccion);
                }
                $oModulo->aAcciones = $aAcciones;
                array_push($aModulos, $oModulo);
            }
        }

        return $aModulos;
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Metodos Otros">
    public function verificarNombre()
    {

        $aParams = array();
        $aParams["cNombre"] = $this->input->post("cNombre");

        if ($this->input->post("idRol"))
        {
            $aParams["idRol <>"] = $this->input->post("idRol");
        }

        $iExiste = $this->Rol_model->findCount($aParams);
        return ($iExiste > 0) ? FALSE : TRUE;
    }

    // </editor-fold>
}
