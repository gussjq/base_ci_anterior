<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * incluye_componente_uploadify
 *
 * Metodo que se encarga de incluir las hojas de estilo y el javascript para el componente uploadify
 *
 * @access	public
 * @return	void
 */
if (!function_exists('incluye_componente_uploadify'))
{

    function incluye_componente_uploadify()
    {
        link_tag("plugins/uploadify/uploadify");
        script_tag("plugins/uploadify/swfobject");
        script_tag("plugins/uploadify/jquery.uploadify");
    }

}

/**
 * incluye_componente_uploadify
 *
 * Metodo que se encarga de incluir las hojas de estilo y el javascript para el componente uploadify
 *
 * @access	public
 * @return	void
 */
if (!function_exists('incluye_componente_fileupload'))
{

    function incluye_componente_fileupload()
    {
        link_tag("plugins/fileupload/jquery.fileupload");
        script_tag("plugins/fileupload/jquery.iframe-transport");
        script_tag("plugins/fileupload/jquery.fileupload");
    }

}

/**
 * incluye_componente_uploadify
 *
 * Metodo que se encarga de incluir las hojas de estilo y el javascript para el componente validationEngine
 *
 * @access	public
 * @return	void
 */
if (!function_exists('incluye_componente_validationengine'))
{

    function incluye_componente_validationengine()
    {
        link_tag("plugins/validationEngine/validationEngine.jquery");
        script_tag("plugins/validationEngine/jquery.validationEngine-es");
        script_tag("plugins/validationEngine/jquery.validationEngine");
    }

}

/**
 * incluye_componente_uploadify
 *
 * Metodo que se encarga de incluir las hojas de estilo y el javascript para el componente validationEngine
 *
 * @access	public
 * @return	void
 */
if (!function_exists('incluye_componente_validate'))
{

    function incluye_componente_validate()
    {
        script_tag("plugins/validate/jquery.validate");
        script_tag("plugins/validate/additional-methods");
        script_tag("plugins/validate/localization/messages_es");
    }

}

/**
 * incluye_componente_uploadify
 *
 * Metodo que se encarga de incluir las hojas de estilo y el javascript para el componente msgbox
 *
 * @access	public
 * @return	void
 */
if (!function_exists('incluye_componente_msgbox'))
{

    function incluye_componente_msgbox()
    {
        link_tag("plugins/msgBox/jquery.msgbox");
        script_tag("plugins/msgBox/jquery.msgbox.min");
    }

}

/**
 * incluye_componente_growl
 *
 * Metodo que se encarga de incluir las hojas de estilo y el javascript para el componente growl
 *
 * @access	public
 * @return	void
 */
if (!function_exists('incluye_componente_growl'))
{

    function incluye_componente_growl()
    {
        link_tag("plugins/msgGrowl/jquery.msgGrowl");
        script_tag("plugins/msgGrowl/jquery.msgGrowl");
    }

}

/**
 * incluye_componente_datatables
 *
 * Metodo que se encarga de incluir las hojas de estilo y el javascript para el componente datatables
 *
 * @access	public
 * @return	void
 */
if (!function_exists('incluye_componente_datatables'))
{

    function incluye_componente_datatables()
    {
//        link_tag("plugins/datatables/demo_table_jui");
//        link_tag("plugins/datatables/TableTools");
        script_tag("plugins/Datatables/js/jquery.dataTables");
    }

}

/**
 * incluye_componente_treetable
 *
 * Metodo que se encarga de incluir las hojas de estilo y el javascript para el componente treetable
 *
 * @access	public
 * @return	void
 */
if (!function_exists('incluye_componente_treetable'))
{

    function incluye_componente_treetable()
    {
        link_tag("plugins/tbltree/jquery.tbltree");
        script_tag("plugins/tbltree/jquery.tbltree");
    }

}

/**
 * incluye_componente_datatables
 *
 * Metodo que se encarga de incluir las hojas de estilo y el javascript para el componente datatables
 *
 * @access	public
 * @return	void
 */
if (!function_exists('incluye_componente_tabletools'))
{

    function incluye_componente_tabletools()
    {
//      script_tag('plugins/Datatables/extras/TableTools/media/ZeroClipboard/ZeroClipboard');
//        script_tag('plugins/Datatables/extras/TableTools/media/js/ZeroClipboard');
//        script_tag('plugins/Datatables/extras/TableTools/media/js/TableTools');
    }

}

/**
 * incluye_componente_time
 *
 * Metodo que se encarga de incluir las hojas de estilo y el javascript para el componente datatables
 *
 * @access	public
 * @return	void
 */
if (!function_exists('incluye_componente_time'))
{

    function incluye_componente_time()
    {
        link_tag('plugins/time/jquery.timeentry');
        script_tag('plugins/time/jquery.plugin.min');
        script_tag('plugins/time/jquery.timeentry');
    }

}

/**
 * link_tag
 *
 * Genera link tag para cargar las hojas de estilo
 *
 * @access	public
 * @param	mixed	stylesheet hrefs or an array
 * @param       boolean bPrint para intificar si se imprime la etiqueta o si se retorna como string
 * @param	string	rel
 * @param	string	type
 * @param	string	title
 * @param	string	media
 * @param	boolean	should index_page be added to the css path
 * @return	string
 */
if (!function_exists('link_tag'))
{

    function link_tag($href = '', $bPrint = true, $rel = 'stylesheet', $type = 'text/css', $title = '', $media = '', $index_page = FALSE)
    {

        $CI = & get_instance();
        $link = '<link ';
        if (is_array($href))
        {
            foreach ($href as $k => $v)
            {
                if ($k == 'href' AND strpos($v, '://') === FALSE)
                {
                    if ($index_page === TRUE)
                    {
                        $link .= 'href="' . $CI->config->site_url(DIRECTORIO_HOJAS_ESTILO . $v) . '.css" ';
                    }
                    else
                    {
                        $link .= 'href="' . $CI->config->slash_item('base_url') . DIRECTORIO_HOJAS_ESTILO . $v . '.css" ';
                    }
                }
                else
                {
                    $link .= "$k=\"$v\" ";
                }
            }
            $link .= "/>";
        }
        else
        {
            if (strpos($href, '://') !== FALSE)
            {
                $link .= 'href="' . $href . '" ';
            }
            elseif ($index_page === TRUE)
            {
                $link .= 'href="' . $CI->config->site_url(DIRECTORIO_HOJAS_ESTILO . $href) . '.css" ';
            }
            else
            {
                $link .= 'href="' . $CI->config->slash_item('base_url') . DIRECTORIO_HOJAS_ESTILO . $href . '.css" ';
            }

            $link .= 'rel="' . $rel . '" type="' . $type . '" ';
            if ($media != '')
            {
                $link .= 'media="' . $media . '" ';
            }

            if ($title != '')
            {
                $link .= 'title="' . $title . '" ';
            }
            $link .= '/>';
        }
        if ($bPrint)
        {
            echo $link . "\n";
        }
        else
        {
            return $link . "\n";
        }
    }

}

/**
 * script_tag
 *
 * Genera script tag para cargar las hojas de estilo
 *
 * @access	public
 * @param	mixed  src or an array
 * @param       boolean bPrint para intificar si se imprime la etiqueta o si se retorna como string
 * @param	string	type
 * @param	boolean	should index_page be added to the css path
 * @return	string
 */
if (!function_exists('script_tag'))
{

    function script_tag($src = '', $bPrint = true, $type = 'text/javascript', $index_page = FALSE)
    {
        $CI = & get_instance();

        $link = "<script ";
        if (is_array($src))
        {
            foreach ($src as $k => $v)
            {
                if ($k == 'src' AND strpos($v, '://') === FALSE)
                {
                    if ($index_page === TRUE)
                    {
                        $link .= 'src="' . $CI->config->site_url(DIRECTORIO_JAVASCRIPT . $v) . '.js" ';
                    }
                    else
                    {
                        $link .= 'src="' . $CI->config->slash_item('base_url') . DIRECTORIO_JAVASCRIPT . $v . '.js" ';
                    }
                }
                else
                {
                    $link .= "$k=\"$v\" ";
                }
            }
            $link .= ">";
        }
        else
        {

            if (strpos($src, '://') !== FALSE)
            {
                $link .= 'src="' . $src . '" ';
            }
            elseif ($index_page === TRUE)
            {
                $link .= 'src="' . $CI->config->site_url(DIRECTORIO_JAVASCRIPT . $src) . '.js" ';
            }
            else
            {
                $link .= 'src="' . $CI->config->slash_item('base_url') . DIRECTORIO_JAVASCRIPT . $src . '.js" ';
            }

            $link .= 'type="' . $type . '" ';
            $link .= '>';
        }
        $link .= "</script>";

        if ($bPrint)
        {
            echo $link . "\n";
        }
        else
        {
            return $link . "\n";
        }
    }

}


/**
 * Image
 *
 * Generates an <img /> element
 *
 * @access	public
 * @param	mixed
 * @return	string
 */
if (!function_exists('img'))
{

    function img($src = '', $bPrint = true, $index_page = FALSE)
    {

        if (!is_array($src))
        {
            $src = array('src' => $src);
        }

        // If there is no alt attribute defined, set it to an empty string
        if (!isset($src['alt']))
        {
            $src['alt'] = '';
        }

        $img = '<img';

        foreach ($src as $k => $v)
        {
            if ($k == 'src' AND strpos($v, '://') === FALSE)
            {
                $CI = & get_instance();
                if ($index_page === TRUE)
                {
                    $img .= ' src="' . $CI->config->site_url($v) . '"';
                }
                else
                {
                    $cRutaImagen = getDocumentRoot() . $v;
                    if (@file_exists($cRutaImagen))
                    {
                        $img .= ' src="' . $CI->config->slash_item('base_url') . $v . '"';
                    }
                    else
                    {
                        $img .= ' src="' . $CI->config->slash_item('base_url') . DIRECTORIO_IMAGENES_ICON . ICON_IMAGEN_NO_ENCONTRADA . '"';
                    }
                }
            }
            else
            {
                $img .= " $k=\"$v\"";
            }
        }
        $img .= '/>';

        if ($bPrint)
        {
            echo $img . "\n";
        }
        else
        {
            return $img . "\n";
        }
    }

}

if (!function_exists('incluye_icono'))
{

    function incluye_icono($nombre = '', $alias = null, $estilo = "", $accion = "#", $extra = "", $print = TRUE, $accionNombre = null, $moduloNombre = null)
    {
        $CI = &get_instance();
        $icon = '';
        switch (strtolower($nombre))
        {
            case 'forma':
            case 'editar':
                $icon = 'pencil';
                break;
            case 'insertar':
            case 'actualizar':
                $icon = 'disk';
                break;
            case 'eliminar':
                $icon = 'trash';
                break;
            case 'listado':
                $icon = 'clipboard';
                break;
            case 'denegar':
                $icon = 'locked';
                break;
            case 'desbloquear':
                $icon = 'unlocked';
                break;
            case 'buscar':
                $icon = 'search';
                break;
            case 'agregar':
                $icon = 'plus';
                break;
            case 'ordenar':
                $icon = 'carat-2-n-s';
                break;
            case 'deshabilitar':
                $icon = 'closethick';
                break;
            case 'habilitar':
                $icon = 'check';
                break;
            case 'descargar' :
                $icon = 'circle-arrow-s';
                break;
            case 'refrescar':
                $icon = 'arrowrefresh-1-s';
                break;
            case 'archivos':
                $icon = 'folder-collapsed';
                break;
            case 'bitacora':
            case 'tramite':
                $icon = 'note';
                break;
            case 'correo':
                $icon = 'mail-closed';
                break;
            case 'candidatos':
                $icon = 'person';
                break;
            case 'imprimir':
                $icon = 'print';
                break;
            case 'copiar':
                $icon = 'newwin';
                break;
            case 'visualizar':
                $icon = 'document';
                break;
            case 'visualizarhabilidad':
                $icon = 'extlink';
                break;
            case 'seleccionar':
                $icon = 'circle-arrow-e';
                break;
            case 'configurar':
                $icon = 'gear';
                break;
            case 'reingreso':
                $icon = 'arrowreturnthick-1-w';
                break;
            case 'detalle':
                $icon = 'circle-plus';
                break;
            case 'apariencia':
                $icon = 'contact';
                break;
            case 'abrir':
                $icon = 'mail-open';
                break;
            case 'escalar':
                $icon = 'arrowreturnthick-1-n';
                break;
            case 'unidadnegocios':
                $icon = 'bookmark';
                break;
            case 'asociar':
                $icon = 'arrowthick-2-e-w';
                break;
            case 'alert':
                $icon = 'alert';
                break;
            case 'tag':
                $icon = 'tag';    
                break;
            case 'subir':
                $icon = 'triangle-1-n';    
                break;
            case 'bajar':
                $icon = 'triangle-1-s'; 
                break;
            case 'user':
                $icon = 'person';
                break;
            case 'importar':
                $icon = 'circle-arrow-n';
                break;
        }

        $ret = "";
        //$ret .= sprintf("<a style='%s' title='%s' class='fg-button ui-state-default fg-button-icon-solo ui-corner-all' href='%s'  %s ><span class='ui-icon ui-icon-%s'></span></a>", $estilo, ($alias) ? $alias : $nombre, $accion, $extra, $icon);
        $ret .= sprintf("<a style='%s' title='%s' data-original-title='%s' class='show-tooltip fg-button fg-button-icon-solo ui-corner-all' href='%s'  %s ><span class='ui-icon ui-icon-%s'></span></a>", $estilo, ($alias) ? $alias : $nombre, ($alias) ? $alias : $nombre, $accion, $extra, $icon);

        if (!$moduloNombre)
        {
            $moduloNombre = $CI->uri->segment(1);
        }

        if (!$accionNombre)
        {
            $accionNombre = $nombre;
        }


//        if (true) {
        if ($CI->seguridad->verificarAcceso($moduloNombre, $accionNombre))
        {
            if ($print)
            {
                echo $ret;
            }
            else
            {
                return $ret;
            }
        }
        return;
    }

}
