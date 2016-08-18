<?php

/*
 * Idioma
 * 
 * Clase encargada de abstraer la informacion de la base de datos
 * 
 * @package models
 * @author DEVELOPER 1 <correo@developer1>
 * @create date 06-09-2014
 * @update date 
 */

class Idioma_model extends MY_Model {


    public $idIdioma;
    public $cNombre;


    public function __construct() {
        parent::__construct();
        $this->loadTable("tcidiomas");
    }


    /**
     * Metodo que se encarga de retornar un array de datos de Idioma, de acuerdo al criterio de filtrado
     * puede retornar un array de objectos o un array de arrays 
     * 
     * @access public
     * @param object viewmodel $oIdioma
     * @param booelan $bArray para especificar el tipo de retorno true array false object
     * @return array $aResult informacion de la tabla
     */
    public function getAll($oIdioma, $bArray = false) {

        $this->db->select("i.idIdioma,i.cNombre, i.cAlias, i.cNombreArchivo", FALSE);
        $this->db->from("tcidiomas AS i");


        // filtros por Idioma
        if ($oIdioma->idIdioma) {
            $this->db->where("i.idIdioma", $oIdioma->idIdioma);
        }
        
        if ($oIdioma->cNombre) {
            $this->db->like("i.cNombre", $oIdioma->cNombre);
        }
        
        if ($oIdioma->cAlias) {
            $this->db->like("i.cAlias", $oIdioma->cAlias);
        }
        
        if ($oIdioma->cNombreArchivo) {
            $this->db->like("i.cNombreArchivo", $oIdioma->cNombreArchivo);
        }

        //filtros generales
        if ($oIdioma->limit && $oIdioma->offset) {
            $this->db->limit($oIdioma->limit, $oIdioma->offset);
        } else {
            if ($oIdioma->limit) {
                $this->db->limit($oIdioma->limit);
            }
        }


        if ($oIdioma->sortBy && $oIdioma->order) {
            $this->db->order_by($oIdioma->order . ' ' . $oIdioma->sortBy);
        }


        if (is_array($oIdioma->not) && count($oIdioma->not) > 0) {
            foreach ($oIdioma->not as $key => $value) {
                $this->db->where_not_in($key, $value);
            }
        }

        if ($oIdioma->count) {
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
     * @param object viewmodel $oIdioma
     * @param booelan $bArray para especificar el tipo de retorno true array false object
     * @return array $aResult informacion de la tabla
     */
    public function get($oIdioma, $bArray = FALSE) {
        $aData = $this->getAll($oIdioma, $bArray);
        if (is_numeric($aData)) {
            return $aData;
        }
        return (count($aData) > 0) ? $aData[0] : array();
    }


    /**
     * Metodo que se encarga de agregar un nuevo Idioma a la tabla
     * 
     * @access public
     * @param object viewmodel $oIdioma
     * @return int indentificador del registro insertado
     */
    public function insertar($oIdioma) {
        $this->create();
        $oIdioma->bHabilitado = SI;
        $this->save($oIdioma);
        return $this->getInsertID();
    }


    /**
     * Metodo que se encarga de actualizar un registro Idioma en la tabla
     * 
     * @access public
     * @param object viewmodel $oIdioma
     * @return int indentificador del registro actualizado
     */
    public function actualizar($oIdioma) {
        $this->save($oIdioma, $oIdioma->idIdioma);
        return $this->getID();
    }


    /**
     * Metodo que se encarga de realizar un borraddo fisico
     * 
     * @access public
     * @param object viewmodel $oIdioma
     * @return boolean 
     */
    public function eliminar($oIdioma) {
        $dbIdioma = $this->find(array("idIdioma" => $oIdioma->idIdioma));
        $dbIdioma->bBorradoLogico = SI;
        $this->save($dbIdioma, $dbIdioma->idIdioma);
        return $this->getID();
    }
    
    public function existe($aParams = array()) {
        
        if((isset($aParams["id"])) && ($aParams["id"] > 0)){
            $this->db->where("idIdioma <>", $aParams["id"]);
        }
        
        $this->db->from("tcidiomas");
        $this->db->where($aParams["columna"], $aParams["valor"]);
        return ($this->db->count_all_results() > 0) ? TRUE : FALSE;
    }
}
