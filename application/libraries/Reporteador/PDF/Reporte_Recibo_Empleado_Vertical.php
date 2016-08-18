<?php
include_once '/../tcpdf/tcpdf.php';

class Reporte_Recibo_Empleado_Vertical extends TCPDF {
    
    private $iFiltrarPor;
    private $aEmpleados;
    
    public function __construct($orientation = 'P', $unit = 'mm', $format = 'A4', $unicode = true, $encoding = 'UTF-8', $diskcache = false)
    {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache);
        
        $this->CI = &get_instance();
    }
    
    /**
     * Metodo que simula ser nuestro constructor
     * @param array $aEmpleados
     */
    public function initialize(&$aEmpleados, $iFiltrarPor)
    {
        $this->aEmpleados = &$aEmpleados;
        $this->iFiltrarPor = $iFiltrarPor;
    }
    
    /**
     * Metodo que se encarga de generar el recibo de nomina
     */
    public function generarReporte()
    {
       $bFill = 0;
       $bFillSeparador = 0;
       $iIncrementarHeigth = 4;
       
       $iInitY = 0;
       $iInitX = 0;
       
       $bIterar = false;
       
       $this->setPrintHeader(FALSE);
       $this->setFooterMargin(25);
       $this->SetFontSize(9);
       
       foreach($this->aEmpleados as $oEmpleado)
       {
            $this->AddPage('L', 'A4');
            
            $this->cNombreEmpleado = $oEmpleado->cNombreCompleto;
            
            // header del recibo
            $this->SetFont(PDF_FONT_NAME_MAIN, "B");
            $this->SetFontSize(12);
            $this->MultiCell(45.33, 4, '', $bFill, 'C', 0, 0);
            $this->MultiCell(91.66, 4, $oEmpleado->cNombreEmpresa, $bFill, 'C', 0, 0);    
            
            // separador
            $this->MultiCell(2, 4, '', $bFillSeparador, 'C', $bFillSeparador, 0);    
            
            $this->MultiCell(45.33, 4, '', $bFill, 'C', 0, 0);
            $this->MultiCell(91.66, 4, $oEmpleado->cNombreEmpresa, $bFill, 'C', 0, 0);
            
            $this->Ln();
            
            $this->SetFontSize(9);
            $this->SetFont(PDF_FONT_NAME_MAIN);
            $this->MultiCell(45.33, 4, '', $bFill, 'C', 0, 0);
            $this->MultiCell(91.66, 4, $this->_concatenarDireccion($oEmpleado), $bFill, 'C', 0, 0);
            
            // separador
            $this->MultiCell(2, 4, '', $bFillSeparador, 'C', $bFillSeparador, 0);
            
            $this->MultiCell(45.33, 4, '', $bFill, 'C', 0, 0);
            $this->MultiCell(91.66, 4, $this->_concatenarDireccion($oEmpleado), $bFill, 'C', 0, 0);
            
            $this->Ln();
            
            $this->SetFont(PDF_FONT_NAME_MAIN);
            $this->MultiCell(45.33, 4, '', $bFill, 'C', 0, 0);
            $this->MultiCell(91.66, 4, $oEmpleado->cRazonSocialEmpresa, $bFill, 'C', 0, 0);
            
            // separador
            $this->MultiCell(2, 4, '', $bFillSeparador, 'C', $bFillSeparador, 0);
            
            $this->MultiCell(45.33, 4, '', $bFill, 'C', 0, 0);
            $this->MultiCell(91.66, 4, $oEmpleado->cRazonSocialEmpresa, $bFill, 'C', 0, 0);
            
            $this->Ln();
            $this->Ln();
            
            // body del recibo
            $this->SetFont(PDF_FONT_NAME_MAIN, 'B');
            $this->MultiCell(68.5, 4, lang("recibonomina_pdf_recibo_nomina"), $bFill, 'L', 0, 0);
            $this->SetFont(PDF_FONT_NAME_MAIN);
            $this->MultiCell(68.5, 4, lang("recibonomina_pdf_recibo_vertical_rfc") . " : " . $oEmpleado->cRFCEmpresa, $bFill, 'L', 0, 0);
            
            // separador
            $this->MultiCell(2, 4, '', $bFillSeparador, 'C', $bFillSeparador, 0);
            
            $this->SetFont(PDF_FONT_NAME_MAIN, 'B');
            $this->MultiCell(68.5, 4, lang("recibonomina_pdf_recibo_nomina"), $bFill, 'L', 0, 0);
            $this->SetFont(PDF_FONT_NAME_MAIN);
            $this->MultiCell(68.5, 4, lang("recibonomina_pdf_recibo_vertical_rfc") . " : " . $oEmpleado->cRFCEmpresa, $bFill, 'L', 0, 0);
            
            $this->Ln();
            
            // listado
            $this->SetFont(PDF_FONT_NAME_MAIN, 'B');
            $this->SetFontSize(8);
            $this->SetFillColor(208, 208, 208);
            $this->MultiCell(20, 4, lang("recibonomina_pdf_recibo_vertical_numero"), 1, 'C', 1, 0);
            $this->MultiCell(49, 4, lang("recibonomina_pdf_recibo_vertical_nombre"), 1, 'C', 1, 0);
            $this->MultiCell(34, 4, lang("recibonomina_pdf_recibo_vertical_rfc"), 1, 'C', 1, 0);
            $this->MultiCell(34, 4, lang("recibonomina_pdf_recibo_vertical_imss"), 1, 'C', 1, 0);
            
            // separador
            $this->MultiCell(2, 4, '', $bFillSeparador, 'C', $bFillSeparador, 0);
            
            $this->MultiCell(20, 4, lang("recibonomina_pdf_recibo_vertical_numero"), 1, 'C', 1, 0);
            $this->MultiCell(49, 4, lang("recibonomina_pdf_recibo_vertical_nombre"), 1, 'C', 1, 0);
            $this->MultiCell(34, 4, lang("recibonomina_pdf_recibo_vertical_rfc"), 1, 'C', 1, 0);
            $this->MultiCell(34, 4, lang("recibonomina_pdf_recibo_vertical_imss"), 1, 'C', 1, 0);
            
            $this->Ln();
            
            $this->SetFont(PDF_FONT_NAME_MAIN);
            
            $rowcount = max(
                    $this->getNumLines($oEmpleado->iNumero,20),
                    $this->getNumLines($oEmpleado->cNombreCompleto,49),
                    $this->getNumLines($oEmpleado->cRFC,34),
                    $this->getNumLines($oEmpleado->cIMSS,34)
            );
            
            $iHeigth = ($rowcount * $iIncrementarHeigth);
            
            $this->MultiCell(20, $iHeigth, $oEmpleado->iNumero, 1, 'C', 0, 0);
            $this->MultiCell(49, $iHeigth, $oEmpleado->cNombreCompleto, 1, 'C', 0, 0);
            $this->MultiCell(34, $iHeigth, $oEmpleado->cRFC, 1, 'C', 0, 0);
            $this->MultiCell(34, $iHeigth, $oEmpleado->cIMSS, 1, 'C', 0, 0);
            
            // separador
            $this->MultiCell(2, 4, '', $bFillSeparador, 'C', $bFillSeparador, 0);
            
            $this->MultiCell(20, $iHeigth, $oEmpleado->iNumero, 1, 'C', 0, 0);
            $this->MultiCell(49, $iHeigth, $oEmpleado->cNombreCompleto, 1, 'C', 0, 0);
            $this->MultiCell(34, $iHeigth, $oEmpleado->cRFC, 1, 'C', 0, 0);
            $this->MultiCell(34, $iHeigth, $oEmpleado->cIMSS, 1, 'C', 0, 0);
            
            $this->Ln();
            
            $this->SetFont(PDF_FONT_NAME_MAIN, "B");
            $this->MultiCell(69, 4, lang("recibonomina_pdf_recibo_vertical_periodo"), 1, 'C', 1, 0);
            $this->MultiCell(34, 4, lang("recibonomina_pdf_recibo_vertical_fecha_pago"), 1, 'C', 1, 0);
            $this->MultiCell(34, 4, lang("recibonomina_pdf_recibo_vertical_salario_diario"), 1, 'C', 1, 0);
            
            // separador
            $this->MultiCell(2, 4, '', $bFillSeparador, 'C', $bFillSeparador, 0);
            
            $this->MultiCell(69, 4, lang("recibonomina_pdf_recibo_vertical_periodo"), 1, 'C', 1, 0);
            $this->MultiCell(34, 4, lang("recibonomina_pdf_recibo_vertical_fecha_pago"), 1, 'C', 1, 0);
            $this->MultiCell(34, 4, lang("recibonomina_pdf_recibo_vertical_salario_diario"), 1, 'C', 1, 0);
            
            $this->Ln();
            
            $this->SetFont(PDF_FONT_NAME_MAIN);
            $rowcount = max(
                    $this->getNumLines($oEmpleado->iNumeroPeriodo . " del " . $oEmpleado->dtFechaInicial . " al " . $oEmpleado->dtFechaFinal,69),
                    $this->getNumLines($oEmpleado->dtFechaPago,34),
                    $this->getNumLines($oEmpleado->fSalario,34)
            );
            
            $iHeigth = ($rowcount * $iIncrementarHeigth);
            
            $this->MultiCell(69, $iHeigth, $oEmpleado->iNumeroPeriodo . " del " . $oEmpleado->dtFechaInicial . " al " . $oEmpleado->dtFechaFinal, 1, 'C', 0, 0);
            $this->MultiCell(34, $iHeigth, $oEmpleado->dtFechaPago, 1, 'C', 0, 0);
            $this->MultiCell(34, $iHeigth, $oEmpleado->fSalario, 1, 'C', 0, 0);
            
            // separador
            $this->MultiCell(2, 4, '', $bFillSeparador, 'C', $bFillSeparador, 0);
            
            $this->MultiCell(69, $iHeigth, $oEmpleado->iNumeroPeriodo . " del " . $oEmpleado->dtFechaInicial . " al " . $oEmpleado->dtFechaFinal, 1, 'C', 0, 0);
            $this->MultiCell(34, $iHeigth, $oEmpleado->dtFechaPago, 1, 'C', 0, 0);
            $this->MultiCell(34, $iHeigth, $oEmpleado->fSalario, 1, 'C', 0, 0);
            
            $this->Ln();
            
            $this->SetFont(PDF_FONT_NAME_MAIN, 'B');
            $this->MultiCell(34.25, 4, lang("recibonomina_pdf_recibo_vertical_departamento"), 1, 'C', 1, 0);
            $this->MultiCell(31.25, 4, lang("recibonomina_pdf_recibo_vertical_puesto"), 1, 'C', 1, 0);
            $this->MultiCell(37.5, 4, lang("recibonomina_pdf_recibo_vertical_turno"), 1, 'C', 1, 0);
            $this->MultiCell(34, 4, lang("recibonomina_pdf_recibo_vertical_fecha_ingreso"), 1, 'C', 1, 0);
            
            // separador
            $this->MultiCell(2, 4, '', $bFillSeparador, 'C', $bFillSeparador, 0);
            
            $this->MultiCell(34.25, 4, lang("recibonomina_pdf_recibo_vertical_departamento"), 1, 'C', 1, 0);
            $this->MultiCell(31.25, 4, lang("recibonomina_pdf_recibo_vertical_puesto"), 1, 'C', 1, 0);
            $this->MultiCell(37.5, 4, lang("recibonomina_pdf_recibo_vertical_turno"), 1, 'C', 1, 0);
            $this->MultiCell(34, 4, lang("recibonomina_pdf_recibo_vertical_fecha_ingreso"), 1, 'C', 1, 0);
            
            $this->Ln();
            
            $rowcount = max(
                    $this->getNumLines($oEmpleado->cNombreDepartamento,34.25),
                    $this->getNumLines($oEmpleado->cNombrePuesto,31.25),
                    $this->getNumLines($oEmpleado->cNombreTurno,37.5),
                    $this->getNumLines($oEmpleado->dtFechaIngreso,34)
            );
            
            $iHeigth = ($rowcount * $iIncrementarHeigth);
            
            $this->SetFont(PDF_FONT_NAME_MAIN);
            $this->MultiCell(34.25, $iHeigth, $oEmpleado->cNombreDepartamento, 1, 'C', 0, 0);
            $this->MultiCell(31.25, $iHeigth, $oEmpleado->cNombrePuesto, 1, 'C', 0, 0);
            $this->MultiCell(37.5, $iHeigth, $oEmpleado->cNombreTurno, 1, 'C', 0, 0);
            $this->MultiCell(34, $iHeigth, $oEmpleado->dtFechaIngreso, 1, 'C', 0, 0);
            
            // separador
            $this->MultiCell(2, 4, '', $bFillSeparador, 'C', $bFillSeparador, 0);
            
            $this->MultiCell(34.25, $iHeigth, $oEmpleado->cNombreDepartamento, 1, 'C', 0, 0);
            $this->MultiCell(31.25, $iHeigth, $oEmpleado->cNombrePuesto, 1, 'C', 0, 0);
            $this->MultiCell(37.5, $iHeigth, $oEmpleado->cNombreTurno, 1, 'C', 0, 0);
            $this->MultiCell(34, $iHeigth, $oEmpleado->dtFechaIngreso, 1, 'C', 0, 0);
            
            $this->Ln();
            
            $this->SetFont(PDF_FONT_NAME_MAIN, "B");
            
            $this->MultiCell(68.5, 4, lang("recibonomina_pdf_recibo_vertical_percepciones"), 1, 'C', 1, 0);
            $this->MultiCell(68.5, 4, lang("recibonomina_pdf_recibo_vertical_deducciones"), 1, 'C', 1, 0);
            
            // separador
            $this->MultiCell(2, 4, '', $bFillSeparador, 'C', $bFillSeparador, 0);
            
            $this->MultiCell(68.5, 4, lang("recibonomina_pdf_recibo_vertical_percepciones"), 1, 'C', 1, 0);
            $this->MultiCell(68.5, 4, lang("recibonomina_pdf_recibo_vertical_deducciones"), 1, 'C', 1, 0);
            
            $this->Ln();
            
            // conceptos percepciones
            $this->SetFont(PDF_FONT_NAME_MAIN);
                        
            $iInitPrimeroY = $this->GetY();
            $iInitPrimeroX = $this->GetX();
            
            foreach($oEmpleado->aPercepciones AS $oPercepcion)
            {   
                $rowcount = max(
                    $this->getNumLines($oPercepcion->iNumeroConcepto,11.5),
                    $this->getNumLines($oPercepcion->cNombreConcepto,30),
                    $this->getNumLines($oPercepcion->iValor,13.5),
                    $this->getNumLines($oPercepcion->fPagarGravable,13.5)
                );
                
                $iHeigth = ($rowcount * $iIncrementarHeigth);

                if($bIterar)
                {
                    $iInitY = $this->GetY();
                }
                else
                {
                    $iInitY = $iInitPrimeroY;
                    $iInitX = $iInitPrimeroX;
                }
                
                $this->SetXY($iInitX, $iInitY);
                
                $this->MultiCell(11.5, $iHeigth, $oPercepcion->iNumeroConcepto, $bFill, 'L', 0, 0);
                $this->MultiCell(30, $iHeigth, $oPercepcion->cNombreConcepto, $bFill, 'L', 0, 0);
                $this->MultiCell(13.5, $iHeigth, $oPercepcion->iValor, $bFill, 'C', 0, 0);
                $this->MultiCell(13.5, $iHeigth, $oPercepcion->fPagarGravable, $bFill, 'C', 0, 0);
                
                $this->SetXY(($iInitX + 139), $iInitY);
                $this->MultiCell(11.5, $iHeigth, $oPercepcion->iNumeroConcepto, $bFill, 'L', 0, 0);
                $this->MultiCell(30, $iHeigth, $oPercepcion->cNombreConcepto, $bFill, 'L', 0, 0);
                $this->MultiCell(13.5, $iHeigth, $oPercepcion->iValor, $bFill, 'C', 0, 0);
                $this->MultiCell(13.5, $iHeigth, $oPercepcion->fPagarGravable, $bFill, 'C', 0, 0);
                
                $this->Ln();
                
                if(!$bIterar)
                {
                    $bIterar = true;
                }
            }
            
            $bIterar = false;
            foreach($oEmpleado->aDeducciones AS $oDeduccion)
            {   
                $rowcount = max(
                    $this->getNumLines($oDeduccion->iNumeroConcepto,11.5),
                    $this->getNumLines($oDeduccion->cNombreConcepto,41.5),                    
                    $this->getNumLines($oDeduccion->fPagarGravable,15)
                );
                
                $iHeigth = ($rowcount * $iIncrementarHeigth);
                
                if($bIterar)
                {
                    $iInitY = $this->GetY();
                }
                else
                {
                    $iInitY = $iInitPrimeroY;
                    $iInitX = $iInitPrimeroX;
                }
                
                $this->SetXY(($iInitX + 69), $iInitY);
                $this->MultiCell(11.5, 4, $oDeduccion->iNumeroConcepto, $bFill, 'L', 0, 0);
                $this->MultiCell(41.5, 4, $oDeduccion->cNombreConcepto, $bFill, 'L', 0, 0);
                $this->MultiCell(15, 4, $oDeduccion->fPagarGravable, $bFill, 'C', 0, 0);
                
                $this->SetXY(($iInitX + 208), $iInitY);
                $this->MultiCell(11.5, 4, $oDeduccion->iNumeroConcepto, $bFill, 'L', 0, 0);
                $this->MultiCell(41.5, 4, $oDeduccion->cNombreConcepto, $bFill, 'L', 0, 0);
                $this->MultiCell(15, 4, $oDeduccion->fPagarGravable, $bFill, 'C', 0, 0);
                
                $this->Ln();
                
                if(!$bIterar)
                {
                    $bIterar = true;
                }
            }
            
            $this->Ln();
            
            $rowcount = max(
                    $this->getNumLines(($this->iFiltrarPor == RECIBONOMINA_FILTRO_GRAVABLE) ? $oEmpleado->fTotalPercepciones : $oEmpleado->fTotalPercepcionesPS,40),
                    $this->getNumLines(($this->iFiltrarPor == RECIBONOMINA_FILTRO_GRAVABLE) ? $oEmpleado->fTotalDeducciones : $oEmpleado->fTotalDeduccionesPS,28)
            );
            
            $iHeigth = ($rowcount * $iIncrementarHeigth);
            
            $this->SetFont(PDF_FONT_NAME_MAIN, 'B');
            $this->MultiCell(40.4, $iHeigth, lang("recibonomina_pdf_recibo_vertical_totla_percepciones"), 1, 'C', 1, 0);
            $this->SetFont(PDF_FONT_NAME_MAIN);
            $this->MultiCell(28, $iHeigth, ($this->iFiltrarPor == RECIBONOMINA_FILTRO_GRAVABLE) ? $oEmpleado->fTotalPercepciones : $oEmpleado->fTotalPercepcionesPS, 1, 'C', 0, 0);
            
            $this->SetFont(PDF_FONT_NAME_MAIN, 'B');
            $this->MultiCell(40.4, $iHeigth, lang("recibonomina_pdf_recibo_vertical_totla_deducciones"), 1, 'C', 1, 0);
            $this->SetFont(PDF_FONT_NAME_MAIN);
            $this->MultiCell(28, $iHeigth, ($this->iFiltrarPor == RECIBONOMINA_FILTRO_GRAVABLE) ? $oEmpleado->fTotalDeducciones : $oEmpleado->fTotalDeduccionesPS, 1, 'C', 0, 0);
            
            
            // separador
            $this->MultiCell(2, 4, '', $bFillSeparador, 'C', $bFillSeparador, 0);
            
            $this->SetFont(PDF_FONT_NAME_MAIN, 'B');
            $this->MultiCell(40.4, $iHeigth, lang("recibonomina_pdf_recibo_vertical_totla_percepciones"), 1, 'C', 1, 0);
            $this->SetFont(PDF_FONT_NAME_MAIN);
            $this->MultiCell(28, $iHeigth, ($this->iFiltrarPor == RECIBONOMINA_FILTRO_GRAVABLE) ? $oEmpleado->fTotalPercepciones : $oEmpleado->fTotalPercepcionesPS, 1, 'C', 0, 0);
            
            $this->SetFont(PDF_FONT_NAME_MAIN, 'B');
            $this->MultiCell(40.4, $iHeigth, lang("recibonomina_pdf_recibo_vertical_totla_deducciones"), 1, 'C', 1, 0);
            $this->SetFont(PDF_FONT_NAME_MAIN);
            $this->MultiCell(28, $iHeigth, ($this->iFiltrarPor == RECIBONOMINA_FILTRO_GRAVABLE) ? $oEmpleado->fTotalDeducciones : $oEmpleado->fTotalDeduccionesPS, 1, 'C', 0, 0);
            
            $this->Ln();
            $this->Ln();
            
            $this->MultiCell(109, 4, str_replace("%s", $oEmpleado->cRazonSocialEmpresa,lang("recibonomina_pdf_recibo_vertical_descripcion_footer")), 1, 'L', 0, 0);
            $this->MultiCell(28, 4, ($this->iFiltrarPor==RECIBONOMINA_FILTRO_GRAVABLE) ? $oEmpleado->fNetoPagar : $oEmpleado->fNetoPagarPS, 1, 'C', 0, 0);

            $this->SetXY(119, $this->GetY() +  4);
            $this->SetFont(PDF_FONT_NAME_MAIN, 'B');
            $this->MultiCell(28, 4, lang("recibonomina_pdf_recibo_vertical_neto_pagar"), 1, 'C', 1, 0);
            
            $this->SetFont(PDF_FONT_NAME_MAIN);
            $this->SetXY(149,$this->GetY() - 4);
            $this->MultiCell(109, 4, str_replace("%s", $oEmpleado->cRazonSocialEmpresa,lang("recibonomina_pdf_recibo_vertical_descripcion_footer")), 1, 'L', 0, 0);
            $this->MultiCell(28, 4, ($this->iFiltrarPor==RECIBONOMINA_FILTRO_GRAVABLE) ? $oEmpleado->fNetoPagar : $oEmpleado->fNetoPagarPS, 1, 'C', 0, 0);
            
            $this->SetXY(258, $this->GetY() +  4);
            $this->SetFont(PDF_FONT_NAME_MAIN, 'B');
            $this->MultiCell(28, 4, lang("recibonomina_pdf_recibo_vertical_neto_pagar"), 1, 'C', 1, 0);

       }
       
       $this->Output('recibo_empleado.pdf', 'I');
   }
   
   
   public function Footer()
   {
        $this->SetFontSize(9);
        $this->SetY(185);
        $this->MultiCell(137, 4, "__________________________________________", 0, 'C', 0, 0);
        
        // separador
        $this->MultiCell(2, 4, '', 0, 'C', 0, 0);
        
        $this->MultiCell(137, 4, "__________________________________________", 0, 'C', 0, 0);
        $this->Ln();
        
        $this->MultiCell(137, 4, $this->cNombreEmpleado, 0, 'C', 0, 0);
        
        // separador
        $this->MultiCell(2, 4, '', 0, 'C', 0, 0);
        
        $this->MultiCell(137, 4, $this->cNombreEmpleado, 0, 'C', 0, 0);
        
        $this->Ln();
        
        $this->SetFont(PDF_FONT_NAME_MAIN, 'B');
        $this->SetFontSize(9);
        $this->MultiCell(137, 4, lang("recibonomina_pdf_recibo_vertical_recibo_empresa"), 0, 'R', 0, 0);
        
        // separador
        $this->MultiCell(2, 4, '', 0, 'C', 0, 0);
        
        $this->SetFont(PDF_FONT_NAME_MAIN, 'B');
        $this->MultiCell(137, 4, lang("recibonomina_pdf_recibo_vertical_recibo_empleado"), 0, 'R', 0, 0);
        
   }
   
   private function _concatenarDireccion($oEmpleado)
   {
       $cConcatenarDireccion = "";
       if (isset($oEmpleado->cCalleEmpresa)){
           $cConcatenarDireccion .= $oEmpleado->cCalleEmpresa;
       }
        
       if (isset($oEmpleado->cNumeroExteriorEmpresa)){ 
           $cConcatenarDireccion .= " #" . $oEmpleado->cNumeroExteriorEmpresa . ", ";
       }
        
       if (isset($oEmpleado->cNombreColoniaEmpresa)){
           $cConcatenarDireccion .= $oEmpleado->cNombreColoniaEmpresa . ", ";
       }
                                                
       if (isset($oEmpleado->cNombreCiudadEmpresa)){ 
            $cConcatenarDireccion .= $oEmpleado->cNombreCiudadEmpresa . ", ";
       }
       
       if (isset($oEmpleado->cNombreEstadoEmpresa)){
           $cConcatenarDireccion .= $oEmpleado->cNombreEstadoEmpresa . ". ";
       }
       
       if (isset($oEmpleado->cCodigoPostalEmpresa)){
           $cConcatenarDireccion .= lang("recibonomina_pdf_recibo_vertical_cp") . $oEmpleado->cCodigoPostalEmpresa ." ";
       }
       
       if (isset($oEmpleado->cTelefonoEmpresa)){
           $cConcatenarDireccion .= lang("recibonomina_pdf_recibo_vertical_telefono") . $oEmpleado->cTelefonoEmpresa;
       }
       
       return $cConcatenarDireccion;
   }
}
