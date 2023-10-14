<?php

namespace MiniRest\Http\Request\Rules;

use MiniRest\Http\Request\RequestValidation\ValidationRule;

class ArrayRule implements ValidationRule
{

    public function passes($value, $params): bool
    {
        if (!is_array($value) && !count($value) > 0) {
            return false;
        }

        if (isset($params[0])) {
            $expectedType = $params[0];
            foreach ($value as $item) {
                if (!call_user_func('is_' . $expectedType, $item)) {
                    return false;
                }
            }
        }
        return true;
    }

    public function errorMessage($field, $params): string
    {
        return "O campo $field não é um Array válido.";
    }
}