<?php

/**
 * Configuraciion
 * 
 * Clase encargada de guardar la configuracion global del sistema
 */
class Configuracion extends MY_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model("Sistema/Configuracion_model");
        $this->load->library("ViewModels/Configuracion_ViewModel");
    }

    // <editor-fold defaultstate="collapsed" desc="Metodos del Catalogo">

    /**
     * Metodo que visualiza la forma al momento de agregar o editar un nuevo registro
     * 
     * @access public
     * @return void 
     */
    public function forma()
    {
        try {
            $aParams = array();
            $aConfiguracion = ConfigHelper::get();

            $cRutaLogo = DIRECTORIO_CONFIGURACION . ConfigHelper::getCache('cLogo', $aConfiguracion);
            $cRutaLogo = getRutaImagen($cRutaLogo);

            $aParams['aConfiguracion'] = $aConfiguracion;
            $aParams['cTitulo'] = lang("configuracion_titulo");
            $aParams['aCombosForma'] = $this->_getCombosForma();
            $aParams['cRutaLogo'] = $cRutaLogo;
            $aParams['aMenuCatalogo'] = $this->seguridad->getMenu(MENU_CONFIGURACION);
            $aParams["aMigajaPan"] = array(lang("general_accion_forma"));

            $this->layout->view($this->cModulo . '/forma_view', $aParams);
        } catch (Exception $exc) {
            $this->message->clearMessages();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $exc);
        }
    }

    public function actualizar()
    {
        $aResponse = array("success" => false, "noLogin" => false, "data" => array());
        try {

            if ($this->_validarForma())
            {
                $this->seguridad->startTransaction();
                $aConfiguracion = ConfigHelper::get();

                $aPost = $_POST;
                foreach ($aPost as $k => $value)
                {
                    if ($k == "cLogo")
                    {
                        $this->_moverArchivo(ConfigHelper::getCache('cLogo', $aConfiguracion), $value);
                    }

                    $oConfiguracion = new Configuracion_ViewModel();
                    $oConfiguracion->cClave = $k;
                    $oConfiguracion->cValor = trim($value);
                    $this->Configuracion_model->actualizar($oConfiguracion);
                }

                $this->message->addExito(lang("general_evento_actualizar"));
                $this->seguridad->setBitacora($this->cModulo, $this->cAccion, lang("bitacora_evento_configuracion"), array(UsuarioHelper::get('cCorreo')));

                $aResponse["success"] = true;
                $this->seguridad->commitTransaction();
            }
        } catch (Exception $exc) {
            $this->message->clearMessages();
            $this->message->addError($exc->getMessage());
            $this->seguridad->rollbackTransaction();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $exc, FALSE);
        }

        $aResponse["data"]["messages"] = $this->message->toJsonObject();
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
            if ($this->upload->do_upload("cLogoUpload"))
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

    private function _moverArchivo($cArchivoAnterior, $cArchivoNuevo)
    {
        $cLogoTemporal = getDocumentRoot() . DIRECTORIO_TEMPORAL . $cArchivoNuevo;
        if (@file_exists($cLogoTemporal))
        {
            $cLogoAnterior = getDocumentRoot() . DIRECTORIO_CONFIGURACION . $cArchivoAnterior;
            $cLogoNuevo = getDocumentRoot() . DIRECTORIO_CONFIGURACION . $cArchivoNuevo;

            if (copy($cLogoTemporal, $cLogoNuevo))
            {
                if (file_exists($cLogoAnterior))
                {
                    unlink($cLogoAnterior);
                }
                if (file_exists($cLogoTemporal))
                {
                    unlink($cLogoTemporal);
                }
            }
        }
    }

    private function _validarForma()
    {
        $this->load->library('form_validation');

        $aPost = $_POST;
        $aConfiguracion = ConfigHelper::get();

        foreach ($aPost as $k => $value)
        {
            foreach ($aConfiguracion as $j => $oConfiguracion)
            {
                if ($k == $oConfiguracion->cClave)
                {
                    $this->form_validation->set_rules($oConfiguracion->cClave, lang($oConfiguracion->cEtiqueta), $oConfiguracion->cRules);
                }
            }
        }

        $bValidacion = $this->form_validation->run();
        if (!$bValidacion)
        {
            $aErrores = $this->form_validation->getErrores();
            $this->message->addErrors($aErrores);
        }
        return $bValidacion;
    }

    private function _getCombosForma()
    {
        $this->load->model("Sistema/Idioma_model");
        $this->load->library("ViewModels/Idioma_ViewModel");
        $aCombos = array();

        $oIdioma = new Idioma_ViewModel();
        $oIdioma->bHabilitado = SI;
        $aIdiomas = $this->Idioma_model->getAll($oIdioma);
        $aCombos["aIdiomas"] = getComboForma('idIdioma', 'cAlias', $aIdiomas);

        $aCombos["aProtocolos"] = getComboArray(array(
            "smtp" => "Smtp", "mail" => "Mail", "sendmail" => "SendMail"
        ));

        $aCombos["aTipoCorreos"] = getComboArray(array(
            "html" => "Formato Html", "text" => "Texto Plano"
        ));
        
        $aCombos['si_no'] = getComboArray(array(
            "0" => lang('general_si'),
            "1" => lang('general_no')
        ));

        return $aCombos;
    }

    // </editor-fold>
}
