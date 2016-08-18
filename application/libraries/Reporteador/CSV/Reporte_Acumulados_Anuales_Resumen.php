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
        $oPHPExcel = new Excel();
        $oPHPExcel->getProperties()->setTitle($this->oReporte->cNombre);
        
        // creacion de la cabecera del reporte
        $oPHPExcel->setActiveSheetIndex(0);
        
        // comenzamos a pintar los datos
        $aData = $this->_getData();        
        $iRowDatos = 1; 
        
        // comienza en 5 por que es la fila donde comenzara a pintarse las filas
        foreach($aData AS $oData)
        {    
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
        }
        
        header('Content-Type:application/csv;charset=UTF-8');
        header('Content-Disposition: attachment;filename="' . $this->oReporte->cNombre . '".csv');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($oPHPExcel, 'CSV');
        $objWriter->setUseBOM(true);
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
