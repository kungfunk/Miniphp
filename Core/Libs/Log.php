<?php
/**
 * This file is part of Mierdium Framework.
 *
 * (c) Victor Calzón <victor@victorcalzon.com>
 */

namespace Core\Libs;

/**
 * Logger de errores propios de la aplicacion. Los logs se archivan en /App/Logs
 * en formato de texto plano.
 * 
 * @package Mierdium Framework
 * @subpackage Core
 * @category Libs
 * @author Victor Calzón <victor@victorcalzon.com>
 */
class Log
{
    public static function write($user = 'guest', $action = 'none', $text = '') {
        if(\App\Config::get('log')) {
            switch(\App\Config::get('logType')) {
                case 'plaintext':
                    return self::writeToPlaintext($user, $action, $text);
                break;
            }
        }
    }

    private static function writeToPlaintext($user, $action, $text) {
        $path = \App\Config::get('logStore');
        $file = date('Y-m-d');
        $time = date('H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'];
        file_put_contents($path.$file, $time.' '.$user.' '.$ip.' '.$action.' '.$text."\n", FILE_APPEND);
        return true;
    }

    public static function load($log) {
        if(\App\Config::get('log')) {
            switch(\App\Config::get('logType')) {
                case 'plaintext':
                    return self::loadPlaintext($log);
                break;
            }
        }
    }

    private static function loadPlaintext($filename) {
        $path = \App\Config::get('logStore');
        if(file_exists($path.$filename)) {
            return file_get_contents($path.$filename);
        }
        return false;
    }

    public static function getList() {
        if(\App\Config::get('log')) {
            switch(\App\Config::get('logType')) {
                case 'plaintext':
                    return self::listPlaintext();
                break;
            }
        }
    }

    public static function listPlaintext() {
        if (($handle = opendir(\App\Config::get('logStore')))) {
            $list = array();
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && $file != '.svn') {
                    $list[] = $file;
                }
            }
            closedir($handle);
            return $list;
        }
        return false;
    }
}
?>
