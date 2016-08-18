<?php

/**
 * getDocumentRoot
 * 
 * Funcion que se encarga de recuperar la carpeta del proyecto
 */
if (!function_exists("getDocumentRoot"))
{

    function getDocumentRoot()
    {
        return str_replace("\\", "/", getcwd() . "/");
    }

}

if (!function_exists("getRutaImagen"))
{

    function getRutaImagen($cRuta)
    {
        $cPathImagen = "";
        $cPath = getDocumentRoot() . $cRuta;
        if (@file_exists($cPath))
        {
            $cPathImagen = base_url() . $cRuta;
        }
        else
        {
            $cPathImagen = getRutaImagenDefault();
        }
        return $cPathImagen;
    }

}

if (!function_exists("getRutaImagenDefault"))
{

    function getRutaImagenDefault()
    {

        return base_url() . DIRECTORIO_IMAGENES_ICON . ICON_IMAGEN_NO_ENCONTRADA;
    }

}

if (!function_exists("showMessages"))
{

    function showMessages()
    {
        $CI = &get_instance();
        if ($CI->session->flashdata('_MESSAGES'))
        {
            echo $CI->session->flashdata('_MESSAGES');
        }
        else
        {
            echo "\"null\"";
        }
    }

}

if (!function_exists("getController"))
{

    function getController()
    {
        $CI = &get_instance();
        return $CI->uri->segment(1);
    }

}

if (!function_exists("getAccion"))
{

    function getAccion()
    {
        $CI = &get_instance();
        return $CI->uri->segment(2);
    }

}

if (!function_exists("getModulo"))
{

    function getModulo($cItem = "")
    {
        $cValor = "";
        $CI = &get_instance();
        $oModulo = ($CI->session->userdata("_MODULO")) ? $CI->session->userdata("_MODULO") : NULL;
        if (!empty($cItem))
        {
            if (isset($oModulo->$cItem))
            {
                $cValor = $oModulo->$cItem;
            }
            return $cValor;
        }
        return $oModulo;
    }

}


if (!function_exists("getComboForma"))
{

    function getComboForma($key, $valor, $aCombo, $bArray = FALSE, $bAgregarDefault = TRUE)
    {
        $aReturn = array();
        foreach ($aCombo as $value)
        {
            if ($bArray)
            {
                if(is_array($valor))
                {
                    $cMostrar = "";
                    $iConta = count($valor);
                    for ($i = 0; $i < $iConta; $i++)
                    {
                        if (isset($value[$key]) && isset($value[$valor[$i]]))
                        {
                            $cMostrar .= $value[$valor[$i]];
                            
                            if($i < $iConta - 1)
                            {
                                $cMostrar .= " - ";
                            }
                        }
                    }
                    
                    $aReturn[$value[$key]] = $cMostrar;
                }
                else
                {
                    if (isset($value[$key]) && isset($value[$valor]))
                    {
                        $aReturn[$value[$key]] = $value[$valor];
                    }
                }
            }
            else
            {
                if(is_array($valor))
                {
                    $cMostrar = "";
                    $iConta = count($valor);
                    for ($i = 0; $i < $iConta; $i++)
                    {
                        if (isset($value->$key) && isset($value->$valor[$i]))
                        {
                            $cMostrar .= $value->$valor[$i];
                            
                            if($i < $iConta -1)
                            {
                                $cMostrar .= " - ";
                            }
                        }
                    }
                    
                    $aReturn[$value->$key] = $cMostrar;
                }
                else
                {
                    if (isset($value->$key) && isset($value->$valor))
                    {
                        $aReturn[$value->$key] = $value->$valor;
                    }
                }
            }
        }
        
        if($bAgregarDefault)
        {
            $aReturn = array("" => lang("general_seleccionar")) + $aReturn;
        }
        
        return $aReturn;
    }

}

if (!function_exists("getComboMultiSelect"))
{

    function getComboMultiSelect($key, $valor, $aCombo, $bArray = FALSE)
    {
        $aOptions = array();
        foreach ($aCombo as $value)
        {
            if ($bArray)
            {
                if (isset($value[$key]) && isset($value[$valor]))
                {
                    $aOptions[] = array("ID" => $value[$key], "Text" => $value[$valor]);
                }
            }
            else
            {
                if (isset($value->$key) && isset($value->$valor))
                {
                    $aOptions[] = array("ID" => $value->$key, "Text" => $value->$valor);
                }
            }
        }

        return $aOptions;
    }

}

if (!function_exists("getArrayCombo"))
{

    function getComboArray($aCombo)
    {
        $aReturn = array();
        $aReturn = array("" => lang("general_seleccionar")) + $aCombo;
        return $aReturn;
    }

}

if (!function_exists('stringRemplace'))
{

    function stringRemplace($cString = "", $aParametros = array())
    {
        $l = count($aParametros);
        $replaceText = array();
        for ($i = 0; $i < $l; $i++)
        {
            $replaceText[] = '{' . $i . '}';
        }
        $cString = str_replace($replaceText, $aParametros, $cString);

        $cString = vsprintf($cString, $aParametros);
        return $cString;
    }

}


if (!function_exists("hexToDecColor"))
{

    function hexToDecColor($color)
    {
        $result = str_split($color, 2);
        foreach ($result as $key => $col)
        {
            $result[$key] = hexdec($col);
        }
        return $result;
    }

}

if (!function_exists("isEnviromentDevelopment"))
{

    function isEnviromentDevelopment()
    {
        return ((ENVIRONMENT === "development") || (ENVIRONMENT === "testing"));
    }

}

if (!function_exists("getHorasDiferencia"))
{

    function getHorasDiferencia($inicio, $fin)
    {
        return date("H:i:s", (strtotime("00:00:00") + strtotime($fin) - strtotime($inicio)));
    }
    
}

if(!function_exists("getFecha"))
{
    function getFecha($cAccion, $cFormat, $iAno, $dtAno)
    {
        return  date($cFormat, strtotime("{$cAccion} {$iAno} year" , strtotime($dtAno)));
    }
}

if(!function_exists("getBimestre"))
{
    function getBimestre($dtFecha)
    {
        $mes = date("n",strtotime($dtFecha));
        $iBimestre = ceil($mes / 2);
        return $iBimestre;
    }
}

if(!function_exists("getDiasBimestre"))
{
    function getDiasBimestre($dtFecha)
    {
        $iBimestre = getBimestre($dtFecha);
        $aDiasBimestre = array(59, 61, 61,62,61,61);
        return (isset($aDiasBimestre[$iBimestre - 1])) ? $aDiasBimestre[$iBimestre - 1] : NULL;
    }
}


if(!function_exists("getDiasDiferencia"))
{
    function getDiasDiferencia($dtFechaInicial, $dtFechaFinal, $bSumarUno = FALSE)
    {
        $datetime1 = new DateTime((string) $dtFechaInicial);
        $datetime2 = new DateTime((string) $dtFechaFinal);
        $interval = $datetime1->diff($datetime2);
        
        $iDias = $interval->days;
        
        // en algunos casos fue es necesario sumar uno
        if($bSumarUno){
            $iDias = $iDias + 1;
        }
        
        return $iDias;
    }
}


if(!function_exists("getSumarDias")){
    
    function getSumarDias($dtFecha, $iDias)
    {   
        $fecha = date_create((string) $dtFecha);
        date_add($fecha, date_interval_create_from_date_string($iDias . 'days'));
        return date_format($fecha, 'Y-m-d');
    }
}


/**
 * Metodo que se encarga de recuperar el primer dia del bimestre en curso, 
 * por ejemplo si se pasa como parametro 22-01-2015 la funcion devolvera 01-02-2015, es utilizado en 
 * credito infonavit para setear el valor dtFecha alta cuando se registra un nuevo credito
 */
if(!function_exists("getFechaPrimerDiaBimestre"))
{
   function getFechaPrimerDiaBimestre($dtFecha)
   {
       $iBimestre = (int) getBimestre($dtFecha);
       $dtFechaPrimerDiaBimestre = null;
       
       for($i = 1; $i<=12;$i++)
       {
           $dtFechaCiclo = date("Y") ."-". ((strlen($i) == 1) ? "0" . $i : $i) ."-". "01";
           $iBimestreCiclo = (int) getBimestre($dtFechaCiclo);
           
           if($iBimestre === $iBimestreCiclo)
           {
               $dtFechaPrimerDiaBimestre = $dtFechaCiclo;
               break;
           }
       }      
       
       return $dtFechaPrimerDiaBimestre;
   } 
}

/**
 * Metodo que se encarga de recuperar los años meses y dias de diferencia preformateada
 */
if(!function_exists("getAntiguedad"))
{
    function getAntiguedad($dtFechaInicial, $dtFechaFinal, $cFormat)
    {
        $datetime1 = new DateTime((string) $dtFechaInicial);
        $datetime2 = new DateTime((string) $dtFechaFinal);
        $interval = $datetime1->diff($datetime2);                

        if ($cFormat == "s")
        {
            $cAntiguedad = "";

            // años
            if ($interval->y > 0)
            {
                if ($interval->y > 1)
                {
                    $cAntiguedad .= stringRemplace(lang("general_fecha_anos"), array($interval->y)) . " ";
                }
                else
                {
                    $cAntiguedad .= stringRemplace(lang("general_fecha_ano"), array($interval->y)) . " ";
                }
            }

            // meses
            if ($interval->m > 0)
            {
                if ($interval->m > 1)
                {
                    $cAntiguedad .= stringRemplace(lang("general_fecha_meses"), array($interval->m)) . " ";
                }
                else
                {
                    $cAntiguedad .= stringRemplace(lang("general_fecha_mes"), array($interval->m)) . " ";
                }
            }

            // dias
            if ($interval->d > 0)
            {
                if ($interval->d > 1)
                {
                    $cAntiguedad .= stringRemplace(lang("general_fecha_dias"), array($interval->d)) . " ";
                }
                else
                {
                    $cAntiguedad .= stringRemplace(lang("general_fecha_dia"), array($interval->d)) . " ";
                }
            }
            
            return $cAntiguedad;
        }

        // recupera los años que han pasado en dos fechas en valor numerico
        if($cFormat == "i")
        {     
            $iAnosAntiguedad = $interval->y + (($interval->m + ($interval->d /30.4)) / 12);   
            return round($iAnosAntiguedad, 4);
        }
        
        return NULL;
    }

}

/**
 * Funcion que se encarga de recuperar la fecha al aniversario, es utilizado en vacaciones para calcular el saldo 
 * de vacaciones  a aniversario
 */
if(!function_exists("getFechaAniversario"))
{
    function getFechaAniversario($dtFechaAntiguedad)
    {
        $iAnoActual = date("Y");
        $iAnoAntiguedad = "";
        $iMesAntiguedad = "";
        $iDiaAntiguedad = "";
        
        list($iAnoAntiguedad, $iMesAntiguedad, $iDiaAntiguedad) = explode("-", $dtFechaAntiguedad);
        
        $dtFechaAntiguedadActual = $iAnoActual . "-" . $iMesAntiguedad . "-" . $iDiaAntiguedad;
        
        $iFechaActualTime = strtotime('now');
        $iFechaAntiguedadTime = strtotime($dtFechaAntiguedadActual);
        
        if($iFechaActualTime >= $iFechaAntiguedadTime)
        {
            return $dtFechaAntiguedadActual;
        }
        else
        {
            $dtFechaAntiguedadActual = ($iAnoActual - 1) . "-" . $iMesAntiguedad . "-" . $iDiaAntiguedad;
            return $dtFechaAntiguedadActual;
        }
    }
}

if(!function_exists("getDiaAjusteMes"))
{
    function getDiaAjusteMes($dtFecha, $iDiaAjuste, $idSemana)
    {
        $iDiaEncontrado = 0;
        $dtFechaAjuste = "";
        $iDiasMes = date("t", strtotime($dtFecha));
        list($iAno,$iMes,$iDia) = explode('-',$dtFecha);
        
        for($i=1;$i<$iDiasMes;$i++)
        {
            if(date("w", mktime(0,0,0,$iMes,$i,$iAno)) == getSemanaW($idSemana))
            {
                $iDiaEncontrado++;
            }
            
            if($iDiaEncontrado == $iDiaAjuste)
            {
                $dtFechaAjuste = date("Y-m-d", mktime(0,0,0,$iMes,$i,$iAno));
                break;
            }
        }
        
        return $dtFechaAjuste;
    }
}

function getSemanaW($idSemana){
    if($idSemana == 7){
        return 0;
    }
    return $idSemana;
}

