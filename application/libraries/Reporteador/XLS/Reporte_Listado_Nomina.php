<?php

include_once '/../../Excel.php';
include_once '/../../ReportesData.php';

class Reporte_Listado_Nomina  {
    
    private $oReporte;
    private $oPeriodo;
    private $CI;
    
    private $oPHPExcel;
    
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
    
    
    // identificador del empleado
    private $idEmpleado = 0;
    
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
        $this->oPHPExcel = new Excel();
    }
    
    public function generarReporte()
    {
        // cabeceras de la tabla
        $this->oPHPExcel->getProperties()->setTitle($this->oReporte->cNombre);
        $this->oPHPExcel->getDefaultStyle()->getFont()->setName("Arial")->setSize(8);
        
        // creacion de la cabecera del reporte
        $this->oPHPExcel->setActiveSheetIndex(0);
        $this->oPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setSize(12)->setBold(true);
        $this->oPHPExcel->getActiveSheet()->getStyle("A2")->getFont()->setSize(12)->setBold(true);
        $this->oPHPExcel->getActiveSheet()->getStyle("A3")->getFont()->setSize(12)->setBold(true);
        $this->oPHPExcel->getActiveSheet()->getStyle("A4")->getFont()->setSize(12)->setBold(true);
        
        $this->oPHPExcel->getActiveSheet()->setCellValue("A1", $this->oReporte->cNombreEmpresa);
        $this->oPHPExcel->getActiveSheet()->setCellValue("A2", $this->oReporte->cNombre);
        $this->oPHPExcel->getActiveSheet()->setCellValue("A3", $this->oPeriodo->cNombre);
        $this->oPHPExcel->getActiveSheet()->setCellValue("A4", "Del " . $this->oPeriodo->dtFechaInicial . " al " . $this->oPeriodo->dtFechaFinal);
        
        // configuramos el width que tendra las columnas
        $this->oPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(36.71);
        $this->oPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(10);
        $this->oPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(30);
        $this->oPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(10);
        $this->oPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth(15);
        $this->oPHPExcel->getActiveSheet()->getColumnDimension("F")->setWidth(10);
        $this->oPHPExcel->getActiveSheet()->getColumnDimension("G")->setWidth(30);
        $this->oPHPExcel->getActiveSheet()->getColumnDimension("H")->setWidth(15);
        
        // configuramos las lineas para negras solo para diseño del reporte
        $this->oPHPExcel->getActiveSheet()->getRowDimension($this->iRowLineaHeader)->setRowHeight(0.75);
        $this->oPHPExcel->getActiveSheet()->getRowDimension($this->iRowLineaHeader2)->setRowHeight(0.75);
        
        
        $this->oPHPExcel->getActiveSheet()->getStyle("A" . $this->iRowLineaHeader . ":H" .$this->iRowLineaHeader)
                ->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('0000000');
        
        $this->oPHPExcel->getActiveSheet()->getStyle("A" . $this->iRowLineaHeader2 . ":H" .$this->iRowLineaHeader2)
                ->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('0000000');        
        
        $this->oPHPExcel->getActiveSheet()->getStyle("A" . $this->iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $this->oPHPExcel->getActiveSheet()->getStyle("B" . $this->iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $this->oPHPExcel->getActiveSheet()->getStyle("C" . $this->iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $this->oPHPExcel->getActiveSheet()->getStyle("D" . $this->iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $this->oPHPExcel->getActiveSheet()->getStyle("E" . $this->iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $this->oPHPExcel->getActiveSheet()->getStyle("F" . $this->iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $this->oPHPExcel->getActiveSheet()->getStyle("G" . $this->iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $this->oPHPExcel->getActiveSheet()->getStyle("H" . $this->iRowCabecera)->getFont()->setSize(8)->setBold(true);
        
        $this->oPHPExcel->getActiveSheet()->setCellValue("A" . $this->iRowCabecera, lang("reportes_reporte_listdo_nomina_empleado"));
        $this->oPHPExcel->getActiveSheet()->setCellValue("B" . $this->iRowCabecera, lang("reportes_reporte_listdo_nomina_numero"));
        $this->oPHPExcel->getActiveSheet()->setCellValue("C" . $this->iRowCabecera, lang("reportes_reporte_listdo_nomina_percepcion"));
        $this->oPHPExcel->getActiveSheet()->setCellValue("D" . $this->iRowCabecera, lang("reportes_reporte_listdo_nomina_dias"));
        $this->oPHPExcel->getActiveSheet()->setCellValue("E" . $this->iRowCabecera, lang("reportes_reporte_listdo_nomina_monto"));
        $this->oPHPExcel->getActiveSheet()->setCellValue("F" . $this->iRowCabecera, lang("reportes_reporte_listdo_nomina_numero"));
        $this->oPHPExcel->getActiveSheet()->setCellValue("G" . $this->iRowCabecera, lang("reportes_reporte_listdo_nomina_deduccion"));
        $this->oPHPExcel->getActiveSheet()->setCellValue("H" . $this->iRowCabecera, lang("reportes_reporte_listdo_nomina_monto"));
        
        $aDepartamentos = & $this->_getDepartamentos();
        $oNomina = new Nomina_ViewModel();
        
        foreach($aDepartamentos AS $oDepartamento)
        {
            $oNomina->idTipoNomina = $this->oReporte->idTipoNomina;
            $oNomina->idPeriodo = $this->oReporte->idPeriodo;
            $oNomina->idDepartamento = $oDepartamento->idDepartamento;
            
            $this->oPHPExcel->getActiveSheet()->mergeCells('A'. $this->iRowContador . ':H' . $this->iRowContador);
            $this->oPHPExcel->getActiveSheet()->getStyle("A" . $this->iRowContador)->getFont()->setSize(8)->setBold(true);
            $this->oPHPExcel->getActiveSheet()->setCellValue("A" . $this->iRowContador, $oDepartamento->cCodigo . " - " . $oDepartamento->cNombre);
            
            $this->iRowContador++;
            $this->oPHPExcel->getActiveSheet()->getRowDimension($this->iRowContador)->setRowHeight(0.75);
            $this->oPHPExcel->getActiveSheet()->mergeCells('A'. $this->iRowContador . ':H' . $this->iRowContador);
            $this->oPHPExcel->getActiveSheet()->getStyle('A' . ($this->iRowContador) . ':H' .($this->iRowContador))
                        ->getFill()
                        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('0000000'); 
            
            $this->_pintarDetalle($oNomina);
            $this->_pintarSubTotal($oNomina);
  
        }
        
        $this->_pintarGranTotal();
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $this->oReporte->cNombre . '".xls');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($this->oPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }
    
    private function _pintarDetalle($oNomina)
    {
        $binit = true;
        $aEmpleados = $this->CI->Nomina_model->getListadoNomina($oNomina);
        foreach($aEmpleados AS $oConcepto)
        {            
            // colocamos el header del departamento
            if($this->idEmpleado != $oConcepto->idEmpleado)
            {
                if($binit == false)
                {
                    $this->iTotalesY = ($this->iMaxPerY > $this->iMaxDesY) ? $this->iMaxPerY : $this->iMaxDesY;
                    $this->iRowContador = $this->iTotalesY;
                    $this->iRowContador++;$this->iRowContador++;
                    
                    $this->oPHPExcel->getActiveSheet()->setCellValue("A" . $this->iRowContador, lang("reportes_reporte_listdo_neto"));
                    
                    $this->oPHPExcel->getActiveSheet()->getStyle("B" . $this->iRowContador)->getFont()->setSize(8)->setBold(true);
                    $this->oPHPExcel->getActiveSheet()->getStyle("E" . $this->iRowContador)->getFont()->setSize(8)->setBold(true);
                    $this->oPHPExcel->getActiveSheet()->getStyle("H" . $this->iRowContador)->getFont()->setSize(8)->setBold(true);

                    $this->oPHPExcel->getActiveSheet()->getStyle("B" . $this->iRowContador)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $this->oPHPExcel->getActiveSheet()->getStyle("E" . $this->iRowContador)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $this->oPHPExcel->getActiveSheet()->getStyle("H" . $this->iRowContador)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

                    $this->oPHPExcel->getActiveSheet()->setCellValue("B" . $this->iRowContador, ($this->fPercepciones - $this->fDeducciones));
                    $this->oPHPExcel->getActiveSheet()->setCellValue("E" . $this->iRowContador, $this->fPercepciones);
                    $this->oPHPExcel->getActiveSheet()->setCellValue("H" . $this->iRowContador, $this->fDeducciones);

                    $this->iRowContador++;
                    $this->oPHPExcel->getActiveSheet()->getRowDimension($this->iRowContador)->setRowHeight(0.75);
                    $this->oPHPExcel->getActiveSheet()->getStyle('A' . ($this->iRowContador) . ':H' .($this->iRowContador))
                        ->getFill()
                        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('0000000'); 
                }
//                
                // colocamos los datos informativos del departamento
                $this->iRowContador++;
                $this->oPHPExcel->getActiveSheet()->getStyle('A' . ($this->iRowContador))->getAlignment()->setWrapText(true);   
                $this->oPHPExcel->getActiveSheet()->setCellValue("A" . ($this->iRowContador), $oConcepto->iNumero . " : " .$oConcepto->cNombreEmpleado);                
                $this->iRowContador++;
                $this->oPHPExcel->getActiveSheet()->setCellValue("A" . ($this->iRowContador), $oConcepto->cRFC);
                $this->iRowContador++;
                $this->oPHPExcel->getActiveSheet()->setCellValue("A" . ($this->iRowContador), $oConcepto->cIMSS . " Ing: " .$oConcepto->dtFechaIngreso);
                $this->iRowContador++;
                $this->oPHPExcel->getActiveSheet()->setCellValue("A" . ($this->iRowContador), "SD: " . ($oConcepto->fSalario) . " SDI: " . ($oConcepto->fSalarioDiarioIntegrado));
                
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
                    $this->iRowContador = $this->iRowContador - 4;
                }  

                $this->oPHPExcel->getActiveSheet()->getStyle('C' . ($this->iRowContador))->getAlignment()->setWrapText(true);        
                $this->oPHPExcel->getActiveSheet()->getStyle("E" . ($this->iRowContador))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                
                $this->oPHPExcel->getActiveSheet()->setCellValue("B" . ($this->iRowContador), $oConcepto->iNumeroConcepto);
                $this->oPHPExcel->getActiveSheet()->setCellValue("C" . ($this->iRowContador), $oConcepto->cNombreConcepto);
                $this->oPHPExcel->getActiveSheet()->setCellValue("D" . ($this->iRowContador), ($oConcepto->iValor) ? $oConcepto->iValor : '');
                $this->oPHPExcel->getActiveSheet()->setCellValue("E" . ($this->iRowContador), $oConcepto->fPagarGravable);
                
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
                
                $this->oPHPExcel->getActiveSheet()->getStyle('G' . ($this->iRowContador))->getAlignment()->setWrapText(true);        
                $this->oPHPExcel->getActiveSheet()->getStyle("H" . ($this->iRowContador))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                
                $this->oPHPExcel->getActiveSheet()->setCellValue("F" . ($this->iRowContador), $oConcepto->iNumeroConcepto);
                $this->oPHPExcel->getActiveSheet()->setCellValue("G" . ($this->iRowContador), $oConcepto->cNombreConcepto);
                $this->oPHPExcel->getActiveSheet()->setCellValue("H" . ($this->iRowContador), $oConcepto->fPagarGravable);
                
                $this->fDeducciones +=$oConcepto->fPagarGravable;
                $this->iMaxDesY = ($this->iRowContador);
                $this->iContaDes++;
            }           
            
            $this->idEmpleado = $oConcepto->idEmpleado;
            $this->iConta++;
            $this->iRowContador ++;
        }
        
        
        // agregamos los totales
        $this->iTotalesY = ($this->iMaxPerY > $this->iMaxDesY) ? $this->iMaxPerY : $this->iMaxDesY;
        $this->iRowContador = $this->iTotalesY;
        $this->iRowContador++;$this->iRowContador++;

        $this->oPHPExcel->getActiveSheet()->setCellValue("A" . $this->iRowContador, lang("reportes_reporte_listdo_neto"));

        $this->oPHPExcel->getActiveSheet()->getStyle("B" . $this->iRowContador)->getFont()->setSize(8)->setBold(true);
        $this->oPHPExcel->getActiveSheet()->getStyle("E" . $this->iRowContador)->getFont()->setSize(8)->setBold(true);
        $this->oPHPExcel->getActiveSheet()->getStyle("H" . $this->iRowContador)->getFont()->setSize(8)->setBold(true);

        $this->oPHPExcel->getActiveSheet()->getStyle("B" . $this->iRowContador)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $this->oPHPExcel->getActiveSheet()->getStyle("E" . $this->iRowContador)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $this->oPHPExcel->getActiveSheet()->getStyle("H" . $this->iRowContador)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        $this->oPHPExcel->getActiveSheet()->setCellValue("B" . $this->iRowContador, ($this->fPercepciones - $this->fDeducciones));
        $this->oPHPExcel->getActiveSheet()->setCellValue("E" . $this->iRowContador, $this->fPercepciones);
        $this->oPHPExcel->getActiveSheet()->setCellValue("H" . $this->iRowContador, $this->fDeducciones);

        $this->iRowContador++;
        $this->oPHPExcel->getActiveSheet()->getRowDimension($this->iRowContador)->setRowHeight(0.75);
        $this->oPHPExcel->getActiveSheet()->getStyle('A' . ($this->iRowContador) . ':H' .($this->iRowContador))
            ->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('0000000'); 

        $this->iRowContador++;
    }
    
    private function _pintarSubTotal($oNomina)
    {
        $aConceptos = & $this->CI->Nomina_model->getSubtotalConceptos($oNomina);
        
        $binit = true;
        $this->iContaPer = 0;
        $this->iContaDes = 0;
        $this->fDeducciones = 0;
        $this->fPercepciones = 0;
        
        foreach($aConceptos AS $oConcepto)
        {
            // colocamos el header del departamento
            if($this->idDepartamento != $oConcepto->idDepartamento)
            {
                if($binit == false)
                {
                    $this->iTotalesY = ($this->iMaxPerY > $this->iMaxDesY) ? $this->iMaxPerY : $this->iMaxDesY;
                    $this->iRowContador = $this->iTotalesY;
                    $this->iRowContador++;$this->iRowContador++;
                    
                    $this->oPHPExcel->getActiveSheet()->setCellValue("A" . $this->iRowContador, lang("reportes_reporte_listdo_neto"));
                    
                    $this->oPHPExcel->getActiveSheet()->getStyle("B" . $this->iRowContador)->getFont()->setSize(8)->setBold(true);
                    $this->oPHPExcel->getActiveSheet()->getStyle("E" . $this->iRowContador)->getFont()->setSize(8)->setBold(true);
                    $this->oPHPExcel->getActiveSheet()->getStyle("H" . $this->iRowContador)->getFont()->setSize(8)->setBold(true);

                    $this->oPHPExcel->getActiveSheet()->getStyle("B" . $this->iRowContador)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $this->oPHPExcel->getActiveSheet()->getStyle("E" . $this->iRowContador)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $this->oPHPExcel->getActiveSheet()->getStyle("H" . $this->iRowContador)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

                    $this->oPHPExcel->getActiveSheet()->setCellValue("B" . $this->iRowContador, ($this->fPercepciones - $this->fDeducciones));
                    $this->oPHPExcel->getActiveSheet()->setCellValue("E" . $this->iRowContador, $this->fPercepciones);
                    $this->oPHPExcel->getActiveSheet()->setCellValue("H" . $this->iRowContador, $this->fDeducciones);

                    $this->iRowContador++;
                    $this->oPHPExcel->getActiveSheet()->getRowDimension($this->iRowContador)->setRowHeight(0.75);
                    $this->oPHPExcel->getActiveSheet()->getStyle('A' . ($this->iRowContador) . ':H' .($this->iRowContador))
                        ->getFill()
                        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('0000000'); 
                    
                    $this->iRowContador++;
                }
                
                $this->oPHPExcel->getActiveSheet()->getStyle('A' . $this->iRowContador)->getFont()->setSize(8)->setBold(true);
                $this->oPHPExcel->getActiveSheet()->mergeCells('A'. $this->iRowContador . ':H' . $this->iRowContador);
                $this->oPHPExcel->getActiveSheet()->setCellValue('A'. $this->iRowContador, $oConcepto->cCodigoDepartamento . " - " .$oConcepto->cNombreDepartamento);
                
                $this->iRowContador++;
                $this->oPHPExcel->getActiveSheet()->getRowDimension($this->iRowContador)->setRowHeight(0.75);
                $this->oPHPExcel->getActiveSheet()->getStyle('A' . ($this->iRowContador) . ':H' .($this->iRowContador))
                    ->getFill()
                    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('0000000');    
                
                // colocamos los datos informativos del departamento
                $this->iRowContador++;
                $this->oPHPExcel->getActiveSheet()->setCellValue("A" . ($this->iRowContador), lang("reportes_reporte_listdo_total_de") . " " . $oConcepto->cCodigoDepartamento);                
                $this->iRowContador++;
                $this->oPHPExcel->getActiveSheet()->setCellValue("A" . ($this->iRowContador), $oConcepto->cNombreDepartamento);
                $this->iRowContador++;
                $this->oPHPExcel->getActiveSheet()->setCellValue("A" . ($this->iRowContador), lang("reportes_reporte_listdo_nomina_empleados") . ": " . $oConcepto->iNumeroEmpleados);
                
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
                    $this->iRowContador = $this->iRowContador - 4;
                }  

                $this->oPHPExcel->getActiveSheet()->getStyle('C' . ($this->iRowContador))->getAlignment()->setWrapText(true);        
                $this->oPHPExcel->getActiveSheet()->getStyle("E" . ($this->iRowContador))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                
                $this->oPHPExcel->getActiveSheet()->setCellValue("B" . ($this->iRowContador), $oConcepto->iNumeroConcepto);
                $this->oPHPExcel->getActiveSheet()->setCellValue("C" . ($this->iRowContador), $oConcepto->cNombreConcepto);
                $this->oPHPExcel->getActiveSheet()->setCellValue("D" . ($this->iRowContador), ($oConcepto->iValor) ? $oConcepto->iValor : '');
                $this->oPHPExcel->getActiveSheet()->setCellValue("E" . ($this->iRowContador), $oConcepto->fPagarGravable);
                
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
                
                $this->oPHPExcel->getActiveSheet()->getStyle('G' . ($this->iRowContador))->getAlignment()->setWrapText(true);        
                $this->oPHPExcel->getActiveSheet()->getStyle("H" . ($this->iRowContador))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                
                $this->oPHPExcel->getActiveSheet()->setCellValue("F" . ($this->iRowContador), $oConcepto->iNumeroConcepto);
                $this->oPHPExcel->getActiveSheet()->setCellValue("G" . ($this->iRowContador), $oConcepto->cNombreConcepto);
                $this->oPHPExcel->getActiveSheet()->setCellValue("H" . ($this->iRowContador), $oConcepto->fPagarGravable);
                
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

        $this->oPHPExcel->getActiveSheet()->setCellValue("A" . $this->iRowContador, lang("reportes_reporte_listdo_neto"));

        $this->oPHPExcel->getActiveSheet()->getStyle("B" . $this->iRowContador)->getFont()->setSize(8)->setBold(true);
        $this->oPHPExcel->getActiveSheet()->getStyle("E" . $this->iRowContador)->getFont()->setSize(8)->setBold(true);
        $this->oPHPExcel->getActiveSheet()->getStyle("H" . $this->iRowContador)->getFont()->setSize(8)->setBold(true);

        $this->oPHPExcel->getActiveSheet()->getStyle("B" . $this->iRowContador)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $this->oPHPExcel->getActiveSheet()->getStyle("E" . $this->iRowContador)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $this->oPHPExcel->getActiveSheet()->getStyle("H" . $this->iRowContador)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        $this->oPHPExcel->getActiveSheet()->setCellValue("B" . $this->iRowContador, ($this->fPercepciones - $this->fDeducciones));
        $this->oPHPExcel->getActiveSheet()->setCellValue("E" . $this->iRowContador, $this->fPercepciones);
        $this->oPHPExcel->getActiveSheet()->setCellValue("H" . $this->iRowContador, $this->fDeducciones);

        $this->iRowContador++;
        $this->oPHPExcel->getActiveSheet()->getRowDimension($this->iRowContador)->setRowHeight(0.75);
        $this->oPHPExcel->getActiveSheet()->getStyle('A' . ($this->iRowContador) . ':H' .($this->iRowContador))
            ->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('0000000'); 

        $this->iRowContador++;
    }
    
    private function _pintarGranTotal()
    {
        $aTotales = $this->_getTotalesConceptos();
        
        $binit = true;
        $this->iConta = 0;
        $this->iContaPer = 0;
        $this->iContaDes = 0;
        $this->fDeducciones = 0;
        $this->fPercepciones = 0;
        
        foreach($aTotales AS $oConcepto)
        {
            // colocamos el header del departamento
            if($this->iConta == 0)
            {
                $this->oPHPExcel->getActiveSheet()->getStyle('A' . $this->iRowContador)->getFont()->setSize(8)->setBold(true);
                $this->oPHPExcel->getActiveSheet()->mergeCells('A'. $this->iRowContador . ':H' . $this->iRowContador);
                $this->oPHPExcel->getActiveSheet()->setCellValue('A'. $this->iRowContador, lang("reportes_reporte_listdo_gran_total_sin_asteriscos"));
                
                $this->iRowContador++;
                $this->oPHPExcel->getActiveSheet()->getRowDimension($this->iRowContador)->setRowHeight(0.75);
                $this->oPHPExcel->getActiveSheet()->getStyle('A' . ($this->iRowContador) . ':H' .($this->iRowContador))
                    ->getFill()
                    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('0000000');      
                
                $this->iRowContador++;
                $this->oPHPExcel->getActiveSheet()->setCellValue("A" . ($this->iRowContador), lang("reportes_reporte_listdo_gran_total"));                
                
                $this->iRowContador++;
                $this->oPHPExcel->getActiveSheet()->setCellValue("A" . ($this->iRowContador), lang("reportes_reporte_listdo_empresa"));
                
                $this->iRowContador++;
                $this->oPHPExcel->getActiveSheet()->setCellValue("A" . ($this->iRowContador), lang("reportes_reporte_listdo_nomina_empleados") . ": " . $oConcepto->iNumeroEmpleados);
            }
            
            
            // colocamos pas percepciones y deducciones
            // colocamos pas percepciones y deducciones
            if($oConcepto->idTipoConcepto == CONCEPTOS_TIPO_PERCEPCION)
            {
                if($this->iContaPer == 0 && $this->iConta == 0)
                {
                    $this->iRowContador = $this->iRowContador - 2;
                }  
                
                $this->oPHPExcel->getActiveSheet()->getStyle('C' . ($this->iRowContador))->getAlignment()->setWrapText(true);        
                $this->oPHPExcel->getActiveSheet()->getStyle("E" . ($this->iRowContador))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                
                $this->oPHPExcel->getActiveSheet()->setCellValue("B" . ($this->iRowContador), $oConcepto->iNumeroConcepto);
                $this->oPHPExcel->getActiveSheet()->setCellValue("C" . ($this->iRowContador), $oConcepto->cNombreConcepto);
                $this->oPHPExcel->getActiveSheet()->setCellValue("D" . ($this->iRowContador), ($oConcepto->iValor) ? $oConcepto->iValor : '');
                $this->oPHPExcel->getActiveSheet()->setCellValue("E" . ($this->iRowContador), $oConcepto->fPagarGravable);
                
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
                
                $this->oPHPExcel->getActiveSheet()->getStyle('G' . ($this->iRowContador))->getAlignment()->setWrapText(true);        
                $this->oPHPExcel->getActiveSheet()->getStyle("H" . ($this->iRowContador))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                
                $this->oPHPExcel->getActiveSheet()->setCellValue("F" . ($this->iRowContador), $oConcepto->iNumeroConcepto);
                $this->oPHPExcel->getActiveSheet()->setCellValue("G" . ($this->iRowContador), $oConcepto->cNombreConcepto);
                $this->oPHPExcel->getActiveSheet()->setCellValue("H" . ($this->iRowContador), $oConcepto->fPagarGravable);
                
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

        $this->oPHPExcel->getActiveSheet()->setCellValue("A" . $this->iRowContador, lang("reportes_reporte_listdo_neto"));

        $this->oPHPExcel->getActiveSheet()->getStyle("B" . $this->iRowContador)->getFont()->setSize(8)->setBold(true);
        $this->oPHPExcel->getActiveSheet()->getStyle("E" . $this->iRowContador)->getFont()->setSize(8)->setBold(true);
        $this->oPHPExcel->getActiveSheet()->getStyle("H" . $this->iRowContador)->getFont()->setSize(8)->setBold(true);

        $this->oPHPExcel->getActiveSheet()->getStyle("B" . $this->iRowContador)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $this->oPHPExcel->getActiveSheet()->getStyle("E" . $this->iRowContador)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $this->oPHPExcel->getActiveSheet()->getStyle("H" . $this->iRowContador)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        $this->oPHPExcel->getActiveSheet()->setCellValue("B" . $this->iRowContador, ($this->fPercepciones - $this->fDeducciones));
        $this->oPHPExcel->getActiveSheet()->setCellValue("E" . $this->iRowContador, $this->fPercepciones);
        $this->oPHPExcel->getActiveSheet()->setCellValue("H" . $this->iRowContador, $this->fDeducciones);

        $this->iRowContador++;
        $this->oPHPExcel->getActiveSheet()->getRowDimension($this->iRowContador)->setRowHeight(0.75);
        $this->oPHPExcel->getActiveSheet()->getStyle('A' . ($this->iRowContador) . ':H' .($this->iRowContador))
            ->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('0000000'); 

        $this->iRowContador++;
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
    
    private function & _getDepartamentos()
   {
       $oNomina = new Nomina_ViewModel();
       $oNomina->idTipoNomina = $this->oReporte->idTipoNomina;
       $oNomina->idPeriodo = $this->oReporte->idPeriodo;
       
       $aDepartamentos = & $this->CI->Nomina_model->getDepartamentos($oNomina);
       
       return $aDepartamentos;
   }
}
