<?php
class Router
{
    private $routes = [];

    public function addRoute($path, $callback, $methods = ['GET'])
    {
        $methods = (array) $methods; // Ensure $methods is always an array
        foreach ($methods as $method) {
            $this->routes[] = [
                'path' => rtrim($path, '/'),
                'callback' => $callback,
                'method' => strtoupper($method),
            ];
        }
    }

    public function dispatch($uri, $requestMethod) {
        $uri = rtrim(parse_url($uri, PHP_URL_PATH), '/');
        $requestMethod = strtoupper($requestMethod);

        foreach ($this->routes as $route) {
            if ($route['path'] === $uri && $route['method'] === $requestMethod) {
                if (is_callable($route['callback'])) {
                    call_user_func($route['callback']);
                } else {
                    require $route['callback'];
                }
                return;
            }
        }

        $this->handleNotFound();
    }

    private function handleNotFound()
    {
        require 'views/404.view.php';
    }
}

// Create a new router instance
$router = new Router();

// Register your routes with the specified mappings
$router->addRoute('/', 'controller/index.php');
$router->addRoute('/about', 'controller/about.php');
// $router->addRoute('/services', 'controller/services.php');
$router->addRoute('/gallery', 'controller/gallery.php');
$router->addRoute('/blog', 'controller/blog.php');
$router->addRoute('/contact', 'controller/contact.php');
$router->addRoute('/whatWeDo', 'controller/whatWeDo.php');

// admin login
$router->addRoute('/login', 'accounts/login.php');
$router->addRoute('/resetPassword', 'accounts/reset/password.php');

// Dispatch the current request
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);