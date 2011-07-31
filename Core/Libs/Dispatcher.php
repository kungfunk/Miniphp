<?php
/**
 * This file is part of Mierdium Framework.
 *
 * (c) Victor Calzón <victor@victorcalzon.com>
 */

namespace Core\Libs;

/**
 * El dispatcher se encarga de enrrutar cada peticion a su respectivo 
 * controlador/metodo, haciendo uso de la clase Url. Tambien se encarga de 
 * despachar los errores y hacia su respectivo controlador y de iniciar las
 * sesiones.
 * 
 * @package Mierdium Framework
 * @subpackage Core
 * @category Libs
 * @author Victor Calzón <victor@victorcalzon.com>
 */
class Dispatcher
{
    public $config;
    public $controller;
    public $db;

    public $input = array();
    public $url = array();

    public function __construct() {
        $this->config = \App\Config::getConfig();
        $this->db = \Core\Database\Factory::getDB();
        $this->url = \Core\Libs\Url::parseUrl(REQUEST_URI);
        $this->clean_input();
        $this->session();
    }

    public function pool() {
        return true;
    }

    public function showtime() {
        $controller_token = '\App\Controllers\\'.$this->url['controller'];
        try {
            if (class_exists($controller_token)) {
                $this->controller = new $controller_token;
                if(!empty($this->url['vars']) && method_exists($this->controller, $this->url['vars'][0])) {
                    $this->controller->action = $this->url['vars'][0];
                }
                else {
                    $this->controller->action = (!empty($this->url['vars'])) ? 'item' : 'index';
                }
                if(!method_exists($this->controller, $this->controller->action))
                    throw new \Core\Libs\Fail('Pagina no encontrada: '.SELF_URL, 404);
                $auth = \Core\Libs\Auth::getAuth();
                if(!$auth->requirePermission(@$this->controller->permissions[$this->controller->action]))
                    throw new \Core\Libs\Fail('No estas autorizado '.SELF_URL, 403);
            }
            else {
                throw new \Core\Libs\Fail('Pagina no encontrada: '.SELF_URL, 404);
            }
            $this->controller->input = $this->input;
            $this->controller->url = $this->url['vars'];
            $content = call_user_func(array($this->controller, $this->controller->action));
            if($this->config->ajax && is_ajax() && method_exists($this->controller, 'ajax_view'))
                $this->controller->ajax_view($content);
            else
                $this->controller->view($content);
        }
        catch (\Core\Libs\Fail $e) {
            $this->controller = new \App\Controllers\error;
            $this->controller->action = 'index';
            $this->controller->input = $this->input;
            $this->controller->url = $this->url['vars'];
            $content = call_user_func(array($this->controller, $this->controller->action));
            if($this->config->ajax && is_ajax() && method_exists($this->controller, 'ajax_view'))
                $this->controller->ajax_view($content);
            else
                $this->controller->view($content);
        }
    }

    private function clean_input() {
        $this->input['post'] = $_POST;
        $this->input['get'] = $_GET;
        $this->input['files'] = $_FILES;
        unset($_POST);
        //unset($_GET); comentado por la clase del facebook, que pilla la sesion por $_GET
        unset($_FILES);
    }

    private function session() {
        session_name($this->config->sessionName);
        session_start();
    }
}
?>