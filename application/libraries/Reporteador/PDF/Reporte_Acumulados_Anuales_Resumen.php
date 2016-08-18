<?php

include_once '/../tcpdf/tcpdf.php';
include_once '/../../ReportesData.php';

class Reporte_Acumulados_Anuales_Resumen extends TCPDF {
    
    private $oReporte;
    
    public function __construct($orientation = 'P', $unit = 'mm', $format = 'A4', $unicode = true, $encoding = 'UTF-8', $diskcache = false)
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
        $bFill = 0;
        $this->SetY(5);
        $this->SetFontSize(16);
        $this->SetFont(PDF_FONT_NAME_MAIN, "B");
        $this->Cell(190, 10, $this->oReporte->cNombreEmpresa, 0, 1, '', 0, '', 0);
        
        $this->SetY(12);
        $this->SetFontSize(13);
        $this->Cell(190, 10, $this->oReporte->cNombre, 0, 1, '', 0, '', 0);
        
        $this->SetFontSize(8);
        $this->Ln();
        $this->writeHTMLCell(278, 1, 10, 30, "<hr />", 0, 1, 0, 1);
        
        $this->SetY(32);
        $this->MultiCell(10, 4, lang("reportes_reporte_acumulados_numero"), $bFill, 'R', 0, 0);
        $this->MultiCell(50, 4, lang("reportes_reporte_acumulados_concepto"), $bFill, 'C', 0, 0);
        $this->MultiCell(17, 4, lang("reportes_reporte_acumulados_enero"), $bFill, 'R', 0, 0);
        $this->MultiCell(17, 4, lang("reportes_reporte_acumulados_febrero"), $bFill, 'R', 0, 0);
        $this->MultiCell(17, 4, lang("reportes_reporte_acumulados_marzo"), $bFill, 'R', 0, 0);
        $this->MultiCell(17, 4, lang("reportes_reporte_acumulados_abril"), $bFill, 'R', 0, 0);
        $this->MultiCell(17, 4, lang("reportes_reporte_acumulados_mayo"), $bFill, 'R', 0, 0);
        $this->MultiCell(17, 4, lang("reportes_reporte_acumulados_junio"), $bFill, 'R', 0, 0);
        $this->MultiCell(17, 4, lang("reportes_reporte_acumulados_julio"), $bFill, 'R', 0, 0);
        $this->MultiCell(17, 4, lang("reportes_reporte_acumulados_agosto"), $bFill, 'R', 0, 0);
        $this->MultiCell(17, 4, lang("reportes_reporte_acumulados_septiembre"), $bFill, 'R', 0, 0);
        $this->MultiCell(17, 4, lang("reportes_reporte_acumulados_octubre"), $bFill, 'R', 0, 0);
        $this->MultiCell(17, 4, lang("reportes_reporte_acumulados_noviembre"), $bFill, 'R', 0, 0);
        $this->MultiCell(17, 4, lang("reportes_reporte_acumulados_diciembre"), $bFill, 'R', 0, 0);
        $this->MultiCell(17, 4, lang("reportes_reporte_acumulados_total"), $bFill, 'R', 0, 0);
        
        $this->writeHTMLCell(278, 1, 10, 37, "<hr />", 0, 1, 0, 1);
    }


    /**
     * Metodo que se encarga de generar el cuerpo del reporte solicitado
     */
    public function generarReporte()
    {
        // recuperamos los empleados que apareceran en el reporte 
        $aData = & $this->_getData();
        $iContaTotalData = count($aData);
        
        // configuramos los datos 
        $this->SetFontSize(8);
        $this->AddPage('L', 'A4');
        
        // iterar
        $bFill = 0;        
        $iIncrementarHeigth = 4;
        $this->SetY(REPORTE_TIPO_LISTADO_EJE_Y_PRIMER_ROW);
        $this->SetFont(PDF_FONT_NAME_MAIN);
        
        foreach($aData AS $oData)
        {
            if($this->checkPageBreak(25,'',true))
            {
                $this->SetY(REPORTE_TIPO_LISTADO_EJE_Y_PRIMER_ROW);
            }
            
            // recuperamos el valor mas alto de la celda
            $rowcount = max(
                    $this->getNumLines($oData->iNumeroConcepto,10),
                    $this->getNumLines($oData->cNombreConcepto,50),
                    $this->getNumLines($oData->fMontoEnero,17),
                    $this->getNumLines($oData->fMontoFebrero,17),
                    $this->getNumLines($oData->fMontoMarzo,17),
                    $this->getNumLines($oData->fMontoAbril,17),
                    $this->getNumLines($oData->fMontoMayo,17),
                    $this->getNumLines($oData->fMontoJunio,17),
                    $this->getNumLines($oData->fMontoJulio,17),
                    $this->getNumLines($oData->fMontoAgosto,17),
                    $this->getNumLines($oData->fMontoSeptiembre,17),
                    $this->getNumLines($oData->fMontoOctubre,17),
                    $this->getNumLines($oData->fMontoNoviembre,17),
                    $this->getNumLines($oData->fMontoDiciembre,17),
                    $this->getNumLines($oData->fMontoTotal,17)
            );             
            
            $iHeigth = ($rowcount * $iIncrementarHeigth);
            
            $this->MultiCell(10, $iHeigth, $oData->iNumeroConcepto, $bFill, 'R', 0, 0);
            $this->MultiCell(50, $iHeigth, $oData->cNombreConcepto, $bFill, 'L', 0, 0);
            $this->MultiCell(17, $iHeigth, number_format($oData->fMontoEnero, REDONDEAR_DECIMALES), $bFill, 'R', 0, 0);
            $this->MultiCell(17, $iHeigth, number_format($oData->fMontoFebrero, REDONDEAR_DECIMALES), $bFill, 'R', 0, 0);
            $this->MultiCell(17, $iHeigth, number_format($oData->fMontoMarzo, REDONDEAR_DECIMALES), $bFill, 'R', 0, 0);
            $this->MultiCell(17, $iHeigth, number_format($oData->fMontoAbril, REDONDEAR_DECIMALES), $bFill, 'R', 0, 0);
            $this->MultiCell(17, $iHeigth, number_format($oData->fMontoMayo, REDONDEAR_DECIMALES), $bFill, 'R', 0, 0);
            $this->MultiCell(17, $iHeigth, number_format($oData->fMontoJunio, REDONDEAR_DECIMALES), $bFill, 'R', 0, 0);
            $this->MultiCell(17, $iHeigth, number_format($oData->fMontoJulio, REDONDEAR_DECIMALES), $bFill, 'R', 0, 0);
            $this->MultiCell(17, $iHeigth, number_format($oData->fMontoAgosto, REDONDEAR_DECIMALES), $bFill, 'R', 0, 0);
            $this->MultiCell(17, $iHeigth, number_format($oData->fMontoSeptiembre, REDONDEAR_DECIMALES), $bFill, 'R', 0, 0);
            $this->MultiCell(17, $iHeigth, number_format($oData->fMontoOctubre, REDONDEAR_DECIMALES), $bFill, 'R', 0, 0);
            $this->MultiCell(17, $iHeigth, number_format($oData->fMontoNoviembre, REDONDEAR_DECIMALES), $bFill, 'R', 0, 0);
            $this->MultiCell(17, $iHeigth, number_format($oData->fMontoDiciembre, REDONDEAR_DECIMALES), $bFill, 'R', 0, 0);
            $this->MultiCell(17, $iHeigth, number_format($oData->fMontoTotal, REDONDEAR_DECIMALES), $bFill, 'R', 0, 0);           
            
            $this->Ln();             
        } 
       
        $this->writeHTMLCell(278, 1, 10, $this->GetY(), "<hr />", 0, 1, 0, 1);
        $this->SetFont(PDF_FONT_NAME_MAIN, "B");
        
        // se agrega la etiqueta de total de registros
        $this->MultiCell(30, 4, lang("reportes_total_registros"), 0, 'C', 0, 0);
        // se agrega el total de los registros
        $this->MultiCell(20, 4, $iContaTotalData, 0, 'C', 0, 0);        
        
        
        $this->Output($this->oReporte->cNombre .  '.pdf', 'I');
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
