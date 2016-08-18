<?php



class Error extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    // <editor-fold defaultstate="collapsed" desc="Metodos Catalogo">

    /**
     * Pantalla del listado de Area
     * 
     * @access public
     * @return void 
     */
    public function index()
    {
        $this->layout->view('error/error_general_view', array("message" => "Mensaje general"));   
    }

  

    // </editor-fold>

}
