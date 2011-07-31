<?php
/**
 * This file is part of Mierdium Framework.
 *
 * (c) Victor Calzón <victor@victorcalzon.com>
 */

function loader($class) {
    $file = ROOT_PATH.'/'.str_replace('\\', '/', $class) . '.php';
    if(file_exists($file))
        require_once $file;
}

spl_autoload_register('loader');
?>
