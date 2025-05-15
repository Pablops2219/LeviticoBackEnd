<?php

class Router {
    private $controller = 'Api';
    private $method = 'index';
    private $params = [];

    public function dispatch(): void {
        $url = $this->parseUrl();

        if(isset($url[0])) {
            $potentialController = ucfirst($url[0]) . 'Controller';
            $controllerFile = 'controllers/' . $potentialController . '.php';

            if(file_exists($controllerFile)) {
                $this->controller = $potentialController;
                unset($url[0]);
            }
        }

        require_once 'controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller();

        if(isset($url[1])) {
            if(method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    private function parseUrl(): array {
        if(isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}