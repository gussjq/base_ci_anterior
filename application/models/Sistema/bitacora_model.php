<?php

class Bitacora_model extends MY_Model {
   
   public $idBitacora;
   public $idUsuario;
   public $idModulo;
   public $idAccion;
   public $cNombreUsuario;
   public $cModulo;
   public $cAccion;
   public $dtFecha;
   
   public function __construct() {
      parent::__construct();
      $this->loadTable("tcbitacora");
   }
   
   public function getAll($oBitacora, $bArray = false){
       
       $this->db->select("b.idBitacora, b.idUsuario, b.idModulo, b.idAccion, b.cNombreUsuario, b.cModulo, b.cAccion, b.cDescripcion, b.dtFecha", false);
       $this->db->from("tcbitacora AS b");
       
       if($oBitacora->idUsuario){
           $this->db->where("idUsuario", $oBitacora->idUsuario);
       }
       
       if($oBitacora->idModulo){
           $this->db->where("idModulo", $oBitacora->idModulo);
       }
       
       if($oBitacora->idAccion){
           $this->db->where("idAccion", $oBitacora->idAccion);
       }
       
       if($oBitacora->cNombreUsuario){
           $this->db->like("cNombreUsuario", $oBitacora->cNombreUsuario);
       }
       
       if($oBitacora->cModulo){
           $this->db->like("cModulo", $oBitacora->cModulo);
       }
       
       if($oBitacora->cAccion){
           $this->db->like("cAccion", $oBitacora->cAccion);
       }
       
       if($oBitacora->dtFechaInicio){
           $this->db->where("dtFecha >=", $oBitacora->dtFechaInicio);
       }
       
       if($oBitacora->dtFechaFin){
           $this->db->where("dtFecha <= ADDDATE('{$oBitacora->dtFechaFin}', 1);");
       }
       
       $query = $this->db->get();
       $aData = array();
       
       if(!$this->db->_error_message()){
           if($query->num_rows() > 0){
               $aData = ($bArray) ? $query->result_array() : $query->result();
           }
       }
       
       return $aData;
   }
   
   public function get($oBitacora, $bArray = FALSE) {
        $aData = $this->getAll($oBitacora, $bArray);
        if(is_numeric($aData)){
            return $aData;
        }
        return (count($aData) > 0) ? $aData[0] : array();
    }
   
   public function insertar($oBitacora){
       $id = $this->save($oBitacora);
       return $id;
   }
}
