<?php

class Dashboard extends MY_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model("Sistema/Menu_model");
        $this->load->model("Sistema/Usuario_model");
        $this->load->model("nomina/Periodo_model");
        $this->load->model("recursoshumanos/Empleado_model");
        $this->load->library("ViewModels/Empleado_ViewModel");
        $this->load->library("ViewModels/Periodo_ViewModel");
        $this->load->library("Empleado", array(), "Empleado");
    }

    /**
     * Pantalla principal del catalogo de dashboard
     * @access public
     */
    public function index() {
        try {
            
            $aParams["aAvisos"] = UsuarioHelper::getAvisos();            
            $aParams["cTitulo"] = lang("dashboard_titulo");
            
            $this->layout->view("dashboard/index_view", $aParams);
            
        } catch (Exception $exc) {
            
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $exc);
        }
    }
    
    /**
     * Listado que muestra los peridos nuevos en el listado 
     * @access public
     */
    public function listadoPeriodosNuevosAjax()
    {
        try {
            $oPeriodo = new Periodo_ViewModel();
            $oPeriodo = $this->seguridad->getPost($oPeriodo);
            $oPeriodo->cBuscar = $this->input->post('sSearch');
            $oPeriodo->iOrdenadoPor = $this->input->post('iSortCol_0');
            $oPeriodo->iODireccion = strtoupper($this->input->post('sSortDir_0'));
            
            $oPeriodo->idEstatusPeriodo = PERIODO_ESTATUS_NUEVA;

            $aData = $this->Periodo_model->getPeriodosDahsboard($oPeriodo, true);
            $aResponse = parent::paginacion($aData);

            echo json_encode($aResponse);
        } catch (Exception $exc) {
            $this->message->clearMessages();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $exc, FALSE);
        }
    }
    
    /**
     * Metodo que muestra los periodos en proceso en el listado
     * @access public
     */
    public function listadoPeriodosProcesoAjax()
    {
        try {
            
            $oPeriodo = new Periodo_ViewModel();
            $oPeriodo = $this->seguridad->getPost($oPeriodo);
            $oPeriodo->cBuscar = $this->input->post('sSearch');
            $oPeriodo->iOrdenadoPor = $this->input->post('iSortCol_0');
            $oPeriodo->iODireccion = strtoupper($this->input->post('sSortDir_0'));
            
            $oPeriodo->idEstatusPeriodo = PERIODO_ESTATUS_NOMINA_TOTAL;

            $aData = $this->Periodo_model->getPeriodosDahsboard($oPeriodo, true);
            $aResponse = parent::paginacion($aData);

            echo json_encode($aResponse);
        } catch (Exception $exc) {
            $this->message->clearMessages();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $exc, FALSE);
        }
    }
    
    /**
     * Metodo que muestra los 
     */
    public function listadoPeriodosTerminadosAjax()
    {
        try {
            
            $oPeriodo = new Periodo_ViewModel();
           
            $oPeriodo = $this->seguridad->getPost($oPeriodo);
            $oPeriodo->cBuscar = $this->input->post('sSearch');
            $oPeriodo->iOrdenadoPor = $this->input->post('iSortCol_0');
            $oPeriodo->iODireccion = strtoupper($this->input->post('sSortDir_0'));
            
            $oPeriodo->bFiltroTerminados = true;
            $oPeriodo->aTiposPeriodosEditables = ConfigHelper::get('aTiposPeriodosEditables');

            $aData = $this->Periodo_model->getPeriodosDahsboard($oPeriodo, true);
            $aResponse = parent::paginacion($aData);

            echo json_encode($aResponse);
            
        } catch (Exception $exc) {
            $this->message->clearMessages();
            $this->seguridad->setExeption($this->cModulo, $this->cAccion, $exc, FALSE);
        }
    }
    
    
    /**
     * Metodo que se encarga de recuperar los empleados
     */
    public function buscarEmpleados($offset = 0) 
    {
        $aResponse = array("success" => false, "failure" => true, "nologin" => false, "data" => array());
        
        try {
            if($this->_validarForma())
            {
                $this->load->library("pagination");
                
                $config = array();
                $config["base_url"] = base_url() . "dashboard/buscarEmpleados/";
                $config["total_rows"] = $this->Empleado_model->getEmpleadosMultiEmpresa(array("count" => true, "cBuscarEmpleado" => $this->input->post("cBuscarEmpleado", true)));
                $config["per_page"] = PAGINACION_PEAR_PAGE;
                $config["num_links"] = PAGINACION_NUM_LINKS;
                $config["cur_tag_open"] = "<li class=\"active\"><a href=\"#\">";
                $config["cur_tag_close"] = "</a></li>";
                $config["full_tag_open"] = "<ul id=\"paginacion\" class=\"pagination pagination-mini\">";
                $config["full_tag_close"] = "</ul>";
                $config["num_tag_open"] = "<li>";
                $config["num_tag_close"] = "</li>";
                $config['next_link'] = '>';
                $config['next_tag_open'] = '<li>';
                $config['next_tag_close'] = '</li>';
                $config['prev_link'] = '<';
                $config['prev_tag_open'] = '<li>';
                $config['prev_tag_close'] = '</li>';
                $config['first_link'] = '<<';
                $config['first_tag_open'] = '<li>';
                $config['first_tag_close'] = '</li>';
                $config['last_link'] = '>>';
                $config['last_tag_open'] = '<li>';
                $config['last_tag_close'] = '</li>';
                
                $this->pagination->initialize($config);
                
                $aEmpleado = $this->Empleado_model->getEmpleadosMultiEmpresa(array(
                    "limit" => PAGINACION_PEAR_PAGE,
                    "offset" => $offset,
                    "cBuscarEmpleado" => $this->input->post("cBuscarEmpleado", true)
                ));
                
                $aResponse["success"] = true;
                $aResponse["failure"] = false;
                $aResponse["data"]["aEmpleados"] = $aEmpleado;
                $aResponse["data"]["cLink"] = $this->pagination->create_links();
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
    
    public function detalleEmpleado()
    {
        $aResponse = array("success" => false, "failure" => true, "nologin" => false, "data" => array());
        
        try {
            $oEmpleado = new Empleado_ViewModel();
            $oEmpleado->idEmpleado = $this->input->post("idEmpleado");
            $oEmpleado = $this->Empleado->get($oEmpleado, false);
            
            if(is_object($oEmpleado))
            {
                $aResponse["success"] = true;
                $aResponse["failure"] = false;
                $aResponse["data"]["oEmpleado"] = $oEmpleado;
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

        $aResponse["data"]["messages"] = $this->message->toJsonObject();
        echo json_encode($aResponse);
    }
    
    
    /**
     * Metodo que se encarga de validar la forma cuendo se realiza una busqueda del empleado
     * retorna un valor boolean
     * 
     * @access private
     * @return boolean $bValidation retorna true si pasa las reglas de validacion
     */
    private function _validarForma()
    {
        $this->load->library("form_validation");

        $this->form_validation->set_rules("cBuscarEmpleado", lang("dashboard_buscar_empleado"), "trim|required|max_length[45]");

        $bValidacion = $this->form_validation->run();
        if (!$bValidacion)
        {
            $aErrores = $this->form_validation->getErrores();
            $this->message->addErrors($aErrores);
        }
        return $bValidacion;
    }
}
