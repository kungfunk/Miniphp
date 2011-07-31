<?php
/**
 * This file is part of Mierdium Framework.
 *
 * (c) Victor Calzón <victor@victorcalzon.com>
 */

namespace Core\Libs;

/**
 * Clase basica de control de usuarios.
 * Un usuario no autentificado contra la base de datos, genera un usuario virtual
 * tipo "invitado".
 * 
 * @package Mierdium Framework
 * @subpackage Core
 * @category Libs
 * @author Victor Calzón <victor@victorcalzon.com>
 */
class Auth
{
    private static $me;

    public $id;
    public $username;
    public $rol;
    public $permissions;
    public $fullname;

    public $user;

    private $loggedIn;

    private function __construct($user_to_impersonate = null) {
        $this->generateEmptyUser();

        if(!is_null($user_to_impersonate))
            return $this->impersonate($user_to_impersonate);

        if($this->attemptSessionLogin())
           return;

        if($this->attemptCookieLogin())
            return;
    }

    private function generateEmptyUser() {
        $this->id = null;
        $this->username = 'Invitado';
        $this->rol = 'Invitado';        
        try {
        $this->user = new \App\Models\Usuario();
        $this->permissions = Array('CONT_LOGIN_INDEX');
        $this->loggedIn = false;
        }
        catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public static function getAuth($user_to_impersonate = null)
    {
        if(is_null(self::$me))
            self::$me = new Auth($user_to_impersonate);
        return self::$me;
    }

    public function login($un, $pw)
    {
        $pw = $this->createHashedPassword($pw);
        return $this->attemptLogin($un, $pw);
    }

    public function logout() {
        $this->generateEmptyUser();
        $Config = \App\Config::getConfig();
        
        $_SESSION['un'] = '';
        $_SESSION['pw'] = '';
        setcookie($Config->cookieName, '.', time() - 3600, '/', $Config->authDomain);
    }

    public function loggedIn()
    {
        return $this->loggedIn;
    }

    public function requirePermission($permission) {
        return (in_array($permission, $this->permissions));
    }

    public function requireRole($role) {
        return ($role == $this->rol);
    }

    public function passwordIsCorrect($pw)
    {
        $Config = \App\Config::getConfig();

        if($Config->useHashedPasswords === true)
            $pw = $this->createHashedPassword($pw);

        return (\App\Models\Usuario::find(array('username' => $this->username, 'password' => $pw)) != null);
    }

    public function impersonate($user_to_impersonate) {

    }

    private function attemptSessionLogin()
    {
        if(isset($_SESSION['un']) && isset($_SESSION['pw']))
            return $this->attemptLogin($_SESSION['un'], $_SESSION['pw']);
        else
            return false;
    }

    private function attemptCookieLogin()
    {
        $Config = \App\Config::getConfig();
            if(isset($_COOKIE[$Config->cookieName]) && is_string($_COOKIE[$Config->cookieName]))
        {
            $s = json_decode($_COOKIE[$Config->cookieName], true);

            if(isset($s['un']) && isset($s['pw']))
            {
                return $this->attemptLogin($s['un'], $s['pw']);
            }
        }

        return false;
    }

    private function attemptLogin($un, $pw)
    {
        $Config = \App\Config::getConfig();
        $usuario = \App\Models\Usuario::find_by_username($un);
        
        // cuidadito aqui, que antes habia un === false
        if($usuario === null) return false;

        if($Config->useHashedPasswords === false)
            $usuario->password = $this->createHashedPassword($usuario->password);

        if($pw != $usuario->password) return false;

        $this->id  = $usuario->id;
        $this->username = $usuario->username;
        $this->rol = $usuario->rol->nombre;
        foreach($usuario->rol->permisos as $permiso)
            $this->permissions[] = $permiso->descripcion;
        $this->fullname = $usuario->nombre.' '.$usuario->apellidos;
        $this->user = $usuario;
        
        $this->storeSessionData($un, $pw);
        $this->loggedIn = true;

        return true;
    }

    private function storeSessionData($un, $pw)
    {
        if(headers_sent()) return false;
        $Config = \App\Config::getConfig();
        $_SESSION['un'] = $un;
        $_SESSION['pw'] = $pw;
        $s = json_encode(array('un' => $un, 'pw' => $pw));
        return setcookie($Config->cookieName, $s, time()+60*60*24*30, '/', $Config->authDomain);
    }

    private function createHashedPassword($pw)
    {
        $Config = \App\Config::getConfig();
        return sha1($pw . $Config->authSalt);
    }
}
?>