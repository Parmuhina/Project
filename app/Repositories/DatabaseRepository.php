<?php

namespace App\Repositories;

use App\Models\Collections\DatabaseUserCollection;

interface DatabaseRepository
{
    public function getAllDatabase(): DatabaseUserCollection;
}