<?php

namespace MiniRest\Http\Request\Rules;

use MiniRest\Http\Request\RequestValidation\ValidationRule;
use MiniRest\Models\User;

class UniqueRule implements ValidationRule
{

    public function passes($value, $params): bool
    {
        return User::where('Email', '=', $value)
            ->first() === null;
    }

    public function errorMessage($field, $params): string
    {
        return "O email não pode ser duplicado, este email já foi cadastrado no sistema.";
    }
}