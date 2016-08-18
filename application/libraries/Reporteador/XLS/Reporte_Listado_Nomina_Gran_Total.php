<?php

include_once '/../../Excel.php';
include_once '/../../ReportesData.php';

class Reporte_Listado_Nomina_Gran_Total  {
    
    private $oReporte;
    private $oPeriodo;
    private $CI;
    
    
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
        $iRowDatos = 8; 
        $iRowCabecera = 6;
        $iRowLineaHeader = 5;
        $iRowLineaHeader2 = 7;
        
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
        $oPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(17.57);
        $oPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(10);
        $oPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth(13);
        $oPHPExcel->getActiveSheet()->getColumnDimension("F")->setWidth(10);
        $oPHPExcel->getActiveSheet()->getColumnDimension("G")->setWidth(17.47);
        $oPHPExcel->getActiveSheet()->getColumnDimension("H")->setWidth(13);
        
        // configuramos las lineas para negras solo para diseño del reporte
        $oPHPExcel->getActiveSheet()->getRowDimension($iRowLineaHeader)->setRowHeight(0.75);
        $oPHPExcel->getActiveSheet()->getRowDimension($iRowLineaHeader2)->setRowHeight(0.75);
        
        
        $oPHPExcel->getActiveSheet()->getStyle("A" . $iRowLineaHeader . ":H" .$iRowLineaHeader)
                ->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('0000000');
        
        $oPHPExcel->getActiveSheet()->getStyle("A" . $iRowLineaHeader2 . ":H" .$iRowLineaHeader2)
                ->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('0000000');        
        
        $oPHPExcel->getActiveSheet()->getStyle("A" . $iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("B" . $iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("C" . $iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("D" . $iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("E" . $iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("F" . $iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("G" . $iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("H" . $iRowCabecera)->getFont()->setSize(8)->setBold(true);
        
        $oPHPExcel->getActiveSheet()->setCellValue("A" . $iRowCabecera, lang("reportes_reporte_listdo_nomina_empleado"));
        $oPHPExcel->getActiveSheet()->setCellValue("B" . $iRowCabecera, lang("reportes_reporte_listdo_nomina_numero"));
        $oPHPExcel->getActiveSheet()->setCellValue("C" . $iRowCabecera, lang("reportes_reporte_listdo_nomina_percepcion"));
        $oPHPExcel->getActiveSheet()->setCellValue("D" . $iRowCabecera, lang("reportes_reporte_listdo_nomina_dias"));
        $oPHPExcel->getActiveSheet()->setCellValue("E" . $iRowCabecera, lang("reportes_reporte_listdo_nomina_monto"));
        $oPHPExcel->getActiveSheet()->setCellValue("F" . $iRowCabecera, lang("reportes_reporte_listdo_nomina_numero"));
        $oPHPExcel->getActiveSheet()->setCellValue("G" . $iRowCabecera, lang("reportes_reporte_listdo_nomina_deduccion"));
        $oPHPExcel->getActiveSheet()->setCellValue("H" . $iRowCabecera, lang("reportes_reporte_listdo_nomina_monto"));
                
        // comenzamos a pintar los datos
        $aData = $this->_getData();
        $iConta = 0;
        $iRowConta = $iRowDatos;
        $bDeducciones = false;
        $iMaxPerY = 0;
        $iMaxDesY=0;
        $iTotalesY = 0;
        $fPercepciones = 0;
        $fDeducciones = 0;
        
        // comienza en 5 por que es la fila donde comenzara a pintarse las filas
        foreach($aData AS $oConcepto)
        {   
            if($iConta > 0)
            {
                $oPHPExcel->getActiveSheet()->setCellValue("A8", lang("reportes_reporte_listdo_gran_total"));
                $oPHPExcel->getActiveSheet()->setCellValue("A9", lang("reportes_reporte_listdo_empresa"));
                $oPHPExcel->getActiveSheet()->setCellValue("A10", lang("reportes_reporte_listdo_nomina_empleados") . ": " . $oConcepto->iNumeroEmpleados);
            }
            
            if($oConcepto->idTipoConcepto == CONCEPTOS_TIPO_PERCEPCION)
            {   
                $oPHPExcel->getActiveSheet()->setCellValue("B" . $iRowConta, $oConcepto->iNumeroConcepto);
                $oPHPExcel->getActiveSheet()->setCellValue("C" . $iRowConta, $oConcepto->cNombreConcepto);
                $oPHPExcel->getActiveSheet()->setCellValue("D" . $iRowConta, ($oConcepto->iValor) ? $oConcepto->iValor : '');
                
                $oPHPExcel->getActiveSheet()->getStyle("E" . $iRowConta)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $oPHPExcel->getActiveSheet()->setCellValue("E" . $iRowConta, $oConcepto->fPagarGravable);
                
                $fPercepciones+=$oConcepto->fPagarGravable;
                $iMaxPerY = $iRowConta;
                $iRowConta++;
            }
            else
            {
                // inicializamos las variables necesarias
                if($bDeducciones === false)
                {
                    $iRowConta = $iRowDatos;
                }
                
                $oPHPExcel->getActiveSheet()->setCellValue("F" . $iRowConta, $oConcepto->iNumeroConcepto);
                $oPHPExcel->getActiveSheet()->setCellValue("G" . $iRowConta, $oConcepto->cNombreConcepto);
                $oPHPExcel->getActiveSheet()->setCellValue("H" . $iRowConta, ($oConcepto->iValor) ? $oConcepto->iValor : '');
                
                $oPHPExcel->getActiveSheet()->getStyle("H" . $iRowConta)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $oPHPExcel->getActiveSheet()->setCellValue("H" . $iRowConta, $oConcepto->fPagarGravable);
                
                $fDeducciones+=$oConcepto->fPagarGravable;
                $iMaxDesY = $iRowConta;
                $bDeducciones = true;
                $iRowConta++;
            }
            
            $iConta++;
        }
        
        
        // agregamos los totales
        $iTotalesY = ($iMaxPerY > $iMaxDesY) ? $iMaxPerY : $iMaxDesY;
        $iTotalesY = $iTotalesY + 2;
        
        $oPHPExcel->getActiveSheet()->setCellValue("A" . $iTotalesY, lang("reportes_reporte_listdo_neto"));
        
        $oPHPExcel->getActiveSheet()->getStyle("B" . $iTotalesY)->getFont()->setSize(8)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("E" . $iTotalesY)->getFont()->setSize(8)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("H" . $iTotalesY)->getFont()->setSize(8)->setBold(true);
        
        $oPHPExcel->getActiveSheet()->getStyle("B" . $iTotalesY)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $oPHPExcel->getActiveSheet()->getStyle("E" . $iTotalesY)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $oPHPExcel->getActiveSheet()->getStyle("H" . $iTotalesY)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        
        $oPHPExcel->getActiveSheet()->setCellValue("B" . $iTotalesY, ($fPercepciones - $fDeducciones));
        $oPHPExcel->getActiveSheet()->setCellValue("E" . $iTotalesY, $fPercepciones);
        $oPHPExcel->getActiveSheet()->setCellValue("H" . $iTotalesY, $fDeducciones);

        $oPHPExcel->getActiveSheet()->getRowDimension(($iTotalesY + 1))->setRowHeight(0.75);
        
        $oPHPExcel->getActiveSheet()->getStyle("A" . ($iTotalesY + 1) . ":H" .($iTotalesY + 1))
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
    
    private function & _getData()
    {
       $oNomina = new Nomina_ViewModel();
       $oNomina->idTipoNomina = $this->oReporte->idTipoNomina;
       $oNomina->idPeriodo = $this->oReporte->idPeriodo;
       
       $aConceptos = & $this->CI->Nomina_model->getTotalesConceptos($oNomina);
       
       return $aConceptos;
    }
}
