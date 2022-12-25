<?php

namespace App\TemplateVariables;

use App\Services\SymbolService;

class TransactionsVariables
{
    private SymbolService $symbolService;

    public function __construct(SymbolService $symbolService)
    {
        $this->symbolService = $symbolService;
    }

    public function getName()
    {
        return 'transactions';
    }

    public function getValue()
    {
        $result = [];
        if (!isset($_SESSION['id'])) {
            return [];
        }

        $service = $this->symbolService->getSymbolCollection()->getSymbols();

        foreach ($service as $row) {
            $result[] = [
                'username' => $row->getUsername(),
                'cash' => $row->getCash(),
                'symbol' => $row->getSymbol(),
                'price' => $row->getPrice(),
                'value' => $row->getValue(),
                'paymount' => $row->getPaymount()
            ];
        }

        return $result;
    }
}
