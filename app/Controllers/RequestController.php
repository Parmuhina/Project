<?php

namespace App\Controllers;

use App\Services\RequestService;
use App\Views\Template;

class RequestController
{
    private RequestService $requestService;

    public function __construct(RequestService $requestService)
    {
        $this->requestService = $requestService;
    }

    public function index(): Template
    {
        $convert = $_GET['convert'] ?? 'EUR';
        $symbols = $_GET['symbols'] ?? 'BTC,ETH,USDT,USDC,BNB,XRP,ADA,BUSD';
        $requestService = $this->requestService->getRequestCollection($convert, $symbols);

        return new Template('mainView.twig', ['requests' => $requestService->getRequests()]);
    }
}
