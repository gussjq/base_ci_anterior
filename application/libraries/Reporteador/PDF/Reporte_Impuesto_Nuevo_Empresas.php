<?php

include_once '/../tcpdf/tcpdf.php';
include_once '/../../ReportesData.php';

class Reporte_Impuesto_Nuevo_Empresas extends TCPDF {
    
    private $oReporte;
    private $bFill = 1;
    
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
        
        $iHeigth = 8;
        $this->MultiCell(60, $iHeigth, lang("reportes_reporte_impuesto_nuevo_empresas_tipo_nomina"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(20, $iHeigth, lang("reportes_reporte_impuesto_nuevo_empresas_sueldo"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(20, $iHeigth, lang("reportes_reporte_impuesto_nuevo_empresas_ps"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(20, $iHeigth, lang("reportes_reporte_impuesto_nuevo_empresas_otras_percepciones"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(20, $iHeigth, lang("reportes_reporte_impuesto_nuevo_empresas_isr"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(20, $iHeigth, lang("reportes_reporte_impuesto_nuevo_empresas_imss"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(20, $iHeigth, lang("reportes_reporte_impuesto_nuevo_empresas_subcidio"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(20, $iHeigth, lang("reportes_reporte_impuesto_nuevo_empresas_credito_infonavit"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(20, $iHeigth, lang("reportes_reporte_impuesto_nuevo_empresas_impuesto_estatal"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(20, $iHeigth, lang("reportes_reporte_impuesto_nuevo_empresas_imss_empresa"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(20, $iHeigth, lang("reportes_reporte_impuesto_nuevo_empresas_imss_retiro"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(20, $iHeigth, lang("reportes_reporte_impuesto_nuevo_empresas_infonavit_empresa"), $this->bFill, 'C', 0, 0);
        
        $this->writeHTMLCell(278, 1, 10, 40, "<hr />", 0, 1, 0, 1);
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
        $iIncrementarHeigth = 4;
        $this->SetY(REPORTE_TIPO_LISTADO_EJE_Y_PRIMER_ROW);
        $this->SetFont(PDF_FONT_NAME_MAIN);
        
        
        $iTotalEnero = 0;
        $iTotalFebrero = 0;
        $iTotalMarzo = 0;
        $iTotalAbril = 0;
        $iTotalMayo = 0;
        $iTotalJunio = 0;
        $iTotalJulio = 0;
        $iTotalAgosto = 0;
        $iTotalSeptiembre = 0;
        $iTotalOctubre = 0;
        $iTotalNoviembre = 0;
        $iTotalDiciembre = 0;
        $iTotalTotal = 0;
        
        $cNombreConceptoTMP = "";
        
        
        foreach($aData AS $oData)
        {
            
            if($this->checkPageBreak(15,'',true))
            {
                $this->SetY(REPORTE_TIPO_LISTADO_EJE_Y_PRIMER_ROW);
            }
            
            // recuperamos el valor mas alto de la celda
            $rowcount = max(
                    $this->getNumLines($oData->iNumeroConcepto,10),
                    $this->getNumLines($oData->cNombreCompleto,50),
                    $this->getNumLines($oData->fMontoEnero,20),
                    $this->getNumLines($oData->fMontoFebrero,20),
                    $this->getNumLines($oData->fMontoMarzo,20),
                    $this->getNumLines($oData->fMontoAbril,20),
                    $this->getNumLines($oData->fMontoMayo,20),
                    $this->getNumLines($oData->fMontoJunio,20),
                    $this->getNumLines($oData->fMontoJulio,20),
                    $this->getNumLines($oData->fMontoAgosto,20),
                    $this->getNumLines($oData->fMontoSeptiembre,20),
                    $this->getNumLines($oData->fMontoOctubre,20)
            );              
           
           if($cNombreConceptoTMP != $oData->cNombreConcepto)
           {
                $this->SetFont(PDF_FONT_NAME_MAIN, "B");
                $this->SetFontSize(9);
                               
                $this->MultiCell(30, 4, lang("reportes_reporte_acumulado_tag_concepto"), $this->bFill, 'C', 0, 0);
                
                $this->SetX(40);
                $this->MultiCell(95, 4, $oData->iNumeroConcepto . " : " .$oData->cNombreConcepto, $this->bFill, 'L', 0, 0);
                
                $this->Ln();
           }
            
            $iHeigth = ($rowcount * $iIncrementarHeigth);
            
            $this->SetFont(PDF_FONT_NAME_MAIN);
            
            $this->MultiCell(10, $iHeigth, $oData->iNumeroEmpleado, $this->bFill, 'C', 0, 0);
            $this->MultiCell(50, $iHeigth, $oData->cNombreCompleto, $this->bFill, 'L', 0, 0);
            $this->MultiCell(20, $iHeigth, $oData->fMontoEnero, $this->bFill, 'C', 0, 0);
            $this->MultiCell(20, $iHeigth, $oData->fMontoFebrero, $this->bFill, 'C', 0, 0);
            $this->MultiCell(20, $iHeigth, $oData->fMontoMarzo, $this->bFill, 'C', 0, 0);
            $this->MultiCell(20, $iHeigth, $oData->fMontoAbril, $this->bFill, 'C', 0, 0);
            $this->MultiCell(20, $iHeigth, $oData->fMontoMayo, $this->bFill, 'C', 0, 0);
            $this->MultiCell(20, $iHeigth, $oData->fMontoJunio, $this->bFill, 'C', 0, 0);
            $this->MultiCell(20, $iHeigth, $oData->fMontoJulio, $this->bFill, 'C', 0, 0);
            $this->MultiCell(20, $iHeigth, $oData->fMontoAgosto, $this->bFill, 'C', 0, 0);
            $this->MultiCell(20, $iHeigth, $oData->fMontoSeptiembre, $this->bFill, 'C', 0, 0);
            $this->MultiCell(20, $iHeigth, $oData->fMontoOctubre, $this->bFill, 'C', 0, 0);
            
            $this->Ln();    
            
            $iTotalEnero += $oData->fMontoEnero;
            $iTotalFebrero += $oData->fMontoFebrero;
            $iTotalMarzo += $oData->fMontoMarzo;
            $iTotalAbril += $oData->fMontoAbril;
            $iTotalMayo += $oData->fMontoMayo;
            $iTotalJunio += $oData->fMontoJunio;
            $iTotalJulio += $oData->fMontoJulio;
            $iTotalAgosto += $oData->fMontoAgosto;
            $iTotalSeptiembre += $oData->fMontoSeptiembre;
            $iTotalOctubre += $oData->fMontoOctubre;
            $iTotalNoviembre += $oData->fMontoNoviembre;
            $iTotalDiciembre += $oData->fMontoDiciembre;
            $iTotalTotal += $oData->fMontoTotal;
        
            $cNombreConceptoTMP = $oData->cNombreConcepto;
        } 
         
         
        
        $this->Ln();
        $this->SetFont(PDF_FONT_NAME_MAIN, "B");        
        
        $this->Ln();
        $this->Ln();
       
        $this->writeHTMLCell(278, 1, 10, $this->GetY(), "<hr />", 0, 1, 0, 1);
        
        // se agrega la etiqueta de total de registros
        $this->MultiCell(30, 4, lang("reportes_total_registros"), $this->bFill, 'C', 0, 0);
        
        
        // se agrega el total de los registros
        $this->MultiCell(30, 4, $iContaTotalData, $this->bFill, 'C', 0, 0);        
        $this->MultiCell(20, 4, number_format($iTotalEnero, REDONDEAR_DECIMALES, NUMBER_FORMAT_PUNTO, NUMBER_FORMAT_COMA), $this->bFill, 'C', 0, 0);
        $this->MultiCell(20, 4, number_format($iTotalFebrero, REDONDEAR_DECIMALES, NUMBER_FORMAT_PUNTO, NUMBER_FORMAT_COMA), $this->bFill, 'C', 0, 0);
        $this->MultiCell(20, 4, number_format($iTotalMarzo, REDONDEAR_DECIMALES, NUMBER_FORMAT_PUNTO, NUMBER_FORMAT_COMA), $this->bFill, 'C', 0, 0);
        $this->MultiCell(20, 4, number_format($iTotalAbril, REDONDEAR_DECIMALES, NUMBER_FORMAT_PUNTO, NUMBER_FORMAT_COMA), $this->bFill, 'C', 0, 0);
        $this->MultiCell(20, 4, number_format($iTotalMayo, REDONDEAR_DECIMALES, NUMBER_FORMAT_PUNTO, NUMBER_FORMAT_COMA), $this->bFill, 'C', 0, 0);
        $this->MultiCell(20, 4, number_format($iTotalJunio, REDONDEAR_DECIMALES, NUMBER_FORMAT_PUNTO, NUMBER_FORMAT_COMA), $this->bFill, 'C', 0, 0);
        $this->MultiCell(20, 4, number_format($iTotalJulio, REDONDEAR_DECIMALES, NUMBER_FORMAT_PUNTO, NUMBER_FORMAT_COMA), $this->bFill, 'C', 0, 0);
        $this->MultiCell(20, 4, number_format($iTotalAgosto, REDONDEAR_DECIMALES, NUMBER_FORMAT_PUNTO, NUMBER_FORMAT_COMA), $this->bFill, 'C', 0, 0);
        $this->MultiCell(20, 4, number_format($iTotalSeptiembre, REDONDEAR_DECIMALES, NUMBER_FORMAT_PUNTO, NUMBER_FORMAT_COMA), $this->bFill, 'C', 0, 0);
        $this->MultiCell(20, 4, number_format($iTotalOctubre, REDONDEAR_DECIMALES, NUMBER_FORMAT_PUNTO, NUMBER_FORMAT_COMA), $this->bFill, 'C', 0, 0);
        $this->MultiCell(20, 4, number_format($iTotalOctubre, REDONDEAR_DECIMALES, NUMBER_FORMAT_PUNTO, NUMBER_FORMAT_COMA), $this->bFill, 'C', 0, 0);
         
        
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
    
    
    private function  _getData()
    {
//        $oReporteData = new ReportesData();
//        $oReporteData->initialize($this->oReporte);
//        $aDataReporte = & $oReporteData->getDataReporte();
//        return $aDataReporte;
        
        return array();
    }
}
