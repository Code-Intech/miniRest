<?php

namespace MiniRest\Http\Request\RequestValidation;

use Exception;
use MiniRest\Exceptions\RuleNotFound;

class ValidationRuleFactory
{
    /**
     * @throws Exception
     */
    public static function createRule($ruleName): ValidationRule
    {
        $className =  "MiniRest\\Http\Request\\Rules\\" .
            ucfirst($ruleName) . 'Rule';

        if (class_exists($className)) {
            return new $className();
        }

        throw new RuleNotFound("Regra de validação {$ruleName} não encontrada.");
    }
}