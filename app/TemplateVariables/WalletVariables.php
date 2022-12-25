<?php

namespace App\TemplateVariables;

use App\Services\RequestService;
use App\Services\SymbolService;

class WalletVariables
{
    private SymbolService $symbolService;
    private RequestService $requestService;

    public function __construct(SymbolService $symbolService, RequestService $requestService)
    {
        $this->symbolService = $symbolService;
        $this->requestService = $requestService;
    }

    public function getName()
    {
        return 'wallet';
    }

    public function getValue()
    {
        if (!isset($_SESSION['id'])) {
            return [];
        }

        $service = $this->symbolService->getSymbolCollection()->getSymbols();
        $path = [];
        foreach ($service as $row) {
            if (in_array($row->getSymbol(), $path) === false) {
                $path[] = $row->getSymbol();
            }
        }
        $result = [];

        foreach ($path as $symbol) {
            $count = 0;
            foreach ($service as $user) {
                if ($user->getSymbol() === $symbol) {
                    if ($user->getPaymount() < 0) {
                        $count += $user->getValue();
                    } else {
                        $count -= $user->getValue();
                    }
                }
            }
            $logo = '';
            $request=$this->requestService->getRequestCollection("EUR", $symbol);
            foreach ($request->getRequests() as $row) {
                if($symbol===$row->getSymbol()){
                    $logo = $row->getLogo();
                }
            }
            $result[] = ['symbol' => $symbol, 'count' => $count, 'logo' => $logo];
        }
        return $result;
    }
}
