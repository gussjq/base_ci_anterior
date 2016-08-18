<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Extended Model Class
 *
 * Provides a number of useful functions to generate model specific queries.
 * Takes inspiration from CakePHP's implementation of Model and keeps the function
 * names pretty same.
 *
 * A list of functions would be:
 *
 * - loadTable
 * - find
 * - findAll
 * - findCount
 * - field
 * - generateArrayList
 * - generateList
 * - generateSingleArray
 * - getAffectedRows
 * - getID
 * - getInsertID
 * - getNumRows
 * - insert
 * - read
 * - save
 * - remove
 * - query
 * - lastQuery
 * 
 * Agregados
 * 
 * -saveBatch
 * -whereIn
 * -whereNotIn
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		Md Emran Hasan (phpfour@gmail.com)
 * @link		http://phpfour.com
 */
Class MY_Model extends CI_Model {

    /**
     * Value of the primary key ID of the record that this model is currently pointing to
     *
     * @var unknown_type
     * @access public
     */
    Public $id = null;

    /**
     * Container for the data that this model gets from persistent storage (the database).
     *
     * @var array
     * @access public
     */
    Public $data = array();

    /**
     * The name of the associate table name of the Model object
     * @var string
     * @access public
     */
    Public $_table;

    /**
     * The name of the ID field for this Model.
     *
     * @var string
     * @access public
     */
    Public $primaryKey;

    /**
     * Container for the fields of the table that this model gets from persistent storage (the database).
     *
     * @var array
     * @access public
     */
    Private $fields = array();

    /**
     * The last inserted ID of the data that this model created
     *
     * @var int
     * @access private
     */
    Private $__insertID = null;

    /**
     * The number of records returned by the last query
     *
     * @access private
     * @var int
     */
    Private $__numRows = null;

    /**
     * The number of records affected by the last query
     *
     * @access private
     * @var int
     */
    Private $__affectedRows = null;

    /**
     * Tells the model whether to return results in array or not
     *
     * @var string
     * @access public
     */
    Public $returnArray = FALSE;

    /**
     * Prints helpful debug messages if asked
     *
     * @var string
     * @access public
     */
    Public $debug = TRUE;

    /**
     * Fields que no son llaves primarias
     * @var array
     * @acces public
     */
    Public $non_primary = array();

    /**
     * fields no nullos
     * @var unknown_type
     */
    Public $not_null = array();

    /**
     * Tipos de datos
     * @var array
     * @acces public
     */
    public $data_type = array();

    /**
     * Constructor
     *
     * @access public
     */
    Public function __construct()
    {
        parent::__construct();
        log_message('debug', "Extended Model Class Initialized");
    }

    /**
     * Load the associated database table.
     *
     * @author md emran hasan <emran@rightbrainsolution.com>
     * @access public
     */
    Public function loadTable($table, $fields = array())
    {
        if ($this->debug)
            log_message('debug', "Loading model table: $table");

        $this->_table = $table;
        $this->fields = (!empty($fields)) ? $fields : $this->db->list_fields($table);
        $this->_dump_info($table);

        if ($this->debug)
        {
            log_message('debug', "Successfully Loaded model table: $table");
        }
    }

    /**
     * Returns a resultset array with specified fields from database matching given conditions.
     *
     * @author md emran hasan <emran@rightbrainsolution.com>
     * @return query result either in array or in object based on model config
     * @access public
     */
    Public function findAll($conditions = NULL, $fields = '*', $order = NULL, $start = 0, $limit = NULL, $is_array = FALSE)
    {
        if ($conditions != NULL)
        {
            if (is_array($conditions))
            {
                $this->db->where($conditions);
            }
            else
            {
                $this->db->where($conditions, NULL, FALSE);
            }
        }

        if ($fields != NULL)
        {
            $this->db->select($fields, false);
        }

        if ($order != NULL)
        {
            $this->db->order_by($order);
        }

        if ($limit != NULL)
        {
            $this->db->limit($limit, $start);
        }

        $query = $this->db->get($this->_table);
        if (is_object($query))
        {
            $this->__numRows = $query->num_rows();
            return ($this->returnArray || $is_array) ? $query->result_array() : $query->result();
        }
        else
        {
            return array();
        }
    }

    public function whereIn($campo, $wherein = NULL, $conditions = NULL, $fields = '*', $order = NULL, $start = 0, $limit = NULL, $is_array = FALSE)
    {
        if ($wherein != NULL)
        {
            if (is_string($wherein))
            {
                $wherein = explode(",", $wherein);
            }
            $this->db->where_in($campo, $wherein);
        }

        if ($conditions != NULL)
        {
            if (is_array($conditions))
            {
                $this->db->where($conditions);
            }
            else
            {
                $this->db->where($conditions, NULL, FALSE);
            }
        }

        if ($fields != NULL)
        {
            $this->db->select($fields, false);
        }

        if ($order != NULL)
        {
            $this->db->order_by($order);
        }

        if ($limit != NULL)
        {
            $this->db->limit($limit, $start);
        }

        $query = $this->db->get($this->_table);
        if (is_object($query))
        {
            $this->__numRows = $query->num_rows();
            return ($this->returnArray || $is_array) ? $query->result_array() : $query->result();
        }
        else
        {
            return array();
        }
    }

    public function whereNotIn($campo, $conditions = NULL, $fields = '*', $order = NULL, $start = 0, $limit = NULL, $is_array = FALSE)
    {
        if ($conditions != NULL)
        {
            $this->db->where_not_in($campo, $conditions);
        }

        if ($fields != NULL)
        {
            $this->db->select($fields, false);
        }

        if ($order != NULL)
        {
            $this->db->order_by($order);
        }

        if ($limit != NULL)
        {
            $this->db->limit($limit, $start);
        }

        $query = $this->db->get($this->_table);
        if (is_object($query))
        {
            $this->__numRows = $query->num_rows();
            return ($this->returnArray || $is_array) ? $query->result_array() : $query->result();
        }
        else
        {
            return array();
        }
    }

    public function whereOr($whereOr = NULL, $conditions = NULL, $fields = '*', $order = NULL, $start = 0, $limit = NULL, $is_array = FALSE)
    {
        if ($wherein != NULL)
        {
            $i=0;
            foreach ($whereOr AS $campo)
            {
                if($i==0){
                    //$this->db->where();
                }
                $i++;
            }
        }

        if ($conditions != NULL)
        {
            if (is_array($conditions))
            {
                $this->db->where($conditions);
            }
            else
            {
                $this->db->where($conditions, NULL, FALSE);
            }
        }

        if ($fields != NULL)
        {
            $this->db->select($fields, false);
        }

        if ($order != NULL)
        {
            $this->db->order_by($order);
        }

        if ($limit != NULL)
        {
            $this->db->limit($limit, $start);
        }

        $query = $this->db->get($this->_table);
        
        if (is_object($query))
        {
            $this->__numRows = $query->num_rows();
            return ($this->returnArray || $is_array) ? $query->result_array() : $query->result();
        }
        else
        {
            return array();
        }
    }

    /**
     * Return a single row as a resultset array with specified fields from database matching given conditions.
     *
     * @author md emran hasan <emran@rightbrainsolution.com>
     * @return single row either in array or in object based on model config
     * @access public
     */
    Public function find($conditions = NULL, $fields = '*', $order = NULL)
    {
        $data = $this->findAll($conditions, $fields, $order, 0, 1);
        if ($data)
        {
            return $data[0];
        }
        else
        {
            return false;
        }
    }

    /**
     * Returns contents of a field in a query matching given conditions.
     *
     * @author md emran hasan <emran@rightbrainsolution.com>
     * @return string the value of the field specified of the first row
     * @access public
     */
    Public function field($conditions = null, $name, $fields = '*', $order = NULL)
    {
        $data = $this->findAll($conditions, $fields, $order, 0, 1);

        if ($data)
        {
            $row = $data[0];

            if (isset($row[$name]))
            {
                return $row[$name];
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    /**
     * Returns number of rows matching given SQL condition.
     *
     * @author md emran hasan <emran@rightbrainsolution.com>
     * @return integer the number of records returned by the condition
     * @access public
     */
    Public function findCount($conditions = null)
    {
        $data = $this->findAll($conditions, 'COUNT(*) AS count', null, 0, 1, TRUE);

        if ($data)
        {
            return $data[0]['count'];
        }
        else
        {
            return false;
        }
    }

    /**
     * Returns a key value pair array from database matching given conditions.
     *
     * Example use: generateList(null, '', 0. 10, 'id', 'username');
     * Returns: array('10' => 'emran', '11' => 'hasan')
     *
     * @author md emran hasan <emran@rightbrainsolution.com>
     * @return array a list of key val ue pairs given criteria
     * @access public
     */
    Public function generateList($conditions = null, $order = 'id ASC', $start = 0, $limit = NULL, $key = null, $value = null)
    {
        $data = $this->findAll($conditions, "$key, $value", $order, $start, $limit);

        if ($data)
        {
            foreach ($data as $row)
            {
                $keys[] = ($this->returnArray) ? $row[$key] : $row->$key;
                $vals[] = ($this->returnArray) ? $row[$value] : $row->$value;
            }

            if (!empty($keys) && !empty($vals))
            {
                $return = array_combine($keys, $vals);
                return $return;
            }
        }
        else
        {
            return false;
        }
    }

    Public function generateArrayList($conditions = null, $order = 'id ASC', $start = 0, $limit = NULL, $value = null)
    {
        $data = $this->findAll($conditions, "$value", $order, $start, $limit);
        if ($data)
        {
            foreach ($data as $row)
            {
                $vals[] = ($this->returnArray) ? $row[$value] : $row->$value;
            }

            if (!empty($vals))
            {
                $return = $vals;
                return $return;
            }
        }
        else
        {
            return false;
        }
    }

    /**
     * Returns an array of the values of a specific column from database matching given conditions.
     *
     * Example use: generateSingleArray(null, 'name');
     *
     * @author md emran hasan <emran@rightbrainsolution.com>
     * @return array a list of key value pairs given criteria
     * @access public
     */
    Public function generateSingleArray($conditions = null, $field = null, $order = 'id ASC', $start = 0, $limit = NULL)
    {
        $order = $this->primaryKey . ' ASC';
        $data = $this->findAll($conditions, $this->primaryKey . "," . $field, $order, $start, $limit, TRUE);

        if ($data)
        {
            foreach ($data as $row)
            {
                $arr[$row[$this->primaryKey]] = $row[$field];
            }

            return $arr;
        }
        else
        {
            return array();
        }
    }

    /**
     * Initializes the model for writing a new record.
     *
     * @author md emran hasan <emran@rightbrainsolution.com>
     * @return boolean True
     * @access public
     */
    Public function create()
    {
        $this->id = false;
        unset($this->data);

        $this->data = array();
        return true;
    }

    /**
     * Returns a list of fields from the database and saves in the model
     *
     * @author md emran hasan <emran@rightbrainsolution.com>
     * @return array Array of database fields
     * @access public
     */
    Public function read($id = null, $fields = null)
    {

        if ($id != null)
        {
            $this->id = $id;
        }

        $id = $this->id;

        if ($this->id !== null && $this->id !== false)
        {

            $this->data = $this->find($this->primaryKey . ' = ' . $id, $fields);
            return $this->data;
        }
        else
        {
            return false;
        }
    }

    /**
     * Inserts a new record in the database.
     *
     * @author md emran hasan <emran@rightbrainsolution.com>
     * @return boolean success
     * @access public
     */
    Public function insert($data = null)
    {
        if ($data == null)
        {
            return FALSE;
        }

        $this->data = $data;
        $this->data['create_date'] = date("Y-m-d H:i:s");

        foreach ($this->data as $key => $value)
        {
            if (array_search($key, $this->fields) === FALSE)
            {
                unset($this->data[$key]);
            }
        }

        $this->db->insert($this->_table, $this->data);

        $this->__insertID = $this->db->insert_id();
        return $this->__insertID;
    }

    /**
     * Saves model data to the database.
     *
     * @author md emran hasan <emran@rightbrainsolution.com>
     * @return boolean success
     * @access public
     */
    Public function save($data = null, $id = null, $haveId = false)
    {


        if ($data)
        {

            if (is_object($data))
            {
                $data = $this->db->_object_to_array($data);
            }
            $this->data = $data;
            foreach ($this->data as $key => $value)
            {
                if (array_search($key, $this->fields) === FALSE)
                {
                    unset($this->data[$key]);
                }
                else if ($key == $this->primaryKey)
                {

                    if (!$haveId)
                    {
                        unset($this->data[$key]);
                    }
                    else
                    {
                        $this->id = NULL;
                    }
                }
            }

            if ($id != NULL)
            {
                $this->id = $id;
            }
            else
            {
                $this->id = NULL;
            }
            if ($this->id !== null && $this->id !== false)
            {
                $this->db->where($this->primaryKey, $this->id);
                $this->db->update($this->_table, $this->data);

                $this->__affectedRows = $this->db->affected_rows();
                return $this->id;
            }
            else
            {
                $this->db->insert($this->_table, $this->data);
                $this->__insertID = $this->db->insert_id();
                return $this->__insertID;
            }
        }
    }

    /**
     * 
     * @param type $data
     * @param type $bNuevo
     */
    public function saveBatch($data = array(), $bNuevo = true)
    {
        $aData = array();
        if (is_array($data) && count($data) > 0)
        {
            foreach ($data as $item)
            {
                $aItem = $this->db->_object_to_array($item);

                foreach ($aItem as $key => $value)
                {
                    if (array_search($key, $this->fields) === FALSE)
                    {
                        unset($aItem[$key]);
                    }
                }

                $aData[] = $aItem;
            }

            if ($bNuevo)
            {
                $this->db->insert_batch($this->_table, $aData);
            }
            else
            {
                $this->db->update_batch($this->_table, $aData);
            }
        }
    }

    /**
     * Removes record for given id. If no id is given, the current id is used. Returns true on success.
     *
     * @author md emran hasan <emran@rightbrainsolution.com>
     * @return boolean True on success
     * @access public
     */
    Public function remove($id = null)
    {
        if ($id != null)
        {
            $this->id = $id;
        }

        $id = $this->id;

        if ($this->id !== null && $this->id !== false)
        {
            if ($this->db->delete($this->_table, array($this->primaryKey => $id)))
            {
                $this->id = null;
                $this->data = array();

                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    /**
     * Returns a resultset for given SQL statement. Generic SQL queries should be made with this method.
     *
     * @author md emran hasan <emran@rightbrainsolution.com>
     * @return array Resultset
     * @access public
     */
    Public function query($sql)
    {
        return $this->db->query($sql);
    }

    /**
     * Returns the last query that was run (the query string, not the result).
     *
     * @author md emran hasan <emran@rightbrainsolution.com>
     * @return string SQL statement
     * @access public
     */
    Public function lastQuery()
    {
        return $this->db->last_query();
    }

    /**
     * This function simplifies the process of writing database inserts. It returns a correctly formatted SQL insert string.
     *
     * @author md emran hasan <emran@rightbrainsolution.com>
     * @return string SQL statement
     * @access public
     */
    Public function insertString($data)
    {
        return $this->db->insert_string($this->_table, $data);
    }

    /**
     * Returns the current record's ID.
     *
     * @author md emran hasan <emran@rightbrainsolution.com>
     * @return integer The ID of the current record
     * @access public
     */
    Public function getID()
    {
        return $this->id;
    }

    /**
     * Returns the ID of the last record this Model inserted.
     *
     * @author md emran hasan <emran@rightbrainsolution.com>
     * @return int
     * @access public
     */
    public function getInsertID()
    {
        return $this->__insertID;
    }

    /**
     * Returns the number of rows returned from the last query.
     *
     * @author md emran hasan <emran@rightbrainsolution.com>
     * @return int
     * @access public
     */
    public function getNumRows()
    {
        return $this->__numRows;
    }

    /**
     * Returns the number of rows affected by the last query
     *
     * @author md emran hasan <emran@rightbrainsolution.com>
     * @return int
     * @access public
     */
    Public function getAffectedRows()
    {
        return $this->__affectedRows;
    }

    Public function nextId()
    {
        $this->db->select_max($this->primaryKey);
        $sql = $this->db->get($this->_table);
        $max = $sql->result_array();
        return $max[0][$this->primaryKey] + 1;
    }

    public function nextNumField($field, $bHabilitado = null, $bBorradoLogico = null)
    {
        $this->db->select_max($field);

        if (!is_null($bHabilitado))
        {
            $this->where("bHabilitado", $bHabilitado);
        }

        if (!is_null($bBorradoLogico))
        {
            $this->where("bBorradoLogico", $bBorradoLogico);
        }

        $sql = $this->db->get($this->_table);
        $max = $sql->result_array();
        return $max[0][$field] + 1;
    }

    public function nextField($field, $conditions)
    {
        if ($conditions != NULL)
        {
            if (is_array($conditions))
            {
                $this->db->where($conditions);
            }
            else
            {
                $this->db->where($conditions, NULL, FALSE);
            }
        }

        $this->db->select_max($field);

        $sql = $this->db->get($this->_table);
        $max = $sql->result_array();
        return $max[0][$field] + 1;
    }
    
    public function selectMax($field, $conditions)
    {
        if ($conditions != NULL)
        {
            if (is_array($conditions))
            {
                $this->db->where($conditions);
            }
            else
            {
                $this->db->where($conditions, NULL, FALSE);
            }
        }

        $this->db->select_max($field);

        $sql = $this->db->get($this->_table);
        $max = $sql->result_array();
        return $max[0][$field];
    }

    /**
     * Carga los filtros para las busquedas dependiendo si se encuentran en el modelo
     *
     * @return String $where
     * */
    Public function loadFilters($values, $debug = false)
    {
        $where = '';
        $this->_dump_info($this->_table);
        if (is_object($values))
        {
            $values = $this->db->_object_to_array($values);
        }
        if ($this->attributes_names)
        {
            foreach ($this->attributes_names as $at)
            {
                if ($values[$at] != '' and $values[$at] != null)
                {
                    if (!is_numeric($values[$at]))
                    {
                        $where.= " AND " . $this->_table . "." . $at . " like '%" . ($this->db->escape_str($values[$at])) . "%'";
                    }
                    else
                    {
                        $where.= " AND " . $this->_table . "." . $at . " = '" . ($values[$at]) . "'";
                    }
                }
            }
        }

        $where = substr($where, 4, strlen($where));
        if ($where != '')
        {
            $where = " AND ( $where )";
        }

        if ($debug == true)
        {
            echo "WHERE: " . $where;
        }
        return $where;
    }

    /**
     * Vuelca la informaci&oacute;n de la tabla $table en la base de datos
     * para armar los atributos y meta-data del ActiveRecord
     *
     * @param string $table
     * @return boolean
     */
    private function _dump_info($table, $schema = '')
    {
        foreach ($this->get_metadata($table) as $field)
        {
            if ($field['Key'] == 'PRI')
            {
                $this->primaryKey = $field['Field'];
            }
            else
                $this->non_primary[] = $field['Field'];
            if ($field['Null'] == 'NO')
            {
                $this->not_null[] = $field['Field'];
            }
            if ($field['Type'])
            {
                $this->data_type[$field['Field']] = strtolower($field['Type']);
            }
        }
        $this->attributes_names = $this->fields;
        return true;
    }

    function get_metadata($table = '')
    {
        switch ($this->db->dbdriver)
        {
            case 'mssql':
                $sql = " SELECT
				cols.name as 'Field',
				typs.name as 'Type',
				cols.prec as 'Precisión',
				CASE WHEN Allownulls = '1' THEN 'YES' ELSE 'NO' END  AS 'Null',
				CASE WHEN pk.xtype = 'PK' THEN 'PRI' ELSE pk.xtype END  AS 'Key'
				FROM sysobjects sobj
				INNER JOIN syscolumns cols ON sobj.id=cols.id
				INNER JOIN systypes typs ON cols.xusertype=typs.xusertype
				LEFT JOIN syscomments c ON cdefault=c.id
				LEFT JOIN  sysindexkeys ik ON ik.id = cols.id AND ik.colid = cols.colid
				LEFT JOIN  sysindexes indx ON indx.id = ik.id AND indx.indid = ik.indid
				LEFT JOIN  sysobjects pk ON indx.name = pk.name AND pk.parent_obj = indx.id AND pk.xtype = 'PK'
				WHERE cols.id = OBJECT_ID('" . $table . "' )";
                break;
            default:
                $sql = " DESCRIBE " . $table;
                break;
        }
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    function call($params)
    {
        $aResult = array();
        if (array_key_exists("stored", $params))
        {
            $cCall = "CALL " . $params["stored"];
            $this->db->conn_id->query($cCall);
            $this->db->free_resource();
        }
        
        if (array_key_exists("return", $params))
        {
            $query = $this->db->query($params["return"]);
            $aResult = $query->result_array();
        }
        
        return $aResult;
    }

}

// END Model Class