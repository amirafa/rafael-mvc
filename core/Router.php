<?php

class Router {
    private $routes = [];

    public function get($path, $handler, $middleware = []) {
        $this->addRoute('GET', $path, $handler, $middleware);
    }

    public function post($path, $handler, $middleware = []) {
        $this->addRoute('POST', $path, $handler, $middleware);
    }

    public function put($path, $handler, $middleware = []) {
        $this->addRoute('PUT', $path, $handler, $middleware);
    }

    public function delete($path, $handler, $middleware = []) {
        $this->addRoute('DELETE', $path, $handler, $middleware);
    }

    private function addRoute($method, $path, $handler, $middleware = []) {
        $this->routes[] = compact('method', 'path', 'handler', 'middleware');
    }

    public function dispatch($url, $method) {
        $url = trim($url, '/');

        foreach ($this->routes as $route) {
            if ($this->matchRoute($route['path'], $url, $params) && $route['method'] === $method) {
                foreach ((array)$route['middleware'] as $middleware) {
                    require_once "../core/middleware/{$middleware}.php";
                    $middleware::handle();
                }
                return $this->callHandler($route['handler'], $params);
            }
        }

        http_response_code(404);
        echo json_encode(['message' => 'Route not found']);
    }

    private function matchRoute($routePath, $url, &$params) {
        $params = [];
        $routeParts = explode('/', trim($routePath, '/'));
        $urlParts = explode('/', $url);

        if (count($routeParts) !== count($urlParts)) return false;

        foreach ($routeParts as $index => $part) {
            if (preg_match('/^\{(.+?)\}$/', $part, $matches)) {
                $params[$matches[1]] = $urlParts[$index];
            } elseif ($part !== $urlParts[$index]) {
                return false;
            }
        }
        return true;
    }

    private function callHandler($handler, $params) {
        list($controllerName, $method) = explode('@', $handler);
        require_once "../app/controllers/{$controllerName}.php";
        $controller = new $controllerName();
        call_user_func_array([$controller, $method], $params);
    }
}
