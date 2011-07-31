<?php
/**
 * This file is part of Mierdium Framework.
 *
 * (c) Victor Calzón <victor@victorcalzon.com>
 */

namespace Core\Database;

/**
 * Esta clase hace de puente entre la clase Mongo y los dataobjects,
 * (user, post, etc).
 * Al ser una clase singleton, el objeto Mongo se guarda en la variable
 * $me y puede ser reutilizado.
 * 
 * @package Mierdium Framework
 * @subpackage Core
 * @category Database
 * @author Victor Calzón <victor@victorcalzon.com>
 */
class MongoDB
{
    /**
     * Variable estatica para el singleton
     * @var MongoDB 
     */
    private static $me;

    /**
     * Variable que guarda el objeto Mongo
     * @var Mongo
     */
    public $db;

    /**
     * Nombre del host (example.com)
     * @var string
     * @see Config::dbHost
     */
    private $host;

    /**
     * Usuario de la base de datos
     * @var string
     * @see Config::dbUsername
     */
    private $username;

    /**
     * Password del usuario de la base de datos
     * @var string
     * @see Config::dbPassword
     */
    private $password;
    
    /**
     * El constructor intenta crear una clase Mongo con los datos de la
     * clase config, y guardarla en la variable db. En caso de error tira una
     * excepcion Fail.
     */
    public function __construct() {
        $this->host = \App\Config::get('dbHost');
        $this->username = \App\Config::get('dbUsername');
        $this->password = \App\Config::get('dbPassword');

        try {
            $this->db = new \Mongo('mongodb://'.$this->host.':27017/'.\App\Config::get('dbName'));
        }
        catch (\MongoConnectionException $e) {
            throw new \Core\Libs\Fail('Unable to connect', 80);
        }
    }

    /**
     * Devuelve un objeto MongoCollection a partir del nombre de la coleccion
     * a usar.
     * @param string $collection_name
     * @return MongoCollection
     */
    public function collection($collection_name) {
        return $this->db->selectCollection(\App\Config::get('dbName'), $collection_name);
    }

    /**
     * Funcion singleton
     * @return MongoDB
     */
    public static function getMongoDB() {
        if(is_null(self::$me))
            self::$me = new \Core\Database\MongoDB();
        return self::$me;
    }
}
?>
