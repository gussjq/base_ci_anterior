<?php

/**
 * Description of test
 *
 * @author GussJQ
 */
class Test extends MY_Controller {
    
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        try {
           
            $this->layout->view($this->cModulo . "/index_view", array());
            
        } catch (Exception $ex) {
         
        }
    }
}
