<?php

namespace MiniRest\Helpers\StatusCode;

enum StatusCode: int
{
    case OK = 200;
    case CREATED = 201;
    case NOT_FOUND = 404;
    case ACCESS_NOT_ALLOWED = 401;
    case REQUEST_ERROR = 400;
}