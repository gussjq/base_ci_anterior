<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * Reporte_ViewModel
 * 
 * Clase que interactua como una capa intermedia entre el modelo y la vista,
 * donde se encarga de pasar datos o información requerida entre estas dos capas, mediante un objeto que contiene
 * todos los atributos necesarios ya sea para mostrar información en una forma (Vista) o para agregar o actualizar información
 * en la tabla (Modelo), de esta manera se asegura que siempre cada proceso contara con lo necesario para realizar su función 
 * 
 * @package ViewModels
 * @created 11-03-2015
 * @author DEVELOPER 1 <correo@developer1> cel <1111111111>
 */
require_once('Reportes_ViewModel.php');

class Reporte_ViewModel extends Reportes_ViewModel {

    public $idReporte;
    public $cNombre;
    public $cAlias;
    public $idTipoReporte;
    public $idTablaReporte;
    public $idReporteCarpeta;
    
    
    public $iTipoFormato;
    
    public $iMes;
    public $iAno;
    public $fIva;
    
    public $iFiltroTipoNomina;    
    public $txTipoNomina;
    public $bCheckTodosTipoNomina;
    
    public $iFiltroEmpleado;
    public $txEmpleado;
    public $bCheckTodosEmpleado;

    public $iFiltroTipoAhorroPrestamo;
    public $txTipoAhorroPrestamo;
    public $bCheckTodosTipoAhorroPresatmo;    

    public $iEjercicio;
    public $iFiltroConcepto;
    public $txConcepto;
    public $bCheckTodosConcepto;
    
    public $idEmpresa;
    public $iMesInicio;
    public $iMesFin;
    public $bConta;
    
    public $idTipoNomina;
    public $idPeriodo;

}
