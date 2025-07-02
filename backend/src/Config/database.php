<?php
namespace Src\Config;

use PDO;

class Database {
    private static ?PDO $instance = null;

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $host = Config::get('DB_HOST');
            $port = Config::get('DB_PORT');
            $db   = Config::get('DB_NAME');
            $user = Config::get('DB_USER');
            $pass = Config::get('DB_PASS');
            $dsn  = "pgsql:host=localhost;port=5432;dbname=organizr;user=postgres;password=Nana2002#";

            self::$instance = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        }
        return self::$instance;
    }
}