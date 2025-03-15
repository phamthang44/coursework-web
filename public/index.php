<?php
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . "/../config/database.php";
// // public/index.php
use utils\Router;
use routes\WebRoutes;

session_start();
$router = new Router();

WebRoutes::register($router); // Register routes
// require_once __DIR__ . '/../src/routes/web.php'; // Load routes

$router->dispatch();
