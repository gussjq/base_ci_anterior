<?php

/**
 * set_last_url
 *
 * Returns the full URL (including segments) of the page where this
 * function is placed
 *
 * @access	public
 * @return	string
 */
if (!function_exists('set_last_url'))
{

    function set_last_url($cUrl)
    {
        $this->session->set_userdata("_LAST_URL", $cUrl);
    }

}

/**
 * get_last_url
 *
 * Returns the full URL (including segments) of the page where this
 * function is placed
 *
 * @access	public
 * @return	string
 */
if (!function_exists('get_last_url'))
{

    function get_last_url($cUrl)
    {
        return $this->session->userdata("_LAST_URL", $cUrl);
    }

}

/**
 * get_last_url
 *
 * Returns the full URL (including segments) of the page where this
 * function is placed
 *
 * @access	public
 * @return	string
 */
if (!function_exists('migaja_pan'))
{

    function migaja_pan($migaja)
    {
        $cItem = "";
        $iConta = 0;
        $iTope = count($migaja);

        if (is_array($migaja))
        {
            foreach ($migaja AS $value)
            {
                $cItem .= ($iConta === 0) ? ": " : " > ";
                $cItem .= $value;
                $iConta++;
            }
            
            echo $cItem;
        }
        
        if((is_string($migaja)) && (!empty($migaja)))
        {
            echo $migaja;
        }
    }

}


