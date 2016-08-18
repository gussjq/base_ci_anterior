<?php

class Accion_model extends MY_Model {

    public $idAccion;
    public $idModulo;
    public $cNombre;
    public $cAlias;
    public $cDescripcion;
    public $idTipoAccion;
    public $bHabilitado;

    public function __construct() {
        parent::__construct();
        $this->loadTable("tcaccion");
    }

    public function getAll($oAccion, $bArray = false) {

        $this->db->select("a.idAccion, a.idModulo, a.cNombre, a.cAlias, a.cDescripcion, a.idTipoAccion", FALSE);
        $this->db->select("a.bHabilitado", FALSE);
        $this->db->select("m.idModulo AS idAccionModulo, m.cNombre AS cNombreModulo, m.cAlias AS cAliasModulo, m.cEtiquetaTitulo AS cEtiquetaTituloModulo, m.cEtiquetaDescripcion AS cEtiquetaDescripcionModulo", FALSE);
        $this->db->from("tcaccion as a");
        $this->db->join("tcmodulos AS m", "a.idModulo = m.idModulo", "inner");

        if ($oAccion->idAccion) {
            $this->db->where("a.idAccion", $oAccion->idAccion);
        }

        if ($oAccion->idModulo) {
            $this->db->where("a.idModulo", $oAccion->idModulo);
        }

        if ($oAccion->cNombre) {
            $this->db->like("a.cNombre", $oAccion->cNombre);
        }

        if ($oAccion->cAlias) {
            $this->db->like("a.cAlias", $oAccion->cAlias);
        }

        if ($oAccion->cDescripcion) {
            $this->db->like("a.cDescripcion", $oAccion->cDescripcion);
        }

        if ($oAccion->idTipoAccion) {
            $this->db->where("a.idTipoAccion", $oAccion->idTipoAccion);
        }

        if ($oAccion->bHabilitado) {
            $this->db->where("a.bHabilitado", $oAccion->bHabilitado);
        }

        //filtros generales
        if ($oAccion->limit && $oAccion->offset) {
            $this->db->limit($oAccion->limit, $oAccion->offset);
        } else {
            if ($oAccion->limit) {
                $this->db->limit($oAccion->limit);
            }
        }

        if ($oAccion->sortBy && $oAccion->order) {
            $this->db->order_by($oAccion->order . ' ' . $oAccion->sortBy);
        }

        if (is_array($oAccion->not) && count($oAccion->not) > 0) {
            foreach ($oAccion->not as $key => $value) {
                $this->db->where_not_in($key, $value);
            }
        }

        if ($oAccion->count) {
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

    public function get($oAccion, $bArray = FALSE) {
        $aData = $this->getAll($oAccion, $bArray);
        if (is_numeric($aData)) {
            return $aData;
        }
        return (count($aData) > 0) ? $aData[0] : array();
    }

    public function insertar($oAccion) {
        $this->create();
        $oAccion->bHabilitado = SI;
        $this->save($oAccion);
        return $this->getInsertID();
    }

    public function actualizar($oAccion) {
        $dbAccion = $this->find(array("idAccion" => $oAccion->idAccion, "bHabilitado" => SI), 'bHabilitado');
        $oAccion->bHabilitado = $dbAccion->bHabilitado;
        $this->save($oAccion, $oAccion->idAccion);
        return $this->getID();
    }

    public function habilitar($oAccion) {
        $dbAccion = $this->find(array("idAccion" => $oAccion->idAccion));
        $dbAccion->bHabilitado = SI;
        $this->save($dbAccion, $dbAccion->idAccion);
        return $this->getID();
    }

    public function deshabilitar($oAccion) {
        $dbAccion = $this->find(array("idAccion" => $oAccion->idAccion));
        $dbAccion->bHabilitado = NO;
        $this->save($dbAccion, $dbAccion->idAccion);
        return $this->getID();
    }

    public function existe($aParams = array()) {
        
        if((isset($aParams["id"])) && ($aParams["id"] > 0)){
            $this->db->where("idAccion <>", $aParams["id"]);
        }
        
        $this->db->from("tcaccion");
        $this->db->where("idModulo", $aParams["idModulo"]);
        $this->db->where($aParams["columna"], $aParams["valor"]);
        
        return ($this->db->count_all_results() > 0) ? TRUE : FALSE;
    }
}
