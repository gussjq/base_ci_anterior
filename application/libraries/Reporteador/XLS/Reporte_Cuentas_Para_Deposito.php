<?php

include_once '/../../Excel.php';
include_once '/../../ReportesData.php';

class Reporte_Cuentas_Para_Deposito  {
    
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
        $oPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(17.57);
        $oPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(36.71);
        $oPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(25);
        $oPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(25);
        $oPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth(17.57);
        $oPHPExcel->getActiveSheet()->getColumnDimension("F")->setWidth(17.57);
        
        // configuramos las lineas para negras solo para diseño del reporte
        $oPHPExcel->getActiveSheet()->getRowDimension($iRowLineaHeader)->setRowHeight(0.75);
        $oPHPExcel->getActiveSheet()->getRowDimension($iRowLineaHeader2)->setRowHeight(0.75);
        
        $oPHPExcel->getActiveSheet()->getStyle("A" . $iRowLineaHeader . ":F" .$iRowLineaHeader)
                ->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('0000000');
        
        $oPHPExcel->getActiveSheet()->getStyle("A" . $iRowLineaHeader2 . ":F" .$iRowLineaHeader2)
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
        
        $oPHPExcel->getActiveSheet()->setCellValue("A" . $iRowCabecera, lang("reportes_reporte_empleado_numero"));
        $oPHPExcel->getActiveSheet()->setCellValue("B" . $iRowCabecera, lang("reportes_reporte_empleado_nombre"));
        $oPHPExcel->getActiveSheet()->setCellValue("C" . $iRowCabecera, lang("reportes_reporte_empleado_cuenta"));
        $oPHPExcel->getActiveSheet()->setCellValue("D" . $iRowCabecera, lang("reportes_reporte_empleado_cuenta_interbancaria"));
        $oPHPExcel->getActiveSheet()->setCellValue("E" . $iRowCabecera, lang("reportes_reporte_empleado_nombre_banco"));
        $oPHPExcel->getActiveSheet()->setCellValue("F" . $iRowCabecera, lang("reportes_reporte_empleado_forma_pago"));
                
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
            if($cAliasBancoTMP != $oData->cAliasBanco)
            {
                if($iContaEmpleadosPorBanco > 0)
                { 
                    $oPHPExcel->getActiveSheet()->getStyle("A" . $iRowDatos)->getFont()->setBold(true);
                    $oPHPExcel->getActiveSheet()->setCellValue("A" . $iRowDatos, $iContaEmpleadosPorBanco);
                    $iRowDatos++;
                    $iContaEmpleadosPorBanco = 0;
                }
                
                $oPHPExcel->getActiveSheet()->mergeCells("B" .$iRowDatos . ":F".$iRowDatos);
                
                $oPHPExcel->getActiveSheet()->getStyle("A" . $iRowDatos)->getFont()->setBold(true);
                $oPHPExcel->getActiveSheet()->getStyle("B" . $iRowDatos)->getFont()->setBold(true);
                $oPHPExcel->getActiveSheet()->setCellValue("A" . $iRowDatos, lang("reportes_reporte_empleado_banco"));
                $oPHPExcel->getActiveSheet()->setCellValue("B" . $iRowDatos, $oData->cAliasBanco);
                $iRowDatos++;
                $iContaEmpleadosPorDepartamento = 0;
                $cNombreDepartamentoTMP = "";
            }
            
            if($cNombreDepartamentoTMP != $oData->cNombreDepartamento)
            {
                if($iContaEmpleadosPorDepartamento > 0)
                { 
                    $oPHPExcel->getActiveSheet()->getStyle("A" . $iRowDatos)->getFont()->setBold(true);
                    $oPHPExcel->getActiveSheet()->setCellValue("A" . $iRowDatos, $iContaEmpleadosPorDepartamento);
                    $iRowDatos++;
                    $iContaEmpleadosPorDepartamento = 0;
                }
                
                $oPHPExcel->getActiveSheet()->mergeCells("B" .$iRowDatos . ":F".$iRowDatos);
                
                $oPHPExcel->getActiveSheet()->getStyle("A" . $iRowDatos)->getFont()->setBold(true);
                $oPHPExcel->getActiveSheet()->getStyle("B" . $iRowDatos)->getFont()->setBold(true);
                $oPHPExcel->getActiveSheet()->setCellValue("A" . $iRowDatos, lang("reportes_reporte_empleado_tag_departamento"));
                $oPHPExcel->getActiveSheet()->setCellValue("B" . $iRowDatos, $oData->cNombreDepartamento);
                $iRowDatos++;
            }
            
            $oPHPExcel->getActiveSheet()->getStyle("A" . $iRowDatos)->getAlignment()->setWrapText(true);
            $oPHPExcel->getActiveSheet()->getStyle("B" . $iRowDatos)->getAlignment()->setWrapText(true);
            $oPHPExcel->getActiveSheet()->getStyle("C" . $iRowDatos)->getAlignment()->setWrapText(true);
            $oPHPExcel->getActiveSheet()->getStyle("D" . $iRowDatos)->getAlignment()->setWrapText(true);
            $oPHPExcel->getActiveSheet()->getStyle("E" . $iRowDatos)->getAlignment()->setWrapText(true);
            $oPHPExcel->getActiveSheet()->getStyle("F" . $iRowDatos)->getAlignment()->setWrapText(true);
             
            $oPHPExcel->getActiveSheet()->setCellValue("A" . $iRowDatos, $oData->iNumero);
            $oPHPExcel->getActiveSheet()->setCellValue("B" . $iRowDatos, $oData->cNombreCompleto);
            
            $oPHPExcel->getActiveSheet()->setCellValueExplicit("C" . $iRowDatos, $oData->cCuentaBancaria,PHPExcel_Cell_DataType::TYPE_STRING);
            $oPHPExcel->getActiveSheet()->setCellValueExplicit("D" . $iRowDatos, $oData->cCuentaInterbancaria,PHPExcel_Cell_DataType::TYPE_STRING);
            
            $oPHPExcel->getActiveSheet()->setCellValue("E" . $iRowDatos, $oData->cAliasBanco);
            $oPHPExcel->getActiveSheet()->setCellValue("F" . $iRowDatos, $oData->cNombreFormaPago);            
            
            $iRowDatos++;
            $iRowFilas++;
            $iContaEmpleadosPorBanco++;
            $iContaEmpleadosPorDepartamento++;
            
            $cAliasBancoTMP = $oData->cAliasBanco;
            $cNombreDepartamentoTMP = $oData->cNombreDepartamento;
        }
        
        if($iRowFilas == $iTotalRegistrosData)
        {
           $oPHPExcel->getActiveSheet()->getStyle("A" . $iRowDatos)->getFont()->setBold(true);
           $oPHPExcel->getActiveSheet()->setCellValue("A" . $iRowDatos, $iContaEmpleadosPorDepartamento);
           $iRowDatos++;
           $iContaEmpleadosPorBanco = 0;    
        }
        
        // footer
        // pinta linea
        $oPHPExcel->getActiveSheet()->getRowDimension($iRowDatos)->setRowHeight(0.75);
        $oPHPExcel->getActiveSheet()->getStyle("A" . ($iRowDatos) . ":F" .($iRowDatos))
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
