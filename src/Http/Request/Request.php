<?php

namespace MiniRest\Http\Request;

class Request {

    public function get($key, $default = null) {

        if (isset($_GET[$key])) {
            return $_GET[$key];
        }

        return $default;
    }

    /**
     * @deprecated
     * @param $key
     * @param $default
     * @return mixed|null
     */
    public function post($key, $default = null) {
        if (isset($_POST[$key])) {
            return $_POST[$key];
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
            'json' => $this->getJsonData(),
        ]);
    }

    protected function getJsonData() {
        $jsonData = file_get_contents('php://input');
        return json_decode($jsonData, true);
    }
}