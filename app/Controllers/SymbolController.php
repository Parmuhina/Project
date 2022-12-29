<?php

namespace App\Controllers;

use App\Repositories\RequestRepository;
use App\Services\SymbolService;
use App\TemplateVariables\WalletVariables;
use App\Views\Redirect;
use App\Views\Template;

class SymbolController
{
    private SymbolService $symbolService;
    private WalletVariables $wallet;
    private RequestRepository $requestRepository;

    public function __construct(
        SymbolService $symbolService,
        WalletVariables $wallet,
        RequestRepository $requestRepository
    )
    {
        $this->symbolService = $symbolService;
        $this->wallet = $wallet;
        $this->requestRepository = $requestRepository;
    }

    public function showSymbol(array $vars): Template
    {
        $_SESSION['buySellSymbol'] = $vars['symbol'];
        $price = $this->requestRepository->getRequest("EUR", $vars['symbol'])->getRequests()[0]->getPrice();
        $_SESSION['price'] = $price;

        return new Template(
            'symbol.twig', ['buySellSymbol' => $vars['symbol'], 'price' => $price]
        );
    }

    public function buyCurrency(): Redirect
    {
        $symbolService = $this->symbolService;
        $cash = 0;
        foreach ($symbolService->getSymbolCollection()->getSymbols() as $user) {
            if ($user->getId() === $_SESSION['id']) {
                $cash = $user->getCash();
            }
        }
        $sellCount = 0;
        foreach ($this->wallet->getValue() as $row) {
            if ($row['symbol'] === $_SESSION['buySellSymbol']) {
                $sellCount = $row['count'];
            }
        }
        $this->validationSell($_POST, $sellCount, $cash);

        if (!empty ($_SESSION['sellError'])) {
            return new Redirect("/symbol/{$_SESSION["buySellSymbol"]}");
        }

        $symbolService->insertBuySell(
            $_POST['numberBuy'],
            $_POST['numberSell'],
            $_SESSION['buySellSymbol'],
            $_SESSION['id']
        );

        unset ($_SESSION['buySellSymbol']);
        return new Redirect('/');
    }

    public function slot(): Redirect
    {
        unset($_SESSION['numberSell']);
        unset($_SESSION['numberBuy']);
        unset($_SESSION['number']);
        unset($_SESSION['numbers']);

        $this->symbolService->slot(
            $_POST['slot'],
            $_SESSION['buySellSymbol'],
            $_SESSION['id']
        );

        unset ($_SESSION['buySellSymbol']);
        return new Redirect('/');
    }

    private function validationSell(array $post, float $sellCount, float $cash): void
    {
        unset($_SESSION['sellError']);

        if (empty ($_SESSION['id'])) {
            $_SESSION['sellError']['number'] = 'You need to authorize.';
        }

        if ((floatval($post['numberBuy'])) < 0) {
            $_SESSION['sellError']['numberBuy'] = 'Number of coins need to be more than 0';
        }

        if ((floatval($post['numberBuy']) * $_SESSION['price']) > $cash) {
            $_SESSION['sellError']['numberBuyCost'] = 'You don`t have currency for pay bill';
        }

        if ((floatval($post['numberSell'])) > $sellCount && strlen($post['numberSell']) != 0) {
            $_SESSION['sellError']['numberSell'] = 'Number of sell coins need to be less or equal that you have got';
        }

        if (strlen($post['numberBuy']) === 0 && strlen($post['numberSell']) === 0) {
            $_SESSION['sellError']['numbers'] = 'One of two number of coins need to be not empty';
        }
    }
}