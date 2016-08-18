<?php

include_once '/../tcpdf/tcpdf.php';
include_once '/../../ReportesData.php';

class Reporte_Empleado_Por_Registro_Patronal extends TCPDF {
    
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
        $this->MultiCell(20, 4, lang("reportes_reporte_empleado_numero"), 0, 'C', 0, 0);
        $this->MultiCell(55, 4, lang("reportes_reporte_empleado_nombre"), 0, 'C', 0, 0);
        $this->MultiCell(38, 4, lang("reportes_reporte_empleado_puesto"), 0, 'C', 0, 0);
        $this->MultiCell(38, 4, lang("reportes_reporte_empleado_imss"), 0, 'C', 0, 0);
        $this->MultiCell(38, 4, lang("reportes_reporte_empleado_registro_patronal"), 0, 'C', 0, 0);
        
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
        $cTipoNominaTMP = "";
        $iIncrementarHeigth = 4;
        $this->SetY(REPORTE_TIPO_LISTADO_EJE_Y_PRIMER_ROW);
        
        $iContaNominas = 0;
        $iContaRows = 0;
        
        foreach($aEmpleados AS $oEmpleado)
        {
            if($this->checkPageBreak(15,'',true))
            {
                $this->SetY(REPORTE_TIPO_LISTADO_EJE_Y_PRIMER_ROW);
            }
            
            // recuperamos el valor mas alto de la celda
            $rowcount = max(
                    $this->getNumLines($oEmpleado->iNumero,20),
                    $this->getNumLines($oEmpleado->cNombreCompleto,55),
                    $this->getNumLines($oEmpleado->cIMSS,38),
                    $this->getNumLines($oEmpleado->cNombrePuesto,38),
                    $this->getNumLines($oEmpleado->cNombreRegistroPatronal ,38)
            );  
            
            // si el contador es igual a cero se agrega un row con el tipo de nomina
            if($cTipoNominaTMP != $oEmpleado->cNombreTipoNomina)
            {                
                $this->SetFont(PDF_FONT_NAME_MAIN, "B");
                
                // se coloca para contar cuantos empleados pertenecen al tipo de nomina
                if($iContaNominas > 0)
                {
                    $this->MultiCell(30, 4, $iContaNominas, $bFill, 'C', 0, 0);
                    $this->Ln();
                    $iContaNominas = 0;
                }
                
                $this->SetFontSize(9);
                
                // se coloca la etiqueta del tipo de nomina
                $this->MultiCell(30, 4, lang("reportes_reporte_empleado_tipo_de_nomina"), $bFill, 'C', 0, 0);
                
                // se agrega el nombre del tipo de nomina
                $this->SetX(40);
                $this->MultiCell(95, 4, $oEmpleado->cNombreTipoNomina, $bFill, 'L', 0, 0);
                
                $this->Ln();
                
            }
            
            $iContaNominas++;
            $cTipoNominaTMP = $oEmpleado->cNombreTipoNomina;
            
            $this->SetFontSize(8);
            $this->SetFont(PDF_FONT_NAME_MAIN);
            
            $iHeigth = ($rowcount * $iIncrementarHeigth);
            
            // se agrega los datos del numero del empleado
            $this->MultiCell(20, $iHeigth, $oEmpleado->iNumero, 0, 'L', 0, 0);

            // se agrega el nombre del empleado
            $this->MultiCell(55, $iHeigth, $oEmpleado->cNombreCompleto, 0, 'L', 0, 0);

            // se agrega el puesto del empleado
            $this->MultiCell(38, $iHeigth, $oEmpleado->cNombrePuesto, 0, 'C', 0, 0);

            // se agrega el numero infonavit del empleado
            $this->MultiCell(38, $iHeigth, $oEmpleado->cIMSS, 0, 'C', 0, 0);

            // se agrega el registro patronal del empleado
            $this->MultiCell(38, $iHeigth, $oEmpleado->cNombreRegistroPatronal, 0, 'C', 0, 0);
            
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
            
            // se coloca para contar cuantos empleados pertenecen al tipo de nomina
            $this->MultiCell(30, 4, $iContaNominas, $bFill, 'C', 0, 0);
            $this->Ln();
            $iContaNominas = 0;    
        }
        
       
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
