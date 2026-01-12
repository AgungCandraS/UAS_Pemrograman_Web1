<?php
/**
 * Router Class
 * Handles URL routing and dispatching to controllers
 */

class Router {
    private $routes = [];
    private $notFound;
    
    /**
     * Add a GET route
     */
    public function get($path, $callback) {
        $this->addRoute('GET', $path, $callback);
    }
    
    /**
     * Add a POST route
     */
    public function post($path, $callback) {
        $this->addRoute('POST', $path, $callback);
    }
    
    /**
     * Add a PUT route
     */
    public function put($path, $callback) {
        $this->addRoute('PUT', $path, $callback);
    }
    
    /**
     * Add a DELETE route
     */
    public function delete($path, $callback) {
        $this->addRoute('DELETE', $path, $callback);
    }
    
    /**
     * Add any method route
     */
    public function any($path, $callback) {
        $this->addRoute('GET|POST|PUT|DELETE', $path, $callback);
    }
    
    /**
     * Add route with middleware
     */
    private function addRoute($method, $path, $callback) {
        $pattern = '#^' . preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $path) . '$#';
        $this->routes[] = [
            'method' => $method,
            'pattern' => $pattern,
            'callback' => $callback,
            'path' => $path
        ];
    }
    
    /**
     * Set 404 handler
     */
    public function notFound($callback) {
        $this->notFound = $callback;
    }
    
    /**
     * Run the router
     */
    public function run() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remove base path if exists
        $basePath = str_replace('/public', '', dirname($_SERVER['SCRIPT_NAME']));
        if ($basePath !== '/' && strpos($requestUri, $basePath) === 0) {
            $requestUri = substr($requestUri, strlen($basePath));
        }
        
        $requestUri = rtrim($requestUri, '/') ?: '/';
        
        foreach ($this->routes as $route) {
            if (preg_match($route['pattern'], $requestUri, $matches) && 
                strpos($route['method'], $requestMethod) !== false) {
                
                // Extract parameters
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                
                // Call the callback
                if (is_callable($route['callback'])) {
                    call_user_func_array($route['callback'], $params);
                } elseif (is_string($route['callback'])) {
                    $this->dispatch($route['callback'], $params);
                }
                return;
            }
        }
        
        // 404 Not Found
        if ($this->notFound) {
            call_user_func($this->notFound);
        } else {
            http_response_code(404);
            echo "404 - Page Not Found";
        }
    }
    
    /**
     * Dispatch to controller@method
     */
    private function dispatch($callback, $params = []) {
        list($controller, $method) = explode('@', $callback);
        
        if (class_exists($controller)) {
            $controllerInstance = new $controller();
            if (method_exists($controllerInstance, $method)) {
                call_user_func_array([$controllerInstance, $method], $params);
            } else {
                throw new Exception("Method {$method} not found in {$controller}");
            }
        } else {
            throw new Exception("Controller {$controller} not found");
        }
    }
}
