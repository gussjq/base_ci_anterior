<?php

include_once '/../tcpdf/tcpdf.php';
include_once '/../../ReportesData.php';

class Reporte_Calculo_Anual_De_ISR_Montos extends TCPDF {
    
    private $oReporte;
    private $iIncrementarHeigth = 4;
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
        $this->MultiCell(136.5, 4, lang("reportes_calculo_isr_annual_montos_concepto"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(136.5, 4, lang("reportes_calculo_isr_annual_montos_montos"), $this->bFill, 'C', 0, 0);
        
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
            if($this->checkPageBreak(15,'',true))
            {
                $this->SetY($this->iPrimerRow);
            }
            
            // recuperamos el valor mas alto de la celda
            $rowcount = max(
                    $this->getNumLines($oEmpleado->iNumero, 20),
                    $this->getNumLines($oEmpleado->cNombreCompleto, 55)
            );  
            
            $this->SetFont(PDF_FONT_NAME_MAIN, "B");
            $this->SetFontSize(9);
                
            $this->MultiCell(273, 4, lang("reportes_calculo_isr_annual_montos_empleado") . " : " . $oEmpleado->iNumero . ", " . $oEmpleado->cNombreCompleto, $this->bFill, 'L', 0, 0);
            $this->Ln();
            
            $this->SetFont(PDF_FONT_NAME_MAIN);
            
            // SECCION 2 IMPUESTO SOBRE LA RENTA
            
            if($oEmpleado->fISRA)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_impuesto_sobre_la_renta_a"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fISRA, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            if($oEmpleado->fISRB)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_impuesto_sobre_la_renta_b"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fISRB, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            if($oEmpleado->fISRC)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_impuesto_sobre_la_renta_c"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fISRC, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            if($oEmpleado->fISRD)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_impuesto_sobre_la_renta_d"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fISRD, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            if($oEmpleado->fISRE)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_impuesto_sobre_la_renta_e"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fISRE, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            if($oEmpleado->fISRF)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_impuesto_sobre_la_renta_f"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fISRF, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            if($oEmpleado->fISRG)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_impuesto_sobre_la_renta_g"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fISRG, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            if($oEmpleado->fISRH)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_impuesto_sobre_la_renta_h"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fISRH, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            if($oEmpleado->fISRI)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_impuesto_sobre_la_renta_i"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fISRI, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            if($oEmpleado->fISRJ)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_impuesto_sobre_la_renta_j"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fISRJ, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            if($oEmpleado->fISRK)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_impuesto_sobre_la_renta_k"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fISRK, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            if($oEmpleado->fISRL)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_impuesto_sobre_la_renta_l"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fISRL, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            if($oEmpleado->fISRM)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_impuesto_sobre_la_renta_m"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fISRM, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            if($oEmpleado->fISRN)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_impuesto_sobre_la_renta_n"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fISRN, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            if($oEmpleado->fISRO)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_impuesto_sobre_la_renta_o"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fISRO, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            if($oEmpleado->fISRP)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_impuesto_sobre_la_renta_p"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fISRP, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            // SECCION 3 PAGOS POR SEPARACION
            
            if($oEmpleado->fPSQ)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_pagos_separacion_q"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fPSQ, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            if($oEmpleado->fPSR)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_pagos_separacion_r"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fPSR, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            if($oEmpleado->fPSS)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_pagos_separacion_s"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fPSS, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            if($oEmpleado->fPST)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_pagos_separacion_t"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fPST, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            if($oEmpleado->fPSU)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_pagos_separacion_u"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fPSU, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            if($oEmpleado->fPSV)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_pagos_separacion_v"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fPSV, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            if($oEmpleado->fPSW)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_pagos_separacion_w"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fPSW, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            if($oEmpleado->fPSX)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_pagos_separacion_x"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fPSX, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            if($oEmpleado->fPSY)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_pagos_separacion_y"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fPSY, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            if($oEmpleado->fPSZ)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_pagos_separacion_z"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fPSZ, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            if($oEmpleado->fPSa)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_pagos_separacion_a"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fSeparacionMontoTotalPago, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            if($oEmpleado->fPSb)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_pagos_separacion_b"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fSeparacionIngresosExcentos2, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            if($oEmpleado->fPSc)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_pagos_separacion_c"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fSeparacionIngresosGravados2, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            if($oEmpleado->fPSd)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_pagos_separacion_d"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fSeapracionIngresosAcumulables2, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            if($oEmpleado->fPSe)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_pagos_separacion_e"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fSeapracionIngresosAcumulables2, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            if($oEmpleado->fPSf)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_pagos_separacion_f"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fSeparacionImpuestoCorrespondienteUltimoSueldoOrdinario, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            if($oEmpleado->fPSg)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_pagos_separacion_g"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fSeparacionIngresosNoAcumulables, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            if($oEmpleado->fPSh)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_pagos_separacion_h"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fSeparacionImpuestoRetenido, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            // SECCION 4 INGRESOS ASIMILADOS
            
            if($oEmpleado->fIASi)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_ingreso_asimilado_salarios_i"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fIngresoAsimiladosSalarioMonto, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            if($oEmpleado->fIASj)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_ingreso_asimilado_salarios_j"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fIngresoAsimiladosImpuestoRetenidoEjercicio, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            // SECCION 4.1 INGRESOS EN ACCIONES O TITULOS VALOR QUE REQUIERAN BIENES (Por ejecutar la opción otorgada por el empleador)
            
            if($oEmpleado->fIATk)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_ingresos_acciones_titulo_k"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fIngresoAccionesTituloValorMercadoAcciones, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            if($oEmpleado->fIATl)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_ingresos_acciones_titulo_l"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fIngresoAccionesTituloPreciosEstablecidoOtorgarseTituloValor, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            if($oEmpleado->fIATm)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_ingresos_acciones_titulo_m"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fIngresoAccionesTituloIngresoAcumulable, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            if($oEmpleado->fIATn)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_ingresos_acciones_titulo_n"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fIngresoAccionesTituloImpuestoRetenido, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
            
            if($oEmpleado->fSueldoGravado)
            {
                $this->MultiCell(136.5, 4, lang("reportes_forma37_ingresos_acciones_titulo_n"), $this->bFill, 'R', 0, 0);
                $this->MultiCell(136.5, 4, round($oEmpleado->fSueldoGravado, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);
                $this->Ln();
            }
                
            $this->Ln();
            
            $this->SetFontSize(8);
            $this->SetFont(PDF_FONT_NAME_MAIN);
            
            $iHeigth = ($rowcount * $this->iIncrementarHeigth);
            
            $this->Ln();
        }  
        
        $this->Ln();
               
        $this->writeHTMLCell(275, 1, 10, $this->GetY(), "<hr />", $this->bFill, 1, 0, 1);
        $this->SetFont(PDF_FONT_NAME_MAIN, "B");
        
        $this->MultiCell(30, 4, lang("reportes_total_registros"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(45, 4, $iContaEmpleados, $this->bFill, 'L', 0, 0); 
        
        $this->Output($this->oReporte->cNombre . '.pdf', 'I');
    }
    
    /**
     * Metodo que sobre escribe la funcionalidad del diseño del footer que esta en la libreria tcpdf
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
