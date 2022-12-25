<?php

namespace App\Repositories;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class DatabaseConnection
{
    private Connection $connection;
    private static ?DatabaseApiRepository $database = null;

    public function __construct()
    {
        $connectionParams = [
            'dbname' => $_ENV['DBNAME'],
            'user' => $_ENV['USER'],
            'password' => $_ENV['PASSWORD'],
            'host' => $_ENV['HOST'],
            'driver' => $_ENV['DRIVER'],
        ];
        $this->connection = DriverManager::getConnection($connectionParams);
    }

    public static function getDatabase(): DatabaseApiRepository
    {
        if (self::$database === null) {
            self::$database = new DatabaseApiRepository;
        }
        return self::$database;
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }
}