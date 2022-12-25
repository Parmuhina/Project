<?php

namespace App\Repositories;

use App\Models\Collections\SymbolCollection;

interface SymbolRepository
{
    public function getRequest(): SymbolCollection;

    public function add(array $row);

    public function update(float $cash, int $id);
}