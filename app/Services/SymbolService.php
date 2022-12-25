<?php

namespace App\Services;

use App\Models\Collections\SymbolCollection;
use App\Repositories\RequestRepository;
use App\Repositories\SymbolRepository;

class SymbolService
{
    private SymbolRepository $symbolRepository;
    private RequestRepository $requestRepository;

    public function __construct(SymbolRepository $symbolRepository, RequestRepository $requestRepository)
    {
        $this->symbolRepository = $symbolRepository;
        $this->requestRepository = $requestRepository;
    }

    public function getSymbolCollection(): SymbolCollection
    {
        return $this->symbolRepository->getRequest();
    }

    public function insertBuySell(string $valueBuy, string $valueSell, string $symbol, int $userId)
    {
        $price = $this->requestRepository->getRequest("EUR", $symbol)->getRequests()[0]->getPrice();
        $_SESSION['price']=$price;

        if (strlen($valueBuy) != 0) {
            $this->symbolRepository->add([$symbol, $price, (floatval($valueBuy)), (-1) * (floatval($valueBuy)) * $price, $userId]);

            $this->symbolRepository->update(
                (int)$valueBuy * $price * (-1),
                $userId
            );
        }

        if (strlen($valueSell) != 0) {
            $count = 0;
            $averagePrice = 0;
            $transactions = $this->symbolRepository->getRequest()->getSymbols();

            foreach ($transactions as $transaction) {
                if ($transaction->getPaymount() < 0) {
                    $count += $transaction->getValue();
                    $averagePrice += $transaction->getPaymount();
                } else {
                    $count -= $transaction->getValue();
                    $averagePrice -= $transaction->getPaymount();
                }
            }

            $averagePrice = $averagePrice / $count;

            $this->symbolRepository->update(
                $valueSell * $price + ($price - $averagePrice) * $valueSell,
                $userId
            );

            $this->symbolRepository->add([$symbol, $price, (floatval($valueSell)), (floatval($valueSell)) * $price, $userId]);
        }
    }

    public function slot(string $valueSell, string $symbol, int $userId)
    {
        $price = $this->requestRepository->getRequest("EUR", $symbol)->getRequests()[0]->getPrice();

        if (strlen($valueSell) != 0) {
            $this->symbolRepository->update(
                $valueSell * $price,
                $userId
            );

            $this->symbolRepository->add([$symbol, $price, (floatval($valueSell)), (floatval($valueSell)) * $price, $userId]);
        }
    }

}