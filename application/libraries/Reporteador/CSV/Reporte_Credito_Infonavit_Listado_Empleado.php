<?php

include_once '/../../Excel.php';
include_once '/../../ReportesData.php';

class Reporte_Credito_Infonavit_Listado_Empleado  {
    
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
            $oPHPExcel->getActiveSheet()->setCellValue("A" . $iRowDatos, $oData->iNumero);
            $oPHPExcel->getActiveSheet()->setCellValue("B" . $iRowDatos, $oData->cNombreCompleto);
            $oPHPExcel->getActiveSheet()->setCellValue("C" . $iRowDatos, $oData->cNumeroInfonavit);
            $oPHPExcel->getActiveSheet()->setCellValue("D" . $iRowDatos, $oData->dtFechaAlta);
            $oPHPExcel->getActiveSheet()->setCellValue("E" . $iRowDatos, $oData->fValor);
            $oPHPExcel->getActiveSheet()->setCellValue("F" . $iRowDatos, $oData->fDisminucion);
            $oPHPExcel->getActiveSheet()->setCellValue("G" . $iRowDatos, $oData->cNombreRegistroPatronal);
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
