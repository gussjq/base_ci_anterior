<?php

/*
 * ${modulo_singular_mayuscula}_model
 * 
 * Clase que sirve como representación de la capa de datos de la tabla de ${modulo_singular_mayuscula},
 * en donde se encarga de la conexion a la base de datos asi como de realizar las operaciones basicas en la tabla de acuerdo al 
 * nivel de acceso descrita en la logica del negocio (Controller) 
 * 
 * @package models
 * @author Nombre del programador <correo@ejemplo.com> cel : <numero>
 * @created dd-mm-aaaa
 */

class ${modulo_singular_mayuscula}_model extends MY_Model {


    public function __construct() {
        parent::__construct();
        $this->loadTable("");
    }


    /**
     * Metodo que se encarga de retornar un array de datos de ${modulo_singular_mayuscula}, de acuerdo al criterio de filtrado
     * puede retornar un array de objectos o un array de arrays 
     * 
     * @access public
     * @param object viewmodel $o${modulo_singular_mayuscula}
     * @param booelan $bArray para especificar el tipo de retorno true array false object
     * @return array $aResult informacion de la tabla
     */
    public function getAll($o${modulo_singular_mayuscula}, $bArray = false) {

        $this->db->select("", FALSE);
        $this->db->from("");

        // filtros por ${modulo_singular_mayuscula}
        if ($o${modulo_singular_mayuscula}->id${modulo_singular_mayuscula}) {
            $this->db->where("id${modulo_singular_mayuscula}", $o${modulo_singular_mayuscula}->id${modulo_singular_mayuscula});
        }
        
        //filtros generales
        if ($o${modulo_singular_mayuscula}->limit && $o${modulo_singular_mayuscula}->offset) {
            $this->db->limit($o${modulo_singular_mayuscula}->limit, $o${modulo_singular_mayuscula}->offset);
        } else {
            if ($o${modulo_singular_mayuscula}->limit) {
                $this->db->limit($o${modulo_singular_mayuscula}->limit);
            }
        }

        if ($o${modulo_singular_mayuscula}->sortBy && $o${modulo_singular_mayuscula}->order) {
            $this->db->order_by($o${modulo_singular_mayuscula}->order . ' ' . $o${modulo_singular_mayuscula}->sortBy);
        }

        if (is_array($o${modulo_singular_mayuscula}->not) && count($o${modulo_singular_mayuscula}->not) > 0) {
            foreach ($o${modulo_singular_mayuscula}->not as $key => $value) {
                $this->db->where_not_in($key, $value);
            }
        }

        if ($o${modulo_singular_mayuscula}->count) {
            return $this->db->count_all_results();
        }

        $query = $this->db->get();
        $aResult = array();
        if (!$this->db->_error_message()) {
            if ($query->num_rows() > 0) {
                $aResult = ($bArray) ? $query->result_array() : $query->result();
            }
        }
        return $aResult;
    }


    /**
     * Metodo que se encarga de retornar un unico registro, de acuerdo al criterio de filtrado
     * puede retornar un objeto o un array
     * 
     * @access public
     * @param object viewmodel $o${modulo_singular_mayuscula}
     * @param booelan $bArray para especificar el tipo de retorno true array false object
     * @return array $aResult informacion de la tabla
     */
    public function get($o${modulo_singular_mayuscula}, $bArray = FALSE) {
        $aData = $this->getAll($o${modulo_singular_mayuscula}, $bArray);
        if (is_numeric($aData)) {
            return $aData;
        }
        return (count($aData) > 0) ? $aData[0] : array();
    }


    /**
     * Metodo que se encarga de agregar un nuevo ${modulo_singular_mayuscula} a la tabla, recibe como parametro
     * un viewmodel de ${modulo_singular_mayuscula} con los datos necesarios para su registro
     * 
     * @access public
     * @param object viewmodel $o${modulo_singular_mayuscula}
     * @return int indentificador del registro insertado
     */
    public function insertar($o${modulo_singular_mayuscula}) {
        $this->create();
        $this->save($o${modulo_singular_mayuscula});
        return $this->getInsertID();
    }


    /**
     * Metodo que se encarga de actualizar un registro ${modulo_singular_mayuscula} en la tabla, recibe como parametro
     * un objeto viewmodel de ${modulo_singular_mayuscula} con los datos necesarios para su actualizacion
     * 
     * @access public
     * @param object viewmodel $o${modulo_singular_mayuscula}
     * @return int indentificador del registro a actualizado
     */
    public function actualizar($o${modulo_singular_mayuscula}) {
        $this->save($o${modulo_singular_mayuscula}, $o${modulo_singular_mayuscula}->id${modulo_singular_mayuscula});
        return $this->getID();
    }
}
