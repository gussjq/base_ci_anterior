<?php

/*
 * AsociarCliente_model
 * 
 * Clase que sirve como representación de la capa de datos de la tabla de AsociarCliente,
 * en donde se encarga de la conexion a la base de datos asi como de realizar las operaciones basicas en la tabla de acuerdo al 
 * nivel de acceso descrita en la logica del negocio (Controller) 
 * 
 * @package models
 * @author DEVELOPER 1 <correo@developer1> cel : <1111111111>
 * @created 17-04-2015
 */
class AsociarCliente_model extends MY_Model {

    public function __construct()
    {
        parent::__construct();
        $this->loadTable("tdusuariocliente");
    }

    /**
     * Metodo que se encarga de retornar un array de datos de AsociarCliente, de acuerdo al criterio de filtrado
     * puede retornar un array de objectos o un array de arrays 
     * 
     * @access public
     * @param object viewmodel $oAsociarCliente
     * @param booelan $bArray para especificar el tipo de retorno true array false object
     * @return array $aResult informacion de la tabla
     */
    public function getAll($oAsociarCliente, $bArray = false)
    {
        $this->db->select("uc.*", FALSE);
        $this->db->select("c.cNombre AS cNombreCliente, c.cCodigo", FALSE);
        $this->db->select("CONCAT(u.cNombre, ' ', u.cApellidoPaterno, ' ', u.cApellidoMaterno) AS cNombreUsuario", FALSE);
        $this->db->from("tdusuariocliente AS uc");
        $this->db->join("tcusuarios AS u", "uc.idUsuario = u.idUsuario", "inner");
        $this->db->join("tcclientes AS c", "uc.idCliente = c.idCliente", "inner");
        $this->db->where("u.bBorradoLogico", NO);
        $this->db->where("c.bBorradoLogico", NO);   
        $this->db->where("u.bHabilitado", SI);
        $this->db->where("c.bHabilitado", SI); 
        $this->db->where("u.idUsuario", $oAsociarCliente->idUsuario);
        
        if ($oAsociarCliente->cNombreUsuario)
        {
            $this->db->like("u.cNombre", $oAsociarCliente->cNombreUsuario);
            $this->db->or_like("u.cApellidoPaterno", $oAsociarCliente->cNombreUsuario);
            $this->db->or_like("u.cApellidoMaterno", $oAsociarCliente->cNombreUsuario);
        }
        
        if ($oAsociarCliente->idCliente)
        {
            $this->db->where("c.idCliente", $oAsociarCliente->idCliente);
        }
        
        if($oAsociarCliente->cNombreCliente)
        {
            $this->db->like("c.cNombre", $oAsociarCliente->cNombreCliente);
            $this->db->or_like("c.cCodigo", $oAsociarCliente->cCodigo);
        }

        //filtros generales
        if ($oAsociarCliente->limit && $oAsociarCliente->offset)
        {
            $this->db->limit($oAsociarCliente->limit, $oAsociarCliente->offset);
        }
        else
        {
            if ($oAsociarCliente->limit)
            {
                $this->db->limit($oAsociarCliente->limit);
            }
        }

        if ($oAsociarCliente->sortBy && $oAsociarCliente->order)
        {
            $this->db->order_by($oAsociarCliente->order . ' ' . $oAsociarCliente->sortBy);
        }

        if (is_array($oAsociarCliente->not) && count($oAsociarCliente->not) > 0)
        {
            foreach ($oAsociarCliente->not as $key => $value)
            {
                $this->db->where_not_in($key, $value);
            }
        }

        if ($oAsociarCliente->count)
        {
            return $this->db->count_all_results();
        }

        $query = $this->db->get();
        
        $aResult = array();
        if (!$this->db->_error_message())
        {
            if ($query->num_rows() > 0)
            {
                $aResult = ($bArray) ? $query->result_array() : $query->result();
            }
        }
        return $aResult;
    }

        /**
     * Metodo que se encarga de retornar un unico registro, de acuerdo al criterio de filtrado
     * puede retornar un objeto o un array
     * 
     * @access public
     * @param object viewmodel $oAsociarCliente
     * @param booelan $bArray para especificar el tipo de retorno true array false object
     * @return array $aResult informacion de la tabla
     */
    public function get($oAsociarCliente, $bArray = FALSE)
    {
        $aData = $this->getAll($oAsociarCliente, $bArray);
        if (is_numeric($aData))
        {
            return $aData;
        }
        return (count($aData) > 0) ? $aData[0] : array();
    }
    
    public function insertar($idUsuario, $aClientes)
    {   
        $data = array();
        $this->db->delete("tdusuariocliente", array("idUsuario" => $idUsuario));
        
        if (!empty($aClientes))
        {
            foreach ($aClientes as $idCliente)
            {
                $data[] = array("idUsuario" => $idUsuario, "idCliente" => $idCliente);
            }
            $this->db->insert_batch("tdusuariocliente", $data);
        }
    }

}
