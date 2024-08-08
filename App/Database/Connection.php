<?php

namespace App\Database;

use Exception;

class Connection
{
    private static $conn;
    private const DRIVER = 'mysql';
    private const DBNAME = 'telecontrol';
    private const HOST = 'mysql-telecontrol';
    private const USERNAME = 'root';
    private const PASSWORD = 'root';

    /**
     * Creates a connection with database
     *
     * @return Database
     */
    public static function getConnection(): Database
    {
        if (!self::$conn) {
            try {
                $classname = ucfirst(self::DRIVER);
                $relativePath = sprintf("\App\Database\%s\%s", $classname, $classname);
                self::$conn = new $relativePath(
                    self::DBNAME,
                    self::HOST,
                    self::USERNAME,
                    self::PASSWORD
                );

            } catch (Exception $e) {
                sprintf(
                    "Erro %s: %s", 
                    $e->getCode() ?: 400, 
                    $e->getMessage()
                );
            }
        }

        return self::$conn;
    }
}