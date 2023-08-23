<?php

namespace MiniRest\Http\Request\Rules;

use MiniRest\Http\Request\RequestValidation\ValidationRule;

class EmailRule implements ValidationRule
{
    public function passes($value, $params): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function errorMessage($field, $params): string
    {
        return "O campo {$field} deve ser um endereço de e-mail válido.";
    }
}