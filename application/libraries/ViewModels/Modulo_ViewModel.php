<?php
require_once('Reportes_ViewModel.php');
class Modulo_ViewModel extends Reportes_ViewModel {
   public $idModulo;
   public $cNombre;
   public $cAlias;
   public $cDescripcion;
   public $cEtiquetaTitulo;
   public $cEtiquetaDescripcion;
   public $cIcono;
   public $bHabilitado;
   public $bSistema;
   
   public $aAcciones = array();
}
