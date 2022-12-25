<?php

namespace App\TemplateVariables;

class Errors
{
    public function getName(): string
    {
        return 'error';
    }

    public function getValue(): array
    {
        return $_SESSION['error'] ?? [];
    }
}