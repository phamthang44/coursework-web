<?php

namespace utils;

class Router
{
    private $routes = [];
    private $controllerNamespace = 'controllers';

    /**
     * Add a route to the routing table
     *
     * @param string $method HTTP method (GET, POST, etc.)
     * @param string $path The route path
     * @param string $controller Controller class
     * @param string $action Method to call in the controller
     */
    public function addRoute($method, $path, $controller, $action)
    {
        $this->routes[$method][$path] = [
            'controller' => $controller,
            'action' => $action
        ];
    }

    /**
     * Dispatch the request to the appropriate controller and action
     */
    public function dispatch()
    {
        // Get the current request method and URI
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $this->parseUri();
        // var_dump("Method: $method, URI: $uri"); // Debug
        // var_dump($this->routes); // Debug
        error_log("Method: $method, URI: '$uri'");
        error_log("Available routes: " . print_r(array_keys($this->routes[$method] ?? []), true));
        // Check if the route exists
        if (isset($this->routes[$method][$uri])) {
            $route = $this->routes[$method][$uri];
            $controllerName = $this->controllerNamespace . '\\' . $route['controller'];
            $action = $route['action'];
            // var_dump("Controller Name: $controllerName"); // Debug

            if (class_exists($controllerName)) {
                try {
                    $controller = new $controllerName();
                    if (method_exists($controller, $action)) {
                        $controller->$action();
                    } else {
                        error_log("Action not found: $action in $controllerName");
                        $this->notFound();
                    }
                } catch (\Exception $e) {
                    error_log("Failed to instantiate controller $controllerName: " . $e->getMessage());
                    $this->notFound();
                }
            } else {
                error_log("Class not found: $controllerName");
                $this->notFound();
            }
        } else {
            error_log("Route not found for $method $uri");
            $this->notFound();
        }
    }

    /**
     * Parse the URI from the request
     *
     * @return string The cleaned-up URI
     */
    private function parseUri()
    {
        $uri = $_SERVER['REQUEST_URI'];

        // Remove query string and base path if any
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = trim($uri, '/');

        // Return the base URI or empty string if it's the root
        return '/' . $uri;
    }

    /**
     * Handle 404 Not Found
     */
    private function notFound()
    {
        http_response_code(404);
        echo "404 - Page Not Found";
        exit;
    }
}
