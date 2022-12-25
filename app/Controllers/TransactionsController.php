<?php

namespace App\Controllers;

use App\Views\Template;

class TransactionsController
{
    public function showTransactions(): Template
    {
        return new Template(
            'transactions.twig'
        );
    }
}