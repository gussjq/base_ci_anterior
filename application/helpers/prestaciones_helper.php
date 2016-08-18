<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PrestacionesHelper {
    
    public function factorIntegracion($oPrestacionLey)
    {
        $fFactorIntegracion = 0.0000;
        switch ($oPrestacionLey->idTablaPrestacion)
        {
            case TABLA_DE_PRESTACIONES_SALARIOS_MINIMO:
                $fFactorIntegracion = $this->_factorIntegracionSalariosMinimos($oPrestacionLey);
                break;
            case TABLA_DE_PRESTACIONES_EMPLEADOS:
                $fFactorIntegracion = $this->_factorIntegracionEmpleados($oPrestacionLey);
                break;
            case TABLA_DE_PRESTACIONES_OPERARIOS:
                $fFactorIntegracion = $this->_factorIntegracionOperarios($oPrestacionLey);
                break;
        }
        return number_format($fFactorIntegracion, 4);
    }
    
    
    public function factorIntegracionSalariosMinimos($oPrestacionLey)
    {
       $iDiasAguinaldo =  $oPrestacionLey->iDiasAguinaldo + $oPrestacionLey->iDiasAdicionales;
       
       if($oPrestacionLey->bPagoSeptimoDiaVacaciones)
       {
           $iDiasAguinaldo = $iDiasAguinaldo - ($oPrestacionLey->iVacaciones / 6);
       }
        
       $f = ((($iDiasAguinaldo / DIAS_CALENDARIO) + (($oPrestacionLey->iVacaciones / DIAS_CALENDARIO ) * ($oPrestacionLey->fPrimaVacacional / 100))) + UNIDAD);
       echo number_format($f, 4);
       exit();
    }
    
    private function _factorIntegracionEmpleados()
    {
        
    }
    
    private function _factorIntegracionOperarios()
    {
        
    }
}
