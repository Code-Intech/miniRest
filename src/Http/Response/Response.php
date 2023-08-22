<?php

namespace MiniRest\Http\Response;
class Response {
    public static function json($data, $status = 200): void
    {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
    }
}