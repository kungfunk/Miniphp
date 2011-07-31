<?php
/**
 * This file is part of Mierdium Framework.
 *
 * (c) Victor Calzón <victor@victorcalzon.com>
 */

namespace Core\Utils;

/**
 * Utilidad que genera combos a partir de \Core\Utils\HtmlSelect llenándolos 
 * con el contenido de diferentes tablas de la base de datos
 * 
 * @package Mierdium Framework
 * @subpackage Core
 * @category Utils
 * @author Javi González <javigh@gmail.com>
 */
class Combo
{
    public $combo;

    /**
     * Genera un combo con los elementos de la tabla usuarios
     *
     * @param string $nombre nombre con el que nos vamos a referir al combo, por defecto es 'usuario_id'
     * @param string $selected id del uduario que debe aparecer seleccionado, por defecto es null
     * @param boolean $vacio indica si el combo debe tener un primer elemento vacío, por defecto es false
     * @return string código HTML del combo generado
     */
    public static function usuarios($nombre = 'usuario_id', $selected = null, $vacio = false) {
        $usuarios = \App\Models\Usuario::all();
        $options = array();
        if($vacio == true){
            $options[null] = '&nbsp;';
        }
        foreach($usuarios as $usuario)
            $options[$usuario->id] = $usuario->apellidos.', '.$usuario->nombre;
        $select = new \Core\Utils\HtmlSelect($nombre, $options, $selected);
        return $select->generate(); 
    }

    /**
     * Genera un combo box con los datos de una tabla tipo {nombre, descripcion}
     *
     * @param string $tabla Nombre de la tabla que hay que leer
     * @param string $nombre Nombre que va a tener el combo dentro del formulario, por defecto es $tabla_id
     * @param int $selected id del elemento seleccionado al crear el combo, por defecto es null
     * @param booleano $vacio Indica si el combo debe tener un elemento vacío al principio, por defecto es false
     * @return código HTML del combo generado
     */
    public static function tipo($tabla ,$nombre = null , $selected = null, $vacio = false) {
        $modelo = '\App\Models\''.$tabla;
        $datos = $modelo::all();
        $options = array();
        if(is_null($nombre)){
            $nombre = strtolower($tabla.'_id');
        }
        if($vacio == true){
            $options[null] = '&nbsp;';
        }
        
        foreach($datos as $dato)
            $options[$dato->id] = $dato->descripcion;
        $select = new \Core\Utils\HtmlSelect($nombre, $options, $selected);
        return $select->generate();
    }

    /**
     * Genera un combo box con horas
     *
     * @param integer $tipo Selecciona entre mostrar un combo de 12 o 24 horas. Por defecto es de 24
     * @param integer $selected Hora que aparece seleccionada en el combo. Por defecto es null
     * @return código HTML del combo generado
     */
    public static function horas($selected = null, $name = 'horas', $tipo = 24) {
        for($contador = 0;$contador<=$tipo-1;$contador++){
            $hora = (strlen(strval($contador)) < 2) ? '0'.strval($contador) : $contador;
            $options[$hora] = $hora;
        }
        $select = new \Core\Utils\HtmlSelect($name, $options, $selected);
        return $select->generate();
    }

    /**
     * Genera un combo box con minutos
     *
     * @param integer $intervalo Intervalos de minutos mostrados. Por defecto es 1
     * @param integer $selected Minuto que aparece seleccionado en el combo. Por defecto es null
     * @return código HTML del combo generado
     */
    public static function minutos($selected = null, $name = 'minutos', $intervalo = 1) {
        for($contador = 0;$contador<=59;$contador+=$intervalo){
            $minutos = (strlen(strval($contador)) < 2) ? '0'.strval($contador) : $contador;
            $options[$minutos] = $minutos;
        }
        $select = new \Core\Utils\HtmlSelect($name, $options, $selected);
        return $select->generate();
    }
}
?>