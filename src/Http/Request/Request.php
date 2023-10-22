<?php

namespace MiniRest\Http\Request;

use MiniRest\Http\Request\RequestValidation\RequestValidator;

class Request extends RequestValidator
{

    public function get($key, $default = null) {

        if (isset($_GET[$key])) {
            return $_GET[$key];
        }

        return $default;
    }

    public function post($key, $default = null) {
        if (isset($_POST[$key])) {
            return $_POST[$key];
        }

        return $default;
    }

    public function files($key, $default = null) {
        if (isset($_FILES[$key])) {
            return $_FILES[$key];
        }

        return $default;
    }

    public function json($key, $default = null) {
        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData, true);

        if (isset($data[$key])) {
            return $data[$key];
        }

        return $default;
    }

    public function all(): Collection
    {
        return new Collection([
            'get' => $_GET,
            'post' => $_POST,
            'files' => $_FILES,
            'json' => $this->getJsonData(),
        ]);
    }

    protected function getJsonData() {
        $jsonData = file_get_contents('php://input');
        return json_decode($jsonData, true);
    }

    public function headers(string $headerName)
    {
        $headers = $this->getAllHeaders();

        if (isset($headers[$headerName])) {
            return $headers[$headerName];
        }

        return null;
    }

    private function getAllHeaders(): array
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) === 'HTTP_') {
                $headerName = str_replace('_', ' ', substr($key, 5));
                $headerName = ucwords(strtolower($headerName));
                $headerName = str_replace(' ', '-', $headerName);
                $headers[$headerName] = $value;
            }
        }

        return $headers;
    }

    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}