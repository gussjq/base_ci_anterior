<?php

/*
 * Usuario
 * 
 * Descripcion del aplicativo
 * 
 * @package controller
 * @author Nombre programador <correo@ejemplo.com>
 * @create date 06-09-2014
 * @update date 
 */

class Perfil extends MY_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model("Sistema/Usuario_model");
        $this->load->model("Sistema/Rol_model");

        $this->load->library("ViewModels/Usuario_ViewModel");
        $this->load->library("ViewModels/Rol_ViewModel");
        $this->load->library("email");
    }

    // <editor-fold defaultstate="collapsed" desc="Metodos Catalogo">

    /**
     * Metodo que visualiza la forma al momento de agregar o editar un nuevo registro
     * 
     * @access public
     * @param numeric $idAccion Identificador del Usuario a editar
     * @return void 
     */
    public function forma() {
        try {
            $oUsuario = UsuarioHelper::get();
            $cRutaImagen = getRutaImagenDefault();

            if (!is_object($oUsuario)) {
                $this->message->addError(lang("general_error_registro_no_encontrado"));
                $this->session->set_flashdata('_MESSAGES', json_encode($this->message->toJsonObject()));
                redirect("dashboard/index");
            }

            if (!empty($oUsuarioSesion->cImagen)) {
                $cRutaImagen = getRutaImagen(DIRECTORIO_USUARIOS . $oUsuario->cImagen);
            }

            $aParams = array();
            $aParams['oUsuario'] = $oUsuario;
            $aParams['cRutaImagen'] = $cRutaImagen;
            $aParams["aCombosForma"] = $this->_getCombosForma();
            $aParams["cTitulo"] = lang("usuario_titulo");
            $aParams['aMigajaPan'] = array(lang("general_accion_forma"));
            $aParams['aMenuCatalogo'] = $this->seguridad->getMenu(MENU_SECCION_USUARIO);

            $this->layout->view($this->cModulo . '/forma_view', $aParams);
        } catch (Exception $exc) {
            $this->message->clearMessages();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $exc);
        }
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
    public function actualizar() {
        $aResponse = array("success" => false, "failure" => true, "noLogin" => false, "data" => array());
        try {
            $oUsuario = new Usuario_ViewModel();
            $oUsuario = $this->seguridad->getPost($oUsuario);
            if ($this->_validarForma()) {
                $this->seguridad->startTransaction();

                $dbUsuario = $this->Usuario_model->find(array(
                    "idUsuario" => UsuarioHelper::get("idUsuario"),
                    "bBorradoLogico" => NO,
                    "bBloqueado" => NO,
                    "bHabilitado" => SI
                ));

                if (is_object($dbUsuario)) {
                    $dbUsuario->idIdioma = $oUsuario->idIdioma;
                    $dbUsuario->cContrasena = (!empty($oUsuario->cContrasena)) ? $this->seguridad->encriptar($oUsuario->cContrasena) : $dbUsuario->cContrasena;
                    $dbUsuario->bNuevo = NO;
                    $this->Usuario_model->actualizar($dbUsuario);

                    UsuarioHelper::set($dbUsuario->idUsuario);
                    IdiomaHelper::set($dbUsuario->idIdioma);
                    
                    if($this->input->post("cContrasena")){
                        $this->email->actualizar_contrasena(UsuarioHelper::get("cNombreCompleto"), $dbUsuario->cCorreo, $oUsuario->cContrasena);
                        $this->message->addExito(lang("email_enviado_actualizar_contrasena"));
                    }
                    
                    $this->seguridad->setBitacora($this->cModulo, $this->cAccion, lang("bitacora_evento_actualizar"), array(UsuarioHelper::get('cNombreCompleto'), $this->cModulo, $oUsuario->cNombre));
                    $this->message->addExito(lang("general_evento_actualizar"));
                    
                    $aResponse["success"] = true;
                    $aResponse["failure"] = false;
                    
                    $this->session->set_flashdata('_MESSAGES', json_encode($this->message->toJsonObject()));
                    
                } else {
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
    private function _validarForma() {
        $this->load->library("form_validation");

        $this->form_validation->set_rules("cContrasena", lang("usuario_contrasena"), "trim|max_length[" . ConfigHelper::get('iMaxContrasena') . "]|min_length[" . ConfigHelper::get('iMinContrasena') . "]|matches[cConfirmarContrasena]|callback_verificarContrasena");
        $this->form_validation->set_rules("cConfirmarContrasena", lang("usuario_confirmar_contrasena"), "trim|callback_verificarConfirmarContrasena");
        $this->form_validation->set_rules("idIdioma", lang("usuario_idioma"), "trim|required");
        
        $this->form_validation->set_message("verificarContrasena", lang("usuario_error_verificar_contrasena"));
        $this->form_validation->set_message("verificarConfirmarContrasena", lang("usuario_error_verificar_confirmar_contrasena"));

        $bValidacion = $this->form_validation->run();
        if (!$bValidacion) {
            $aErrores = $this->form_validation->getErrores();
            $this->message->addErrors($aErrores);
        }
        return $bValidacion;
    }

    private function _getCombosForma() {
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
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="Otros Metodos">
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
