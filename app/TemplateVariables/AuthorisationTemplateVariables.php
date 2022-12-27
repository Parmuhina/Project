<?php

namespace App\TemplateVariables;

use App\Repositories\DatabaseConnection;

class AuthorisationTemplateVariables
{
    public function getName()
    {
        return 'user';
    }

    public function getValue()
    {
        if (!isset($_SESSION['id'])) {
            return [];
        }
        $queryBuilder = (new DatabaseConnection())->getConnection()->createQueryBuilder();

        $user = $queryBuilder
            ->select('*')
            ->from('user_schema.usersRegister')
            ->where('id=?')
            ->setParameter(0, $_SESSION['id'])
            ->fetchAssociative();
        return ['id' => $user['id'], 'username' => $user['username'], 'email' => $user['email']];
    }
}