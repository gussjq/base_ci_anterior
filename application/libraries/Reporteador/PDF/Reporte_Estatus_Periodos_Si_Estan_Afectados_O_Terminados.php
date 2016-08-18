<?php

include_once '/../tcpdf/tcpdf.php';
include_once '/../../ReportesData.php';

class Reporte_Estatus_Periodos_Si_Estan_Afectados_O_Terminados extends TCPDF {
    
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
        $this->MultiCell(40, 4, lang("reportes_reporte_periodos_numero"), $bFill, 'R', 0, 0);
        $this->MultiCell(80, 4, lang("reportes_reporte_periodos_nombre"), $bFill, 'C', 0, 0);
        $this->MultiCell(60, 4, lang("reportes_reporte_periodos_estatus"), $bFill, 'C', 0, 0);
        
        $this->writeHTMLCell(190, 1, 10, 37, "<hr />", 0, 1, 0, 1);
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
        $this->AddPage('P', 'A4');
        
        // iterar
        $bFill = 0;
        $iIncrementarHeigth = 4;
        $iContaData = 0;
        $iContaRows =0;
        $cNombreTipoNominaTMP = "";
        $this->SetY(REPORTE_TIPO_LISTADO_EJE_Y_PRIMER_ROW);
        
        foreach($aData AS $oData)
        {
            if($this->checkPageBreak(15,'',true))
            {
                $this->SetY(REPORTE_TIPO_LISTADO_EJE_Y_PRIMER_ROW);
            }
            
            // recuperamos el valor mas alto de la celda
            $rowcount = max(
                    $this->getNumLines($oData->iNumero,40),
                    $this->getNumLines($oData->cNombre,80),
                    $this->getNumLines($oData->cNombreEstatusPeriodo,60)
            );  
            
            // si el contador es igual a cero se agrega un row con el tipo de nomina
            if($cNombreTipoNominaTMP != $oData->cNombreTipoNomina)
            {
                $this->SetFont(PDF_FONT_NAME_MAIN, "B");
                
                // se coloca para contar cuantos empleados pertenecen al tipo de nomina
                if($iContaData > 0)
                {
                    $this->MultiCell(30, 4, $iContaData, $bFill, 'C', 0, 0);
                    $this->Ln();
                    $iContaData = 0;
                }
                
                $this->SetFontSize(9);
                
                // se coloca la etiqueta del tipo de nomina
                $this->MultiCell(30, 4, lang("reportes_reporte_periodos_tag_tipo_nomina"), $bFill, 'C', 0, 0);
                
                // se agrega el nombre del tipo de nomina
                $this->SetX(40);
                $this->MultiCell(95, 4, $oData->cNombreTipoNomina, $bFill, 'L', 0, 0);
                
                $this->Ln();
            }
            
            $iContaData++;
            $cNombreTipoNominaTMP = $oData->cNombreTipoNomina;
            
            $this->SetFontSize(8);
            $this->SetFont(PDF_FONT_NAME_MAIN);
            
            $iHeigth = ($rowcount * $iIncrementarHeigth);
            
            $this->MultiCell(40, $iHeigth, $oData->iNumero, $bFill, 'R', 0, 0);

            $this->MultiCell(80, $iHeigth, $oData->cNombre, $bFill, 'C', 0, 0);
            
            $this->MultiCell(60, $iHeigth, $oData->cNombreEstatusPeriodo, $bFill, 'C', 0, 0);            
            
            $this->Ln(); 
            
            $iContaRows++;
        }  
        
        $this->SetFontSize(8);
        
        if($iContaRows == $iContaTotalData)
        {
            $this->SetFont(PDF_FONT_NAME_MAIN, "B");
            
            $this->MultiCell(30, 4, $iContaData, $bFill, 'C', 0, 0);
            $this->Ln();
            $iContaData = 0;    
        }
       
        $this->writeHTMLCell(190, 1, 10, $this->GetY(), "<hr />", 0, 1, 0, 1);
        $this->SetFont(PDF_FONT_NAME_MAIN, "B");
        
        // se agrega la etiqueta de total de registros
        $this->MultiCell(30, 4, lang("reportes_total_registros"), 0, 'C', 0, 0);
        // se agrega el total de los registros
        $this->MultiCell(20, 4, $iContaTotalData, 0, 'C', 0, 0);   
        
        $this->Output($this->oReporte->cNombre .'.pdf', 'I');
         
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
