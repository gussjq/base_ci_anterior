<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Message {

    public $TIPO_MENSAJE_ERROR = 1;
    public $TIPO_MENSAJE_NOTICIA = 2;
    public $TIPO_MENSAJE_EXITO = 3;
    public $TIPO_MENSAJE_PRECAUCION = 4;
    private $arrMsg = array();
    private $typeMsg = array();

    public function clearMessages()
    {
        $this->arrMsg = array();
        $this->typeMsg = array();
    }

    public function addError($msg, $bFirst = false)
    {
        if (!$this->_vacio($msg))
        {
            $msg = preg_replace('/(<\/?)[Pp](>)/', '', $msg);
            $msg = $this->error($msg);
            if ($bFirst)
            {
                array_unshift($this->arrMsg, $msg);
            }
            else
            {
                $this->arrMsg[] = $msg;
            }
            $this->typeMsg[] = $this->TIPO_MENSAJE_ERROR;
        }
    }

    public function addErrors($aMessages)
    {
        if (!empty($aMessages))
        {
            foreach ($aMessages as $sMessage)
            {
                $this->addError($sMessage);
            }
        }
    }

    public function addNoticia($msg)
    {
        if (!$this->_vacio($msg))
        {
            $msg = $this->noticia($msg);
            $this->arrMsg[] = $msg;
            $this->typeMsg[] = $this->TIPO_MENSAJE_NOTICIA;
        }
    }

    public function addExito($msg)
    {
        if (!$this->_vacio($msg))
        {
            $msg = $this->exito($msg);
            $this->arrMsg[] = $msg;
            $this->typeMsg[] = $this->TIPO_MENSAJE_EXITO;
        }
    }

    public function addPrecaucion($msg)
    {
        if (!$this->_vacio($msg))
        {
            $msg = $this->precaucion($msg);
            $this->arrMsg[] = $msg;
            $this->typeMsg[] = $this->TIPO_MENSAJE_PRECAUCION;
        }
    }

    function toHtml()
    {
        $html = ''; //'<table border=\'0\'>';

        foreach ($this->arrMsg as $msg)
        {
            //$html = $html . '<tr><td>' . $msg . '</td></tr>';
            $html = $html . $msg;
        }
        //$html = $html . '</table>' ;

        return $html;
    }

    function toString($separador = '<br/>')
    {
        $text = '';

        foreach ($this->arrMsg as $msg)
        {
            $text .= preg_replace('/(<\/?)[^>]+(>)/', '', $msg) . $separador;
        }

        return $text;
    }

    function toJsonObject($bOnlyText = true)
    {
        $lTipos = array();
        $lMensajes = array();
        foreach ($this->arrMsg as $index => $msg)
        {
            if ($bOnlyText)
            {
                $msg = preg_replace('/(<\/?)[^>]+(>)/', '', $msg);
            }

            $sTipo = NULL;
            switch ($this->typeMsg[$index])
            {
                case $this->TIPO_MENSAJE_ERROR:
                    $sTipo = "error_mensaje";
                    break;
                case $this->TIPO_MENSAJE_NOTICIA:
                    $sTipo = "noticia_mensaje";
                    break;
                case $this->TIPO_MENSAJE_EXITO:
                    $sTipo = "exito_mensaje";
                    break;
            }
            $pos = array_search($sTipo, $lTipos);
            if ($pos === false)
            {
                $lTipos[] = $sTipo;
                $lMensajes[] = array($msg);
            }
            else
            {
                $lMensajes[$pos][] = $msg;
            }
        }

        return array("lTipos" => $lTipos, "lMensajes" => $lMensajes);
    }

    /**
     * Visualiza un mensaje flash
     *
     * @param string $name	Para tipo de mensaje y para CSS class='$nombre'.
     * @param string $msj 	Mensaje a mostrar
     */
    public function mostrar($nombre, $msj)
    {
        return '<div class="' . $nombre . '">' . $msj . ' <span class="cerrar_mensaje ui-icon ui-icon-closethick"></span></div>' . "\n";
    }

    /**
     * Visualiza un mensaje de error
     *
     * @param string $err
     */
    public function error($err)
    {
        return $this->mostrar('error_mensaje', $err);
    }

    /**
     * Visualiza informacion en pantalla
     *
     * @param string $msj
     */
    public function noticia($msj)
    {
        return $this->mostrar('noticia_mensaje', $msj);
    }

    /**
     * Visualiza informacion de Suceso en pantalla
     *
     * @param string $msj
     */
    public function exito($msj)
    {
        return $this->mostrar('exito_mensaje', $msj);
    }

    /**
     * Visualiza un mensaje de advertencia en pantalla
     *
     * @param string $msj
     */
    public function precaucion($msj)
    {
        return $this->mostrar('precaucion_mensaje', $msj);
    }

    private function _vacio($msg)
    {
        if (!isset($msg))
        {
            return true;
        }
        if ($msg == false)
        {
            return true;
        }
        if (trim($msg) == '')
        {
            return true;
        }
        return false;
    }

}
