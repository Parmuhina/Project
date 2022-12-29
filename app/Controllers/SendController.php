<?php

namespace App\Controllers;

use App\Services\DatabaseService;
use App\Services\SendService;
use App\TemplateVariables\WalletVariables;
use App\Views\Redirect;
use App\Views\Template;

class SendController
{
    private SendService $sendService;
    private WalletVariables $variables;
    private DatabaseService $database;

    public function __construct(
        SendService $sendService,
        WalletVariables $variables,
        DatabaseService $database)
    {
        $this->sendService = $sendService;
        $this->variables = $variables;
        $this->database = $database;
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
        $this->validationSend($_POST, $_SESSION['sendSymbol']);

        if (!empty ($_SESSION['errorSend'])) {
            return new Redirect("/send/{$_SESSION['sendSymbol']}");
        }

        $this->sendService->sendCoins($_SESSION['id'],$_POST, $_SESSION['sendSymbol']);
        unset($_SESSION['sendSymbol']);
        return new Redirect('/');
    }

    private function validationSend(array $post, string $symbolSend)
    {
        unset($_SESSION['numberSend']);
        unset($_SESSION['password']);
        unset($_SESSION['username']);

        $wallet = $this->variables->getValue();

        foreach ($wallet as $coin) {
            if ($coin['symbol'] === $symbolSend && $coin['count'] < $post['numberSend']) {
                $_SESSION['errorSend']['numberSend'] = 'You send more than you have got.';
            }
        }

        if ((floatval($post['numberSend'])) <= 0) {
            $_SESSION['errorSend']['numberSend'] = 'Coin number not more than zero.';
        }

        if (
            strlen($post['numberSend']) === 0 ||
            strlen($post['password']) === 0 ||
            strlen($post['username']) === 0
        ) {
            $_SESSION['errorSend']['password'] = 'Empty fields to be filled.';
        }

        $userCollection = $this->database->getDatabaseCollection()->getUsers();
        $usernames = [];
        foreach ($userCollection as $user) {
            $usernames[] = $user->getUsername();

            if ($user->getId() === $_SESSION['id'] && $user->getPassword() != $post['password']) {
                $_SESSION['errorSend']['password'] = 'Password isn`t correct.';
            }
        }

        if (in_array($post['username'], $usernames) === false) {
            $_SESSION['errorSend']['username'] = 'Such username doesn`t exist.';
        }
    }
}
