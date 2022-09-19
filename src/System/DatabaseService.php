<?php
namespace Cosmic\System;

use Exception;
use PDO;
use \Pecee\Pixie\Connection;

class DatabaseService {

    public static $instance;
  
    public static function connection(){
      
        $dotenv = new \Symfony\Component\Dotenv\Dotenv(true);
        $dotenv->loadEnv(dirname(__DIR__).'/../.env');
      
        $config = [
            'driver'    => $_ENV['DB_DRIVER'], 
            'host'      => $_ENV['DB_HOST'],
            'database'  => $_ENV['DB_NAME'],
            'username'  => $_ENV['DB_USER'],
            'password'  => $_ENV['DB_PASS'],
            'charset'   => $_ENV['DB_CHARSET'], 
            'collation' => $_ENV['DB_COLLATION'],
            'options'   => [
                PDO::ATTR_TIMEOUT => 5,
                PDO::ATTR_EMULATE_PREPARES => false,
            ],
        ];
      
        if(!isset(self::$instance)){
            self::$instance = (new \Pecee\Pixie\Connection('mysql', $config))->getQueryBuilder();
        }
        return self::$instance;
    }
}
