<?php
require_once 'Reportes_ViewModel.php';
class Accion_ViewModel extends Reportes_ViewModel {
   
   public $idAccion;
   public $idModulo;
   public $cNombre;
   public $cAlias;
   public $cDescripcion;
   public $idTipoAccion;
   public $bHabilitado;
   public $bSistema;
   
   public $idAccionModulo;
   public $cNombreModulo;
   public $cAliasModulo;
   public $cEtiquetaTituloModulo;
   public $cEtiquetaDescripcionModulo;
}
