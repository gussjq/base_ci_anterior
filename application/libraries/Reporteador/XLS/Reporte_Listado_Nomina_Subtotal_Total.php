<?php

include_once '/../../Excel.php';
include_once '/../../ReportesData.php';

class Reporte_Listado_Nomina_Subtotal_Total  {
    
    private $oReporte;
    private $oPeriodo;
    private $CI;
    
    private $iConta = 0;
    private $iContaPer = 0;
    private $iContaDes = 0;
    
    // identificaor del departamento
    private $idDepartamento =0;
    
    // fila que se esta recorriendo actualmente
    private $iRowContador = 9;        
        
    // fila maxima de las percepciones
    private $iMaxPerY = 0;
    
    // fila maxima de las deducciones
    private $iMaxDesY=0;
    
    // fila maxima para colocar los totales por departamento
    private $iTotalesY = 0;
    
    // row por defaul de la primer vez en colocar los conceptos de percepcion y deduccion
    private $iRowDefaultCon = 11;
        
    // total de percepciones
    private $fPercepciones = 0;
    
    // total deducciones
    private $fDeducciones = 0;
    
    // cabecera de la tabla
    private $iRowCabecera = 7;
    
    // linea negra de la cabecera de la tabla
    private $iRowLineaHeader = 6;    
    private $iRowLineaHeader2 = 8;
    
    public function __construct() 
    {
        $this->CI  = & get_instance();
        $this->CI->load->model("nomina/Nomina_model");
        $this->CI->load->library("ViewModels/Nomina_ViewModel");
        $this->CI->load->library("Nominas");
    }


    public function initialize($oReporte)
    {
        $this->oReporte = $oReporte;
        $this->oPeriodo = $this->getPeriodo();    
    }
    
    public function generarReporte()
    {
        // cabeceras de la tabla
        $oPHPExcel = new Excel();
        $oPHPExcel->getProperties()->setTitle($this->oReporte->cNombre);
        
        $oPHPExcel->getDefaultStyle()->getFont()->setName("Arial")->setSize(8);
        
        // creacion de la cabecera del reporte
        $oPHPExcel->setActiveSheetIndex(0);
        $oPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setSize(12)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("A2")->getFont()->setSize(12)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("A3")->getFont()->setSize(12)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("A4")->getFont()->setSize(12)->setBold(true);
        
        $oPHPExcel->getActiveSheet()->setCellValue("A1", $this->oReporte->cNombreEmpresa);
        $oPHPExcel->getActiveSheet()->setCellValue("A2", $this->oReporte->cNombre);
        $oPHPExcel->getActiveSheet()->setCellValue("A3", $this->oPeriodo->cNombre);
        $oPHPExcel->getActiveSheet()->setCellValue("A4", "Del " . $this->oPeriodo->dtFechaInicial . " al " . $this->oPeriodo->dtFechaFinal);
        
        // configuramos el width que tendra las columnas
        $oPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(36.71);
        $oPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(10);
        $oPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(30);
        $oPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(10);
        $oPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth(15);
        $oPHPExcel->getActiveSheet()->getColumnDimension("F")->setWidth(10);
        $oPHPExcel->getActiveSheet()->getColumnDimension("G")->setWidth(30);
        $oPHPExcel->getActiveSheet()->getColumnDimension("H")->setWidth(15);
        
        // configuramos las lineas para negras solo para diseño del reporte
        $oPHPExcel->getActiveSheet()->getRowDimension($this->iRowLineaHeader)->setRowHeight(0.75);
        $oPHPExcel->getActiveSheet()->getRowDimension($this->iRowLineaHeader2)->setRowHeight(0.75);
        
        
        $oPHPExcel->getActiveSheet()->getStyle("A" . $this->iRowLineaHeader . ":H" .$this->iRowLineaHeader)
                ->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('0000000');
        
        $oPHPExcel->getActiveSheet()->getStyle("A" . $this->iRowLineaHeader2 . ":H" .$this->iRowLineaHeader2)
                ->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('0000000');        
        
        $oPHPExcel->getActiveSheet()->getStyle("A" . $this->iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("B" . $this->iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("C" . $this->iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("D" . $this->iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("E" . $this->iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("F" . $this->iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("G" . $this->iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("H" . $this->iRowCabecera)->getFont()->setSize(8)->setBold(true);
        
        $oPHPExcel->getActiveSheet()->setCellValue("A" . $this->iRowCabecera, lang("reportes_reporte_listdo_nomina_empleado"));
        $oPHPExcel->getActiveSheet()->setCellValue("B" . $this->iRowCabecera, lang("reportes_reporte_listdo_nomina_numero"));
        $oPHPExcel->getActiveSheet()->setCellValue("C" . $this->iRowCabecera, lang("reportes_reporte_listdo_nomina_percepcion"));
        $oPHPExcel->getActiveSheet()->setCellValue("D" . $this->iRowCabecera, lang("reportes_reporte_listdo_nomina_dias"));
        $oPHPExcel->getActiveSheet()->setCellValue("E" . $this->iRowCabecera, lang("reportes_reporte_listdo_nomina_monto"));
        $oPHPExcel->getActiveSheet()->setCellValue("F" . $this->iRowCabecera, lang("reportes_reporte_listdo_nomina_numero"));
        $oPHPExcel->getActiveSheet()->setCellValue("G" . $this->iRowCabecera, lang("reportes_reporte_listdo_nomina_deduccion"));
        $oPHPExcel->getActiveSheet()->setCellValue("H" . $this->iRowCabecera, lang("reportes_reporte_listdo_nomina_monto"));
                
        $aSubtotales = $this->_getSubtotalConceptos();
        
        $binit = true;
        foreach($aSubtotales AS $oConcepto)
        {
            // colocamos el header del departamento
            if($this->idDepartamento != $oConcepto->idDepartamento)
            {
                if($binit == false)
                {
                    $this->iTotalesY = ($this->iMaxPerY > $this->iMaxDesY) ? $this->iMaxPerY : $this->iMaxDesY;
                    $this->iRowContador = $this->iTotalesY;
                    $this->iRowContador++;$this->iRowContador++;
                    
                    $oPHPExcel->getActiveSheet()->setCellValue("A" . $this->iRowContador, lang("reportes_reporte_listdo_neto"));
                    
                    $oPHPExcel->getActiveSheet()->getStyle("B" . $this->iRowContador)->getFont()->setSize(8)->setBold(true);
                    $oPHPExcel->getActiveSheet()->getStyle("E" . $this->iRowContador)->getFont()->setSize(8)->setBold(true);
                    $oPHPExcel->getActiveSheet()->getStyle("H" . $this->iRowContador)->getFont()->setSize(8)->setBold(true);

                    $oPHPExcel->getActiveSheet()->getStyle("B" . $this->iRowContador)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $oPHPExcel->getActiveSheet()->getStyle("E" . $this->iRowContador)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $oPHPExcel->getActiveSheet()->getStyle("H" . $this->iRowContador)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

                    $oPHPExcel->getActiveSheet()->setCellValue("B" . $this->iRowContador, ($this->fPercepciones - $this->fDeducciones));
                    $oPHPExcel->getActiveSheet()->setCellValue("E" . $this->iRowContador, $this->fPercepciones);
                    $oPHPExcel->getActiveSheet()->setCellValue("H" . $this->iRowContador, $this->fDeducciones);

                    $this->iRowContador++;
                    $oPHPExcel->getActiveSheet()->getRowDimension($this->iRowContador)->setRowHeight(0.75);
                    $oPHPExcel->getActiveSheet()->getStyle('A' . ($this->iRowContador) . ':H' .($this->iRowContador))
                        ->getFill()
                        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('0000000'); 
                    
                    $this->iRowContador++;
                }
                
                $oPHPExcel->getActiveSheet()->getStyle('A' . $this->iRowContador)->getFont()->setSize(8)->setBold(true);
                $oPHPExcel->getActiveSheet()->mergeCells('A'. $this->iRowContador . ':H' . $this->iRowContador);
                $oPHPExcel->getActiveSheet()->setCellValue('A'. $this->iRowContador, $oConcepto->cCodigoDepartamento . " - " .$oConcepto->cNombreDepartamento);
                
                $this->iRowContador++;
                $oPHPExcel->getActiveSheet()->getRowDimension($this->iRowContador)->setRowHeight(0.75);
                $oPHPExcel->getActiveSheet()->getStyle('A' . ($this->iRowContador) . ':H' .($this->iRowContador))
                    ->getFill()
                    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('0000000');    
                
                // colocamos los datos informativos del departamento
                $this->iRowContador++;
                $oPHPExcel->getActiveSheet()->setCellValue("A" . ($this->iRowContador), lang("reportes_reporte_listdo_total_de") . " " . $oConcepto->cCodigoDepartamento);                
                $this->iRowContador++;
                $oPHPExcel->getActiveSheet()->setCellValue("A" . ($this->iRowContador), $oConcepto->cNombreDepartamento);
                $this->iRowContador++;
                $oPHPExcel->getActiveSheet()->setCellValue("A" . ($this->iRowContador), lang("reportes_reporte_listdo_nomina_empleados") . ": " . $oConcepto->iNumeroEmpleados);
                
                $this->iRowContador++;
                $this->iContaPer =0;
                $this->iContaDes = 0;
                $this->fDeducciones = 0;
                $this->fPercepciones = 0;
                
                $binit= false;
            }
            
            
            // colocamos pas percepciones y deducciones
            if($oConcepto->idTipoConcepto == CONCEPTOS_TIPO_PERCEPCION)
            {    
                if($this->iContaPer == 0 && $this->iConta == 0)
                {
                    $this->iRowContador = $this->iRowDefaultCon;
                }                
                
                if($this->iContaPer == 0 && $this->iConta > 0)
                {
                    $this->iRowContador = $this->iRowContador - 3;
                }  

                $oPHPExcel->getActiveSheet()->getStyle('C' . ($this->iRowContador))->getAlignment()->setWrapText(true);        
                $oPHPExcel->getActiveSheet()->getStyle("E" . ($this->iRowContador))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                
                $oPHPExcel->getActiveSheet()->setCellValue("B" . ($this->iRowContador), $oConcepto->iNumeroConcepto);
                $oPHPExcel->getActiveSheet()->setCellValue("C" . ($this->iRowContador), $oConcepto->cNombreConcepto);
                $oPHPExcel->getActiveSheet()->setCellValue("D" . ($this->iRowContador), ($oConcepto->iValor) ? $oConcepto->iValor : '');
                $oPHPExcel->getActiveSheet()->setCellValue("E" . ($this->iRowContador), $oConcepto->fPagarGravable);
                
                $this->fPercepciones+=$oConcepto->fPagarGravable;
                $this->iMaxPerY = ($this->iRowContador);
                $this->iContaPer++;
            }
            else
            {
                if($this->iContaDes == 0 && $this->iConta > 0)
                {
                    $this->iRowContador = $this->iRowContador - $this->iContaPer;
                }  
                
                $oPHPExcel->getActiveSheet()->getStyle('G' . ($this->iRowContador))->getAlignment()->setWrapText(true);        
                $oPHPExcel->getActiveSheet()->getStyle("H" . ($this->iRowContador))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                
                $oPHPExcel->getActiveSheet()->setCellValue("F" . ($this->iRowContador), $oConcepto->iNumeroConcepto);
                $oPHPExcel->getActiveSheet()->setCellValue("G" . ($this->iRowContador), $oConcepto->cNombreConcepto);
                $oPHPExcel->getActiveSheet()->setCellValue("H" . ($this->iRowContador), $oConcepto->fPagarGravable);
                
                $this->fDeducciones +=$oConcepto->fPagarGravable;
                $this->iMaxDesY = ($this->iRowContador);
                $this->iContaDes++;
            }           
            
            $this->idDepartamento = $oConcepto->idDepartamento;
            $this->iConta++;
            $this->iRowContador ++;
        }
        
        
        // agregamos los totales
        
        
        $this->iTotalesY = ($this->iMaxPerY > $this->iMaxDesY) ? $this->iMaxPerY : $this->iMaxDesY;
        $this->iRowContador = $this->iTotalesY;
        $this->iRowContador++;$this->iRowContador++;

        $oPHPExcel->getActiveSheet()->setCellValue("A" . $this->iRowContador, lang("reportes_reporte_listdo_neto"));

        $oPHPExcel->getActiveSheet()->getStyle("B" . $this->iRowContador)->getFont()->setSize(8)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("E" . $this->iRowContador)->getFont()->setSize(8)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("H" . $this->iRowContador)->getFont()->setSize(8)->setBold(true);

        $oPHPExcel->getActiveSheet()->getStyle("B" . $this->iRowContador)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $oPHPExcel->getActiveSheet()->getStyle("E" . $this->iRowContador)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $oPHPExcel->getActiveSheet()->getStyle("H" . $this->iRowContador)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        $oPHPExcel->getActiveSheet()->setCellValue("B" . $this->iRowContador, ($this->fPercepciones - $this->fDeducciones));
        $oPHPExcel->getActiveSheet()->setCellValue("E" . $this->iRowContador, $this->fPercepciones);
        $oPHPExcel->getActiveSheet()->setCellValue("H" . $this->iRowContador, $this->fDeducciones);

        $this->iRowContador++;
        $oPHPExcel->getActiveSheet()->getRowDimension($this->iRowContador)->setRowHeight(0.75);
        $oPHPExcel->getActiveSheet()->getStyle('A' . ($this->iRowContador) . ':H' .($this->iRowContador))
            ->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('0000000'); 

        $this->iRowContador++;
                    
        $this->iConta = 0;
        $this->iContaPer = 0;$this->iContaDes = 0;
        $this->fPercepciones = 0;$this->fDeducciones = 0;
        
        // agrergamos el Gran Total
        $aTotales = $this->_getTotalesConceptos();
        
        foreach($aTotales AS $oConcepto)
        {
            if($this->iConta == 0)
            {
                $oPHPExcel->getActiveSheet()->getStyle('A' . $this->iRowContador)->getFont()->setSize(8)->setBold(true);
                $oPHPExcel->getActiveSheet()->mergeCells('A'. $this->iRowContador . ':H' . $this->iRowContador);
                $oPHPExcel->getActiveSheet()->setCellValue('A'. $this->iRowContador, lang("reportes_reporte_listdo_gran_total_sin_asteriscos"));
                
                $this->iRowContador++;
                $oPHPExcel->getActiveSheet()->getRowDimension($this->iRowContador)->setRowHeight(0.75);
                $oPHPExcel->getActiveSheet()->getStyle('A' . ($this->iRowContador) . ':H' .($this->iRowContador))
                    ->getFill()
                    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('0000000');      
                
                $this->iRowContador++;
                $oPHPExcel->getActiveSheet()->setCellValue("A" . ($this->iRowContador), lang("reportes_reporte_listdo_gran_total"));                
                
                $this->iRowContador++;
                $oPHPExcel->getActiveSheet()->setCellValue("A" . ($this->iRowContador), lang("reportes_reporte_listdo_empresa"));
                
                $this->iRowContador++;
                $oPHPExcel->getActiveSheet()->setCellValue("A" . ($this->iRowContador), lang("reportes_reporte_listdo_nomina_empleados") . ": " . $oConcepto->iNumeroEmpleados);
            }
            
            // colocamos pas percepciones y deducciones
            if($oConcepto->idTipoConcepto == CONCEPTOS_TIPO_PERCEPCION)
            {
                if($this->iContaPer == 0 && $this->iConta == 0)
                {
                    $this->iRowContador = $this->iRowContador - 2;
                }  
                
                $oPHPExcel->getActiveSheet()->getStyle('C' . ($this->iRowContador))->getAlignment()->setWrapText(true);        
                $oPHPExcel->getActiveSheet()->getStyle("E" . ($this->iRowContador))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                
                $oPHPExcel->getActiveSheet()->setCellValue("B" . ($this->iRowContador), $oConcepto->iNumeroConcepto);
                $oPHPExcel->getActiveSheet()->setCellValue("C" . ($this->iRowContador), $oConcepto->cNombreConcepto);
                $oPHPExcel->getActiveSheet()->setCellValue("D" . ($this->iRowContador), ($oConcepto->iValor) ? $oConcepto->iValor : '');
                $oPHPExcel->getActiveSheet()->setCellValue("E" . ($this->iRowContador), $oConcepto->fPagarGravable);
                
                $this->fPercepciones+=$oConcepto->fPagarGravable;
                $this->iMaxPerY = ($this->iRowContador);
                $this->iContaPer++;
            }
            else
            {
                if($this->iContaDes == 0 && $this->iConta > 0)
                {
                    $this->iRowContador = $this->iRowContador - $this->iContaPer;
                }  
                
                $oPHPExcel->getActiveSheet()->getStyle('G' . ($this->iRowContador))->getAlignment()->setWrapText(true);        
                $oPHPExcel->getActiveSheet()->getStyle("H" . ($this->iRowContador))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                
                $oPHPExcel->getActiveSheet()->setCellValue("F" . ($this->iRowContador), $oConcepto->iNumeroConcepto);
                $oPHPExcel->getActiveSheet()->setCellValue("G" . ($this->iRowContador), $oConcepto->cNombreConcepto);
                $oPHPExcel->getActiveSheet()->setCellValue("H" . ($this->iRowContador), $oConcepto->fPagarGravable);
                
                $this->fDeducciones+=$oConcepto->fPagarGravable;
                $this->iMaxDesY = ($this->iRowContador );
                $this->iContaDes++;
            }
            
            $this->iConta++;
            $this->iRowContador++;
        }
        
        
        
        // agregamos los totales
        $this->iTotalesY = ($this->iMaxPerY > $this->iMaxDesY) ? $this->iMaxPerY : $this->iMaxDesY;
        $this->iRowContador = $this->iTotalesY;
        $this->iRowContador++;$this->iRowContador++;

        $oPHPExcel->getActiveSheet()->setCellValue("A" . $this->iRowContador, lang("reportes_reporte_listdo_neto"));

        $oPHPExcel->getActiveSheet()->getStyle("B" . $this->iRowContador)->getFont()->setSize(8)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("E" . $this->iRowContador)->getFont()->setSize(8)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("H" . $this->iRowContador)->getFont()->setSize(8)->setBold(true);

        $oPHPExcel->getActiveSheet()->getStyle("B" . $this->iRowContador)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $oPHPExcel->getActiveSheet()->getStyle("E" . $this->iRowContador)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $oPHPExcel->getActiveSheet()->getStyle("H" . $this->iRowContador)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        $oPHPExcel->getActiveSheet()->setCellValue("B" . $this->iRowContador, ($this->fPercepciones - $this->fDeducciones));
        $oPHPExcel->getActiveSheet()->setCellValue("E" . $this->iRowContador, $this->fPercepciones);
        $oPHPExcel->getActiveSheet()->setCellValue("H" . $this->iRowContador, $this->fDeducciones);

        $this->iRowContador++;
        $oPHPExcel->getActiveSheet()->getRowDimension($this->iRowContador)->setRowHeight(0.75);
        $oPHPExcel->getActiveSheet()->getStyle('A' . ($this->iRowContador) . ':H' .($this->iRowContador))
            ->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('0000000'); 
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $this->oReporte->cNombre . '".xls');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($oPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }
    
   private function getPeriodo()
   {
       $this->CI->load->model("nomina/Periodo_model");
       $this->CI->load->library("ViewModels/Periodo_ViewModel");
       $this->CI->load->library("seguridad");
       
       $dbPeriodo = $this->CI->Periodo_model->find(array(
           "idPeriodo" => $this->oReporte->idPeriodo, "bBorradoLogico" => NO
       ));
       
       $oPeriodo = new Periodo_ViewModel();
       if(is_object($dbPeriodo))
       {
           $oPeriodo = $this->CI->seguridad->dbToView($oPeriodo, $dbPeriodo);
       }
       
       return $oPeriodo;
   }
   
   private function & _getSubtotalConceptos()
   {
       $oNomina = new Nomina_ViewModel();
       $oNomina->idTipoNomina = $this->oReporte->idTipoNomina;
       $oNomina->idPeriodo = $this->oReporte->idPeriodo;
       
       $aConceptos = & $this->CI->Nomina_model->getSubtotalConceptos($oNomina);
       
       return $aConceptos;
   }
    
    private function & _getTotalesConceptos()
    {
       $oNomina = new Nomina_ViewModel();
       $oNomina->idTipoNomina = $this->oReporte->idTipoNomina;
       $oNomina->idPeriodo = $this->oReporte->idPeriodo;
       
       $aConceptos = & $this->CI->Nomina_model->getTotalesConceptos($oNomina);
       
       return $aConceptos;
    }
}
