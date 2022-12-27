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

    public function validationSend(array $post, string $symbolSend)
    {
        unset($_SESSION['numberSend']);
        unset($_SESSION['password']);
        unset($_SESSION['username']);

        $wallet = $this->variables->getValue();
        $symbols = [];
        foreach ($wallet as $coin) {
            $symbols[] = $coin['symbol'];

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