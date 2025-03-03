<?php
header('Content-Type: application/json');
require '../core/Router.php';
require '../core/Controller.php';
require '../core/Database.php';
require '../core/Model.php';

$router = new Router();
require '../config/routes.php';

$url = $_GET['url'] ?? '/';
$router->dispatch($url, $_SERVER['REQUEST_METHOD']);
