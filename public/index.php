<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../autoload.php';

session_start();
// public/index.php

require_once __DIR__ . "/../config/database.php";
// require_once __DIR__ . '/../src/dal/PostDAOI.php';
// require_once __DIR__ . '/../src/dal/PostDAOImpl.php'; // Bao gồm file PostDAOImpl
// require_once __DIR__ . '/../src/dal/ModuleDAOI.php';
// require_once __DIR__ . '/../src/dal/ModuleDAOImpl.php'; // Bao gồm file ModuleDAOImpl
// require_once __DIR__ . '/../src/models/Post.php';
// require_once __DIR__ . '/../src/models/Module.php';
// require_once __DIR__ . '/../src/controllers/PostController.php';
// require_once __DIR__ . '/../src/dal/PostAssetDAOI.php';
// require_once __DIR__ . '/../src/dal/PostAssetDAOImpl.php'; // Bao gồm file PostAssetDAOImpl
// require_once __DIR__ . '/../src/models/PostAsset.php';
// // Include file controller
require_once __DIR__ . '/../src/views/layouts/header.php';
require_once __DIR__ . '/../src/views/layouts/footer.php';

use controllers\AuthController;
use controllers\PostController;

$postController = new PostController();
$authController = new AuthController();
//$controller->index(); // Gọi hàm index



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
    default:
        $postController->index();
}
