<?php
/**
 * This file is part of Mierdium Framework.
 *
 * (c) Victor Calzón <victor@victorcalzon.com>
 */

namespace App;

/**
 * Aqui configuramos los servidores, la base de datos y algunos datos mas.
 * 
 * @package Mierdium Framework
 * @subpackage App
 * @category Config
 * @author Victor Calzón <victor@victorcalzon.com>
 */
class Config
{
    private static $me;

    // ($_SERVER['HTTP_HOST'])
    private $productionServers = array('', '');
    private $localServers = array('localhost');
    private $testServers = array('gualda.cobaltosoftware.com');

    // ajax mode
    public $ajax;

    // logs
    public $log;
    public $logType;
    public $logStore;
    public $logExceptions;

    // imagenes temporales
    public $tempStore;

    // clase auth
    public $authDomain;         // Dominio para la cookie
    public $authSalt;           // Hash a añadir a las contraseñas
    public $useHashedPasswords; // Guardar contraseñas con hash en o directamente
    public $cookieName;		// Nombre de la cookie que vamos a guardar

    // clase database (ver Factory.php)
    public $dbDriver;
    public $dbHost;       // Database server
    public $dbName;       // Database name
    public $dbUsername;   // Database username
    public $dbPassword;   // Database password
    
    public $useDBSessions;

    // Singleton constructor
    private function __construct() {
        $this->everywhere();

        $i_am_here = $this->whereAmI();

        if('production' == $i_am_here)
            $this->production();
        elseif('test' == $i_am_here)
            $this->test();
        elseif('local' == $i_am_here)
            $this->local();
        else
            die('<h1>¿Has olvidado el Config.php?</h1> 
                 <p>Verifica si has incluido correctamente el http_host en el array de servidores correspondiente</p>
                 <p><code>$_SERVER[\'HTTP_HOST\']</code> reportado <code>' . $_SERVER['HTTP_HOST'] . '</code></p>');

        // ojito al .htaccess para entender esto
        if(isset($_SERVER['REDIRECT_SELF_URL'])) {
            define('REQUEST_URI', $_SERVER['REDIRECT_SELF_URL']);
        }
        else {
            $part = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            define('REQUEST_URI', str_replace(DOC_URL, '', $part));
        }

        define('SELF_URL', DOC_URL.REQUEST_URI);
    }

    /**
     * Singleton
     * @return Config
     */
    public static function getConfig() {
        if(is_null(self::$me))
            self::$me = new Config();
        return self::$me;
    }

    // Ex: Config::get('valor')
    public static function get($key) {
        return self::$me->$key;
    }

    // codigo que funciona en todos los servidores
    private function everywhere() {
        // el idioma lo primero
        setlocale(LC_ALL, 'es_ES.UTF-8');

        // guardamos sesiones en base de datos (no implementado)
        $this->useDBSessions = true;

        $this->authDomain = $_SERVER['HTTP_HOST'];
        $this->useHashedPasswords = true;
        $this->authSalt = ''; // string que se añade a las contraseñas de usuario para reforzar la seguridad
        $this->cookieName = ''; // nombre del proyecto normalmente
        $this->sessionName = '';

        $this->tempStore = ROOT_PATH.'/public/temp/';

        $this->useTheme = true; // leer la clase Theme para mas info
        $this->theme = 'basic';

        $this->defaultController = ''; // controlador que se ejecuta en el indice de la aplicacion
    }

    private function production() {
        ini_set('display_errors', '0');

        define('DOC_URL', '');

        $this->dbDriver = '';
        $this->dbHost = '';
        $this->dbName = '';
        $this->dbUsername = '';
        $this->dbPassword = '';
        
        $this->ajax = true;

        $this->log = true;
        $this->logType = 'plaintext';
        $this->logStore = ROOT_PATH.'/App/Logs/';
        $this->logExceptions = true;
    }

    private function local() {
        ini_set('display_errors', '1');
        ini_set('error_reporting', E_ALL | E_STRICT);

        define('DOC_URL', '');

        $this->dbDriver = '';
        $this->dbHost = '';
        $this->dbName = '';
        $this->dbUsername = '';
        $this->dbPassword = '';

        $this->ajax = true;

        $this->log = true;
        $this->logType = 'plaintext';
        $this->logStore = ROOT_PATH.'/App/Logs/';
        $this->logExceptions = true;
    }

    private function test() {
        ini_set('display_errors', '1');
        ini_set('error_reporting', E_ALL | E_STRICT);

        define('DOC_URL', '');

        $this->dbDriver = '';
        $this->dbHost = '';
        $this->dbName = '';
        $this->dbUsername = '';
        $this->dbPassword = '';

        $this->ajax = true;

        $this->log = true;
        $this->logType = 'plaintext';
        $this->logStore = ROOT_PATH.'/App/Logs/';
        $this->logExceptions = true;
    }

    public function whereAmI() {
        if(in_array($_SERVER['HTTP_HOST'], $this->productionServers))
            return 'production';
        elseif(in_array($_SERVER['HTTP_HOST'], $this->testServers))
            return 'test';
        elseif(in_array($_SERVER['HTTP_HOST'], $this->localServers))
            return 'local';
        else
            return false;
    }
}
?>