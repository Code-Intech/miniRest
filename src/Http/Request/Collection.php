<?php

namespace MiniRest\Http\Request;

class Collection
{
    protected $data = [];

    public function __construct($data) {
        $this->data = $data;
    }

    public function get($key = null, $default = null) {

        if ($key === null) throw new \Exception('$key must be a value');

        return $this->data[$key] ?? $default;
    }
}