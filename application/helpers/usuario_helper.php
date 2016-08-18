<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class UsuarioHelper {

    /**
     * Metodo que se encarga de devolver un valor especifico del usuario logeado o bien 
     * de retornar el objeto view model del usuario logeado
     * 
     * @param string $cItem
     * @return value|object
     */
    public static function get($cItem = "")
    {
        $CI = &get_instance();
        $oUsuario = (isset($_SESSION["_USUARIO"])) ? $_SESSION["_USUARIO"] : NULL;
        $cValor = null;
        if (!empty($cItem))
        {
            if (isset($oUsuario->$cItem))
            {
                $cValor = $oUsuario->$cItem;
            }
            return $cValor;
        }
        return $oUsuario;
    }

    /**
     * Metodo que se encatga de cargar la informacion del usuario logeado en una variable de sesion
     * para manter la informacion disponible del usaurio en todo el programa
     * 
     * @param type $usuario
     */
    public static function set($usuario)
    {
        if (is_object($usuario))
        {
            self::remove();
            $_SESSION["_USUARIO"] = $usuario;
        }

        if (is_numeric($usuario))
        {
            $CI = &get_instance();
            $CI->load->model("Sistema/Usuario_model");
            $CI->load->library("ViewModels/Usuario_ViewModel");

            $oUsuario = new Usuario_ViewModel();
            $oUsuario->idUsuario = $usuario;
            $dbUsuario = $CI->Usuario_model->get($oUsuario);

            self::remove();

            $_SESSION["_USUARIO"] = $dbUsuario;
        }
    }

    /**
     * Metodo que se encarga de destruir la variable de sesión con la informacion del usaurio
     * 
     */
    public static function remove()
    {
        if (isset($_SESSION["_USUARIO"]))
        {
            unset($_SESSION["_USUARIO"]);
        }
    }

    /**
     * Metodo que se encarga de recuperar todos los avisos que el usuario no ha visto, es utilizado en el dashboard
     * @return array Arreglo de avisos
     */
    public static function getAvisos()
    {
        $CI = &get_instance();
        $CI->load->model("Sistema/Aviso_model");
        $CI->load->library("ViewModels/Aviso_ViewModel");

        $oAviso = new Aviso_ViewModel();
        $oAviso->bLeido = NO;
        $oAviso->dtFechaCreacionInicio = date("Y-m-d H:i:s", strtotime("-7 day", strtotime(date("Y-m-d"))));
        $oAviso->dtFechaCreacionFin = date("Y-m-d H:i:s", strtotime("+23 hour", strtotime(date("Y-m-d"))));
        $aAvisos = $CI->Aviso_model->getAll($oAviso);

        return $aAvisos;
    }

    /**
     * Metodo que se encarga de recuperar cuantos avisos tiene el usuario pendientes por leer
     * @return int
     */
    public static function getNumeroAvisos()
    {
        $CI = &get_instance();
        $CI->load->model("Sistema/Aviso_model");
        $CI->load->library("ViewModels/Aviso_ViewModel");

        $oAviso = new Aviso_ViewModel();
        $oAviso->bLeido = NO;
        $oAviso->dtFechaCreacionInicio = date("Y-m-d H:i:s", strtotime("-7 day", strtotime(date("Y-m-d"))));
        $oAviso->dtFechaCreacionFin = date("Y-m-d H:i:s", strtotime("+23 hour", strtotime(date("Y-m-d"))));
        $oAviso->count = true;

        $iAvisos = $CI->Aviso_model->getAll($oAviso);

        return $iAvisos;
    }

    /**
     * Metodo que se encarga de recuperar los clientes que el usuario puede administrar en base a sus permisos
     * @return type
     */
    public static function getClientes()
    {
        $CI = &get_instance();
        $CI->load->model("Sistema/AsociarCliente_model");
        $CI->load->library("ViewModels/AsociarCliente_ViewModel");

        $oAsociarCliente = new AsociarCliente_ViewModel();
        $oAsociarCliente->idUsuario = self::get("idUsuario");

        $aClientes = $CI->AsociarCliente_model->getAll($oAsociarCliente);
        return $aClientes;
    }

    /**
     * 
     * @param type $idTipoNomina
     * @return boolean
     */
    public static function getTipoNomina($idTipoNomina = null, $idCliente = null)
    {
        $CI = &get_instance();
        $CI->load->model("contratacion/TipoNomina_model");
        $CI->load->library("ViewModels/TipoNomina_ViewModel");

        $oTipoNomina = new TipoNomina_ViewModel();
        if ($idTipoNomina)
        {
            $oTipoNomina->idTipoNomina = $idTipoNomina;
        }
        
        if($idCliente)
        {
            $oTipoNomina->idCliente = $idCliente;
        } 

        $oTipoNomina->bFiltrarClientes = true;
        $aTipoNomina = $CI->TipoNomina_model->getAll($oTipoNomina);

        return $aTipoNomina;
    }
    
    /**
     * 
     * @param type $idTipoNomina
     * @return boolean
     */
    public static function getTipoNominaConta($idTipoNomina = null, $idCliente = null)
    {
        $CI = &get_instance();
        $CI->load->model("contratacion/TipoNomina_model");
        $CI->load->library("ViewModels/TipoNomina_ViewModel");

        $oTipoNomina = new TipoNomina_ViewModel();
        if ($idTipoNomina)
        {
            $oTipoNomina->idTipoNomina = $idTipoNomina;
        }
        
        if($idCliente)
        {
            $oTipoNomina->idCliente = $idCliente;
        } 

        $oTipoNomina->bFiltrarClientes = true;
        $oTipoNomina->count = true;
        $aTipoNomina = $CI->TipoNomina_model->getAll($oTipoNomina);
        
        return $aTipoNomina;
    }
    
    public static function setAnoTools($iAno)
    {
        if (isset($_SESSION["_ANO"]))
        {
            unset($_SESSION["_ANO"]);
        }
        
        $_SESSION['_ANO'] = $iAno;
    }
    
    public static function setTipoNominaTools($idTipoNomina)
    {
        if (isset($_SESSION["_TIPO_NOMINA"]))
        {
            unset($_SESSION["_TIPO_NOMINA"]);
        }
        
        $_SESSION['_TIPO_NOMINA'] = $idTipoNomina;
    }
    
    /**
     * Metodo que recupera de la sesion el año por default con el que se mostrara el listado en la seccion de tools
     * @return int año del listado select de la secion tools
     */
    public static function getAnoTools()
    {
        if(isset($_SESSION['_ANO']))
        {
            return $_SESSION['_ANO'];
        }
        return NULL;
    }
    
    /**
     * Metodo que se encarga de recuperar el valor del tipo de nomina de la secion de tools
     * @return int identificador del tipo de nomina
     */
    public static function getTipoNominaTools()
    {
        if(isset($_SESSION['_TIPO_NOMINA']))
        {
            return $_SESSION['_TIPO_NOMINA'];
        }
        return NULL;
    }

}
