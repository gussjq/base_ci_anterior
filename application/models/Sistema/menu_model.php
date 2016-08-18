<?php

/*
 * Menu
 * 
 * Clase encargada de abstraer la informacion de la base de datos
 * 
 * @package models
 * @author DEVELOPER 1 <correo@developer1>
 * @create date 06-09-2014
 * @update date 
 */

class Menu_model extends MY_Model {

    public function __construct()
    {
        parent::__construct();
        $this->loadTable("tcmenu");
    }

    /**
     * Metodo que se encarga de retornar un array de datos de Menu, de acuerdo al criterio de filtrado
     * puede retornar un array de objectos o un array de arrays 
     * 
     * @access public
     * @param object viewmodel $oMenu
     * @param booelan $bArray para especificar el tipo de retorno true array false object
     * @return array $aResult informacion de la tabla
     */
    public function getAll($oMenu, $bArray = false)
    {
        $this->db->select("m.idMenu, m.cNombre, m.cDescripcion, m.bHabilitado", FALSE);
        $this->db->from("tcmenu AS m");


        // filtros por Menu
        if ($oMenu->idMenu)
        {
            $this->db->where("idMenu", $oMenu->idMenu);
        }

        if ($oMenu->cNombre)
        {
            $this->db->like("cNombre", $oMenu->cNombre);
        }

        if ($oMenu->cDescripcion)
        {
            $this->db->like("cDescripcion", $oMenu->cDescripcion);
        }

        if ($oMenu->bHabilitado)
        {
            $this->db->where("bHabilitado", $oMenu->bHabilitado);
        }

        //filtros generales
        if ($oMenu->limit && $oMenu->offset)
        {
            $this->db->limit($oMenu->limit, $oMenu->offset);
        }
        else
        {
            if ($oMenu->limit)
            {
                $this->db->limit($oMenu->limit);
            }
        }


        if ($oMenu->sortBy && $oMenu->order)
        {
            $this->db->order_by($oMenu->order . ' ' . $oMenu->sortBy);
        }


        if (is_array($oMenu->not) && count($oMenu->not) > 0)
        {
            foreach ($oMenu->not as $key => $value)
            {
                $this->db->where_not_in($key, $value);
            }
        }


        if ($oMenu->count)
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
     * @param object viewmodel $oMenu
     * @param booelan $bArray para especificar el tipo de retorno true array false object
     * @return array $aResult informacion de la tabla
     */
    public function get($oMenu, $bArray = FALSE)
    {
        $aData = $this->getAll($oMenu, $bArray);
        if (is_numeric($aData))
        {
            return $aData;
        }
        return (count($aData) > 0) ? $aData[0] : array();
    }

    /**
     * Metodo que se encarga de actualizar un registro Menu en la tabla
     * 
     * @access public
     * @param object viewmodel $oaplicativo
     * @return int indentificador del registro actualizado
     */
    public function actualizar($oMenu)
    {
        $dbMenu = $this->find(array("idMenu" => $oMenu->idMenu), 'bHabilitado');
        $oMenu->bHabilitado = $dbMenu->bHabilitado;
        $this->save($oMenu, $oMenu->idMenu);
        return $this->getID();
    }

    /**
     * Metodo que se encarga de habilitar un Menu en la tabla
     * 
     * @access public
     * @param object viewmodel $oaplicativo
     * @return int indentificador del registro insertado
     */
    public function habilitar($oMenu)
    {
        $dbMenu = $this->find(array("idMenu" => $oMenu->idMenu));
        $dbMenu->bHabilitado = SI;
        $this->save($dbMenu, $dbMenu->idMenu);
        return $this->getID();
    }

    /**
     * Metodo que se encarga de deshabilitar un Menu en la tabla 
     * 
     * @access public
     * @param object viewmodel $oaplicativo 
     * @return int identificador del Menu
     */
    public function deshabilitar($oMenu)
    {
        $dbMenu = $this->find(array("idMenu" => $oMenu->idMenu));
        $dbMenu->bHabilitado = NO;
        $this->save($dbMenu, $dbMenu->idMenu);
        return $this->getID();
    }

    /**
     * Metodo que devuelve un menu o panel del sistema con base al nivel de acceso del usuario logeado
     * 
     * @access public
     * @param int $idRol 
     * @param int $idTipoMenu
     * @param int $idMenuPadre
     * @param boolean $bMenu
     * @return array
     */
    public function getMenu($idRol, $idTipoMenu, $idMenuPadre = NULL, $bMenu = TRUE)
    {
        $cFiltro = "";
        $cSql = "(
                    SELECT 
                            it.idItems, it.idMenu, it.idModulo, it.idAccion, it.idMenuPadre, 
                            it.iOrden, it.cLink, it.cIcono, it.cEtiquetaTitulo, it.cEtiquetaDescripcion,
                            NULL AS cNombreModulo, it.bPadre
                    FROM tcitems AS it
                    WHERE idMenu = %s AND (idAccion IS NULL OR idAccion = 0)
                    %s
                    
                    ) UNION (

                    SELECT 
                            it.idItems, it.idMenu, it.idModulo, it.idAccion, it.idMenuPadre, 
                            it.iOrden, it.cLink,
                            (CASE WHEN (it.cIcono = '' OR it.cIcono IS NULL) THEN
                                    m.cIcono
                            ELSE
                                    it.cIcono
                            END) AS cIcono,
                            (CASE WHEN (it.cEtiquetaTitulo = '' OR it.cEtiquetaTitulo IS NULL) THEN
                                    m.cEtiquetaTitulo
                            ELSE
                                    it.cEtiquetaTitulo
                            END) AS cEtiquetaTitulo,
                            (CASE WHEN (it.cEtiquetaDescripcion = '' OR it.cEtiquetaDescripcion IS NULL) THEN
                                    m.cEtiquetaDescripcion
                            ELSE
                                    it.cEtiquetaDescripcion
                            END) AS cEtiquetaDescripcion,
                            m.cNombre AS cNombreModulo, it.bPadre
                    FROM tcroles AS r 
                            INNER JOIN tdrolaccion AS ra ON r.idRol = ra.idRol
                            INNER JOIN tcaccion AS a ON ra.idAccion = a.idAccion OR a.idTipoAccion = %s
                            INNER JOIN tcmodulos AS m ON a.idModulo = m.idModulo 
                            INNER JOIN tcitems AS it ON a.idAccion = it.idAccion 
                    WHERE r.idRol = %s AND r.bHabilitado = %s AND a.bHabilitado = %s AND m.bHabilitado = %s AND it.idMenu = %s
                    %s
                    ) ORDER BY iOrden ASC";


        if ($bMenu == FALSE)
        {
            $menuPadre = (is_null($idMenuPadre)) ? "IS NULL" : " = {$this->db->escape_str($idMenuPadre)}";
            $cFiltro = " AND it.idMenuPadre {$menuPadre} ";
        }

        $cSql = sprintf($cSql, $idTipoMenu, $cFiltro, TIPO_ACCION_PUBLICA, $idRol, SI, SI, SI, $idTipoMenu, $cFiltro);
        $query = $this->db->query($cSql);
        
        $aItems = array();
        $aMenu = array();
        
        if($this->db->_error_message())
        {
            return $aMenu;
        }
        
        if($query->num_rows() == 0)
        {
            return $aMenu;
        }
        
        $aItems = $query->result();
        if ($bMenu)
        {
            $aMenu = $this->_getItemsRoot($aItems);
            foreach ($aMenu as $key => $oItemRoot)
            {
                $oItemRoot->aHijos = array();
                $aHijos = $this->_agruparMenu($oItemRoot, $aItems);
                if (count($aHijos) > 0)
                {
                    $oItemRoot->aHijos = $aHijos;
                }
                $aMenu[$key] = $oItemRoot;
            }
            
            $aMenu = $this->_verficarIntegridad($aMenu);
        }
        else
        {
            $aMenu = $aItems;
        }

        return $aMenu;
    }

    private function _getItemsRoot($aItems)
    {
        $aItemsRoot = array();
        foreach ($aItems as $oItem)
        {
            if ($oItem->idMenuPadre == NULL)
            {
                $aItemsRoot[] = $oItem;
            }
        }
        return $aItemsRoot;
    }

    private function _agruparMenu($oItem, $aItems)
    {
        $aHijos = array();
        foreach ($aItems as $item)
        {
            if ($oItem->idItems == $item->idMenuPadre)
            {
                $aHijos[] = $item;
            }
        }

        if (count($aHijos) > 0)
        {
            foreach ($aHijos as $key => $oItem)
            {
                $oItem->aHijos = array();
                $aNietos = $this->_agruparMenu($oItem, $aItems);
                if (count($aNietos) > 0)
                {
                    $oItem->aHijos = $aNietos;
                }
                $aHijos[$key] = $oItem;
            }
        }
        return $aHijos;
    }
    
    /**
     * Metodo que se encarga de verificar la integridad del menu, en caso de no tener items asociados a una accion
     * son eliminadas del menu debido a que unicamente serian items de tipo agrupador sin relevancia para el usuario
     * 
     * @access private
     * @param array $aMenu
     * @param boolean $bInicio
     * @return array $aMenu
     */
    private function _verficarIntegridad($aMenu)
    {
        foreach($aMenu as $key => $oMenu)
        {
            $oMenu->idAccion = (int) $oMenu->idAccion;
            if((($oMenu->idAccion === 0)) && (count($oMenu->aHijos) > 0))
            {
               $oMenu->aHijos = $this->_verficarIntegridad($oMenu->aHijos);
               $aMenu[$key] = $oMenu;
            }
            if((($oMenu->idAccion === 0)) && (count($oMenu->aHijos) === 0))
            {
                unset($aMenu[$key]);
            }
        }
        return $aMenu;
    }

}