<?php
/**
 * This file is part of Mierdium Framework.
 *
 * (c) Victor Calzón <victor@victorcalzon.com>
 */

namespace Core\Libs;

/**
 * Clase abstracta que sirve como base para el resto de controladores que se
 * situan en /App/Controllers.
 * 
 * @package Mierdium Framework
 * @subpackage Core
 * @category Libs
 * @author Victor Calzón <victor@victorcalzon.com>
 */
abstract class Controller
{
    public $config;
    public $input;
    public $url;
    public $header;
    public $footer;

    public $action;
    public $page;

    public $permissions;

    public function __construct() {
        $this->header = new \Core\Libs\View();
        $this->footer = new \Core\Libs\View();
    }

    abstract public function index();

    public function view($content) {
        if(\App\Config::get('useTheme'))
            $this->header->theme_includes = \Core\Libs\Themes::getIncludes(\App\Config::get('theme'));
        echo $this->header->display(array('common', 'header'));
        echo $content;
        echo $this->footer->display(array('common', 'footer'));
    }

    public function ajax_view($content) {
        echo $content;
    }

    public function __call($name, $arguments) {
        if(!method_exists($this, $name)) {
            throw new \Core\Libs\Fail('Undefined method '.$name, 50);
        }
        else {
            call_user_func(array($this, $name), $arguments);
        }
    }

    public function ajax_check() {
        if(!is_ajax())
            throw new \Core\Libs\Fail('Imposible aceptar una peticion directa en esta url', 500);
    }
}
?>
