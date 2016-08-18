<?php

/**
 * 
 */
class MY_Controller extends CI_Controller {

    /** @var array Arreglo que contiene la información del catalogo a exportar */
    public $aAmbito;

    /** @var string Modulo al cual se esta accediendo por default tiene el valor de dashboard */
    public $cModulo;

    /** @var string Accion al cual se esta accediendo por default tiene el valor de listado */
    public $cAccion;

    public function __construct()
    {
        try {

            if (!isset($_SESSION))
            {
                session_start();
            }

            parent::__construct();

            $this->load->model('Sistema/Modulo_model');
            $this->load->model('Sistema/Accion_model');

            $this->load->library('ViewModels/Modulo_ViewModel');
            $this->load->library('ViewModels/Accion_ViewModel');

            $this->cModulo = $this->uri->segment(1);
            $this->cAccion = $this->uri->segment(2);

            date_default_timezone_set("Mexico/General");

            if (!$this->cModulo)
            {
                $this->cModulo = CONTROLLER_DEFAULT;
            }

            if (!$this->cAccion)
            {
                $this->cAccion = ACCION_DEFAULT;
            }

            $oModulo = new Modulo_ViewModel();
            $dbModulo = $this->Modulo_model->find(array(
               "cNombre" => trim($this->cModulo), "bHabilitado" => SI
            ));
            $oModulo = $this->seguridad->dbToView($oModulo, $dbModulo);

            $this->session->set_userdata('_MODULO', $oModulo);
            $this->aAmbito = array($this->cModulo);
            
            
            $this->_initAvisos();
            
        } catch (Exception $exc) {
            
        }
    }

    public function __destruct()
    {
        try {
            if ($this->db)
            {
                $this->db->close();
            }
        } catch (Exception $ex) {
            echo $ex;
        }
    }

    // <editor-fold defaultstate="collapsed" desc="Metodos utilizadion para datatables">

    /**
     * Metodo que se encarga de la paginacion
     * 
     * @param array $allRegister
     * @param int $iSortCol
     * @param int $iOrderDirection
     * @update 27-01-2015 ser realizo una modificacion para corregir el error de los caracteres especiales en los listaos
     * devido que en el hosting cuenta con una codificación diferene se reemplazo la funcion htmlentities por html_entity_decode
     * @return array
     */
    protected function paginacion(& $allRegister, $iSortCol = 0, $iOrderDirection = '')
    {
        
        $arrResult = array();
        $iTotalRecords = count($allRegister);

        $inicio = $this->input->post('iDisplayStart');
        if (!$inicio)
        {
            $inicio = 0;
        }
        $limite = $this->input->post('iDisplayLength');
        if (!$limite)
        {
            $limite = 20;
        }
        $limite = $limite + $inicio;
        if (!$this->input->post('iSortCol_0'))
        {
            $ordenar = $iSortCol;
            if (!$ordenar)
            {
                $ordenar = 0;
            }
        }
        else
        {
            $ordenar = $this->input->post('iSortCol_0');
        }

        if ($iOrderDirection)
        {
            $dirOrdenar = strtolower($iOrderDirection);
        }
        else
        {
            $dirOrdenar = $this->input->post('sSortDir_0');
            if (!$dirOrdenar)
            {
                $dirOrdenar = 'asc';
            }
        }
        
        $buscar = $this->input->post('sSearch');
        if ($buscar)
        {
            $buscar = strtolower($buscar);
            $buscar = utf8_decode($buscar);
        }
        $echo = $this->input->post('sEcho');
        $columns = $this->input->post('sColumns');

        $arrTotal = array();
        if ($columns)
        {
            $arrColumnas = preg_split('/,/', $columns);

            if ($ordenar !== 'undefined')
            {
                $sortColumn = $arrColumnas[$ordenar];
            }
            else
            {
                $sortColumn = 'undefined';
            }

            //Searchable
            $arrSearchable = array();

            foreach ($arrColumnas as $key => $columna)
            {
                $bSearchable = $this->input->post('bSearchable_' . $key);
                if ($bSearchable == 'true')
                {
                    $arrSearchable[$columna] = true;
                }
                else
                {
                    $arrSearchable[$columna] = false;
                }
            }

            $arrSort = array();
            //filtra 
            foreach ($allRegister as $key => $reg)
            {
                $bFiltro = false;
                $regDisplay = array();
                foreach ($arrColumnas as $columna)
                {
                    $value = $reg[$columna];
                   // $regDisplay[] = htmlentities($value);
                     $regDisplay[] = html_entity_decode($value);
                    if ($buscar)
                    {
                        if ($arrSearchable[$columna] && stripos(utf8_decode($value), $buscar) !== false)
                        {
                            $bFiltro = true;
                        }
                    }
                    else
                    {
                        $bFiltro = true;
                    }
                }
                if ($bFiltro)
                {
                    if ($sortColumn != 'undefined')
                    {
                        $arrSort[$key] = $reg[$sortColumn];
                    }
                    else
                    {
                        $arrSort[$key] = '';
                    }
                    $arrTotal[$key] = $regDisplay;
                }
                unset($allRegister[$key]);
            }
            unset($allRegister);
            
            
            //ordena
            if ($sortColumn != 'undefined')
            {
                if ($dirOrdenar == 'asc')
                {
                    asort($arrSort);
                }
                else
                {
                    arsort($arrSort);
                }
            }
            //pagina
            $l = count($arrSort);
            $i = 0;
            foreach ($arrSort as $key => $value)
            {
                if ($i >= $inicio && ($i < $limite && $i < $l))
                {
                    $arrResult[] = $arrTotal[$key];
                }
                $i++;
            }
        }

        $iTotalDisplayRecords = count($arrTotal);
        

        return array('sEcho' => $echo, 'iTotalRecords' => $iTotalRecords, 'iTotalDisplayRecords' => $iTotalDisplayRecords, 'aaData' => $arrResult);
    }

    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="Metodos utilizados para exportar en los catalogos">

    public function exportar()
   {

      $this->reserveWord = array('CONCAT', 'CASE', 'WHEN', 'THEN', 'ELSE', 'END', 'GETTAG', 'FORMATDATE', 'ADDDATE', 'DATEDIFF');
      $this->listFunciones = array('CONCAT', 'GETTAG', 'FORMATDATE', 'ADDDATE', 'DATEDIFF');
      
      try
      {
         $this->load->library('MetaColumnaVista');
         $this->load->library('MetaFilaVista');

         $arrParams = $this->getParams();
 
         $formatOut = $this->uri->segment(3);
         $filters = $arrParams['filters'];
         $sort = $arrParams['sort'];
         $sortAlign = $arrParams['sortAlign'];
         $gruop = $arrParams['gruop'];
         $typeReport = $arrParams['typeReport'];
         $find = $arrParams['find'];
         $extParam = $arrParams['extParam'];
         $tituloReporte = $arrParams['tituloReporte'];
         
         if (!is_array($gruop))
         {
            if ($gruop)
            {
               $gruop = array($gruop);
            }
            else
            {
               $gruop = array();
            }
         }

         if (!$formatOut)
         {
            $formatOut = 'pdf';
         }
         
         
         $struct = $this->getStruct($typeReport);
         $class = new ReflectionClass($struct['modelName']);
         if ($class->isInstantiable())
         {
            $objResult = $class->newInstance();
         }
         else
         {
            exit();
         }

         if ($struct['functionName'])
         {
            $funcObj = $class->getMethod($struct['functionName']);
            $filters = array($filters, TRUE);
            $arrData = $funcObj->invokeArgs($objResult, $filters);
         }
         else
         {
            $arrData = $objResult->getAll($filters, TRUE);
         }

         $resultData = $this->createDataModel($struct, $arrData, $sort, $sortAlign, $extParam);
         if ($find)
         {
            $resultData = $this->appQuickFilter($resultData, $find);
         }
         if (count($gruop) > 0)
         {
            $gruopRow = $this->onGrouping($resultData, $struct, $gruop);
            $resultData = $this->gruopData($resultData, $struct, $gruop);
         }
//         $resultData = $this->formatData($struct, $resultData, $gruop);

         switch ($formatOut)
         {
            case 'pdf':
               $this->buildPdf($struct, $resultData, $gruop, $tituloReporte);
               break;
            case 'xls':
               $this->buildXls($struct, $resultData, $gruop, $tituloReporte);
               break;
            case 'csv':
               $this->buildCsv($struct, $resultData, $gruop, $tituloReporte);
               break;
            case 'json':
               $this->buildJson($struct, $resultData, $gruop, $tituloReporte);
            case 'word':
               $this->buildWord($struct, $resultData, $gruop, $tituloReporte);    
               break;
         }
      }
      catch (Exception $ex)
      {
//          dump($ex);
//         $this->setException($this->uri->segment(1), $this->uri->segment(2), getUserId(), $ex);
      }
   }
   
   protected function getParams()
   {
      $result = array();

      $result['filters'] = '';
      $result['sort'] = '';
      $result['sortAlign'] = 'ASC';
      $result['gruop'] = '';
      $result['typeReport'] = strtolower($this->cModulo);
      $result['find'] = '';
      $result['extParam'] = array('iNumIntentos' => 4);
      $result['tituloReporte'] = html_entity_decode($this->cModulo);

      return $result;
   }
   
   private function getStruct($typeReport)
   {
      $dom = new DomDocument();
      $dom->prevservWhiteSpace = false;
      $metadata = array();

      if (!@$dom->load("application/config/reportes.xml"))
      {
         echo lang('error.archivo.noencontrado');
         exit();
         return array();
      }

      $i = 0;
      $reportes = $dom->getElementsByTagName('reporte');
      foreach ($reportes as $reporte)
      {
         $idReporte = $reportes->item($i)->getAttribute('id');
         if ($idReporte == $typeReport)
         {
            $modelName = $reportes->item($i)->getAttribute('modelo');
            $functionName = false;
            if ($reportes->item($i)->hasAttribute('funcion'))
            {
               $functionName = $reportes->item($i)->getAttribute('funcion');
            }
            
            $metadata['idReporte'] = $idReporte;
            $metadata['modelName'] = $modelName;
            $metadata['functionName'] = $functionName;
            $metadata['aColumnas'] = array();
            $metadata['aFilas'] = array();

            $k = 0;
            $columnas = $reportes->item($i)->getElementsByTagName('columna');
            foreach ($columnas as $columna)
            {
               $dataColumna = new MetaColumnaVista();
               $dataColumna->Contenido = $columnas->item($k)->getAttribute('contenido');

               if ($columnas->item($k)->hasAttribute('nombre'))
               {
                  $dataColumna->Nombre = $columnas->item($k)->getAttribute('nombre');
               }
               else
               {
                  $dataColumna->Nombre = $dataColumna->Contenido;
               }
               if ($columnas->item($k)->hasAttribute('etiqueta'))
               {
                  $dataColumna->Etiqueta = $columnas->item($k)->getAttribute('etiqueta');
               }
               else
               {
                  $dataColumna->Etiqueta = '';
               }
               if ($columnas->item($k)->hasAttribute('alineacion'))
               {
                  $dataColumna->Alineacion = $columnas->item($k)->getAttribute('alineacion');
               }
               else
               {
                  $dataColumna->Alineacion = 'L';
               }
               if ($columnas->item($k)->hasAttribute('agrupador'))
               {
                  $dataColumna->Agrupador = $columnas->item($k)->getAttribute('agrupador');
               }
               if ($columnas->item($k)->hasAttribute('formato'))
               {
                  $dataColumna->Formato = $columnas->item($k)->getAttribute('formato');
               }
               else
               {
                  $dataColumna->Formato = 'texto';
               }
               if ($columnas->item($k)->hasAttribute('ordenable'))
               {
                  $dataColumna->Ordenable = $columnas->item($k)->getAttribute('ordenable');
               }
               else
               {
                  $dataColumna->Ordenable = 'Si';
               }
               if ($columnas->item($k)->hasAttribute('agrupable'))
               {
                  $dataColumna->Agrupable = $columnas->item($k)->getAttribute('agrupable');
               }
               else
               {
                  $dataColumna->Agrupable = 'Si';
               }
               if ($columnas->item($k)->hasAttribute('ancho'))
               {
                  $dataColumna->Ancho = $columnas->item($k)->getAttribute('ancho');
               }
               else
               {
                  $dataColumna->Ancho = 0;
               }
               if ($columnas->item($k)->hasAttribute('default'))
               {
                  $dataColumna->Default = $columnas->item($k)->getAttribute('default');
               }
               else
               {
                  $dataColumna->Default = 0;
               }
               if ($columnas->item($k)->hasAttribute('pivote'))
               {
                  $dataColumna->Pivote = $columnas->item($k)->getAttribute('pivote');
               }
               else
               {
                  $dataColumna->Pivote = 0;
               }

               $metadata['aColumnas'][] = $dataColumna;
               $k++;
            }

            $k = 0;
            $filas = $reportes->item($i)->getElementsByTagName('filas');
            foreach ($filas as $fila)
            {
               $dataFila = new MetaFilaVista();
               $dataFila->Tipo = $filas->item($k)->getAttribute('tipo');
               if ($filas->item($k)->hasAttribute('color'))
               {
                  $dataFila->Color = $filas->item($k)->getAttribute('color');
               }
               else
               {
                  $dataFila->Color = 'FFFFFF';
               }
               $metadata['aFilas'][] = $dataFila;
               $k++;
            }
            break;
         }
         $i++;
      }
      return $metadata;
   }
   
   private function createDataModel($struct, $arrData, $sort, $sortAlign, $extParam)
   {
      $resultData = array();
      $tableData = array();
      $indexSort = array();

      $esSortNumerico = is_numeric($sort);

      foreach ($arrData as $row)
      {
         $rowData = array();
         $i = 0;
         foreach ($struct['aColumnas'] as $key => $metaColumna)
         {
            $cellData = $this->evalData($metaColumna->Contenido, $row, $extParam);
            if ($cellData === false || $cellData === NULL)
            {
               $cellData = lang($metaColumna->Default);
            }
            $rowData[] = $cellData;
            if ($esSortNumerico)
            {
               if ($sort == $key)
               {
                  $indexSort[] = $cellData;
               }
            }
            else
            {
               if ($sort == $metaColumna->Nombre)
               {
                  $indexSort[] = $cellData;
               }
            }
            $i++;
         }
         $rowData[] = 'N';
         $tableData[] = $rowData;
      }
      //ordena
      if (count($indexSort) > 0)
      {
         if ($sortAlign == 'ASC')
         {
            asort($indexSort);
         }
         else
         {
            arsort($indexSort);
         }
         foreach ($indexSort as $key => $value)
         {
            $resultData[] = $tableData[$key];
         }
      }
      else
      {
         $resultData = $tableData;
      }
      return $resultData;
   }
   
   private function appQuickFilter($resultData, $find)
   {
      $result = array();

      foreach ($resultData as $row)
      {
         $bAgregar = false;
         $i = 0;
         $l = count($row) - 1;
         foreach ($row as $cell)
         {
            if ($i < $l)
            {
               if (!is_object($cell) && stripos($cell, $find) !== false)
               {
                  $bAgregar = true;
                  break;
               }
            }
            $i++;
         }
         if ($bAgregar)
         {
            $result[] = $row;
         }
      }

      return $result;
   }

   private function gruopData($resultData, $struct, $gruop)
   {
      $arrAgrupador = array();
      if (is_array($gruop))
      {
         $agrupador = array_shift($gruop);
         $index = -1;
         foreach ($struct['aColumnas'] as $key => $columna)
         {
            if ($columna->Nombre == $agrupador)
            {
               $index = $key;
               break;
            }
         }

         if ($index > -1)
         {
            foreach ($resultData as $row)
            {
               $key = $row[$index];
               $newRow = array();
               foreach ($row as $keyCell => $value)
               {
                  if ($index != $keyCell)
                  {
                     $newRow[$keyCell] = $value;
                  }
               }
               if (is_float($key)) $key = intval($key);
               if (!array_key_exists($key, $arrAgrupador))
               {
                  $arrAgrupador[$key] = array();
               }
               $arrAgrupador[$key][] = $newRow;
            }
            foreach ($arrAgrupador as $key => $table)
            {
               $gruopRow = $this->onGrouping($table, $struct, $gruop);
               if (count($gruop) > 0)
               {
                  $table = $this->gruopData($table, $struct, $gruop);
               }
               if ($gruopRow)
               {
                  $table[] = $gruopRow;
               }
               $arrAgrupador[$key] = $table;
            }
         }
         else
         {
            $arrAgrupador = $resultData;
         }
      }
      else
      {
         $arrAgrupador = $this->gruopData($resultData, $struct, array($gruop));
      }
      return $arrAgrupador;
   }

   private function onGrouping($table, $struct, $gruop)
   {
      $resultRow = array();
      $resultOpe = array();
      $bAgrupar = false;

      if (count($table) > 0)
      {
         foreach ($table[0] as $key => $cell)
         {
            if (array_key_exists($key, $struct['aColumnas']))
            {
               $metaColumna = $struct['aColumnas'][$key];
               if (!in_array($metaColumna->Nombre, $gruop))
               {
                  if ($metaColumna->Agrupador)
                  {
                     $resultOpe[$key] = $metaColumna->Agrupador;
                     $resultRow[$key] = 0;
                     $bAgrupar = true;
                  }
                  else
                  {
                     $resultOpe[$key] = '';
                     $resultRow[$key] = '';
                  }
               }
            }
            else
            {
               $resultOpe[$key] = 'GR';
               $resultRow[$key] = 'GR';
            }
         }
      }
      if (!$bAgrupar) return null;

      foreach ($table as $row)
      {
         $bPrimero = true;
         foreach ($row as $key => $cell)
         {
            if (array_key_exists($key, $resultOpe) && $resultOpe[$key])
            {
               switch ($resultOpe[$key])
               {
                  case 'COUNT':
                     $resultRow[$key]++;
                     break;
                  case 'AVG':
                  case 'SUM':
                     $resultRow[$key] += $cell;
                     break;
                  case 'MIN':
                     if ($bPrimero || $resultRow[$key] > $cell)
                     {
                        $resultRow[$key] = $cell;
                        $bPrimero = false;
                     }
                     break;
                  case 'MAX':
                     if ($bPrimero || $resultRow[$key] < $cell)
                     {
                        $resultRow[$key] = $cell;
                        $bPrimero = false;
                     }
                     break;
                  default:
                     $resultRow[$key] = lang($resultOpe[$key]);
               }
            }
         }
      }

      $l = count($table);
      foreach ($resultOpe as $key => $ope)
      {
         if ($resultOpe[$key] == 'AVG')
         {
            $resultRow[$key] = $resultRow[$key] / $l;
         }
      }

      return $resultRow;
   }

   private function formatData($struct, $resultData, $gruop)
   {
      reset($resultData);
      $pila = array($resultData);
      $pilaKey = array();
      while (($l = count($pila)) > 0)
      {
         $tope = & $pila[$l - 1];
         if (is_array($tope))
         {
            $hijo = current($tope);
            if ($hijo !== false)
            {
               if (is_array($hijo))
               {
                  reset($hijo);
               }
               if (is_array($hijo) && is_array(current($hijo)))
               {
                  
               }
               array_push($pila, $hijo);
               array_push($pilaKey, key($tope));
               next($tope);
            }
            else
            {
               $elemento = array_pop($pila);
               if (count($pilaKey) > 0)
               {
                  $keyElemento = array_pop($pilaKey);
                  $l = count($pila);
                  unset($tope);
                  $tope = & $pila[$l - 1];
                  $tope[$keyElemento] = $elemento;
                  unset($tope);
               }
               else
               {
                  $resultData = $elemento;
               }
            }
         }
         else
         {
            $elemento = array_pop($pila);
            $arr = array_pop($pila);
            $keyElemento = array_pop($pilaKey);
            $keyArr = array_pop($pilaKey);
            if (is_array($arr))
            {
               $i = 0;
               $l = count($arr) - 1;
               foreach ($arr as $key => $elemento)
               {
                  if ($i < $l)
                  {
                     $metaColumna = $struct["aColumnas"][$key];
                     switch ($metaColumna->Formato)
                     {
                        case 'moneda':
                           $arr[$key] = Generic::formatNumber($elemento);
                           break;
                        case 'porcentaje':
                           if ($elemento)
                           {
                              $arr[$key] = $elemento . '%';
                           }
                           break;
                        case 'fecha':
                           if ($elemento && $arr[$l + count($gruop)] != 'GR')
                           {
                              $arr[$key] = Generic::ConvertirFormatoFecha($elemento, '-', 'f');
                           }
                           break;
                     }
                  }
                  $i++;
               }
               $l = count($pila);
               unset($tope);
               $tope = & $pila[$l - 1];
               $tope[$keyArr] = $arr;
               unset($tope);
            }
         }
      }
      return $resultData;
   }
   
   private function evalData($contenido, $row, $extParam)
   {
      $regExp = "/([,\\(\\)\\+\\-\\*\\/])|([\\!\\=]\\=)|([\\<\\>][\\=]?)|(\'[^\']+\')|([@]?[\w]+)|([\\-]?\d+(.\d+)?)/";
      preg_match_all($regExp, $contenido, $components, PREG_SET_ORDER);
      $pilaSimbolos = array();
      $pilaTipos = array();
      $pilaOperador = array();

      foreach ($components as $component)
      {
         $comp = trim($component[0]);
         $tipo = '';

         //define el tipo
         if (strpos($comp, "@") === 0)
         {
            $comp = $comp;
            $tipo = 'variable';
         }
         else if (preg_match('/^[\\-]?\d+(.\d+)?$/', $comp))
         {
            $tipo = 'numero';
         }
         else if (preg_match('/^([,\\(\\)\\+\\-\\*\\/])|([\\!\\=]\\=)|([\\<\\>][\\=]?)$/', $comp))
         {
            $tipo = 'operador';
         }
         else if (preg_match('/^[\w]+$/', $comp))
         {
            if (in_array($comp, $this->reserveWord))
            {
               $tipo = 'palabra';
            }
            else
            {
               $tipo = 'extVar';
            }
         }
         else
         {
            $tipo = 'constante';
         }

         if ($tipo == 'operador')
         {
            if ($comp == ')')
            {

               $params = array();
               $tipo1 = array_pop($pilaTipos);
               $simbolo1 = array_pop($pilaSimbolos);
               $operador = array_pop($pilaOperador);
               while ($operador !== '(')
               {
                  switch ($operador)
                  {
                     case ',':
                        array_unshift($params, $simbolo1);
                        $tipo1 = array_pop($pilaTipos);
                        $simbolo1 = array_pop($pilaSimbolos);
                        $operador = array_pop($pilaOperador);
                        break;
                     default:
                        $tipo2 = array_pop($pilaTipos);
                        $simbolo2 = array_pop($pilaSimbolos);
                        $simbolo1 = $this->evalOperador($simbolo2, $operador, $simbolo1);
                        $operador = array_pop($pilaOperador);
                        break;
                  }
               }
               $tip = $this->getTop($pilaTipos);
               $simb = $this->getTop($pilaSimbolos);
               if ($tip == 'palabra')
               {//Es palabra reservada
                  if (in_array($simb, $this->listFunciones))
                  {
                     array_unshift($params, $simbolo1);
                     array_pop($pilaTipos);
                     $funcion = array_pop($pilaSimbolos);
                     array_push($pilaSimbolos, $this->evalFuncion($funcion, $params));
                     array_push($pilaTipos, 'constante');
                  }
               }
               else
               {
                  array_push($pilaSimbolos, $simbolo1);
                  array_push($pilaTipos, 'constante');
               }
            }
            else
            {
               if (in_array($comp, array('+', '-', '*', '/', '<', '>', '<=', '>=', '==', '!='), true))
               {
                  $ope = $this->getTop($pilaTipos);
                  while ($this->jerarquia($ope) >= $this->jerarquia($comp))
                  {
                     $tipo1 = array_pop($pilaTipos);
                     $simbolo1 = array_pop($pilaSimbolos);
                     $operador = array_pop($pilaOperador);
                     $tipo2 = array_pop($pilaTipos);
                     $simbolo2 = array_pop($pilaSimbolos);
                     $simbolo1 = $this->evalOperador($simbolo2, $operador, $simbolo1);
                     array_push($pilaSimbolos, $simbolo1);
                     array_push($pilaTipos, $tipo1);
                  }
               }
               array_push($pilaOperador, $comp);
            }
         }
         else if ($tipo == 'palabra')
         {
            if (in_array($comp, array('WHEN', 'THEN', 'ELSE', 'END'), true))
            {
               $simb = $this->getTop($pilaSimbolos);
               while (!in_array($simb, array('CASE', 'WHEN', 'THEN', 'ELSE'), true))
               {
                  $tipo1 = array_pop($pilaTipos);
                  $simbolo1 = array_pop($pilaSimbolos);
                  $simb = $this->getTop($pilaSimbolos);
                  if (!in_array($simb, array('CASE', 'WHEN', 'THEN', 'ELSE'), true))
                  {
                     $operador = array_pop($pilaOperador);
                     $tipo2 = array_pop($pilaTipos);
                     $simbolo2 = array_pop($pilaSimbolos);
                     $simbolo1 = $this->evalOperador($simbolo2, $operador, $simbolo1);
                     $simb = $this->getTop($pilaSimbolos);
                  }
                  array_push($pilaSimbolos, $simbolo1);
                  array_push($pilaTipos, 'constante');
               }
               array_push($pilaSimbolos, $comp);
               array_push($pilaTipos, 'palabra');
               if ($comp === 'END')
               {
                  $params = array();
                  do
                  {
                     $tipo1 = array_pop($pilaTipos);
                     $simbolo1 = array_pop($pilaSimbolos);
                     array_unshift($params, $simbolo1);
                  }
                  while ($simbolo1 !== 'CASE');
                  $simbolo1 = $this->evalCase($params);
                  array_push($pilaSimbolos, $simbolo1);
                  array_push($pilaTipos, 'constante');
               }
            }
            else
            {
               array_push($pilaSimbolos, $comp);
               array_push($pilaTipos, $tipo);
            }
         }
         else
         {
            if ($tipo == 'constante')
            {
               $comp = substr($comp, 1, strlen($comp) - 2);
               array_push($pilaSimbolos, $comp);
            }
            else if ($tipo == 'variable')
            {
               $comp = substr($comp, 1);
               if (array_key_exists($comp, $row))
               {
                  array_push($pilaSimbolos, $row[$comp]);
               }
               else
               {
                  array_push($pilaSimbolos, '');
               }
            }
            else if ($tipo == 'extVar')
            {
               if (array_key_exists($comp, $extParam))
               {
                  array_push($pilaSimbolos, $extParam[$comp]);
               }
               else
               {
                  array_push($pilaSimbolos, '');
               }
               $tipo = 'constante';
            }
            else
            {
               array_push($pilaSimbolos, $comp);
            }
            array_push($pilaTipos, $tipo);
         }
      }

      while (count($pilaOperador) > 0)
      {
         $tipo1 = array_pop($pilaTipos);
         $simbolo1 = array_pop($pilaSimbolos);
         $operador = array_pop($pilaOperador);
         $tipo2 = array_pop($pilaTipos);
         $simbolo2 = array_pop($pilaSimbolos);
         $simbolo1 = $this->evalOperador($simbolo2, $operador, $simbolo1);
         array_push($pilaSimbolos, $simbolo1);
         array_push($pilaTipos, $tipo1);
      }
      $tipo1 = array_pop($pilaTipos);
      $simbolo1 = array_pop($pilaSimbolos);

      return $simbolo1;
   }

   private function getTop($pila)
   {
      $result = null;
      $l = count($pila);
      if ($l > 0)
      {
         $result = $pila[$l - 1];
      }

      return $result;
   }

   private function jerarquia($operador)
   {
      $result = 0;
      switch ($operador)
      {
         case '(':
            $result = 0;
            break;
         case '<':
         case '>':
         case '<=':
         case '>=':
         case '==':
         case '!=':
            $result = 1;
            break;
         case '+':
         case '-':
            $result = 2;
            break;
         case '*':
         case '/':
            $result = 3;
            break;
      }

      return $result;
   }

   private function evalOperador($simbolo2, $operador, $simbolo1)
   {
      $result = '';
      switch ($operador)
      {
         case '<':
            $result = $simbolo2 < $simbolo1;
            break;
         case '>':
            $result = $simbolo2 > $simbolo1;
            break;
         case '<=':
            $result = $simbolo2 <= $simbolo1;
            break;
         case '>=':
            $result = $simbolo2 >= $simbolo1;
            break;
         case '==':
            $result = $simbolo2 == $simbolo1;
            break;
         case '!=':
            $result = $simbolo2 != $simbolo1;
            break;
         case '*':
            $result = $simbolo2 * $simbolo1;
            break;
         case '/':
            $result = $simbolo2 / $simbolo1;
            break;
         case '-':
            $result = $simbolo2 - $simbolo1;
            break;
         default:
            $result = $simbolo2 + $simbolo1;
      }
      return $result;
   }

   private function evalFuncion($funcion, $params)
   {
      $result = null;
      switch ($funcion)
      {
         case 'CONCAT':
            $result = '';
            foreach ($params as $param)
            {
               $result .= $param;
            }
            break;
         case 'GETTAG':
            if (count($params) > 0)
            {
               $result = lang($params[0]);
            }
            else
            {
               $result = '';
            }
            break;
         case 'FORMATDATE':
            if (count($params) > 0)
            {
               if ($params[0])
               {
                  $result = Generic::ConvertirFormatoFecha($params[0], '-', 'f');
               }
            }
            break;
         case 'ADDDATE':
            if (count($params) >= 2)
            {
               $fecha = $params[0];
               $cant = $params[1];
               $uni = $params[2];
               $fecha = strtotime($fecha);
               $fecha = strtotime($cant . ' ' . $uni, $fecha);
               $result = date('Y-m-d', $fecha);
            }
            break;
         case 'DATEDIFF':
            if (count($params) >= 2)
            {
               $fecha1 = $params[0];
               $fecha2 = $params[1];
               $fecha1 = strtotime($fecha1);
               $fecha2 = strtotime($fecha2);
               $diff = $fecha1 - $fecha2;
               $diff = floor($diff / (3600 * 24));
               $result = date('Y-m-d', $diff);
            }
            break;
      }

      return $result;
   }

   private function evalCase($params)
   {
      $bCondicion = false;
      $bEncontrado = false;
      $bFinalizado = false;
      $result = false;

      foreach ($params as $key => $param)
      {
         if ($key == 1 && $param !== 'WHEN')
         {
            $bCondicion = $param;
            continue;
         }

         if ($bCondicion || $key > 0)
         {
            if ($bCondicion !== false)
            {
               $key = ($key - 2) % 4;
            }
            else
            {
               $key = ($key - 1) % 4;
            }
            switch ($key)
            {
               case 0:
                  $lastSymbolOpe = $param;
                  break;
               case 1:
                  if ($lastSymbolOpe == 'WHEN')
                  {
                     if ($bCondicion !== false)
                     {
                        if ($bCondicion == $param)
                        {
                           $bEncontrado = true;
                        }
                     }
                     else if ($param)
                     {
                        $bEncontrado = true;
                     }
                  }
                  else
                  {
                     $bEncontrado = true;
                     $result = $param;
                     $bFinalizado = true;
                  }
                  break;
               case 3:
                  if ($bEncontrado)
                  {
                     $result = $param;
                     $bFinalizado = true;
                  }
                  break;
            }
         }
         if ($bFinalizado)
         {
            break;
         }
      }

      return $result;
   }
   
   private function buildPdf($struct, $resultData, $gruop, $tituloReporte)
   {
      require_once("application/libraries/PDF_Exportar.php");
      
      $oApariencia = new stdClass();
      $oApariencia->cTablaFila1 = EXPORTAR_COLOR_FILA_UNO;
      $oApariencia->cTablaFila2 = EXPORTAR_COLOR_FILA_DOS;
      $datosEncabezado = array('logo' => FALSE, 'nombre' => ConfigHelper::get("cNombreEmpresa"), 'titulo' => $tituloReporte);

      $pdf = new PDF_Exportar($struct, $gruop, $oApariencia, $datosEncabezado);
      $pdf->AliasNbPages();
      $pdf->build($resultData);
      $pdf->Output();
   }
   
   private function buildXls($struct, $resultData, $gruop, $tituloReporte)
    {
        $this->load->library("Excel");
        
        $objPHPExcel = new Excel();
        $objPHPExcel->getProperties()->setTitle($tituloReporte);

        $oActiveIndex = $objPHPExcel->setActiveSheetIndex(0);
        $aAbc = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "X", "Y", "Z", "AA", "AB", "AC", "AD", "AE", "AF", "AG", "AH", "AI", "AJ", "AK", "AL", "AM", "AN", "AO", "AP", "AQ", "AR", "AS", "AT", "AU", "AV", "AX", "AY", "AZ");

        $iTotalColumns = count($struct["aColumnas"]);
        for ($i = 0; $i < $iTotalColumns; $i++)
        {
            $oActiveIndex->setCellValue($aAbc[$i] . "1", lang($struct["aColumnas"][$i]->Etiqueta));
        }
        
        $iTotalRows = count($resultData);
        $iSumaColumnaEspacio = 2;
        if ($iTotalRows > 0)
        {
            for ($i = 0; $i < $iTotalRows; $i++)
            {
                for ($j = 0; $j < $iTotalColumns; $j++)
                {
                    $oActiveIndex->setCellValueByColumnAndRow($j, $i + $iSumaColumnaEspacio, html_entity_decode($resultData[$i][$j]));
                }
            }
        }
        else
        {
            $oActiveIndex->setCellValueByColumnAndRow(0, $iSumaColumnaEspacio, lang("datatable_registrosnoencontrados"));
            $oActiveIndex->mergeCellsByColumnAndRow(0, $iSumaColumnaEspacio, ($iTotalColumns - 1), $iSumaColumnaEspacio);
        }
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $tituloReporte . '".xls');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }
    
    private function buildCsv($struct, $resultData, $gruop, $tituloReporte)
    {
        $this->load->library("Excel");
        
        $objPHPExcel = new Excel();
        $objPHPExcel->getProperties()->setTitle($tituloReporte);

        $oActiveIndex = $objPHPExcel->setActiveSheetIndex(0);
        $aAbc = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "X", "Y", "Z", "AA", "AB", "AC", "AD", "AE", "AF", "AG", "AH", "AI", "AJ", "AK", "AL", "AM", "AN", "AO", "AP", "AQ", "AR", "AS", "AT", "AU", "AV", "AX", "AY", "AZ");

        $iTotalColumns = count($struct["aColumnas"]);
        for ($i = 0; $i < $iTotalColumns; $i++)
        {
            $oActiveIndex->setCellValue($aAbc[$i] . "1", lang($struct["aColumnas"][$i]->Etiqueta));
        }
        
        $iTotalRows = count($resultData);
        $iSumaColumnaEspacio = 2;
        if ($iTotalRows > 0)
        {
            for ($i = 0; $i < $iTotalRows; $i++)
            {
                for ($j = 0; $j < $iTotalColumns; $j++)
                {
                    $oActiveIndex->setCellValueByColumnAndRow($j, $i + $iSumaColumnaEspacio, html_entity_decode($resultData[$i][$j]));
                }
            }
        }
        else
        {
            $oActiveIndex->setCellValueByColumnAndRow(0, $iSumaColumnaEspacio, lang("datatable_registrosnoencontrados"));
            $oActiveIndex->mergeCellsByColumnAndRow(0, $iSumaColumnaEspacio, ($iTotalColumns - 1), $iSumaColumnaEspacio);
        }
        
        header('Content-Type:application/csv;charset=UTF-8');
        header('Content-Disposition: attachment;filename="' . $tituloReporte . '".csv');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
        $objWriter->setUseBOM(true);
        $objWriter->save('php://output');
    }
    
    /**
     * Metodo que se encarga de generar el archivo de word a exportar con los datos del listado
     * 
     * @access private
     * @param array $aConfig arreglo de configuracion del listado
     * @param array $aData arreglo de datos a exportar
     * return void No retorna valor
     */
    private function buildWord($struct, $resultData, $gruop, $tituloReporte)
    {
        header('Content-Type: application/vnd.ms-word');
        header('Content-Disposition: attachment;filename="' . $tituloReporte . '".doc');
        header('Cache-Control: max-age=0');

        $cHtml = $this->load->view("exportar/word_view", array("config" => $struct, "data" => $resultData, "tituloReporte" => $tituloReporte), TRUE);
        echo $cHtml;
    }

// </editor-fold>
    
    
    private function _initAvisos()
    {
        $iAnteriorNumeroAvisos=0;
        if (isset($_SESSION['_NUMERO_AVISOS_USUARIO']))
        {
            $iAnteriorNumeroAvisos = $_SESSION['_NUMERO_AVISOS_USUARIO'];
            unset($_SESSION['_NUMERO_AVISOS_USUARIO']);
        }
        $iNumeroAvisos = UsuarioHelper::getNumeroAvisos();
        $_SESSION['_NUMERO_AVISOS_USUARIO'] = $iNumeroAvisos;
    }

}
