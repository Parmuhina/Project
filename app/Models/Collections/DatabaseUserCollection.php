<?php

namespace App\Models\Collections;

use App\Models\UserObject;

class DatabaseUserCollection
{
    private array $users;

    public function __construct(?array $users = [])
    {
        foreach ($users as $user) {
            $this->add($user);
        }
    }

    public function add(UserObject $user): void
    {
        $this->users[] = $user;
    }

    public function getUsers(): array
    {
        return $this->users;
    }
}