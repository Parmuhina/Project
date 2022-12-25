<?php

namespace App\Services;

use App\Models\Collections\RequestCollection;
use App\Repositories\RequestRepository;

class RequestService
{
    private RequestRepository $requestRepository;

    public function __construct(RequestRepository $requestRepository)
    {
        $this->requestRepository = $requestRepository;
    }

    public function getRequestCollection(string $convert, string $symbols): RequestCollection
    {
        return $this->requestRepository->getRequest($convert, $symbols);
    }
}
