<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Libreria que se encarga de la seguridad a nivel sistema
 *
 * @author GussJQ
 * 
 */
class Seguridad {

    protected $CI;
    protected $_trans_depth;

    public function __construct()
    {
        $this->CI = &get_instance();
    }
    
    // <editor-fold defaultstate="collapsed" desc="Metodos seguridad sistema">
    
    /**
     * Metodo que se encarga de cargar un objeto view model con una peticion post
     * 
     * @param object $oObject objeto view model
     * @param boolean $bFilterXss bandera para validar ataques xss 
     * @return object objeto view model con informacion
     */
    public function getPost($oObject, $bFilterXss = true)
    {
        $aPost = $this->CI->input->post();
        if (!empty($aPost))
        {
            foreach ($oObject as $key => $value)
            {
                if (array_key_exists($key, $aPost))
                {
                    if(is_numeric($aPost[$key]))
                    {
                        if($aPost[$key] == 0 || $aPost[$key] == "0" || $aPost[$key] === 0)
                        {
                            $oObject->$key = 0;
                        }
                        else
                        {
                            $oObject->$key = ($this->CI->input->post($key, $bFilterXss)) ? trim($this->CI->input->post($key, $bFilterXss)) : NULL;
                        }
                    }
                    else
                    {
                        $oObject->$key = ($this->CI->input->post($key, $bFilterXss)) ? trim($this->CI->input->post($key, $bFilterXss)) : NULL;
                    }
                }
            }
        }
        
        return $oObject;
    }

    /**
     * Metodo utilizado para debolver a la vista un determinado objeto view model
     * cargado con los datos de un query 
     * 
     * @param object $oViewModel objeto view model
     * @param object $oDbModel objeto resultado de un query
     * @return object objeto view model con informacion
     */
    public function dbToView($oViewModel, $oDbModel)
    {
        if (!empty($oViewModel) && !empty($oDbModel))
        {
            foreach ($oViewModel as $key => $value)
            {
                if (isset($oDbModel->$key))
                {
                    $oViewModel->$key = trim($oDbModel->$key);
                }
            }
        }
        return $oViewModel;
    }

    /**
     * 
     * 
     * @param type $cModulo
     * @param type $cAccion
     * @return boolean
     */
    public function verificarAcceso($cModulo, $cAccion)
    {
        $bResult = FALSE;
        $aPermisos = (isset($_SESSION["_PERMISOS"])) ? $_SESSION["_PERMISOS"] : NULL;

        if ((count($aPermisos) > 0) && $aPermisos != null)
        {
            foreach ($aPermisos as $oAccion)
            {
                if (($oAccion->cNombreModulo == $cModulo) && ($oAccion->cNombreAccion == $cAccion))
                {
                    $bResult = TRUE;
                    break;
                }
            }
        }

        return $bResult;
    }

    public function getPermiso($cModulo, $cAccion)
    {
        $oPermiso = NULL;
        $aPermisos = (isset($_SESSION["_PERMISOS"])) ? $_SESSION["_PERMISOS"] : NULL;
        
        if ((count($aPermisos) > 0) && $aPermisos != null)
        {
            foreach ($aPermisos as $oAccion)
            {
                if (($oAccion->cNombreModulo == $cModulo) && ($oAccion->cNombreAccion == $cAccion))
                {
                    $oPermiso = $oAccion;
                    break;
                }
            }
        }
        return $oPermiso;
    }

    public function getAccionId($idModulo, $cNombreAccion)
    {
        $this->CI->load->model('Sistema/Accion_model');
        $this->CI->load->library('ViewModels/Accion_ViewModel');

        $oAccion = new Accion_ViewModel();
        $oAccion->idModulo = $idModulo;
        $oAccion->cNombre = $cNombreAccion;

        $dbAccion = $this->CI->Accion_model->get($oAccion);
        return (is_object($dbAccion)) ? $dbAccion->idAccion : NULL;
    }

    public function getModuloId($cNombre)
    {
        $this->CI->load->model('Sistema/Modulo_model');
        $this->CI->load->library('ViewModels/Modulo_ViewModel');

        $oModulo = new Modulo_ViewModel();
        $oModulo->cNombre = $cNombre;
        $dbModulo = $this->CI->Modulo_model->get($oModulo);
        return (is_object($dbModulo)) ? $dbModulo->idModulo : NULL;
    }

    public function encriptar($cValue)
    {
        return sha1($cValue . $this->CI->config->item('encryption_key'));
    }

    public function setExeption($cModulo, $cAccion, $ex, $bShowPageError = true)
    {
        $msgDBError = false;
        if ($this->CI->db)
        {
            $msgDBError = $this->CI->db->_error_message();
            $lastquery = $this->CI->db->last_query();
            $numDBError = $this->CI->db->_error_number();
        }

        $message = $ex->getMessage();
        $this->setBitacora($cModulo, $cAccion, $message);

        $detalles = '';
        if ($msgDBError)
        {
            $detalles .= lang('general.errorbd') . '<br/>';
            if ($numDBError)
            {
                $detalles .= lang('general.numerror') . ': ' . $numDBError . '<br/>';
            }
            $detalles .= str_replace(PHP_EOL, '<br/>', $lastquery) . '<br/><br/>';
        }
        $detalles .= $ex->__toString();

        if ($bShowPageError)
        {
            $error = & load_class('Exceptions', 'core');
            echo $error->show_error(lang('general_error_encontrado'), $message, 'error_db', 500, $detalles);
            exit();
        }
    }

    public function setBitacora($cModulo, $cAccion, $cDescripcion, $aRemplasar = array())
    {
        $this->CI->load->model("Sistema/Bitacora_model");
        $this->CI->load->library("ViewModels/Bitacora_ViewModel");

        $oBitacora = new Bitacora_ViewModel();
        $oBitacora->cModulo = $cModulo;
        $oBitacora->cAccion = $cAccion;
        $oBitacora->idModulo = $this->getModuloId($cModulo);
        $oBitacora->idAccion = $this->getAccionId($oBitacora->idModulo, $cAccion);
        $oBitacora->idUsuario = UsuarioHelper::get('idUsuario');
        $oBitacora->cNombreUsuario = UsuarioHelper::get('cNombreCompleto');
        $oBitacora->dtFecha = date("Y-m-d H:i:s");

        if (count($aRemplasar) > 0)
        {
            $oBitacora->cDescripcion = stringRemplace($cDescripcion, $aRemplasar);
        }
        else
        {
            $oBitacora->cDescripcion = $cDescripcion;
        }
        $this->CI->Bitacora_model->insertar($oBitacora);
    }

    public function generarContrasena()
    {
        $cContrasena = "";
        $iLongitudContrasena = ConfigHelper::get("iMaxContrasena");
        $cCadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
        $iLongitudCadena = strlen($cCadena);

        for ($i = 1; $i <= $iLongitudContrasena; $i++)
        {
            $pos = rand(0, $iLongitudCadena - 1);
            $cContrasena .= substr($cCadena, $pos, 1);
        }

        return $cContrasena;
    }
    
    public function generarKey()
    {
        $cContrasena = "";
        $iLongitudContrasena =20;
        $cCadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
        $iLongitudCadena = strlen($cCadena);

        for ($i = 1; $i <= $iLongitudContrasena; $i++)
        {
            $pos = rand(0, $iLongitudCadena - 1);
            $cContrasena .= substr($cCadena, $pos, 1);
        }

        return $cContrasena;
    }

    public function startTransaction()
    {
        $this->CI->db->trans_begin();
        $this->_trans_depth++;
    }

    public function commitTransaction()
    {
        if ($this->CI->db->trans_status() == false)
        {
            throw new CustomException('', $this->CI->db->_error_message());
        }
        else
        {
            $this->CI->db->trans_commit();
        }
        $this->_trans_depth--;
    }

    public function rollbackTransaction()
    {
        if ($this->_trans_depth > 0)
        {
            $this->CI->db->trans_rollback();
            $this->CI->db->trans_off(); // agregado si no jala se quita
            $this->_trans_depth--;
        }
    }

    public function iniciarSesion($oUsuario)
    {
        $this->CI->load->model("Sistema/Usuario_model");
        $this->CI->load->library("PeriodosNomina", array(), "PeriodosNomina");
        $this->CI->load->library("Nominas", array(), "Nominas");
        
        UsuarioHelper::set($oUsuario);
        UsuarioHelper::setAnoTools($this->CI->PeriodosNomina->getPrimerAnoCombo());
        UsuarioHelper::setTipoNominaTools($this->CI->Nominas->getPrimerTipoNominaCombo());
        IdiomaHelper::set($oUsuario->idIdioma);
        
        $_SESSION["_PERMISOS"] = $this->CI->Usuario_model->getPermisos($oUsuario->idRol);
        $_SESSION["_MENU"] = $this->getMenu(MENU_PRINCIPAL);
        $_SESSION["_MENU_SECCION_USUARIO"] = $this->getMenu(MENU_SECCION_USUARIO);
    }
    
    
    public function getMenu($idMenu)
    {
        $this->CI->load->model("Sistema/Menu_model");
        $aMenu = $this->CI->Menu_model->getMenu(UsuarioHelper::get("idRol"), $idMenu);
        return $aMenu;
    }
    
    // </editor-fold>
}
