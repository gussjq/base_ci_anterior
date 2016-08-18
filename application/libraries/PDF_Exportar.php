<?php

require_once('fpdf/PDF.php');

class PDF_Exportar extends PDF {

    private $_WIDTH = 195.9;
    private $bFondo;
    private $altoCelda = 7;
    private $dataTables;
    private $struct;
    private $gruops;
    private $titulos;
    private $colsWidth;
    private $alineacion;
    private $colores;
    private $logoEmpresa;
    private $empresaNombre;
    private $tituloReporte;

    public function __construct($struct, $gruops, $apariencia, $datosEncabezado = null, $bFondo = true) {
        parent::__construct('P', 'mm', 'Letter');

        $this->titulos = array();
        $this->colsWidth = array();
        $this->alineacion = array();
        $l = 0;
        $widthTotal = 0;
        $bAnchoPredef = true;
        foreach ($struct['aColumnas'] as $columna) {
            if (!in_array($columna->Nombre, $gruops)) {
                if ($columna->Ancho) {
                    $widthTotal += $columna->Ancho;
                } else {
                    $bAnchoPredef = false;
                }
                $l++;
            }
        }
        foreach ($struct['aColumnas'] as $key => $columna) {
            if (!in_array($columna->Nombre, $gruops)) {
                $this->titulos[$key] = utf8_decode(lang($columna->Etiqueta));
                if ($bAnchoPredef && $widthTotal > 0) {
                    $this->colsWidth[$key] = $columna->Ancho * $this->_WIDTH / $widthTotal;
                } else {
                    $this->colsWidth[$key] = $this->_WIDTH / $l;
                }
                $this->alineacion[$key] = $columna->Alineacion;
            }
        }

        $this->colores = array();
        $this->colores['impar'] = hexToDecColor($apariencia->cTablaFila1);
        $this->colores['par'] = hexToDecColor($apariencia->cTablaFila2);
        $this->colores['grupo'] = array(209, 207, 208);
        foreach ($struct['aFilas'] as $key => $fila) {
            if (array_key_exists($fila->Tipo, $this->colores)) {
                if ($fila->Color) {
                    $this->colores[$fila->Tipo] = hexToDecColor($fila->Color);
                }
            }
        }
        $this->struct = $struct;
        $this->gruops = $gruops;
        $this->bFondo = $bFondo;

        if (is_array($datosEncabezado)) {
            if (file_exists($datosEncabezado['logo'])) {
                $this->logoEmpresa = $datosEncabezado['logo'];
            } else {
                $this->logoEmpresa = false;
            }
            $this->empresaNombre = $datosEncabezado['nombre'];
            $this->tituloReporte = $datosEncabezado['titulo'];
        } else {
            $this->logoEmpresa = false;
            $this->empresaNombre = '';
            $this->tituloReporte = '';
        }
    }

    function Header() {
        $imgHeight = 10;
        $this->SetFont('Arial', '', PDF_FUENTE_CHICA);
        $this->Cell($this->_WIDTH / 2, $this->altoCelda, date('d/m/Y'), 0, 0, 'L');
        $this->Cell($this->_WIDTH / 2, $this->altoCelda, date('H:i:s'), 0, 0, 'R');
        $this->Ln();
        $this->SetFont('Arial', 'B', PDF_FUENTE_MEDIANA);
        if ($this->logoEmpresa) {
            $size = getimagesize($this->logoEmpresa);
            if (is_array($size)) {
                $offset = $size[0] * $imgHeight / $size[1];
                $this->Image($this->logoEmpresa, ($this->_WIDTH + 20) / 2 - $offset / 2, 10, 0, $imgHeight);
            } else {
                $this->Image($this->logoEmpresa, 40, 10, 0, $imgHeight);
            }
            $this->SetWidths(array($this->_WIDTH));
            $this->Cell($this->_WIDTH, $imgHeight - $this->altoCelda, " ", 0, 0, 'R');
            $this->Ln();
        } else {
            $this->Cell($this->_WIDTH, $this->altoCelda, $this->empresaNombre, 0, 0, 'C');
            $this->Ln();
        }
        if (is_array($this->tituloReporte)) {
            foreach ($this->tituloReporte as $titulo) {
                $this->Cell($this->_WIDTH, $this->altoCelda, $titulo, 0, 0, 'C');
                $this->Ln();
            }
        } else {
            $this->Cell($this->_WIDTH, $this->altoCelda, $this->tituloReporte, 0, 0, 'C');
            $this->Ln();
        }

        $this->SetFont('Arial', 'B', PDF_FUENTE_MEDIANA);
        $this->SetFillColor(230, 230, 230);
        $this->SetWidths($this->colsWidth);
        $this->Row($this->titulos, 1, $this->bFondo);
    }

    public function build($dataTables) {
        reset($dataTables);
        $pila = array($dataTables);
        $bPrimerGrupo = true;

        $this->AddPage();
        $impar = true;
        while (($l = count($pila)) > 0) {
            $tope = & $pila[$l - 1];
            if (is_array($tope)) {
                $hijo = current($tope);
                if ($hijo !== false) {
                    if (is_array($hijo) && is_array(current($hijo))) {
                        $campoGrupo = $this->gruops[count($pila) - 1];
                        foreach ($this->struct['aColumnas'] as $key => $columna) {
                            if ($campoGrupo == $columna->Nombre) {
                                if ($columna->Pivote === 'PAGEBREAK') {
                                    if ($bPrimerGrupo) {
                                        $bPrimerGrupo = false;
                                    } else {
                                        $this->AddPage();
                                    }
                                }
                                break;
                            }
                        }

                        $this->SetFont('Arial', 'B', PDF_FUENTE_MEDIANA);
                        $this->SetFillColor($this->colores['grupo'][0], $this->colores['grupo'][1], $this->colores['grupo'][2]);
                        $this->SetWidths(array($this->_WIDTH));
                        $this->SetAligns(array('L'));
                        $this->Row(array(key($tope)), 1, $this->bFondo);
                    }
                    array_push($pila, $hijo);
                    next($tope);
                } else {
                    array_pop($pila);
                }
            } else {
                $elemento = array_pop($pila);
                $arr = array_pop($pila);
                if (is_array($arr)) {
                    $l = count($arr) - 1;
                    $i = 0;
                    $printRow = array();
                    foreach ($arr as $key => $elemento) {
                        if ($i < $l) {
                            if (!$elemento) {
                                $elemento = '--';
                            }
                            $printRow[$key] = utf8_decode(trim($elemento));
                        }
                        $i++;
                    }
                    $l = count($this->struct['aColumnas']);
                    if ($arr[$l] == 'N') {
                        if ($impar) {
                            $this->SetFillColor($this->colores['impar'][0], $this->colores['impar'][1], $this->colores['impar'][2]);
                        } else {
                            $this->SetFillColor($this->colores['par'][0], $this->colores['par'][1], $this->colores['par'][2]);
                        }
                        $impar = !$impar;
                    } else if ($arr[$l] == 'GR') {
                        $this->SetFillColor($this->colores['grupo'][0], $this->colores['grupo'][1], $this->colores['grupo'][2]);
                    }
                    $this->SetFont('Arial', '', PDF_FUENTE_MEDIANA);
                    $this->SetWidths($this->colsWidth);
                    $this->SetAligns($this->alineacion);
                    $this->Row($printRow, 1, $this->bFondo);
                }
            }
        }
    }

    function Footer() {

        $this->SetY(-15);
        $this->SetFont('Arial', '', PDF_FUENTE_CHICA);
        $this->Cell(0, 10, utf8_decode(lang('página')) . ' ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

}