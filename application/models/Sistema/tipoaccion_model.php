<?php

class TipoAccion_model extends MY_Model {
   
   public $idTipoAccion;
   public $cNombre;
   public $cAlias;
   public $cDescripcion;
   public $bHabilitado;
   
   public function __construct() {
      parent::__construct();
      $this->loadTable("tdtipoaccion");
   }
}
