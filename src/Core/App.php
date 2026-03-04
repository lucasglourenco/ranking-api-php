<?php

namespace App\Core;

use App\Http\JsonResponse;
use FastRoute\RouteCollector;
use App\Enums\HttpStatus;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use FastRoute\Dispatcher;

class App
{
    private Dispatcher $dispatcher;

    public function __construct(callable $routes)
    {
        $this->dispatcher = \FastRoute\simpleDispatcher(function (RouteCollector $r) use ($routes) {
            $routes($r);
        });
    }

    public function run(): void
    {
        $psr17Factory = new Psr17Factory();
        $creator = new ServerRequestCreator(
            $psr17Factory,
            $psr17Factory,
            $psr17Factory,
            $psr17Factory
        );

        $request = $creator->fromGlobals();

        $routeInfo = $this->dispatcher->dispatch(
            $request->getMethod(),
            $request->getUri()->getPath()
        );

        $response = match ($routeInfo[0]) {
            Dispatcher::NOT_FOUND =>
            JsonResponse::fail(HttpStatus::NOT_FOUND),

            Dispatcher::METHOD_NOT_ALLOWED =>
            JsonResponse::fail(HttpStatus::METHOD_NOT_ALLOWED),

            Dispatcher::FOUND =>
            $this->resolveHandler(
                $routeInfo[1],
                $request,
                $routeInfo[2]
            ),

            default =>
            JsonResponse::fail(HttpStatus::INTERNAL_SERVER_ERROR),
        };

        http_response_code($response->getStatusCode());

        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header("$name: $value", false);
            }
        }

        echo $response->getBody();
    }

    private function resolveHandler($handler, $request, $vars)
    {
        if (is_array($handler) && is_string($handler[0])) {

            $class = $handler[0];
            $method = $handler[1];

            $instance = new $class();

            return $instance->$method($request, $vars);
        }

        return $handler($request, $vars);
    }
}