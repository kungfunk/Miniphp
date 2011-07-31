<?php
/**
 * This file is part of Mierdium Framework.
 *
 * (c) Victor Calzón <victor@victorcalzon.com>
 */

namespace Core\Libs;

/**
 * Modelo base del que extienden todos los modelos ubicados en App\Models, y 
 * a su vez extiende a ActiveRecord\Model.
 * 
 * @package Mierdium Framework
 * @subpackage Core
 * @category Libs
 * @author Victor Calzón <victor@victorcalzon.com>
 */
namespace Core\Libs;

class Model extends \ActiveRecord\Model
{
    private $_db;
    private $_model;

    public function __construct() {
        try {
            parent::__construct();
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function __toString() {
        return 'Model: '.$this->_model;
    }
}
?>