<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * Avisos_ViewModel
 * 
 * Clase que interactua como una capa intermedia entre el modelo y la vista,
 * donde se encarga de pasar datos o información requerida entre estas dos capas, mediante un objeto que contiene
 * todos los atributos necesarios ya sea para mostrar información en una forma (Vista) o para agregar o actualizar información
 * en la tabla (Modelo), de esta manera se asegura que siempre cada proceso contara con lo necesario para realizar su función 
 * 
 * @package ViewModels
 * @created 22-05-2015
 * @author DEVELOPER 1 <correo@developer1> cel <1111111111>
 */
require_once('Reportes_ViewModel.php');

class Aviso_ViewModel extends Reportes_ViewModel {

    public $idAviso;
    public $cTitulo;
    public $txCuerpo;
    public $dtFechaCreacion;
    public $idUsuario;
    public $iEstatus;
    public $iTipoUsuario;
    
    public $aUsuarios = array();
    public $cListaUsuarios = "";
    
    public $dtFechaCreacionInicio;
    public $dtFechaCreacionFin;
    public $bLeido = "";

}
