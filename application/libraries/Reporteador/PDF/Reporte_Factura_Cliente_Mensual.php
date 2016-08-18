<?php

include_once '/../tcpdf/tcpdf.php';
include_once '/../../ReportesData.php';

class Reporte_Factura_Cliente_Mensual extends TCPDF {
    
    private $oReporte;
    private $bFill = 0;
    private $iPrimerRow = 51;
    private $oTipoNomina;
    private $oPeriodo;
    
    public function __construct($orientation = 'L', $unit = 'mm', $format = array(700,210), $unicode = true, $encoding = 'UTF-8', $diskcache = false)
    {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache);
        
        $this->CI = &get_instance();
    }

    public function initialize($oReporte)
    {
        $this->oReporte = $oReporte;
        $this->oTipoNomina = $this->_getTipoNomina();
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
        $this->Cell(190, 10, $this->oTipoNomina->cNombre, $this->bFill, 1, '', 0, '', 0);
        
        $this->SetY(24);
        $this->SetFontSize(13);
        $this->Cell(190, 10, "Del Mes ".$this->oReporte->iMesInicio . " al Mes " . $this->oReporte->iMesFin, $this->bFill, 1, '', 0, '', 0);        
        
        $this->SetFontSize(8);
        $this->writeHTMLCell(673, 1, 10, 38, "<hr />", 0, 1, 0, 1);
        
        $iIncrementarHeigth = 4;
        $rowcount = max(
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_numero"),25),
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_nombre"),50),
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_pago"),25),
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_ps_ejecutiva"),25),
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_desempleado"),25),
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_desuniforme"),25),
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_desprestamocliente"),25),
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_descolegiatura"),25),
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_desequipo"),25),
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_descurso"),25),
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_desahorro"),25),
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_destienda"),25),
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_subtotalnomina"),25),
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_comisionhonorarios"),25),
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_comisionhonorarioscliente"),25),
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_imss_patronal"),25),
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_imss_retiro_patronal"),25),
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_infonavit_patronal"),25),
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_impuesto_estatal_nominas"),25),
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_subtotalcargasocial"),25),
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_impuestos_empleado"),25),
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_subtotal"),25),
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_iva"),25),
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_total"),25),
                    $this->getNumLines(lang("reportes_reporte_factura_cliente_area"),30)
            ); 
        
        $iHeigth = ($rowcount * $iIncrementarHeigth);
        
        $this->MultiCell(25, $iHeigth, lang("reportes_reporte_factura_cliente_numero"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(50, $iHeigth, lang("reportes_reporte_factura_cliente_nombre"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(25, $iHeigth, lang("reportes_reporte_factura_cliente_pago"), $this->bFill, 'C', 0, 0);        
        $this->MultiCell(25, $iHeigth, lang("reportes_reporte_factura_cliente_ps_ejecutiva"), $this->bFill, 'C', 0, 0);        
        $this->MultiCell(25, $iHeigth, lang("reportes_reporte_factura_cliente_desempleado"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(25, $iHeigth, lang("reportes_reporte_factura_cliente_desuniforme"), $this->bFill, 'C', 0, 0);        
        $this->MultiCell(25, $iHeigth, lang("reportes_reporte_factura_cliente_desprestamocliente"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(25, $iHeigth, lang("reportes_reporte_factura_cliente_descolegiatura"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(25, $iHeigth, lang("reportes_reporte_factura_cliente_desequipo"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(25, $iHeigth, lang("reportes_reporte_factura_cliente_descurso"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(25, $iHeigth, lang("reportes_reporte_factura_cliente_desahorro"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(25, $iHeigth, lang("reportes_reporte_factura_cliente_destienda"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(25, $iHeigth, lang("reportes_reporte_factura_cliente_subtotalnomina"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(25, $iHeigth, lang("reportes_reporte_factura_cliente_comisionhonorarios"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(25, $iHeigth, lang("reportes_reporte_factura_cliente_comisionhonorarioscliente"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(25, $iHeigth, lang("reportes_reporte_factura_cliente_imss_patronal"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(25, $iHeigth, lang("reportes_reporte_factura_cliente_imss_retiro_patronal"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(25, $iHeigth, lang("reportes_reporte_factura_cliente_infonavit_patronal"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(25, $iHeigth, lang("reportes_reporte_factura_cliente_impuesto_estatal_nominas"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(25, $iHeigth, lang("reportes_reporte_factura_cliente_subtotalcargasocial"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(25, $iHeigth, lang("reportes_reporte_factura_cliente_impuestos_empleado"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(25, $iHeigth, lang("reportes_reporte_factura_cliente_subtotal"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(25, $iHeigth, lang("reportes_reporte_factura_cliente_iva"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(25, $iHeigth, lang("reportes_reporte_factura_cliente_total"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(25, $iHeigth, lang("reportes_reporte_factura_cliente_area"), $this->bFill, 'C', 0, 0);
        
        $this->writeHTMLCell(673, 1, 10, 50, "<hr />", 0, 1, 0, 1);
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
        $this->AddPage();
        
        $iIncrementarHeigth = 4;
        $this->SetY($this->iPrimerRow);
        $iContaRows = 0;
        $iHeigth = 0;
        
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
        
        foreach($aEmpleados AS $oEmpleado)
        {
            if($this->checkPageBreak(15,'',true))
            {
                $this->SetY($this->iPrimerRow);
            }
            
            // recuperamos el valor mas alto de la celda
            $rowcount = max(
                    $this->getNumLines($oEmpleado->iNumero,25),
                    $this->getNumLines($oEmpleado->cNombreEmpleado,50),
                    $this->getNumLines(number_format($oEmpleado->fPago, REDONDEAR_DECIMALES),25),
                    $this->getNumLines(number_format($oEmpleado->fPSEjecutiva, REDONDEAR_DECIMALES),25),
                    $this->getNumLines(number_format($oEmpleado->fDescuentoEmpleado, REDONDEAR_DECIMALES),25),
                    $this->getNumLines(number_format($oEmpleado->fDescuentoUniformes, REDONDEAR_DECIMALES),25),
                    $this->getNumLines(number_format($oEmpleado->fDescuentoPrestamoCliente, REDONDEAR_DECIMALES),25),
                    $this->getNumLines(number_format($oEmpleado->fDescuentoColegiatura, REDONDEAR_DECIMALES),25),
                    $this->getNumLines(number_format($oEmpleado->fDescuentoEquipo, REDONDEAR_DECIMALES),25),
                    $this->getNumLines(number_format($oEmpleado->fDescuentoCurso, REDONDEAR_DECIMALES),25),
                    $this->getNumLines(number_format($oEmpleado->fDescuentoAhorro, REDONDEAR_DECIMALES),25),
                    $this->getNumLines(number_format($oEmpleado->fDescuentoTienda, REDONDEAR_DECIMALES),25),
                    $this->getNumLines(number_format($oEmpleado->fSubtotalNomina, REDONDEAR_DECIMALES),25),
                    $this->getNumLines(number_format($oEmpleado->fComisionHonorarios, REDONDEAR_DECIMALES),25),
                    $this->getNumLines(number_format($oEmpleado->fComisionHonorariosCliente, REDONDEAR_DECIMALES),25),
                    $this->getNumLines(number_format($oEmpleado->fIMSSPatronal, REDONDEAR_DECIMALES),25),
                    $this->getNumLines(number_format($oEmpleado->fIMSSRetiroPatronal, REDONDEAR_DECIMALES),25),
                    $this->getNumLines(number_format($oEmpleado->fInfonavitPatronal, REDONDEAR_DECIMALES),25),
                    $this->getNumLines(number_format($oEmpleado->fImpuestoEstatalNominas, REDONDEAR_DECIMALES),25),
                    $this->getNumLines(number_format($oEmpleado->fSubTotalCargaSocial, REDONDEAR_DECIMALES),25),
                    $this->getNumLines(number_format($oEmpleado->fImpuestosTrabajador, REDONDEAR_DECIMALES),25),
                    $this->getNumLines(number_format($oEmpleado->fSubTotal, REDONDEAR_DECIMALES),25),
                    $this->getNumLines(number_format($oEmpleado->fMontoIva, REDONDEAR_DECIMALES),25),
                    $this->getNumLines(number_format($oEmpleado->fTotal, REDONDEAR_DECIMALES),25),
                    $this->getNumLines($oEmpleado->cNombreArea,30)
            );  

            $this->SetFontSize(8);
            $this->SetFont(PDF_FONT_NAME_MAIN);
            
            $iHeigth = ($rowcount * $iIncrementarHeigth);
            
            $this->MultiCell(25,$iHeigth,$oEmpleado->iNumero,$this->bFill,'L',0,0);
            $this->MultiCell(50,$iHeigth,$oEmpleado->cNombreEmpleado,$this->bFill,'L',0,0);
            $this->MultiCell(25,$iHeigth,number_format($oEmpleado->fPago, REDONDEAR_DECIMALES),$this->bFill,'C',0,0);
            $this->MultiCell(25,$iHeigth,number_format($oEmpleado->fPSEjecutiva, REDONDEAR_DECIMALES),$this->bFill,'C',0,0);
            $this->MultiCell(25,$iHeigth,number_format($oEmpleado->fDescuentoEmpleado, REDONDEAR_DECIMALES),$this->bFill,'C',0,0);
            $this->MultiCell(25,$iHeigth,number_format($oEmpleado->fDescuentoUniformes, REDONDEAR_DECIMALES),$this->bFill,'C',0,0);
            $this->MultiCell(25,$iHeigth,number_format($oEmpleado->fDescuentoPrestamoCliente, REDONDEAR_DECIMALES),$this->bFill,'C',0,0);
            $this->MultiCell(25,$iHeigth,number_format($oEmpleado->fDescuentoColegiatura, REDONDEAR_DECIMALES),$this->bFill,'C',0,0);
            $this->MultiCell(25,$iHeigth,number_format($oEmpleado->fDescuentoEquipo, REDONDEAR_DECIMALES),$this->bFill,'C',0,0);
            $this->MultiCell(25,$iHeigth,number_format($oEmpleado->fDescuentoCurso, REDONDEAR_DECIMALES),$this->bFill,'C',0,0);
            $this->MultiCell(25,$iHeigth,number_format($oEmpleado->fDescuentoAhorro, REDONDEAR_DECIMALES),$this->bFill,'C',0,0);
            $this->MultiCell(25,$iHeigth,number_format($oEmpleado->fDescuentoTienda, REDONDEAR_DECIMALES),$this->bFill,'C',0,0);
            $this->MultiCell(25,$iHeigth,number_format($oEmpleado->fSubtotalNomina, REDONDEAR_DECIMALES),$this->bFill,'C',0,0);
            $this->MultiCell(25,$iHeigth,number_format($oEmpleado->fComisionHonorarios, REDONDEAR_DECIMALES),$this->bFill,'C',0,0);
            $this->MultiCell(25,$iHeigth,number_format($oEmpleado->fComisionHonorariosCliente, REDONDEAR_DECIMALES),$this->bFill,'C',0,0);
            $this->MultiCell(25,$iHeigth,number_format($oEmpleado->fIMSSPatronal, REDONDEAR_DECIMALES),$this->bFill,'C',0,0);
            $this->MultiCell(25,$iHeigth,number_format($oEmpleado->fIMSSRetiroPatronal, REDONDEAR_DECIMALES),$this->bFill,'C',0,0);
            $this->MultiCell(25,$iHeigth,number_format($oEmpleado->fInfonavitPatronal, REDONDEAR_DECIMALES),$this->bFill,'C',0,0);
            $this->MultiCell(25,$iHeigth,number_format($oEmpleado->fImpuestoEstatalNominas, REDONDEAR_DECIMALES),$this->bFill,'C',0,0);
            $this->MultiCell(25,$iHeigth,number_format($oEmpleado->fSubTotalCargaSocial, REDONDEAR_DECIMALES),$this->bFill,'C',0,0);
            $this->MultiCell(25,$iHeigth,number_format($oEmpleado->fImpuestosTrabajador, REDONDEAR_DECIMALES),$this->bFill,'C',0,0);
            $this->MultiCell(25,$iHeigth,number_format($oEmpleado->fSubTotal, REDONDEAR_DECIMALES),$this->bFill,'C',0,0);
            $this->MultiCell(25,$iHeigth,number_format($oEmpleado->fMontoIva, REDONDEAR_DECIMALES),$this->bFill,'C',0,0);
            $this->MultiCell(25,$iHeigth,number_format($oEmpleado->fTotal, REDONDEAR_DECIMALES),$this->bFill,'C',0,0);
            $this->MultiCell(25,$iHeigth,$oEmpleado->cNombreArea,$this->bFill,'L',0,0);
            
            $this->Ln(); 
            $iContaRows++;
            
            $fTotalPago += $oEmpleado->fPago;
            $fTotalPSEjecutiva += $oEmpleado->fPSEjecutiva;
            $fTotalDescuentoEmpleado += $oEmpleado->fDescuentoEmpleado;
            $fTotalDescuentoUniformes += $oEmpleado->fDescuentoUniformes;
            $fTotalDescuentoPrestamoCliente += $oEmpleado->fDescuentoPrestamoCliente;
            $fTotalDescuentoColegiatura += $oEmpleado->fDescuentoColegiatura;
            $fTotalDescuentoEquipo += $oEmpleado->fDescuentoEquipo;
            $fTotalDescuentoCurso += $oEmpleado->fDescuentoCurso;
            $fTotalDescuentoAhorro += $oEmpleado->fDescuentoAhorro;
            $fTotalDescuentoTienda += $oEmpleado->fDescuentoTienda;
            $fTotalSubtotalNomina += $oEmpleado->fSubtotalNomina;
            $fTotalComisionHonorarios += $oEmpleado->fComisionHonorarios;
            $fTotalComisionHonorariosCliente += $oEmpleado->fComisionHonorariosCliente;
            $fTotalIMSSPatronal += $oEmpleado->fIMSSPatronal;
            $fTotalIMSSRetiroPatronal += $oEmpleado->fIMSSRetiroPatronal;
            $fTotalInfonavitPatronal += $oEmpleado->fInfonavitPatronal;
            $fTotalImpuestoEstatalNomina += $oEmpleado->fImpuestoEstatalNominas;
            $fTotalSubTotalCargaSocial += $oEmpleado->fSubTotalCargaSocial;
            $fTotalImpuestosTrabajador += $oEmpleado->fImpuestosTrabajador;
            $fTotalSubTotal += $oEmpleado->fSubTotal;
            $fTotalMontoIva += $oEmpleado->fMontoIva;
            $fTotalTotal += $oEmpleado->fTotal;
        }  
        
        $this->SetFontSize(8);


        $this->writeHTMLCell(673, 1, 10, $this->GetY(), "<hr />", 0, 1, 0, 1);
        $this->SetFont(PDF_FONT_NAME_MAIN, "B");
        
        // se agrega la etiqueta de total de registros
        $this->MultiCell(25, $iHeigth, lang("reportes_total_registros"), $this->bFill, 'C', 0, 0);
        // se agrega el total de los registros
        $this->MultiCell(50, $iHeigth, $iContaEmpleados, $this->bFill, 'C', 0, 0);        
        $this->MultiCell(25, $iHeigth, number_format($fTotalPago, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);  
        $this->MultiCell(25, $iHeigth, number_format($fTotalPSEjecutiva, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);  
        $this->MultiCell(25, $iHeigth, number_format($fTotalDescuentoEmpleado, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);  
        $this->MultiCell(25, $iHeigth, number_format($fTotalDescuentoUniformes, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);  
        $this->MultiCell(25, $iHeigth, number_format($fTotalDescuentoPrestamoCliente, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);  
        $this->MultiCell(25, $iHeigth, number_format($fTotalDescuentoColegiatura, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);  
        $this->MultiCell(25, $iHeigth, number_format($fTotalDescuentoEquipo, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);  
        $this->MultiCell(25, $iHeigth, number_format($fTotalDescuentoCurso, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);  
        $this->MultiCell(25, $iHeigth, number_format($fTotalDescuentoAhorro, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);  
        $this->MultiCell(25, $iHeigth, number_format($fTotalDescuentoTienda, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);  
        $this->MultiCell(25, $iHeigth, number_format($fTotalSubtotalNomina, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);  
        $this->MultiCell(25, $iHeigth, number_format($fTotalComisionHonorarios, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);  
        $this->MultiCell(25, $iHeigth, number_format($fTotalComisionHonorariosCliente, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);  
        $this->MultiCell(25, $iHeigth, number_format($fTotalIMSSPatronal, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);  
        $this->MultiCell(25, $iHeigth, number_format($fTotalIMSSRetiroPatronal, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);  
        $this->MultiCell(25, $iHeigth, number_format($fTotalInfonavitPatronal, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);  
        $this->MultiCell(25, $iHeigth, number_format($fTotalImpuestoEstatalNomina, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);  
        $this->MultiCell(25, $iHeigth, number_format($fTotalSubTotalCargaSocial, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);  
        $this->MultiCell(25, $iHeigth, number_format($fTotalImpuestosTrabajador, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);  
        $this->MultiCell(25, $iHeigth, number_format($fTotalSubTotal, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);  
        $this->MultiCell(25, $iHeigth, number_format($fTotalMontoIva, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);  
        $this->MultiCell(25, $iHeigth, number_format($fTotalTotal, REDONDEAR_DECIMALES), $this->bFill, 'C', 0, 0);  
        
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
    
    private function _getTipoNomina()
    {
        $this->CI->load->model("contratacion/TipoNomina_model");
        $oTipoNomina = $this->CI->TipoNomina_model->find(array(
            "idTipoNomina" => $this->oReporte->idTipoNomina
        ));
        
        return $oTipoNomina;
    }
}
