<?php
class Reportes_ViewModel {	
	public $iOrdenadoPor;
	public $iAgrupadoPor;
	public $iODireccion;
	public $indiceAgruparPor;
	public $Agrupar;
	public $indiceOrdenarPor;
	public $cBuscar;
        
        //para el modelo unicamente
        public $order;
        public $sortBy;
        public $offset;
        public $limit;
        public $count;
        public $not = array();
        
        public $where_in = array();
}  