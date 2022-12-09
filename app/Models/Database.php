<?php

namespace App\Models;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class Database
{
    private Connection $connection;
    private static ?Database $database = null;

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

    public static function getDatabase(): Database
    {
        if (self::$database === null) {
            self::$database = new Database;
        }
        return self::$database;
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }

    public function getAllDatabase(): array
    {
        $queryBuilder = self::getDatabase()->getConnection()->createQueryBuilder();

        return $queryBuilder
            ->select('*')
            ->from('user')
            ->fetchAllAssociative();
    }
}
