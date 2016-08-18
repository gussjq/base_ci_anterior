<?php

/*
 * RolAccion
 * 
 * Clase encargada de abstraer la informacion de la base de datos
 * 
 * @package models
 * @author DEVELOPER 1 <correo@developer1>
 * @create date 06-09-2014
 * @update date 
 */

class RolAccion_model extends MY_Model {

    public $idRolAccion;
    public $idRol;
    public $idAccion;

    public function __construct() {
        parent::__construct();
        $this->loadTable("tdrolaccion");
    }

    public function getAll($oRolAccion, $bArray = false) {

        $this->db->select("ra.idRol, ra.idAccion", FALSE);
        $this->db->select("a.idAccion, a.cNombre, a.cAlias, a.cDescripcion", FALSE);
        $this->db->from("tdrolaccion AS ra");
        $this->db->join("tcaccion AS a", "ra.idAccion = a.idAccion", "inner");
        
        if($oRolAccion->idRol){
            $this->db->where("idRol", $oRolAccion->idRol);
        }       

        //filtros generales
        if ($oRolAccion->limit && $oRolAccion->offset) {
            $this->db->limit($oRolAccion->limit, $oRolAccion->offset);
        } else {
            if ($oRolAccion->limit) {
                $this->db->limit($oRolAccion->limit);
            }
        }

        if ($oRolAccion->sortBy && $oRolAccion->order) {
            $this->db->order_by($oRolAccion->order . ' ' . $oRolAccion->sortBy);
        }
        
        if (is_array($oRolAccion->not) && count($oRolAccion->not) > 0) {
            foreach($oRolAccion->not as $key => $value) {
                $this->db->where_not_in($key, $value);
            }
      }

        if ($oRolAccion->count) {
            return $this->db->count_all_results();
        }

        $query = $this->db->get();
        $aData = array();
        if (!$this->db->_error_message()) {
            if ($query->num_rows() > 0) {
                $aData = ($bArray) ? $query->result_array() : $query->result();
            }
        }
        return $aData;
    }

    public function get($oRolAccion, $bArray = FALSE) {
        $aData = $this->getAll($oRolAccion, $bArray);
        if(is_numeric($aData)){
            return $aData;
        }
        return (count($aData) > 0) ? $aData[0] : array();
    }

    /**
     * Metodo que se encarga de agregar un nuevo RolAccion a la tabla
     * 
     * @access public
     * @param object viewmodel $oRolAccion
     * @return int indentificador del registro insertado
     */
    public function insertar($oRolAccion) {
        $this->create();
        $this->save($oRolAccion);
        return $this->getInsertID();
    }

    /**
     * Metodo que se encarga de realizar un borraddo fisico
     * 
     * @access public
     * @param int identificador del rol
     * @return boolean 
     */
    public function eliminar($idRol) {
        $this->db->where('idRol', $idRol);
        $this->db->delete("tdrolaccion");
    }

}
