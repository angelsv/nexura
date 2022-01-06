<?php

namespace App\Models;

class Connection{
    
    private $connection = null;
    private static $instance = null;

    private function __construct(){
        $dsn = 'mysql:dbname='.$_ENV["DB_NAME"].';host='.$_ENV["DB_HOST"];
        try {

            $options = array(
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_ORACLE_NULLS => \PDO::NULL_EMPTY_STRING,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
            );
            $this->connection = new \PDO($dsn, $_ENV["DB_USER"], $_ENV["DB_PASSWORD"], $options);

        } catch (\PDOException $e) {
            throw new \PDOException( $e->getMessage( ) , $e->getCode( ) );
            // die("Database connection failed: " . $e->getMessage());
            exit;
        }
    }

    static function getInstance(){
        if(is_null(self::$instance)){
            self::$instance = new Connection;
        }
        return self::$instance;
    }

    function getConnection(){
        return self::getInstance()->connection;
    }
}