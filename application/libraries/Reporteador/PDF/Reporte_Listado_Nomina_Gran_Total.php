<?php

include_once '/../tcpdf/tcpdf.php';

class Reporte_Listado_Nomina_Gran_Total extends TCPDF {
    
    private $oReporte;
    private $bFill = 0;
    private $cNombreEmpleado ="";
    private $oPeriodo;
    private $iPrimerRow = 40;
    
    public function __construct($orientation = 'P', $unit = 'mm', $format = 'A4', $unicode = true, $encoding = 'UTF-8', $diskcache = false)
    {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache);
        
        $this->CI = &get_instance();
        
        $this->CI->load->model("nomina/Nomina_model");
        $this->CI->load->library("ViewModels/Nomina_ViewModel");
        $this->CI->load->library("Nominas");
    }
    
    /**
     * Metodo que simula ser nuestro constructor
     * @param array $aEmpleados
     */
    public function initialize($oReporte)
    {
        $this->oReporte = $oReporte;
        $this->oPeriodo = $this->getPeriodo();
    }
    
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
        $this->Cell(190, 10, $this->oPeriodo->cNombre, $this->bFill, 1, '', 0, '', 0);
        
        $this->SetY(24);
        $this->Cell(190, 10, "Del " . $this->oPeriodo->dtFechaInicial . " al " . $this->oPeriodo->dtFechaFinal, $this->bFill, 1, '', 0, '', 0);
        
        $this->SetFontSize(8);
        $this->Ln();
        $this->writeHTMLCell(190, 1, 10, 35, "<hr />", 0, 1, 0, 1);
        
        $this->SetY(36);
        $this->MultiCell(55, 4, lang("reportes_reporte_listdo_nomina_empleado"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(10, 4, lang("reportes_reporte_listdo_nomina_numero"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(35, 4, lang("reportes_reporte_listdo_nomina_percepcion"), $this->bFill, 'L', 0, 0);
        $this->MultiCell(10, 4, lang("reportes_reporte_listdo_nomina_dias"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(20, 4, lang("reportes_reporte_listdo_nomina_monto"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(10, 4, lang("reportes_reporte_listdo_nomina_numero"), $this->bFill, 'C', 0, 0);
        $this->MultiCell(35, 4, lang("reportes_reporte_listdo_nomina_deduccion"), $this->bFill, 'L', 0, 0);
        $this->MultiCell(20, 4, lang("reportes_reporte_listdo_nomina_monto"), $this->bFill, 'C', 0, 0);
         
        $this->writeHTMLCell(190, 1, 10, 41, "<hr />", 0, 1, 0, 1);
                 
        
    }
    
    /**
     * Metodo que se encarga de generar el recibo de nomina
     */
    public function generarReporte()
    {
        $aTotalesConceptos = & $this->_getTotalesConceptos();
        
        // agregamos una pagina para el gran total
        $this->SetFontSize(8);
        $this->AddPage('P', 'A4');
        $this->SetY($this->iPrimerRow);
        $iConta = 0;
        
        $fPercepciones = 0;
        $fDeducciones = 0;
        
        $iX; $iHeigth = 0; $iIncrementarHeigth = 4;  
        $iY; $iContaDes = 0; $iMaxPerY = 0; $iMaxDesY = 0; $iTotalesY =0;
        
        foreach($aTotalesConceptos AS $oConcepto)
        {
            // colocamos los datos informativos del gran total, solo tiene que ir una sola vez
            if($iConta == 0)
            {
                $iY = $this->GetY() + 2;
                $this->SetY($iY);
                $this->Cell(55, 5, lang("reportes_reporte_listdo_gran_total"), $this->bFill, 1, '', 0, '', 0);
                $this->Cell(55, 5, lang("reportes_reporte_listdo_empresa"), $this->bFill, 1, '', 0, '', 0);
                $this->Cell(55, 5, lang("reportes_reporte_listdo_nomina_empleados") . ": " . $oConcepto->iNumeroEmpleados, $this->bFill, 1, '', 0, '', 0);
            }
            
            // acemos el calculo para saber el alto de la celda
            $rowcount = max(
                    $this->getNumLines($oConcepto->iNumeroConcepto,10),
                    $this->getNumLines($oConcepto->cNombreConcepto,35),
                    $this->getNumLines($oConcepto->iValor,10),
                    $this->getNumLines($oConcepto->fPagarGravable,20)
            );  
            
            $iHeigth = ($rowcount * $iIncrementarHeigth);
            
            
            // validamos si es una percepcion o deduccion
            if($oConcepto->idTipoConcepto == 1)
            {
                // para guardar el eje y apartir de donde se mostraran los datos de los conceptos
                if($iConta == 0)
                {
                    $this->SetY($iY);
                }
                
                // se llenan los datos de las percepciones 
                $this->SetX(65);
                $this->MultiCell(10, $iHeigth, $oConcepto->iNumeroConcepto, $this->bFill, 'R', 0, 0);
                $this->MultiCell(35, $iHeigth, $oConcepto->cNombreConcepto, $this->bFill, 'L', 0, 0);
                $this->MultiCell(10, $iHeigth, ($oConcepto->iValor) ? $oConcepto->iValor : '', $this->bFill, 'C', 0, 0);
                $this->MultiCell(20, $iHeigth, number_format($oConcepto->fPagarGravable, REDONDEAR_DECIMALES), $this->bFill, 'R', 0, 0);
                
                // sumamos los montos de percepcion
                $fPercepciones+=$oConcepto->fPagarGravable;
                
                // guardamos el eje Y de la ultima percepcion
                $iMaxPerY = $this->GetY();
            }
            else
            {
                // para recuperar el eje Y y se vea en la misma fila 
                if($iContaDes == 0)
                {
                    $this->SetY($iY);
                }
                
                // agregamos los concepto de descuento
                $this->SetX(140);
                $this->MultiCell(10, $iHeigth, $oConcepto->iNumeroConcepto, $this->bFill, 'R', 0, 0);
                $this->MultiCell(35, $iHeigth, $oConcepto->cNombreConcepto, $this->bFill, 'L', 0, 0);
                $this->MultiCell(20, $iHeigth, number_format($oConcepto->fPagarGravable, REDONDEAR_DECIMALES), $this->bFill, 'R', 0, 0);
                
                // sumamos el monto de deduccion
                $fDeducciones+=$oConcepto->fPagarGravable;
                $iContaDes++;
                
                
                $iMaxDesY = $this->GetY();
            }
            
            $this->Ln();
            $iConta++;
        }
        
        $this->Ln();        

               
        // totales
        
        // recuperamos cual es el eje y mas grande si es el de percepcion o deduccion 
        // para que apartir de ahi agregar los totales
        $iTotalesY = ($iMaxPerY > $iMaxDesY) ? $iMaxPerY :$iMaxDesY;
        $this->SetY($iTotalesY + 7);
        $this->SetFontSize(8);
        $this->SetFont(PDF_FONT_NAME_MAIN, "B");
        
        // agregamos los datos del neto a pagar y totales de percepcion y deduccion
        $this->MultiCell(20, 4, lang("reportes_reporte_listdo_neto") . ":", $this->bFill, 'R', 0, 0);
        $this->MultiCell(90, 4, number_format(($fPercepciones - $fDeducciones), REDONDEAR_DECIMALES), $this->bFill, 'L', 0, 0);
        $this->MultiCell(20, 4, number_format($fPercepciones, REDONDEAR_DECIMALES), $this->bFill, 'R', 0, 0);
        $this->MultiCell(45, 4, '', $this->bFill, 'C', 0, 0);
        $this->MultiCell(20, 4, number_format($fDeducciones, REDONDEAR_DECIMALES), $this->bFill, 'R', 0, 0);
        
        $this->writeHTMLCell(192, 1, 10, $this->GetY() +5, "<hr />", 0, 1, 0, 1);
        
        $this->Output($this->oReporte->cNombre . '.pdf', 'I');
    }
    
    
   
   
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
   
   private function getPeriodo()
   {
       $this->CI->load->model("nomina/Periodo_model");
       $this->CI->load->library("ViewModels/Periodo_ViewModel");
       $this->CI->load->library("seguridad");
       
       $dbPeriodo = $this->CI->Periodo_model->find(array(
           "idPeriodo" => $this->oReporte->idPeriodo, "bBorradoLogico" => NO
       ));
       
       $oPeriodo = new Periodo_ViewModel();
       if(is_object($dbPeriodo))
       {
           $oPeriodo = $this->CI->seguridad->dbToView($oPeriodo, $dbPeriodo);
       }
       
       return $oPeriodo;
   }
   
   private function & _getTotalesConceptos()
   {
       $oNomina = new Nomina_ViewModel();
       $oNomina->idTipoNomina = $this->oReporte->idTipoNomina;
       $oNomina->idPeriodo = $this->oReporte->idPeriodo;
       
       $aConceptos = & $this->CI->Nomina_model->getTotalesConceptos($oNomina);
       
       return $aConceptos;
   }
}
