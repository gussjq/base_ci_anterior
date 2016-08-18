<?php

include_once '/../tcpdf/tcpdf.php';
include_once '/../../ReportesData.php';

class Reporte_Calculo_Anual_De_ISR extends TCPDF {
    
    private $oReporte;
    private $iIncrementarHeigth = 4;
    
    private $iWidthNumero = 20;
    private $iWidthNombreEmpleado = 55;
    private $iWidthIngresosSueldosSalarios = 25;
    private $iWidthIngresosExcento = 25;
    private $iWidthIngresosAcumulables = 25;
    private $iWidthImpuestoSobreLaRentaEjercicio = 25;
    private $iWidthImpuestoRetenidoContribuyente = 25;
    private $iWidthFavor = 25;
    private $iWidthCargo = 25;
    private $iWidthCalcular = 25;
    
    private $iPrimerRow = 45;
    private $bFill = 0;
    
    public function __construct($orientation = 'P', $unit = 'mm', $format = 'A4', $unicode = true, $encoding = 'UTF-8', $diskcache = false)
    {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache);
        
        $this->CI = &get_instance();
        $this->CI->load->model("procesosanuales/CalculoISREmpleado_model");
        $this->CI->load->library("ViewModels/CalculoISREmpleado_ViewModel");
    }

    /**
     * Metodo que se encarga de realzar la funcion de un semiconstructor, en donde se encarga de inicializar los
     * parametros para el proceso
     * 
     * @param object $oReporte
     */
    public function initialize($oReporte)
    {
        $this->oReporte = $oReporte;
    }
    
    /**
     * Metodo que se encarga de diseñar el header del pdf
     * @access public
     * @return void No retorna valor
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
        
        $this->SetY(25);
        $this->SetFontSize(12);
        $this->Cell(27, 10, lang("calculoisr_empresa") . " :", $this->bFill, 1, '', 0, '', 0);
        
        $this->SetXY(38,25);
        $this->Cell(170, 10, $this->_getRazonSocial(), $this->bFill, 1, '', 0, '', 0);
        
        $this->SetXY(209,25);
        $this->Cell(27, 10, lang("calculoisr_ano") . " :", $this->bFill, 1, '', 0, '', 0);
        
        $this->SetXY(236,25);
        $this->Cell(27, 10, $this->oReporte->iAno, $this->bFill, 1, '', 0, '', 0);
                
        $this->SetFontSize(8);
        $this->writeHTMLCell(273, 1, 10, 35, "<hr />", $this->bFill, 1, 0, 1);
        
        $this->SetY(37);
        $this->MultiCell(20, 4, lang("calculoisr_numero"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(55, 4, lang("calculoisr_empleado"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(25, 4, lang("calculoisr_percepciones"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(25, 4, lang("calculoisr_excento"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(25, 4, lang("calculoisr_gravadas"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(25, 4, lang("calculoisr_calculo_isr"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(25, 4, lang("calculoisr_retenido"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(25, 4, lang("calculoisr_favor"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(25, 4, lang("calculoisr_cargo"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(25, 4, lang("calculoisr_calcular"), $this->bFill, 'C', 0, 0);
        
        $this->writeHTMLCell(273, 1, 10, 42, "<hr />", 0, 1, 0, 1);
    }


    /**
     * Metodo que se encarga de generar el cuerpo del reporte solicitado
     */
    public function generarReporte()
    {
        // recuperamos los empleados que apareceran en el reporte 
        $oCalculoISREmpleado = new CalculoISREmpleado_ViewModel();
        $oCalculoISREmpleado->idEmpresa = $this->oReporte->idEmpresa;
        $oCalculoISREmpleado->iAno = $this->oReporte->iAno;
                
        $aEmpleados = & $this->CI->CalculoISREmpleado_model->getExportarDIM($oCalculoISREmpleado); 
        $iContaEmpleados = count($aEmpleados);
        
        // configuramos los datos 
        $this->SetFontSize(8);
        $this->AddPage('L', 'A4');
       
        $this->SetY($this->iPrimerRow);
        
        foreach($aEmpleados AS $oEmpleado)
        {
            if($this->checkPageBreak(25,'',true))
            {
                $this->SetY($this->iPrimerRow);
            }
            
            // recuperamos el valor mas alto de la celda
            $rowcount = max(
                    $this->getNumLines($oEmpleado->iNumero, $this->iWidthNumero),
                    $this->getNumLines($oEmpleado->cNombreCompleto, $this->iWidthNombreEmpleado),
                    $this->getNumLines(round($oEmpleado->fISRA,REDONDEAR_DECIMALES), $this->iWidthIngresosSueldosSalarios),
                    $this->getNumLines(round($oEmpleado->fISRC,REDONDEAR_DECIMALES), $this->iWidthIngresosExcento),
                    $this->getNumLines(round($oEmpleado->fISRF,REDONDEAR_DECIMALES), $this->iWidthIngresosAcumulables),
                    $this->getNumLines(round($oEmpleado->fISRO,REDONDEAR_DECIMALES), $this->iWidthImpuestoSobreLaRentaEjercicio),
                    $this->getNumLines(round($oEmpleado->fISRP,REDONDEAR_DECIMALES), $this->iWidthImpuestoRetenidoContribuyente),
                    $this->getNumLines(round($oEmpleado->fFavor,REDONDEAR_DECIMALES), $this->iWidthFavor),
                    $this->getNumLines(round($oEmpleado->fCargo,REDONDEAR_DECIMALES), $this->iWidthCargo),
                    $this->getNumLines($oEmpleado->bCalcular, $this->iWidthCalcular)
            );  
            
            $this->SetFontSize(8);
            $this->SetFont(PDF_FONT_NAME_MAIN);
            
            $iHeigth = ($rowcount * $this->iIncrementarHeigth);
            
            $this->MultiCell($this->iWidthNumero, $iHeigth, $oEmpleado->iNumero, $this->bFill, 'L', 0, 0);
            $this->MultiCell($this->iWidthNombreEmpleado, $iHeigth, $oEmpleado->cNombreCompleto, $this->bFill, 'L', 0, 0);
            $this->MultiCell($this->iWidthIngresosSueldosSalarios, $iHeigth, round($oEmpleado->fISRA,REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
            $this->MultiCell($this->iWidthIngresosExcento, $iHeigth, round($oEmpleado->fISRC,REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
            $this->MultiCell($this->iWidthIngresosAcumulables, $iHeigth, round($oEmpleado->fISRF,REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
            $this->MultiCell($this->iWidthImpuestoSobreLaRentaEjercicio, $iHeigth, round($oEmpleado->fISRO,REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
            $this->MultiCell($this->iWidthImpuestoRetenidoContribuyente, $iHeigth, round($oEmpleado->fISRO,REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
            $this->MultiCell($this->iWidthFavor, $iHeigth, round($oEmpleado->fFavor,REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
            $this->MultiCell($this->iWidthCargo, $iHeigth, round($oEmpleado->fCargo,REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
            $this->MultiCell($this->iWidthCalcular, $iHeigth, ($oEmpleado->bCalcular) ? lang("general_si") : lang("general_no"), $this->bFill, 'C', 0, 0);
            
            $this->Ln();
        }  
        
        $this->Ln();
               
        $this->writeHTMLCell(275, 1, 10, $this->GetY(), "<hr />", $this->bFill, 1, 0, 1);
        $this->SetFont(PDF_FONT_NAME_MAIN, "B");
        
        $this->MultiCell(30, 4, lang("reportes_total_registros"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(45, 4, $iContaEmpleados, $this->bFill, 'L', 0, 0); 
        
        $this->MultiCell($this->iWidthIngresosSueldosSalarios, $iHeigth, round($oEmpleado->fTotalPercepciones,REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
        $this->MultiCell($this->iWidthIngresosExcento, $iHeigth, round($oEmpleado->fTotalExcentas,REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
        $this->MultiCell($this->iWidthIngresosAcumulables, $iHeigth, round($oEmpleado->fTotalGravadas,REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
        $this->MultiCell($this->iWidthImpuestoSobreLaRentaEjercicio, $iHeigth, round($oEmpleado->fTotalISRAnual,REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
        $this->MultiCell($this->iWidthImpuestoRetenidoContribuyente, $iHeigth, round($oEmpleado->fTotalRetenido,REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
        $this->MultiCell($this->iWidthFavor, $iHeigth, round($oEmpleado->fTotalFavor,REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
        $this->MultiCell($this->iWidthCargo, $iHeigth, round($oEmpleado->fTotalCargo,REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);

        
        $this->Output($this->oReporte->cNombre . '.pdf', 'I');
    }
    
    /**
     * Metodo que sobre escribe la funcionalidad del diseño del footer que esta en la libreria tcpdf
     * 
     * @access public
     * @return void No retorna valor
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
    
    /**
     * Metodo que se encarga de recuperar la razon social de la empresa
     * @return string
     */
    private function _getRazonSocial()
    {
        $this->CI->load->model("contratacion/Empresa_model");
        
        $oEmpresa = $this->CI->Empresa_model->find(array(
            "idEmpresa" => $this->oReporte->idEmpresa,
            "bHabilitado" => SI,
            "bBorradoLogico" => NO
        ));
        
        return (is_object($oEmpresa)) ? $oEmpresa->cRazonSocial : "";
    }
}
