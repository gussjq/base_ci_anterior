<?php

include_once '/../tcpdf/tcpdf.php';
include_once '/../../ReportesData.php';

class Reporte_Cuentas_Para_Deposito extends TCPDF {
    
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
        $this->writeHTMLCell(190, 1, 10, 30, "<hr />", 0, 1, 0, 1);
        
        $this->SetY(32);
        $this->MultiCell(15, 4, lang("reportes_reporte_empleado_numero"), $bFill, 'C', 0, 0);
        $this->MultiCell(40, 4, lang("reportes_reporte_empleado_nombre"), $bFill, 'C', 0, 0);
        $this->MultiCell(35, 4, lang("reportes_reporte_empleado_cuenta"), $bFill, 'C', 0, 0);
        $this->MultiCell(35, 4, lang("reportes_reporte_empleado_cuenta_interbancaria"), $bFill, 'C', 0, 0);
        $this->MultiCell(35, 4, lang("reportes_reporte_empleado_nombre_banco"), $bFill, 'C', 0, 0);
        $this->MultiCell(35, 4, lang("reportes_reporte_empleado_forma_pago"), $bFill, 'C', 0, 0);
        
        $this->writeHTMLCell(190, 1, 10, 37, "<hr />", 0, 1, 0, 1);
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
        $this->AddPage('P', 'A4');
        
        // iterar
        $bFill = 0;
        $cBancoTMP = "";
        $cDepartamentoTMP = "";
        $iIncrementarHeigth = 4;
        $this->SetY(REPORTE_TIPO_LISTADO_EJE_Y_PRIMER_ROW);
        
        $iContaRows = 0;
        $iContaEmpleadosBanco = 0;
        $iContaDepartamentos = 0;
        
        foreach($aEmpleados AS $oEmpleado)
        {
            if($this->checkPageBreak(15,'',true))
            {
                $this->SetY(REPORTE_TIPO_LISTADO_EJE_Y_PRIMER_ROW);
            }
            
            // recuperamos el valor mas alto de la celda
            $rowcount = max(
                    $this->getNumLines($oEmpleado->iNumero,15),
                    $this->getNumLines($oEmpleado->cNombreCompleto,40),
                    $this->getNumLines($oEmpleado->cCuentaBancaria,35),
                    $this->getNumLines($oEmpleado->cCuentaInterbancaria,35),
                    $this->getNumLines($oEmpleado->cAliasBanco,35),
                    $this->getNumLines($oEmpleado->cNombreFormaPago ,35)
            );  
            
            // si el contador es igual a cero se agrega un row con el tipo de nomina
            if($cBancoTMP != $oEmpleado->cAliasBanco)
            { 
                $this->SetFont(PDF_FONT_NAME_MAIN, "B");
                if($iContaDepartamentos>0)
                {
                    $this->MultiCell(30, 4, $iContaDepartamentos, $bFill, 'C', 0, 0);
                    $this->Ln();
                }
                
                $this->SetFontSize(9);
                
                // se coloca la etiqueta del tipo de nomina
                $this->MultiCell(30, 4, lang("reportes_reporte_empleado_banco"), $bFill, 'C', 0, 0);
                
                // se agrega el nombre del tipo de nomina
                $this->SetX(40);
                $this->MultiCell(95, 4, $oEmpleado->cAliasBanco, $bFill, 'L', 0, 0);
                
                $this->Ln();
                
                $cDepartamentoTMP = "";
                $iContaDepartamentos = 0;
            }
            
            if($cDepartamentoTMP != $oEmpleado->cNombreDepartamento)
            {   
                $this->SetFontSize(8);
                $this->SetFont(PDF_FONT_NAME_MAIN, "B");
                if($iContaDepartamentos > 0)
                {
                    $this->MultiCell(30, 4, $iContaDepartamentos, $bFill, 'C', 0, 0);
                    $this->Ln();
                }
                
                $this->SetFontSize(9);
                
                // se coloca la etiqueta del tipo de nomina
                $this->MultiCell(30, 4, lang("reportes_reporte_empleado_tag_departamento"), $bFill, 'C', 0, 0);
                
                // se agrega el nombre del tipo de nomina
                $this->SetX(40);
                $this->MultiCell(95, 4, $oEmpleado->cNombreDepartamento, $bFill, 'L', 0, 0);
                
                $this->Ln();
                $iContaDepartamentos = 0;
            }
            
            $iContaDepartamentos++;
            $cBancoTMP = $oEmpleado->cAliasBanco;
            $cDepartamentoTMP = $oEmpleado->cNombreDepartamento;            
            
            $this->SetFontSize(8);
            $this->SetFont(PDF_FONT_NAME_MAIN);
            
            $iHeigth = ($rowcount * $iIncrementarHeigth);
            
            $this->MultiCell(15, $iHeigth, $oEmpleado->iNumero, 0, 'L', 0, 0);

            $this->MultiCell(40, $iHeigth, $oEmpleado->cNombreCompleto, 0, 'L', 0, 0);
            
            $this->MultiCell(35, $iHeigth, $oEmpleado->cCuentaBancaria, 0, 'C', 0, 0);
            
            $this->MultiCell(35, $iHeigth, $oEmpleado->cCuentaInterbancaria, 0, 'C', 0, 0);
            
            $this->MultiCell(35, $iHeigth, $oEmpleado->cAliasBanco, 0, 'C', 0, 0);
            
            $this->MultiCell(35, $iHeigth, $oEmpleado->cNombreFormaPago, 0, 'C', 0, 0);
            
            $this->Ln(); 
            $iContaRows++;
        }  
        
        $this->SetFontSize(8);
        
        if($this->checkPageBreak(15,'',true))
        {
            $this->SetY(REPORTE_TIPO_LISTADO_EJE_Y_PRIMER_ROW);
        }
        
        if($iContaRows == $iContaEmpleados)
        {
            $this->SetFont(PDF_FONT_NAME_MAIN, "B");
            $this->MultiCell(30, 4, $iContaDepartamentos, $bFill, 'C', 0, 0);
            $this->Ln();
        }
        
        $this->SetFont(PDF_FONT_NAME_MAIN);
       
        $this->writeHTMLCell(190, 1, 10, $this->GetY(), "<hr />", 0, 1, 0, 1);
        $this->SetFont(PDF_FONT_NAME_MAIN, "B");
        
        // se agrega la etiqueta de total de registros
        $this->MultiCell(30, 4, lang("reportes_total_registros"), 0, 'C', 0, 0);
        // se agrega el total de los registros
        $this->MultiCell(20, 4, $iContaEmpleados, 0, 'C', 0, 0);  
        
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
        
        $this->SetY(288);
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
