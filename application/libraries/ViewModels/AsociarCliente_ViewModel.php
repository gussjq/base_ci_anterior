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

class AsociarCliente_ViewModel extends Reportes_ViewModel {
    
    public $idUsuarioCliente; 

    /** @var int Identificador del usuario al que se asociaran los clientes */
    public $idUsuario;
    
    /** @var int Identificador del cliente de los que seran asociado al usuario */
    public $idCliente;
    
    public $cNombreUsuario;
    
    public $cNombreCliente;
    
//    public $aClientesAsociados;

}
