<?php

namespace utils;

class Router
{
    private static $routes = [];

    // Register a GET route
    public static function get($uri, $callback)
    {
        self::$routes['GET'][$uri] = $callback;
    }

    // Register a POST route
    public static function post($uri, $callback)
    {
        self::$routes['POST'][$uri] = $callback;
    }

    // Register a DELETE route
    public static function delete($uri, $callback)
    {
        self::$routes['DELETE'][$uri] = $callback;
    }

    // Register a PUT route
    public static function put($uri, $callback)
    {
        self::$routes['PUT'][$uri] = $callback;
    }

    // Dispatch the request
    public static function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $requestUri = strtok($_SERVER['REQUEST_URI'], '?'); // Remove query parameters

        if (isset(self::$routes[$method][$requestUri])) {
            call_user_func(self::$routes[$method][$requestUri]);
        } else {
            http_response_code(404);
            echo "404 Not Found";
        }
    }
}
