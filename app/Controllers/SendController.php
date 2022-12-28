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

    public function showSendProfile(array $vars): Template
    {
        $_SESSION['sendSymbol'] = $vars['symbol'];
        return new Template(
            'sendProfile.twig', ['sendSymbol' => $vars['symbol']]
        );
    }

    public function send(): Redirect
    {
        unset($_SESSION['errorSend']);
        $this->validationSend->validationSend($_POST, $_SESSION['sendSymbol']);

        if (!empty ($_SESSION['errorSend'])) {
            return new Redirect("/send/{$_SESSION['sendSymbol']}");
        }

        $this->sendService->sendCoins($_POST, $_SESSION['sendSymbol']);
        unset($_SESSION['sendSymbol']);
        return new Redirect('/');
    }
}
