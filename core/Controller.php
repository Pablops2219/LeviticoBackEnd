<?php
class Controller {
    public function model($model) {
        require_once 'models/' . $model . '.php';
        return new $model();
    }

    public function view($view, $data = []): void {
        require_once 'views/' . $view . '.php';
    }
}