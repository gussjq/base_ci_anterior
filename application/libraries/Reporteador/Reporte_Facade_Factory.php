<?php


class Reporte_Facade_Factory
{
    public static function Build($clase)
    {
        if(class_exists($clase))
        {
            return new $clase();
        }
        else
        {
            throw new Exception("No se pudo fabricar el objecto de la clase -> " . $clase);
        }
    }
}

