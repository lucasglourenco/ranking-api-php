<?php

namespace App\Http;

use App\Enums\HttpStatus;
use Nyholm\Psr7\Response;

class JsonResponse
{
    private static function build(HttpStatus $status, array $data = []): Response
    {
        $payload = [
            'success' => $status->value < 400,
            'status'  => $status->value,
            'message' => $status->message(),
            'data'    => $status->value < 400 ? $data : null,
        ];

        $response = new Response($status->value);
        $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_UNICODE));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function success(HttpStatus $status, array $data = []): Response
    {
        return self::build($status, $data);
    }

    public static function fail(HttpStatus $status, array $error = []): Response
    {
        return self::build($status, $error);
    }
}