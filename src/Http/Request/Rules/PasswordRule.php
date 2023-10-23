<?php

namespace MiniRest\Http\Request\Rules;

use MiniRest\Http\Request\RequestValidation\ValidationRule;

class PasswordRule implements ValidationRule
{

    public function passes($value, $params): bool
    {
        $hasMinLength = strlen($value) >= (int)explode('=',$params[0])[1];
        $hasAlphaNum = preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[$*&@#])/', $value);
        $hasNumber = preg_match('/\d/', $value);
        $hasSpecialChar = preg_match('/[!@#$%^&*(),.?":{}|<>]/', $value);

        return $hasMinLength && $hasAlphaNum && $hasNumber && $hasSpecialChar;
    }

    public function errorMessage($field, $params): string
    {
        return "O campo {$field} deve atender aos requisitos mínimos de senha.";
    }
}