<?php
/**
 * This file is part of Mierdium Framework.
 *
 * (c) Victor Calzón <victor@victorcalzon.com>
 */

namespace Core\Libs;

/**
 * Clase que extiende el sistema basico de excepciones de php.
 * Se encarga de añadir los errores al colector Libs\Error y loguearlos si
 * asi lo hemos indicado en el Config.
 * 
 * @package Mierdium Framework
 * @subpackage Core
 * @category Libs
 * @author Victor Calzón <victor@victorcalzon.com>
 */
class Fail extends \Exception
{
    /**
     *
     * @param string $message
     * @param int $code
     * @param Exception $previous 
     */
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
        $this->toError($code, $message);
        $this->toLog($code, $message);
    }

    /**
     *
     * @param int $code
     * @param string $message 
     */
    public function toError($code, $message) {
        \Core\Libs\Error::getError()->add($code, $message);
    }

    public function toLog($code, $message) {
        if(\App\Config::get('logExceptions')) {
            $user = $this->getUser($code);
            \Core\Libs\Log::write($user, $code, $message);
        }
    }

    private function getUser($code) {
        if($code == 80) {
            return 'SYSTEM';
        }
        $auth = \Core\Libs\Auth::getAuth();
        return $auth->username;
    }
}
?>
