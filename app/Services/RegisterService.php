<?php

namespace App\Services;

use App\Repositories\DatabaseConnection;
use App\Models\UserObject;
use App\Repositories\DatabaseRepository;

class RegisterService
{
    private DatabaseRepository $databaseRepository;

    public function __construct(DatabaseRepository $databaseRepository)
    {
        $this->databaseRepository = $databaseRepository;
    }

    public function execute(UserObject $request): void
    {
        (new DatabaseConnection)->getConnection()->insert(
            "new_schema.usersRegister",
            [
                "username" => $request->getUsername(),
                "email" => $request->getEmail(),
                "password" => $request->getPassword()
            ]
        );
    }

    public function findID(UserObject $request): ?int
    {
        $database = $this->databaseRepository->getAllDatabase()->getUsers();
        foreach ($database as $row) {
            if ($row->getUsername() === $request->getUsername()) {
                return $row->getId();
            }
        }
        return null;
    }

    public function findUsername(): array
    {
        $database = $this->databaseRepository->getAllDatabase()->getUsers();
        foreach ($database as $row) {
            if ($row->getId() === $_SESSION['id']) {
                return $row->getUsername();
            }
        }
        return [];
    }
}