<?php
/**
 * This file is part of Mierdium Framework.
 *
 * (c) Victor Calzón <victor@victorcalzon.com>
 */

namespace Core\Utils;

/**
 * Generador de etiquetas html <select>
 * 
 * @package Mierdium Framework
 * @subpackage Core
 * @category Utils
 * @author Victor Calzón <victor@victorcalzon.com>
 */
class HtmlSelect
{
    public $html_select;
    public $html_option;
    public $name;
    public $options;
    public $selected;

    public function __construct($name, $options, $selected = null) {
        $this->name = $name;
        $this->options = $options;
        $this->selected = $selected;
        $this->html_select = '<select id="{name}" name="{name}">{content}</select>';
        $this->html_option = '<option value="{key}" {selected}>{value}</option>';
    }

    public function generate() {
        $options = '';
        foreach ($this->options as $key=>$value) {
            $selected = ($key == $this->selected) ? 'selected="selected"' : '';
            $options .= str_replace(array('{key}', '{value}', '{selected}'), array($key, $value, $selected), $this->html_option);
        }
        $select = str_replace(array('{name}', '{content}'), array($this->name, $options), $this->html_select);
        return $select;
    }
}
?>
