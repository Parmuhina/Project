<?php

namespace App\Models\Collections;

use App\Models\RequestObject;

class RequestCollection
{
    private array $requests;

    public function __construct(?array $requests = [])
    {
        foreach ($requests as $request) {
            $this->add($request);
        }
    }

    public function add(RequestObject $request): void
    {
        $this->requests[] = $request;
    }

    public function getRequests(): array
    {
        return $this->requests;
    }

}