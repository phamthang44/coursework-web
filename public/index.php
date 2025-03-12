<?php
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

require_once __DIR__ . '/../autoload.php';

session_start();
// // public/index.php

require_once __DIR__ . "/../config/database.php";
// // require_once __DIR__ . '/../src/dal/PostDAOI.php';
// // require_once __DIR__ . '/../src/dal/PostDAOImpl.php'; // Bao gồm file PostDAOImpl
// // require_once __DIR__ . '/../src/dal/ModuleDAOI.php';
// // require_once __DIR__ . '/../src/dal/ModuleDAOImpl.php'; // Bao gồm file ModuleDAOImpl
// // require_once __DIR__ . '/../src/models/Post.php';
// // require_once __DIR__ . '/../src/models/Module.php';
// // require_once __DIR__ . '/../src/controllers/PostController.php';
// // require_once __DIR__ . '/../src/dal/PostAssetDAOI.php';
// // require_once __DIR__ . '/../src/dal/PostAssetDAOImpl.php'; // Bao gồm file PostAssetDAOImpl
// // require_once __DIR__ . '/../src/models/PostAsset.php';
// // // Include file controller
require_once __DIR__ . '/../src/views/layouts/header.php';
require_once __DIR__ . '/../src/views/layouts/footer.php';

use controllers\AuthController;
use controllers\PostController;
use controllers\UserController;

$postController = new PostController();
$authController = new AuthController();
$userController = new UserController();
//$controller->index(); // Gọi hàm index

// Get the requested URL path
$request = $_SERVER['REQUEST_URI'];
$request = strtok($request, '?'); // Remove query string

$action = $_GET['action'] ?? 'index';

switch ($action) {
    case 'create':
        $postController->create();
        break;
    case 'store':
        $postController->store();
        break;
    case 'delete':
        $postController->delete($_GET['postId']);
        break;
    case 'update':
        $postController->update($_GET['postId']);
        break;
    case 'edit':
        $postController->edit($_GET['postId']);
        break;
    case 'login':
        $authController->login();
        break;
    case 'logout':
        $authController->logout();
        break;
    case 'signup':
        $userController->signup();
        break;
    default:
        $postController->index();
}

//---------------------------------------- version 1 ------------------------------------------------------

// ini_set('display_errors', 1);
// error_reporting(E_ALL);

// require_once __DIR__ . '/../autoload.php';

// use controllers\AuthController;
// use controllers\PostController;
// use controllers\UserController;
// use utils\SessionManager;

// SessionManager::start(); // Start session safely

// // Get requested URL path and action
// $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// $action = filter_input(INPUT_GET, 'action') ?? 'index';

// // Initialize controllers
// $postController = new PostController();
// $authController = new AuthController();
// $userController = new UserController();

// // Route handling
// switch ($action) {
//     case 'create':
//         $postController->create();
//         break;
//     case 'store':
//         $postController->store();
//         break;
//     case 'delete':
//         $postId = filter_input(INPUT_GET, 'postId', FILTER_VALIDATE_INT);
//         if ($postId) {
//             $postController->delete($postId);
//         } else {
//             echo "Invalid Post ID.";
//         }
//         break;
//     case 'update':
//         $postId = filter_input(INPUT_GET, 'postId', FILTER_VALIDATE_INT);
//         if ($postId) {
//             $postController->update($postId);
//         } else {
//             echo "Invalid Post ID.";
//         }
//         break;
//     case 'edit':
//         $postId = filter_input(INPUT_GET, 'postId', FILTER_VALIDATE_INT);
//         if ($postId) {
//             $postController->edit($postId);
//         } else {
//             echo "Invalid Post ID.";
//         }
//         break;
//     case 'login':
//         $authController->login();
//         break;
//     case 'logout':
//         $authController->logout();
//         break;
//     case 'signup':
//         $userController->signup();
//         break;
//     default:
//         $postController->index(); // Default action
// }
//---------------------------------------------------------- version 2 ----------------------------------------------------------------
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

// require_once __DIR__ . '/../autoload.php';
// require_once __DIR__ . '/../config/database.php';
// require_once __DIR__ . '/../src/routes/web.php';  // Load all web routes


// session_start();

// // Dispatch the router
// use utils\Router;

// Router::dispatch();


//------------------------------------------------------version 3------------------------------------------------------------------
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

// require_once __DIR__ . '/../autoload.php';
// require_once __DIR__ . '/../config/database.php';
// require_once __DIR__ . '/../src/routes/web.php'; // Load routes

// use controllers\AuthController;
// use controllers\PostController;
// use controllers\UserController;

// session_start();

// $authController = new AuthController();
// $postController = new PostController();
// $userController = new UserController();

// // Get the requested URL
// $requestUri = $_GET['url'] ?? 'home'; // Default page

// // Define routes
// $routes = [
//     '' => fn() => $postController->index(), // Homepage
//     'posts' => fn() => $postController->index(),
//     'posts/create' => fn() => $postController->create(),
//     'posts/store' => fn() => $postController->store(),
//     'posts/edit' => fn() => $postController->edit($_GET['postId'] ?? null),
//     'posts/update' => fn() => $postController->update($_GET['postId'] ?? null),
//     'posts/delete' => fn() => $postController->delete($_GET['postId'] ?? null),
//     'login' => fn() => $authController->login(),
//     'logout' => fn() => $authController->logout(),
//     'signup' => fn() => $userController->signup(),
// ];

// // Handle routing
// if (array_key_exists($requestUri, $routes)) {
//     $routes[$requestUri]();
// } else {
//     http_response_code(404);
//     echo "404 Not Found";
// }
