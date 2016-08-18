<?php

include_once '/../../Excel.php';
include_once '/../../ReportesData.php';

class Reporte_Acumulados_Anuales_Resumen  {
    
    private $oReporte;
    
    public function initialize($oReporte)
    {
        $this->oReporte = $oReporte;
    }
    
    public function generarReporte()
    {
        // cabeceras de la tabla
        $iRowDatos = 8; 
        $iRowCabecera = 5;
        $iRowLineaHeader = 4;
        $iRowLineaHeader2 = 6;
        
        $oPHPExcel = new Excel();
        $oPHPExcel->getProperties()->setTitle($this->oReporte->cNombre);
        
        $oPHPExcel->getDefaultStyle()->getFont()->setName("Arial")->setSize(8);
        
        // creacion de la cabecera del reporte
        $oPHPExcel->setActiveSheetIndex(0);
        $oPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setSize(12)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("A2")->getFont()->setSize(12)->setBold(true);
        
        $oPHPExcel->getActiveSheet()->setCellValue("A1", $this->oReporte->cNombreEmpresa);
        $oPHPExcel->getActiveSheet()->setCellValue("A2", $this->oReporte->cNombre);
        
        // configuramos el width que tendra las columnas
        $oPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(12);
        $oPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(36.71);
        $oPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(12);
        $oPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(12);
        $oPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth(12);
        $oPHPExcel->getActiveSheet()->getColumnDimension("F")->setWidth(12);
        $oPHPExcel->getActiveSheet()->getColumnDimension("G")->setWidth(12);
        $oPHPExcel->getActiveSheet()->getColumnDimension("H")->setWidth(12);
        $oPHPExcel->getActiveSheet()->getColumnDimension("I")->setWidth(12);
        $oPHPExcel->getActiveSheet()->getColumnDimension("J")->setWidth(12);
        $oPHPExcel->getActiveSheet()->getColumnDimension("K")->setWidth(12);
        $oPHPExcel->getActiveSheet()->getColumnDimension("L")->setWidth(12);
        $oPHPExcel->getActiveSheet()->getColumnDimension("M")->setWidth(12);
        $oPHPExcel->getActiveSheet()->getColumnDimension("N")->setWidth(12);
        $oPHPExcel->getActiveSheet()->getColumnDimension("O")->setWidth(12);
        
        // configuramos las lineas para negras solo para diseño del reporte
        $oPHPExcel->getActiveSheet()->getRowDimension($iRowLineaHeader)->setRowHeight(0.75);
        $oPHPExcel->getActiveSheet()->getRowDimension($iRowLineaHeader2)->setRowHeight(0.75);
        
        $oPHPExcel->getActiveSheet()->getStyle("A" . $iRowLineaHeader . ":O" .$iRowLineaHeader)
                ->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('0000000');
        
        $oPHPExcel->getActiveSheet()->getStyle("A" . $iRowLineaHeader2 . ":O" .$iRowLineaHeader2)
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
        $oPHPExcel->getActiveSheet()->getStyle("I" . $iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("J" . $iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("K" . $iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("L" . $iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("M" . $iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("N" . $iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("O" . $iRowCabecera)->getFont()->setSize(8)->setBold(true);
        
        $oPHPExcel->getActiveSheet()->setCellValue("A" . $iRowCabecera, lang("reportes_reporte_acumulados_numero"));
        $oPHPExcel->getActiveSheet()->setCellValue("B" . $iRowCabecera, lang("reportes_reporte_acumulados_concepto"));
        $oPHPExcel->getActiveSheet()->setCellValue("C" . $iRowCabecera, lang("reportes_reporte_acumulados_enero"));
        $oPHPExcel->getActiveSheet()->setCellValue("D" . $iRowCabecera, lang("reportes_reporte_acumulados_febrero"));
        $oPHPExcel->getActiveSheet()->setCellValue("E" . $iRowCabecera, lang("reportes_reporte_acumulados_marzo"));
        $oPHPExcel->getActiveSheet()->setCellValue("F" . $iRowCabecera, lang("reportes_reporte_acumulados_abril"));
        $oPHPExcel->getActiveSheet()->setCellValue("G" . $iRowCabecera, lang("reportes_reporte_acumulados_mayo"));
        $oPHPExcel->getActiveSheet()->setCellValue("H" . $iRowCabecera, lang("reportes_reporte_acumulados_junio"));
        $oPHPExcel->getActiveSheet()->setCellValue("I" . $iRowCabecera, lang("reportes_reporte_acumulados_julio"));
        $oPHPExcel->getActiveSheet()->setCellValue("J" . $iRowCabecera, lang("reportes_reporte_acumulados_agosto"));
        $oPHPExcel->getActiveSheet()->setCellValue("K" . $iRowCabecera, lang("reportes_reporte_acumulados_septiembre"));
        $oPHPExcel->getActiveSheet()->setCellValue("L" . $iRowCabecera, lang("reportes_reporte_acumulados_octubre"));
        $oPHPExcel->getActiveSheet()->setCellValue("M" . $iRowCabecera, lang("reportes_reporte_acumulados_noviembre"));
        $oPHPExcel->getActiveSheet()->setCellValue("N" . $iRowCabecera, lang("reportes_reporte_acumulados_diciembre"));
        $oPHPExcel->getActiveSheet()->setCellValue("O" . $iRowCabecera, lang("reportes_reporte_acumulados_total"));
                
        // comenzamos a pintar los datos
        $aData = $this->_getData();
        $iTotalRegistrosData = count($aData);
        
        $iContaEmpleadosPorBanco = 0;
        $iContaEmpleadosPorDepartamento = 0;
        $iRowFilas = 0;
        $cAliasBancoTMP = "";
        $cNombreDepartamentoTMP = "";
        
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
            $oPHPExcel->getActiveSheet()->getStyle("I" . $iRowDatos)->getAlignment()->setWrapText(true);
            $oPHPExcel->getActiveSheet()->getStyle("J" . $iRowDatos)->getAlignment()->setWrapText(true);
            $oPHPExcel->getActiveSheet()->getStyle("K" . $iRowDatos)->getAlignment()->setWrapText(true);
            $oPHPExcel->getActiveSheet()->getStyle("L" . $iRowDatos)->getAlignment()->setWrapText(true);
            $oPHPExcel->getActiveSheet()->getStyle("M" . $iRowDatos)->getAlignment()->setWrapText(true);
            $oPHPExcel->getActiveSheet()->getStyle("N" . $iRowDatos)->getAlignment()->setWrapText(true);
            $oPHPExcel->getActiveSheet()->getStyle("O" . $iRowDatos)->getAlignment()->setWrapText(true);
             
            $oPHPExcel->getActiveSheet()->setCellValue("A" . $iRowDatos, $oData->iNumeroConcepto);
            $oPHPExcel->getActiveSheet()->setCellValue("B" . $iRowDatos, $oData->cNombreConcepto);
            $oPHPExcel->getActiveSheet()->setCellValue("C" . $iRowDatos, $oData->fMontoEnero);
            $oPHPExcel->getActiveSheet()->setCellValue("D" . $iRowDatos, $oData->fMontoFebrero);
            $oPHPExcel->getActiveSheet()->setCellValue("E" . $iRowDatos, $oData->fMontoMarzo);
            $oPHPExcel->getActiveSheet()->setCellValue("F" . $iRowDatos, $oData->fMontoAbril);
            $oPHPExcel->getActiveSheet()->setCellValue("G" . $iRowDatos, $oData->fMontoMayo);
            $oPHPExcel->getActiveSheet()->setCellValue("H" . $iRowDatos, $oData->fMontoJunio);
            $oPHPExcel->getActiveSheet()->setCellValue("I" . $iRowDatos, $oData->fMontoJulio);
            $oPHPExcel->getActiveSheet()->setCellValue("J" . $iRowDatos, $oData->fMontoAgosto);
            $oPHPExcel->getActiveSheet()->setCellValue("K" . $iRowDatos, $oData->fMontoSeptiembre);
            $oPHPExcel->getActiveSheet()->setCellValue("L" . $iRowDatos, $oData->fMontoOctubre);
            $oPHPExcel->getActiveSheet()->setCellValue("M" . $iRowDatos, $oData->fMontoNoviembre);
            $oPHPExcel->getActiveSheet()->setCellValue("N" . $iRowDatos, $oData->fMontoDiciembre);
            $oPHPExcel->getActiveSheet()->setCellValue("O" . $iRowDatos, $oData->fMontoTotal);           
            
            $iRowDatos++;
            $iRowFilas++;
        }
        
        // footer
        // pinta linea
        $oPHPExcel->getActiveSheet()->getRowDimension($iRowDatos)->setRowHeight(0.75);
        $oPHPExcel->getActiveSheet()->getStyle("A" . ($iRowDatos) . ":O" .($iRowDatos))
                ->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('0000000');
        
        // coloca totales
        $oPHPExcel->getActiveSheet()->getStyle("A" . ($iRowDatos + 1))->getFont()->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("B" . ($iRowDatos + 1))->getFont()->setBold(true);
        $oPHPExcel->getActiveSheet()->setCellValue("A" . ($iRowDatos + 1), lang("reportes_total_registros"));
        $oPHPExcel->getActiveSheet()->setCellValue("B" . ($iRowDatos + 1), $iTotalRegistrosData);
        
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
}
