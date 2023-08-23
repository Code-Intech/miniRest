<?php

namespace MiniRest\Http\Request;

class RequestValidator
{
    protected $rules = [];

    public function rules(array $rules): void
    {
        $this->rules = $rules;
    }

    public function validate(array $data): true|array
    {
        $errors = [];

        foreach ($this->rules as $field => $ruleString) {
            $rules = explode('|', $ruleString);

            foreach ($rules as $rule) {
                [$ruleName, $params] = $this->parseRule($rule);

                $isValid = $this->validateRule($ruleName, $data[$field] ?? null, $params);

                if (!$isValid) {
                    $errors[$field][] = $this->getErrorMessage($field, $ruleName, $params);
                }
            }
        }

        return empty($errors) ? true : $errors;
    }

    protected function parseRule($rule): array
    {
        $params = [];

        if (strpos($rule, ':') !== false) {
            [$ruleName, $paramsString] = explode(':', $rule, 2);
            $params = explode(',', $paramsString);
        } else {
            $ruleName = $rule;
        }

        return [$ruleName, $params];
    }

    protected function validateRule($ruleName, $value, $params): bool
    {
        switch ($ruleName) {
            case 'required':
                return isset($value) && $value !== '';

            case 'password':
                $hasMinLength = strlen($value) >= intval($params[0] ?? 0);
                $hasAlphaNum = preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[$*&@#])/', $value);
                $hasNumber = preg_match('/\d/', $value);
                $hasSpecialChar = preg_match('/[!@#$%^&*(),.?":{}|<>]/', $value);

                return $hasMinLength && $hasAlphaNum && $hasNumber && $hasSpecialChar;

            case 'max':
                $maxLength = intval($params[0] ?? 0);
                return strlen($value) <= $maxLength;

            case 'min':
                $minLength = intval($params[0] ?? 0);
                return strlen($value) >= $minLength;

            case 'email':
                return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;

            case 'string':
                return is_string($value) !== false;

//            case 'unique':
//                [$table, $column] = $params;
//                return !$this->isValueExistsInDatabase($table, $column, $value);
        }
        return true;
    }

    protected function getErrorMessage($field, $ruleName, $params)
    {
        switch ($ruleName) {
            case 'required':
                return "O campo {$field} é obrigatório.";

            case 'email':
                return "O campo {$field} deve ser um endereço de e-mail válido.";

            case 'password':
                return "O campo {$field} deve atender aos requisitos mínimos de senha.";

            case 'string':
                return "O campo {$field} deve ser um uma string válida.";

            case 'max':
                $maxLength = intval($params[0] ?? 0);
                return "O campo {$field} não deve exceder {$maxLength} caracteres.";

            case 'min':
                $minLength = intval($params[0] ?? 0);
                return "O campo {$field} não deve ter no minimo {$minLength} caracteres.";

            case 'unique':
                return "O valor informado para o campo {$field} já está em uso.";
        }
    }
}