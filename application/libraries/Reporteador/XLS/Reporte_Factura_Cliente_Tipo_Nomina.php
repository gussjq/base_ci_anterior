<?php

include_once '/../../Excel.php';
include_once '/../../ReportesData.php';

class Reporte_Factura_Cliente_Tipo_Nomina  {
    
    private $oReporte;
    private $oTipoNomina;
    private $CI;
    
    public function __construct() 
    {
        $this->CI = &get_instance();
    }
    
    public function initialize($oReporte)
    {
        $this->oReporte = $oReporte;
        $this->oTipoNomina = $this->_getTipoNomina();
    }
    
    public function generarReporte()
    {
        // cabeceras de la tabla
        $iRowDatos = 10; 
        $iRowCabecera = 7;
        $iRowLineaHeader = 6;
        $iRowLineaHeader2 = 8;
        
        $oPHPExcel = new Excel();
        $oPHPExcel->getProperties()->setTitle($this->oReporte->cNombre);
        
        $oPHPExcel->getDefaultStyle()->getFont()->setName("Arial")->setSize(8);
        
        // creacion de la cabecera del reporte
        $oPHPExcel->setActiveSheetIndex(0);
        $oPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setSize(12)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("A2")->getFont()->setSize(12)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("A3")->getFont()->setSize(10)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("A4")->getFont()->setSize(10)->setBold(true);
        
        $oPHPExcel->getActiveSheet()->setCellValue("A1", $this->oReporte->cNombreEmpresa);
        $oPHPExcel->getActiveSheet()->setCellValue("A2", $this->oReporte->cNombre);
        
        $oPHPExcel->getActiveSheet()->setCellValue("A4", "Del Mes " . $this->oReporte->iMesInicio . " al Mes " . $this->oReporte->iMesFin);
        
        // configuramos el width que tendra las columnas
        $oPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(36.71);
        $oPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(16);
        $oPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(16);
        $oPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(16);
        $oPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth(16);
        $oPHPExcel->getActiveSheet()->getColumnDimension("F")->setWidth(16);
        $oPHPExcel->getActiveSheet()->getColumnDimension("G")->setWidth(16);
        $oPHPExcel->getActiveSheet()->getColumnDimension("H")->setWidth(16);
        
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
        
        
        $oPHPExcel->getActiveSheet()->setCellValue("A" . $iRowCabecera, lang("reportes_reporte_factura_cliente_tipo_nomina"));
        $oPHPExcel->getActiveSheet()->setCellValue("B" . $iRowCabecera, lang("reportes_reporte_factura_cliente_subtotalnomina"));
        $oPHPExcel->getActiveSheet()->setCellValue("C" . $iRowCabecera, lang("reportes_reporte_factura_cliente_porcentaje_comision"));
        $oPHPExcel->getActiveSheet()->setCellValue("D" . $iRowCabecera, lang("reportes_reporte_factura_cliente_comisionhonorarios"));
        $oPHPExcel->getActiveSheet()->setCellValue("E" . $iRowCabecera, lang("reportes_reporte_factura_cliente_subtotal"));
        $oPHPExcel->getActiveSheet()->setCellValue("F" . $iRowCabecera, lang("reportes_reporte_factura_cliente_iva"));
        $oPHPExcel->getActiveSheet()->setCellValue("G" . $iRowCabecera, lang("reportes_reporte_factura_cliente_total"));
        $oPHPExcel->getActiveSheet()->setCellValue("H" . $iRowCabecera, lang("reportes_reporte_factura_cliente_empleados_activos"));
                
        // comenzamos a pintar los datos
        $aData = $this->_getData();
        $iTotalRegistrosData = count($aData);
               
        $iRowFilas = 0;
        
        $fTotalSubTotalNomina = 0;
        $fTotalPorcentajeComision = 0;
        $fTotalComisionHonorarios = 0;
        $fTotalSubTotal = 0;
        $fTotalIva = 0;
        $fTotalTotal = 0;
        $fTotalEmpleados = 0;
        
        // comienza en 5 por que es la fila donde comenzara a pintarse las filas
        foreach($aData AS $oData)
        {   
            $oPHPExcel->getActiveSheet()->getStyle("A" . $iRowDatos)->getAlignment()->setWrapText(true);
            $oPHPExcel->getActiveSheet()->getStyle("B" . $iRowDatos)->getAlignment()->setWrapText(true);
            $oPHPExcel->getActiveSheet()->getStyle("C" . $iRowDatos)->getAlignment()->setWrapText(true);
            $oPHPExcel->getActiveSheet()->getStyle("D" . $iRowDatos)->getAlignment()->setWrapText(true);
            $oPHPExcel->getActiveSheet()->getStyle("E" . $iRowDatos)->getAlignment()->setWrapText(true);
            $oPHPExcel->getActiveSheet()->getStyle("F" . $iRowDatos)->getAlignment()->setWrapText(true);
            $oPHPExcel->getActiveSheet()->getStyle("G" . $iRowDatos)->getAlignment()->setWrapText(true);
            $oPHPExcel->getActiveSheet()->getStyle("H" . $iRowDatos)->getAlignment()->setWrapText(true);
             
            $oPHPExcel->getActiveSheet()->setCellValue("A" . $iRowDatos,$oData->cNombreTipoNomina);
            $oPHPExcel->getActiveSheet()->setCellValue("B" . $iRowDatos,$oData->fSubtotalNomina);
            $oPHPExcel->getActiveSheet()->setCellValue("C" . $iRowDatos,number_format($oData->fPorcentajeComision, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("D" . $iRowDatos,number_format($oData->fComisionHonorarios, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("E" . $iRowDatos,number_format($oData->fSubTotal, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("F" . $iRowDatos,number_format($oData->fMontoIva, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("G" . $iRowDatos,number_format($oData->fTotal, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("H" . $iRowDatos,number_format($oData->iTotalEmpleados, REDONDEAR_DECIMALES));
            
            $iRowDatos++;
            $iRowFilas++;     
            
            $fTotalSubTotalNomina += $oData->fSubtotalNomina;
            $fTotalPorcentajeComision += $oData->fPorcentajeComision;
            $fTotalComisionHonorarios += $oData->fComisionHonorarios;
            $fTotalSubTotal += $oData->fSubTotal;
            $fTotalIva += $oData->fMontoIva;
            $fTotalTotal += $oData->fTotal;
            $fTotalEmpleados += $oData->iTotalEmpleados;
        }
        
        // footer
        // pinta linea
        $oPHPExcel->getActiveSheet()->getRowDimension($iRowDatos)->setRowHeight(0.75);
        $oPHPExcel->getActiveSheet()->getStyle("A" . ($iRowDatos) . ":H" .($iRowDatos))
                ->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('0000000');
        
        // coloca totales
        $oPHPExcel->getActiveSheet()->getStyle("A" . ($iRowDatos + 1))->getFont()->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("B" . ($iRowDatos + 1))->getFont()->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("C" . ($iRowDatos + 1))->getFont()->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("D" . ($iRowDatos + 1))->getFont()->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("E" . ($iRowDatos + 1))->getFont()->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("F" . ($iRowDatos + 1))->getFont()->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("G" . ($iRowDatos + 1))->getFont()->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("H" . ($iRowDatos + 1))->getFont()->setBold(true);
        
        $oPHPExcel->getActiveSheet()->setCellValue("A" . ($iRowDatos + 1), lang("reportes_total_registros") . " ".$iTotalRegistrosData);
        $oPHPExcel->getActiveSheet()->setCellValue("B" . ($iRowDatos + 1), number_format($fTotalSubTotalNomina, REDONDEAR_DECIMALES));
        $oPHPExcel->getActiveSheet()->setCellValue("C" . ($iRowDatos + 1), number_format($fTotalPorcentajeComision, REDONDEAR_DECIMALES));
        $oPHPExcel->getActiveSheet()->setCellValue("D" . ($iRowDatos + 1), number_format($fTotalComisionHonorarios, REDONDEAR_DECIMALES));
        $oPHPExcel->getActiveSheet()->setCellValue("E" . ($iRowDatos + 1), number_format($fTotalSubTotal, REDONDEAR_DECIMALES));
        $oPHPExcel->getActiveSheet()->setCellValue("F" . ($iRowDatos + 1), number_format($fTotalIva, REDONDEAR_DECIMALES));
        $oPHPExcel->getActiveSheet()->setCellValue("G" . ($iRowDatos + 1), number_format($fTotalTotal, REDONDEAR_DECIMALES));
        $oPHPExcel->getActiveSheet()->setCellValue("H" . ($iRowDatos + 1), number_format($fTotalEmpleados, REDONDEAR_DECIMALES));
        
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $this->oReporte->cNombre . '".xls');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($oPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }
    
    private function & _getData()
    {
        $oReporteData = new ReportesData();
        $oReporteData->initialize($this->oReporte);
        $aDataReporte = & $oReporteData->getDataReporte();
        return $aDataReporte;
    }
    
    private function _getTipoNomina()
    {
        $this->CI->load->model("contratacion/TipoNomina_model");
        $oTipoNomina = $this->CI->TipoNomina_model->find(array(
            "idTipoNomina" => $this->oReporte->idTipoNomina
        ));
        
        return $oTipoNomina;
    }
}
