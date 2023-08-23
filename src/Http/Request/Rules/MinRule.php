<?php

namespace MiniRest\Http\Request\Rules;

use MiniRest\Http\Request\RequestValidation\ValidationRule;

class MinRule implements ValidationRule
{

    public function passes($value, $params): bool
    {
        $minLength = intval($params[0] ?? 0);
        return strlen($value) >= $minLength;
    }

    public function errorMessage($field, $params): string
    {
        $minLength = intval($params[0] ?? 0);
        return "O campo {$field} deve ter pelo menos {$minLength} caracteres.";
    }
}