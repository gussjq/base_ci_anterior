<?php

class Modulo_model extends MY_Model {

    public $idModulo;
    public $cNombre;
    public $cAlias;
    public $cDescripcion;
    public $cEtiquetaTitulo;
    public $cEtiquetaDescripcion;
    public $cIcono;
    public $bHabilitado;

    public function __construct() {
        parent::__construct();
        $this->loadTable("tcmodulos");
    }

    public function getAll($oModulo, $bArray = false) {

        $this->db->select("m.idModulo, m.cNombre, m.cAlias, m.cDescripcion, m.cEtiquetaTitulo, m.cEtiquetaDescripcion,, m.cIcono, m.bHabilitado", FALSE);
        $this->db->from("tcmodulos as m");

        // filtros por modulo
        if ($oModulo->idModulo) {
            $this->db->where("m.idModulo", $oModulo->idModulo);
        }

        if ($oModulo->cNombre) {
            $this->db->like("m.cNombre", $oModulo->cNombre);
        }

        if ($oModulo->cAlias) {
            $this->db->like("m.cAlias", $oModulo->cAlias);
        }

        if ($oModulo->cDescripcion) {
            $this->db->like("m.cDescripcion", $oModulo->cDescripcion);
        }

        if ($oModulo->cEtiquetaTitulo) {
            $this->db->like("m.cEtiquetaTitulo", $oModulo->cEtiquetaTitulo);
        }

        if ($oModulo->cIcono) {
            $this->db->like("m.cIcono", $oModulo->cIcono);
        }

        if ($oModulo->bHabilitado) {
            $this->db->where("m.bHabilitado", $oModulo->bHabilitado);
        }

        //filtros generales
        if ($oModulo->limit && $oModulo->offset) {
            $this->db->limit($oModulo->limit, $oModulo->offset);
        } else {
            if ($oModulo->limit) {
                $this->db->limit($oModulo->limit);
            }
        }

        if ($oModulo->sortBy && $oModulo->order) {
            $this->db->order_by($oModulo->order . ' ' . $oModulo->sortBy);
        }

        if (is_array($oModulo->not) && count($oModulo->not) > 0) {
            foreach ($oModulo->not as $key => $value) {
                $this->db->where_not_in($key, $value);
            }
        }

        if ($oModulo->count) {
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

    public function get($oMoudulo, $bArray = FALSE) {
        $aData = $this->getAll($oMoudulo, $bArray);
        if (is_numeric($aData)) {
            return $aData;
        }
        return (count($aData) > 0) ? $aData[0] : array();
    }

    public function insertar($oMoudulo) {
        $this->create();
        $oMoudulo->bHabilitado = SI;
        $this->save($oMoudulo);
        return $this->getInsertID();
    }

    public function actualizar($oModulo) {
        $dbModulo = $this->find(array("idModulo" => $oModulo->idModulo), 'bHabilitado');
        $oModulo->bHabilitado = $dbModulo->bHabilitado;
        $this->save($oModulo, $oModulo->idModulo);
        return $this->getID();
    }

    public function habilitar($oModulo) {
        $dbModulo = $this->find(array("idModulo" => $oModulo->idModulo, "bHabilitado" => NO));
        $dbModulo->bHabilitado = SI;
        $this->save($dbModulo, $dbModulo->idModulo);
        return $this->getID();
    }

    public function deshabilitar($oModulo) {
        $dbModulo = $this->find(array("idModulo" => $oModulo->idModulo, "bHabilitado" => SI));
        $dbModulo->bHabilitado = NO;
        $this->save($dbModulo, $dbModulo->idModulo);
        return $this->getID();
    }

    public function existe($aParams = array()) {
        
        if((isset($aParams["id"])) && ($aParams["id"] > 0)){
            $this->db->where("idModulo <>", $aParams["id"]);
        }
        
        $this->db->from("tcmodulos");
        $this->db->where($aParams["columna"], $aParams["valor"]);
        return ($this->db->count_all_results() > 0) ? TRUE : FALSE;
    }

}
