<?php

/*
 * Aviso_model
 * 
 * Clase que sirve como representación de la capa de datos de la tabla de Aviso,
 * en donde se encarga de la conexion a la base de datos asi como de realizar las operaciones basicas en la tabla de acuerdo al 
 * nivel de acceso descrita en la logica del negocio (Controller) 
 * 
 * @package models
 * @author DEVELOPER 1 <correo@developer1> cel : <1111111111>
 * @created 08-04-2015
 */

class Aviso_model extends MY_Model {

    public function __construct()
    {
        parent::__construct();
        $this->loadTable("tcavisos");
    }

        /**
     * Metodo que se encarga de retornar un array de datos de Aviso, de acuerdo al criterio de filtrado
     * puede retornar un array de objectos o un array de arrays 
     * 
     * @access public
     * @param object viewmodel $oAviso
     * @param booelan $bArray para especificar el tipo de retorno true array false object
     * @return array $aResult informacion de la tabla
     */
    public function getAll($oAviso, $bArray = false)
    {
        $this->db->select("a.*", FALSE);
        $this->db->select("r.*", FALSE);
        $this->db->from("tcavisos AS a");
        $this->db->join("tdreceptor AS r", "a.idAviso = r.idAviso", "inner");
        $this->db->join("tcusuarios AS u", "r.idUsuario = u.idUsuario", "inner");
        $this->db->where("r.idUsuario", UsuarioHelper::get("idUsuario"));
        $this->db->where("u.bHabilitado", SI);
        $this->db->where("u.bBloqueado", NO);
        $this->db->where("u.bBorradoLogico", NO);
        
        if ($oAviso->dtFechaCreacionInicio)
        {
            $this->db->where("a.dtFechaCreacion >", $oAviso->dtFechaCreacionInicio);
        }
        
        if ($oAviso->dtFechaCreacionFin)
        {
            $this->db->where("a.dtFechaCreacion <=", $oAviso->dtFechaCreacionFin);
        }
        
        if ($oAviso->iEstatus)
        {
            $this->db->where("a.iEstatus", $oAviso->iEstatus);
        }
        
        
        if (is_int($oAviso->bLeido))
        {
            $this->db->where("r.bLeido", ($oAviso->bLeido == SI) ? SI : NO);
        }

        //filtros generales
        if ($oAviso->limit && $oAviso->offset)
        {
            $this->db->limit($oAviso->limit, $oAviso->offset);
        }
        else
        {
            if ($oAviso->limit)
            {
                $this->db->limit($oAviso->limit);
            }
        }

        if ($oAviso->sortBy && $oAviso->order)
        {
            $this->db->order_by($oAviso->order . ' ' . $oAviso->sortBy);
        }

        if (is_array($oAviso->not) && count($oAviso->not) > 0)
        {
            foreach ($oAviso->not as $key => $value)
            {
                $this->db->where_not_in($key, $value);
            }
        }

        if ($oAviso->count)
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
     * @param object viewmodel $oAviso
     * @param booelan $bArray para especificar el tipo de retorno true array false object
     * @return array $aResult informacion de la tabla
     */
    public function get($oAviso, $bArray = FALSE)
    {
        $aData = $this->getAll($oAviso, $bArray);
        if (is_numeric($aData))
        {
            return $aData;
        }
        return (count($aData) > 0) ? $aData[0] : array();
    }

}
