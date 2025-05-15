<?php
class View {
    public static function render($template, $data = []): void {
        extract($data);
        include 'views/' . $template . '.php';
    }
}