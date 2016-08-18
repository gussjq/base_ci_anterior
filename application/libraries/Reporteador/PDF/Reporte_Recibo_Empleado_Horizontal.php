<?php
include_once '/../tcpdf/tcpdf.php';

class Reporte_Recibo_Empleado_Horizontal extends TCPDF {
    
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
       $this->setPrintHeader(FALSE);
       $this->setPrintFooter(FALSE);
       $this->setFooterMargin(25);
       $this->SetFontSize(9);
       
       foreach($this->aEmpleados as $oEmpleado)
       {
            $this->AddPage('P', 'A4');
            $this->_pintarPDF($oEmpleado);
            $this->_pintarPDF($oEmpleado, 150);
       }
       
       $this->Output('recibo_empleado.pdf', 'I');
   }
   
   private function _pintarPDF(&$oEmpleado, $y = NULL) {
        // header
        if ($y) {
            $this->SetY($y);
        }
        
        $bFill = 0;
        $iHeigth = 0;
        $iIncrementarHeigth = 4;
        
        $this->SetFont(PDF_FONT_NAME_MAIN, "B");
        $this->SetFontSize(11);
        $this->MultiCell(190, 4, $oEmpleado->cRazonSocialEmpresa, $bFill, 'R', 0, 0);

        $this->Ln();

        $rowcount = max(
                $this->getNumLines($oEmpleado->cRFCEmpresa, 30), $this->getNumLines($oEmpleado->cNumeroRegistroPatronal, 30)
        );

        $iHeigth = ($rowcount * $iIncrementarHeigth);

        $this->SetFont(PDF_FONT_NAME_MAIN, "B");
        $this->SetFontSize(8);
        $this->MultiCell(65, 4, '', $bFill, 'R', 0, 0);
        $this->MultiCell(20, $iHeigth, lang("recibonomina_pdf_recibo_horizontal_rfc"), $bFill, 'R', 0, 0);
        $this->SetFont(PDF_FONT_NAME_MAIN);
        $this->MultiCell(30, $iHeigth, $oEmpleado->cRFCEmpresa, $bFill, 'R', 0, 0);
        $this->SetFont(PDF_FONT_NAME_MAIN, "B");
        $this->MultiCell(40, $iHeigth, lang("recibonomina_pdf_recibo_horizontal_registro_patronal"), $bFill, 'R', 0, 0);
        $this->SetFont(PDF_FONT_NAME_MAIN);
        $this->MultiCell(30, $iHeigth, $oEmpleado->cNumeroRegistroPatronal, $bFill, 'R', 0, 0);
        $this->MultiCell(5, $iHeigth, "", $bFill, 'R', 0, 0);


        $rowcount = max(
                $this->getNumLines($this->_concatenarDireccion($oEmpleado), 120), $this->getNumLines($this->_concatenarDireccion($oEmpleado), 120)
        );

        $iHeigth = ($rowcount * $iIncrementarHeigth);

        $this->Ln();
        $this->MultiCell(65, $iHeigth, '', $bFill, 'R', 0, 0);
        $this->MultiCell(120, $iHeigth, $this->_concatenarDireccion($oEmpleado), $bFill, 'R', 0, 0);
        $this->MultiCell(5, $iHeigth, "", $bFill, 'R', 0, 0);

        $this->Ln();
        $this->Ln();

        // body
        $rowcount = max(
                $this->getNumLines($oEmpleado->iNumero . " " . $oEmpleado->cNombreCompleto, 62), $this->getNumLines($oEmpleado->cCurp, 38), $this->getNumLines($oEmpleado->cIMSS, 25)
        );

        $iHeigth = ($rowcount * $iIncrementarHeigth);

        $this->SetFont(PDF_FONT_NAME_MAIN, "B");
        $this->MultiCell(30, $iHeigth, lang("recibonomina_pdf_recibo_horizontal_recibo_nomina"), $bFill, 'L', 0, 0);
        $this->SetFont(PDF_FONT_NAME_MAIN);
        $this->MultiCell(62, $iHeigth, $oEmpleado->iNumero . " " . $oEmpleado->cNombreCompleto, $bFill, 'L', 0, 0);
        $this->SetFont(PDF_FONT_NAME_MAIN, "B");
        $this->MultiCell(15, $iHeigth, lang("recibonomina_pdf_recibo_horizontal_curp"), $bFill, 'L', 0, 0);
        $this->SetFont(PDF_FONT_NAME_MAIN);
        $this->MultiCell(38, $iHeigth, $oEmpleado->cCurp, $bFill, 'L', 0, 0);
        $this->SetFont(PDF_FONT_NAME_MAIN, "B");
        $this->MultiCell(20, $iHeigth, lang("recibonomina_pdf_recibo_horizontal_imss"), $bFill, 'L', 0, 0);
        $this->SetFont(PDF_FONT_NAME_MAIN);
        $this->MultiCell(25, $iHeigth, $oEmpleado->cIMSS, $bFill, 'L', 0, 0);

        $this->Ln();

        $rowcount = max(
                $this->getNumLines($oEmpleado->cNombreDepartamento, 35), $this->getNumLines($oEmpleado->dtFechaIngreso, 25), $this->getNumLines($oEmpleado->fSalario, 20), $this->getNumLines($oEmpleado->fSalarioDiarioIntegrado, 20)
        );

        $iHeigth = ($rowcount * $iIncrementarHeigth);

        $this->SetFont(PDF_FONT_NAME_MAIN, "B");
        $this->MultiCell(25, $iHeigth, lang("recibonomina_pdf_recibo_horizontal_departamento"), $bFill, 'L', 0, 0);
        $this->SetFont(PDF_FONT_NAME_MAIN);
        $this->MultiCell(35, $iHeigth, $oEmpleado->cNombreDepartamento, $bFill, 'L', 0, 0);
        $this->SetFont(PDF_FONT_NAME_MAIN, "B");
        $this->MultiCell(30, $iHeigth, lang("recibonomina_pdf_recibo_horizontal_fecha_ingreso"), $bFill, 'L', 0, 0);
        $this->SetFont(PDF_FONT_NAME_MAIN);
        $this->MultiCell(25, $iHeigth, $oEmpleado->dtFechaIngreso, $bFill, 'L', 0, 0);
        $this->SetFont(PDF_FONT_NAME_MAIN, "B");
        $this->MultiCell(25, $iHeigth, lang("recibonomina_pdf_recibo_horizontal_salario_diario"), $bFill, 'L', 0, 0);
        $this->SetFont(PDF_FONT_NAME_MAIN);
        $this->MultiCell(20, $iHeigth, $oEmpleado->fSalario, $bFill, 'L', 0, 0);
        $this->SetFont(PDF_FONT_NAME_MAIN, "B");
        $this->MultiCell(10, $iHeigth, lang("recibonomina_pdf_recibo_horizontal_sbc"), $bFill, 'L', 0, 0);
        $this->SetFont(PDF_FONT_NAME_MAIN);
        $this->MultiCell(20, $iHeigth, $oEmpleado->fSalarioDiarioIntegrado, $bFill, 'L', 0, 0);

        $this->Ln();

        $rowcount = max(
                $this->getNumLines($oEmpleado->cNombrePuesto, 35), $this->getNumLines($oEmpleado->cNombrePeriodo, 70), $this->getNumLines($oEmpleado->dtFechaInicial . " a " . $oEmpleado->dtFechaFinal, 35)
        );

        $iHeigth = ($rowcount * $iIncrementarHeigth);

        $this->SetFont(PDF_FONT_NAME_MAIN, "B");
        $this->MultiCell(25, $iHeigth, lang("recibonomina_pdf_recibo_horizontal_puesto"), $bFill, 'L', 0, 0);
        $this->SetFont(PDF_FONT_NAME_MAIN);
        $this->MultiCell(35, $iHeigth, $oEmpleado->cNombrePuesto, $bFill, 'L', 0, 0);
        $this->MultiCell(70, $iHeigth, $oEmpleado->cNombrePeriodo, $bFill, 'C', 0, 0);
        $this->SetFont(PDF_FONT_NAME_MAIN, "B");
        $this->MultiCell(25, $iHeigth, lang("recibonomina_pdf_recibo_horizontal_fechas"), $bFill, 'L', 0, 0);
        $this->SetFont(PDF_FONT_NAME_MAIN);
        $this->MultiCell(35, $iHeigth, $oEmpleado->dtFechaInicial . " a " . $oEmpleado->dtFechaFinal, $bFill, 'L', 0, 0);

        $this->Ln();
        $this->MultiCell(190, 0.5, "_______________________________________________________________________________________________________________________", $bFill, 'L', 0, 0);

        $this->Ln();

        $iInitY = $this->GetY();
        foreach ($oEmpleado->aPercepciones AS $oPercepcion) {
            $rowcount = max(
                    $this->getNumLines($oPercepcion->iNumeroConcepto, 15), $this->getNumLines($oPercepcion->cNombreConcepto, 40), $this->getNumLines($oPercepcion->iValor, 15), $this->getNumLines($oPercepcion->fPagarGravable, 35)
            );

            //95
            $iHeigth = ($rowcount * $iIncrementarHeigth);
            $this->MultiCell(15, $iHeigth, $oPercepcion->iNumeroConcepto, $bFill, 'L', 0, 0);
            $this->MultiCell(40, $iHeigth, $oPercepcion->cNombreConcepto, $bFill, 'L', 0, 0);
            $this->MultiCell(15, $iHeigth, $oPercepcion->iValor, $bFill, 'C', 0, 0);
            $this->MultiCell(25, $iHeigth, $oPercepcion->fPagarGravable, $bFill, 'C', 0, 0);

            $this->Ln();
        }

        $bIterar = false;
        $iInitX = $this->GetX() + 95;
        foreach ($oEmpleado->aDeducciones AS $oDeduccion) {
            $rowcount = max(
                    $this->getNumLines($oDeduccion->iNumeroConcepto, 15), $this->getNumLines($oDeduccion->cNombreConcepto, 40), $this->getNumLines($oDeduccion->fPagarGravable, 35)
            );

            //95
            if (!$bIterar) {
                $this->SetXY($iInitX, $iInitY);
                $bIterar = true;
            } else {
                $this->SetX($iInitX);
            }

            $iHeigth = ($rowcount * $iIncrementarHeigth);
            $this->MultiCell(15, $iHeigth, $oDeduccion->iNumeroConcepto, $bFill, 'L', 0, 0);
            $this->MultiCell(40, $iHeigth, $oDeduccion->cNombreConcepto, $bFill, 'L', 0, 0);
            $this->MultiCell(15, $iHeigth, '', $bFill, 'C', 0, 0);
            $this->MultiCell(25, $iHeigth, $oDeduccion->fPagarGravable, $bFill, 'C', 0, 0);

            $this->Ln();
        }

        $this->MultiCell(190, 0.5, "_______________________________________________________________________________________________________________________", $bFill, 'L', 0, 0);

        $this->Ln();

        $rowcount = max(
                $this->getNumLines(($this->iFiltrarPor == RECIBONOMINA_FILTRO_GRAVABLE) ? $oEmpleado->fTotalPercepciones : $oEmpleado->fTotalPercepcionesPS, 40), $this->getNumLines(($this->iFiltrarPor == RECIBONOMINA_FILTRO_GRAVABLE) ? $oEmpleado->fTotalDeducciones : $oEmpleado->fTotalDeduccionesPS, 25)
        );
        $iHeigth = ($rowcount * $iIncrementarHeigth);

        $this->SetFont(PDF_FONT_NAME_MAIN, "B");
        $this->MultiCell(70, $iHeigth, lang("recibonomina_pdf_recibo_horizontal_total_percepciones"), $bFill, 'R', 0, 0);
        $this->SetFont(PDF_FONT_NAME_MAIN);
        $this->MultiCell(25, $iHeigth, ($this->iFiltrarPor == RECIBONOMINA_FILTRO_GRAVABLE) ? $oEmpleado->fTotalPercepciones : $oEmpleado->fTotalPercepcionesPS, $bFill, 'C', 0, 0);
        $this->SetFont(PDF_FONT_NAME_MAIN, "B");
        $this->MultiCell(70, $iHeigth, lang("recibonomina_pdf_recibo_horizontal_total_deducciones"), $bFill, 'R', 0, 0);
        $this->SetFont(PDF_FONT_NAME_MAIN);
        $this->MultiCell(25, $iHeigth, ($this->iFiltrarPor == RECIBONOMINA_FILTRO_GRAVABLE) ? $oEmpleado->fTotalDeducciones : $oEmpleado->fTotalDeduccionesPS, $bFill, 'C', 0, 0);

        $this->Ln();
        $this->Ln();
        $this->Ln();

        $this->SetFont(PDF_FONT_NAME_MAIN, "B");
        $this->MultiCell(70, $iHeigth, lang("recibonomina_pdf_recibo_horizontal_neto_pagar"), $bFill, 'R', 0, 0);
        $this->MultiCell(25, $iHeigth, ($this->iFiltrarPor == RECIBONOMINA_FILTRO_GRAVABLE) ? $oEmpleado->fNetoPagar : $oEmpleado->fNetoPagarPS, $bFill, 'C', 0, 0);
        $this->MultiCell(95, $iHeigth, "_________________________________________", $bFill, 'C', 0, 0);

        $this->Ln();
        $this->MultiCell(95, $iHeigth, "", $bFill, 'R', 0, 0);
        $this->MultiCell(95, $iHeigth, lang("recibonomina_pdf_recibo_horizontal_nombre_firma"), $bFill, 'C', 0, 0);

        $this->Ln();
        $this->Ln();
        $this->Ln();
        $this->SetFont(PDF_FONT_NAME_MAIN);
        $this->MultiCell(190, $iHeigth, lang("recibonomina_pdf_recibo_horizontal_footer"), $bFill, 'L', 0, 0);
    }

    private function _concatenarDireccion(&$oEmpleado)
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
