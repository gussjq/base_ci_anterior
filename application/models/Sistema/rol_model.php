<?php
/*
 * Rol
 * 
 * Clase encargada de abstraer la informacion de la base de datos
 * 
 * @package models
 * @author DEVELOPER 1 <correo@developer1>
 * @create date 06-09-2014
 * @update date 
 */

class Rol_model extends MY_Model {


    public function __construct() {
        parent::__construct();
        $this->loadTable("tcroles");
    }


    /**
     * Metodo que se encarga de retornar un array de datos de Roles, de acuerdo al criterio de filtrado
     * puede retornar un array de objectos o un array de arrays 
     * 
     * @access public
     * @param object viewmodel $oRol
     * @param booelan $bArray para especificar el tipo de retorno true array false object
     * @return array $aResult informacion de la tabla
     */
    public function getAll($oRol, $bArray = false) {

        $this->db->select("r.idRol, r.cNombre, r.cDescripcion, r.bHabilitado", FALSE);
        $this->db->from("tcroles AS r");

        // filtros por Rol
        if ($oRol->idRol) {
            $this->db->where("r.idRol", $oRol->idRol);
        }
        
        if ($oRol->cNombre) {
            $this->db->like("r.cNombre", $oRol->cNombre);
        }
        
        if ($oRol->cDescripcion) {
            $this->db->like("r.cDescripcion", $oRol->cDescripcion);
        }
        
        if ($oRol->bHabilitado) {
            $this->db->where("r.bHabilitado", $oRol->bHabilitado);
        }

        //filtros generales
        if ($oRol->limit && $oRol->offset) {
            $this->db->limit($oRol->limit, $oRol->offset);
        } else {
            if ($oRol->limit) {
                $this->db->limit($oRol->limit);
            }
        }

        if ($oRol->sortBy && $oRol->order) {
            $this->db->order_by($oRol->order . ' ' . $oRol->sortBy);
        }
        
        if (is_array($oRol->not) && count($oRol->not) > 0) {
            foreach($oRol->not as $key => $value) {
                $this->db->where_not_in($key, $value);
            }
      }

        if ($oRol->count) {
            return $this->db->count_all_results();
        }

        $query = $this->db->get();
        
        $aResult = array();
        if (!$this->db->_error_message()) {
            if ($query->num_rows() > 0) {
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
     * @param object viewmodel $oRol
     * @param booelan $bArray para especificar el tipo de retorno true array false object
     * @return array $aResult informacion de la tabla
     */
    public function get($oRol, $bArray = FALSE) {
        $aData = $this->getAll($oRol, $bArray);
        if(is_numeric($aData)){
            return $aData;
        }
        return (count($aData) > 0) ? $aData[0] : array();
    }


    /**
     * Metodo que se encarga de agregar un nuevo Rol a la tabla
     * 
     * @access public
     * @param object viewmodel $oRol
     * @return int indentificador del registro insertado
     */
    public function insertar($oRol) {
        $this->create();
        $oRol->bHabilitado = SI;
        $this->save($oRol);
        return $this->getInsertID();
    }


    /**
     * Metodo que se encarga de actualizar un registro Rol en la tabla
     * 
     * @access public
     * @param object viewmodel $oRol
     * @return int indentificador del registro actualizado
     */
    public function actualizar($oRol) {
        $dbRol = $this->find(array("idRol" => $oRol->idRol), 'bHabilitado');
        $oRol->bHabilitado = $dbRol->bHabilitado;
        $this->save($oRol, $oRol->idRol);
        return $this->getID();
    }


    /**
     * Metodo que se encarga de realizar un borraddo fisico
     * 
     * @access public
     * @param object viewmodel $oRol
     * @return boolean 
     */
    public function eliminar($oRol) {
        $this->db->where("idRol", $oRol->idRol);
        $this->db->delete("tcroles");
    }


    /**
     * Metodo que se encarga de habilitar un Rol en la tabla
     * 
     * @access public
     * @param object viewmodel $oRol
     * @return int indentificador del registro insertado
     */
    public function habilitar($oRol) {
        
        $dbRol = $this->find(array("idRol" => $oRol->idRol));
        $dbRol->bHabilitado = SI;
        
        $this->save($dbRol, $dbRol->idRol);
        return $this->getID();
    }


    /**
     * Metodo que se encarga de deshabilitar un Rol en la tabla 
     * 
     * @access public
     * @param object viewmodel $oRol 
     * @return int identificador del Rol
     */
    public function deshabilitar($oRol) {
        $dbRol = $this->find(array("idRol" => $oRol->idRol));
        $dbRol->bHabilitado = NO;
        $this->save($dbRol, $dbRol->idRol);
        return $this->getID();
    }


   public function existe($aParams = array()) {
        
        if((isset($$aParams["id"])) && ($aParams["id"] > 0)){
            $this->db->where("idRol <>", $aParams["id"]);
        }
        
        $this->db->from("tcroles");
        $this->db->where($aParams["columna"], $aParams["valor"]);
        return ($this->db->count_all_results() > 0) ? TRUE : FALSE;
    }
    
    
    /**
     * Metodo que se encarga de devolver una lista de roles para ser procesados por el plugin autocomplete
     * es utilizado en el catálogo de avisos para identificar a que roles se les mandara el aviso
     * 
     * @param string $cTerm
     * @param array $aItems
     * @return type
     */
    public function getAutoComplete($cTerm, $aItems = array())
    {
        $this->db->select("r.*");
        $this->db->from("tcroles AS r");
        $this->db->like("cNombre", $cTerm);
        $this->db->where("bHabilitado", SI);
        
        if(count($aItems) > 0)
        {
            $this->db->where_not_in("idRol", $aItems);
        }
        
        $query = $this->db->get();
        $aData = array();

        if (!$this->db->_error_message())
        {
            if ($query->num_rows() > 0)
            {
               $aData = $query->result();
            }
        }
        
        return $aData;
    }
}
