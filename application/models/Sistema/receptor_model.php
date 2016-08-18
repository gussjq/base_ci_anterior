<?php

/*
 * Receptor_model
 * 
 * Clase que sirve como representación de la capa de datos de la tabla de Receptor,
 * en donde se encarga de la conexion a la base de datos asi como de realizar las operaciones basicas en la tabla de acuerdo al 
 * nivel de acceso descrita en la logica del negocio (Controller) 
 * 
 * @package models
 * @author DEVELOPER 1 <correo@developer1> cel : <1111111111>
 * @created 08-04-2015
 */

class Receptor_model extends MY_Model {

    public function __construct()
    {
        parent::__construct();
        $this->loadTable("tdreceptor");
    }

        /**
     * Metodo que se encarga de retornar un array de datos de Receptor, de acuerdo al criterio de filtrado
     * puede retornar un array de objectos o un array de arrays 
     * 
     * @access public
     * @param object viewmodel $oReceptor
     * @param booelan $bArray para especificar el tipo de retorno true array false object
     * @return array $aResult informacion de la tabla
     */
    public function getAll($oReceptor, $bArray = false)
    {
        $this->db->select("a.*", FALSE);

        // filtros por Receptor
        if ($oReceptor->idReceptor)
        {
            $this->db->where("a.idReceptor", $oReceptor->idReceptor);
        }

        //filtros generales
        if ($oReceptor->limit && $oReceptor->offset)
        {
            $this->db->limit($oReceptor->limit, $oReceptor->offset);
        }
        else
        {
            if ($oReceptor->limit)
            {
                $this->db->limit($oReceptor->limit);
            }
        }

        if ($oReceptor->sortBy && $oReceptor->order)
        {
            $this->db->order_by($oReceptor->order . ' ' . $oReceptor->sortBy);
        }

        if (is_array($oReceptor->not) && count($oReceptor->not) > 0)
        {
            foreach ($oReceptor->not as $key => $value)
            {
                $this->db->where_not_in($key, $value);
            }
        }

        if ($oReceptor->count)
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
     * @param object viewmodel $oReceptor
     * @param booelan $bArray para especificar el tipo de retorno true array false object
     * @return array $aResult informacion de la tabla
     */
    public function get($oReceptor, $bArray = FALSE)
    {
        $aData = $this->getAll($oReceptor, $bArray);
        if (is_numeric($aData))
        {
            return $aData;
        }
        return (count($aData) > 0) ? $aData[0] : array();
    }

}
