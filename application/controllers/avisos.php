<?php

/*
 * Avisos
 * 
 * Catalogo que administra los avisos dados de alta en el sistema.
 * 
 * @package controller
 * @author DEVELOPER 1 <correo@developer1>
 * @create date 22-05-2015
 */
class Avisos extends MY_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model("Sistema/Aviso_model");
        $this->load->library("ViewModels/Aviso_ViewModel");
        
        $this->load->model("Sistema/Receptor_model");
        $this->load->library("ViewModels/Receptor_ViewModel");
        
        $this->load->model("Sistema/Usuario_model");
        $this->load->library("ViewModels/Usuario_ViewModel");
        
        $this->load->model("Sistema/Rol_model");
        $this->load->library("ViewModels/Rol_ViewModel");
    }

    // <editor-fold defaultstate="collapsed" desc="Metodos Catalogo">

    /**
     * Pantalla del listado de Aviso
     * 
     * @access public
     * @return void 
     */
    public function listado()
    {
        try {
            
            $oAviso = new Aviso_ViewModel();
            $oAviso = $this->seguridad->getPost($oAviso);
            
            FindSessionHelper::add("FindAviso", $oAviso, $this->aAmbito);

            $aParams = array();
            $aParams["oAviso"] = $oAviso;
            $aParams["cTitulo"] = lang("avisos_titulo");
            $aParams['aMigajaPan'] = array(lang("general_accion_listado"));
            $aParams['aMenuCatalogo'] = $this->seguridad->getMenu(MENU_SECCION_USUARIO);

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
            $oAviso = FindSessionHelper::get("FindAviso");
            if (!$oAviso)
            {
                 $oAviso = new Aviso_ViewModel();
            }
           
            $oAviso = $this->seguridad->getPost($oAviso);
            $oAviso->cBuscar = $this->input->post('sSearch');
            $oAviso->iOrdenadoPor = $this->input->post('iSortCol_0');
            $oAviso->iODireccion = strtoupper($this->input->post('sSortDir_0'));
            
            FindSessionHelper::add("FindAviso", $oAviso, $this->aAmbito);

            $aData = $this->Aviso_model->getAll($oAviso, true);
            $aResponse = parent::paginacion($aData);

            echo json_encode($aResponse);
        } catch (Exception $exc) {
            $this->message->clearMessages();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $exc, FALSE);
        }
    }
    
    public function getParams()
    {
        $oAviso= FindSessionHelper::get("FindAviso");
        if ($oAviso == NULL)
        {
            $oAviso = new Aviso_ViewModel();
        }
        if (!$oAviso->iODireccion)
        {
            $oAviso->iODireccion = 'ASC';
        }

        $result = parent::getParams();
        $result['filters'] = $oAviso;

        if ($oAviso->iOrdenadoPor !== false)
        {
            $result['sort'] = $oAviso->iOrdenadoPor;
        }
        else
        {
            $result['sort'] = '';
        }
        $result['sortAlign'] = $oAviso->iODireccion;
        $result['find'] = $oAviso->cBuscar;
        
        $result['tituloReporte'] = lang("avisos_titulo");
        return $result;
    }

    /**
     * Metodo que visualiza la forma al momento de agregar o editar un nuevo registro
     * 
     * @access public
     * @param numeric $idAccion Identificador del Aviso a editar
     * @return void 
     */
    public function forma()
    {
        try {
            $oAviso = new Aviso_ViewModel();

            if ($this->uri->segment(3))
            {
                $dbAviso = $this->Aviso_model->find(array(
                    "idAviso" => $this->uri->segment(3), "bBorradoLogico" => NO
                ));
                
                if (is_object($dbAviso))
                {
                    $oAviso = $this->seguridad->dbToView($oAviso, $dbAviso);
                }
                else
                {
                    $this->message->addError(lang("general_error_registro_no_encontrado"));
                    $this->session->set_flashdata('_MESSAGES', json_encode($this->message->toJsonObject()));
                    redirect($this->cModulo . "/listado");
                }
            }

            $aParams = array();
            $aParams['oAviso'] = $oAviso;
            $aParams["cTitulo"] = lang("avisos_titulo");
            $aParams['aMigajaPan'] = array(lang("general_accion_forma"));
            $aParams['aMenuCatalogo'] = $this->seguridad->getMenu(MENU_CONFIGURACION);
            $this->layout->view($this->cModulo . '/forma_view', $aParams);
            
        } catch (Exception $exc) {
            $this->message->clearMessages();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $exc);
        }
    }

    /**
     * Metodo encargado de insertar un nuevo Aviso en la base de datos
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
            $oAviso = new Aviso_ViewModel();
            $oAviso = $this->seguridad->getPost($oAviso);

            if ($this->_validarForma())
            {
                $this->seguridad->startTransaction();

                $this->Aviso_model->create();
                $oAviso->idUsuario = UsuarioHelper::get("idUsuario");
                $oAviso->iEstatus = AVISOS_ESTATUS_ENVIADO;
                $oAviso->dtFechaCreacion = date("Y-m-d H:i:s");
                $idAviso = $this->Aviso_model->save($oAviso);
                
                $aReceptor = $this->_prepararDatos($idAviso,$oAviso);
                $this->Receptor_model->saveBatch($aReceptor);

                $this->message->addExito(lang("general_evento_insertar"));
                $this->seguridad->setBitacora($this->cModulo, $this->cAccion, lang("bitacora_evento_insertar"), array(UsuarioHelper::get('cNombreCompleto'), $this->cModulo, $oAviso->cTitulo));

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
     * Metodo encargado de elimina Aviso en la base de datos
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
            $oReceptor = $this->Receptor_model->find(array(
                    "idReceptor" => $this->input->post("id", true), "idUsuario" => UsuarioHelper::get("idUsuario")
            ));
            
            if (is_object($oReceptor))
            {   
                $this->seguridad->startTransaction();
                
                $oAviso = $this->Aviso_model->find(array(
                    "idAviso" => $oReceptor->idAviso, "iEstatus"=> AVISOS_ESTATUS_ENVIADO
                ));
                
                $this->Receptor_model->remove($oReceptor->idReceptor);
                
                $this->message->addExito(lang("general_evento_eliminar"));
                $this->seguridad->setBitacora($this->cModulo, $this->cAccion, lang("bitacora_evento_eliminar"), array(UsuarioHelper::get('cNombreCompleto'), $this->cModulo, $oAviso->cTitulo));

                $aResponse["success"] = true;
                $aResponse["failure"] = false;
                
                $this->seguridad->commitTransaction();
            }
            else
            {
                $this->message->addError(lang("general_error_registro_no_encontrado"));
            }
        } catch (Exception $exc) {
            $this->message->clearMessages();
            $this->message->addError(lang("general_error"));
            $this->seguridad->rollbackTransaction();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $exc, FALSE);
        }

        $aResponse["data"]['messages'] = $this->message->toJsonObject();
        echo json_encode($aResponse);
    }
    
    public function visualizar()
    {
        $aResponse = array("success" => false, "failure" => true, "nologin" => false, "data" => array());
        
        try {   
            $oReceptor = $this->Receptor_model->find(array(
                    "idReceptor" => $this->input->post("idReceptor"), "idUsuario" => UsuarioHelper::get("idUsuario")
            ));
            
            if (is_object($oReceptor))
            {   
                $this->seguridad->startTransaction();
                
                $oAviso = $this->Aviso_model->find(array(
                    "idAviso" => $oReceptor->idAviso, "iEstatus"=> AVISOS_ESTATUS_ENVIADO
                ));
                
                if (is_object($oAviso))
                {
                    $oReceptor->bLeido = SI;
                    $oReceptor->dtFechaLeido = date("Y-m-d H:i:s");
                    $this->Receptor_model->save($oReceptor, $oReceptor->idReceptor);
                    $this->seguridad->setBitacora($this->cModulo, $this->cAccion, lang("aviso_bitacora_evento_visualizar"), array(UsuarioHelper::get('cNombreCompleto'), $oAviso->cTitulo));

                    $aResponse["success"] = true;
                    $aResponse["failure"] = false;
                    $aResponse["data"]["aviso"] = $oAviso;
                    $aResponse["data"]["iNumeroAvisos"] = UsuarioHelper::getNumeroAvisos();
                }
                else
                {
                    $this->message->addError(lang("general_error_registro_no_encontrado"));
                }

                $this->seguridad->commitTransaction();
            }
            else
            {
                $this->message->addError(lang("general_error_registro_no_encontrado"));
            }
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
     * 
     */
    public function buscarUsuarios()
    {
        $aResponse = array("iTipoUsuario" => null, "data" => array());
        $cTerm = $this->input->post("term");
        $iTipoUsuario = $this->input->post("iTipoUsuario");
        $aItems = $this->input->post("aItems");

        if (!empty($cTerm) && !empty($iTipoUsuario))
        {
            switch ($iTipoUsuario)
            {
                case AVISOS_BUSCAR_USUARIO:
                    $aResponse["iTipoUsuario"] = $iTipoUsuario;
                    $aResponse["data"] = $this->Usuario_model->getAutoComplete($cTerm, $aItems);
                    break;
                case AVISOS_BUSCAR_NIVEL_ACCESO:
                    $aResponse["iTipoUsuario"] = $iTipoUsuario;
                    $aResponse["data"] = $this->Rol_model->getAutoComplete($cTerm, $aItems);
                    break;
                default :
                    $aResponse["iTipoUsuario"] = $iTipoUsuario;
                    $aResponse["data"] = array();
                    break;
            }
        }

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

        $this->form_validation->set_rules("cTitulo", lang("avisos_nombre_titulo"), "trim|required|max_length[255]");
        $this->form_validation->set_rules("txCuerpo", lang("avisos_cuerpo"), "trim|required");
        $this->form_validation->set_rules("iTipoUsuario", lang("avisos_para"), "trim|required");
        $this->form_validation->set_rules("cListaUsuarios", lang("avisos_usuarios"), "trim|required");

        $bValidacion = $this->form_validation->run();
        if (!$bValidacion)
        {
            $aErrores = $this->form_validation->getErrores();
            $this->message->addErrors($aErrores);
        }
        return $bValidacion;
    }
    
    /**
     * 
     * @param type $idAviso
     * @param type $oAviso
     * @return \Receptor_ViewModel
     */
    private function _prepararDatos($idAviso, $oAviso)
    {
        $aData = array();
        $aUsuarios = array();
        
        switch ($oAviso->iTipoUsuario)
        {
            case AVISOS_BUSCAR_USUARIO:
                $aUsuarios = $this->Usuario_model->whereIn("idUsuario", $oAviso->cListaUsuarios, array(
                    "bBorradoLogico" => NO, "bHabilitado" => SI, "bBloqueado" => NO
                ));
                break;
            case AVISOS_BUSCAR_NIVEL_ACCESO:
                $aUsuarios = $this->Usuario_model->whereIn("idRol", $oAviso->cListaUsuarios, array(
                    "bBorradoLogico" => NO, "bHabilitado" => SI, "bBloqueado" => NO
                ));
                break;
            case AVISOS_BUSCAR_TODOS:
                $aUsuarios = $this->Usuario_model->findAll(array(
                    "bBorradoLogico" => NO, "bHabilitado" => SI, "bBloqueado" => NO
                ));
                break;
        }

        foreach ($aUsuarios AS $oUsuario)
        {
            $oReceptor = new Receptor_ViewModel();
            $oReceptor->idAviso = $idAviso;
            $oReceptor->bLeido = NO;
            $oReceptor->idUsuario = $oUsuario->idUsuario;

            $aData[] = $oReceptor;
        }

        return $aData;
    }

    // </editor-fold>
}
