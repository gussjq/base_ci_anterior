<?php

class FiltroSeguridad {

    private $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function verificar()
    {
        $oUsuario = UsuarioHelper::get();
        $cModulo = ($this->CI->uri->segment(1)) ? $this->CI->uri->segment(1) : CONTROLLER_DEFAULT;
        $cAccion = ($this->CI->uri->segment(2)) ? $this->CI->uri->segment(2) : ACCION_DEFAULT;

        $aResponse = array();
        if ($this->_buscarModulo($cModulo))
        {
            if ($this->CI->input->server('HTTP_X_REQUESTED_WITH') == "XMLHttpRequest")
            {
                if ((is_object($oUsuario)) == FALSE)
                {
                    $this->CI->message->addError(lang("general_error_sesion_no_iniciada"));
                    $this->CI->session->set_flashdata('_MESSAGES', json_encode($this->CI->message->toJsonObject()));
                    die(json_encode(array('success' => false, 'failure' => false, 'noLogin' => true, 'data' => array())));
                }
                else
                {
                    $oAccion = $this->CI->seguridad->getPermiso($cModulo, $cAccion);
                    if (is_object($oAccion))
                    {
                        if (($oAccion->idTipoAccion != TIPO_ACCION_PRIVADA_AJAX) && ($oAccion->idTipoAccion != TIPO_ACCION_PUBLICA_AJAX))
                        {
                            $this->CI->session->unset_userdata('_REDIRECT');
                            $this->CI->message->addError(lang("general_error_no_permisos"));
                            die(json_encode(array('success' => false, 'failure' => true, 'noLogin' => false, 'data' => array("messages" => $this->CI->message->toJsonObject()))));
                        }
                    }
                    else
                    {
                        $this->CI->session->unset_userdata('_REDIRECT');
                        $this->CI->message->addError(lang("general_error_no_permisos"));
                        die(json_encode(array('success' => false, 'failure' => true, 'noLogin' => false, 'data' => array("messages" => $this->CI->message->toJsonObject()))));
                    }
                }
            }
            else
            {
                if ((is_object($oUsuario)) == FALSE)
                {
                    $this->CI->session->unset_userdata('_REDIRECT');
                    $this->CI->session->set_userdata('_REDIRECT', current_url());
                    $this->CI->message->addError(lang("general_error_sesion_no_iniciada"));
                    $this->CI->session->set_flashdata('_MESSAGES', json_encode($this->CI->message->toJsonObject()));
                    redirect('acceso/forma');
                }
                else
                {
                    if((!empty($oUsuario->bNuevo)) && ($cModulo != "perfil")){
                        redirect("perfil/forma");
                    }
                    
                    $oAccion = $this->CI->seguridad->getPermiso($cModulo, $cAccion);
                    if (is_object($oAccion))
                    {
                        if (($oAccion->idTipoAccion == TIPO_ACCION_PRIVADA_AJAX) || ($oAccion->idTipoAccion == TIPO_ACCION_PUBLICA_AJAX))
                        {
                            $this->CI->session->unset_userdata('_REDIRECT');
                            show_error(lang("general_error_no_permisos"));
                        }
                    }
                    else
                    {
                        $this->CI->session->unset_userdata('_REDIRECT');
                        show_error(lang("general_error_no_permisos"));
                    }
                }
            }
        }
        
        // se quitan todos los filtros que no sean del modulo actual
        FindSessionHelper::remove($cModulo);
    }

    private function _buscarModulo($cModulo)
    {
        $bAcceso = TRUE;
        $aIgnorarControllers = array("acceso", "jobs");

        if (ENVIRONMENT == "development")
        {
            $aIgnorarControllers[] = "test";
        }

        foreach ($aIgnorarControllers as $modulo)
        {
            if ($modulo == $cModulo)
            {
                $bAcceso = FALSE;
            }
        }
        return $bAcceso;
    }

}
