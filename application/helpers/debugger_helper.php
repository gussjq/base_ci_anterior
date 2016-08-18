<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'ChromePhp/ChromePhp.php';

if (!function_exists('debug')) {

    function debug($var, $bExit = false) {
        if (ENVIRONMENT == "development") {
            $CI = &get_instance();
            $cController = $CI->uri->segment(1) . " : ";
            ChromePhp::log($cController);
            ChromePhp::log($var);
            if ($bExit) {
                exit();
            }
        }
    }
}

if(!function_exists("dump")){
    
    function dump($var){
        if(ENVIRONMENT == "development"){
            var_dump($var);
            exit();
        }
        
    }
}
