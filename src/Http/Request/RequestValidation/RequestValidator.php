<?php

namespace MiniRest\Http\Request\RequestValidation;

use \Exception;
use MiniRest\Helpers\StatusCode\StatusCode;
use MiniRest\Http\Request\Request;
use MiniRest\Http\Response\Response;

class RequestValidator
{
    protected $rules = [];
    protected $errorMessages = [];

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

    public function validate($data = null) : bool
    {

        if (!$data) $data = (new Request())->all()->get('json');

        $this->errorMessages = [];
        foreach ($this->rules as $field => $rules) {

            if (!isset($data[$field])) {
                $this->errorMessages[$field][] = "Parametro \"$field\" não encontrada.";
                continue;
            }

            foreach ($rules as $rule) {
                if (!$rule['rule']->passes($data[$field], $rule['params'])) {
                    $errorMessage = $rule['rule']->errorMessage($field, $rule['params']);
                    $this->errorMessages[$field][] = $errorMessage;
                }
            }
        }

        if (count($this->errorMessages) > 0) return false;

        return true;
    }

    public function errors(): void
    {
        if (!$this->validate() && count($this->errorMessages) > 0) {
            $erro = [];
            foreach ($this->errorMessages as $item){
                $erro[] = $item[0];
            }
            Response::json(['errors' => $erro], StatusCode::REQUEST_ERROR);
            return;
        }
    }
}