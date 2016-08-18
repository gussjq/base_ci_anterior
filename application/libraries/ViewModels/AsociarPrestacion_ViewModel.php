<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * AsociarPquetes_ViewModel
 * 
 * Clase que interactua como una capa intermedia entre el modelo y la vista,
 * donde se encarga de pasar datos o información requerida entre estas dos capas, mediante un objeto que contiene
 * todos los atributos necesarios ya sea para mostrar información en una forma (Vista) o para agregar o actualizar información
 * en la tabla (Modelo), de esta manera se asegura que siempre cada proceso contara con lo necesario para realizar su función 
 * 
 * @package ViewModels
 * @created 08-04-2015
 * @author DEVELOPER 1 <correo@developer1> cel <1111111111>
 */
require_once('Reportes_ViewModel.php');

class AsociarPrestacion_ViewModel extends Reportes_ViewModel {

    /** @var int Identificador unico a nivel base de datos de la asociación de los paquetes con sus prestaciones */
    public $idPrestacionPaquete;
    
    /** @var int Identificador del paquete de prestaciones que sera asociada a los empleados (Llave foranea), campo requerido para insertar y actualizar*/
    public $idPaquetePrestacion;
    
    /** @var int Identificador de la prestación que sera asociada al paquete de prestaciones (Llave foranea), campo requerido para insertar y actualizar*/
    public $idPrestacion;
    
    /** @var float Monto de la prestación dentro del paquete, por default se colocan el monto que corresponde a la prestación, sin embargo el valor del monto de la prestación puede ser sobre escrito para este paquete */
    public $fMonto;
    
    /** @var int Ordenación que llebaran las prestaciones dentro del paquete */
    public $iOrden;

}
