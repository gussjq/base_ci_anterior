<?php

/*
 * Items
 * 
 * Catalogo de items del menu, sirve para crear los items del menu de manera dinamica
 * 
 * @package controller
 * @author DEVELOPER 1 <correo@developer1> cel : <1111111111>
 * @creado 06-09-2014
 */

class Items extends MY_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model("Sistema/Menu_model");
        $this->load->model("Sistema/Items_model");

        $this->load->library("ViewModels/Menu_ViewModel");
        $this->load->library("ViewModels/Items_ViewModel");
    }

    // <editor-fold defaultstate="collapsed" desc="Metodos Catalogo">

    /**
     * Metodo que visualiza la forma al momento de agregar o editar un nuevo registro
     * 
     * @access public
     * @param numeric $idAccion Identificador del Items a editar
     * @return void 
     */
    public function forma()
    {
        try {

            $idMenu = $this->uri->segment(3);
            $oMenu = $this->_menuListado($idMenu);

            $aParams = array();
            $aParams["oMenu"] = $oMenu;
            $aParams["cTitulo"] = lang("Items_titulo");
            $aParams["aCombosForma"] = $this->_getCombosForma();
            $aParams["aItems"] = $this->Items_model->getPadresHijos($oMenu->idMenu);

            $aParams["cRutaDefault"] = getRutaImagenDefault();
            $aParams["cLinkDefault"] = MENU_LINK_DEFAULT;
            
            $aParams['aMigajaPan'] = array(lang("general_accion_forma"));
            $aParams['aMenuCatalogo'] = $this->seguridad->getMenu(MENU_CONFIGURACION);

            $this->layout->view($this->cModulo . '/forma_view', $aParams);
        } catch (Exception $exc) {
            $this->message->clearMessages();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $exc);
        }
    }

    /**
     * Metodo encargado de insertar un nuevo Items en la base de datos
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
            $oItems = new Items_ViewModel();
            $oItems = $this->seguridad->getPost($oItems);

            if ($this->_validarForma())
            {
                $this->seguridad->startTransaction();
                $idItem = $this->Items_model->insertar($oItems);

                $this->_moverArchivo(null, $oItems->cIcono);

                $this->message->addExito(lang("general_evento_insertar"));
                $this->seguridad->setBitacora($this->cModulo, $this->cAccion, lang("bitacora_evento_insertar"), array(UsuarioHelper::get('cNombreCompleto'), $this->cModulo, lang($oItems->cEtiquetaTitulo)));

                $aResponse["success"] = TRUE;
                $aResponse["failure"] = FALSE;
                $aResponse["data"]["item"] = $this->_getItem($idItem);
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
     * Metodo encargado de actualizar un Items en la base de datos
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
            $oItems = new Items_ViewModel();
            $oItems = $this->seguridad->getPost($oItems);

            if ($this->_validarForma())
            {
                $this->seguridad->startTransaction();
                $dbItems = $this->Items_model->find(array("idItems" => $oItems->idItems));

                if (is_object($dbItems))
                {
                    $this->Items_model->actualizar($oItems);
                    $this->_moverArchivo($dbItems->cIcono, $oItems->cIcono);

                    $this->message->addExito(lang("general_evento_actualizar"));
                    $this->seguridad->setBitacora($this->cModulo, $this->cAccion, lang("bitacora_evento_actualizar"), array(UsuarioHelper::get('cNombreCompleto'), $this->cModulo, lang($oItems->cEtiquetaTitulo)));

                    $aResponse["success"] = true;
                    $aResponse["failure"] = false;
                    $aResponse["data"]["item"] = $this->_getItem($oItems->idItems);
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
     * Metodo encargado de elimiar un Items en la base de datos
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
            $oItems = new Items_ViewModel();

            $oItems->idItems = $this->input->post("idItems", true);
            $oItems = $this->Items_model->get($oItems);

            if (is_object($oItems))
            {
                $this->Items_model->eliminar($oItems);

                $aItem = $this->_getItem($oItems->idItems);
                $this->message->addExito(lang("general_evento_eliminar"));
                $this->seguridad->setBitacora($this->cModulo, $this->cAccion, lang("bitacora_evento_eliminar"), array(UsuarioHelper::get('cNombreCompleto'), $this->cModulo, $aItem["cTitulo"]));

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
    // <editor-fold defaultstate="collapsed" desc=" Metodos Ajax">

    public function getAccionesAjax()
    {
        $aResponse = array("success" => FALSE, "noLogin" => FALSE, "data" => array());
        try {
            $idModulo = (int) $this->input->post("idModulo");
            if ($idModulo > 0)
            {
                $cFiltro = "idModulo = {$idModulo} AND (idTipoAccion = " . TIPO_ACCION_PRIVADA . " OR idTipoAccion = " . TIPO_ACCION_PUBLICA . ")";
                $aAcciones = $this->Accion_model->findAll($cFiltro, "*", "cNombre ASC");

                $aResponse["success"] = TRUE;
                $aResponse["data"]["options"] = getComboMultiSelect('idAccion', 'cAlias', $aAcciones);
            }
        } catch (Exception $ex) {
            $this->message->clearMessages();
            $this->message->addError(lang("general_error"));
            $aResponse["data"]["message"] = $this->message->toJsonObject();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $ex, false);
        }

        echo json_encode($aResponse);
    }

    public function ordenarMenuAjax()
    {
        $aResponse = array("success" => false, "failure" => true, "noLogin" => false, "data" => array());

        try {
            $this->seguridad->startTransaction();
            $idMenu = $this->input->post("idMenu");
            $aItems = $this->input->post("aItems");

            $this->Items_model->ordenarMenu($idMenu, $aItems);

            $aResponse["success"] = true;
            $aResponse["failure"] = false;
            $this->seguridad->commitTransaction();
        } catch (Exception $exc) {
            $this->message->clearMessages();
            $this->message->addError(lang("general_error"));
            $this->seguridad->rollbackTransaction();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $exc, false);
        }
        $aResponse["data"]["message"] = $this->message->toJsonObject();
        echo json_encode($aResponse);
    }

    public function getItemAjax()
    {
        $aResponse = array("success" => false, "failure" => true, "noLogin" => false, "data" => array());
        try {
            $oItem = new Items_ViewModel();
            $oItem->idItems = $this->input->post("idItems");
            $oItem = $this->Items_model->get($oItem);

            if (!is_object($oItem))
            {
                $this->message->addError(lang("general_error_registro_no_encontrado"));
            }

            $aResponse["success"] = true;
            $aResponse["failure"] = false;
            $aResponse["data"]["item"] = $oItem;
        } catch (Exception $exc) {
            $this->message->clearMessages();
            $this->message->addError(lang("general_error"));
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $exc, false);
        }

        $aResponse["data"]["message"] = $this->message->toJsonObject();
        echo json_encode($aResponse);
    }

    public function getLinkAjax()
    {
        $aResponse = array("success" => false, "failure" => true, "noLogin" => false, "data" => array());
        try {
            $cLink = MENU_LINK_DEFAULT;
            $idModulo = $this->input->post("idModulo");
            $idAccion = $this->input->post("idAccion");

            if ($idModulo && $idAccion)
            {
                $oModulo = $this->Modulo_model->find(array("idModulo" => $idModulo, "bHabilitado" => SI));
                $oAccion = $this->Accion_model->find(array("idAccion" => $idAccion, "bHabilitado" => SI));

                if (is_object($oModulo) && is_object($oAccion))
                {
                    $cLink = $oModulo->cNombre . "/" . $oAccion->cNombre;
                }
            }

            $aResponse["success"] = true;
            $aResponse["failure"] = false;
            $aResponse["data"]["link"] = $cLink;
        } catch (Exception $exc) {
            $this->message->clearMessages();
            $this->message->addError(lang("general_error"));
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $exc, false);
        }

        $aResponse["data"]["message"] = $this->message->toJsonObject();
        echo json_encode($aResponse);
    }

    public function cargarArchivoAjax()
    {
        $aResponse = array("success" => false, "noLogin" => false, "data" => array());
        try {
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
            $this->message->clearMessages();
            $this->message->addError(lang("general_error_subir_archivo"));
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $ex, false);
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

        $this->form_validation->set_rules("idMenu", lang("Items_menu"), "trim|numeric");
        $this->form_validation->set_rules("idAccion", lang("Items_accion"), "trim|numeric");
        $this->form_validation->set_rules("cLink", lang("Items_link"), "trim|max_length[100]");
        $this->form_validation->set_rules("cEtiquetaTitulo", lang("Items_etiqueta_titulo"), "trim|max_length[60]");
        $this->form_validation->set_rules("cEtiquetaTitulo", lang("Items_etiqueta_descripcion"), "trim|max_length[60]");

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
        $aCombos = array();

        $this->load->model("Sistema/Modulo_model");
        $this->load->library("ViewModels/Modulo_ViewModel");

        $aModulos = $this->Modulo_model->findAll(array("bHabilitado" => SI), "*", "cAlias ASC");
        $aCombos["aModulos"] = getComboForma("idModulo", "cAlias", $aModulos);

        return $aCombos;
    }

    /**
     * Metodo que se encarga de validar si el identificador del menu sea valido
     * 
     * @access public
     * @param int $idMenu identidicador del modulo al que pertenecen las acciones
     * @return void
     */
    private function _menuListado($id = 0)
    {
        $dbMenu = null;
        if ($id > 0)
        {
            $oMenu = new Menu_ViewModel();
            $oMenu->idMenu = (int) $id;
            $dbMenu = $this->Menu_model->get($oMenu);

            if (!is_object($dbMenu))
            {
                $this->message->addError(lang("Items_error_menu_no_existe"));
                $this->session->set_flashdata('_MESSAGES', json_encode($this->message->toJsonObject()));
                redirect("menu/listado");
            }
        }
        else
        {
            $this->message->addError(lang("Items_error_menu_no_existe"));
            $this->session->set_flashdata('_MESSAGES', json_encode($this->message->toJsonObject()));
            redirect("menu/listado");
        }
        return $dbMenu;
    }

    private function _getItem($idItem)
    {
        $this->load->model("Sistema/Modulo_model");
        $aReturn = array("idItem" => NULL, "cTitulo" => "");
        $oItem = $this->Items_model->find(array("idItems" => $idItem));

        if (is_object($oItem))
        {
            if (empty($oItem->cEtiquetaTitulo))
            {
                $oModelo = $this->Modulo_model->find(array("idModulo" => $oItem->idModulo));
                if (is_object($oModelo))
                {
                    $aReturn["cTitulo"] = lang($oModelo->cEtiquetaTitulo);
                }
            }
            else
            {
                $aReturn["cTitulo"] = lang($oItem->cEtiquetaTitulo);
            }

            $aReturn["idItem"] = $oItem->idItems;
        }
        return $aReturn;
    }

    /**
     * Metodo que se encarga de mover el icono de 
     * 
     * @param string $cArchivoAnterior
     * @param string $cArchivoNuevo
     */
    private function _moverArchivo($cArchivoAnterior, $cArchivoNuevo)
    {
        if ($this->input->post("cIcono"))
        {
            $cArchivoTemporal = getDocumentRoot() . DIRECTORIO_TEMPORAL . $cArchivoNuevo;
            if (@file_exists($cArchivoTemporal))
            {
                $cIconoAnterior = getDocumentRoot() . DIRECTORIO_ITEMS_MENU . $cArchivoAnterior;
                $cIconoNuevo = getDocumentRoot() . DIRECTORIO_ITEMS_MENU . $cArchivoNuevo;

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
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Otros Metodos">

    public function verificarOrden()
    {
        $aParams = array();
        $aParams["iOrden"] = $this->input->post("iOrden");

        if ($this->input->post("idItems"))
        {
            $aParams["idMenu <>"] = $this->input->post("idItems");
        }

        $iExiste = $this->Items_model->findCount($aParams);
        return ($iExiste > 0) ? FALSE : TRUE;
    }

    // </editor-fold>
}
