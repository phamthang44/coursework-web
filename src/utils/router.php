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
     * @param string $path The route path (e.g., /posts/delete/{id})
     * @param string $controller Controller class
     * @param string $action Method to call in the controller
     */
    public function addRoute($method, $path, $controller, $action)
    {
        $this->routes[$method][$path] = [
            'controller' => $controller,
            'action' => $action,
            'pattern' => $this->createPattern($path)
        ];
    }

    /**
     * Create a regex pattern from the route path
     *
     * @param string $path The route path with placeholders (e.g., /posts/delete/{id})
     * @return string The regex pattern
     */
    private function createPattern($path)
    {
        // Replace {id} with a regex pattern to capture any alphanumeric value
        $pattern = preg_replace('/\{([a-zA-Z0-9_-]+)\}/', '([a-zA-Z0-9_-]+)', $path);
        return '#^' . $pattern . '$#';
    }

    /**
     * Dispatch the request to the appropriate controller and action
     */
    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $this->parseUri();
        // error_log("Method: $method, URI: '$uri'");
        // error_log("Available routes: " . print_r(array_keys($this->routes[$method] ?? []), true));

        foreach ($this->routes[$method] as $path => $route) {
            if (preg_match($route['pattern'], $uri, $matches)) {
                $controllerName = $this->controllerNamespace . '\\' . $route['controller'];
                $action = $route['action'];
                // error_log("Matched Controller Name: $controllerName");

                if (class_exists($controllerName)) {
                    try {
                        $controller = new $controllerName();
                        if (method_exists($controller, $action)) {
                            // Extract parameters (skip the full match at index 0)
                            array_shift($matches);
                            $params = $matches;
                            if (strpos($path, '{firstname-lastname-id}') !== false && !empty($params)) {
                                $parts = explode('-', $params[0]);
                                if (count($parts) === 3) {
                                    call_user_func_array([$controller, $action], $parts);
                                    return;
                                } else {
                                    echo "❌ Error: Invalid format URL!";
                                    return;
                                }
                            }
                            // else {
                            //     call_user_func_array([$controller, $action], $params);
                            // }
                            // Call the action with parameters
                            if (!empty($params)) {
                                call_user_func_array([$controller, $action], $params);
                            } else {
                                $controller->$action();
                            }
                            //call_user_func_array([$controller, $action], $params);
                            return;
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
                return; // Exit after finding a match
            }
        }

        error_log("Route not found for $method $uri");
        $this->notFound();
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

        // Return the base URI with leading slash
        return '/' . $uri;
    }

    /**
     * Handle 404 Not Found
     */
    private function notFound()
    {
        http_response_code(404);
        header('Location: /404');
        exit;
    }
}
