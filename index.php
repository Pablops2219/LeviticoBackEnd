<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'core/Router.php';
require_once 'core/Controller.php';
require_once 'core/Model.php';
require_once 'core/View.php';

$router = new Router();

$router->dispatch();