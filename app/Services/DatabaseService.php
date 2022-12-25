<?php

namespace App\Services;

use App\Models\Collections\DatabaseUserCollection;
use App\Repositories\DatabaseApiRepository;
use App\Repositories\DatabaseRepository;

class DatabaseService
{
    private DatabaseRepository $databaseRepository;

    public function __construct(DatabaseRepository $databaseRepository)
    {
        $this->databaseRepository = $databaseRepository;
    }

    public function getDatabaseCollection(): DatabaseUserCollection
    {
        return $this->databaseRepository->getAllDatabase();
    }
}