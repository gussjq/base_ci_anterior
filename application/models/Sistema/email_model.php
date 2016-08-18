<?php

/*
 * Email
 * 
 * Clase encargada de abstraer la informacion de la base de datos
 * 
 * @package models
 * @author DEVELOPER 1 <correo@developer1>
 * @create date 06-09-2014
 * @update date 
 */

class Email_model extends MY_Model {

    public function __construct()
    {
        parent::__construct();
        $this->loadTable("tcemail");
    }

    /**
     * Metodo que se encarga de retornar un array de datos de Email, de acuerdo al criterio de filtrado
     * puede retornar un array de objectos o un array de arrays 
     * 
     * @access public
     * @param object viewmodel $oEmail
     * @param booelan $bArray para especificar el tipo de retorno true array false object
     * @return array $aResult informacion de la tabla
     */
    public function getAll($oEmail, $bArray = false)
    {
        $this->db->select("e.*", FALSE);
        $this->db->select("i.cAlias", FALSE);
        $this->db->from("tcemail AS e");
        $this->db->join("tcidiomas AS i", "e.idIdioma = i.idIdioma", "inner");

        // filtros por Email
        if ($oEmail->idEmail)
        {
            $this->db->where("e.idEmail", $oEmail->idEmail);
        }

        if ($oEmail->cTitulo)
        {
            $this->db->like("e.cTitulo", $oEmail->cTitulo);
        }

        if ($oEmail->cDescripcion)
        {
            $this->db->like("e.cDescripcion", $oEmail->cDescripcion);
        }

        if ($oEmail->txCuerpo)
        {
            $this->db->like("e.txCuerpo", $oEmail->txCuerpo);
        }

        if ($oEmail->idIdioma)
        {
            $this->db->where("e.idIdioma", $oEmail->idIdioma);
        }

        if ($oEmail->idTipoEmail)
        {
            $this->db->where("e.idTipoEmail", $oEmail->idTipoEmail);
        }

        //filtros generales
        if ($oEmail->limit && $oEmail->offset)
        {
            $this->db->limit($oEmail->limit, $oEmail->offset);
        }
        else
        {
            if ($oEmail->limit)
            {
                $this->db->limit($oEmail->limit);
            }
        }


        if ($oEmail->sortBy && $oEmail->order)
        {
            $this->db->order_by($oEmail->order . ' ' . $oEmail->sortBy);
        }


        if (is_array($oEmail->not) && count($oEmail->not) > 0)
        {
            foreach ($oEmail->not as $key => $value)
            {
                $this->db->where_not_in($key, $value);
            }
        }


        if ($oEmail->count)
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
     * @param object viewmodel $oEmail
     * @param booelan $bArray para especificar el tipo de retorno true array false object
     * @return array $aResult informacion de la tabla
     */
    public function get($oEmail, $bArray = FALSE)
    {
        $aData = $this->getAll($oEmail, $bArray);
        if (is_numeric($aData))
        {
            return $aData;
        }
        return (count($aData) > 0) ? $aData[0] : array();
    }

    /**
     * Metodo que se encarga de agregar un nuevo Email a la tabla
     * 
     * @access public
     * @param object viewmodel $oEmail
     * @return int indentificador del registro insertado
     */
    public function insertar($oEmail)
    {
        $this->create();
        $this->save($oEmail);
        return $this->getInsertID();
    }

    /**
     * Metodo que se encarga de actualizar un registro Email en la tabla
     * 
     * @access public
     * @param object viewmodel $oEmail
     * @return int indentificador del registro actualizado
     */
    public function actualizar($oEmail)
    {
        $this->save($oEmail, $oEmail->idEmail);
        return $this->getID();
    }

    /**
     * 
     * @param type $aParams
     * @return type
     */
    public function existe($aParams = array())
    {
        if ((isset($$aParams["id"])) && ($aParams["id"] > 0))
        {
            $this->db->where("idEmail <>", $aParams["id"]);
        }

        $this->db->from("tcemail");
        $this->db->where($aParams["columna"], $aParams["valor"]);
        return ($this->db->count_all_results() > 0) ? TRUE : FALSE;
    }

}
