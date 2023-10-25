<?php

namespace MiniRest\Http\Request\Rules;

use MiniRest\Http\Request\RequestValidation\ValidationRule;
use MiniRest\Storage\DiskStorage;
use MiniRest\Storage\Storage;

class MultipleFilesRule implements ValidationRule
{

    public function passes($value, $params = []): bool
    {
        if (!is_array($value)) {
            return false;
        }

        foreach ((new Storage())->reArrayFiles($value) as $file) {
            if (!$this->isValidFile($file, $params)) {
                return false;
            }
        }

        return true;
    }

    public function errorMessage($field, $params = []): string
    {
        return "O campo $field deve ser um arquivo do tipo " . implode(', ', $params);
    }

    protected function isValidFile($file, $params = []): bool
    {
        $allowedTypes = $params;
        $fileType = pathinfo($file['name'], PATHINFO_EXTENSION);

        return in_array($fileType, $allowedTypes);
    }
}