<?php

/*
 * https://www.youtube.com/watch?v=YwmbutMLoms
 * https://www.youtube.com/watch?v=4M8FDqVie2s
 * https://www.youtube.com/watch?v=7cwk0qU6Ew8
 * https://www.youtube.com/watch?v=m1fPvn0XroU
 * https://www.youtube.com/watch?v=nN1nko0eLGk
 */

class Modulos extends MY_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model("Sistema/Modulo_model");
        $this->load->library("ViewModels/Modulo_ViewModel");
    }

    // <editor-fold defaultstate="collapsed" desc="Metodos Catalogo">

    public function listado()
    {
        try {
            $oModulo = new Modulo_ViewModel();
            $oModulo = $this->seguridad->getPost($oModulo);
            
            FindSessionHelper::add("FindModulos", $oModulo, $this->aAmbito);

            $aParams = array();
            $aParams["oModulo"] = $oModulo;
            $aParams["cTitulo"] = lang("modulos_titulo");
            $aParams["aMigajaPan"] = array(lang("general_accion_listado"));
            $aParams['aMenuCatalogo'] = $this->seguridad->getMenu(MENU_CONFIGURACION);

            $this->layout->view($this->cModulo . '/listado_view', $aParams);
        } catch (Exception $ex) {
            $this->message->clearMessages();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $ex);
        }
    }

    public function listadoAjax()
    {
        try {
            $oModulo = FindSessionHelper::get("FindModulos");
            if (!$oModulo)
            {
                $oModulo = new Modulo_ViewModel();
            }
            
            $oModulo = $this->seguridad->getPost($oModulo);
            $oModulo->cBuscar = $this->input->post('sSearch');
            $oModulo->iOrdenadoPor = $this->input->post('iSortCol_0');
            $oModulo->iODireccion = strtoupper($this->input->post('sSortDir_0'));

            FindSessionHelper::add("FindModulos", $oModulo, $this->aAmbito);
            
            $aData = $this->Modulo_model->getAll($oModulo, true);
            $aResponse = parent::paginacion($aData);
            echo json_encode($aResponse);
        } catch (Exception $exc) {
            $this->message->clearMessages();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $exc, FALSE);
        }
    }
    
    public function getParams()
    {
        $oModulo= FindSessionHelper::get("FindModulos");
        if ($oModulo == NULL)
        {
            $oModulo = new Modulo_ViewModel();
        }
        if (!$oModulo->iODireccion)
        {
            $oModulo->iODireccion = 'ASC';
        }

        $result = parent::getParams();
        $result['filters'] = $oModulo;

        if ($oModulo->iOrdenadoPor !== false)
        {
            $result['sort'] = $oModulo->iOrdenadoPor;
        }
        else
        {
            $result['sort'] = '';
        }
        $result['sortAlign'] = $oModulo->iODireccion;
        $result['find'] = $oModulo->cBuscar;
        
        $result['tituloReporte'] = lang("modulos_titulo");
        return $result;
    }

    public function forma()
    {
        try {
            $cRutaArchivo = getRutaImagenDefault();
            $idModulo = $this->uri->segment(3);
            $oModulo = new Modulo_ViewModel();

            if ($idModulo)
            {
                $oModulo->idModulo = $idModulo;
                $dbModulo = $this->Modulo_model->get($oModulo);
                if (is_object($dbModulo))
                {
                    $oModulo = $this->seguridad->dbToView($oModulo, $dbModulo);
                    $cRutaArchivo = DIRECTORIO_MODULOS . $oModulo->cIcono;
                    $cRutaArchivo = getRutaImagen($cRutaArchivo);
                }
                else
                {
                    $this->message->addError(lang("general_error_registro_no_encontrado"));
                    $this->session->set_flashdata('_MESSAGES', json_encode($this->message->toJsonObject()));
                    redirect($this->cModulo . "/listado");
                }
            }

            $aParams['oModulo'] = $oModulo;
            $aParams['cRutaArchivo'] = $cRutaArchivo;
            $aParams["cTitulo"] = lang("modulos_titulo");
            $aParams["aMigajaPan"] = array(lang("general_accion_forma"));
            $aParams['aMenuCatalogo'] = $this->seguridad->getMenu(MENU_CONFIGURACION);

            $this->layout->view($this->cModulo . '/forma_view', $aParams);
        } catch (Exception $ex) {
            $this->message->clearMessages();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $ex);
        }
    }

    public function insertar()
    {
        $aResponse = array("success" => false, "failure" => true, "nologin" => false, "data" => array());
        try {
            $oModulo = new Modulo_ViewModel();
            $oModulo = $this->seguridad->getPost($oModulo);

            if ($this->_validarForma())
            {
                $this->seguridad->startTransaction();
                $this->Modulo_model->insertar($oModulo);
//                $this->_moverArchivo(null, $oModulo->cIcono);
                $this->message->addExito(lang("general_evento_insertar"));
                $this->session->set_flashdata('_MESSAGES', json_encode($this->message->toJsonObject()));
                $this->seguridad->setBitacora($this->cModulo, $this->cAccion, lang("bitacora_evento_insertar"), array(UsuarioHelper::get('cNombreCompleto'), $this->cModulo, $oModulo->cNombre));

                $aResponse["success"] = true;
                $aResponse["failure"] = false;
                $this->seguridad->commitTransaction();
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

    public function actualizar()
    {
        $aResponse = array("success" => false, "failure" => true, "nologin" => false, "data" => array());
        try {
            $oModulo = new Modulo_ViewModel();
            $oModulo = $this->seguridad->getPost($oModulo);

            if ($this->_validarForma())
            {
                $this->seguridad->startTransaction();
                $dbModulo = $this->Modulo_model->find(array("idModulo" => $oModulo->idModulo));
                if (is_object($dbModulo))
                {
                    $this->Modulo_model->actualizar($oModulo);
//                    $this->_moverArchivo($dbModulo->cIcono, $oModulo->cIcono);
                    $this->message->addExito(lang("general_evento_actualizar"));
                    $this->session->set_flashdata('_MESSAGES', json_encode($this->message->toJsonObject()));
                    $this->seguridad->setBitacora($this->cModulo, $this->cAccion, lang("bitacora_evento_actualizar"), array(UsuarioHelper::get('cNombreCompleto'), $this->cModulo, $oModulo->cNombre));

                    $aResponse["success"] = true;
                    $aResponse["failure"] = false;
                }
                else
                {
                    $this->message->addError(lang("general_error_registro_no_encontrado"));
                }
                $this->seguridad->commitTransaction();
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

    public function habilitar()
    {
        $aResponse = array("success" => false, "failure" => true, "nologin" => false, "data" => array());
        try {
            $this->seguridad->startTransaction();
            $oModulo = new Modulo_ViewModel();
            $oModulo->idModulo = $this->input->post("id", true);

            $oModulo = $this->Modulo_model->get($oModulo);
            if (is_object($oModulo))
            {
                $this->Modulo_model->habilitar($oModulo);
                $this->message->addExito(lang("general_evento_habilitar"));
                $this->seguridad->setBitacora($this->cModulo, $this->cAccion, lang("bitacora_evento_habilitar"), array(UsuarioHelper::get('cNombreCompleto'), $this->cModulo, $oModulo->cNombre));

                $aResponse["success"] = true;
                $aResponse["failure"] = false;
            }
            else
            {
                $this->message->addError(lang("general_error_registro_no_encontrado"));
            }
            $this->seguridad->commitTransaction();
        } catch (Exception $ex) {
            $this->message->clearMessages();
            $this->message->addError(lang("general_error"));
            $this->seguridad->rollbackTransaction();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $ex, FALSE);
        }

        $aResponse["data"]['messages'] = $this->message->toJsonObject();
        echo json_encode($aResponse);
    }

    public function deshabilitar()
    {
        $aResponse = array("success" => false, "failure" => true, "noLogin" => false, "data" => array());

        try {
            $this->seguridad->startTransaction();
            $oModulo = new Modulo_ViewModel();
            $oModulo->idModulo = (int) $this->input->post("id", true);

            $oModulo = $this->Modulo_model->get($oModulo);
            if (is_object($oModulo))
            {
                $this->Modulo_model->deshabilitar($oModulo);
                $this->message->addExito(lang("general_evento_deshabilitar"));
                $this->seguridad->setBitacora($this->cModulo, $this->cAccion, lang("bitacora_evento_deshabilitar"), array(UsuarioHelper::get('cNombreCompleto'), $this->cModulo, $oModulo->cNombre));

                $aResponse["success"] = true;
                $aResponse["failure"] = false;
            }
            else
            {
                $this->message->addError(lang("general_error_registro_no_encontrado"));
            }
            $this->seguridad->commitTransaction();
        } catch (Exception $ex) {
            $this->message->clearMessages();
            $this->message->addError(lang("general_error"));
            $this->seguridad->rollbackTransaction();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $ex, FALSE);
        }

        $aResponse["data"]['messages'] = $this->message->toJsonObject();
        echo json_encode($aResponse);
    }

    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="Metodos Ajax">
    public function cargarArchivoAjax()
    {
        $aResponse = array("success" => false, "noLogin" => false, "data" => array());
        try {

            $cDirTemporal = getDocumentRoot() . DIRECTORIO_TEMPORAL;
            $aConfig['upload_path'] = DIRECTORIO_TEMPORAL;
            $aConfig['allowed_types'] = 'png|jpg|gif';
            $aConfig['encrypt_name'] = true;

            $this->load->library('upload', $aConfig);
            if ($this->upload->do_upload("cIconoUpload"))
            {
                $aResponse["success"] = true;
                $aResponse["data"]["aFileData"] = $this->upload->data();
            }
            else
            {
                $this->message->addError($this->upload->display_errors());
            }
        } catch (Exception $ex) {
            //guardar en bitacora error 
            $this->message->addError(lang("general_error_subir_archivo"));
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $ex, false);
        }

        $aResponse["data"]["messages"] = $this->message->toJsonObject();
        echo json_encode($aResponse);
    }

    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="Metodos Privados">
    private function _moverArchivo($cArchivoAnterior, $cArchivoNuevo)
    {
        $cArchivoTemporal = getDocumentRoot() . DIRECTORIO_TEMPORAL . $cArchivoNuevo;
        if (@file_exists($cArchivoTemporal))
        {

            $cIconoAnterior = getDocumentRoot() . DIRECTORIO_MODULOS . $cArchivoAnterior;
            $cIconoNuevo = getDocumentRoot() . DIRECTORIO_MODULOS . $cArchivoNuevo;

            if (copy($cArchivoTemporal, $cIconoNuevo))
            {
                if (!empty($cArchivoAnterior) && file_exists($cIconoAnterior))
                {
                    unlink($cIconoAnterior);
                }
                if (!empty($cArchivoNuevo) && file_exists($cArchivoTemporal))
                {
                    unlink($cArchivoTemporal);
                }
            }
        }
    }

    private function _validarForma()
    {
        $this->load->library("form_validation");

        $this->form_validation->set_rules("cNombre", lang("modulos_nombre"), "trim|required|max_length[45]|alpha_underscore|callback_verificarNombre");
        $this->form_validation->set_rules("cAlias", lang("modulos_alias"), "trim|required|max_length[45]|callback_verificarAlias");
        $this->form_validation->set_rules("cDescripcion", lang("modulos_descripcion_modulo"), "trim|required|max_length[255]");
        $this->form_validation->set_rules("cEtiquetaTitulo", lang("modulos_etiqueta_titulo"), "trim|required|max_length[60]|alpha_underscore|callback_verificarEtiquetaTitulo");
        $this->form_validation->set_rules("cEtiquetaDescripcion", lang("modulos_etiqueta_descripcion"), "trim|required|max_length[60]|alpha_underscore|callback_verificarEtiquetaDescripcion");
        //$this->form_validation->set_rules("cIcono", lang("modulos_icono"), "trim|required|max_length[60]");

        $this->form_validation->set_message("verificarNombre", lang("modulos_verificar_nombre"));
        $this->form_validation->set_message("verificarAlias", lang("modulos_verificar_alias"));
        $this->form_validation->set_message("verificarEtiquetaTitulo", lang("modulos_verificar_etiquetas_titulo"));
        $this->form_validation->set_message("verificarEtiquetaDescripcion", lang("modulos_verificar_etiquetas_descripcion"));

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

        if ($this->input->post("idModulo"))
        {
            $aParams["idModulo <>"] = $this->input->post("idModulo");
        }

        $iExiste = $this->Modulo_model->findCount($aParams);
        return ($iExiste > 0) ? FALSE : TRUE;
    }

    public function verificarAlias()
    {
        $aParams = array();
        $aParams["cAlias"] = $this->input->post("cAlias");

        if ($this->input->post("idModulo"))
        {
            $aParams["idModulo <>"] = $this->input->post("idModulo");
        }

        $iExiste = $this->Modulo_model->findCount($aParams);
        return ($iExiste > 0) ? FALSE : TRUE;
    }

    public function verificarEtiquetaTitulo()
    {
        $aParams = array();
        $aParams["cEtiquetaTitulo"] = $this->input->post("cEtiquetaTitulo");

        if ($this->input->post("idModulo"))
        {
            $aParams["idModulo <>"] = $this->input->post("idModulo");
        }

        $iExiste = $this->Modulo_model->findCount($aParams);
        return ($iExiste > 0) ? FALSE : TRUE;
    }

    public function verificarEtiquetaDescripcion()
    {

        $aParams = array();
        $aParams["cEtiquetaDescripcion"] = $this->input->post("cEtiquetaDescripcion");

        if ($this->input->post("idModulo"))
        {
            $aParams["idModulo <>"] = $this->input->post("idModulo");
        }

        $iExiste = $this->Modulo_model->findCount($aParams);
        return ($iExiste > 0) ? FALSE : TRUE;
    }

    // </editor-fold>
}
