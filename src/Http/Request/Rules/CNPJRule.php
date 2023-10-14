<?php

namespace MiniRest\Http\Request\Rules;

use MiniRest\Http\Request\RequestValidation\ValidationRule;

class CNPJRule implements ValidationRule
{

    public function passes($value, $params): bool
    {
        $cnpj = preg_replace('/[^0-9]/is', '', $value);

        if (strlen($cnpj) != 14) {
            return false;
        }

        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }

        for ($t = 12; $t < 14; $t++) {
            for ($d = 0, $m = ($t - 7), $i = 0; $i < $t; $i++) {
                $d += $cnpj[$i] * $m;
                $m = ($m == 2 ? 9 : --$m);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cnpj[$i] != $d) {
                return false;
            }
        }
        return true;
    }

    public function errorMessage($field, $params): string
    {
        return "O campo $field não é um CNPJ válido.";
    }
}