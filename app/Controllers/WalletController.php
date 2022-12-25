<?php

namespace App\Controllers;

use App\Views\Template;

class WalletController
{
    public function showWallet(): Template
    {
        return new Template(
            'wallet.twig'
        );
    }
}