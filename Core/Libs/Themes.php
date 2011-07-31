<?php
/**
 * This file is part of Mierdium Framework.
 *
 * (c) Victor Calzón <victor@victorcalzon.com>
 */

namespace Core\Libs;

/**
 * Sistema de themes (css/js/imagenes y demas estaticos) en version MUY preliminar.
 * 
 * @package Mierdium Framework
 * @subpackage Core
 * @category Libs
 * @author Victor Calzón <victor@victorcalzon.com>
 */
namespace Core\Libs;

class Themes
{
    public static function getIncludes($theme) {
        include_once (\ROOT_PATH.'/public/static/themes/'.strtolower($theme).'/head');
        return $head;
    }
}
?>
