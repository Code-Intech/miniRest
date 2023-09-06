<?php

namespace MiniRest\Http\Response;
use MiniRest\Helpers\StatusCode\StatusCode;

class Response {
    public static function json($data, StatusCode $status = StatusCode::OK): void
    {
        header('Content-Type: application/json');
        http_response_code($status->value);
        echo json_encode($data);
    }

    public static function notFound(): void
    {
        self::json(['error' => 'Route not found'], StatusCode::NOT_FOUND);
    }
}