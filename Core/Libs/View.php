<?php
/**
 * This file is part of Mierdium Framework.
 *
 * (c) Victor Calzón <victor@victorcalzon.com>
 */

namespace Core\Libs;

/**
 * Generador de vistas. Se encarga de buscar el .html correcto e insertar las 
 * variables que hayamos seteado desde el controlador.
 * 
 * @package Mierdium Framework
 * @subpackage Core
 * @category Libs
 * @author Victor Calzón <victor@victorcalzon.com>
 */
class View
{
    public $tpl_path;
    public $tpl_name;
    public $tpl_ext;
    private $fullname;
    protected $vars = array();

    public function __construct() {
        $this->tpl_ext = '.html';
        $this->tpl_path = ROOT_PATH.'/App/Views/';
    }

    public function __set($key, $value)
    {
        $this->vars[$key] = $value;
        return $value;
    }

    public function setArray($array) {
        if(!is_array($array))
            return false;
        foreach($array as $k => $v) {
            $this->vars[$k] = $v;
        }
    }

    public function display($template) {
        $this->tpl_name = implode('/', $template);
        if($this->findfile() === false) {
            throw new \Core\Libs\Fail('View not found '.$template, 10);
        }
        else {
            extract($this->vars, EXTR_REFS);
            ob_start(array($this, 'postprocess'));
            include $this->fullname;
            $contents = ob_get_contents();
            ob_end_clean();
            return $contents;
        }
    }

    private function postprocess($buffer) {
        return $buffer;
    }

    private function findfile() {
        $fullname = $this->tpl_path.$this->tpl_name.$this->tpl_ext;
        if (file_exists($fullname) && is_readable($fullname)) {
            $this->fullname = $fullname;
            return true;
        }
        else {
            return false;
        }
    }
}
?>
