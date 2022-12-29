<?php

namespace App\TemplateVariables;

use App\Services\SymbolService;

class BilanceVariables
{
    private SymbolService $symbolService;

    public function __construct(SymbolService $symbolService)
    {
        $this->symbolService = $symbolService;
    }

    public function getName()
    {
        return 'bilance';
    }

    public function getValue()
    {
        $result = 0;
        if (!isset($_SESSION['id'])) {
            return [];
        }
        $service = $this->symbolService->getSymbolCollection()->getSymbols();

        foreach ($service as $row) {
            $result += $row->getPaymount();
        }


        return ['bilance' => $result];
    }
}