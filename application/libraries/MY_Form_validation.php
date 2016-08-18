<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Clase que sobre escribe a la clase nativa de codeigniter CI_Form_validation 
 * para agregar una funcion que devuelva los errores en un formato de array
 * 
 * @author GussJQ
 * @creado 15-01-2015
 * @package libraries
 */
class MY_Form_validation extends CI_Form_validation {

    public function __construct($rules = array())
    {
        parent::__construct();
    }
    
    // <editor-fold defaultstate="collapsed" desc="METODOS AGREGADOS PARA VALIDACIONES DE FORMAS">

    /**
     * Metodo que se encarga de recuperar un array de errores
     * 
     * @access public
     * @param array errores
     */
    public function getErrores()
    {
        return $this->_error_array;
    }

    /**
     * Metodo que se encarga de validar una curp 
     * 
     * @access public
     * @example BADD110313HCMLNS09 curp valida
     * @param string $cString
     * @return boolean true|false
     */
    public function validate_curp($cString)
    {
        $cPatron = "/[A-Z]{1}[AEIOU][A-Z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[HM]{1}(AS|BC|BS|CC|CS|CH|CL|CM|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[B-DF-HJ-NP-TV-Z]{3}[0-9A-Z]{1}[0-9]{1}$/";
        return $this->regex_match($cString, $cPatron);
    }
    
    
    /**
     * Metodo que se encarga de validar unicamente el formato del rfc de una perosona moral,
     * este formato consta de 12 caracteres en total, los primeros 3 caracteres alfabeticos correspondientes a las 
     * letras iniciales de la razon social 6 caracteres numericos correspondientes al año de alta o modificacion de la razon social y
     * y  los ultimos 3 caracteres son compuestos por 2 de la homoclave mas 1 del sello verificador
     * 
     * @access public
     * @example AAS070817SPA RFC valido
     * @link http://todoconta.com/que-es-el-rfc-y-como-se-determina/ Descripcion de como se compone un RFC tanto para personas morales como fisicas
     * @return boolean true|false
     */
    public function validate_format_rfc_moral($cString)
    {
        $cPatron = '/[A-Z]{3}[0-9]{6}[A-Z0-9]{3}$/';
        return $this->regex_match($cString, $cPatron);
    }
    
    /**
     * Metodo que se encarga de validar el rfc fisico de una persona 
     * @link http://www.javerosanonimos.com/2009/02/expresiones-regulares.html
     * @param string $cString valor del campo a validar en este caso un rfc fisico
     * @return true|false
     */
    public function validate_rfc_fisico($cString)
    {
        $cPatron = '/[A-Z]{4}[0-9]{6}[A-Z0-9]{3}$/';
        return $this->regex_match($cString, $cPatron);
    }

    /**
     * Metodo que se encarga de validar que solo se pueda usar letras y underscore
     *
     * @access	public
     * @param	string cString
     * @example hola|hola_mundo|hola_mundo_hola casos aceptados
     * @return	boolean true|false
     */
    public function alpha_underscore($cString)
    {
        $cPatron = "/^([a-z_])+$/i";
        return $this->regex_match($cString, $cPatron);
    }
    
    /**
     * Metodo que se encarga de validar que solo se pueda usar letras, numeos y underscore
     *
     * @access	public
     * @param	string cString
     * @example hola|hola_mundo|hola_mundo_hola casos aceptados
     * @return	boolean true|false
     */
    public function alpha_numeric_underscore($cString)
    {
        $cPatron = "/^([a-z0-9_])+$/i";
        return $this->regex_match($cString, $cPatron);
    }
    
    /**
     * Metodo que se encarga de validar que solo se pueda usar letras, numeos y underscore
     *
     * @access	public
     * @param	string cString
     * @example hola|hola_mundo|hola_mundo_hola casos aceptados
     * @return	boolean true|false
     */
    public function alpha_numeric_underscore_guion($cString)
    {
        $cPatron = "/^([a-z0-9\_\-])+$/i";
        return $this->regex_match($cString, $cPatron);
    }
    
    /**
     * Metodo que se encarga de 
     * @param type $cString
     * @return type
     */
    public function natural_no_zero_y_solo_comas($cString)
    {
        $cPatron = "/^[0-9]+([,][0-9]*)?$/";
        return $this->regex_match($cString, $cPatron);
    }

    /**
     * Metodo que se encarga de validar que el campo sea mayo al campo especificado
     *
     * @access	public
     * @param	string str valor del campo
     * @param string $field campo con el que se va a comparar
     * @return	boolean true|false
     */
    public function greater_than_field($str, $field)
    {
        $iField = $this->CI->input->post($field);
        if (!is_numeric($iField))
        {
            return false;
        }
        return parent::greater_than($str, $iField);
    }

    /**
     * Metodo que se encarga de validar que el campo sea mayo al campo especificado
     *
     * @access	public
     * @param	string cString
     * @example hola|hola_mundo|hola_mundo_hola casos aceptados
     * @return	boolean true|false
     */
    public function less_than_field($str, $field)
    {
        $iField = $this->CI->input->post($field);
        if (!is_numeric($iField))
        {
            return false;
        }
        return parent::less_than($str, $iField);
    }

    /**
     * Metodo que se encarga de validar si un registro se encuentra repetido, unicamente hace la validacion con registros
     * que no cuentan un borrado logico
     * 
     * @param string $str valor del campo del formulario
     * @param string $field Cadena de texto compuesta por el nombre de la tabla y por el campo a validar
     * @return boolean resultaod de la comparacion
     */
    public function is_unique_no_delete($str, $field)
    {
        $aExplode= explode('.', $field);
        
        if(count($aExplode) == 2)
        {
            list($table, $field) = $aExplode;
        }
        
        if(count($aExplode) == 3)
        {
            list($table, $field, $id) = $aExplode;
        }
        
        if(count($aExplode) == 4)
        {
            list($table, $field, $id, $case) = $aExplode;
            
            if($case === "uppercase")
            {
                mb_strtoupper(trim($str), 'UTF-8'); 
            }
            
            if($case === "lowercase")
            {
                mb_strtolower(trim($str), 'UTF-8');
            }
        }
        
        
        $this->CI->db->from($table);
        $this->CI->db->where($field, trim($str));
        $this->CI->db->where("bBorradoLogico", NO);
        
        if(count($aExplode) >= 3)
        {
            $idPost = $this->CI->input->post($id, true);
            $this->CI->db->where_not_in($id, $idPost);            
        }
        
        $query = $this->CI->db->get();
        debug("lastquery -> " .$this->CI->db->last_query());
        
        return $query->num_rows() === 0;
    }
    
 
    /**
     * Metodo que sobreescribe al is_unique anterior
     * @param type $str
     * @param type $field
     * @return type
     */
    public function is_unique($str, $field)
    {
        $aExplode = explode('.', $field);

        if (count($aExplode) == 2)
        {
            list($table, $field) = $aExplode;
        }

        if (count($aExplode) == 3)
        {
            list($table, $field, $id) = $aExplode;
        }

        if (count($aExplode) == 4)
        {
            list($table, $field, $id, $case) = $aExplode;

            if ($case === "uppercase")
            {
                mb_strtoupper(trim($str), 'UTF-8');
            }

            if ($case === "lowercase")
            {
                mb_strtolower(trim($str), 'UTF-8');
            }
        }

        $this->CI->db->from($table);
        $this->CI->db->where($field, trim($str));

        if (count($aExplode) >= 3)
        {
            $idPost = $this->CI->input->post($id, true);
            $this->CI->db->where_not_in($id, $idPost);
        }

        $query = $this->CI->db->get();
        debug("lastquery -> " .$this->CI->db->last_query());
        return $query->num_rows() === 0;
    }

    public function is_habilitado_no_borrado($str, $field)
    {
        $aExplode = explode('.', $field);
        list($table, $id) = $aExplode;

        $this->CI->db->from($table);
        $this->CI->db->where($id, $str);
        $this->CI->db->where("bHabilitado", SI);
        $this->CI->db->where("bBorradoLogico", NO);

        $query = $this->CI->db->get();

        return $query->num_rows() > 0;
    }
    
    public function is_habilitado($str, $field)
    {
        $aExplode = explode('.', $field);
        list($table, $id) = $aExplode;

        $this->CI->db->from($table);
        $this->CI->db->where($id, $str);
        $this->CI->db->where("bHabilitado", SI);

        $query = $this->CI->db->get();

        return $query->num_rows() > 0;
    }
    
    public function is_no_borrado($str, $field)
    {
        $aExplode = explode('.', $field);
        list($table, $id) = $aExplode;

        $this->CI->db->from($table);
        $this->CI->db->where($id, $str);
        $this->CI->db->where("bBorradoLogico", NO);

        $query = $this->CI->db->get();


        return $query->num_rows() > 0;
    }
    
    public function is_existe($str, $field)
    {
        $aExplode = explode('.', $field);
        list($table, $id) = $aExplode;

        $this->CI->db->from($table);
        $this->CI->db->where($id, $str);

        $query = $this->CI->db->get();

        return $query->num_rows() > 0;
    }

    public function validate_date($str, $field)
    {
        $arr = explode("-", $str);
        
        if (count($arr) == 3)
        {
            $y = $arr[0];
            $m = $arr[1];
            $d = $arr[2];

            if (is_numeric($y) && is_numeric($m) && is_numeric($d))
            {
                return checkdate($m, $d, $y);
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
    
    public function validate_date_now($str, $field)
    {
        if($this->validate_date($str, $field))
        {
            return (strtotime($str) <= strtotime(date("Y-m-d")));
        }
        else
        {
            return false;
        }
    }
    
    public function validate_date_menor_a($str, $field)
    {          
        if($this->validate_date($str, $field))
        { 
            return (strtotime($str) < strtotime($this->CI->input->post($field)));
        }
        else
        {
           
            return false;
        }
    }
    
    public function validate_date_mayor_igual_a($str, $field)
    {          
        if($this->validate_date($str, $field))
        { 
            return (strtotime($str) >= strtotime($this->CI->input->post($field)));
        }
        else
        {
           
            return false;
        }
    }
    
    /**
     * Metodo que se encarga de validar que el numero sea un mes valido 
     * 1 - 12 
     * @param type $str
     * @param type $field
     * @return boolean
     */
    public function validate_mes($str, $field)
    {
        if(!$this->is_natural_no_zero($str))
        {
            return false;
        }
        
        return ($str > 0 && $str <= 12);
    }
 

    public function number_sin_guion($str, $field)
    {
        return (bool)preg_match( '/^[0-9]*\.?[0-9]+$/', $str);
    }
    
    public function number_sin_guion_no_zero($str, $field)
    {   
        if(!preg_match( '/^[0-9]*\.?[0-9]+$/', $str))
        {
            return FALSE;
        }
        
        if ($str == 0)
        {
            return FALSE;
        }
    }
    
    // </editor-fold>
        
    // <editor-fold defaultstate="collapsed" desc="SEGURIDAD LOGICA DE NEGOCIO DEL CLIENTE">
    
    /**
     * Metodo que se encarga de validar si el usuario que inicio sesión cuenta con permisos suficientes para administrar
     * el tipo de nomina seleccionado
     */
    public function validar_permiso_tipo_nomina($str, $field)
    {
        $this->CI->load->library("Nominas", array(), "Nominas");
        $this->CI->load->library("ViewModels/TipoNomina_ViewModel");
        
        $oTipoNomina = new TipoNomina_ViewModel();
        $oTipoNomina->idTipoNomina = $str;
        
        if(!isset($field))
        {
            $field = true;
        }
        else
        {
            $field = ($field === "false") ? false:true;
        }
        
        return $this->CI->Nominas->verificarTipoNomina($oTipoNomina);
    }
    
    /**
     * 
     * @return type
     */
    public function validar_permiso_periodo($str, $field)
    {
        $this->CI->load->library("PeriodosNomina", array(), "PeriodosNomina");
        $this->CI->load->library("ViewModels/Periodo_ViewModel");
        
        $oPeriodo = new Periodo_ViewModel();
        $oPeriodo->idPeriodo = $str;
        
        if(!isset($field))
        {
            $field = true;
        }
        else
        {
            $field = ($field === "false") ? false:true;
        }
        
        return $this->CI->PeriodosNomina->verificarPeriodo($oPeriodo, $field);
    }
    
    public function validar_fecha_periodo($str, $field)
    {
        $this->CI->load->library("PeriodosNomina", array(), "PeriodosNomina");
        $this->CI->load->library("ViewModels/Periodo_ViewModel");
        
        return $this->CI->PeriodosNomina->validarFechaPeriodo($str, $this->CI->input->post("idPeriodo"));
    }
    
    /**
     * 
     * @return type
     */
    public function validar_permiso_empleado($str, $field)
    {
        $this->CI->load->library("Empleado", array(), "Empleado");
        $this->CI->load->library("ViewModels/Empleado_ViewModel");
        
        $oEmpleado = new Empleado_ViewModel();
        $oEmpleado->idEmpleado = $str;        
        
        return $this->CI->Empleado->verificarEmpleado($oEmpleado);
    }
    
    
    public function validar_credito_infonavit_activo()
    {
        $this->CI->load->library("Infonavit", array(), "Infonavit");
        return $this->CI->Infonavit->verificarCreditoInfonavit($str);
    }
    
    public function validar_credito_infonavit_suspendido()
    {
        $this->CI->load->library("Infonavit", array(), "Infonavit");
        return $this->CI->Infonavit->verificarCreditoSuspendido($str);
    }
    
    /**
     * 
     */
    public function validar_nomina_estatus()
    {
        $aEstatus = explode(",",ConfigHelper::get('aTiposPeriodosEditables'));
        $this->CI->load->model("nomina/Nomina_model");
        $this->CI->load->library("ViewModels/Nomina_ViewModel");
        $this->CI->load->library("Nominas", array(), "Nominas");        
        
        $oNomina = new Nomina_ViewModel();
        $oNomina->idTipoNomina = $this->CI->input->post("idTipoNomina");
        $oNomina->idPeriodo = $this->CI->input->post("idPeriodo");
        
        $dbNomina = $this->CI->Nomina_model->get($oNomina);
        
        // si no hay nomina registrada quiere decir que es una nueva y porlo tanto debe de pasar
        if(!is_object($dbNomina))
        {
            return true;
        }
        
        $oTipoNomina = new TipoNomina_ViewModel();
        $oTipoNomina->idTipoNomina = $dbNomina->idTipoNomina;
        
        if(!$this->CI->Nominas->verificarTipoNomina($oTipoNomina))
        {
            return false;
        }
        
        return in_array($dbNomina->iEstatus, $aEstatus);
    }
    
    /**
     * Metodo que se encarga de validar que una nomina se encuentre afectada para continuar con algun proceso
     * @return boolean
     */
    public function validar_nomina_afectada()
    {
        $this->CI->load->model("nomina/Nomina_model");
        $this->CI->load->library("Nominas", array(), "Nominas");
        
        $oNomina = new Nomina_ViewModel();
        if($this->CI->input->post("idNomina"))
        {
            $oNomina->idNomina = $this->CI->input->post("idNomina");
        }
        elseif($this->CI->input->post("idTipoNomina") &&  $this->CI->input->post("idPeriodo"))
        {
            $oNomina->idTipoNomina = $this->CI->input->post("idTipoNomina");
            $oNomina->idPeriodo = $this->CI->input->post("idPeriodo");
        }
        else
        {
            return false;
        }
        
        $dbNomina = $this->CI->Nomina_model->get($oNomina);
        // si no hay nomina registrada quiere decir que es una nueva y porlo tanto debe de pasar
        if(!is_object($dbNomina))
        {
            return false;
        }
        
        $oTipoNomina = new TipoNomina_ViewModel();
        $oTipoNomina->idTipoNomina = $dbNomina->idTipoNomina;
        
        if(!$this->CI->Nominas->verificarTipoNomina($oTipoNomina))
        {
            return false;
        }
        
        return ($dbNomina->iEstatus == NOMINA_ESTATUS_NOMINA_AFECTADA_TOTAL);
    }
    
    public function validar_nomina_total()
    {
        $this->CI->load->model("nomina/Nomina_model");
        $this->CI->load->library("Nominas", array(), "Nominas");
        
        $oNomina = new Nomina_ViewModel();
        if($this->CI->input->post("idNomina"))
        {
            $oNomina->idNomina = $this->CI->input->post("idNomina");
        }
        elseif($this->CI->input->post("idTipoNomina") &&  $this->CI->input->post("idPeriodo"))
        {
            $oNomina->idTipoNomina = $this->CI->input->post("idTipoNomina");
            $oNomina->idPeriodo = $this->CI->input->post("idPeriodo");
        }
        else
        {
            return false;
        }
        
        $dbNomina = $this->CI->Nomina_model->get($oNomina);
        // si no hay nomina registrada quiere decir que es una nueva y porlo tanto debe de pasar
        if(!is_object($dbNomina))
        {
            return false;
        }
        
        $oTipoNomina = new TipoNomina_ViewModel();
        $oTipoNomina->idTipoNomina = $dbNomina->idTipoNomina;
        
        if(!$this->CI->Nominas->verificarTipoNomina($oTipoNomina))
        {
            return false;
        }
        
        return ($dbNomina->iEstatus == NOMINA_ESTATUS_NOMINA_TOTAL);
    }
    
    
     public function validar_nomina($str, $field)
    {
        $aEstatus = explode(",",ConfigHelper::get('aTiposPeriodosEditables'));
        $this->CI->load->model("nomina/Nomina_model");
        $this->CI->load->library("ViewModels/Nomina_ViewModel");
        $this->CI->load->library("Nominas", array(), "Nominas");
        $this->CI->load->library("PeriodosNomina", array(), "PeriodosNomina");
        
        $oNomina = new Nomina_ViewModel();
        $oNomina->idNomina = $this->CI->input->post("idNomina");
        
        $dbNomina = $this->CI->Nomina_model->get($oNomina);
        
        // si no hay nomina registrada quiere decir que es una nueva y porlo tanto debe de pasar
        if(!is_object($dbNomina))
        {
            return true;
        }
        
        $oTipoNomina = new TipoNomina_ViewModel();
        $oTipoNomina->idTipoNomina = $dbNomina->idTipoNomina;
        
        if(!$this->CI->Nominas->verificarTipoNomina($oTipoNomina))
        {
            return false;
        }
        
        if(!isset($field))
        {
            $field = true;
        }
        else
        {
            $field = ($field === "false")? false:true;
        }
        
        if($field)
        {
            return in_array($dbNomina->iEstatus, $aEstatus);
        }
        
        return true;
    }
   
    
    
    
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="VALIDAR EMPLEADO LOGICA DE NEGOCIO DEL CLIENTE">
    
    
    /**
     * Metodo que se encarga de validar si el empleado cuanta con un salario valido
     * @return boolean
     */
    public function validar_salario_empleado()
    {
        $this->CI->load->library("Salario", array(), "Salario");
        
        $bValidacion = false;
        
        if(!$this->CI->input->post("idUbicacion") 
                || !$this->CI->input->post("fSalario")){
            return false;
        }
        
        $fSalarioGeneral = $this->CI->Salario->getSalarioGeneral(array(
            "idUbicacion" => $this->CI->input->post("idUbicacion")
        ));
        
        if($fSalarioGeneral != NULL)
        {
            $fSalarioDiario = $this->CI->Salario->calcularSalarioDiario(array(
                "idFrecuencia" => $this->CI->input->post("idFrecuencia"),
                "fSalario" => $this->CI->input->post("fSalario")
            ));
            
            $bValidacion = (round($fSalarioDiario, 2) >= round($fSalarioGeneral, 2));
        }
        
        return $bValidacion;
    }
    
    /**
     * Metodo que se encarga de validar que el usurio puede asigar un empleado a un cliente, este cliente debe de estar asignado al tipo de nomina
     * seleccionado
     * 
     * @param idTipoNomina
     * @return true|false
     */
    public function validar_cliente()
    {
        $bResult = false;
        if ($this->CI->input->post("idTipoNomina") && $this->CI->input->post("idCliente"))
        {
            $iTipoNomina = UsuarioHelper::getTipoNominaConta($this->CI->input->post("idTipoNomina"), $this->CI->input->post("idCliente"));
            if ($iTipoNomina > 0)
            {
                $bResult = true;
            }
        }
        return $bResult;
    }
    
    /**
     * Metodo que se encarga de comprobar que el salario diario integrado corresponda 
     * @return true|false
     */
    public function validar_salario_diario_integrado()
    {
        $this->CI->load->library("Salario", array(), "Salario");
        $this->CI->load->library("Prestaciones", array(), "Prestaciones");
        
        $fSalarioDiario = $this->CI->Salario->calcularSalarioDiario(array(
            "idFrecuencia" => $this->CI->input->post("idFrecuencia"),
            "fSalario" => $this->CI->input->post("fSalario")
        ));

        $fFactorIntegracion = $this->CI->Prestaciones->getFactorIntegracion(array(
            "idTablaPrestacion" => $this->CI->input->post("idTablaPrestacion")
        ));

        $fSalarioPreIntegrado = $this->CI->Salario->calcularSDPI(array(
            "fFactorIntegracion" => $fFactorIntegracion,
            "fSalarioDiario" => $fSalarioDiario
        ));

        $fSalarioDiarioIntegrado = $this->CI->Salario->calcularSDI(array(
            "fSalarioPreIntegrado" => $fSalarioPreIntegrado,
            "fPromedioVariables" => $this->CI->input->post("fPromedioVariables")
        ));
        
        return (round($fSalarioDiarioIntegrado, 2) === round($this->CI->input->post("fSalarioDiarioIntegrado"), 2));
    }
    
// </editor-fold>


}

// END Form Validation Class

/* End of file MY_Form_validation.php */
/* Location: ./application/libraries/MY_Form_validation.php */
