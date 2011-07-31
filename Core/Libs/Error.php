<?php
/**
 * This file is part of Mierdium Framework.
 *
 * (c) Victor Calzón <victor@victorcalzon.com>
 */

namespace Core\Libs;

/**
 * Colector de errores.
 * 
 * @package Mierdium Framework
 * @subpackage Core
 * @category Libs
 * @author Victor Calzón <victor@victorcalzon.com>
 */
class Error
{
    private static $me;
    public $errors;

    private function __construct() {
        $this->errors = array();
    }

    public static function getError() {
        if(is_null(self::$me))
            self::$me = new \Core\Libs\Error();
        return self::$me;
    }

    public function ok() {
        return count($this->errors) == 0;
    }

    public function add($id, $msg) {
        if(isset($this->errors[$id]) && !is_array($this->errors[$id]))
            if(is_array($msg)) {
                foreach($msg as $key=>$val) {
                    $this->errors[$id][$key] = $val;
                }
            }
            else {
                $this->errors[$id] = array($msg);
            }
        else
            if(is_array($msg)) {
                foreach($msg as $key=>$val) {
                    $this->errors[$id][$key] = $val;
                }
            }
            else {
                $this->errors[$id][] = $msg;
            }
    }

    public function delete($id) {
        unset($this->errors[$id]);
    }

    public function msg($id, $onlyfirsterror = false) {
        if($onlyfirsterror && is_array($this->errors[$id])) {
            return $this->errors[$id][0];
        }
        return $this->errors[$id];
    }

    public function check($id) {
        return !empty($this->errors[$id]);
    }

    public function showAll() {
        printr($this->errors);
    }
}
?>
