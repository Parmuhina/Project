<?php

namespace App\Controllers;

use App\Services\SendService;
use App\Validation\ValidationSend;
use App\Views\Redirect;
use App\Views\Template;

class SendController
{
    private ValidationSend $validationSend;
    private SendService $sendService;

    public function __construct(ValidationSend $validationSend, SendService $sendService)
    {
        $this->validationSend = $validationSend;
        $this->sendService = $sendService;
    }

    public function showSend(): Template
    {
        return new Template(
            'send.twig'
        );
    }

    public function send(): Redirect
    {
        $this->validationSend->validationSend($_POST);

        if (!empty ($_SESSION['errorSend'])) {
            return new Redirect('/send');
        }

        $this->sendService->sendCoins($_POST);
        return new Redirect('/');
    }
}
