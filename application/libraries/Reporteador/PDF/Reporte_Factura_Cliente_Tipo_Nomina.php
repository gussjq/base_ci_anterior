<?php

include_once '/../tcpdf/tcpdf.php';
include_once '/../../ReportesData.php';

class Reporte_Factura_Cliente_Tipo_Nomina extends TCPDF {
    
    private $oReporte;
    private $bFill = 0;
    private $iPrimerRow = 45;
    private $oTipoNomina;
    
    public function __construct($orientation = 'L', $unit = 'mm', $format = 'A4', $unicode = true, $encoding = 'UTF-8', $diskcache = false)
    {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache);
        
        $this->CI = &get_instance();
    }

    public function initialize($oReporte)
    {
        $this->oReporte = $oReporte;
    }
    
    /**
     * 
     */
    public function Header() 
    {
        $this->SetY(5);
        $this->SetFontSize(16);
        $this->SetFont(PDF_FONT_NAME_MAIN, "B");
        $this->Cell(190, 10, $this->oReporte->cNombreEmpresa, $this->bFill, 1, '', 0, '', 0);
        
        $this->SetY(12);
        $this->SetFontSize(13);
        $this->Cell(190, 10, $this->oReporte->cNombre, $this->bFill, 1, '', 0, '', 0);
        
        $this->SetY(18);
        $this->SetFontSize(13);
        $this->Cell(190, 10, "Del Mes ".$this->oReporte->iMesInicio . " al Mes " . $this->oReporte->iMesFin, $this->bFill, 1, '', 0, '', 0);        
        
        $this->SetFontSize(8);
        $this->writeHTMLCell(273, 1, 10, 35, "<hr />", 0, 1, 0, 1);
        
        $iIncrementarHeigth = 4;
        $rowcount = max(
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_tipo_nomina"),50),
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_subtotalnomina"),30),
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_porcentaje_comision"),30),
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_comisionhonorarios"),30),
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_subtotal"),30),
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_iva"),30),
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_total"),30),
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_empleados_activos"),30)
            ); 
        
        $iHeigth = ($rowcount * $iIncrementarHeigth);
        $this->SetY(37);
        
        $this->MultiCell(50, $iHeigth, lang("reportes_reporte_factura_cliente_tipo_nomina"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(30, $iHeigth, lang("reportes_reporte_factura_cliente_subtotalnomina"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(30, $iHeigth, lang("reportes_reporte_factura_cliente_porcentaje_comision"), $this->bFill, 'C', 0, 0);        
        $this->MultiCell(30, $iHeigth, lang("reportes_reporte_factura_cliente_comisionhonorarios"), $this->bFill, 'C', 0, 0);        
        $this->MultiCell(30, $iHeigth, lang("reportes_reporte_factura_cliente_subtotal"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(30, $iHeigth, lang("reportes_reporte_factura_cliente_iva"), $this->bFill, 'C', 0, 0);        
        $this->MultiCell(30, $iHeigth, lang("reportes_reporte_factura_cliente_total"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(30, $iHeigth, lang("reportes_reporte_factura_cliente_empleados_activos"), $this->bFill, 'C', 0, 0);
        
        $this->writeHTMLCell(273, 1, 10, 42, "<hr />", 0, 1, 0, 1);
    }


    /**
     * Metodo que se encarga de generar el cuerpo del reporte solicitado
     */
    public function generarReporte()
    {
        // recuperamos los empleados que apareceran en el reporte 
        $aEmpleados = & $this->_getData();
        $iContaEmpleados = count($aEmpleados);
        
        // configuramos los datos 
        $this->SetFontSize(8);
        $this->AddPage('L', 'A4');
        
        $iIncrementarHeigth = 4;
        $this->SetY($this->iPrimerRow);
        $iContaRows = 0;
        $iHeigth = 0;
        
        $fTotalSubTotalNomina = 0;
        $fTotalPorcentajeComision = 0;
        $fTotalComisionHonorarios = 0;
        $fTotalSubTotal = 0;
        $fTotalIva = 0;
        $fTotalTotal = 0;
        $fTotalEmpleados = 0;
        
        foreach($aEmpleados AS $oEmpleado)
        {
            if($this->checkPageBreak(15,'',true))
            {
                $this->SetY($this->iPrimerRow);
            }
            
            // recuperamos el valor mas alto de la celda
            $rowcount = max(
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_tipo_nomina"),30),
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_subtotalnomina"),50),
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_porcentaje_comision"),30),
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_comisionhonorarios"),30),
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_subtotal"),30),
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_iva"),30),
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_total"),30),
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_empleados_activos"),30)
            ); 

            $this->SetFontSize(8);
            $this->SetFont(PDF_FONT_NAME_MAIN);
            
            $iHeigth = ($rowcount * $iIncrementarHeigth);
            
            $this->MultiCell(50,$iHeigth,$oEmpleado->cNombreTipoNomina,$this->bFill,'L',0,0);
            $this->MultiCell(30,$iHeigth,number_format($oEmpleado->fSubtotalNomina, REDONDEAR_DECIMALES),$this->bFill,'C',0,0);
            $this->MultiCell(30,$iHeigth,number_format($oEmpleado->fPorcentajeComision, REDONDEAR_DECIMALES),$this->bFill,'C',0,0);
            $this->MultiCell(30,$iHeigth,number_format($oEmpleado->fComisionHonorarios, REDONDEAR_DECIMALES),$this->bFill,'C',0,0);
            $this->MultiCell(30,$iHeigth,number_format($oEmpleado->fSubTotal, REDONDEAR_DECIMALES),$this->bFill,'C',0,0);
            $this->MultiCell(30,$iHeigth,number_format($oEmpleado->fMontoIva, REDONDEAR_DECIMALES),$this->bFill,'C',0,0);
            $this->MultiCell(30,$iHeigth,number_format($oEmpleado->fTotal, REDONDEAR_DECIMALES),$this->bFill,'C',0,0);
            $this->MultiCell(30,$iHeigth,$oEmpleado->iTotalEmpleados,$this->bFill,'C',0,0);
            
            $this->Ln(); 
            $iContaRows++;
            
            $fTotalSubTotalNomina += $oEmpleado->fSubtotalNomina;
            $fTotalPorcentajeComision += $oEmpleado->fPorcentajeComision;
            $fTotalComisionHonorarios += $oEmpleado->fComisionHonorarios;
            $fTotalSubTotal += $oEmpleado->fSubTotal;
            $fTotalIva += $oEmpleado->fMontoIva;
            $fTotalTotal += $oEmpleado->fTotal;
            $fTotalEmpleados += $oEmpleado->iTotalEmpleados;
        }  
        
        $this->SetFontSize(8);


        $this->writeHTMLCell(275, 1, 10, $this->GetY(), "<hr />", 0, 1, 0, 1);
        $this->SetFont(PDF_FONT_NAME_MAIN, "B");
        
        // se agrega la etiqueta de total de registros
        $this->MultiCell(30, $iHeigth, lang("reportes_total_registros"), $this->bFill, 'C', 0, 0);
        
        // se agrega el total de los registros
        $this->MultiCell(20, $iHeigth, $iContaEmpleados, $this->bFill, 'C', 0, 0);        
        $this->MultiCell(30, $iHeigth, number_format($fTotalSubTotalNomina, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);        
        $this->MultiCell(30, $iHeigth, number_format($fTotalPorcentajeComision, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);        
        $this->MultiCell(30, $iHeigth, number_format($fTotalComisionHonorarios, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);        
        $this->MultiCell(30, $iHeigth, number_format($fTotalSubTotal, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);        
        $this->MultiCell(30, $iHeigth, number_format($fTotalIva, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);        
        $this->MultiCell(30, $iHeigth, number_format($fTotalTotal, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);        
        $this->MultiCell(30, $iHeigth, $fTotalEmpleados, $this->bFill, 'C', 0, 0);        
        
        $this->Output($this->oReporte->cNombre . '.pdf', 'I');
    }
    
    /**
     * Metodo que sobre escribe la funcionalidad del diseño del footer que esta en la libreria tcpdf
     */
    public function Footer() 
    {   
        $ormargins = $this->getOriginalMargins();
        $this->SetTextColor(0, 0, 0);
        
        //set style for cell border
        $line_width = 0.85 / $this->getScaleFactor();
        $this->SetLineStyle(array('width' => $line_width, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));   
        $pagenumtxt = $this->l['w_page'] . ' ' . $this->getAliasNumPage() . ' / ' . $this->getAliasNbPages();
        
        $this->SetY(200);
        $this->SetFontSize(8);
        $this->SetX($ormargins['left']);
        $this->Cell(0, 0, $pagenumtxt, 'T', 0, 'R');
        $this->SetX($ormargins['right']);
        $this->Cell(0, 0, date("Y-m-d H:i:s"), 'T', 0, 'L');
        
        $this->SetX($ormargins['right'] + 30);
        $this->Cell(0, 0, $this->oReporte->cNombreUsuario, 'T', 0, 'L');
    }
    
    
    private function & _getData()
    {
        $oReporteData = new ReportesData();
        $oReporteData->initialize($this->oReporte);
        $aDataReporte = & $oReporteData->getDataReporte();
        return $aDataReporte;
    }
}
