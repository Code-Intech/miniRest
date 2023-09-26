<?php

namespace MiniRest\Http\Response;
use MiniRest\Exceptions\InvalidContentTypeException;
use MiniRest\Exceptions\InvalidJsonResponseException;
use MiniRest\Helpers\StatusCode\StatusCode;

class Response {
    public static function json(mixed $data = null, int | StatusCode $status = StatusCode::OK): void
    {

        if (!$data) throw new InvalidJsonResponseException();

        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
    }

    public static function anyType(mixed $data = null, string $type, int | StatusCode $status = StatusCode::OK): void
    {

        if (!$data) throw new InvalidJsonResponseException();
        if (strlen($type) <= 0) throw new InvalidContentTypeException();

        header("Content-Type: $type");
        http_response_code($status);
        echo $data;
    }

    public static function notFound(): void
    {
        self::json(['error' => 'Route not found'], StatusCode::NOT_FOUND);
    }
}