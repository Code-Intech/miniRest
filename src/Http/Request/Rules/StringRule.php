<?php

namespace MiniRest\Http\Request\Rules;

use MiniRest\Http\Request\RequestValidation\ValidationRule;

class StringRule implements ValidationRule
{

    public function passes($value, $params): bool
    {
        return is_string($value) !== false;
    }

    public function errorMessage($field, $params): string
    {
        return "O campo {$field} deve ser uma String válida.";
    }
}