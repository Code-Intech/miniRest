<?php

namespace MiniRest\Http\Request\RequestValidation;

use \Exception;

class RequestValidator
{
    protected $rules = [];

    /**
     * @throws \Exception
     */
    public function rules(array $rules)
    {
        foreach ($rules as $field => $rule) {
            $this->rules[$field] = [];

            foreach (explode('|', $rule) as $rawRule) {
                [$ruleName, $ruleParams] = $this->parseRule($rawRule);
                $validationRule = ValidationRuleFactory::createRule($ruleName);

                $this->rules[$field][] = [
                    'rule' => $validationRule,
                    'params' => $ruleParams,
                ];
            }
        }

        return $this;
    }

    private function parseRule($rawRule): array
    {
        $parts = explode(':', $rawRule);
        $ruleName = $parts[0];
        $ruleParams = isset($parts[1]) ? explode(',', $parts[1]) : [];

        return [$ruleName, $ruleParams];
    }

    public function validate($data)
    {
        $errorMessages = [];
        foreach ($this->rules as $field => $rules) {

            if (!isset($data[$field])) throw new Exception("Parametro \"$field\" não encontrada.");

            foreach ($rules as $rule) {
                if (!$rule['rule']->passes($data[$field], $rule['params'])) {
                    $errorMessage = $rule['rule']->errorMessage($field, $rule['params']);
                    $errorMessages[$field][] = $errorMessage;
                }
            }
        }
        return $errorMessages;
    }
}