<?php

class Usuario_model extends MY_Model {

    public function __construct()
    {
        $this->loadTable("tcusuarios");
    }

    public function getAll($oUsuario, $bArray = FALSE)
    {
        $this->db->select("u.*", FALSE);
        $this->db->select("CONCAT(u.cNombre, ' ' ,u.cApellidoPaterno, ' ', u.cApellidoMaterno) AS cNombreCompleto", FALSE);
        $this->db->select("r.idRol, r.cNombre AS cNombreRol", FALSE);
        $this->db->from("tcusuarios AS u");
        $this->db->join("tcroles AS r", "u.idRol = r.idRol AND r.bHabilitado = " . SI . "", "inner");
        $this->db->where("u.bBorradoLogico", NO);

        if ($oUsuario->idUsuario)
        {
            $this->db->where('u.idUsuario', $oUsuario->idUsuario);
        }

        if ($oUsuario->idRol)
        {
            $this->db->where('u.idRol', $oUsuario->idRol);
        }

        if ($oUsuario->cNombre)
        {
            $this->db->like('u.cNombre', $oUsuario->cNombre);
        }

        if ($oUsuario->cApellidoPaterno)
        {
            $this->db->like('u.cApellidoPaterno', $oUsuario->cApellidoPaterno);
        }

        if ($oUsuario->cApellidoMaterno)
        {
            $this->db->like('u.cApellidoMaterno', $oUsuario->cApellidoMaterno);
        }

        if ($oUsuario->cCorreo)
        {
            $this->db->where('u.cCorreo', $oUsuario->cCorreo);
        }

        if ($oUsuario->cContrasena)
        {
            $this->db->where('u.cContrasena', $oUsuario->cContrasena);
        }

        if ($oUsuario->dtFechaAcceso)
        {
            $this->db->where('u.dtFechaAcceso', $oUsuario->dtFechaAcceso);
        }

        if ($oUsuario->iIntentosAcceso)
        {
            $this->db->where('u.iIntentosAcceso', $oUsuario->iIntentosAcceso);
        }

        if ($oUsuario->bHabilitado)
        {
            $this->db->where('u.bHabilitado', $oUsuario->bHabilitado);
        }

        if ($oUsuario->dtFechaIntentosAcceso)
        {
            $this->db->where('u.dtFechaIntentosAcceso', $oUsuario->dtFechaIntentosAcceso);
        }

        if ($oUsuario->cRecuperar)
        {
            $this->db->where('u.cRecuperar', $oUsuario->cRecuperar);
        }

        if ($oUsuario->bBloqueado)
        {
            $this->db->where('u.bBloqueado', $oUsuario->bBloqueado);
        }

        //filtros generales
        if ($oUsuario->limit && $oUsuario->offset)
        {
            $this->db->limit($oUsuario->limit, $oUsuario->offset);
        }
        else
        {
            if ($oUsuario->limit)
            {
                $this->db->limit($oUsuario->limit);
            }
        }

        if ($oUsuario->sortBy && $oUsuario->order)
        {
            $this->db->order_by($oUsuario->order . ' ' . $oUsuario->sortBy);
        }

        if (is_array($oUsuario->not) && count($oUsuario->not) > 0)
        {
            foreach ($oUsuario->not as $key => $value)
            {
                $this->db->where_not_in($key, $value);
            }
        }
       
        if ($oUsuario->count)
        {
            return $this->db->count_all_results();
        }

        $query = $this->db->get();
        $aData = array();

        if (!$this->db->_error_message())
        {
            if ($query->num_rows() > 0)
            {
                $aData = ($bArray) ? $query->result_array() : $query->result();
            }
        }
        return $aData;
    }

    public function get($oUsuario, $bArray = FALSE)
    {
        $aData = $this->getAll($oUsuario, $bArray);
        if (is_numeric($aData))
        {
            return $aData;
        }
        return (count($aData) > 0) ? $aData[0] : array();
    }

    public function insertar($oUsuario)
    {
        $this->create();
        $oUsuario->bHabilitado = SI;
        $oUsuario->bBorradoLogico = NO;
        $oUsuario->bBloqueado = NO;
        $oUsuario->bNuevo = SI;
        $this->save($oUsuario);
        return $this->getInsertID();
    }

    public function actualizar($oUsuario)
    {
        $dbUsuario = $this->find(array("idUsuario" => $oUsuario->idUsuario));
        $oUsuario->dtFechaAcceso = $dbUsuario->dtFechaAcceso;
        $oUsuario->iIntentosAcceso = $dbUsuario->iIntentosAcceso;
        $oUsuario->dtFechaIntentosAcceso = $dbUsuario->dtFechaIntentosAcceso;
        $oUsuario->bHabilitado = $dbUsuario->bHabilitado;
        $oUsuario->bBorradoLogico = $dbUsuario->bBorradoLogico;
        $oUsuario->bBloqueado = $dbUsuario->bBloqueado;
        $oUsuario->cRecuperar = $dbUsuario->cRecuperar;
        $this->save($oUsuario, $dbUsuario->idUsuario);
        return $this->getID();
    }

    public function eliminar($oUsuario)
    {
        $dbUsuario = $this->find(array("idUsuario" => $oUsuario->idUsuario));
        $dbUsuario->bBorradoLogico = SI;
        $id = $this->save($dbUsuario, $dbUsuario->idUsuario);
        return $id;
    }

    public function habilitar($oUsuario)
    {
        $dbUsuario = $this->find(array("idUsuario" => $oUsuario->idUsuario));
        $dbUsuario->bHabilitado = SI;
        $id = $this->save($dbUsuario, $dbUsuario->idUsuario);
        return $id;
    }

    public function deshabilitar($oUsuario)
    {
        $dbUsuario = $this->find(array("idUsuario" => $oUsuario->idUsuario));
        $dbUsuario->bHabilitado = NO;
        $id = $this->save($dbUsuario, $dbUsuario->idUsuario);
        return $id;
    }

    /**
     * Metodo que se encarga de incrementar los intentos de acceso de un usuario
     * 
     * @access public
     * @param integer $idUsuario
     * @return integer identificador del usuario
     */
    public function incrementarIntentosAcceso($idUsuario = 0, $iNumerosIntentosConf = 0)
    {
        $dbUsuario = $this->find(array("idUsuario" => $idUsuario, "bHabilitado" => SI, "bBorradoLogico" => NO));
        if (is_object($dbUsuario))
        {
            $dbUsuario->iIntentosAcceso++;
            $iIntentos = $dbUsuario->iIntentosAcceso;
            if ($iNumerosIntentosConf <= $iIntentos)
            {
                $dbUsuario->bBloqueado = SI;
                $dbUsuario->iIntentosAcceso = 0;
            }

            if ($dbUsuario->bBloqueado == SI)
            {
                if ($iNumerosIntentosConf <= $iIntentos)
                {
                    $dbUsuario->dtFechaIntentosAcceso = date("Y-m-d H:i:s");
                }
            }
            else
            {
                $dbUsuario->dtFechaIntentosAcceso = date("Y-m-d H:i:s");
            }
            $idUsuario = $this->save($dbUsuario, $dbUsuario->idUsuario);
        }
        return $idUsuario;
    }

    /**
     * Metodo que se encarga de recetear los intentos de un usuario al ingresar al sistema
     * 
     * @access public
     * @param integer $idUsuario
     * @return integer identificador del usuario
     */
    public function recetearIntentosAcceso($idUsuario = 0)
    {
        $dbUsuario = $this->find(array("idUsuario" => $idUsuario, "bHabilitado" => SI, "bBorradoLogico" => NO));
        if (is_object($dbUsuario))
        {
            $dbUsuario->iIntentosAcceso = 0;
            $dbUsuario->dtFechaIntentosAcceso = NULL;
            $dbUsuario->bBloqueado = NO;
            $idUsuario = $this->save($dbUsuario, $dbUsuario->idUsuario);
        }
        return $idUsuario;
    }

    /**
     * Recupera los permisos por usuario
     * @param integer $idRol
     */
    public function getPermisos($idRol = 0)
    {
        $sql = "SELECT DISTINCT(a.idAccion), a.idModulo, a.cNombre AS cNombreAccion, a.idTipoAccion, m.cNombre AS cNombreModulo
            FROM (`tdrolaccion` AS ra)
                INNER JOIN `tcroles` AS r ON `r`.`idRol` = `ra`.`idRol` AND r.bHabilitado = ? 
                INNER JOIN `tcaccion` AS a ON ((`ra`.`idAccion` = `a`.`idAccion`) OR (a.idTipoAccion =  ?) OR (a.idTipoAccion =  ?)) AND a.bHabilitado = ? 
                INNER JOIN `tcmodulos` AS m ON `a`.`idModulo` = `m`.`idModulo` AND m.bHabilitado = ? 
            WHERE `ra`.`idRol` =  ?";

        $query = $this->db->query($sql, array(SI, TIPO_ACCION_PUBLICA, TIPO_ACCION_PUBLICA_AJAX, SI, SI, $idRol));
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

    public function guardaRecuperar($oUsuario)
    {
        $dbUsuario = $this->find(array("idUsuario" => $oUsuario->idUsuario));
        $dbUsuario->cRecuperar = $oUsuario->cRecuperar;
        $this->save($dbUsuario, $dbUsuario->idUsuario);
    }
    
    /**
     * Metodo que se encarga de devolver una lista de usuarios para ser procesados por el plugin autocomplete
     * es utilizado en el catálogo de avisos para identificar a que usuarios se les mandara el aviso
     * 
     * @param string $cTerm
     * @param array $aItems
     * @return type
     */
    public function getAutoComplete($cTerm, $aItems = array())
    {
        $this->db->select("u.*, CONCAT(u.cNombre, ' ',u.cApellidoPaterno, ' ',u.cApellidoMaterno) AS cNombreCompleto", false);
        $this->db->from("tcusuarios AS u");
        $this->db->where("u.bBorradoLogico", NO);
        $this->db->where("u.bHabilitado", SI);
        $this->db->where("u.bBloqueado", NO);
        
        $this->db->like("cNombre", $cTerm);
        $this->db->or_like("cApellidoPaterno", $cTerm);
        $this->db->or_like("cApellidoMaterno", $cTerm);
        $this->db->or_like("cCorreo", $cTerm);    
        
        if(count($aItems) > 0)
        {
            $this->db->where_not_in("idUsuario", $aItems);
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
