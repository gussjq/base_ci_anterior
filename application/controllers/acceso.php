<?php

/**
 * Clase que se encarga de validar el acceso de los usuarios al sistema 
 * asi como tambien de recuperar la contrasena de los usuarios registrados previamente en el sistema
 * 
 * @author DEVELOPER 1 <correo@developer1> cel: <1111111111>
 * @creado 04/12/2014
 * @package controllers
 */
class Acceso extends MY_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model("Sistema/Configuracion_model");
        $this->load->model("Sistema/Usuario_model");

        $this->load->library("ViewModels/Usuario_ViewModel");
        $this->load->library("email");
        
        $this->load->library("PeriodosNomina", array(), "PeriodosNomina");
        $this->load->library("Nominas", array(), "Nominas");
        
    }

    // <editor-fold defaultstate="collapsed" desc="Metodos acceso">

    /**
     * Metodo que se encarga de cargar la forma de login para que los usuarios puedan iniciar sesion
     * 
     * @access public
     * @return void No retorna ningun valor
     * 
     */
    public function forma()
    {
        $this->_validarSessionIniciada();
        $this->layout->setLayout(LAYOUT_DEFAULT . LAYOUT_LOGIN)
                ->view($this->cModulo . '/acceso_view', array("cTitulo" => lang("acceso_titulo")));
    }

    /**
     * Metodo que se encarga de validar el inicio de secion para ingresar al sistema
     * 
     * @access public
     * @params string $cCorreo Correo electronico del usuario a iniciar sesion
     * @params string $cContrasena Contraseña o password del usuario que ingresa al sistema
     * @return array json $aResponse Se retorna un array con los siguientes valores
     *      -success true para identificar si el proceso se raliza con exito
     *      -failure false si durante el proceso se produce un fallo
     *      -data array con valores que se le pasa a la vista
     *          -redirect url a donde se dirigira al usuario despues de haber iniciado sesion
     */
    public function ingresar()
    {
        $aResponse = array("success" => false, "failure" => true, "data" => array());

        try {
            $oUsuario = new Usuario_ViewModel();
            $oUsuario = $this->seguridad->getPost($oUsuario);
            if ($this->_validarForma())
            {
                $dbUsuario = new Usuario_ViewModel();
                $dbUsuario->bHabilitado = SI;
                $dbUsuario->cCorreo = $oUsuario->cCorreo;
                $dbUsuario = $this->Usuario_model->get($dbUsuario);
                if (is_object($dbUsuario))
                {
                    if (empty($dbUsuario->cRecuperar))
                    {
                        $iMinutosIntentosAcceso = ConfigHelper::get('iMinutosIntentosAcceso');
                        $iIntentosAcceso = ConfigHelper::get('iIntentosAcceso');
                        if ($this->_validarNumeroIntentos($dbUsuario, $iMinutosIntentosAcceso))
                        {
                            $oUsuario->cContrasena = $this->seguridad->encriptar($oUsuario->cContrasena);
                            if (trim($dbUsuario->cContrasena) == trim($oUsuario->cContrasena))
                            {
                                $this->seguridad->startTransaction();
                                $this->Usuario_model->recetearIntentosAcceso($dbUsuario->idUsuario);
                                $cRedirect = "";
                                if ($this->session->userdata("_REDIRECT"))
                                {
                                    $cRedirect = $this->session->userdata("_REDIRECT");
                                    $this->session->unset_userdata("_REDIRECT");
                                }
                                else
                                {
                                    $cRedirect = base_url() . MENU_LINK_DEFAULT;
                                }
                                $this->seguridad->iniciarSesion($dbUsuario);
                                $this->seguridad->setBitacora($this->cModulo, $this->cAccion, lang("bitacora_evento_iniciarsesion"), array($dbUsuario->cNombreCompleto . " (" . $dbUsuario->cCorreo . " ) "));
                                $this->seguridad->commitTransaction();
                                $aResponse["success"] = true;
                                $aResponse["data"]["redirect"] = $cRedirect;
                            }
                            else
                            {
                                $this->Usuario_model->incrementarIntentosAcceso($dbUsuario->idUsuario, $iIntentosAcceso);
                                $this->message->addError(lang("acceso_error"));
                            }
                        }
                        else
                        {
                            $this->Usuario_model->incrementarIntentosAcceso($dbUsuario->idUsuario, $iIntentosAcceso);
                            $this->message->addError(stringRemplace(lang("acceso_error_numero_intentos"), array($iMinutosIntentosAcceso)));
                        }
                    }
                    else
                    {
                        $this->message->addError(lang("acceso_error_restablecer_contrasena_activado"));
                        $this->email->restablecer_contrasena($dbUsuario);
                    }
                }
                else
                {
                    $this->message->addError(lang("acceso_error"));
                }
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
     * Metodo que se encarga de cerrar la sesion del usuario en el sistema
     * 
     * @access public
     * @return void no retorna datos
     */
    public function cerrarSesion()
    {
        $aSesion = $this->session->all_userdata();
        $this->session->unset_userdata($aSesion);

        session_destroy();
        redirect('acceso/forma');
    }
    
    private function _validarNumeroIntentos($dbUsuario, $iMinutosIntentosAcceso)
    {
        $bAcceso = true;
        if ($dbUsuario->bBloqueado == 1)
        {
            $iMinIntentosAcceso = (int) $iMinutosIntentosAcceso;
            $iMinDiff = (int) (strtotime(date('Y-m-d H:i:s')) - strtotime($dbUsuario->dtFechaIntentosAcceso)) / 60;
            if ($iMinDiff < $iMinIntentosAcceso)
            {
                $bAcceso = false;
            }
        }
        return $bAcceso;
    }

    /**
     * Metod que se encarga de validar si un usuario ya ha iniciado sesion, es utilizado
     * en la forma de acceso para impedir que un usuario ya registrado pueda ingresar a esta pantalla
     * 
     * @access private
     * @return void no retorna ningun valor
     */
    private function _validarSessionIniciada()
    {
        if (isset($_SESSION['_USUARIO']))
        {
            redirect(MENU_LINK_DEFAULT);
        }
    }

    /**
     * Metodo que se encarga de validar la forma de inicio de sesion
     * 
     * @param string $cCorreo Correo electronico del usuario que ingresa al sistema
     * @param string $cContrasena Contraseña del usuario que ingresa al sistema
     * @return boolean $bValidacion 
     */
    private function _validarForma()
    {
        $this->load->library("form_validation");

        $this->form_validation->set_rules("cCorreo", lang("acceso_email"), "trim|required|valid_email");
        $this->form_validation->set_rules("cContrasena", lang("acceso_contrasena"), "trim|required|max_length[45]");

        $bValidacion = $this->form_validation->run();
        if (!$bValidacion)
        {
            $aErrores = $this->form_validation->getErrores();
            $this->message->addErrors($aErrores);
        }
        return $bValidacion;
    }

    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="Metodos restablecer">

    /**
     * Metodo que se encarga de generar al usuario una llave o identificador para realizar el proceoso
     * de restablecer contraseña 
     * 
     * @param string $cCorreo Correo electronico del usuario al que se le generara una llave para restablecer su contraseña,
     *      Sele encvia un correo electronico con los pasos a seguir para restablecer su contraseña
     * @return array json $aResponse Se retorna un array con los siguientes valores
     *      -success true para identificar si el proceso se raliza con exito
     *      -failure false si durante el proceso se produce un fallo
     *      -data array con valores que se le pasa a la vista
     *          -messages mensajes de error o exito 
     */
    public function restablecerContrasenaAjax()
    {
        $aResponse = array("success" => false, "failure" => true, "data" => array());
        try {
            $oUsuario = new Usuario_ViewModel();
            $oUsuario = $this->seguridad->getPost($oUsuario);
            if ($this->_validarFormaRestablecerContrasena())
            {
                $this->seguridad->startTransaction();
                $dbUsuario = $this->Usuario_model->find(array("cCorreo" => $oUsuario->cCorreo,"bHabilitado" => SI, "bBorradoLogico" => NO));
                if (is_object($dbUsuario))
                {
                    $dbUsuario->cRecuperar = $this->seguridad->encriptar(time());
                    $this->Usuario_model->guardaRecuperar($dbUsuario);
                    $this->email->restablecer_contrasena($dbUsuario);
                    $this->message->addExito(lang("acceso_recuperar_contrasena"));

                    $aResponse["success"] = true;
                    $aResponse["failure"] = false;
                }
                else
                {
                    $this->message->addError(lang("acceso_recuperar_correo_no_encontrado"));
                }
                $this->seguridad->commitTransaction();
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
     * Metodo que se encarga de validar la forma de restablecer contrasena
     * 
     * @param string $cCorreo Correo electronico del usuario que solicita la peticion para restablcer su contraseña
     * @return boolean $bValidacion 
     */
    private function _validarFormaRestablecerContrasena()
    {
        $this->load->library("form_validation");
        $this->form_validation->set_rules("cCorreo", lang("acceso_email"), "trim|required|valid_email");

        $bValidacion = $this->form_validation->run();
        if (!$bValidacion)
        {
            $aErrores = $this->form_validation->getErrores();
            $this->message->addErrors($aErrores);
        }
        return $bValidacion;
    }

    /**
     * Metodo que se encarga de cargar la forma de restablecer contrasena
     * 
     * @access public
     * @param sitring $cRecuperar Clave que se le genera al usuario al momento de enviar la solicitud para restablecer su contraseña
     * @return void No retorna ningun valor
     */
    public function formaRestablecer()
    {
        try {
            $cRecuperar = $this->uri->segment(3);
            $iExiste = $this->Usuario_model->findCount(array('cRecuperar' => $cRecuperar, "bHabilitado" => SI, "bBorradoLogico" => NO));
            if ($iExiste == 0 || empty($cRecuperar))
            {
                $this->message->addError(lang("acceso_restablecer_codigo_no_valido"));
                $this->session->set_flashdata('_MESSAGES', json_encode($this->message->toJsonObject()));
                redirect("acceso/forma");
            }
            $this->layout->setLayout(LAYOUT_DEFAULT . LAYOUT_LOGIN)
                    ->view($this->cModulo . "/forma_restablecer_view", array("cTitulo" => lang("acceso_restablecer_titulo")));
        } catch (Exception $exc) {
            $this->message->clearMessages();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $exc);
        }
    }

    /**
     * Metodo que se encarga de actualziar la contraseña en la base de datos
     * 
     * @access public
     * @param string $cRecuperar Llave que se le genera al usuario al momento de mandar la solicitud para restablecer su contraseña
     * @param string $cContrasena Nueva contraseña que tendra el usuario
     * @param string $cConfirmarContrasena Confirmacion de la nueva contraseña
     * @return array json $aResponse Se retorna un array con los siguientes valores
     *      -success true para identificar si el proceso se raliza con exito
     *      -failure false si durante el proceso se produce un fallo
     *      -data array con valores que se le pasa a la vista
     *          -messages mensajes de error o exito 
     */
    public function actulizarContrasena()
    {
        $aResponse = array("success" => false, "failure" => true, "data" => array());
        try {
            $this->seguridad->startTransaction();

            $oUsuario = new Usuario_ViewModel();
            $oUsuario = $this->seguridad->getPost($oUsuario);

            if ($this->_validarFormaActualizarContrasena())
            {
                $dbUsuario = new Usuario_ViewModel();
                $dbUsuario->cRecuperar = $oUsuario->cRecuperar;
                $dbUsuario->bBloqueado = NO;
                $dbUsuario->bBorradoLogico = NO;
                $dbUsuario->bHabilitado = SI;

                $dbUsuario = $this->Usuario_model->get($dbUsuario);
                if (is_object($dbUsuario))
                {
                    $dbUsuario->cRecuperar = NULL;
                    $dbUsuario->iIntentosAcceso = 0;
                    $dbUsuario->bBloqueado = 0;
                    $dbUsuario->dtFechaAcceso = NULL;
                    $dbUsuario->cContrasena = $this->seguridad->encriptar($oUsuario->cContrasena);
                    $this->Usuario_model->save($dbUsuario, $dbUsuario->idUsuario);

                    $this->email->actualizar_contrasena($dbUsuario->cNombreCompleto, $dbUsuario->cCorreo, $oUsuario->cContrasena);
                    $this->seguridad->setBitacora($this->cModulo, $this->cAccion, lang("bitacora_evento_restablecer_contrasena"), array($dbUsuario->cNombreCompleto . " ( " . $dbUsuario->cCorreo . " ) "));
                    $this->message->addExito(lang("acceso_restabelcer_sucess_actualizar_contrasena"));
                    $this->message->addExito(lang("email_enviado_actualizar_contrasena"));
                    $this->session->set_flashdata('_MESSAGES', json_encode($this->message->toJsonObject()));
                                       
                    $cRedirect = (UsuarioHelper::get()) ? MENU_LINK_DEFAULT : "acceso/forma";

                    $aResponse["success"] = true;
                    $aResponse["failure"] = false;
                    $aResponse["data"]["redirect"] = $cRedirect;
                }
                else
                {
                    $this->message->addError("acceso_restablecer_usuario_no_valido");
                }
            }
            $this->seguridad->commitTransaction();
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
     * Metodo que se encarga de validar la forma de recuperar contraseña
     * 
     * @param string $cRecuperar Llave que sele genera al usuario al momento de enviar la solicitud para restablecer su contraseña
     * @param string $cContrasena Contraseña del usuario que ingresa al sistema
     * @param string $cConfirmarContrasena Confirmacion de la nueva contraseña
     * @return boolean $bValidacion 
     */
    private function _validarFormaActualizarContrasena()
    {
        $this->load->library("form_validation");

        $this->form_validation->set_rules("cRecuperar", lang("acceso_restablecer_codigo"), "trim|required");
        $this->form_validation->set_rules("cContrasena", lang("acceso_contrasena"), "trim|required|max_length[" . ConfigHelper::get('iMaxContrasena') . "]|min_length[" . ConfigHelper::get('iMinContrasena') . "]|matches[cConfirmarContrasena]");
        $this->form_validation->set_rules("cConfirmarContrasena", lang("acceso_restablecer_confiramar_contrasena"), "trim|required");

        $bValidacion = $this->form_validation->run();
        if (!$bValidacion)
        {
            $aErrores = $this->form_validation->getErrores();
            $this->message->addErrors($aErrores);
        }
        return $bValidacion;
    }

    // </editor-fold>
}
