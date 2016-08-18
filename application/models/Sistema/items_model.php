<?php

/*
 * Items
 * 
 * Clase encargada de abstraer la informacion de la base de datos
 * 
 * @package models
 * @author DEVELOPER 1 <correo@developer1>
 * @create date 06-09-2014
 * @update date 
 */

class Items_model extends MY_Model {

    public function __construct()
    {
        parent::__construct();
        $this->loadTable("tcitems");
    }

    /**
     * Metodo que se encarga de retornar un array de datos de Items, de acuerdo al criterio de filtrado
     * puede retornar un array de objectos o un array de arrays 
     * 
     * @access public
     * @param object viewmodel $oItems
     * @param booelan $bArray para especificar el tipo de retorno true array false object
     * @return array $aResult informacion de la tabla
     */
    public function getAll($oItems, $bArray = false)
    {

        $this->db->select("i.idItems, i.idMenu, i.idModulo, i.idAccion, i.idMenuPadre, i.iOrden, i.cLink, i.bPadre", FALSE);
        $this->db->select("(CASE WHEN (i.cEtiquetaTitulo IS NULL) OR (i.cEtiquetaTitulo = '') THEN m.cEtiquetaTitulo ELSE i.cEtiquetaTitulo END) AS cEtiquetaTitulo,", FALSE);
        $this->db->select("(CASE WHEN (i.cEtiquetaDescripcion IS NULL) OR (i.cEtiquetaDescripcion = '') THEN m.cEtiquetaDescripcion ELSE i.cEtiquetaDescripcion END) AS cEtiquetaDescripcion", FALSE);
        $this->db->select("(CASE WHEN (i.cIcono IS NULL) OR (i.cIcono = '') THEN m.cIcono ELSE i.cIcono END) AS cIcono", FALSE);
        $this->db->from("tcitems as i");
        $this->db->join("tcmodulos AS m", "i.idModulo = m.idModulo AND m.bHabilitado = 1", "left");

        // filtros por Items
        if ($oItems->idItems)
        {
            $this->db->where("idItems", $oItems->idItems);
        }

        if ($oItems->idMenu)
        {
            $this->db->where("idMenu", $oItems->idMenu);
        }

        if ($oItems->idAccion)
        {
            $this->db->where("idAccion", $oItems->idAccion);
        }

        if ($oItems->idMenuPadre)
        {
            $this->db->where("idMenuPadre", $oItems->idMenuPadre);
        }

        if ($oItems->iOrden)
        {
            $this->db->where("iOrden", $oItems->iOrden);
        }

        if ($oItems->cLink)
        {
            $this->db->like("cLink", $oItems->cLink);
        }

        if ($oItems->cEtiquetaTitulo)
        {
            $this->db->where("cEtiquetaTitulo", $oItems->cEtiquetaTitulo);
        }

        if ($oItems->bPadre)
        {
            $this->db->where("bPadre", $oItems->bPadre);
        }

        //filtros generales
        if ($oItems->limit && $oItems->offset)
        {
            $this->db->limit($oItems->limit, $oItems->offset);
        }
        else
        {
            if ($oItems->limit)
            {
                $this->db->limit($oItems->limit);
            }
        }


        if ($oItems->sortBy && $oItems->order)
        {
            $this->db->order_by($oItems->order . ' ' . $oItems->sortBy);
        }


        if (is_array($oItems->not) && count($oItems->not) > 0)
        {
            foreach ($oItems->not as $key => $value)
            {
                $this->db->where_not_in($key, $value);
            }
        }


        if ($oItems->count)
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
     * @param object viewmodel $oItems
     * @param booelan $bArray para especificar el tipo de retorno true array false object
     * @return array $aResult informacion de la tabla
     */
    public function get($oItems, $bArray = FALSE)
    {
        $aData = $this->getAll($oItems, $bArray);
        if (is_numeric($aData))
        {
            return $aData;
        }
        return (count($aData) > 0) ? $aData[0] : array();
    }

    /**
     * Metodo que se encarga de agregar un nuevo Items a la tabla
     * 
     * @access public
     * @param object viewmodel $oaplicativo
     * @return int indentificador del registro insertado
     */
    public function insertar($oItems)
    {
        $this->create();
        $oItems->iOrden = $this->recuperaOrden($oItems);
        $this->save($oItems);
        return $this->getInsertID();
    }

    /**
     * Metodo que se encarga de actualizar un registro Items en la tabla
     * 
     * @access public
     * @param object viewmodel $oaplicativo
     * @return int indentificador del registro actualizado
     */
    public function actualizar($oItems)
    {
        $dbItems = $this->find(array("idItems" => $oItems->idItems));
        $oItems->bPadre = $dbItems->bPadre;
        $oItems->iOrden = $dbItems->iOrden;
        $oItems->idMenuPadre = $dbItems->idMenuPadre;

        $this->save($oItems, $oItems->idItems);
        return $this->getID();
    }

    /**
     * Metodo que se encarga de eliminar el item del menu seleccionado, ademas verifica si el item cuenta con hijos
     * en caso de ser asi tambien los elimina, el tipo de borrado a utilizar es un borrado fisico de la base de datos
     * 
     * @access public
     * @param object viewmodel $oaplicativo
     * @return boolean 
     */
    public function eliminar($oItems)
    {
        $this->db->where("idMenuPadre", $oItems->idItems);
        $this->db->delete("tcitems");

        $this->db->where("idItems", $oItems->idItems);
        $this->db->delete("tcitems");
    }

    public function recuperaOrden($oItems)
    {
        $iOrden = 0;
        if ($oItems->idMenu != 0)
        {
            $oOrden = $this->find(array("idMenu" => $oItems->idMenu, "idMenuPadre" => NULL), "Max(iOrden) AS max_orden");
            $iOrden = (is_null($oOrden->max_orden)) ? 1 : $oOrden->max_orden + 1;
        }
        return $iOrden;
    }

    public function getPadresHijos($idMenu = 0)
    {
        $aPadres = array();
        if ($idMenu > 0)
        {
            $this->db->select("i.idItems, i.idMenu, i.idAccion, i.idMenuPadre, i.iOrden, i.cLink,", FALSE);
            $this->db->select("(CASE WHEN (i.cEtiquetaTitulo IS NULL) OR (i.cEtiquetaTitulo = '') THEN m.cEtiquetaTitulo ELSE i.cEtiquetaTitulo END) AS cEtiquetaTitulo,", FALSE);
            $this->db->select("(CASE WHEN (i.cEtiquetaDescripcion IS NULL) OR (i.cEtiquetaDescripcion = '') THEN m.cEtiquetaDescripcion ELSE i.cEtiquetaDescripcion END) AS cEtiquetaDescripcion", FALSE);
            $this->db->select("(CASE WHEN (i.cIcono IS NULL) OR (i.cIcono = '') THEN m.cIcono ELSE i.cIcono END) AS cIcono", FALSE);
            $this->db->from("tcitems AS i");
            $this->db->join("tcmodulos AS m", "i.idModulo = m.idModulo AND m.bHabilitado = 1", "left");
            $this->db->where("i.idMenu", $idMenu);
            $this->db->where("i.idMenuPadre", NULL);
            $this->db->order_by("i.iOrden", "ASC");

            $query = $this->db->get();
            $aPadres = array();
            if (!$this->db->_error_message())
            {
                $aPadres = $query->result();
                if ($query->num_rows() > 0)
                {
                    foreach ($aPadres as $key => $oItemPadre)
                    {
                        $oItemPadre->cEtiquetaTitulo = lang($oItemPadre->cEtiquetaTitulo);
                        $oItemPadre->aHijos = $this->_getHijos($oItemPadre->idItems);
                        $aPadres[$key] = $oItemPadre;
                    }
                }
            }
        }
        return $aPadres;
    }

    private function _getHijos($idPadre)
    {
        $iConta = 0;
        $this->db->select("i.idItems, i.idMenu, i.idAccion, i.idMenuPadre, i.iOrden, i.cLink,", FALSE);
        $this->db->select("(CASE WHEN (i.cEtiquetaTitulo IS NULL) OR (i.cEtiquetaTitulo = '') THEN m.cEtiquetaTitulo ELSE i.cEtiquetaTitulo END) AS cEtiquetaTitulo,", FALSE);
        $this->db->select("(CASE WHEN (i.cEtiquetaDescripcion IS NULL) OR (i.cEtiquetaDescripcion = '') THEN m.cEtiquetaDescripcion ELSE i.cEtiquetaDescripcion END) AS cEtiquetaDescripcion", FALSE);
        $this->db->select("(CASE WHEN (i.cIcono IS NULL) OR (i.cIcono = '') THEN m.cIcono ELSE i.cIcono END) AS cIcono", FALSE);
        $this->db->from("tcitems AS i");
        $this->db->join("tcmodulos AS m", "i.idModulo = m.idModulo AND m.bHabilitado = 1", "left");
        $this->db->where("i.idMenuPadre", $idPadre);
        $this->db->order_by("i.iOrden", "ASC");

        $query = $this->db->get();
        $dbHijos = array();
        if (!$this->db->_error_message())
        {
            $dbHijos = $query->result();
            if ($query->num_rows() > 0)
            {
                foreach ($dbHijos as $key => $oItems)
                {
                    $oItems->cEtiquetaTitulo = lang($oItems->cEtiquetaTitulo);
                    $oItems->aHijos = array();
                    $iConta = $this->findCount(array("idMenuPadre" => $oItems->idItems));
                    if ($iConta > 0)
                    {
                        $oItems->aHijos = $this->_getHijos($oItems->idItems);
                        $dbHijos[$key] = $oItems;
                    }
                }
            }
        }

        return (count($dbHijos) > 0) ? $dbHijos : array();
    }

    public function ordenarMenu($idMenu, $aItems = array())
    {
        $this->db->where("idMenu", $idMenu);
        $this->db->update("tcitems", array(
            "iOrden" => NULL,
            "bPadre" => NULL,
            "idMenuPadre" => NULL
        ));

        $this->ordena($aItems);
    }

    private function ordena($aItems, $idMenuPadre = NULL)
    {
        $iConta = 1;
        foreach ($aItems as $item)
        {
            $this->db->where("idItems", $item["id"]);
            $this->db->update("tcitems", array(
                "iOrden" => $iConta,
                "idMenuPadre" => $idMenuPadre,
                "bPadre" => ((isset($item["children"])) && (count($item["children"]) > 0)) ? SI : NULL
            ));

            $iConta++;
            if ((isset($item["children"])) && (count($item["children"]) > 0))
            {
                $this->ordena($item["children"], $item["id"]);
            }
        }
    }

}
