<?php

include_once '/../tcpdf/tcpdf.php';
include_once '/../../ReportesData.php';

class Reporte_Constancias_De_Sueldo_Forma_37 extends TCPDF {
    
    private $oReporte;
    private $bFill = 0;
    
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

    }


    /**
     * Metodo que se encarga de generar el cuerpo del reporte solicitado
     */
    public function generarReporte()
    {
        // recuperamos los empleados que apareceran en el reporte 
        $aEmpleados = & $this->_getData();
        
        // recuperamos la imagenes que seran utilizados para el reporte
        $cPagina1 = getDocumentRoot() . DIRECTORIO_REPORTES_SISTEMA . REPORTE_CONSTANCIA_SUELDO_FORMA_37_PAGINA_UNO;
        $cPagina2 = getDocumentRoot() . DIRECTORIO_REPORTES_SISTEMA . REPORTE_CONSTANCIA_SUELDO_FORMA_37_PAGINA_DOS;
        $cPagina3 = getDocumentRoot() . DIRECTORIO_REPORTES_SISTEMA . REPORTE_CONSTANCIA_SUELDO_FORMA_37_PAGINA_TRES;
        
        // configuramos los datos 
        $this->SetFontSize(8);
        foreach($aEmpleados AS $oEmpleado)
        {
            $this->_pagina1($cPagina1, $oEmpleado);
            $this->_pagina2($cPagina2, $oEmpleado);
            $this->_pagina3($cPagina3, $oEmpleado);
        }
        
        $this->Output($this->oReporte->cNombre .'.pdf', 'I');
    }
    
    public function _pagina1($cPagina1, & $oEmpleado) 
    {
        $this->AddPage('P', 'A4');
        $this->Image($cPagina1, -2, 0, 220);

        // DATOS DEL TRABAJADOR O ASIMILADO A SALARIO
        // periodo de la constancia
        if($oEmpleado->dtFechaInicio > $oEmpleado->dtFechaIngreso)
        {
            $this->SetXY(56, 25);
            $this->Cell(10, 5, date("m", strtotime($oEmpleado->dtFechaInicio)), $this->bFill, 0, 'C');
        }
        else
        {
            $this->SetXY(56, 25);
            $this->Cell(10, 5, date("m", strtotime($oEmpleado->dtFechaIngreso)), $this->bFill, 0, 'C');
        }
        
        // periodo de la constancia
        if($oEmpleado->dtFechaFin > $oEmpleado->dtFechaBaja)
        {
            $this->SetXY(68, 25);
            $this->Cell(10, 5, date("m", strtotime($oEmpleado->dtFechaFin)), $this->bFill, 0, 'C');
        }
        else
        {
            $this->SetXY(68, 25);
            $this->Cell(10, 5, date("m", strtotime($oEmpleado->dtFechaBaja)), $this->bFill, 0, 'C');
        }

        $this->SetXY(80, 25);
        $this->Cell(15, 5, $oEmpleado->iAno, $this->bFill, 0, 'C');

        // rfc
        $this->SetXY(38, 39);
        $this->Cell(57, 5, $oEmpleado->cRFC, $this->bFill);

        // curp
        $this->SetXY(133, 39);
        $this->Cell(57, 5, $oEmpleado->cCurp, $this->bFill);

        // apellido paterno
        $this->SetXY(13, 48);
        $this->Cell(57, 5, $oEmpleado->cApellidoPaterno, $this->bFill);

        // apellido materno
        $this->SetXY(73, 48);
        $this->Cell(57, 5, $oEmpleado->cApellidoMaterno, $this->bFill);

        // nombre
        $this->SetXY(133, 48);
        $this->Cell(57, 5, $oEmpleado->cNombre, $this->bFill);

        // areageografica
        $this->SetXY(40, 58);
        $this->Cell(5, 5, $oEmpleado->cAbreviacion, $this->bFill, 0, 'C');

        // calcilo patron
        if ($oEmpleado->bCalcular) 
        {
            $this->SetXY(84, 58);
            $this->Cell(5, 5, 'X', $this->bFill, 0, 'C');
        }

        // del ejercicio que declara PREGUNTAR SI SIEMPRE SERA ASI Y QUE PASA CON LA TARIFA 1991
        $this->SetXY(147, 58);
        $this->Cell(5, 5, 'X', $this->bFill);


        // si el trabajador es sindicalizado
        if ($oEmpleado->bSindicalizado) 
        {
            $this->SetXY(59, 76);
            $this->Cell(5, 5, 'X', $this->bFill);
        }

        // si el trabajador es asimilado a salarios
        if ($oEmpleado->cCodigoAsimilado) 
        {
            $this->SetXY(113, 76);
            $this->Cell(9, 5, $oEmpleado->cCodigoAsimilado, $this->bFill, 0, 'C');
        }

        // clave de la entidad federativa
        $this->SetXY(180, 76);
        $this->Cell(10, 5, $oEmpleado->cCodigoEstado, $this->bFill, 0, 'C');


        // IMPUESTO SOBRE LA RENTA
        // total de ingresoso por sueldos, salarios y conceptos asimilados
        if($oEmpleado->fISRA)
        {
            $this->SetXY(64, 118);
            $this->Cell(36, 5, round($oEmpleado->fISRA,REDONDEAR_DECIMALES), $this->bFill, 0, 'C');
        }
        
        
        // impuesto local a los ingresos por sueldos
        if($oEmpleado->fISRB)
        {
            $this->SetXY(64, 126);
            $this->Cell(36, 5, round($oEmpleado->fISRB,REDONDEAR_DECIMALES), $this->bFill, 0, 'C');
        }
        
        // ingresos excentos
        if($oEmpleado->fISRC)
        {
            $this->SetXY(64, 133);
            $this->Cell(36, 5, round($oEmpleado->fISRC,REDONDEAR_DECIMALES), $this->bFill, 0, 'C');
        }
        
        // total de aportaciones voluntarias
        if($oEmpleado->fISRD)
        {
             $this->SetXY(64, 141);
            $this->Cell(36, 5, round($oEmpleado->fISRD,REDONDEAR_DECIMALES), $this->bFill, 0, 'C');
        }
        
        // ingresos no acumulables
        if($oEmpleado->fISRE)
        {
            $this->SetXY(64, 149);
            $this->Cell(36, 5, round($oEmpleado->fISRE,REDONDEAR_DECIMALES), $this->bFill, 0, 'C');
        }
        
         // ingresos acumulables
        if($oEmpleado->fISRF)
        {
            $this->SetXY(64, 157);
            $this->Cell(36, 5, round($oEmpleado->fISRF, REDONDEAR_DECIMALES), $this->bFill, 0, 'C');
        }        
        
        // impuesto conforme a la tarifa anual
        if($oEmpleado->fISRG)
        {
            $this->SetXY(64, 164);
            $this->Cell(36, 5, round($oEmpleado->fISRG, REDONDEAR_DECIMALES), $this->bFill, 0, 'C');
        }
        
        // subcidio acreditable
        if($oEmpleado->fISRH)
        {
            $this->SetXY(64, 172);
            $this->Cell(36, 5, round($oEmpleado->fISRH, REDONDEAR_DECIMALES), $this->bFill, 0, 'C');
        }
        
        // subcidio no acreditable
        if($oEmpleado->fISRI)
        {
            $this->SetXY(154, 118);
            $this->Cell(36, 5, round($oEmpleado->fISRI, REDONDEAR_DECIMALES), $this->bFill, 0, 'C');
        }
        
        // monto subcidio para el empleado en el ejercicio
        if($oEmpleado->fISRJ)
        {
            $this->SetXY(154, 126);
            $this->Cell(36, 5, round($oEmpleado->fISRJ, REDONDEAR_DECIMALES), $this->bFill, 0, 'C');
        }
        
        // monto del subcidio acreditable fraccion III
        if($oEmpleado->fISRK)
        {
            $this->SetXY(154, 134);
            $this->Cell(36, 5, round($oEmpleado->fISRK, REDONDEAR_DECIMALES), $this->bFill, 0, 'C');
        }
        
        // monto del subcidio acreditable fracion IV
        if($oEmpleado->fISRL)
        {
            $this->SetXY(154, 141);
            $this->Cell(36, 5, round($oEmpleado->fISRL, REDONDEAR_DECIMALES), $this->bFill, 0, 'C');
        }
        
        // impuesto sobre ingresos acumulables
        if($oEmpleado->fISRM)
        {
            $this->SetXY(154, 149);
            $this->Cell(36, 5, round($oEmpleado->fISRM, REDONDEAR_DECIMALES), $this->bFill, 0, 'C');
        }
        
        if($oEmpleado->fISRN)
        {
            $this->SetXY(154, 156);
            $this->Cell(36, 5, round($oEmpleado->fISRN, REDONDEAR_DECIMALES), $this->bFill, 0, 'C');
        }
        
        // impuesto sobre la renta causado en el ejercicio
        if($oEmpleado->fISRO)
        {
            $this->SetXY(154, 164);
            $this->Cell(36, 5, round($oEmpleado->fISRO,REDONDEAR_DECIMALES), $this->bFill, 0, 'C');
        }
        
        // isr retenido al contribuyente
        if($oEmpleado->fISRP)
        {
            $this->SetXY(154, 172);
            $this->Cell(36, 5, round($oEmpleado->fISRP,REDONDEAR_DECIMALES), $this->bFill, 0, 'C');
        }
    }

    private function _pagina2($cPagina2, & $oEmpleado)
    {
        $this->AddPage('P', 'A4'); 
        $this->Image($cPagina2, -2, 0, 220);
        
        // sueldo y salarios
        $this->SetXY(64,109);
        $this->Cell(36, 5, $oEmpleado->fSueldoGravado, $this->bFill, 0, 'C');
        
        $this->SetXY(113,109);
        $this->Cell(36, 5, $oEmpleado->fSueldoExcento, $this->bFill, 0, 'C');
        
        // gratificacion anual
        $this->SetXY(64,117);
        $this->Cell(36, 5, $oEmpleado->fGratificacionAnualGravado, $this->bFill, 0, 'C');
        
        $this->SetXY(113,117);
        $this->Cell(36, 5, $oEmpleado->fGratificacionAnualExcento, $this->bFill, 0, 'C');
        
        // viaticos gastos de viaje
        $this->SetXY(64,125);
        $this->Cell(36, 5, $oEmpleado->fViaticosGastosViajeGravado, $this->bFill, 0, 'C');
        
        $this->SetXY(113,125);
        $this->Cell(36, 5, $oEmpleado->fViaticosGastosViajeExcento, $this->bFill, 0, 'C');
        
        // tiempo extra
        $this->SetXY(64,132);
        $this->Cell(36, 5, $oEmpleado->fTiempoExtraordinarioGravado, $this->bFill, 0, 'C');
        
        $this->SetXY(113,132);
        $this->Cell(36, 5, $oEmpleado->fTiempoExtraordinarioExcento, $this->bFill, 0, 'C');
        
        // prima vacacional
        $this->SetXY(64,140);
        $this->Cell(36, 5, $oEmpleado->fPrimaVacacionalGravado, $this->bFill, 0, 'C');
        
        $this->SetXY(113,140);
        $this->Cell(36, 5, $oEmpleado->fPrimaVacacionalExcento, $this->bFill, 0, 'C');
        
        // prima dominical
        $this->SetXY(64,148);
        $this->Cell(36, 5, $oEmpleado->fPrimaDominicalGravado, $this->bFill, 0, 'C');
        
        $this->SetXY(113,148);
        $this->Cell(36, 5, $oEmpleado->fPrimaDominicalExcento, $this->bFill, 0, 'C');
        
        // ptu
        $this->SetXY(64,156);
        $this->Cell(36, 5, $oEmpleado->fPtuGravado, $this->bFill, 0, 'C');
        
        $this->SetXY(113,156);
        $this->Cell(36, 5, $oEmpleado->fPtuGravado, $this->bFill, 0, 'C');
        
        // reembolso gastos medicos
        $this->SetXY(64,163);
        $this->Cell(36, 5, $oEmpleado->fReembolsosGastosMedicosGravado, $this->bFill, 0, 'C');
        
        $this->SetXY(113,163);
        $this->Cell(36, 5, $oEmpleado->fReembolsosGastosMedicosExcento, $this->bFill, 0, 'C');
        
        // fondo de ahorro
        $this->SetXY(64,170);
        $this->Cell(36, 5, $oEmpleado->fFondoAhorroGravado, $this->bFill, 0, 'C');
        
        $this->SetXY(113,170);
        $this->Cell(36, 5, $oEmpleado->fFondoAhorroExcento, $this->bFill, 0, 'C');
        
        // caja de ahorro
        $this->SetXY(64,178);
        $this->Cell(36, 5, $oEmpleado->fCajaAhorroGravado, $this->bFill, 0, 'C');
        
        $this->SetXY(113,178);
        $this->Cell(36, 5, $oEmpleado->fCajaAhorroExcento, $this->bFill, 0, 'C');
        
         // vales de despensa
        $this->SetXY(64,186);
        $this->Cell(36, 5, $oEmpleado->fValesDespensaGravado, $this->bFill, 0, 'C');        
        
        $this->SetXY(113,186);
        $this->Cell(36, 5, $oEmpleado->fValesDespensaGravado, $this->bFill, 0, 'C');
        
        // gastos funeral
        $this->SetXY(64,194);
        $this->Cell(36, 5, $oEmpleado->fGastosFuneralGravado, $this->bFill, 0, 'C');
        
        $this->SetXY(113,194);
        $this->Cell(36, 5, $oEmpleado->fGastosFuneralGravado, $this->bFill, 0, 'C');
        
        // contribuciones a cargo del trabajador pagadas por el patron
        $this->SetXY(64,202);
        $this->Cell(36, 5, $oEmpleado->fCAcargoTrabajadorPagadasPatronGravado, $this->bFill, 0, 'C');
        
        $this->SetXY(113,202);
        $this->Cell(36, 5, $oEmpleado->fCAcargoTrabajadorPagadasPatronExcento, $this->bFill, 0, 'C');
        
        // premios puntualidad
        $this->SetXY(64,209);
        $this->Cell(36, 5, $oEmpleado->fPremiosPuntualidadGravado, $this->bFill, 0, 'C');
        
        $this->SetXY(113,209);
        $this->Cell(36, 5, $oEmpleado->fPremiosPuntualidadExcento, $this->bFill, 0, 'C');
         
        // prima seguro de vida
        $this->SetXY(64,217);
        $this->Cell(36, 5, $oEmpleado->fPrimaSeguroVidaGravado, $this->bFill, 0, 'C');
        
        $this->SetXY(113,217);
        $this->Cell(36, 5, $oEmpleado->fPrimaSeguroVidaExcento, $this->bFill, 0, 'C');
        
        // seguro gastos medicos mayores
        $this->SetXY(64,225);
        $this->Cell(36, 5, $oEmpleado->fSeguroGastosMedicosMayoresGravado, $this->bFill, 0, 'C');
        
        $this->SetXY(113,225);
        $this->Cell(36, 5, $oEmpleado->fSeguroGastosMedicosMayoresExcento, $this->bFill, 0, 'C');
        
        // vales de restaurante
        $this->SetXY(64,232);
        $this->Cell(36, 5, $oEmpleado->fValesRestauranteGravado, $this->bFill, 0, 'C');
        
        $this->SetXY(113,232);
        $this->Cell(36, 5, $oEmpleado->fValesRestauranteExcento, $this->bFill, 0, 'C');
        
        // vales de gasolina
        $this->SetXY(64,240);
        $this->Cell(36, 5, $oEmpleado->fValesGasolinaGravado, $this->bFill, 0, 'C');
        
        $this->SetXY(113,240);
        $this->Cell(36, 5, $oEmpleado->fValesGasolinaExcento, $this->bFill, 0, 'C');
        
    }
    
    private function _pagina3($cPagina3, & $oEmpleado)
    {
        $this->AddPage('P', 'A4'); 
        $this->Image($cPagina3, -2, 0, 220);
        
        // CONCEPTOS GRAVADOS Y EXCENTOS
        
        // vales para ropa
        $this->SetXY(64,32);
        $this->Cell(36, 5, $oEmpleado->fValesParaRopaGravado, $this->bFill, 0, 'C');
        
        $this->SetXY(113,32);
        $this->Cell(36, 5, $oEmpleado->fValesParaRopaGravado, $this->bFill, 0, 'C');
        
        // ayuda de renta
        $this->SetXY(64,40);
        $this->Cell(36, 5, $oEmpleado->fAyudaRentaGravado, $this->bFill, 0, 'C');
        
        $this->SetXY(113,40);
        $this->Cell(36, 5, $oEmpleado->fAyudaRentaExcento, $this->bFill, 0, 'C');
        
        // ayuda articulos escolares
        $this->SetXY(64,47);
        $this->Cell(36, 5, $oEmpleado->fAyudaArticulosEscolaresGravado, $this->bFill, 0, 'C');
        
        $this->SetXY(113,47);
        $this->Cell(36, 5, $oEmpleado->fAyudaArticulosEscolaresExcento, $this->bFill, 0, 'C');
        
        // dotaccion ayuda o antejos
        $this->SetXY(64,55);
        $this->Cell(36, 5, $oEmpleado->fDotacionAyudaAnteojosGravado, $this->bFill, 0, 'C');
        
        $this->SetXY(113,55);
        $this->Cell(36, 5, $oEmpleado->fDotacionAyudaAnteojosExcento, $this->bFill, 0, 'C');
        
        // ayuda para transporte
        $this->SetXY(64,63);
        $this->Cell(36, 5, $oEmpleado->fAyudaTransporteGravado, $this->bFill, 0, 'C');
        
        $this->SetXY(113,63);
        $this->Cell(36, 5, $oEmpleado->fAyudaTransporteExcento, $this->bFill, 0, 'C');
        
        // cuotas sindicales pagadas por el patron
        $this->SetXY(64,70);
        $this->Cell(36, 5, $oEmpleado->fCuotasSindicalesPagadasPatronGravado, $this->bFill, 0, 'C');
        
        $this->SetXY(113,70);
        $this->Cell(36, 5, $oEmpleado->fCuotasSindicalesPagadasPatronGravado, $this->bFill, 0, 'C');
        
        // subcidio por incapacidad
        $this->SetXY(64,78);
        $this->Cell(36, 5, $oEmpleado->fSubcidioPorIncapacidadGravado, $this->bFill, 0, 'C');
        
        $this->SetXY(113,78);
        $this->Cell(36, 5, $oEmpleado->fSubcidioPorIncapacidadExcento, $this->bFill, 0, 'C');
        
        // becas trabajadores y sus hijos
        $this->SetXY(64,86);
        $this->Cell(36, 5, $oEmpleado->fBecasTrabajadoresSusHijosGravado, $this->bFill, 0, 'C');
        
        $this->SetXY(113,86);
        $this->Cell(36, 5, $oEmpleado->fBecasTrabajadoresSusHijosExcento, $this->bFill, 0, 'C');
        
        // becas trabajadores y sus hijos
        $this->SetXY(64,93);
        $this->Cell(36, 5, $oEmpleado->fPagosEfectuadosPorOtrosEmpleadoresGravado, $this->bFill, 0, 'C');
        
        $this->SetXY(113,93);
        $this->Cell(36, 5, $oEmpleado->fPagosEfectuadosPorOtrosEmpleadoresExcento, $this->bFill, 0, 'C');
        
        // becas trabajadores y sus hijos
        $this->SetXY(64,101);
        $this->Cell(36, 5, $oEmpleado->fOtrosIngresosSalariosGravado, $this->bFill, 0, 'C');
        
        $this->SetXY(113,101);
        $this->Cell(36, 5, $oEmpleado->fOtrosIngresosSalariosExcento, $this->bFill, 0, 'C');
        
        // IMPUESTO SOBRE LA RENTA POR SUELDOS Y SALARIOS
        
        $this->SetXY(64,116);
        $this->Cell(36, 5, round($oEmpleado->fISRQ1, REDONDEAR_DECIMALES), $this->bFill, 0, 'C');
        
        $this->SetXY(64,124);
        $this->Cell(36, 5, round($oEmpleado->fISRR1, REDONDEAR_DECIMALES), $this->bFill, 0, 'C');
        
        $this->SetXY(64,132);
        $this->Cell(36, 5, round($oEmpleado->fISRS1, REDONDEAR_DECIMALES), $this->bFill, 0, 'C');
        
        $this->SetXY(64,140);
        $this->Cell(36, 5, round($oEmpleado->fISRT1, REDONDEAR_DECIMALES), $this->bFill, 0, 'C');
        
        $this->SetXY(64,148);
        $this->Cell(36, 5, round($oEmpleado->fISRU1, REDONDEAR_DECIMALES), $this->bFill, 0, 'C');
        
        $this->SetXY(64,157);
        $this->Cell(36, 5, round($oEmpleado->fISRV1, REDONDEAR_DECIMALES), $this->bFill, 0, 'C');
        
        $this->SetXY(64,165);
        $this->Cell(36, 5, round($oEmpleado->fISRW1, REDONDEAR_DECIMALES), $this->bFill, 0, 'C');
        
        $this->SetXY(154,116);
        $this->Cell(36, 5, round($oEmpleado->fISRX1, REDONDEAR_DECIMALES), $this->bFill, 0, 'C');
        
        $this->SetXY(154,124);
        $this->Cell(36, 5, round($oEmpleado->fISRY1, REDONDEAR_DECIMALES), $this->bFill, 0, 'C');
        
        $this->SetXY(154,132);
        $this->Cell(36, 5, round($oEmpleado->fISRZ1, REDONDEAR_DECIMALES), $this->bFill, 0, 'C');
        
        $this->SetXY(154,140);
        $this->Cell(36, 5, round($oEmpleado->fISRa1, REDONDEAR_DECIMALES), $this->bFill, 0, 'C');
        
        $this->SetXY(154,148);
        $this->Cell(36, 5, round($oEmpleado->fISRb1, REDONDEAR_DECIMALES), $this->bFill, 0, 'C');
        
        $this->SetXY(154,157);
        $this->Cell(36, 5, round($oEmpleado->fISRc1, REDONDEAR_DECIMALES), $this->bFill, 0, 'C');
        
        
        
        // DATOS DEL RETENEDOR
        
        $this->SetXY(44,179);
        $this->Cell(56, 5, $oEmpleado->cRFCEmpresa, $this->bFill);
        
        $this->SetXY(134,179);
        $this->Cell(56, 5, '', $this->bFill);
        
        $this->SetXY(43,187);
        $this->Cell(150, 5, $oEmpleado->cRazonSocialEmpresa, $this->bFill);
        
        $this->SetXY(43,196);
        $this->Cell(150, 5, $oEmpleado->cCurpRepresentanteLegal, $this->bFill);
        
        $this->SetXY(43,204);
        $this->Cell(150, 5, $oEmpleado->cRepresentanteLegal, $this->bFill);
        
    }
    
    
    /**
     * Metodo que sobre escribe la funcionalidad del diseño del footer que esta en la libreria tcpdf
     */
    public function Footer() 
    {   
        
    }
    
    
    private function & _getData()
    {
        $this->CI->load->model("recursoshumanos/CalculoISREmpleado_model");
        $this->CI->load->library("ViewModels/CalculoISREmpleado_ViewModel");
        
        $oCalculoISREmpleado = new CalculoISREmpleado_ViewModel();
        $oCalculoISREmpleado->idEmpresa = $this->oReporte->idEmpresa;
        $oCalculoISREmpleado->iAno = $this->oReporte->iAno;
        $oCalculoISREmpleado->count = $this->oReporte->bConta;
        $oCalculoISREmpleado->bCalcular = SI;
        
        $data = & $this->CI->CalculoISREmpleado_model->getExportarDIM($oCalculoISREmpleado);
        return $data;
    }
}
