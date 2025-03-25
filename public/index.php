<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . "/../config/database.php";

use utils\Router;
use routes\WebRoutes;
use utils\SessionManager;


SessionManager::start(); // Start session

$router = new Router();

WebRoutes::register($router); // Register routes
// require_once __DIR__ . '/../src/routes/web.php'; // Load routes

$router->dispatch();
