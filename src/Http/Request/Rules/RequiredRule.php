<?php

namespace MiniRest\Http\Request\Rules;

use MiniRest\Http\Request\RequestValidation\ValidationRule;

class RequiredRule implements ValidationRule
{
    public function passes($value, $params): bool
    {
        return isset($value) && $value !== '';
    }

    public function errorMessage($field, $params): string
    {
        return "O campo {$field} é obrigatório.";
    }
}