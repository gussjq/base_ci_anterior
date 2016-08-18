<?php
require_once('Reportes_ViewModel.php');
class Bitacora_ViewModel extends Reportes_ViewModel {
   public $idBitacora;
   public $idUsuario;
   public $idModulo;
   public $idAccion;
   public $cNombreUsuario;
   public $cModulo;
   public $cAccion;
   public $cDescripcion;
   
   public $dtFecha;
   public $dtFechaInicio;
   public $dtFechaFin;
}
