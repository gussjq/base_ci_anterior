<?php
require('fpdf.php');

class PDF extends FPDF {
	
	public $widths;
	public $aligns;
	public $EmpresaName;
	public $AseguradoraName;
	public $LoteLiquidacionNum;
	public $RamoName;
	
	
	Public function __construc() {
		parent::__construct('P', 'mm', 'Letter');
	}
	
	public function Header() {		
		$this->HeaderContent();			
	}
	
	
	public function  HeaderContent() {
		$this->Cell(70);
		$this->SetFontSize(12); 
		$this->Cell(60,10, $this->EmpresaName , 0, 0, 'C');
		$this->Cell(50);		
		$this->SetFont('Arial');
		$this->Cell(20,10, lang('liqcom.liquidacionloteimpresion.pagnum'). $this->PageNo() , 0, 0, 'L');
		$this->Ln(); 
		$this->Cell(90);		
		$this->SetFont('Arial' , 'B');
		$this->SetFontSize(10); 
		$this->Cell(20,10, lang('liqcom.liquidacionloteimpresion.titulo') , 0, 0, 'C');
		$this->Ln(); 		
		$this->Cell(110,10, lang('liqcom.liquidacionloteimpresion.aseguradoraencabezado'), 0, 0, 'L');		
		$this->Ln(); 	
	}
	
	public function SetWidths($w) 	{
	    //Set the array of column widths
	    $this->widths=$w;
	}
	
	public function SetAligns($a) {
	    //Set the array of column alignments
	    $this->aligns=$a;
	}
	
	public function Row($data, $bBorde = 0, $bFill = false) {
	    //Calculate the height of the row
	    $nb=0;
	    foreach ($data as $i => $value){
	    	if (array_key_exists($i, $this->widths)){
				$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
	    	}
		}
	    $h=4*$nb;
	    //Issue a page break first if needed
	    $this->CheckPageBreak($h);
	    //Draw the cells of the row
		foreach ($data as $i => $value)
	    {
	    	if (array_key_exists($i, $this->widths)){
				$w=$this->widths[$i];
				$a=isset($this->aligns[$i]) ? strtoupper($this->aligns[$i]) : 'L';
				//Save the current position
				$x=$this->GetX();
				$y=$this->GetY();
				//Draw the border
				$this->Rect($x,$y,$w,$h,($bFill)?'FD':'D');
				//Print the text
				$this->MultiCell($w, 4, $data[$i], 0, $a, false);
				//Put the position to the right of the cell
				$this->SetXY($x+$w,$y);
	    	}
	    }
	    //Go to the next line
	    $this->Ln($h);
	}
	
	public function CheckPageBreak($h) {
	    //If the height h would cause an overflow, add a new page immediately
	    if($this->GetY()+$h>$this->PageBreakTrigger)
	        $this->AddPage($this->CurOrientation);
	}
	
	public function NbLines($w,$txt) {
	    //Computes the number of lines a MultiCell of width w will take
	    $cw=&$this->CurrentFont['cw'];
	    if($w==0)
	        $w=$this->w-$this->rMargin-$this->x;
	    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
	    $s=str_replace("\r",'',$txt);
	    $nb=strlen($s);
	    if($nb>0 and $s[$nb-1]=="\n")
	        $nb--;
	    $sep=-1;
	    $i=0;
	    $j=0;
	    $l=0;
	    $nl=1;
	    while($i<$nb)
	    {
	        $c=$s[$i];
	        if($c=="\n")
	        {
	            $i++;
	            $sep=-1;
	            $j=$i;
	            $l=0;
	            $nl++;
	            continue;
	        }
	        if($c==' ')
	            $sep=$i;
	        $l+=$cw[$c];
	        if($l>$wmax)
	        {
	            if($sep==-1)
	            {
	                if($i==$j)
	                    $i++;
	            }
	            else
	                $i=$sep+1;
	            $sep=-1;
	            $j=$i;
	            $l=0;
	            $nl++;
	        }
	        else
	            $i++;
	    }
	    return $nl;
	}
	
	public function fillRow($text = '', $maxlenght = 0) {	
		$text =  str_pad($text, $maxlenght -1); 
		return $text;		
	}

}