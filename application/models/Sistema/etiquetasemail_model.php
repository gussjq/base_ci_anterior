<?php

/*
 * EtiquetasEmail
 * 
 * Clase encargada de abstraer la informacion de la base de datos
 * 
 * @package models
 * @author DEVELOPER 1 <correo@developer1>
 * @create date 06-09-2014
 * @update date 
 */

class EtiquetasEmail_model extends MY_Model {


    public $idEtiquetasEmail;
    public $cNombre;
    public $cEtiqueta;
    public $cDescripcion;


    public function __construct() {
        parent::__construct();
        $this->loadTable("tdetiquetasemail");
    }


    /**
     * Metodo que se encarga de retornar un array de datos de EtiquetasEmail, de acuerdo al criterio de filtrado
     * puede retornar un array de objectos o un array de arrays 
     * 
     * @access public
     * @param object viewmodel $oEtiquetasEmail
     * @param booelan $bArray para especificar el tipo de retorno true array false object
     * @return array $aResult informacion de la tabla
     */
    public function getAll($oEtiquetasEmail, $bArray = false) {


        $this->db->select("e.idEtiquetasEmail, e.idEmail, e.cNombre, e.cEtiqueta, e.cEtiquetaDescripcion", FALSE);
        $this->db->from("tdetiquetasemail AS e");


        // filtros por EtiquetasEmail
        if ($oEtiquetasEmail->idEtiquetasEmail) {
            $this->db->where("e.idEtiquetasEmail", $oEtiquetasEmail->idEtiquetasEmail);
        }

        if ($oEtiquetasEmail->idEmail) {
            $this->db->where("e.idEmail", $oEtiquetasEmail->idEmail);
        }
        
        if ($oEtiquetasEmail->cEtiqueta) {
            $this->db->like("e.cEtiqueta", $oEtiquetasEmail->cEtiqueta);
        }

        if ($oEtiquetasEmail->cEtiquetaDescripcion) {
            $this->db->like("e.cEtiquetaDescripcion", $oEtiquetasEmail->cEtiquetaDescripcion);
        }
        
        //filtros generales
        if ($oEtiquetasEmail->limit && $oEtiquetasEmail->offset) {
            $this->db->limit($oEtiquetasEmail->limit, $oEtiquetasEmail->offset);
        } else {
            if ($oEtiquetasEmail->limit) {
                $this->db->limit($oEtiquetasEmail->limit);
            }
        }


        if ($oEtiquetasEmail->sortBy && $oEtiquetasEmail->order) {
            $this->db->order_by($oEtiquetasEmail->order . ' ' . $oEtiquetasEmail->sortBy);
        }


        if (is_array($oEtiquetasEmail->not) && count($oEtiquetasEmail->not) > 0) {
            foreach ($oEtiquetasEmail->not as $key => $value) {
                $this->db->where_not_in($key, $value);
            }
        }


        if ($oEtiquetasEmail->count) {
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
     * @param object viewmodel $oEtiquetasEmail
     * @param booelan $bArray para especificar el tipo de retorno true array false object
     * @return array $aResult informacion de la tabla
     */
    public function get($oEtiquetasEmail, $bArray = FALSE) {
        $aData = $this->getAll($oEtiquetasEmail, $bArray);
        if (is_numeric($aData)) {
            return $aData;
        }
        return (count($aData) > 0) ? $aData[0] : array();
    }
}

