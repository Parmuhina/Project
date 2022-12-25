<?php

namespace App\Validation;

class ValidationSell
{
    public function validationSell(array $post, float $sellCount, float $cash): void
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