<?php
/*
 * TipoEmail
 * 
 * Clase encargada de abstraer la informacion de la base de datos
 * 
 * @package models
 * @author DEVELOPER 1 <correo@developer1>
 * @create date 06-09-2014
 * @update date 
 */

class TipoEmail_model extends MY_Model {


    public $idTipoEmail;
    public $cNombre;


    public function __construct() {
        parent::__construct();
        $this->loadTable("tdtipoemail");
    }


    /**
     * Metodo que se encarga de retornar un array de datos de TipoEmail, de acuerdo al criterio de filtrado
     * puede retornar un array de objectos o un array de arrays 
     * 
     * @access public
     * @param object viewmodel $oTipoEmail
     * @param booelan $bArray para especificar el tipo de retorno true array false object
     * @return array $aResult informacion de la tabla
     */
    public function getAll($oTipoEmail, $bArray = false) {


        $this->db->select("te.idTipoEmail, te.cTitulo, te.cEtiqueta", FALSE);
        $this->db->from("tdtipoemail AS te");


        // filtros por TipoEmail
        if ($oTipoEmail->idTipoEmail) {
            $this->db->where("te.idTipoEmail", $oTipoEmail->idTipoEmail);
        }

        if ($oTipoEmail->cTitulo) {
            $this->db->like("te.cTitulo", $oTipoEmail->cNombre);
        }
        
        if ($oTipoEmail->cEtiqueta) {
            $this->db->like("te.cEtiqueta", $oTipoEmail->cEtiqueta);
        }

        //filtros generales
        if ($oTipoEmail->limit && $oTipoEmail->offset) {
            $this->db->limit($oTipoEmail->limit, $oTipoEmail->offset);
        } else {
            if ($oTipoEmail->limit) {
                $this->db->limit($oTipoEmail->limit);
            }
        }


        if ($oTipoEmail->sortBy && $oTipoEmail->order) {
            $this->db->order_by($oTipoEmail->order . ' ' . $oTipoEmail->sortBy);
        }


        if (is_array($oTipoEmail->not) && count($oTipoEmail->not) > 0) {
            foreach ($oTipoEmail->not as $key => $value) {
                $this->db->where_not_in($key, $value);
            }
        }


        if ($oTipoEmail->count) {
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
     * @param object viewmodel $oTipoEmail
     * @param booelan $bArray para especificar el tipo de retorno true array false object
     * @return array $aResult informacion de la tabla
     */
    public function get($oTipoEmail, $bArray = FALSE) {
        $aData = $this->getAll($oTipoEmail, $bArray);
        if (is_numeric($aData)) {
            return $aData;
        }
        return (count($aData) > 0) ? $aData[0] : array();
    }
   
}
