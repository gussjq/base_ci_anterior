<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Email extends CI_Email {

    private $CI;

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->CI = &get_instance();

        $this->CI->load->model("Sistema/Email_model");
    }

    /**
     * Metodo que envia el correo electronico de al momento que el usuario restablece su contraseña,
     * 
     * @access public
     * @param object $oUsuario
     * @throws Exception Error al no existir la plantilla de email a enviar|Error al no poderse enviar el correo electrnico     
     * @return void No regresa ningun valor
     */
    public function restablecer_contrasena($oUsuario) {
        $config = ConfigHelper::getSmtp();
        $this->initialize($config);

        $oEmail = $this->_getPlantilla(EMAIL_RESTABLECER_CONTRASENA);

        if (is_object($oEmail)) {
            $cUrlAcceso = base_url() . "acceso/forma";
            $cUrlRecuperar = base_url() . "acceso/formaRestablecer/{$oUsuario->cRecuperar}";
            $cNombreCompleto = $oUsuario->cNombre . " " . $oUsuario->cApellidoPaterno . " " . $oUsuario->cApellidoMaterno;
            $cMessage = stringRemplace($oEmail->txCuerpo, array($cNombreCompleto, $cUrlRecuperar, $cUrlAcceso));

            $this->from(ConfigHelper::get('cCorreoSmtp'), lang("email_no_responder"));
            $this->to($oUsuario->cCorreo);
            $this->subject($oEmail->cTitulo);
            $this->message($cMessage);

            if (!$this->send()) {
                throw new Exception(lang("general_error_enviar_email"));
            }
        } else {
            throw new Exception(lang("general_error_enviar_email"));
        }
    }

    /**
     * Correo electronico que se le envia al usuario al momento de actualizar su contrasena
     * o bien cuando se crea un nuevo usuario
     * 
     * @param string $cNombreCompleto 
     * @param string $cCorreo
     * @param string $cContrasena
     * @throws Exception
     */
    public function actualizar_contrasena($cNombreCompleto, $cCorreo, $cContrasena) {

        $config = ConfigHelper::getSmtp();
        $this->initialize($config);

        $oEmail = $this->_getPlantilla(EMAIL_ACTUALIZAR_CONTRASENA);

        if (is_object($oEmail)) {
            $cUrlAcceso = base_url() . "acceso/forma";
            $cMessage = stringRemplace($oEmail->txCuerpo, array($cNombreCompleto, $cCorreo, $cContrasena, $cUrlAcceso));

            $this->from(ConfigHelper::get('cCorreoSmtp'), lang("email_no_responder"));
            $this->to($cCorreo);
            $this->subject($oEmail->cTitulo);
            $this->message($cMessage);

            if (!$this->send()) {
                throw new Exception(lang("general_error_enviar_email"));
            }
        } else {
            $cError = str_replace("%s", EMAIL_ACTUALIZAR_CONTRASENA, lang("general_error_email_no_encontrado"));
            throw new Exception($cError);
        }
    }
    
    public function nuevo_usuario($oUsuario, $cContrasena)
    {
        $config = ConfigHelper::getSmtp();
        $this->initialize($config);
        
        $oEmail = $this->_getPlantilla(EMAIL_NUEVO_USUARIO);
        
        if (is_object($oEmail)) {
            $cUrlAcceso = base_url() . "acceso/forma";
            $cNombreUsuario = $oUsuario->cNombre . " " . $oUsuario->cApellidoPaterno . " " .$oUsuario->cApellidoMaterno;
            $cMessage = stringRemplace($oEmail->txCuerpo, array($cNombreUsuario, $oUsuario->cCorreo, $cContrasena, $cUrlAcceso));

            $this->from(ConfigHelper::get('cCorreoSmtp'), lang("email_no_responder"));
            $this->to($oUsuario->cCorreo);
            $this->subject($oEmail->cTitulo);
            $this->message($cMessage);

            if (!$this->send()) {
                throw new Exception(lang("general_error_enviar_email"));
            }
        } else {
            $cError = str_replace("%s", EMAIL_ACTUALIZAR_CONTRASENA, lang("general_error_email_no_encontrado"));
            throw new Exception($cError);
        }
    }

    private function _getPlantilla($idTipoEmail) {

        $oEmail = $this->CI->Email_model->find(array(
            'idIdioma' => IdiomaHelper::get("idIdioma"),
            'idTipoEmail' => $idTipoEmail
        ));

        if (!is_object($oEmail)) {
            $oEmail = $this->CI->Email_model->find(array(
                'idIdioma' => ConfigHelper::get("idIdioma"),
                'idTipoEmail' => $idTipoEmail
            ));
        }
        
        return $oEmail;
    }

}
