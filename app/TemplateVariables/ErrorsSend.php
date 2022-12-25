<?php

namespace App\TemplateVariables;

class ErrorsSend
{
    public function getName(): string
    {
        return 'errorSend';
    }

    public function getValue(): array
    {
        return $_SESSION['errorSend'] ?? [];
    }
}