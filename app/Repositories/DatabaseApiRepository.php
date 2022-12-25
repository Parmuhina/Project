<?php

namespace App\Repositories;

use App\Models\Collections\DatabaseUserCollection;
use App\Models\UserObject;

class DatabaseApiRepository implements DatabaseRepository
{
    public function getAllDatabase(): DatabaseUserCollection
    {
        $databaseUserCollection = new DatabaseUserCollection();

        $queryBuilder = (new DatabaseConnection())->getConnection()->createQueryBuilder();
        $queries = $queryBuilder
            ->select('*')
            ->from('user_schema.usersRegister')
            ->fetchAllAssociative();

        foreach ($queries as $query) {

            $databaseUserCollection->add(
                new UserObject(
                    $query['id'], $query['username'], $query['email'], $query['password']
                )
            );
        }
        return $databaseUserCollection;
    }
}
