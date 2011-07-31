<?php
/**
 * This file is part of Mierdium Framework.
 *
 * (c) Victor Calzón <victor@victorcalzon.com>
 */
/**
 * Fichero de entrada, todas las peticiones pasan por aqui.
 * Mirar .htaccess para mas info.
 */
//ini_set('xdebug.auto_trace', 'On');
define('ROOT_PATH', realpath(dirname(__FILE__).'/../'));

// Global include files
require ROOT_PATH . '/Core/functions.php';
require ROOT_PATH . '/Core/autoload.php';

$dispatcher = new \Core\Libs\Dispatcher();
$dispatcher->showtime();
?>