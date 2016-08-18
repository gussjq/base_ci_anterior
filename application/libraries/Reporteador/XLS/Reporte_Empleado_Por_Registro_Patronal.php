<?php

include_once '/../../Excel.php';
include_once '/../../ReportesData.php';

class Reporte_Empleado_Por_Registro_Patronal  {
    
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
        $oPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(17.57);
        $oPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(17.57);
        $oPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth(36.71);
        
        // configuramos las lineas para negras solo para diseño del reporte
        $oPHPExcel->getActiveSheet()->getRowDimension($iRowLineaHeader)->setRowHeight(0.75);
        $oPHPExcel->getActiveSheet()->getRowDimension($iRowLineaHeader2)->setRowHeight(0.75);
        
        $oPHPExcel->getActiveSheet()->getStyle("A" . $iRowLineaHeader . ":E" .$iRowLineaHeader)
                ->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('0000000');
        
        $oPHPExcel->getActiveSheet()->getStyle("A" . $iRowLineaHeader2 . ":E" .$iRowLineaHeader2)
                ->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('0000000');
        
        $oPHPExcel->getActiveSheet()->getStyle("A" . $iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("B" . $iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("C" . $iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("D" . $iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("E" . $iRowCabecera)->getFont()->setSize(8)->setBold(true);
        
        $oPHPExcel->getActiveSheet()->setCellValue("A" . $iRowCabecera, lang("reportes_reporte_empleado_numero"));
        $oPHPExcel->getActiveSheet()->setCellValue("B" . $iRowCabecera, lang("reportes_reporte_empleado_nombre"));
        $oPHPExcel->getActiveSheet()->setCellValue("C" . $iRowCabecera, lang("reportes_reporte_empleado_puesto"));
        $oPHPExcel->getActiveSheet()->setCellValue("D" . $iRowCabecera, lang("reportes_reporte_empleado_imss"));
        $oPHPExcel->getActiveSheet()->setCellValue("E" . $iRowCabecera, lang("reportes_reporte_empleado_registro_patronal"));
                
        // comenzamos a pintar los datos
        $aData = $this->_getData();
        $iTotalRegistrosData = count($aData);
        
        $iContaNominas = 0;
        $iRowFilas = 0;
        $cNombreTipoNominaTMP = "";
        
        // comienza en 5 por que es la fila donde comenzara a pintarse las filas
        foreach($aData AS $oData)
        {
            if($cNombreTipoNominaTMP != $oData->cNombreTipoNomina)
            {
                if($iContaNominas > 0)
                { 
                    $oPHPExcel->getActiveSheet()->getStyle("A" . $iRowDatos)->getFont()->setBold(true);
                    $oPHPExcel->getActiveSheet()->setCellValue("A" . $iRowDatos, $iContaNominas);
                    $iRowDatos++;
                    $iContaNominas = 0;
                }
                
                $oPHPExcel->getActiveSheet()->mergeCells("B" .$iRowDatos . ":E".$iRowDatos);
                
                $oPHPExcel->getActiveSheet()->getStyle("A" . $iRowDatos)->getFont()->setBold(true);
                $oPHPExcel->getActiveSheet()->getStyle("B" . $iRowDatos)->getFont()->setBold(true);
                $oPHPExcel->getActiveSheet()->setCellValue("A" . $iRowDatos, lang("reportes_reporte_empleado_tipo_de_nomina"));
                $oPHPExcel->getActiveSheet()->setCellValue("B" . $iRowDatos, $oData->cNombreTipoNomina);
                $iRowDatos++;
            }
            
            $oPHPExcel->getActiveSheet()->getStyle("A" . $iRowDatos)->getAlignment()->setWrapText(true);
            $oPHPExcel->getActiveSheet()->getStyle("B" . $iRowDatos)->getAlignment()->setWrapText(true);
            $oPHPExcel->getActiveSheet()->getStyle("C" . $iRowDatos)->getAlignment()->setWrapText(true);
            $oPHPExcel->getActiveSheet()->getStyle("D" . $iRowDatos)->getAlignment()->setWrapText(true);
            $oPHPExcel->getActiveSheet()->getStyle("E" . $iRowDatos)->getAlignment()->setWrapText(true);
             
            $oPHPExcel->getActiveSheet()->setCellValue("A" . $iRowDatos, $oData->iNumero);
            $oPHPExcel->getActiveSheet()->setCellValue("B" . $iRowDatos, $oData->cNombreCompleto);
            $oPHPExcel->getActiveSheet()->setCellValue("C" . $iRowDatos, $oData->cNombrePuesto);
            $oPHPExcel->getActiveSheet()->setCellValue("D" . $iRowDatos, $oData->cIMSS);
            $oPHPExcel->getActiveSheet()->setCellValue("E" . $iRowDatos, $oData->cNombreRegistroPatronal);
            
            $iRowDatos++;
            $iRowFilas++;
            $iContaNominas++;
            
            $cNombreTipoNominaTMP = $oData->cNombreTipoNomina;
        }
        
        if($iRowFilas == $iTotalRegistrosData)
        {
           $oPHPExcel->getActiveSheet()->getStyle("A" . $iRowDatos)->getFont()->setBold(true);
           $oPHPExcel->getActiveSheet()->setCellValue("A" . $iRowDatos, $iContaNominas);
           $iRowDatos++;
           $iContaNominas = 0;    
        }
        
        // footer
        // pinta linea
        $oPHPExcel->getActiveSheet()->getRowDimension($iRowDatos)->setRowHeight(0.75);
        $oPHPExcel->getActiveSheet()->getStyle("A" . ($iRowDatos) . ":E" .($iRowDatos))
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
