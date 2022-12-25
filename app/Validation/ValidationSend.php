<?php

namespace App\Validation;

use App\Services\DatabaseService;
use App\TemplateVariables\WalletVariables;

class ValidationSend
{
    private WalletVariables $variables;
    private DatabaseService $database;

    public function __construct(WalletVariables $variables, DatabaseService $database)
    {
        $this->variables = $variables;
        $this->database = $database;
    }

    public function validationSend(array $post)
    {
        unset($_SESSION['errorSend']);


        $wallet = $this->variables->getValue();
        $symbols = [];
        foreach ($wallet as $coin) {
            $symbols[] = $coin['symbol'];

            if ($coin['symbol'] === $post['symbolSend'] && $coin['count'] < $post['numberSend']) {
                $_SESSION['errorSend']['numberSend'] = 'You send more than you have got.';
            }
        }

        if ((floatval($post['numberSend'])) <= 0) {
            $_SESSION['errorSend']['numberSend'] = 'Coin number not more than zero.';
        }

        if (in_array($post['symbolSend'], $symbols) === false) {
            $_SESSION['errorSend']['symbolSendCoins'] = 'Crypto is not in your wallet.';
        }

        if (
            strlen($post['symbolSend']) === 0 ||
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