<?php

include_once '/../../Excel.php';
include_once '/../../ReportesData.php';

class Reporte_Factura_Cliente_Especial  {
    
    private $oReporte;
    private $oTipoNomina;
    private $oPeriodo;
    
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
            $oPHPExcel->getActiveSheet()->setCellValue("A" . $iRowDatos,$oData->iNumero);
            $oPHPExcel->getActiveSheet()->setCellValue("B" . $iRowDatos,$oData->cNombreEmpleado);
            $oPHPExcel->getActiveSheet()->setCellValue("C" . $iRowDatos,round($oData->fPago, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("D" . $iRowDatos,round($oData->fPSEjecutiva, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("E" . $iRowDatos,round($oData->fDescuentoEmpleado, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("F" . $iRowDatos,round($oData->fDescuentoUniformes, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("G" . $iRowDatos,round($oData->fDescuentoPrestamoCliente, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("H" . $iRowDatos,round($oData->fDescuentoColegiatura, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("I" . $iRowDatos,round($oData->fDescuentoEquipo, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("J" . $iRowDatos,round($oData->fDescuentoCurso, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("K" . $iRowDatos,round($oData->fDescuentoAhorro, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("L" . $iRowDatos,round($oData->fDescuentoTienda, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("M" . $iRowDatos,round($oData->fSubtotalNomina, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("N" . $iRowDatos,round($oData->fComisionHonorarios, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("O" . $iRowDatos,round($oData->fComisionHonorariosCliente, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("P" . $iRowDatos,round($oData->fIMSSPatronal, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("Q" . $iRowDatos,round($oData->fIMSSRetiroPatronal, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("R" . $iRowDatos,round($oData->fInfonavitPatronal, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("S" . $iRowDatos,round($oData->fImpuestoEstatalNominas, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("T" . $iRowDatos,round($oData->fSubTotalCargaSocial, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("U" . $iRowDatos,round($oData->fImpuestosTrabajador, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("V" . $iRowDatos,round($oData->fSubTotal, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("W" . $iRowDatos,round($oData->fMontoIva, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("X" . $iRowDatos,round($oData->fTotal, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("Y" . $iRowDatos,$oData->cNombreArea);
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
