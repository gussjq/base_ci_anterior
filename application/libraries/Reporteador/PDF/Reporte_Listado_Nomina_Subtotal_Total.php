<?php

include_once '/../tcpdf/tcpdf.php';

class Reporte_Listado_Nomina_Subtotal_Total extends TCPDF {
    
    // contienen la informacion del catalogo de reportes
    private $oReporte;
    
    // se utiliza para pintar las lineas guia
    private $bFill = 0;
    
    // nombre del usuario el cual genero el reporte
    private $cNombreEmpleado = "";
    
    // viewmodel con la información del periodo
    private $oPeriodo;
    
    // primer cordenadas Y del primer registro
    private $iPrimerRow = 42;
    
    private $iPrimerRowPer = 49;
    
    private $iPrimerRowDes = 49;
    
    // contador de registros
    private $iContaPer = 0;
    
    // contador de conceptos de deduccion
    private $iContaDes = 0; 
    
    // altura calculada asignada al row
    private $iHeigth = 0; 
    
    // numero de px a incrementar por cada row
    private $iIncrementarHeigth = 4;  
    
    // numero meximo del eje y de los conceptos de deduccion
    private $iMaxPerY = 0; 
    
    // numero maximo del eje y de los conceptos deduccion
    private $iMaxDesY = 0; 
    
    // se guarda el eje y mas grande 
    private $iTotalesY =0;
        
    // total de percepciones
    private $fPercepciones = 0;
        
    // total de deducciones
    private $fDeducciones = 0;      
        
    // id del departamento
    private $idDepartamento = 0;
    
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
        $aSubtotalConceptos = & $this->_getSubtotalConceptos();
        $aTotalesConceptos = & $this->_getTotalesConceptos();
        
        // configuramos los datos 
        $this->SetFontSize(8);     
        $iContaTotal = count($aSubtotalConceptos);
        
        $bInit = true;
        // Colocamos los subtotales
        foreach($aSubtotalConceptos AS $oConcepto)
        {
            // si el departamento es diferente agregamos los datos informativos del departamento
            if($this->idDepartamento != $oConcepto->idDepartamento)
            {
                if($bInit === false)
                {
                    // agrega los subtotales por departamento
                    // recuperamos cual es el eje y mas grande si es el de percepcion o deduccion 
                    // para que apartir de ahi agregar los totales
                    $this->iTotalesY = ($this->iMaxPerY > $this->iMaxDesY) ? $this->iMaxPerY : $this->iMaxDesY;
                    $this->SetY($this->iTotalesY + 7);
                    $this->SetFontSize(8);
                    $this->SetFont(PDF_FONT_NAME_MAIN, "B");

                    // agregamos los datos del neto a pagar y totales de percepcion y deduccion
                    $this->MultiCell(20, 4, lang("reportes_reporte_listdo_neto") . ":", $this->bFill, 'R', 0, 0);
                    $this->MultiCell(90, 4, number_format(($this->fPercepciones - $this->fDeducciones), REDONDEAR_DECIMALES), $this->bFill, 'L', 0, 0);
                    $this->MultiCell(20, 4, number_format($this->fPercepciones, REDONDEAR_DECIMALES), $this->bFill, 'R', 0, 0);
                    $this->MultiCell(45, 4, '', $this->bFill, 'C', 0, 0);
                    $this->MultiCell(20, 4, number_format($this->fDeducciones, REDONDEAR_DECIMALES), $this->bFill, 'R', 0, 0);

                    $this->writeHTMLCell(192, 1, 10, $this->GetY() + 5, "<hr />", 0, 1, 0, 1);
                }
                
                
                $this->AddPage('P', 'A4');
                $this->SetY($this->iPrimerRow);
                
                $this->SetFont(PDF_FONT_NAME_MAIN, "B");
                $this->Cell(190, 5, $oConcepto->cCodigoDepartamento . " - " . $oConcepto->cNombreDepartamento);
                
                $this->SetFont(PDF_FONT_NAME_MAIN);
                $this->writeHTMLCell(190, 1, 10, $this->GetY() + 5, "<hr />", 0, 1, 0, 1);
                
                $this->Cell(55, 5, lang("reportes_reporte_listdo_total_de") . " " . $oConcepto->cCodigoDepartamento, $this->bFill, 1, '', 0, '', 0);
                $this->Cell(55, 5, $oConcepto->cNombreDepartamento, $this->bFill, 1, '', 0, '', 0);
                $this->Cell(55, 5, lang("reportes_reporte_listdo_nomina_empleados") . ": " . $oConcepto->iNumeroEmpleados, $this->bFill, 1, '', 0, '', 0);
                    
                // inicializar las variables
                $this->iContaPer = 0;
                $this->iContaDes = 0;
                
                $this->fPercepciones = 0;
                $this->fDeducciones = 0;
                
                $bInit=false;
            }        
            
            // acemos el calculo para saber el alto de la celda
            $rowcount = max(
                    $this->getNumLines($oConcepto->iNumeroConcepto,10),
                    $this->getNumLines($oConcepto->cNombreConcepto,35),
                    $this->getNumLines($oConcepto->iValor,10),
                    $this->getNumLines($oConcepto->fPagarGravable,20)
            );  
            
            $this->iHeigth = ($rowcount * $this->iIncrementarHeigth);
            
            $this->SetFont(PDF_FONT_NAME_MAIN);
            if($oConcepto->idTipoConcepto == CONCEPTOS_TIPO_PERCEPCION)
            {
                // para guardar el eje y apartir de donde se mostraran los datos de los conceptos
                if($this->iContaPer == 0)
                {
                    $this->SetY($this->iPrimerRowPer);
                }
                
                // se llenan los datos de las percepciones 
                $this->SetX(65);
                $this->MultiCell(10, $this->iHeigth, $oConcepto->iNumeroConcepto, $this->bFill, 'R', 0, 0);
                $this->MultiCell(35, $this->iHeigth, $oConcepto->cNombreConcepto, $this->bFill, 'L', 0, 0);
                $this->MultiCell(10, $this->iHeigth, ($oConcepto->iValor) ? $oConcepto->iValor : '', $this->bFill, 'C', 0, 0);
                $this->MultiCell(20, $this->iHeigth, number_format($oConcepto->fPagarGravable, REDONDEAR_DECIMALES), $this->bFill, 'R', 0, 0);
                
                // sumamos los montos de percepcion
                $this->fPercepciones += $oConcepto->fPagarGravable;
                
                // guardamos el eje Y de la ultima percepcion
                $this->iMaxPerY = $this->GetY();
                $this->iContaPer++;
            }
            else
            {
                // para recuperar el eje Y y se vea en la misma fila 
                if($this->iContaDes == 0)
                {
                    $this->SetY($this->iPrimerRowDes);
                }
                
                // agregamos los concepto de descuento
                $this->SetX(140);
                $this->MultiCell(10, $this->iHeigth, $oConcepto->iNumeroConcepto, $this->bFill, 'R', 0, 0);
                $this->MultiCell(35, $this->iHeigth, $oConcepto->cNombreConcepto, $this->bFill, 'L', 0, 0);
                $this->MultiCell(20, $this->iHeigth, number_format($oConcepto->fPagarGravable, REDONDEAR_DECIMALES), $this->bFill, 'R', 0, 0);
                
                // sumamos el monto de deduccion
                $this->fDeducciones += $oConcepto->fPagarGravable;
                $this->iContaDes++;
                $this->iMaxDesY = $this->GetY();
            }
            
            $this->Ln();
            $this->idDepartamento = $oConcepto->idDepartamento;            
        }   
        
        // agrega el ultimo subtotal del ultimo departamento ya que en el foreach no se puede agregar
        // recuperamos cual es el eje y mas grande si es el de percepcion o deduccion 
        // para que apartir de ahi agregar los totales
        $this->iTotalesY = ($this->iMaxPerY > $this->iMaxDesY) ? $this->iMaxPerY : $this->iMaxDesY;
        $this->SetY($this->iTotalesY + 7);
        $this->SetFontSize(8);
        $this->SetFont(PDF_FONT_NAME_MAIN, "B");

        // agregamos los datos del neto a pagar y totales de percepcion y deduccion
        $this->MultiCell(20, 4, lang("reportes_reporte_listdo_neto") . ":", $this->bFill, 'R', 0, 0);
        $this->MultiCell(90, 4, number_format(($this->fPercepciones - $this->fDeducciones), REDONDEAR_DECIMALES), $this->bFill, 'L', 0, 0);
        $this->MultiCell(20, 4, number_format($this->fPercepciones, REDONDEAR_DECIMALES), $this->bFill, 'R', 0, 0);
        $this->MultiCell(45, 4, '', $this->bFill, 'C', 0, 0);
        $this->MultiCell(20, 4, number_format($this->fDeducciones, REDONDEAR_DECIMALES), $this->bFill, 'R', 0, 0);

        $this->writeHTMLCell(192, 1, 10, $this->GetY() + 5, "<hr />", 0, 1, 0, 1);
        
        
        // agregamos una pagina para el gran total
        $this->SetFont(PDF_FONT_NAME_MAIN);
        $this->AddPage('P', 'A4');
        $this->SetY($this->iPrimerRow);

        $this->iContaPer = 0; $this->iContaDes = 0;
        $this->fPercepciones = 0; $this->fDeducciones = 0;
        
        $bInit = true;
        
        foreach($aTotalesConceptos AS $oConcepto)
        {
            // colocamos los datos informativos del gran total, solo tiene que ir una sola vez
            if($bInit === true)
            {
                $this->SetY($this->iPrimerRow);
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
            
            $this->iHeigth = ($rowcount * $this->iIncrementarHeigth);
            
            
            // validamos si es una percepcion o deduccion
            if($oConcepto->idTipoConcepto == 1)
            {
                // para guardar el eje y apartir de donde se mostraran los datos de los conceptos
                if($this->iContaPer == 0)
                {
                    $this->SetY($this->iPrimerRow);
                }
                
                // se llenan los datos de las percepciones 
                $this->SetX(65);
                $this->MultiCell(10, $this->iHeigth, $oConcepto->iNumeroConcepto, $this->bFill, 'R', 0, 0);
                $this->MultiCell(35, $this->iHeigth, $oConcepto->cNombreConcepto, $this->bFill, 'L', 0, 0);
                $this->MultiCell(10, $this->iHeigth, ($oConcepto->iValor) ? $oConcepto->iValor : '', $this->bFill, 'C', 0, 0);
                $this->MultiCell(20, $this->iHeigth, number_format($oConcepto->fPagarGravable, REDONDEAR_DECIMALES), $this->bFill, 'R', 0, 0);
                
                // sumamos los montos de percepcion
                $this->fPercepciones+=$oConcepto->fPagarGravable;
                
                // guardamos el eje Y de la ultima percepcion
                $this->iMaxPerY = $this->GetY();
                $this->iContaPer++;
            }
            else
            {
                // para recuperar el eje Y y se vea en la misma fila 
                if($this->iContaDes == 0)
                {
                    $this->SetY($this->iPrimerRow);
                }
                
                // agregamos los concepto de descuento
                $this->SetX(140);
                $this->MultiCell(10, $this->iHeigth, $oConcepto->iNumeroConcepto, $this->bFill, 'R', 0, 0);
                $this->MultiCell(35, $this->iHeigth, $oConcepto->cNombreConcepto, $this->bFill, 'L', 0, 0);
                $this->MultiCell(20, $this->iHeigth, number_format($oConcepto->fPagarGravable, REDONDEAR_DECIMALES), $this->bFill, 'R', 0, 0);
                
                // sumamos el monto de deduccion
                $this->fDeducciones +=$oConcepto->fPagarGravable;
                $this->iContaDes++;
                $this->iMaxDesY = $this->GetY();
            }
            
            $this->Ln();
            
            $bInit= false;
        }
        
        $this->Ln();        
        
        // recuperamos cual es el eje y mas grande si es el de percepcion o deduccion 
        // para que apartir de ahi agregar los totales
        $this->iTotalesY = ($this->iMaxPerY > $this->iMaxDesY) ? $this->iMaxPerY : $this->iMaxDesY;
        $this->SetY($this->iTotalesY + 7);
        $this->SetFontSize(8);
        $this->SetFont(PDF_FONT_NAME_MAIN, "B");
        
        // agregamos los datos del neto a pagar y totales de percepcion y deduccion
        $this->MultiCell(20, 4, lang("reportes_reporte_listdo_neto") . ":", $this->bFill, 'R', 0, 0);
        $this->MultiCell(90, 4, number_format(($this->fPercepciones - $this->fDeducciones), REDONDEAR_DECIMALES), $this->bFill, 'L', 0, 0);
        $this->MultiCell(20, 4, number_format($this->fPercepciones, REDONDEAR_DECIMALES), $this->bFill, 'R', 0, 0);
        $this->MultiCell(45, 4, '', $this->bFill, 'C', 0, 0);
        $this->MultiCell(20, 4, number_format($this->fDeducciones, REDONDEAR_DECIMALES), $this->bFill, 'R', 0, 0);
        
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
   
   private function & _getSubtotalConceptos()
   {
       $oNomina = new Nomina_ViewModel();
       $oNomina->idTipoNomina = $this->oReporte->idTipoNomina;
       $oNomina->idPeriodo = $this->oReporte->idPeriodo;
       
       $aConceptos = & $this->CI->Nomina_model->getSubtotalConceptos($oNomina);
       
       return $aConceptos;
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
