<?php

namespace App\Enums;

enum HttpStatus: int
{
    case OK = 200;
    case CREATED = 201;
    case BAD_REQUEST = 400;
    case UNAUTHORIZED = 401;
    case FORBIDDEN = 403;
    case NOT_FOUND = 404;
    case METHOD_NOT_ALLOWED = 405;
    case INTERNAL_SERVER_ERROR = 500;

    public function message(): string
    {
        return match ($this) {
            self::OK => 'OK',
            self::CREATED => 'Resource created successfully',
            self::BAD_REQUEST => 'Bad request',
            self::UNAUTHORIZED => 'Unauthorized',
            self::FORBIDDEN => 'Forbidden',
            self::NOT_FOUND => 'Resource not found',
            self::METHOD_NOT_ALLOWED => 'Method not allowed',
            self::INTERNAL_SERVER_ERROR => 'Internal server error',
        };
    }
}