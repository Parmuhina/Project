<?php

namespace App\TemplateVariables;

class ErrorsSell
{
    public function getName(): string
    {
        return 'sellError';
    }

    public function getValue(): array
    {
        return $_SESSION['sellError'] ?? [];
    }
}