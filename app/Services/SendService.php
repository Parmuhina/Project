<?php

namespace App\Services;

use App\Repositories\DatabaseRepository;
use App\Repositories\RequestRepository;
use App\Repositories\SymbolRepository;

class SendService
{
    private SymbolRepository $symbolRepository;
    private RequestRepository $requestRepository;
    private DatabaseRepository $databaseRepository;

    public function __construct(
        SymbolRepository $symbolRepository,
        RequestRepository $requestRepository,
        DatabaseRepository $databaseRepository
    )
    {
        $this->symbolRepository = $symbolRepository;
        $this->requestRepository = $requestRepository;
        $this->databaseRepository = $databaseRepository;
    }

    public function sendCoins(array $post): void
    {
        $userId = 0;
        $price = $this->requestRepository->getRequest("EUR", $post['symbolSend'])->getRequests()[0]->getPrice();
        foreach ($this->databaseRepository->getAllDatabase()->getUsers() as $user) {
            if ($user->getUsername() === $post['username']) {
                $userId = $user->getId();
            }
        }

        $this->symbolRepository->add(
            [
                $post['symbolSend'],
                $price,
                $post['numberSend'],
                0,
                $userId
            ]
        );

        $this->symbolRepository->add(
            [
                $post['symbolSend'],
                $price,
                $post['numberSend'],
                0,
                $_SESSION['id']
            ]
        );
    }

}