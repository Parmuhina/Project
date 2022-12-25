<?php

namespace App\Repositories;

use App\Models\Collections\RequestCollection;

interface RequestRepository
{
    public function getRequest(string $convert, string $symbols): RequestCollection;
}