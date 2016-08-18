<?php

class Configuracion_model extends MY_Model {

    public $idConfiguracion;
    public $cServidorSmtp;
    public $cPuertoSmtp;
    public $cCorreoSmtp;
    public $cPasswordSmtp;
    public $bSslSmtp;
    public $cMailTypeSmtp;
    public $cProtocolSmtp;
    public $cCharsetSmtp;
    public $iTimeOutSmtp;
    public $cNewLineSmtp;
    public $cLogo;
    public $iIntentosAcceso;
    public $iMinutosIntentosAcceso;
    public $iDuracionSession;
    public $cNombreEmpresa;

    public function __construct()
    {
        $this->loadTable("tcconfiguracion");
    }
    
    /**
     * Metodo que se encarga de retornar un array de datos de Configuracion, de acuerdo al criterio de filtrado
     * puede retornar un array de objectos o un array de arrays 
     * 
     * @access public
     * @param object viewmodel $oConfiguracion
     * @param booelan $bArray para especificar el tipo de retorno true array false object
     * @return array $aResult informacion de la tabla
     */
    public function getAll($oConfiguracion, $bArray = false) {

        $this->db->select("c.*", FALSE);
        $this->db->from("tcconfiguracion AS c");
        
        if($oConfiguracion->iTipoConfiguracion)
        {
           $this->db->where("c.iTipoConfiguracion", $oConfiguracion->iTipoConfiguracion); 
        }

        //filtros generales
        if ($oConfiguracion->limit && $oConfiguracion->offset) {
            $this->db->limit($oConfiguracion->limit, $oConfiguracion->offset);
        } else {
            if ($oConfiguracion->limit) {
                $this->db->limit($oConfiguracion->limit);
            }
        }


        if ($oConfiguracion->sortBy && $oConfiguracion->order) {
            $this->db->order_by($oConfiguracion->order . ' ' . $oConfiguracion->sortBy);
        }


        if (is_array($oConfiguracion->not) && count($oConfiguracion->not) > 0) {
            foreach ($oConfiguracion->not as $key => $value) {
                $this->db->where_not_in($key, $value);
            }
        }

        if ($oConfiguracion->count) {
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
     * @param object viewmodel $oConfiguracion
     * @param booelan $bArray para especificar el tipo de retorno true array false object
     * @return array $aResult informacion de la tabla
     */
    public function get($oConfiguracion, $bArray = FALSE) {
        $aData = $this->getAll($oConfiguracion, $bArray);
        if (is_numeric($aData)) {
            return $aData;
        }
        return (count($aData) > 0) ? $aData[0] : array();
    }

    public function actualizar($oConfiguracion)
    {
        $idConfiguracion = 0;
        $dbConfiguracion = $this->find(array("cClave" => $oConfiguracion->cClave));
        
        if (is_object($dbConfiguracion))
        {
            $dbConfiguracion->cValor = $oConfiguracion->cValor;
            $idConfiguracion = $this->save($dbConfiguracion, $dbConfiguracion->idConfiguracion);
        }
        
        return $idConfiguracion;
    }

}
