<?php

/*
 * Usuario
 * 
 * Descripcion del Usuario
 * 
 * @package controller
 * @author DEVELOPER 1 <correo@developer1>
 * @create date 06-09-2014
 * @update date 
 */

class Usuarios extends MY_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model("Sistema/Usuario_model");
        $this->load->model("Sistema/Rol_model");

        $this->load->library("ViewModels/Usuario_ViewModel");
        $this->load->library("ViewModels/Rol_ViewModel");
        $this->load->library("email");
    }

    // <editor-fold defaultstate="collapsed" desc="Metodos Catalogo">

    /**
     * Pantalla del listado de Usuario
     * 
     * @access public
     * @return void 
     */
    public function listado()
    {
        try {
            $oUsuario = new Usuario_ViewModel();
            $oUsuario = $this->seguridad->getPost($oUsuario);
            
            FindSessionHelper::add("FindUsuario", $oUsuario, $this->aAmbito);

            $aParams = array();
            $aParams["oUsuario"] = $oUsuario;
            $aParams["cTitulo"] = lang("usuario_titulo");
            $aParams["aCombosForma"] = $this->_getCombosForma();
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
            $oUsuario = FindSessionHelper::get("FindUsuario");
            if (!$oUsuario)
            {
                $oUsuario = new Usuario_ViewModel();
            }

            $oUsuario = new Usuario_ViewModel();
            $oUsuario = $this->seguridad->getPost($oUsuario);

            $oUsuario->cBuscar = $this->input->post('sSearch');
            $oUsuario->iOrdenadoPor = $this->input->post('iSortCol_0');
            $oUsuario->iODireccion = strtoupper($this->input->post('sSortDir_0'));
            
            FindSessionHelper::add("FindUsuario", $oUsuario, $this->aAmbito);

            $aData = $this->Usuario_model->getAll($oUsuario, true);
            $aResponse = parent::paginacion($aData);
        } catch (Exception $exc) {
            $this->message->clearMessages();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $exc, FALSE);
        }
        
        echo json_encode($aResponse);
    }
    
    public function getParams()
    {
        $oUsuarios = FindSessionHelper::get("FindUsuarios");
        if ($oUsuarios == NULL)
        {
            $oUsuarios = new Usuario_ViewModel();
        }
        if (!$oUsuarios->iODireccion)
        {
            $oUsuarios->iODireccion = 'ASC';
        }

        $result = parent::getParams();
        $result['filters'] = $oUsuarios;

        if ($oUsuarios->iOrdenadoPor !== false)
        {
            $result['sort'] = $oUsuarios->iOrdenadoPor;
        }
        else
        {
            $result['sort'] = '';
        }
        $result['sortAlign'] = $oUsuarios->iODireccion;
        $result['find'] = $oUsuarios->cBuscar;
        
        $result['tituloReporte'] = lang("usuario_titulo");
        return $result;
    }

    /**
     * Metodo que visualiza la forma al momento de agregar o editar un nuevo registro
     * 
     * @access public
     * @param numeric $idAccion Identificador del Usuario a editar
     * @return void 
     */
    public function forma()
    {
        try {

            $idUsuario = $this->uri->segment(3);
            $oUsuario = new Usuario_ViewModel();
            $cRutaImagen = getRutaImagenDefault();

            if ($idUsuario)
            {
                $dbUsuario = $this->Usuario_model->find(array("idUsuario" => $idUsuario, "bHabilitado" => SI, "bBorradoLogico" => NO));
                if (is_object($dbUsuario))
                {
                    $oUsuario = $this->seguridad->dbToView($oUsuario, $dbUsuario);
                    if (!empty($oUsuario->cImagen))
                    {
                        $cRutaImagen = getRutaImagen(DIRECTORIO_USUARIOS . $oUsuario->cImagen);
                    }
                }
                else
                {
                    $this->message->addError(lang("general_error_registro_no_encontrado"));
                    $this->session->set_flashdata('_MESSAGES', json_encode($this->message->toJsonObject()));
                    redirect($this->cModulo . "/listado");
                }
            }

            $aParams = array();
            $aParams['oUsuario'] = $oUsuario;
            $aParams['cRutaImagen'] = $cRutaImagen;
            $aParams["aCombosForma"] = $this->_getCombosForma();
            $aParams['aMigajaPan'] = array(lang("general_accion_forma"));
            $aParams['aMenuCatalogo'] = $this->seguridad->getMenu(MENU_CONFIGURACION);

            $this->layout->view($this->cModulo . '/forma_view', $aParams);
        } catch (Exception $exc) {
            $this->message->clearMessages();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $exc);
        }
    }

    /**
     * Metodo encargado de insertar un nuevo Usuario en la base de datos
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
            $oUsuario = new Usuario_ViewModel();
            $oUsuario = $this->seguridad->getPost($oUsuario);

            if ($this->_validarForma())
            {
                $this->seguridad->startTransaction();
                
                $cContrasena = (!empty($oUsuario->cContrasena)) ? $oUsuario->cContrasena : $this->seguridad->generarContrasena();
                $oUsuario->cContrasena = $this->seguridad->encriptar($cContrasena);
                
                $this->Usuario_model->insertar($oUsuario);
                $this->email->nuevo_usuario($oUsuario, $cContrasena);

                if (!empty($oUsuario->cImagen))
                {
                    $this->_moverArchivo("", $oUsuario->cImagen);
                }

                $this->message->addExito(lang("general_evento_insertar"));
                $this->session->set_flashdata('_MESSAGES', json_encode($this->message->toJsonObject()));
                $this->seguridad->setBitacora($this->cModulo, $this->cAccion, lang("bitacora_evento_insertar"), array(UsuarioHelper::get('cNombreCompleto'), $this->cModulo, $oUsuario->cNombre));

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
     * Metodo encargado de actualizar un Usuario en la base de datos
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
            $oUsuario = new Usuario_ViewModel();
            $oUsuario = $this->seguridad->getPost($oUsuario);
            if ($this->_validarForma())
            {
                $this->seguridad->startTransaction();
                $dbUsuario = $this->Usuario_model->find(array("idUsuario" => $oUsuario->idUsuario, "bBorradoLogico" => NO, "bHabilitado" => SI));
                if (is_object($dbUsuario))
                {
                    $oUsuario->cContrasena = (!empty($oUsuario->cContrasena)) ? $this->seguridad->encriptar($oUsuario->cContrasena) : $dbUsuario->cContrasena;
                    $this->Usuario_model->actualizar($oUsuario);
                    
                    if($this->input->post("cContrasena"))
                    {
                        $this->email->actualizar_contrasena(UsuarioHelper::get("cNombreCompleto"), $dbUsuario->cCorreo, $oUsuario->cContrasena);
                        $this->message->addExito(lang("email_enviado_actualizar_contrasena"));
                    }

                    if (!empty($oUsuario->cImagen))
                    {
                        $this->_moverArchivo($dbUsuario->cImagen, $oUsuario->cImagen);
                    }

                    $this->message->addExito(lang("general_evento_actualizar"));
                    $this->session->set_flashdata('_MESSAGES', json_encode($this->message->toJsonObject()));
                    $this->seguridad->setBitacora($this->cModulo, $this->cAccion, lang("bitacora_evento_actualizar"), array(UsuarioHelper::get('cNombreCompleto'), $this->cModulo, $oUsuario->cNombre));

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
     * Metodo encargado de elimiar un Usuario en la base de datos
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
            $oUsuario = new Usuario_ViewModel();
            $oUsuario->idUsuario = $this->input->post("id", true);

            $oUsuario = $this->Usuario_model->get($oUsuario);
            if (is_object($oUsuario))
            {
                $this->Usuario_model->eliminar($oUsuario);

                $this->message->addExito(lang("general_evento_eliminar"));
                $this->seguridad->setBitacora($this->cModulo, $this->cAccion, lang("bitacora_evento_eliminar"), array(UsuarioHelper::get('cNombreCompleto'), $this->cModulo, $oUsuario->cNombre));

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
     * Metodo encargado de habilitar Usuario en la base de datos
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
            $oUsuario = new Usuario_ViewModel();
            $oUsuario->idUsuario = $this->input->post("id", true);
            $oUsuario = $this->Usuario_model->get($oUsuario);
            if (is_object($oUsuario))
            {
                $this->Usuario_model->habilitar($oUsuario);
                $this->message->addExito(lang("general_evento_habilitar"));
                $this->seguridad->setBitacora($this->cModulo, $this->cAccion, lang("bitacora_evento_habilitar"), array(UsuarioHelper::get('cNombreCompleto'), $this->cModulo, $oUsuario->cNombre));

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
     * Metodo encargado de deshabilitar un Usuario en la base de datos
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
            $oUsuario = new Usuario_ViewModel();
            $oUsuario->idUsuario = (int) $this->input->post("id", true);

            $oUsuario = $this->Usuario_model->get($oUsuario);
            if (is_object($oUsuario))
            {
                $this->Usuario_model->deshabilitar($oUsuario);

                $this->message->addExito(lang("general_evento_deshabilitar"));
                $this->seguridad->setBitacora($this->cModulo, $this->cAccion, lang("bitacora_evento_deshabilitar"), array(UsuarioHelper::get('cNombreCompleto'), $this->cModulo, $oUsuario->cNombre));

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
        
    // <editor-fold defaultstate="collapsed" desc="Metodos Ajax">
    public function cargarArchivoAjax()
    {
        try {
            $aResponse = array("success" => false, "data" => array());
            $cDirTemporal = getDocumentRoot() . DIRECTORIO_TEMPORAL;

            $aConfig['upload_path'] = DIRECTORIO_TEMPORAL;
            $aConfig['allowed_types'] = 'png|jpg|gif';
            $aConfig['encrypt_name'] = true;

            $this->load->library('upload', $aConfig);
            if ($this->upload->do_upload("cImagenUpload"))
            {
                $aResponse["success"] = true;
                $aResponse["data"]["aFileData"] = $this->upload->data();
            }
            else
            {
                $this->message->addError($this->upload->display_errors());
            }
        } catch (Exception $exc) {
            //guardar en bitacora error 
            $this->message->clearMessages();
            $this->message->addError(lang("general_error_subir_archivo"));
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $exc, false);
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

        $this->form_validation->set_rules("cNombre", lang("usuario_nombre"), "trim|required|max_length[45]");
        $this->form_validation->set_rules("cApellidoPaterno", lang("usuario_apellido_paterno"), "trim|required|max_length[45]");
        $this->form_validation->set_rules("cApellidoMaterno", lang("usuario_apellido_materno"), "trim|required|max_length[45]");
        $this->form_validation->set_rules("cCorreo", lang("usuario_correo"), "trim|required|max_length[150]|valid_email|callback_verificarCorreo");
        $this->form_validation->set_rules("cContrasena", lang("usuario_contrasena"), "trim|max_length[" . ConfigHelper::get('iMaxContrasena') . "]|min_length[" . ConfigHelper::get('iMinContrasena') . "]|callback_verificarContrasena");
        $this->form_validation->set_rules("cConfirmarContrasena", lang("usuario_confirmar_contrasena"), "trim|matches[cContrasena]|callback_verificarConfirmarContrasena");
        $this->form_validation->set_rules("idRol", lang("usuario_rol"), "trim|required|integer");
        $this->form_validation->set_rules("idIdioma", lang("usuario_idioma"), "trim|required|integer");

        $this->form_validation->set_message("verificarCorreo", lang('usuario_error_verificarcorreo'));
        $this->form_validation->set_message("verificarContrasena", lang('usuario_error_verificar_contrasena'));
        $this->form_validation->set_message("verificarConfirmarContrasena", lang('usuario_error_verificar_confirmar_contrasena'));

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
     * @return type
     */
    private function _getCombosForma()
    {

        $aCombos = array();

        $oRol = new Rol_ViewModel();
        $oRol->bHabilitado = SI;
        $aCombos["aRoles"] = getComboForma("idRol", "cNombre", $this->Rol_model->getAll($oRol));
        
        $this->load->model("Sistema/Idioma_model");
        $this->load->library("ViewModels/Idioma_ViewModel");

        $oIdioma = new Idioma_ViewModel();
        $aIdioma = $this->Idioma_model->getAll($oIdioma);
        $aCombos["aIdiomas"] = getComboForma("idIdioma", "cAlias", $aIdioma);

        return $aCombos;
    }

    /**
     * 
     * @param type $cArchivoAnterior
     * @param type $cArchivoNuevo
     */
    private function _moverArchivo($cArchivoAnterior = "", $cArchivoNuevo = "")
    {
        $cImagenTemporal = getDocumentRoot() . DIRECTORIO_TEMPORAL . $cArchivoNuevo;

        if (@file_exists($cImagenTemporal))
        {

            $cImagenAnterior = getDocumentRoot() . DIRECTORIO_USUARIOS . $cArchivoAnterior;
            $cImagenNuevo = getDocumentRoot() . DIRECTORIO_USUARIOS . $cArchivoNuevo;

            if (copy($cImagenTemporal, $cImagenNuevo))
            {
                if (@file_exists($cImagenAnterior) && (!empty($cArchivoAnterior)))
                {
                    unlink($cImagenAnterior);
                }
                if (@file_exists($cImagenTemporal) && (!empty($cArchivoNuevo)))
                {
                    unlink($cImagenTemporal);
                }
            }
        }
    }

    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="Metodos Otros">

    /**
     * 
     * @return boolean 
     */
    public function verificarCorreo()
    {

        $aParams = array();
        $aParams["cCorreo"] = $this->input->post("cCorreo");

        if ($this->input->post("idUsuario"))
        {
            $aParams["idUsuario <>"] = $this->input->post("idUsuario");
        }

        $iExiste = $this->Usuario_model->findCount($aParams);
        return ($iExiste > 0) ? FALSE : TRUE;
    }
    
    /**
     * 
     * @return boolean
     */
    public function verificarContrasena()
    {
        $bResult = true;
        if ($this->input->post("cConfirmarContrasena"))
        {
            if (!$this->input->post("cContrasena"))
            {
                $bResult = false;
            }
        }
        return $bResult;
    }

    /**
     * 
     * @return boolean
     */
    public function verificarConfirmarContrasena()
    {
        $bResult = true;
        if ($this->input->post("cContrasena"))
        {
            if (!$this->input->post("cConfirmarContrasena"))
            {
                $bResult = false;
            }
        }
        return $bResult;
    }

    // </editor-fold>
}
