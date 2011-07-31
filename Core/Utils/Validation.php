<?php
/**
 * This file is part of Mierdium Framework.
 *
 * (c) Victor Calzón <victor@victorcalzon.com>
 */

namespace Core\Utils;

/**
 * Validaciones que generan un array con errores en caso de haberlos.
 * No confundir con el sistema de validacion que esta implementado las clases
 * Active Record.
 * 
 * @package Mierdium Framework
 * @subpackage Core
 * @category Utils
 * @author Victor Calzón <victor@victorcalzon.com>
 */
class Validation
{

    public $errors = array();
    private $validation_rules = array();
    public $sanitized = array();
    private $source = array();
    private $error_text = array();

    public function __construct() {
        $this->error_text['not_set'] = 'El campo "{var}" no esta declarado.';
        $this->error_text['IPv4'] = 'El campo "{var}" no es una cadena IPv4 valida.';
        $this->error_text['IPv6'] = 'El campo "{var}" no es una cadena IPv6 valida.';
        $this->error_text['float'] = 'El campo "{var}" no es un float valido.';
        $this->error_text['short'] = 'El campo "{var}" es demasiado corto.';
        $this->error_text['long'] = 'El campo "{var}" es demasiado largo.';
        $this->error_text['invalid'] = 'El campo "{var}" es invalido.';
        $this->error_text['number'] = 'El campo "{var}" no es un valor numerico.';
        $this->error_text['url'] = 'El campo "{var}" no es una URL correcta.';
        $this->error_text['email'] = 'El campo "{var}" no es una direccion de email valida.';
    }

    public function addSource($source, $trim = false) {
        $this->source = $source;
    }

    public function run() {
        foreach( new \ArrayIterator($this->validation_rules) as $var=>$opt)
        {
            if($opt['required'] == true) {
                $this->is_set($var);
            }

            if( array_key_exists('trim', $opt) && $opt['trim'] == true ) {
                $this->source[$var] = @trim( $this->source[$var] ); //el @ es para las variables sin required que no estan seteadas
            }

            switch($opt['type']) {
                case 'array':
                    $this->validateArray($var, $opt['required']);
                    if(!array_key_exists($var, $this->errors))
                        $this->sanitizeArray($var);
                break;
                case 'object':
                    $this->validateObject($var, $opt['required']);
                    if(!array_key_exists($var, $this->errors))
                        $this->sanitizeObject($var);
                break;
                case 'email':
                    $this->validateEmail($var, $opt['required']);
                    if(!array_key_exists($var, $this->errors))
                        $this->sanitizeEmail($var);
                break;
                case 'url':
                    $this->validateUrl($var);
                    if(!array_key_exists($var, $this->errors))
                        $this->sanitizeUrl($var);
                break;
                case 'numeric':
                    $this->validateNumeric($var, $opt['min'], $opt['max'], $opt['required']);
                    if(!array_key_exists($var, $this->errors))
                        $this->sanitizeNumeric($var);
                break;
                case 'string':
                    $this->validateString($var, $opt['min'], $opt['max'], $opt['required']);
                    if(!array_key_exists($var, $this->errors))
                        $this->sanitizeString($var);
                break;
                case 'html_string':
                    $this->validateString($var, $opt['min'], $opt['max'], $opt['required']);
                    if(!array_key_exists($var, $this->errors))
                        $this->sanitizeSpecial_chars($var);
                break;
                case 'float':
                    $this->validateFloat($var, $opt['required']);
                    if(!array_key_exists($var, $this->errors))
                        $this->sanitizeFloat($var);
                break;
                case 'ipv4':
                    $this->validateIpv4($var, $opt['required']);
                    if(!array_key_exists($var, $this->errors))
                        $this->sanitizeIpv4($var);
                break;
                case 'ipv6':
                    $this->validateIpv6($var, $opt['required']);
                    if(!array_key_exists($var, $this->errors))
                        $this->sanitizeIpv6($var);
                break;
                case 'bool':
                    $this->validateBool($var, $opt['required']);
                    if(!array_key_exists($var, $this->errors))
                        $this->sanitized[$var] = (bool) $this->source[$var];
                break;
            }
        }
    }

    public function addRule($varname, $type, $required = false, $min = 0, $max = 0, $trim = false) {
        $this->validation_rules[$varname] = array('type'=>$type, 'required'=>$required, 'min'=>$min, 'max'=>$max, 'trim'=>$trim);
        return $this;
    }

    public function AddRules(array $rules_array) {
        $this->validation_rules = array_merge($this->validation_rules, $rules_array);
    }

    private function is_set($var) {
        if(!isset($this->source[$var])) {
            $this->errors[$var] = str_replace('{var}', $var, $this->error_text['not_set']);
        }
    }

    private function validateIpv4($var, $required = false) {
        if($required==false && strlen($this->source[$var]) == 0) {
            return true;
        }
        if(filter_var($this->source[$var], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === FALSE) {
            $this->errors[$var] = str_replace('{var}', $var, $this->error_text['IPv4']);
        }
    }

    public function validateIpv6($var, $required = false) {
        if($required==false && strlen($this->source[$var]) == 0) {
            return true;
        }
        if(filter_var($this->source[$var], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === FALSE) {
            $this->errors[$var] = str_replace('{var}', $var, $this->error_text['IPv6']);
        }
    }

    private function validateFloat($var, $required = false) {
        if($required==false && strlen($this->source[$var]) == 0) {
            return true;
        }
        if(filter_var($this->source[$var], FILTER_VALIDATE_FLOAT) === false) {
            $this->errors[$var] = str_replace('{var}', $var, $this->error_text['float']);
        }
    }

    private function validateString($var, $min = 0, $max = 0, $required = false) {
        if($required==false && strlen($this->source[$var]) == 0) {
            return true;
        }

        if(isset($this->source[$var])) {
            if(strlen($this->source[$var]) < $min) {
                $this->errors[$var] = str_replace('{var}', $var, $this->error_text['short']);
            }
            elseif(strlen($this->source[$var]) > $max) {
                $this->errors[$var] = str_replace('{var}', $var, $this->error_text['long']);
            }
            elseif(!is_string($this->source[$var])) {
                $this->errors[$var] = str_replace('{var}', $var, $this->error_text['invalid']);
            }
        }
    }

    private function validateNumeric($var, $min=0, $max=0, $required=false) {
        if($required==false && strlen($this->source[$var]) == 0) {
            return true;
        }
        if(filter_var($this->source[$var], FILTER_VALIDATE_INT, array("options" => array("min_range"=>$min, "max_range"=>$max)))===FALSE) {
            $this->errors[$var] = str_replace('{var}', $var, $this->error_text['number']);
        }
    }

    private function validateUrl($var, $required = false) {
        if($required==false && strlen($this->source[$var]) == 0) {
            return true;
        }
        if(filter_var($this->source[$var], FILTER_VALIDATE_URL) === FALSE) {
            $this->errors[$var] = $str_replace('{var}', $var, $this->error_text['url']);
        }
    }

    private function validateEmail($var, $required = false) {
        if($required==false && strlen($this->source[$var]) == 0) {
            return true;
        }
        if(filter_var($this->source[$var], FILTER_VALIDATE_EMAIL) === FALSE) {
            $this->errors[$var] = str_replace('{var}', $var, $this->error_text['email']);
        }
    }

    private function validateBool($var, $required = false) {
        if($required==false && strlen($this->source[$var]) == 0) {
            return true;
        }
        filter_var($this->source[$var], FILTER_VALIDATE_BOOLEAN);
        {
            $this->errors[$var] = str_replace('{var}', $var, $this->error_text['invalid']);
        }
    }

    private function validateArray($var, $required = false) {
        if($required==false && count($this->source[$var]) == 0) {
            return true;
        }
        if(!is_array($this->source[$var])) {
            $this->errors[$var] = str_replace('{var}', $var, $this->error_text['invalid']);
        }
    }
    
    private function validateObject($var, $required = false) {
        if($required==false && strlen($this->source[$var]) == 0) {
            return true;
        }
        if(!is_object($this->source[$var])) {
            $this->errors[$var] = str_replace('{var}', $var, $this->error_text['invalid']);
        }
    }

    public function sanitizeEmail($var) {
        $email = preg_replace( '((?:\n|\r|\t|%0A|%0D|%08|%09)+)i' , '', $this->source[$var] );
        $this->sanitized[$var] = (string) filter_var($email, FILTER_SANITIZE_EMAIL);
    }

    private function sanitizeUrl($var) {
        $this->sanitized[$var] = (string) filter_var($this->source[$var],  FILTER_SANITIZE_URL);
    }

    private function sanitizeNumeric($var) {
        $this->sanitized[$var] = (int) filter_var($this->source[$var], FILTER_SANITIZE_NUMBER_INT);
    }

    private function sanitizeString($var) {
        $this->sanitized[$var] = (string) filter_var($this->source[$var], FILTER_SANITIZE_STRING);
    }

    private function sanitizeSpecial_chars($var) {
        $this->sanitized[$var] = (string) filter_var($this->source[$var], FILTER_SANITIZE_SPECIAL_CHARS);
    }

    private function sanitizeArray($var) {
        $this->sanitized[$var] = $this->source[$var];
    }

    private function sanitizeObject($var) {
        $this->sanitized[$var] = $this->source[$var];
    }
}
?>