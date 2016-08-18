<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('lang'))
{
	function lang($line, $id = '')
	{
		$CI =& get_instance();
		$sValor = $CI->lang->line($line);
		$line = ($sValor) ? $sValor : $line;

		if ($id != '')
		{
			$line = '<label for="'.$id.'">'.$line."</label>";
		}

		return $line;
	}
}

// ------------------------------------------------------------------------
/* End of file MY_language_helper.php */
/* Location: ./application/helpers/MY_language_helper.php */