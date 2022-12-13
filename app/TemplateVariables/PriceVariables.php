<?php

namespace App\TemplateVariables;

use App\Services\SymbolService;

class PriceVariables
{
    public function getName()
    {
        return 'price';
    }

    public function getValue()
    {

        if (!isset($_SESSION['id'])) {
            return [];
        }

        return $_SESSION['price'];
    }
}

