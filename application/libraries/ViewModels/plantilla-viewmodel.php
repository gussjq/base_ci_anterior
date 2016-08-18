<?php

/**
 * ${modulo_singular}_ViewModdel
 * 
 * Clase que interactua como una capa intermedia entre el modelo y la vista,
 * donde se encarga de pasar datos o información requerida entre estas dos capas, mediante un objeto que contiene
 * todos los atributos necesarios ya sea para mostrar información en una forma (Vista) o para agregar o actualizar información
 * en la tabla (Modelo), de esta manera se asegura que siempre cada proceso contara con lo necesario para realizar su función 
 * 
 * @package ViewModels
 * @created dd-mm-aaaa
 * @author Nombre del programador <correo@ejemplo.com> cel <numero>
 */
require_once('Reportes_ViewModel.php');
class ${modulo_singular}_ViewModel extends Reportes_ViewModel {

    /** @var int description */
    public $id${modulo_singular};
}
