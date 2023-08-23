<?php

namespace MiniRest\Http\Request\RequestValidation;

use Exception;

class ValidationRuleFactory
{
    /**
     * @throws Exception
     */
    public static function createRule($ruleName): ValidationRule
    {
        $className =  "MiniRest\\Http\Request\\RequestValidation\\Rules\\" .
            ucfirst($ruleName) . 'Rule';

        if (class_exists($className)) {
            return new $className();
        }

        throw new Exception("Regra de validação {$ruleName} não encontrada.");
    }
}