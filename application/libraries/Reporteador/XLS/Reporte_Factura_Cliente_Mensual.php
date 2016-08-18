<?php

include_once '/../../Excel.php';
include_once '/../../ReportesData.php';

class Reporte_Factura_Cliente_Mensual  {
    
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
        
        $oPHPExcel->getActiveSheet()->setCellValue("A3", $this->oTipoNomina->cNombre);
        $oPHPExcel->getActiveSheet()->setCellValue("A4", "Del Mes " . $this->oReporte->iMesInicio . " al Mes " . $this->oReporte->iMesFin);
        
        // configuramos el width que tendra las columnas
        $oPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(16);
        $oPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(36.71);
        $oPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(16);
        $oPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(16);
        $oPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth(16);
        $oPHPExcel->getActiveSheet()->getColumnDimension("F")->setWidth(16);
        $oPHPExcel->getActiveSheet()->getColumnDimension("G")->setWidth(16);
        $oPHPExcel->getActiveSheet()->getColumnDimension("H")->setWidth(16);
        $oPHPExcel->getActiveSheet()->getColumnDimension("I")->setWidth(16);
        $oPHPExcel->getActiveSheet()->getColumnDimension("J")->setWidth(16);
        $oPHPExcel->getActiveSheet()->getColumnDimension("K")->setWidth(16);
        $oPHPExcel->getActiveSheet()->getColumnDimension("L")->setWidth(16);
        $oPHPExcel->getActiveSheet()->getColumnDimension("M")->setWidth(16);
        $oPHPExcel->getActiveSheet()->getColumnDimension("N")->setWidth(16);
        $oPHPExcel->getActiveSheet()->getColumnDimension("O")->setWidth(16);
        $oPHPExcel->getActiveSheet()->getColumnDimension("P")->setWidth(16);
        $oPHPExcel->getActiveSheet()->getColumnDimension("Q")->setWidth(16);
        $oPHPExcel->getActiveSheet()->getColumnDimension("R")->setWidth(16);
        $oPHPExcel->getActiveSheet()->getColumnDimension("S")->setWidth(16);
        $oPHPExcel->getActiveSheet()->getColumnDimension("T")->setWidth(16);
        $oPHPExcel->getActiveSheet()->getColumnDimension("U")->setWidth(16);
        $oPHPExcel->getActiveSheet()->getColumnDimension("V")->setWidth(16);
        $oPHPExcel->getActiveSheet()->getColumnDimension("W")->setWidth(16);
        $oPHPExcel->getActiveSheet()->getColumnDimension("X")->setWidth(16);        
        $oPHPExcel->getActiveSheet()->getColumnDimension("Y")->setWidth(16);        
        
        // configuramos las lineas para negras solo para diseño del reporte
        $oPHPExcel->getActiveSheet()->getRowDimension($iRowLineaHeader)->setRowHeight(0.75);
        $oPHPExcel->getActiveSheet()->getRowDimension($iRowLineaHeader2)->setRowHeight(0.75);
        
        $oPHPExcel->getActiveSheet()->getStyle("A" . $iRowLineaHeader . ":Y" .$iRowLineaHeader)
                ->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('0000000');
        
        $oPHPExcel->getActiveSheet()->getStyle("A" . $iRowLineaHeader2 . ":Y" .$iRowLineaHeader2)
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
        $oPHPExcel->getActiveSheet()->getStyle("P" . $iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("Q" . $iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("R" . $iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("S" . $iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("T" . $iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("U" . $iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("V" . $iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("W" . $iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("X" . $iRowCabecera)->getFont()->setSize(8)->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("Y" . $iRowCabecera)->getFont()->setSize(8)->setBold(true);
        
        $oPHPExcel->getActiveSheet()->setCellValue("A" . $iRowCabecera, lang("reportes_reporte_factura_cliente_numero"));
        $oPHPExcel->getActiveSheet()->setCellValue("B" . $iRowCabecera, lang("reportes_reporte_factura_cliente_nombre"));
        $oPHPExcel->getActiveSheet()->setCellValue("C" . $iRowCabecera, lang("reportes_reporte_factura_cliente_pago"));
        $oPHPExcel->getActiveSheet()->setCellValue("D" . $iRowCabecera, lang("reportes_reporte_factura_cliente_ps_ejecutiva"));
        $oPHPExcel->getActiveSheet()->setCellValue("E" . $iRowCabecera, lang("reportes_reporte_factura_cliente_desempleado"));
        $oPHPExcel->getActiveSheet()->setCellValue("F" . $iRowCabecera, lang("reportes_reporte_factura_cliente_desuniforme"));
        $oPHPExcel->getActiveSheet()->setCellValue("G" . $iRowCabecera, lang("reportes_reporte_factura_cliente_desprestamocliente"));
        $oPHPExcel->getActiveSheet()->setCellValue("H" . $iRowCabecera, lang("reportes_reporte_factura_cliente_descolegiatura"));
        $oPHPExcel->getActiveSheet()->setCellValue("I" . $iRowCabecera, lang("reportes_reporte_factura_cliente_desequipo"));
        $oPHPExcel->getActiveSheet()->setCellValue("J" . $iRowCabecera, lang("reportes_reporte_factura_cliente_descurso"));
        $oPHPExcel->getActiveSheet()->setCellValue("K" . $iRowCabecera, lang("reportes_reporte_factura_cliente_desahorro"));
        $oPHPExcel->getActiveSheet()->setCellValue("L" . $iRowCabecera, lang("reportes_reporte_factura_cliente_destienda"));
        $oPHPExcel->getActiveSheet()->setCellValue("M" . $iRowCabecera, lang("reportes_reporte_factura_cliente_subtotalnomina"));
        $oPHPExcel->getActiveSheet()->setCellValue("N" . $iRowCabecera, lang("reportes_reporte_factura_cliente_comisionhonorarios"));
        $oPHPExcel->getActiveSheet()->setCellValue("O" . $iRowCabecera, lang("reportes_reporte_factura_cliente_comisionhonorarioscliente"));
        $oPHPExcel->getActiveSheet()->setCellValue("P" . $iRowCabecera, lang("reportes_reporte_factura_cliente_imss_patronal"));
        $oPHPExcel->getActiveSheet()->setCellValue("Q" . $iRowCabecera, lang("reportes_reporte_factura_cliente_imss_retiro_patronal"));
        $oPHPExcel->getActiveSheet()->setCellValue("R" . $iRowCabecera, lang("reportes_reporte_factura_cliente_infonavit_patronal"));
        $oPHPExcel->getActiveSheet()->setCellValue("S" . $iRowCabecera, lang("reportes_reporte_factura_cliente_impuesto_estatal_nominas"));
        $oPHPExcel->getActiveSheet()->setCellValue("T" . $iRowCabecera, lang("reportes_reporte_factura_cliente_subtotalcargasocial"));
        $oPHPExcel->getActiveSheet()->setCellValue("U" . $iRowCabecera, lang("reportes_reporte_factura_cliente_impuestos_empleado"));
        $oPHPExcel->getActiveSheet()->setCellValue("V" . $iRowCabecera, lang("reportes_reporte_factura_cliente_subtotal"));
        $oPHPExcel->getActiveSheet()->setCellValue("W" . $iRowCabecera, lang("reportes_reporte_factura_cliente_iva"));
        $oPHPExcel->getActiveSheet()->setCellValue("X" . $iRowCabecera, lang("reportes_reporte_factura_cliente_total"));
        $oPHPExcel->getActiveSheet()->setCellValue("Y" . $iRowCabecera, lang("reportes_reporte_factura_cliente_area"));
                
        // comenzamos a pintar los datos
        $aData = $this->_getData();
        $iTotalRegistrosData = count($aData);
               
        $iRowFilas = 0;
        
        $fTotalPago = 0;
        $fTotalPSEjecutiva = 0;
        $fTotalDescuentoEmpleado = 0;
        $fTotalDescuentoUniformes = 0;
        $fTotalDescuentoPrestamoCliente = 0;
        $fTotalDescuentoColegiatura = 0;
        $fTotalDescuentoEquipo = 0;
        $fTotalDescuentoCurso = 0;
        $fTotalDescuentoAhorro = 0;
        $fTotalDescuentoTienda = 0;
        $fTotalSubtotalNomina = 0;
        $fTotalComisionHonorarios = 0;
        $fTotalComisionHonorariosCliente = 0;
        $fTotalIMSSPatronal = 0;
        $fTotalIMSSRetiroPatronal = 0;
        $fTotalInfonavitPatronal = 0;
        $fTotalImpuestoEstatalNomina=0;
        $fTotalSubTotalCargaSocial = 0;
        $fTotalImpuestosTrabajador = 0;
        $fTotalSubTotal = 0;
        $fTotalMontoIva = 0;
        $fTotalTotal = 0;
        
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
            $oPHPExcel->getActiveSheet()->getStyle("P" . $iRowDatos)->getAlignment()->setWrapText(true);
            $oPHPExcel->getActiveSheet()->getStyle("Q" . $iRowDatos)->getAlignment()->setWrapText(true);
            $oPHPExcel->getActiveSheet()->getStyle("R" . $iRowDatos)->getAlignment()->setWrapText(true);
            $oPHPExcel->getActiveSheet()->getStyle("S" . $iRowDatos)->getAlignment()->setWrapText(true);
            $oPHPExcel->getActiveSheet()->getStyle("T" . $iRowDatos)->getAlignment()->setWrapText(true);
            $oPHPExcel->getActiveSheet()->getStyle("U" . $iRowDatos)->getAlignment()->setWrapText(true);
            $oPHPExcel->getActiveSheet()->getStyle("V" . $iRowDatos)->getAlignment()->setWrapText(true);
            $oPHPExcel->getActiveSheet()->getStyle("W" . $iRowDatos)->getAlignment()->setWrapText(true);
            $oPHPExcel->getActiveSheet()->getStyle("X" . $iRowDatos)->getAlignment()->setWrapText(true);
            $oPHPExcel->getActiveSheet()->getStyle("Y" . $iRowDatos)->getAlignment()->setWrapText(true);
             
            $oPHPExcel->getActiveSheet()->setCellValue("A" . $iRowDatos,$oData->iNumero);
            $oPHPExcel->getActiveSheet()->setCellValue("B" . $iRowDatos,$oData->cNombreEmpleado);
            $oPHPExcel->getActiveSheet()->setCellValue("C" . $iRowDatos,number_format($oData->fPago, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("D" . $iRowDatos,number_format($oData->fPSEjecutiva, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("E" . $iRowDatos,number_format($oData->fDescuentoEmpleado, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("F" . $iRowDatos,number_format($oData->fDescuentoUniformes, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("G" . $iRowDatos,number_format($oData->fDescuentoPrestamoCliente, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("H" . $iRowDatos,number_format($oData->fDescuentoColegiatura, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("I" . $iRowDatos,number_format($oData->fDescuentoEquipo, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("J" . $iRowDatos,number_format($oData->fDescuentoCurso, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("K" . $iRowDatos,number_format($oData->fDescuentoAhorro, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("L" . $iRowDatos,number_format($oData->fDescuentoTienda, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("M" . $iRowDatos,number_format($oData->fSubtotalNomina, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("N" . $iRowDatos,number_format($oData->fComisionHonorarios, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("O" . $iRowDatos,number_format($oData->fComisionHonorariosCliente, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("P" . $iRowDatos,number_format($oData->fIMSSPatronal, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("Q" . $iRowDatos,number_format($oData->fIMSSRetiroPatronal, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("R" . $iRowDatos,number_format($oData->fInfonavitPatronal, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("S" . $iRowDatos,number_format($oData->fImpuestoEstatalNominas, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("T" . $iRowDatos,number_format($oData->fSubTotalCargaSocial, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("U" . $iRowDatos,number_format($oData->fImpuestosTrabajador, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("V" . $iRowDatos,number_format($oData->fSubTotal, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("W" . $iRowDatos,number_format($oData->fMontoIva, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("X" . $iRowDatos,number_format($oData->fTotal, REDONDEAR_DECIMALES));
            $oPHPExcel->getActiveSheet()->setCellValue("Y" . $iRowDatos,$oData->cNombreArea);
            
            $iRowDatos++;
            $iRowFilas++; 
            
            $fTotalPago += $oData->fPago;
            $fTotalPSEjecutiva += $oData->fPSEjecutiva;
            $fTotalDescuentoEmpleado += $oData->fDescuentoEmpleado;
            $fTotalDescuentoUniformes += $oData->fDescuentoUniformes;
            $fTotalDescuentoPrestamoCliente += $oData->fDescuentoPrestamoCliente;
            $fTotalDescuentoColegiatura += $oData->fDescuentoColegiatura;
            $fTotalDescuentoEquipo += $oData->fDescuentoEquipo;
            $fTotalDescuentoCurso += $oData->fDescuentoCurso;
            $fTotalDescuentoAhorro += $oData->fDescuentoAhorro;
            $fTotalDescuentoTienda += $oData->fDescuentoTienda;
            $fTotalSubtotalNomina += $oData->fSubtotalNomina;
            $fTotalComisionHonorarios += $oData->fComisionHonorarios;
            $fTotalComisionHonorariosCliente += $oData->fComisionHonorariosCliente;
            $fTotalIMSSPatronal += $oData->fIMSSPatronal;
            $fTotalIMSSRetiroPatronal += $oData->fIMSSRetiroPatronal;
            $fTotalInfonavitPatronal += $oData->fInfonavitPatronal;
            $fTotalImpuestoEstatalNomina += $oData->fImpuestoEstatalNominas;
            $fTotalSubTotalCargaSocial += $oData->fSubTotalCargaSocial;
            $fTotalImpuestosTrabajador += $oData->fImpuestosTrabajador;
            $fTotalSubTotal += $oData->fSubTotal;
            $fTotalMontoIva += $oData->fMontoIva;
            $fTotalTotal += $oData->fTotal;
        }
        
        // footer
        // pinta linea
        $oPHPExcel->getActiveSheet()->getRowDimension($iRowDatos)->setRowHeight(0.75);
        $oPHPExcel->getActiveSheet()->getStyle("A" . ($iRowDatos) . ":Y" .($iRowDatos))
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
        $oPHPExcel->getActiveSheet()->getStyle("I" . ($iRowDatos + 1))->getFont()->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("J" . ($iRowDatos + 1))->getFont()->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("K" . ($iRowDatos + 1))->getFont()->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("L" . ($iRowDatos + 1))->getFont()->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("M" . ($iRowDatos + 1))->getFont()->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("N" . ($iRowDatos + 1))->getFont()->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("O" . ($iRowDatos + 1))->getFont()->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("P" . ($iRowDatos + 1))->getFont()->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("Q" . ($iRowDatos + 1))->getFont()->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("R" . ($iRowDatos + 1))->getFont()->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("S" . ($iRowDatos + 1))->getFont()->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("T" . ($iRowDatos + 1))->getFont()->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("U" . ($iRowDatos + 1))->getFont()->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("V" . ($iRowDatos + 1))->getFont()->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("W" . ($iRowDatos + 1))->getFont()->setBold(true);
        $oPHPExcel->getActiveSheet()->getStyle("X" . ($iRowDatos + 1))->getFont()->setBold(true);
      
        $oPHPExcel->getActiveSheet()->setCellValue("A" . ($iRowDatos + 1), lang("reportes_total_registros"));
        $oPHPExcel->getActiveSheet()->setCellValue("B" . ($iRowDatos + 1), $iTotalRegistrosData);
        $oPHPExcel->getActiveSheet()->setCellValue("C" . ($iRowDatos + 1),number_format($fTotalPago, REDONDEAR_DECIMALES));
        $oPHPExcel->getActiveSheet()->setCellValue("D" . ($iRowDatos + 1),number_format($fTotalPSEjecutiva, REDONDEAR_DECIMALES));
        $oPHPExcel->getActiveSheet()->setCellValue("E" . ($iRowDatos + 1),number_format($fTotalDescuentoEmpleado, REDONDEAR_DECIMALES));
        $oPHPExcel->getActiveSheet()->setCellValue("F" . ($iRowDatos + 1),number_format($fTotalDescuentoUniformes, REDONDEAR_DECIMALES));
        $oPHPExcel->getActiveSheet()->setCellValue("G" . ($iRowDatos + 1),number_format($fTotalDescuentoPrestamoCliente, REDONDEAR_DECIMALES));
        $oPHPExcel->getActiveSheet()->setCellValue("H" . ($iRowDatos + 1),number_format($fTotalDescuentoColegiatura, REDONDEAR_DECIMALES));
        $oPHPExcel->getActiveSheet()->setCellValue("I" . ($iRowDatos + 1),number_format($fTotalDescuentoEquipo, REDONDEAR_DECIMALES));
        $oPHPExcel->getActiveSheet()->setCellValue("J" . ($iRowDatos + 1),number_format($fTotalDescuentoCurso, REDONDEAR_DECIMALES));
        $oPHPExcel->getActiveSheet()->setCellValue("K" . ($iRowDatos + 1),number_format($fTotalDescuentoAhorro, REDONDEAR_DECIMALES));
        $oPHPExcel->getActiveSheet()->setCellValue("L" . ($iRowDatos + 1),number_format($fTotalDescuentoTienda, REDONDEAR_DECIMALES));
        $oPHPExcel->getActiveSheet()->setCellValue("M" . ($iRowDatos + 1),number_format($fTotalSubtotalNomina, REDONDEAR_DECIMALES));
        $oPHPExcel->getActiveSheet()->setCellValue("N" . ($iRowDatos + 1),number_format($fTotalComisionHonorarios, REDONDEAR_DECIMALES));
        $oPHPExcel->getActiveSheet()->setCellValue("O" . ($iRowDatos + 1),number_format($fTotalComisionHonorariosCliente, REDONDEAR_DECIMALES));
        $oPHPExcel->getActiveSheet()->setCellValue("P" . ($iRowDatos + 1),number_format($fTotalIMSSPatronal, REDONDEAR_DECIMALES));
        $oPHPExcel->getActiveSheet()->setCellValue("Q" . ($iRowDatos + 1),number_format($fTotalIMSSRetiroPatronal, REDONDEAR_DECIMALES));
        $oPHPExcel->getActiveSheet()->setCellValue("R" . ($iRowDatos + 1),number_format($fTotalInfonavitPatronal, REDONDEAR_DECIMALES));
        $oPHPExcel->getActiveSheet()->setCellValue("S" . ($iRowDatos + 1),number_format($fTotalImpuestoEstatalNomina, REDONDEAR_DECIMALES));
        $oPHPExcel->getActiveSheet()->setCellValue("T" . ($iRowDatos + 1),number_format($fTotalSubTotalCargaSocial, REDONDEAR_DECIMALES));
        $oPHPExcel->getActiveSheet()->setCellValue("U" . ($iRowDatos + 1),number_format($fTotalImpuestosTrabajador, REDONDEAR_DECIMALES));
        $oPHPExcel->getActiveSheet()->setCellValue("V" . ($iRowDatos + 1),number_format($fTotalSubTotal, REDONDEAR_DECIMALES));
        $oPHPExcel->getActiveSheet()->setCellValue("W" . ($iRowDatos + 1),number_format($fTotalMontoIva, REDONDEAR_DECIMALES));
        $oPHPExcel->getActiveSheet()->setCellValue("X" . ($iRowDatos + 1),number_format($fTotalTotal, REDONDEAR_DECIMALES));
        
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
