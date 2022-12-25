<?php

namespace App\Models\Collections;

use App\Models\SymbolObject;

class SymbolCollection
{
    private array $symbols;

    public function __construct(?array $symbols = [])
    {
        foreach ($symbols as $symbol) {
            $this->add($symbol);
        }
    }

    public function add(SymbolObject $symbol): void
    {
        $this->symbols[] = $symbol;
    }

    public function getSymbols(): array
    {
        return $this->symbols;
    }
}